<?php

namespace App\Http\Controllers;

use App\Models\University;
use Illuminate\Http\Request;
use App\Models\City;
use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Models\State;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Facades\Image;


class UniversityController extends Controller
{
    const NOMINATIM_API_URL = "https://nominatim.openstreetmap.org/reverse?format=json";

    private $validationRules = [
        'name' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'image_url' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    public function __construct()
    {
        $this->middleware('admin')->except(['index', 'show']);
    }
    public function index(Request $request)
    {
       return;
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layouts.universities.create');
        $this->authorize('create', University::class);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate($this->validationRules);

        $cityCode = $this->getCityCode($request->lat, $request->lon);

        $imagePath = $this->uploadImage($request->file('image_url'));

        $university = University::create([
            'name' => $request->input('name'),
            'address' => $request->input('address'),
            'city_id' => $cityCode,
            'image_url' => $imagePath,
        ]);

        return $university
            ? redirect()->route('admin.dashboard')->with('success', 'University created successfully.')
            : redirect()->back()->withInput()->withErrors(['msg' => 'Error saving the university.']);
    }
    private function uploadImage($imageFile) {
        $image = Image::make($imageFile);
        $imagePath = 'universities/' . $imageFile->hashName();
        Storage::disk('public')->put($imagePath, (string)$image->encode());
        return $imagePath; // This should be a path like "universities/filename.jpg"
    }


    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $university = University::findOrFail($id);

        return view('layouts.universities.details', compact('university'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $university = University::findOrFail($id);

        return view('layouts.universities.edit', compact('university'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $university = University::findOrFail($id);
        $this->authorize('update', $university);

        $updateValidationRules = array_merge($this->validationRules, [
            'image_url' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $request->validate($updateValidationRules);

        if ($request->has('city_name')) {
            $cityCode = $this->getCityCode($request->lat, $request->lon);

            if (!$cityCode) {
                return redirect()->back()->withInput()->withErrors([
                    'city' => 'Unable to determine city from the provided name.'
                ]);
            }

            $university->city_id = $cityCode;
        }

        if ($request->hasFile('image_url')) {
            if (Storage::disk('public')->exists($university->image_url)) {
                Storage::disk('public')->delete($university->image_url);
            }

            $university->image_url = $this->uploadImage($request->file('image_url'));
        }

        // Update other fields
        $university->fill($request->only($university->getFillable()));
        $university->save();
        \Log::info('Updated image URL', ['url' => $university->image_url]);

        return redirect()->route('universities.show', $university->id)
            ->with('success', 'University updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $university = University::findOrFail($id);
        $this->authorize('delete', $university);

        try {
            $university->delete();

            return redirect()->route('admin.dashboard')->with('success', 'University deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Error deleting university');
        }
    }
}
