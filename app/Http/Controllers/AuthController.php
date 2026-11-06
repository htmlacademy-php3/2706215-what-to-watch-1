<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

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
    public function login(Request $request): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * User logout.
     */
    public function logout(): Responsable
    {
        return new SuccessResponse([]);
    }
}
