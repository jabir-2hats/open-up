<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return bool
     */
    public function authenticate(LoginRequest $request): bool
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            return true;
        }

        return false;
    }

    /**
     * Register a new user instance.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function register(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        Auth::login($user);
        
        return $user;
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request The HTTP request.
     */
    public function logout(Request $request): void
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
