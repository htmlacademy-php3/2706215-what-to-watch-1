<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Responses\SuccessResponse;
use App\Http\Responses\ValidationResponse;
use App\Models\User;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        $data = $request->validated();
        $user = User::query()->where('email', '=', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return new ValidationResponse(
                ['password' => ['Неверный email или пароль.']]
            );
        }

        $token = $user->createToken('auth-token');

        return new SuccessResponse([
            'token' => $token->plainTextToken,
        ]);
    }

    /**
     * User logout.
     */
    public function logout(): Responsable
    {
        Auth::user()->tokens()->delete();

        return new SuccessResponse([]);
    }
}
