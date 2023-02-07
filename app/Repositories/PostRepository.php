<?php

namespace App\Repositories;

use App\Models\Post;
use Exception;
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

            $created->users()->sync(data_get($attributes, 'user_ids')); // <== Post bog'langan user-larni user_post_pivot jadvalda saqlaydi

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

            if (!$updated) {
                throw new Exception('Failed to update');
            }

            if ($userIds = data_get($attributes, 'user_ids')) {
                $post->users()->sync($userIds);
            }

            return $updated;
        });
    }

    public function forceDelete($post)
    {
        return DB::transaction(function () use ($post) {
            $deleted = $post->forceDelete();

            if (!$deleted) {
                throw new Exception("Resource can not be deleted");
            }

            return $deleted;
        });
    }
}
