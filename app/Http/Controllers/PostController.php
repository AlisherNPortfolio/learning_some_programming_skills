<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralJsonException;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\User;
use App\Notifications\PostSharedNotification;
use App\Repositories\PostRepository;
use App\Rules\IntegerArray;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use PDOException;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return ResourceCollection
     */
    public function index(Request $request)
    {
        $page_size = $request->page_size ?? 20;
        $posts = Post::query()->paginate($page_size);

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request, PostRepository $postRepository)
    {
        $payload = $request->only([
            'title', 'body', 'user_ids'
        ]);

        Validator::validate($payload, [
            'title' => 'string|required',
            'body' => ['array', 'required'],
            'user_ids' => [
                'array',
                'required',
                new IntegerArray(),
            ]
        ], [
            'body.required' => 'Please enter a value for body',
            'title.string' => 'Use a string!'
        ], [
            'user_ids' => 'User ID'
        ]);

        $created = $postRepository->create($payload);

        return new PostResource($created);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post, PostRepository $postRepository)
    {
        $post = $postRepository->update($post, $request->only([
            'title', 'body', 'user_ids'
        ]));

        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post, PostRepository $postRepository)
    {
        $post = $postRepository->forceDelete($post);

        return new JsonResponse([
            'data' => 'success'
        ]);
    }

    public function share(Request $request, Post $post)
    {
        $url = URL::temporarySignedRoute('shared.post', now()->addDays(30), [
            'post' => $post->id
        ]);

        $users = User::query()->whereIn('id', $request->user_ids)->get();

        Notification::send($users, new PostSharedNotification($post, $url));

        $user = User::query()->find(1);
        $user->notify(new PostSharedNotification($post, $url));

        return new JsonResponse([
            'data' => $url,
        ]);
    }
}
