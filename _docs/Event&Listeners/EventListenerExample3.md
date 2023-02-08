# API serverda Eventni ishlatish

Yangi user tizimdan ro'yxatdan o'tganda uning pochtasiga xabar jo'natishni ko'raylik (bundan tashqari update va delete metodlari uchun ham eventlar yaratib ko'ramiz). Nazariy jihatlarini boshqa markdown fayllarda ko'rganmiz. Shuning uchun buni tushuntirib o'tirmasdan, darhol amaliyotga o'tamiz.

# User ro'yxatdan o'tganda unga email yuborish

Avval, user uchun `create`, `update` va `delete` funksionalliklarini yozib chiqamiz.

1. `app/Repositories` papkasida `UserRepository.php` faylini yaratib unga kod yozamiz:

```php
<?php

namespace App\Repositories;

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

```

2. Uni `UserController`da ishlatamiz:

```php
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

```

3. Endi, user yaratilgan paytda ishga tushiriladigan event-ni generatsiya qilamiz: `php artisan make:event UserCreated`
   Odatda, eventlar `app/Events` papkasiga tushadi. Lekin, event fayllar soni ko'payib ketib, fayllarni qidirish qiyin bo'lmasligi uchun, hozir yaratayotgan eventlarimizni `app/Events/User` papkasiga o'tkazib qo'yamiz:

```php
<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}

```

4. Yaratilgan eventimizni `UserRepository`ning `create` metodida ishga tushiramiz:

```php
//...
    public function create(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $created = User::query()->create([
                'name' => data_get($attributes, 'name'),
                'email' => data_get($attributes, 'email')
            ]);

            throw_if(!$created, GeneralJsonException::class, 'Failed to create user');

            event(new UserCreated($created)); // <== event shu yerda ishga tushirilyapti

            return $created;
        });
    }
//...
```

5. Keyin, event ishga tushishini kutib, uni kuzatib turadigan `listener` klasini generatsiya qilamiz. Bu klas event ishga tushgan paytda o'ziga berilgan vazifani bajaradi. Misol uchun, bizning holatda, `UserCreated` eventi ishga tushganda, yangi listener klas userning pochtasiga bu haqida email yuboradi.
   Listenerni generatsiya qilish: `php artisan make:listener SnedWelcomeEmail`
   Xuddi eventdagiday, bu faylni ham o'ziga mos bo'lgan `app/Listeners/User` papkasiga joylashtiramiz:

```php
<?php

namespace App\Listeners;

use App\Events\User\UserCreated;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        // User pochtasiga xabar yuborish kodi...
        dump("You ($event->user->email) have created an account in http://learn.loc");
    }
}

```

6. `SendWelcomeEmail` listener obyekti `UserCreated` event obyektini kuzatib turishi uchun ularni `EventServiceProvider` klasida bir biriga bog'lab qo'yamiz:

```php
//...
    protected $listen = [ // event listener-larni alohida bir biriga biriktirish uchun
      //...

        UserCreated::class => [
            SendWelcomeEmail::class
        ],
       //...
    ];
//...
```

7. Qolganlari (update va delete) uchun ham xuddi shu ko'rinishda event/listener yozib chiqiladi.
