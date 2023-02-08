<?php

namespace App\Repositories;

use App\Events\User\UserCreated;
use App\Exceptions\GeneralJsonException;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = User::query()->create([
                'name' => data_get($attributes, 'name'),
                'email' => data_get($attributes, 'email')
            ]);

            throw_if(!$created, GeneralJsonException::class, 'Failed to create user');

            event(new UserCreated($created));

            return $created;
        });
    }

    public function update($user, array $attributes)
    {
        return DB::transaction(function () use ($user, $attributes) {
            $updated = $user->update([
                'name' => data_get($attributes, 'name', $user->name),
                'email' => data_get($attributes, 'email', $user->email),
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update user');

            return $updated;
        });
    }

    public function forceDelete($user)
    {
        return DB::transaction(function () use ($user) {
            $deleted = $user->forceDelete();

            throw_if(!$deleted, GeneralJsonException::class, 'Failed to delete user');

            return $deleted;
        });
    }
}
