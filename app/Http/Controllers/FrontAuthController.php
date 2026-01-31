<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FrontAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('candidat')->check()) {
            return redirect()->route('form.dashboard');
        }
        
        return view('livewire.front.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Determine if login is email or username
        $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'login';
        
        $credentials = [
            $loginType => $request->login,
            'password' => $request->password,
        ];

        if (Auth::guard('candidat')->attempt($credentials, $request->filled('remember'))) {
            $candidat = Auth::guard('candidat')->user();

            if ($candidat->is_active) {
                $request->session()->regenerate();
                return redirect()->intended(route('form.dashboard'));
            }

            Auth::guard('candidat')->logout();
            return back()->withErrors([
                'login' => 'Your account is disabled.',
            ])->withInput($request->only('login'));
        }

        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('login'));
    }

    public function showRegister()
    {
        if (Auth::guard('candidat')->check()) {
            return redirect()->route('form.dashboard');
        }
        
        return view('livewire.front.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:candidat,email',
            'password' => 'required|min:6',
        ]);

        $candidat = Candidat::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'], 
            'login' => $validated['email'], 
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        Auth::guard('candidat')->login($candidat);

        return redirect()->route('form.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('candidat')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('user.login');
    }
}