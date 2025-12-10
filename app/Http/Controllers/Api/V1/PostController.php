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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
