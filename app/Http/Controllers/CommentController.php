<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;

class CommentController extends Controller
{
    public function index(Post $post)
    {
        return CommentResource::collection($post->comments);
    }

    public function store(CommentRequest $request,Post $post)
    {
        $comment = $post->comments()->create($request->all()+['user_id'=>auth()->user()->id]);

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post,Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Post $post, Comment $comment)
    {
        $this->authorizeForUser(auth()->user(), 'makeAction', [$comment]);

        $comment->update($request->all());

        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post,Comment $comment)
    {
        $this->authorizeForUser(auth()->user(), 'makeAction', [$comment]);

        $comment->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Deleted Successfully',
        ], 200);
    }
}
