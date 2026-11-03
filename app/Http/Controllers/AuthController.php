<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    /**
     * User registration.
     */
    public function register(Request $request): Response
    {
        return response()->noContent();
    }

    /**
     * User login.
     */
    public function login(Request $request): Response
    {
        return response()->noContent();
    }

    /**
     * User logout.
     */
    public function logout(): Response
    {
        return response()->noContent();
    }
}
