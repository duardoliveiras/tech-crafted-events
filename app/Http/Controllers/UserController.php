<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        if ($user && Auth::user()->id === $user->id) {
            return view('layouts.profile.show', ['user' => $user]);
        } else {
            return redirect()->route('home')->with('error', 'Você não tem permissão para acessar este perfil.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            // In case of profile not found
            abort(404);
        }

        if (Auth::user()->id !== $user->id) {
            // Prevent users from editing other users' profiles
            return redirect()->route('home')->with('error', 'You do not have permission to edit.');
        }

        return view('layouts.profile.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            // Profile not found
            abort(404);
        }

        if (Auth::user()->id !== $user->id) {
            // Prevent users from updating other users' profiles
            return redirect()->route('home')->with('error', 'You do not have permission to edit');
        }

        // Data validation
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'birthdate' => 'required|date',
            // Note: The unique validation rule should reference the correct table
            'email' => 'required|email|max:255|unique:users,email,'.$id,
            'phone_number' => 'required|max:20'
        ]);

        // Update profile
        $user->update($validatedData);

        // Redirect to the profile show
        // Note: Make sure the route name is correct. It should not include the view path.
        return redirect()->route('profile.show', $user->id)->with('success', 'Profile updated sucessfully!');
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

        // User deleted
        $user->isDeleted = true;
        $user->save();

        //Logout
        Auth::logout();

        return redirect()->route('login')->with('success', 'Account has been deleted.');
    }

}
