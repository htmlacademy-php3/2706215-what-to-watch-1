<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Comment;
use App\Models\Film;
use Illuminate\Contracts\Support\Responsable;

class CommentController extends Controller
{
    /**
     * List film comments.
     */
    public function index(string $id): Responsable
    {
        $film = Film::findOrFail($id);
        $comments = $film->comments()->with('user')->latest()->get();

        return new SuccessResponse(new CommentCollection($comments)->resolve());
    }

    /**
     * Add a film comment.
     */
    public function store(StoreCommentRequest $request, string $id): Responsable
    {
        $film = Film::findOrFail($id);
        $comment = $film->comments()->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        $comment->load('user');

        return new SuccessResponse(new CommentResource($comment)->resolve(), code: 201);
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
    public function update(UpdateCommentRequest $request, Comment $comment): Responsable
    {
        $comment->update($request->validated());
        $comment->load('user');

        return new SuccessResponse(new CommentResource($comment)->resolve());
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment): Responsable
    {
        $comment->delete();

        return new SuccessResponse([]);
    }
}
