<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\City;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Country;
use App\Models\Category;
use Illuminate\View\View;
use App\Models\Discussion;
use App\Models\University;
use Illuminate\Http\Request;
use App\Models\EventOrganizer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Events\NotificationReceived;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EventController extends Controller
{
    const NOMINATIM_API_URL = "https://nominatim.openstreetmap.org/reverse?format=json";
    protected StripeController $stripeController;

    private $validationRules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'start_date' => 'required|date_format:Y-m-d\TH:i',
        'category_id' => 'required|exists:category,id',
        'end_date' => 'required|date_format:Y-m-d\TH:i|after_or_equal:start_date',
        'start_tickets_qty' => 'required|integer|min:1',
        'current_price' => 'required|numeric',
        'address' => 'required|string|max:255',
        'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    public function __construct(StripeController $stripeController)
    {
        $this->middleware('auth')->except(['index', 'show']);
        $this->stripeController = $stripeController;
    }

    public function index(Request $request)
    {
        $categories = Category::all();
        $locations = City::all();
        $universities = University::all();

        $eventType = $request->query('event-type');
        $location = $request->query('location');
        $dateFilter = $request->query('date-filter');
        $nameFilter = $request->query('full-text-search');
        $universityFilter = $request->query('university');

        $query = Event::query();

        if ($nameFilter) {
            $query->whereRaw("to_tsvector('english', name) @@ to_tsquery('english', ?)", [$nameFilter])->get();
        }

        if ($eventType) {
            $query->where('category_id', $eventType);
        }

        if ($location) {
            $query->where('city_id', $location);
        }

        if ($universityFilter) {
            $query->whereHas('owner.user.university', function ($q) use ($universityFilter) {
                $q->where('university_id', $universityFilter);
            });
        }

        if ($dateFilter) {
            $query->whereDate('start_date', '=', date('Y-m-d', strtotime($dateFilter)));
        }

        $sort = $request->query('sort');

        switch ($sort) {
            case 'name':
                $query->orderBy('name');
                break;
            case 'price-lowest':
                $query->orderBy('current_price', 'asc');
                break;
            case 'price-greater':
                $query->orderBy('current_price', 'desc');
                break;
            default:
                $query->orderBy('start_date');
                break;
        }

        $query->whereIn('status', ['ONGOING', 'UPCOMING']);

        $events = $query->paginate(6);

        if ($request->ajax() || $request->query('ajax')) {
            // Check if the events collection is empty
            return $events->isEmpty() ? response()->json(['html' => ''])
                : response()->json(['html' => view('partials.event', compact('events'))->render()]);

        }

        return view('layouts.event.list', compact('events', 'universities', 'categories', 'locations'));
    }

    public function getCityCode($lat, $lon)
    {
        $response = Http::get(self::NOMINATIM_API_URL . "&lat={$lat}&lon={$lon}");
        $cityCode = '';

        if ($response->successful()) {
            $data = $response->json();
            [$city, $state, $country, $countryCode] = $this->processAddress($data['address']);

            $existingCountry = Country::firstOrCreate(
                ['initials' => strtoupper($countryCode)],
                ['name' => $country, 'initials' => strtoupper($countryCode)]
            );

            $existingState = $existingCountry->states()->firstOrCreate(['name' => $state]);

            $existingCity = $existingState->cities()->firstOrCreate(['name' => $city]);

            $cityCode = $existingCity->id;
        }

        return $cityCode;
    }

    private function processAddress($address)
    {
        $city = $address['city'] ?? 'default';
        $state = $address['state'] ?? $address['county'] ?? 'default';
        $country = $address['country'] ?? 'default';
        $countryCode = $address['country_code'] ?? 'default';

        return [$city, $state, $country, $countryCode];
    }

    public function show($id): View
    {
        [$event, $userHasTicket] = $this->fetchEventAndTicketStatus($id);

        return view('layouts.event.details', compact('event', 'userHasTicket'));
    }

    public function showJson($id): JsonResponse
    {
        [$event, $userHasTicket] = $this->fetchEventAndTicketStatus($id);

        return response()->json(['event' => $event, 'userHasTicket' => $userHasTicket]);
    }

    private function fetchEventAndTicketStatus($id)
    {
        if (!$this->isValidUuid($id)) {
            abort(404, 'Not found');
        }

        try {
            $event = Event::with('ticket')->findOrFail($id);
            $userHasTicket = Auth::check() && $event->ticket->contains('user_id', Auth::id());

            return [$event, $userHasTicket];
        } catch (ModelNotFoundException $e) {
            abort(404, 'Not found');
        } catch (\Exception $e) {
            // Handle other exceptions if necessary
            abort(500, 'Server Error');
        }
    }

    public function byPassTicketShow($eventId, $ticketId): View
    {
        if (!$this->isValidUuid($ticketId) || !$this->isValidUuid($eventId)) {
            abort(404);
        }

        try {
            $ticket = $this->findTicketById($ticketId);

            $ticket->markTicketAsPaid();

            return self::show($eventId);
        } catch (ModelNotFoundException $e) {
            abort(404);
        }
    }

    public function create()
    {
        $categories = Category::all();
        $eventOrganizer = EventOrganizer::where('user_id', Auth::id())->first();
        $hasLegalId = $eventOrganizer && !is_null($eventOrganizer->legal_id);

        if (!$hasLegalId) {
            // needs to create event organizer account first
            return redirect()->route('event-organizer.show');
        }

        return view('layouts.event.create', compact('categories', 'hasLegalId'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $validatedData = $this->validateEventData($request);
            $event = $this->storeEventData($validatedData, $request);
            return redirect()->route('events.index')->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['msg' => 'Error saving the event: ' . $e->getMessage()]);
        }
    }

    public function storeJson(Request $request): JsonResponse
    {
        try {
            $validatedData = $this->validateEventData($request);
            $event = $this->storeEventData($validatedData, $request);
            return response()->json(['success' => 'Event created successfully.', 'event' => $event]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error saving the event: ' . $e->getMessage()], 422);
        }
    }

    private function validateEventData(Request $request): array
    {
        $validatedData = $request->validate($this->validationRules);

        $startDate = Carbon::parse($validatedData['start_date']);
        $endDate = Carbon::parse($validatedData['end_date']);
        if ($endDate->diffInDays($startDate) > 5) {
            throw new \Exception('The event cannot be longer than 5 days.');
        }

        return $request->all();
    }

    private function storeEventData($validatedData, Request $request)
    {
        $cityCode = $this->getCityCode($validatedData['lat'], $validatedData['lon']);

        $eventOrganizer = EventOrganizer::firstOrCreate(
            ['user_id' => Auth::id()],
            ['legal_id' => $request->input('legal_id')]
        );

        if (!$eventOrganizer->legal_id) {
            throw new \Exception('A Legal Identifier is mandatory for creating an event.');
        }

        $eventData = array_merge(
            $validatedData,
            [
                'city_id' => $cityCode,
                'owner_id' => $eventOrganizer->id,
                'current_tickets_qty' => $validatedData['start_tickets_qty'],
                'image_url' => $this->uploadImage($request->file('image_url'))
            ]
        );

        return Event::create($eventData);
    }

    private function uploadImage($imageFile): string
    {
        $image = Image::make($imageFile);

        $imagePath = 'events/' . $imageFile->hashName();
        Storage::disk('public')->put($imagePath, (string) $image->encode());

        return $imagePath;
    }

    public function edit($id)
    {
        $event = $this->findEventById($id);

        $this->authorize('update', $event);

        $categories = Category::all();
        $cities = City::all();

        return view('layouts.event.edit', compact('event', 'categories', 'cities'));
    }

    public function update(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $event = $this->updateEventData($request, $id);
            $users = Ticket::where('event_id', $event->id)
                ->pluck('user_id')
                ->toArray();
            event(new NotificationReceived($users));
            return redirect()->route('events.show', $event->id)->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['msg' => $e->getMessage()]);
        }
    }

    public function updateJson(Request $request, $id): JsonResponse
    {
        try {
            $event = $this->updateEventData($request, $id);
            return response()->json(['success' => 'Event updated successfully!', 'event' => $event]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    private function updateEventData(Request $request, $id): Event
    {
        $event = $this->findEventById($id);
        $this->authorize('update', $event);

        $validatedData = $this->validateUpdateData($request);

        if ($request->hasFile('image_url')) {
            $event->image_url = $this->uploadImage($request->file('image_url'));
        }

        $event->update(array_merge($validatedData, ['current_tickets_qty' => $request->input('current_tickets_qty')]));

        return $event;
    }

    private function validateUpdateData(Request $request): array
    {
        $updateValidationRules = array_merge($this->validationRules, [
            'image_url' => 'sometimes|image|max:2048',
        ]);
        unset($updateValidationRules['start_tickets_qty']);

        $validatedData = $request->validate($updateValidationRules);

        $startDate = Carbon::parse($validatedData['start_date']);
        $endDate = Carbon::parse($validatedData['end_date']);
        if ($endDate->diffInDays($startDate) > 5) {
            throw new \Exception('The event cannot be longer than 5 days.');
        }

        return $validatedData;
    }

    public function destroy($id): \Illuminate\Http\RedirectResponse
    {
        if (!$this->isValidUuid($id)) {
            abort(404);
        }

        try {
            $event = $this->findEventById($id);

            $this->authorizeDeletion($event);

            $this->deleteEventWithDiscussion($event);

            return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', 'Error deleting event. Error: ' . $e->getMessage());
        }
    }

    public function destroyJson($id): JsonResponse
    {
        if (!$this->isValidUuid($id)) {
            response()->json(['error' => 'Event not found.'], 404);
        }

        try {
            $event = $this->findEventById($id);

            $this->authorizeDeletion($event);

            $this->deleteEventWithDiscussion($event);

            return response()->json(['success' => 'Event deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting event'], 422);
        }
    }

    private function isValidUuid(string $uuid): bool
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

        return (bool) preg_match($pattern, $uuid);
    }

    private function findEventById($id): Event
    {
        return Event::findOrFail($id);
    }

    private function findTicketById($id): Ticket
    {
        return Ticket::findOrFail($id);
    }

    private function authorizeDeletion($event): void
    {
        $this->authorize('delete', $event);
    }

    private function deleteEventWithDiscussion($event): void
    {
        DB::transaction(function () use ($event) {
            Discussion::where('event_id', $event->id)->delete();
            $this->refundTickets($event);
            $event->status = 'DELETED';
            $event->save();
        });
    }

    public function leave($eventId, $ticketId)
    {
        if (!$this->isValidUuid($eventId) || !$this->isValidUuid($ticketId)) {
            abort(404);
        }

        try {
            $event = $this->findEventById($eventId);

            $this->stripeController->refundPaymentFromUser($event, Auth::id());

            $ticket = $this->findTicketById($ticketId);

            $ticket->markTicketAsCanceled();

            return redirect()->route('events.index')->with('success', 'Leaved from event "' . $event->name . '" successfully.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', 'Error deleting event. Cause: ' . $e->getMessage());
        }
    }

    private function refundTickets(Event $event): void
    {
        $this->stripeController->refundAllPaymentsFromEvent($event);
    }

    public function getUsers($userEmail)
    {
        $users = User::where('email', 'like', '%' . $userEmail . '%')->get();
        return response()->json($users);
    }
}
