<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

    // JUST AUTHENTICATED USERS
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('profile.index', [
            'users' => User::all()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->id !== $user->id && !Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'You are not allowed to access this profile.');
        }

        return view('layouts.profile.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);

        // Verificar se é o próprio usuário ou se é administrador
        if (!(Auth::user()->id === $user->id || Auth::user()->isAdmin)) {
            return redirect()->route('home')->with('error', 'You do not have permission to edit this profile.');
        }

        return view('layouts.profile.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        if (Auth::user()->id !== $user->id && !Auth::user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'You do not have permission to update this profile.');
        }

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'birthdate' => 'required|date',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone_number' => 'required|max:20'
        ]);

        $user->update(array_merge($validatedData, ['image_url' => $this->uploadImage($request->file('image_url'))]));

        return redirect()->route('profile.show', $user->id)->with('success', 'Profile updated successfully!');
    }

    private function uploadImage($imageFile)
    {
        $image = Image::make($imageFile);

        $imagePath = 'users/' . $imageFile->hashName();
        Storage::disk('public')->put($imagePath, (string)$image->encode());

        return $imagePath;
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            abort(404);
        }

        $authUser = Auth::user();

        if ($authUser->isAdmin() || $authUser->id == $user->id) {

            $user->delete();

            if (!$authUser->isAdmin() || $authUser->id == $user->id) {
                Auth::logout();
                return redirect()->route('login')->with('success', 'Account has been deleted.');
            } else {
                return redirect()->route('profile.index')->with('success', 'User account has been deleted.');
            }
        } else {
            return redirect()->route('home')->with('error', 'You do not have permission to delete this account.');
        }
    }

    
    
}
