<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EmailVerification;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function register($data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'email_verification_token' => Str::random(60)
        ]);

        // Send verification email
        // Mail::to($user->email)->send(new EmailVerification($user));
        Mail::to($user->email)->queue(new EmailVerification($user));

        return $user;
    }

    public function login($email, $password)
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return false;
        }

        if (!$user->isEmailVerified()) {
            return 'email_not_verified';
        }

        session()->regenerate();

        // session(['user_id' => $user->id]);
        Auth::login($user);

        return $user;
    }

    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return false;
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null
        ]);

        return $user;
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
    }

    public function user()
    {
        return Auth::user();
    }
}