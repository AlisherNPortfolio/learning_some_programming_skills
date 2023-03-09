# One to One

One to One relationshipi databasedagi eng sodda relationship (bog'lanish) hisoblanadi. Misol uchun, bitta `User` modeli bitta `Phone` modeli bilan bog'langan bo'lishi mumkin.

Bu bog'lanishni model ichida ifodalash uchun `User` modeli ichida bitta `phone` degan metod ochamiz va bu metodda `Phone` modelini `hasOne`  metodiga argument qilib beramiz (`hasOne` metodi `Illuminate\Database\Eloquent\Model` ota klasida e'lon qilingan bo'ladi):

```php
<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
 
class User extends Model
{
    /**
     * Get the phone associated with the user.
     */
    public function phone(): HasOne
    {
        return $this->hasOne(Phone::class);
    }
}
```

Bog'lovchi metod e'lon qilinganidan keyin `User` modelidan oligan obyekt yordamida unga bog'langan `Phone` modeli ma'lumotlarini olishimiz mumkin bo'ladi. Buning uchun Eloquentning dinamik xususiyatlaridan foydalanamiz. Biz e'lon qilgan bog'lovchi metodga xuddi obyekt xususiyatiga murojaat qilgandek murojaat qilamiz. Eloquent esa bu dinamik xususiyat yordamida biz e'lon qilgan metodni chaqirib beradi:

```php
$phone = User::find(1)->phone;
```

`hasOne` metodi odatda ikkinchi parametr sifatida bog'lanayotgan jadvalining **foreign key**-ini oladi. Bizning misolda bog'lanayotgan jadvalimiz - bu phones jadvali, modeli esa `Phone` modeli. Misolimizda `hasOne` phones jadvalining `user_id` ustunini 2-parametr sifatida oladi.

```php
return $this->hasOne(Phone::class, 'foreign_key'); // Bu yerda foreign_key - bu Phone-ning user_id ustuni
```

Bundan tashqari, Eloquent `hasOne` metodida 3-parametr qilib, **foreign key** bog'lanadigan ota jadvalning (misolimizda users jadvali) `primary key` (odatda id ustuni primary key bo'ladi) bo'lgan ustuni `local_key` ko'rinishida beriladi. Ya'ni:

```php
return $this->hasOne(Phone::class, 'foreign_key', 'local_key'); // bu yerda local_key - bu User-ning id ustuni
```

**Teskari bog'lanishni amalga oshirish**
 Tepada User modeliga bog'langan Phone modelini ma'lumotlarini chaqirishni ko'rdik. Endi shuning teskarisi, Phone modeli bog'langan User modeli ma'lumotlarini chaqirishni ko'raylik. Bu ham xuddi oldingisi singari model klas ichida bog'lovchi metodni chaqirish orqali bajariladi. Endi, bola jadval uchun biz `belongsTo` metodini chaqirib ota jadvalga bog'lanamiz:

 ```php
<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
class Phone extends Model
{
    /**
     * Get the user that owns the phone.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
 ```

Ko'rib turganingizdek, bu yerda ham xuddi oldingiga o'xshash usul, farqi bog'lovchi metod boshqa.
