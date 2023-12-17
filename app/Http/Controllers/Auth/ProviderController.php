<?php

namespace App\Http\Controllers\Auth;

use App\Models\University;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        return view('register-provider', ['universities' => $universities, 'user' => $user, 'provider' => $provider]);
    }


}
