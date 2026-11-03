<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PromoController extends Controller
{
    /**
     * Show the promo film.
     */
    public function index(): Response
    {
        return response()->noContent();
    }

    /**
     * Set the promo film.
     */
    public function store(Request $request, string $id): Response
    {
        return response()->noContent();
    }
}
