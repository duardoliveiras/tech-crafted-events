<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\University;
use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        $events = Event::all();
        return view('layouts.admin.dashboard',compact('users', 'events'));
    }
    public function create() {
        $universities = University::all();
        return view('layouts.admin.create', compact('universities'));
    }

    public function store(Request $request) {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'birthdate' => 'required|date',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required|unique:users',
            'university_id' => 'required|exists:university,id',
            'image_url' => 'sometimes|image|max:2048', // Validate if the file is an image and its size
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $imageUrl = '';
        if ($request->hasFile('image_url') && $request->file('image_url')->isValid()) {
            $imageFile = $request->file('image_url');
            $image = Image::make($imageFile);
            $imagePath = 'users/' . $imageFile->hashName();
            Storage::disk('public')->put($imagePath, (string)$image->encode());
            $imageUrl = $imagePath;
        }

        // Create the User record
        $user = User::create([
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'university_id' => $request->university_id,
            'image_url' => $imageUrl,
            // Other fields can be added as necessary
        ]);

        // Now create the Admin record and associate it with the User
        $admin = new Admin;
        // You can set additional properties for the Admin here if necessary
        $user->admin()->save($admin);

        return redirect()->route('admin.dashboard')->with('success', 'Admin created successfully!');
    }
}
