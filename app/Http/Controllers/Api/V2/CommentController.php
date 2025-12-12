<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\MasterApiController;
use App\Http\Requests\Api\V2\StoreCommentRequest;
use App\Http\Requests\Api\V2\UpdateCommentRequest;
use App\Http\Resources\Api\V2\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Response;

class CommentController extends MasterApiController
{
    /**
     * Display a listing of comments for a post
     */
    public function index(Post $post)
    {
        $comments = $post->comments()
            ->with('user:id,name')
            ->latest();

        $commentCollection = CommentResource::collection($comments->paginate(10)->withQueryString());

        return $this->successResponse(
            $commentCollection->response()->getData(true),
            'Post comments retrieved successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * Store a newly created comment for a post
     */
    public function store(StoreCommentRequest $request, Post $post)
    {
        $comment = $post->comments()->create([
            'comment' => $request->comment,
            'user_id' => auth()->id(),
        ]);

        return $this->successResponse(
            CommentResource::make($comment),
            'Comment created successfully.',
            201
        );
    }

    /**
     * Update a comment
     *
     * @return void
     */
    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        // Optional: Check if user owns the comment
        if ($comment->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            return $this->errorResponse(
                'You do not have permission to update this comment.',
                Response::HTTP_FORBIDDEN
            );
        }

        $comment->update([
            'comment' => $request->comment,
        ]);

        return $this->successResponse(
            CommentResource::make($comment),
            'Comment updated successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * delete a comment
     *
     * @return void
     */
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            return $this->errorResponse(
                'You do not have permission to delete this comment.',
                Response::HTTP_FORBIDDEN
            );
        }

        $comment->delete();

        return $this->successResponse(
            null,
            'Comment deleted successfully.',
            Response::HTTP_NO_CONTENT
        );
    }
}
