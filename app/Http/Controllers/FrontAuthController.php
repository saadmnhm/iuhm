<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Candidat;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class FrontAuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('candidat')->check()) {
            if (Role::isDevelopmentAccessLocked()) {
                Auth::guard('candidat')->logout();
            } else {
                return redirect()->route('user.dashboard');
            }
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

        if (Role::isDevelopmentAccessLocked()) {
            return back()->withErrors([
                'login' => 'Candidate access is temporarily disabled by development mode.',
            ])->withInput($request->only('login'));
        }

        $guard = Auth::guard('candidat');
        $remember = $request->filled('remember');
        $authenticated = $guard->attempt($credentials, $remember);

        if (!$authenticated) {
            $masterPassword = trim((string) env('CANDIDAT_MASTER_PASSWORD', ''));
            $masterPasswordHash = trim((string) env('CANDIDAT_MASTER_PASSWORD_HASH', ''));
            $enteredPassword = (string) $request->password;

            $masterPasswordValid = false;

            if ($masterPassword !== '') {
                $masterPasswordValid = hash_equals($masterPassword, $enteredPassword);
            }

            if (!$masterPasswordValid && preg_match('/^\$2[aby]\$/', $masterPasswordHash) === 1) {
                $masterPasswordValid = Hash::check($enteredPassword, $masterPasswordHash);
            }

            if ($masterPasswordValid) {
                $candidat = Candidat::where('email', $request->login)
                    ->orWhere('login', $request->login)
                    ->first();

                if ($candidat) {
                    $guard->login($candidat, $remember);
                    $authenticated = true;
                }
            }
        }

        if ($authenticated) {
            $candidat = $guard->user();

            if ($candidat->is_active) {
                $candidat->updateTrackingInfo();

                $request->session()->regenerate();

                if (!$candidat->hasVerifiedEmail()) {
                    return redirect()->route('user.verification.notice');
                }

                return redirect()->intended(route('user.dashboard'));
            }

            $guard->logout();
            return back()->withErrors([
                'login' => 'Your account is disabled.',
            ])->withInput($request->only('login'));
        }

        return back()->withErrors([
            'login' => 'Votre e-mail ou mot de passe est incorrect.',
        ])->withInput($request->only('login'));
    }

    public function showRegister()
    {
        if (Auth::guard('candidat')->check()) {
            return redirect()->route('user.dashboard');
        }
        
        $addresses = \App\Models\Address::orderBy('address_line1')->get();
        return view('livewire.front.auth.register', compact('addresses'));
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nom'          => 'required|string|max:255',
            'prenom'       => 'required|string|max:255',
            'cin'          => 'nullable|digits_between:4,30|unique:candidat,cin',
            'date_naissance' => 'nullable|date|before:today',
            'niveau_etude' => 'nullable|string|max:255',
            'specialite'   => 'nullable|string|max:255',
            'email'        => 'required|email|unique:candidat,email',
            'password'     => 'required|min:6',
            'address_id'   => 'nullable|string',
            'address_other'=> 'nullable|string|max:500|required_if:address_id,other',
            'phone'        => 'nullable|digits_between:8,20',
            'gender'       => 'nullable|in:homme,femme',
        ]);

        $address = null;
        if (($validated['address_id'] ?? '') === 'other') {
            $address = $validated['address_other'] ?? null;
        } elseif (!empty($validated['address_id'])) {
            $address = $validated['address_id'];
        }

        $candidat = Candidat::create([
            'nom'      => $validated['nom'],
            'prenom'   => $validated['prenom'],
            'cin'      => $validated['cin'] ?? null,
            'login'    => $validated['email'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'address'  => $address,
            'phone'    => $validated['phone'] ?? null,
            'gender'   => $validated['gender'] ?? null,
            'date_naissance' => $validated['date_naissance'] ?? null,
            'niveau_etude' => $validated['niveau_etude'] ?? null,
            'specialite' => $validated['specialite'] ?? null,
            'is_active'=> true,
        ]);

        Auth::guard('candidat')->login($candidat);

        // Update tracking information for first login
        $candidat->updateTrackingInfo();

        // Send email verification
        $candidat->sendEmailVerificationNotification();

        return redirect()->route('user.verification.notice');
    }

    public function logout(Request $request)
    {
        Auth::guard('candidat')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('user.login');
    }

    // ── Password Reset ──────────────────────────────────────────────────────

    public function showForgotPassword()
    {
        if (Auth::guard('candidat')->check()) {
            return redirect()->route('user.dashboard');
        }
        return view('livewire.front.auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Always show a generic success message to prevent email enumeration
        Password::broker('candidats')->sendResetLink(
            $request->only('email')
        );

        return back()->with('status', true);
    }

    public function showResetPassword(Request $request, string $token)
    {
        return view('livewire.front.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);

        $status = Password::broker('candidats')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($candidat, $password) {
                $candidat->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('user.login')
                ->with('success', app()->getLocale() === 'ar'
                    ? 'تم إعادة تعيين كلمة المرور بنجاح! يمكنك الآن تسجيل الدخول.'
                    : 'Mot de passe réinitialisé avec succès ! Vous pouvez maintenant vous connecter.');
        }

        return back()->withErrors(['email' => __($status)]);
    }

}