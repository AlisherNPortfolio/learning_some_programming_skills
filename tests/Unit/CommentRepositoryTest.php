<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Repositories\CommentRepository;
use Tests\TestCase;

class CommentRepositoryTest extends TestCase
{
    /**
     * A basic unit test create.
     *
     * @return void
     */
    public function test_create()
    {
        $repository = $this->app->make(CommentRepository::class);

        $payload = [
            'body' => 'Assalomu aleykum. Mening ismim Alisher',
            'user_id' => 1,
            'post_id' => 1
        ];

        $created = $repository->create($payload);

        $this->assertSame($payload['body'], $created->body, 'Comment yaratish testi ishlamadi');
    }

    /**
     * A basic unit test update.
     *
     * @return void
     */
    public function test_update()
    {
        $repository = $this->app->make(CommentRepository::class);

        $dummyData = Comment::factory(1)->create()->first();

        $payload = [
            'body' => 'Salom'
        ];

        $updated = $repository->update($dummyData, $payload);

        $this->assertSame($payload['body'], $updated->body, 'Comment yangilab bo\'lmadi!');
    }

    /**
     * A basic unit test update.
     *
     * @return void
     */
    public function test_delete()
    {
        $repository = $this->app->make(CommentRepository::class);

        $dummyComment = Comment::factory(1)->create()->first();

        $deleted = $repository->forceDelete($$dummyComment);

        $deletedComment = Comment::query()->find($dummyComment->id);

        $this->assertSame(null, $deletedComment, 'Comment o\'chmadi!');
    }
}
