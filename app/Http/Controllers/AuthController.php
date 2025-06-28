<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        
        return redirect()->route('login')
                        ->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            $request->email,
            $request->password
        );

        if ($result === false) {
            return back()->withErrors(['email' => 'Invalid credentials.']);
        }

        if ($result === 'email_not_verified') {
            return back()->withErrors(['email' => 'Please verify your email before logging in.']);
        }

        return redirect()->route('dashboard');
    }

    public function verifyEmail($token)
    {
        $user = $this->authService->verifyEmail($token);

        if (!$user) {
            return redirect()->route('login')
                           ->withErrors(['email' => 'Invalid verification token.']);
        }

        return redirect()->route('login')
                        ->with('success', 'Email verified successfully! You can now log in.');
    }

    public function logout()
    {
        $this->authService->logout();
        return redirect()->route('login');
    }
}
