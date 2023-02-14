<?php

namespace App\Repositories;

use App\Events\Post\PostCreated;
use App\Events\Post\PostDeleted;
use App\Exceptions\GeneralJsonException;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {

            $created = Post::query()->create([
                'title' => data_get($attributes, 'title'), // data_get laravelning helper funksiyasi.
                'body' => data_get($attributes, 'body')
            ]);

            throw_if(!$created, GeneralJsonException::class, 'Failed to create');

            event(new PostCreated($created));

            if ($userIds = data_get($attributes, 'user_ids')) {
                $created->users()->sync($userIds); // <== Post bog'langan user-larni user_post_pivot jadvalda saqlaydi
            }

            return $created;
        });
    }

    public function update($post, array $attributes)
    {
        return DB::transaction(function () use ($post, $attributes) {
            $updated = $post->update([
                'title' => data_get($attributes, 'title', $post->title),
                'body' => data_get($attributes, 'body', $post->body)
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update');

            if ($userIds = data_get($attributes, 'user_ids')) {
                $post->users()->sync($userIds);
            }

            return $post;
        });
    }

    public function forceDelete($post)
    {
        return DB::transaction(function () use ($post) {
            $deleted = $post->forceDelete();

            throw_if(!$deleted, GeneralJsonException::class, 'Resource can not be deleted');

            event(new PostDeleted($post));

            return $deleted;
        });
    }
}
