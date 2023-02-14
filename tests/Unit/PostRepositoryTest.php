<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Repositories\PostRepository;
use Tests\TestCase;

class PostRepositoryTest extends TestCase
{
    public function test_create()
    {
        // 1. test qiladigan obyektimizni olvolamiz
        $repository = $this->app->make(PostRepository::class);

        // 2. Test qilish uchun test ma'lumot yaratamiz
        $payload = [
            'title' => 'Salom',
            'body' => []
        ];

        // 3. Test qiladigan obyektimizga test ma'lumotni beramiz.
        $result = $repository->create($payload);

        // 4. Olingan natijani test ma'lumotimiz bilan solishtirib ko'ramiz
        $this->assertSame($payload['title'], $result->title, 'Yaratilgan postda bir xildagi title mavjud emas');
    }

    public function test_update()
    {
        // Maqsad: update metodi yordamida maqolani yangilash

        // env
        $repository = $this->app->make(PostRepository::class);

        $dummyPost = Post::factory(1)->create()[0]; // <== create collection qaytargani uchun

        // source of truth
        $payload = [
            'title' => 'abc123'
        ];

        // natijani solishtirish
        $updated = $repository->update($dummyPost, $payload);
        $this->assertSame($payload['title'], $updated->title, "Yangilangan postda bir xildagi title mavjud emas");
    }

    public function test_delete()
    {
        // Maqsad: forceDelete metodi ishlashini tekshirish

        // env
        $repository = $this->app->make(PostRepository::class);
        $dummy = Post::factory(1)->create()->first();

        // natijani solishtirish
        $deleted = $repository->forceDelete($dummy);

        // o'chirilganini tasdiqlash
        $found = Post::query()->find($dummy->id);

        $this->assertSame(null, $found, 'Post o\'chirilmagan');
    }
}
