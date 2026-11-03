<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    /**
     * List film comments.
     */
    public function index(string $id): Response
    {
        return response()->noContent();
    }

    /**
     * Add a film comment.
     */
    public function store(Request $request, string $id): Response
    {
        return response()->noContent();
    }

    /**
     * Show a comment.
     */
    public function show(string $id): Response
    {
        return response()->noContent();
    }

    /**
     * Update a comment.
     */
    public function update(Request $request, string $comment): Response
    {
        return response()->noContent();
    }

    /**
     * Delete a comment.
     */
    public function destroy(string $comment): Response
    {
        return response()->noContent();
    }
}
