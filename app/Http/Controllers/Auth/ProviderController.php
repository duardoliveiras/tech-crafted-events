<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\University;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class ProviderController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $user = Socialite::driver($provider)->user();
        $universities = University::get();
        $login = User::where('email', $user->email)->first();

        if ($login) {
            Auth::login($login);
            if (Auth::user()->is_banned) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Account has been banned.');
            }
            return redirect('/home');
        } else {
            return view('register-provider', ['universities' => $universities, 'user' => $user, 'provider' => $provider]);
        }

    }


}
