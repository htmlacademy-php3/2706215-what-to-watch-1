<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Show the current user's profile.
     */
    public function show(): Response
    {
        return response()->noContent();
    }

    /**
     * Update the current user's profile.
     */
    public function update(Request $request): Response
    {
        return response()->noContent();
    }
}
