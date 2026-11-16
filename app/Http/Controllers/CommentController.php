<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * List film comments.
     */
    public function index(string $id): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * Add a film comment.
     */
    public function store(Request $request, string $id): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * Show a comment.
     */
    public function show(string $id): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * Update a comment.
     */
    public function update(Request $request, string $comment): Responsable
    {
        return new SuccessResponse([]);
    }

    /**
     * Delete a comment.
     */
    public function destroy(string $comment): Responsable
    {
        return new SuccessResponse([]);
    }
}
