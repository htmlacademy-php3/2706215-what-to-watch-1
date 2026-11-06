<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Show the current user's profile.
     */
    public function show(): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * Update the current user's profile.
     */
    public function update(Request $request): Responsable
    {
        return new SuccessResponse([]);
    }
}
