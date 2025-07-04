<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
        $result = $this->authService->authenticate($request);
        if ($result === true) {
            return redirect()->intended('dashboard');
        }
        return back()->withErrors([
            'email' => $result ?? 'The provided credentials do not match our records.',
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        if ($user) {
            return redirect()->intended('dashboard');
        }
        return back()->withErrors([
            'email' => 'Registration failed. Please try again.',
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);
        return redirect('/login');
    }
}
