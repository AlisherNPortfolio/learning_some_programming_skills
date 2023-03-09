# Kirish

Databasedagi jadvallar ko'pincha o'zaro bog'langan holda bo'ladi. Misol uchun har bir blogda ko'plab izohlar bo'lishi yoki bir nechta buyurtmalar bitta userga tegishli bo'lishi mumkin.

Eloquent mana shunday o'zaro bog'lanishlar bilan ishlashni osonlashtirib beradi. U quyidagicha jadval bog'lanishlari bilan ishlay oladi:

* One to One (Birga bir)
* One to Many (Birga ko'p)
* Many to Many (Ko'pga ko'p)
* Has one Through (Birga bir orqali)
* Has many Through (Birga ko'p orqali)
* Polimorphic One to One
* Polimorphic One to Many
* Polimorphic Many to Many

# Relationlarning e'lon qilinishi

Relationlar model ichida metod ko'rinishida e'lon qilinadi. Bu metod query builder obyektni qaytaradi. Relationlarni metod ko'rinishida e'lon qilish relationdan olinayotga boshqa modelni metod chain ko'rinishida ishlatishga imkon beradi. Misol uchun `posts` metodi orqali `posts` modelini olib ishlatishimiz mumkin:

```php
$user->posts()->where('active', 1)->get();
```
