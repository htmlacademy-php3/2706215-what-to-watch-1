<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    /**
     * Show the promo film.
     */
    public function index(): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * Set the promo film.
     */
    public function store(Request $request, string $id): Responsable
    {
        return new SuccessResponse([]);
    }
}
