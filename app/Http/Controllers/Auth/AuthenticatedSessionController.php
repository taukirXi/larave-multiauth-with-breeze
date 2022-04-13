<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        //     $request->authenticate();

        //     $request->session()->regenerate();

        //     return redirect()->intended(RouteServiceProvider::HOME);


        $request->authenticate();

        $request->session()->regenerate();
        $data = $request->validate([
            'email' => 'required| email',
            'password' => 'required',
        ]);

        if (auth()->attempt(array('email' => $data['email'], 'password' => $data['password']))) {
            if (auth()->user()->is_admin == 1) {
                return redirect()->route('admin.home');
            } else {
                return redirect()->route('dashboard');
            }
        } else {
            return redirect()->route('login');
        }
    }




    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
