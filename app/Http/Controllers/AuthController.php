<?php

namespace App\Http\Controllers;

use App\Requests\RegisterFormRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            // Contas bloqueadas nao podem manter sessao ativa.
            if ($user->blocked) {
                Auth::logout();

                return back()->withErrors(['email' => 'Your account has been suspended. Please contact support.']);
            }

            $request->session()->regenerate();

            // O primeiro login dispara o envio da verificacao quando necessario.
            if (! $user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();

                return redirect()->route('verification.notice');
            }

            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('pages.auth.register');
    }

    public function register(RegisterFormRequest $request)
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'user_type' => 'C',
                'gender' => $validated['gender'],
                'blocked' => false,
            ]);

            Customer::create([
                'id' => $user->id,
                'nif' => $validated['nif'] ?? null,
                'address' => $validated['address'] ?? null,
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('verification.notice');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
