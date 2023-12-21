<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected function redirectPath()
    {
        if (auth()->user()) {
            return route('admin.dashboard');
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/home';
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     */
    protected function credentials(Request $request)
    {

        return array_merge(
            $request->only($this->username(), 'password'),
            ['is_deleted' => false, 'is_banned' => false] // Garante que apenas usuários não deletados possam fazer login
        );
    }

    /**
     * Handle user logout.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
