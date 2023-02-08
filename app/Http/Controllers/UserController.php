<?php

namespace App\Http\Controllers;

use App\Exceptions\GeneralJsonException;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()->paginate($request->page_size ?? 20);

        return UserResource::collection($users);
    }

    public function create(Request $request, UserRepository $userRepository)
    {
        $created = $userRepository->create($request->only([
            'name', 'email'
        ]));

        // Bazada create qilishni tekshirib o'tirmaymiz.
        // Chunki bu repositoryda qilingan

        return new UserResource($created);
    }

    public function update(Request $request, User $user, UserRepository $userRepository)
    {
        $updated = $userRepository->update($user, $request->only([
            'name', 'email'
        ]));

        return new UserResource($updated);
    }

    public function forceDelete(User $user, UserRepository $userRepository)
    {
        $deleted = $userRepository->forceDelete($user);

        return new JsonResponse(['data' => 'success']);
    }
}
