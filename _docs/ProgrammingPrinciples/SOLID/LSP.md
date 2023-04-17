# Liskov Substitution Principle (Liskov almashtirish tamoyili)

LSP SOLID tamoyillarining uchinchisi bo'lib, tushunishga ancha qiyin bo'lgan tamoyil hisoblanadi. Uning ta'rifi quyidagicha:

> Kompyuter dasturida, agar S T-ning osttipi (bolasi) bo'lsa, u holda T-ning obyektlarini S-ning obyektlari bilan (dasturning ixtiyoriy xususiyatlaridan birortasini o'zgartirmagan holda) almashtirib bo'linishi kerak.

Boshqacha aytganda, obyektlar o'zlarining osttiplari (bolalari) bilan almashtirilganda, shu obyektlardagi metodlarni chaqirayotgan har qanday kod muammosiz ishlashda davom etishi kerak.

**Osttip o'zi nima?**

Ko'pchilik OOP tillarda osttip biror klasdan meros olgan boshqa klas yoki interfeysni ishlatgan biror klas bo'lib hisoblanadi.

PHP tilidagi quyidagi misolni ko'raylik:

```php
<?php

interface Animal
{
}

class Cat implements Animal
{
}
class MaineCoon extends Cat
{
}

// interfaceni ishlatgan klasning osttipi/bolasi bo'ladi
echo is_subclass_of(Cat::class, Animal::class); // true
echo '<br>';

// biror klasdan meros olgan boshqa klas o'sha klasning osttipi/bolasi hisoblanadi
echo is_subclass_of(MaineCoon::class, Cat::class); // true
echo '<br>';

// biror interfaceni ishlatgan klasdan meros olgan boshqa klas
// shu interfacening ham osttipi/bolasi hisoblanadi
echo is_subclass_of(MaineCoon::class, Animal::class); // true
```

**Orginal tipning (ota klas yoki interfacening) ishlash logikasini o'zgarmayotganiga qanday qilib ishonch hosil qilish mumkin?**

Open-Close tamoyilida aytilganidek, klaslar va ayniqsa interfacelar kengaytirish mumkin, o'zgartirish mumkin emas bo'lishi kerak. Shuning uchun ham barcha tiplarni kengayishga yo'l qo'ymaslikning iloji yo'q.

Buning o'rniga, orginal tiplarni (ota klaslar yoki interfacelarni) o'zgartirib bo'lmaydigan qilishimiz mumkin.

1. **Bola klaslar metodlarining argumentlari contravariant bo'lishi kerak.**

Faraz qilaylik, bizda `Cat` klasini oladigan `CatShelter` interfacei bor:

```php
interface CatShelter
{
	public function takeIn(Cat $cat): void;
}
```

Bu interfacedan foydalanganimizda, `Cat` argumentini oladigan `takeIn()` metodini chaqiradigan har qanday kod muammosiz ishlashi kerak. Biroq, bola klaslarning metod argumentlari contravariant bo'lgani uchun argument tiplarini o'zgartirishimiz ham mumkin.

```php
class MixedShelter implements CatShelter
{
	public function takeIn(Animal $animal): void
	{
		// bu metod hali ham oldingi ko'zda tutilganidek ishlaydi.
		// chunki, interfacedagi takeIn metodi Catni qabul qiladi.
		// Cat o'rniga Animal klasini bergan bo'lsak ham, bu talabni buzmaydi.
	}
}
```

Metod argumentlarning contravariant bo'lishi tiplarni o'zgartirish mumkinligini anglatishi bilan birga, ularning tiplarini quyi darajada turgan osttiplarga almashtira olmasligimizni ham bildiradi:

```php
class MaineCoonShelter implements CatShelter
{
	public function takeIn(MaineCoon $maineCoon): void
	{
		// Bu noto'g'ri qo'llanilish hisoblanadi. Chunki, metod tipi pastroqdagi osttip/bola klas bilan almashtirilyapti
		// Ya'ni, metod argumentining tipini MaineCoon klasiga almashtirib, CatShelter interfaceida e'lon qilingan va har qanday
		// Cat ni qabul qilishi belgilangan qoidani buzyapmiz. Bu holatda endi faqat metod MaineCoon tipidagi obyektlarni qabul qiladi
		// Lekin, CatShelterdagi takeIn metodi Cat klasiga tegishli barcha obyektlarni qabul qilishi kerak.
	}
}
```

2. **Bola klaslar metodlari qaytariluvchi tiplari covariant bo'lyapti.**

Contravariancega teskari ravishda, qaytariluvchi tiplar covariant bo'lishi ular bola klaslarda yuqori darajada emas quyiroq darajadagi tipda bo'lishini anglatadi.

`CatShelter` misolida, buni quyidagicha kodda ko'rishimiz mumkin:

```php
interface CatShelter
{
	public function getCatForAdoption(): Cat;
}

class MaineCoonShelter implements CatShelter
{
	public function getCatForAdoption(): MaineCoon
	{
		// Cat klasi o'rniga undan pastroqda joylashgan MaineCoon klasini
		// qaytaruvchi tip qilib bergan bo'lsakda, hali ham CatShelter contractini qanoatlantiryapmiz
		// chunki, texnik jihatdan doim talab qilinganidek Cat tipini qaytaryapmiz
	}
}
```

```php
class MixedShelter implements CatShelter
{
	public function getCatForAdoption(): Animal
	{
		// bu esa noto'g'ri ishlatish hisoblanadi, chunki qaytuvchi tipga yuqori darajadagi
		// tipni (klas) beryapmiz. Bu yerda qaytuvchi tip Cat bo'lishiga kafolat bera olmaymiz.
		// Bu metodni chaqirayotgan kod Animal klasining boshqa osttipi (bola klasi) bilan ishlashni
		// bilmasligi mumkin.
	}
}
```

3. **Ost klaslarnda hech qanday yangi exceptionlar berilmasligi kerak. Faqatgina ota klasning exceptioni berilishi mumkin.**

//...

**PHPda `self` va `static` kalit so'zlarining qo'llanilishi**

`self` va `static` klas nomi aliaslarini metod contravariance argument  va qaytariluvchi covariance tiplar bilan birga ishlatib, ajoyib ishlarni qilish mumkin.

self obyektini qaytaruvchi metod misolini ko'raylik:

```php
interface CustomFieldMetadata
{
	public static function fromArray(array $metadata): self;
}

class FreeTextMetadata implements CustomFieldMetadata {
}
```

Garchi qaytariluvchi tipni foydalanayotgan klasimizdan kelib chiqqan holda yo'l-yo'lakay o'zgartirayotgan bo'lsakda, u to'g'ri ishlatilyapti, chunki ostklaslarda qaytariluvchi tip quyiroq darajada gi klas bo'lishiga ruxsat beriladi.

Darhaqiqat, interfaceda metod e'lon qilishda static dan foydalana olmaymiz. Chunki, bu interfacega bog'liq kod `fromArray()` metodidan qaytarilayotgan konkret implementatsiya haqida emas, faqat `CustomFieldMetadata` interfeysining obyektini (instanceini) qaytarishi haqida o'ylashi kerak.

Shunday qilib, biz faqat `self` yoki aniq interface nomini (CustomFieldMetada) metodning qaytariluvchi tipi sifatida qaytarish kerak. Lekin, qaytaruvchi tip covarianceligi sharofati bilan biror impementatsiyada qaytariluvchi tipda quyiroq darajadagi klasni ishlatishimiz mumkin.
