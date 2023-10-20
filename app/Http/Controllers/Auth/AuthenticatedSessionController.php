<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // $request->authenticate();
        // $request->session()->regenerate();
        // dd($request);
        // return redirect()->intended(RouteServiceProvider::HOME);

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        if (Auth::attempt($data, $request->remember)) {
            $request->session()->regenerate();
            $user = Auth::guard('web')->user()->role_id;
            if ($user != 1) {
                return redirect()->route('dashboard');
            } else {
                return redirect()->route('dashboard.admin');
            }
        } else {
            return redirect()->route('login')->with('failed', 'Email atau Password salah');
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
