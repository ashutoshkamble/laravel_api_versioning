<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\MasterApiController;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\Api\V1\PostResource;
use App\Models\Post;
use Symfony\Component\HttpFoundation\Response;

class PostController extends MasterApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (auth()->user()->cannot('viewAny', Post::class)) {
            return $this->errorResponse(
                'You do not have permission to view posts.',
                Response::HTTP_FORBIDDEN
            );
        }

        $postCollection = PostResource::collection(Post::paginate(10));

        return $this->successResponse(
            // larevel pagination data
            $postCollection->response()->getData(true),
            'Posts retrieved successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        if (auth()->user()->cannot('create', Post::class)) {
            return $this->errorResponse(
                'You do not have permission to create posts.',
                Response::HTTP_FORBIDDEN
            );
        }

        $post = Post::create(array_merge(
            $request->validated(),
            ['created_by' => auth()->id()]
        ));

        return $this->successResponse(
            new PostResource($post),
            'Post created successfully.',
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        if (auth()->user()->cannot('view', $post)) {
            return $this->errorResponse(
                'You do not have permission to view posts.',
                Response::HTTP_FORBIDDEN
            );
        }

        return $this->successResponse(
            new PostResource($post),
            'Post retrieved successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        if (auth()->user()->cannot('update', $post)) {
            return $this->errorResponse(
                'You do not have permission to update this post.',
                Response::HTTP_FORBIDDEN
            );
        }

        $post->update($request->validated());

        return $this->successResponse(
            new PostResource($post),
            'Post updated successfully.',
            Response::HTTP_OK
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if (auth()->user()->cannot('delete', $post)) {
            return $this->errorResponse(
                'You do not have permission to delete this post.',
                Response::HTTP_FORBIDDEN
            );
        }

        $post->delete();

        return $this->successResponse(
            null,
            'Post deleted successfully.',
            Response::HTTP_NO_CONTENT
        );
    }
}
