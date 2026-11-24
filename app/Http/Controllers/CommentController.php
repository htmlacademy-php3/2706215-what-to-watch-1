<?php

namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Models\Comment;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
    public function update(Request $request, Comment $comment): Responsable
    {
        // Gate::authorize('update', $comment);

        return new SuccessResponse([]);
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment): Responsable
    {
        // Gate::authorize('delete', $comment);

        return new SuccessResponse([]);
    }
}
