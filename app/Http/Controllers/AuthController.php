<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Traits\ResponseFormatTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Response;

class AuthController extends Controller
{
    use ResponseFormatTrait;
    public function login(): Response
    {
        return inertia('Auth/Login');
    }

    public function postLogin(LoginRequest $request): RedirectResponse
    {
        try {
            $credentials = $request->only('email', 'password');

            //Attempting for auth
            if (Auth::attempt($credentials)) {
                return redirect()->intended(route('dashboard'));
            }
            return back()->withErrors([
                'status'    => 401,
                'message'   => 'Invalid credentials.',
            ]);
        } catch (\Exception $e) {
            return back()->withErrors([
                'status'    => $e->getCode() ? : 500,
                'message'   => $e->getMessage(),
            ]);
        }
    }

    public function logout(Request $request)
    {
        //Log the user out
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
