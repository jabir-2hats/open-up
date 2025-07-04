<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * The authentication service instance.
     *
     * @var AuthService
     */
    protected AuthService $authService;

    /**
     * Create a new AuthController instance.
     *
     * @param AuthService $authService The authentication service.
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Display the login form.
     *
     * @return View
     */
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param LoginRequest $request The login request.
     * @return RedirectResponse
     */
    public function authenticate(LoginRequest $request): RedirectResponse
    {
        $result = $this->authService->authenticate($request);
        if ($result === true) {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => $result ?? 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Display the registration form.
     *
     * @return View
     */
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    /**
     * Handle a registration attempt.
     *
     * @param RegisterRequest $request The registration request.
     * @return RedirectResponse
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = $this->authService->register($request->validated());
        if ($user) {
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Registration failed. Please try again.',
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request The HTTP request.
     * @return RedirectResponse
     */
    public function logout(Request $request): RedirectResponse
    {
        $this->authService->logout($request);

        return redirect('/login');
    }
}