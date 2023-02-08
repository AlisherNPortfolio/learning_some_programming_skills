# Testlash uchun kerak bo'lgan maqola yozish qismi

1. Project yaratiladi: `composer create-project --prefer-dist laravel/laravel blog`
2. Database-ga ulash uchun bo'lgan sozlamalar `.env` faylga kiritiladi.
3. Avval Post modelini barcha kerakli qismlari bilan yaratamiz: `php artisan make:model Post --all --api`. Bu buyruq bilan Post-ga aloqador **model**, **migration**, **seeder**, **contoller**, **form request**, **policy**-larni yaratib beradi.
4. Comment modelini barcha kerakli qismlari bilan yaratamiz: `php artisan make:model Comment --all --api`
5. Migration-larni to'ldirib chiqamiz:
   1. Post migrationi:

```php
//...
Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->json('body')->nullable();
            $table->timestamps();
        });
//...
```

    2. Comment migrationi:

```php
//...
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->json('body')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
            $table->timestamps();
        });
//...
```

6. user va postlarni bog'lovchi pivot jadval yaratamiz: `php artisan make:migration create_user_post_pivot_table`

```php
//....
Schema::create('user_post_pivot', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');
        });
//...
```

7. Migration-ni ishga tushiramiz: `php artisan migrate`
8. UserSeeder yaratib unda User factory bilan test user kiritish: `php artisan make:seeder UserSeeder`

```php
// database/seeders/UserSeeder.php faylda
//...
    public function run()
    {
        $users = User::factory(10)->create();
    }
//...
```

```php
// database/seeders/DatabaseSeeder.php faylda
//...
    public function run()
    {
        $this->call(UserSeeder::class);
    }
//...
```

9. DatabaseSeeder-ni ishga tushiramiz: `php artisan db:seed`
10. UserSeeder ishlashidan oldin `users` jadvalidagi ma'lumotlarni o'chirib yuborish uchun `UserSeeder.php` faylini quyidagicha o'zgartiramiz:

```php
//...
    public function run()
    {
        DB::table('users')->truncate(); // <== shu qator qo'shiladi
        $users = User::factory(10)->create();
    }
//...
```

! Eslatma: agar MySQL bo'lsa quyidagicha:

```php
// ...
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('users')->truncate(); // <== shu qator qo'shiladi
        $users = User::factory(10)->create();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
//...
```

`truncate` qilish va foreign key-ga tekshirishni vaqtinchalik o'chirishni hamma migration klasslarda qaytadan yozishni oldini olish uchun ularni alohida trait-larga joylashtirib ishlatamiz:

```php
// database/seeders/Traits/TruncateTable.php:
<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

trait TruncateTable
{
    public function truncate($table)
    {
        DB::table($table)->truncate();
    }
}

```

```php
// database/seeders/Traits/DisableForeignsKey.php:
<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

trait DisableForeignKeys
{
    public function disableForeignKeys()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
    }

    public function enableForeignKeys()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}

```

`UserSeeder` klasi endi quyidagicha bo'ladi:

```php
//...
class UserSeeder extends Seeder
{
    use TruncateTable, DisableForeignKeys;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->disableForeignKeys(); //<== MySQLda
        $this->truncate('users'); // <== shu qator qo'shiladi
        $users = User::factory(10)->create();
        // $this->enableForeignKeys(); //<== MySQLda
    }
}
```

11. Endi Post factory bilan post uchun ma'lumotlarni kiritish va uni seeder yordamida database-ga kiritishni ko'ramiz:

```php
// app/Models/User.php:
class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body'];

    protected $casts = [
        'body' => 'array'
    ];
}

```

```php
// database/factories/PostFactory.php
//...
    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'body' => []
        ];
    }
//...
```

```php
// database/seeders/PostSeeder.php
class PostSeeder extends Seeder
{
    use TruncateTable;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncate('posts');
        Post::factory(3)->create();
    }
}
```

```php
// database/seeders/DatabaseSeeder.php
//...
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(PostSeeder::class);
    }
//...
```

Oxirida, `php artisan db:seed` buyruqni ishga tushiramiz.

12. Comment factory bilan comment uchun ma'lumotlarni yaratib, uni seeder yordamida database-ga kiritib chiqamiz:

```php
// app/Models/Comment.php
//...
class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['body', 'user_id', 'post_id'];

    protected $casts = [
        'body' => 'array'
    ];
}
```

```php
// database/factories/CommentFactory.php
//...
    public function definition()
    {
        return [
            'body' => [],
            'user_id' => rand(1, 10),
            'post_id' => rand(1, 3)
        ];
    }
//...
```

```php
// database/seeders/CommentSeeder.php
// ...
class CommentSeeder extends Seeder
{
    use TruncateTable;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->truncate('comments');
        Comment::factory(3)->create();
    }
}
```

`database/seeders/DatabaseSeeder.php` ni o'zgartiramiz:

```php
//...
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(PostSeeder::class);
        $this->call(CommentSeeder::class);
    }
//...
```

13. Modellarning o'zaro bog'liqliklarini yozib chiqamiz:
