<?php

namespace Tests\Unit;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    /**
     * A basic unit test create.
     *
     * @return void
     */
    public function test_create()
    {
        // 1. test qiladigan obyektimizni olvolamiz
        $repository = $this->app->make(UserRepository::class);

        // 2. Test qilish uchun test ma'lumot yaratamiz
        $payload = [
            'name' => 'Alisher',
            'email' => fake()->email(), // <== email unique bo'lgani uchun
            'password' => Hash::make('alisher123')
        ];

        // 3. Test qiladigan obyektimizga test ma'lumotni beramiz.
        $created = $repository->create($payload);

        // 4. Olingan natijani test ma'lumotimiz bilan solishtirib ko'ramiz
        $this->assertSame($payload['name'], $created->name, 'User name kutilganidek emas!');
    }

    /**
     * A basic unit test update.
     *
     * @return void
     */
    public function test_update()
    {
        $repository = $this->app->make(UserRepository::class);

        $payload = [
            'name' => 'Alisher Nasrullayev'
        ];

        $dummyUser = User::factory(1)->create()[0];

        $updated = $repository->update($dummyUser, $payload);

        $this->assertSame($payload['name'], $updated->name, 'User name yangilanmadi!');
    }

    /**
     * A basic unit test delete.
     *
     * @return void
     */
    public function test_delete()
    {
        $repository = $this->app->make(UserRepository::class);

        $dummyUser = User::factory(1)->create()->first();

        $deleted = $repository->forceDelete($dummyUser);

        $deletedUser = User::query()->find($dummyUser->id);

        $this->assertSame(null, $deletedUser, 'Userni o\'chirib bo\'lmadi!');
    }
}
