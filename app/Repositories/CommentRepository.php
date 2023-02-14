<?php

namespace App\Repositories;

use App\Events\Comment\CommentCreated;
use App\Events\Comment\CommentDeleted;
use App\Events\Comment\CommentUpdated;
use App\Exceptions\GeneralJsonException;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;

class CommentRepository extends BaseRepository
{
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = Comment::query()->create([
                'body' => data_get($attributes, 'body'),
                'user_id' => data_get($attributes, 'user_id'),
                'post_id' => data_get($attributes, 'post_id')
            ]);

            throw_if(!$created, GeneralJsonException::class, 'Failed to create comment.');

            event(new CommentCreated($created));

            return $created;
        });
    }

    public function update($model, array $attributes)
    {
        return DB::transaction(function () use ($model, $attributes) {
            $updated = $model->update([
                'body' => data_get($attributes, 'body', $model->body),
                'user_id' => data_get($attributes, 'user_id', $model->user_id),
                'post_id' => data_get($attributes, 'post_id', $model->post_id)
            ]);

            throw_if(!$updated, GeneralJsonException::class, 'Failed to update comment');
            event(new CommentUpdated($model));

            return $model;
        });
    }

    public function forceDelete($model)
    {
        return DB::transaction(function () use ($model) {
            $deleted = $model->forceDelete();

            throw_if(!$deleted, GeneralJsonException::class, 'Failed to delete comment');
            event(new CommentDeleted($model));

            return $deleted;
        });
    }
}
