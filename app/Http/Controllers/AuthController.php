<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Responses\AuthResponse;
use App\Http\Responses\SuccessResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * User registration.
     */
    public function register(Request $request): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * User login.
     */
    public function login(LoginRequest $request): Responsable
    {
        if (!Auth::attempt($request->validated())) {
            return new AuthResponse(message: 'Неверный email или пароль.');
        }

        $user = Auth::user();

        if ($request->hasSession()) {
            $request->session()->regenerate();
        }

        $token = $user->createToken('auth-token');

        return new SuccessResponse(['token' => $token->plainTextToken]);
    }

    /**
     * User logout.
     */
    public function logout(Request $request): Responsable
    {
        Auth::user()->tokens()->delete();

        if ($request->hasSession()) {
            Auth::guard('web')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return new SuccessResponse([]);
    }
}
