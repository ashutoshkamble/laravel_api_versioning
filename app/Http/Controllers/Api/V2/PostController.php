<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\MasterApiController;
use App\Http\Requests\Api\V2\StorePostRequest;
use App\Http\Requests\Api\V2\UpdatePostRequest;
use App\Http\Resources\Api\V2\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends MasterApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Post::class)) {
            return $this->errorResponse(
                'You do not have permission to view posts.',
                Response::HTTP_FORBIDDEN
            );
        }

        $posts = Post::query();

        $posts->when($request->user()->isEditor(), function ($query) {
            $query->where('created_by', auth()->id());
        });

        $posts->when($request->user()->isViewer(), function ($query) {
            $query->where('status', 'published');
        });

        $posts->when($request->has('search'), function ($query) use ($request) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        });

        $postCollection = PostResource::collection($posts->paginate(10)->withQueryString());

        return $this->successResponse(
            // laravel pagination data
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
