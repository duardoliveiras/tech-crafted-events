<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Discussion;
use App\Models\Event;
use App\Models\EventOrganizer;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;
use Carbon\Carbon;

class EventController extends Controller
{
    const NOMINATIM_API_URL = "https://nominatim.openstreetmap.org/reverse?format=json";

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

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request): View
    {
        $categories = Category::all();
        $locations = City::all();
        $universities = University::all();

        $eventType = $request->query('eventType');
        $location = $request->query('location');
        $dateFilter = $request->query('date-filter');

        $query = Event::query();

        if ($eventType) {
            $query->where('category_id', $eventType);
        }

        if ($location) {
            $query->where('city_id', $location);
        }

        if ($dateFilter) {
            $query->where('start_date', $dateFilter);
        }

        $events = $query->get();

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
        $event = Event::with('ticket')->findOrFail($id);
        $userHasTicket = auth()->check() && $event->ticket->contains('user_id', auth()->id());

        return view('layouts.event.details', compact('event', 'userHasTicket'));
    }

    public function create()
    {
        $categories = Category::all();
        $eventOrganizer = EventOrganizer::where('user_id', Auth::id())->first();
        $hasLegalId = $eventOrganizer && !is_null($eventOrganizer->legal_id);

        return view('layouts.event.create', compact('categories', 'hasLegalId'));
    }

    public function store(Request $request)
    {
        $request->validate($this->validationRules);
        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));
        if ($endDate->diffInDays($startDate) > 5) {
            return redirect()->back()->withInput()->withErrors([
                'end_date' => 'The event cannot be longer than 5 days.'
            ]);
        }

        $cityCode = $this->getCityCode($request->lat, $request->lon);

        $eventOrganizer = EventOrganizer::firstOrCreate(
            ['user_id' => Auth::id()],
            ['legal_id' => $request->input('legal_id')]
        );

        if (!$eventOrganizer->legal_id) {
            return redirect()->back()->withInput()->withErrors([
                'legal_id' => 'A Legal Identifier is mandatory for creating an event.'
            ]);
        }

        $event = Event::create(array_merge(
            $request->all(),
            [
                'city_id' => $cityCode,
                'owner_id' => $eventOrganizer->id,
                'current_tickets_qty' => $request->start_tickets_qty,
                'image_url' => $this->uploadImage($request->file('image_url'))
            ]
        ));

        return $event
            ? redirect()->route('events.index')->with('success', 'Event created successfully.')
            : redirect()->back()->withInput()->withErrors(['msg' => 'Error saving the event.']);
    }

    private function uploadImage($imageFile)
    {
        $image = Image::make($imageFile);

        $image->resize(null, 1050, function ($constraint) {
            $constraint->aspectRatio();
        })->resizeCanvas(1050, 1050, 'center', false, 'ffffff');

        $imagePath = 'events/' . $imageFile->hashName();
        Storage::disk('public')->put($imagePath, (string)$image->encode());

        return $imagePath;
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);

        $this->authorize('update', $event);

        $categories = Category::all();
        $cities = City::all();

        return view('layouts.event.edit', compact('event', 'categories', 'cities'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);

        $this->authorize('update', $event);

        $updateValidationRules = array_merge($this->validationRules, [
            'image_url' => 'sometimes|image|max:2048',
        ]);
        unset($updateValidationRules['start_tickets_qty']);

        $startDate = Carbon::parse($request->input('start_date'));
        $endDate = Carbon::parse($request->input('end_date'));
        if ($endDate->diffInDays($startDate) > 5) {
            return redirect()->back()->withInput()->withErrors([
                'end_date' => 'The event cannot be longer than 5 days.'
            ]);
        }

        if ($request->hasFile('image_url')) {
            $event->image_url = $this->uploadImage($request->file('image_url'));
        }

        $event->update($request->all());

        return redirect()->route('events.show', $event->id)->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);

        $this->authorize('delete', $event);

        try {
            DB::transaction(function () use ($event) {
                Discussion::where('event_id', $event->id)->delete();
                $event->delete();
            });

            return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('events.index')->with('error', 'Error deleting event');
        }
    }
}
