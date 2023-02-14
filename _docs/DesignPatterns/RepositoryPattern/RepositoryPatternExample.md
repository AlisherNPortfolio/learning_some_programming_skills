# Repository Pattern Example

Odatda. Laravelda model bilan controller ichida ishlanadi. Bunday kod yozish kichkina projectlarda muammo keltirib chiqarmasligi mumkin. Lekin, katta projectlarga kelganda, model bilan create, update qilish kabi amallarni project-ning bir qancha joylarida bajarishga to'g'ri keladi. Bunday paytlarda kodlarni hamma joyda copy/paste qilib ishlatish yaxshi emas (chunki bu DRY tamoyiliga mos kelmaydi). Shu sababli ham, bunday holatlar uchun `Repository Pattern`idan foydalanamiz.

# Misol

`PostController`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
    public function store(StorePostRequest $request)
    {
        $created = DB::transaction(function () use ($request) {

            $created = Post::query()->create([
                'title' => $request->title,
                'body' => $request->body
            ]);

            $created->users()->sync($request->user_ids); // <== Post bog'langan user-larni user_post_pivot jadvalda saqlaydi

            return $created;
        });

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
    public function update(UpdatePostRequest $request, Post $post)
    {
        // $post->update($request->only(['title', 'body']));
        $updated = $post->update([
            'title' => $request->title ?? $post->title,
            'body' => $request->body ?? $post->body,
        ]);

        if (!$updated) {
            return new JsonResponse([
                'errors' => [
                    'Failed to update model.'
                ]
            ], 400);
        }

        return new PostResource($updated);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $deleted = $post->forceDelete();

        if (!$deleted) {
            return new JsonResponse([
                'errors' => [
                    'Could not delete resource'
                ]
            ], 400);
        }

        return new JsonResponse([
            'data' => 'success'
        ]);
    }
}

```

Bizda, `PostController`dagi metodlaridan model bilan ishlangan qismlarni alohida repository klasga chiqarib olamiz.

1. Avval, `app/Repositories` papkasini yaratamiz.
2. Unda `PostRepository` klasini ochib, `create`, `update` va `forceDelete` metodlarini yaratamiz:

```php
<?php

namespace App\Repositories;

class PostRepository
{
    public function create()
    {
    }

    public function update()
    {
    }

    public function forceDelete()
    {
    }
}

```

3. `PostController`dagi `store` metodining kodlarini `PostRepository`ning `create` metodiga olib o'tamiz:

```php
public function create()
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
```

4. `PostController`ning `store` metodiga `PostRepository`ni inject qilamiz:

```php
    public function store(StorePostRequest $request, PostRepository $postRepository)
    {
        $created = $postRepository->create($request->only([
            'title', 'body', 'user_ids'
        ]));

        return new PostResource($created);
    }
```

5. `update` va `forceDelete` metodlari ham xuddi shunday ishlatiladi:

`PostRepository` `update` metod:

```php
//...
    public function update(Post $post, array $attributes)
    {
        DB::transaction(function () use ($post, $attributes) {
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
//...
```

`PostController` `update` metod:

```php
//...
    public function update(UpdatePostRequest $request, Post $post, PostRepository $postRepository)
    {
        $post = $postRepository->update($post, $request->only([
            'title', 'body', 'user_ids'
        ]));

        return new PostResource($post);
    }
//...
```

`PostRepository` `forceDelete` metod:

```php
//...
    public function forceDelete(Post $post)
    {
        return DB::transaction(function () use ($post) {
            $deleted = $post->forceDelete();

            if (!$deleted) {
                throw new Exception("Resource can not be deleted");
            }

            return $deleted;
        });
    }
//...
```

`PostController` `forceDelete` metod:

```php
//...
    public function destroy(Post $post, PostRepository $postRepository)
    {
        $post = $postRepository->forceDelete($post);

        return new JsonResponse([
            'data' => 'success'
        ]);
    }
//...
```

Shu yergacha, faqat bitta model uchun repository yozdik. Lekin agar boshqa modellar uchun ham repositorylarni yozsak nima qilamiz? Chunki, ko'pchilik repositorylarda umumiy metodlar bo'ladi (m: create, delete, update kabi).

Buning uchun quyidagicha o'zgarishlarni qilamiz:

1. Avval asosiy repository klasni yozamiz (`BaseRepository`):

```php
<?php

namespace App\Repositories;

abstract class BaseRepository
{
    abstract public function create(array $attributes);

    abstract public function update($model, array $attributes);

    abstract public function forceDelete($model);
}

```

2. `PostRepository` patternni `BaseRepository` patternidan meros olamiz:

```php
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

            return $post;
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

```
