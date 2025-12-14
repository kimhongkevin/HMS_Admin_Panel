<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'admin_email' => ['required', 'email'],
            'admin_password' => ['required', 'string'],
        ]);

        // Find admin user by email and verify their password
        $admin = User::where('email', $request->admin_email)
                     ->where('role', 'admin')
                     ->first();

        if (!$admin) {
            return back()->withErrors([
                'admin_email' => 'No admin account found with this email address.',
            ])->withInput($request->except('password', 'password_confirmation', 'admin_password'));
        }

        if (!Hash::check($request->admin_password, $admin->password)) {
            return back()->withErrors([
                'admin_password' => 'The admin password is incorrect.',
            ])->withInput($request->except('password', 'password_confirmation', 'admin_password'));
        }

        // Create the new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'staff', // Default role for new registrations
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false))->with('success', 'Account created successfully!');
    }
}
