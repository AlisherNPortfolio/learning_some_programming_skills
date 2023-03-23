# Laravelda adapter patterni

**Adapter patterni nima?**

Adapter pattern (Wrapper pattern deb ham nomalanadi) structural patterns oilasiga mansub bo'lib, obyektlarga o'ziga mos kelmaydigan interfeyslar bilan birga ishlashni ta'minlashda qo'llaniladi.

Adapter patternda klas interfeysini boshqa interfeysga konvertlab, kerakli ko'rinishga keltirib beriladi. Aniqroq aytganda, o'zaro mos kelmaydigan va birga ishlatib bo'lmaydigan klaslarni bir-biriga moslashtirib beradi.

Faraz qilaylik, birjada savdo qilish uchun dastur yozyapsiz. Dasturingiz turli xil manbaalardan birja kotirovkalarini XML ko'rinishda yuklab olib, ularni o'zida grafik ko'rinishda chiqarib beradi.

Ma'lum vaqt o'tgandan so'ng, dasturingizni rivojlantirish maqsadida tahlil qilishda ishlatiluvchi tashqi kutubxonadan foydalanmoqchi bo'ldingiz. Ammo, dasturingizda foydalanmoqchi bo'lgan kutubxonangiz faqat JSON ko'rinishidagi ma'lumotlar bilan ishlay olar ekan (ya'ni muammo sizning dasturingiz XML ma'lumotlar bilan, kutubxona esa JSON ma'lumotlar bilan ishlashida bo'lyapti).

Agar, dasturni kutubxonaga moslashtirib, ma'lumotlarni JSON ko'rinishiga o'tkazsak, dasturning oldin qilingan qismlari ishlamay qoladi, XMLda qoldirsak kutubxonani ishlata olmay qolamiz.

Bunday muammoga yechim sifatida aynan adapter patterni ishlatiladi. Bu patternning vazifasi bitta obyektning interfeysi yoki ma'lumotlarini boshqa obyektga tushunarli ko'rinishga o'tkazib berishdan iborat.

Adapter patternda quyidagi qatnashchilar bo'ladi:

* **Mijoz** (Client): moslashtirilishi kerak bo'lgan tashqi APIni ishlatmoqchi bo'layotgan klas yoki obyekt.
* **Moslashtiruvchi** (Adapter): moslashuvchi va uning mijozi o'rtasida umumiy interfeys yaratib beradi.
* **Moslashuvchi** (Adaptee): tashqaridan, masalan, tashqi kutubxonadan olinayotgan obyekt.


Adapter patternnig eng katta afzalligi - bu mijoz kodini tashqi ulanuvchi resurslardan alohida ajratib berishida.

> Bu patterndan kodingiz tez-tez o'zgarib turadigan tashqi APIga yoki boshqa tashqi klaslarga bo'lganida ishlatiladi.


**Muammo**

Faraz qilaylik, saytimizdagi mahsulotlar omborini tekshiruvchi interfeys bor.

Bunda, dasturimizda ombordagi mahsulotni tekshirib beradigan servisimiz mavjud bo'lib, uni kontrollerda quyidagicha ishlatamiz:

```php
class StockCheckController extends Controller
{
    public function __construct(protected DatabaseStockCheck $databaseStockCheckService)
    {
    }

    public function index(Request $request)
    {
        $sku = $request->input('sku');
        $stock = $this->databaseStockCheckService->getStock($sku);

        return response()->json($stock);
    }
}

```

`DatabaseStockCheck` servisi:

```php
class DatabaseStockCheck 
{
   public function getStock($sku)
   {
	$product = Product::whereSku($sku)->first();
	return $product->qty;
   }
}
```


> Bu yerda keltirilgan misol sodda ko'rinishda berilyapti. Aslida, repository va shu kabi boshqa klaslardan ham foydalaniladi.

Yuqoridagi kodda ko'rganingizdek, kontrollerning index metodida mahsulotning **sku** kodini request obyektidan olib, uni `DatabaseStockCheck` servisiga uzatyapmiz va shu **sku**ga mos keluvchi mahsulotni bazadan olyapmiz.

`DatabaseStockCheck` servisi faqatgina mahsulotni topib, uning bazadagi miqdorini qaytaryapti. Juda oddiy, shundaymi?

Biroz vaqt o'tgandan so'ng, menejerimiz kelib kompaniyaning ERP tizimi ma'lumotlar bazasidan ham mahsulot miqdorini tekshirish kerakligini aytdi. Bu talab qo'yilishi bilan bizda ba'zi muammolar paydo bo'ladi:

* Birinchidan, ERP ma'lumotlar bazasiga so'rov yuborish uchun kontrollerimizni o'zgartirishimiz kerak.
* ERPning ma'lumotlar bazasidan ma'lumot oluvchi API `getStock()` metodini ishlatganmi yoki yo'q, buni bilmaymiz. Ishlatmagan bo'lsa, APIni o'zgartirishni ham so'ray olmaymiz.
* Boshqa servis qo'shib, `DatabaseStockCheck` klasini o'zgartirishimiz kerak.
* ERPning APIsi bizga kerak bo'lgan ko'rinishidagi ma'lumot tipini berish yoki bermasligini ham bilmaymiz.

Endi faraz qilamiz, ERP APIning quyidagicha klas bor. Bu klasni o'zgartira olmaymiz. Undan faqat foydalanishimiz mumkin xolos:

```php
class Erp
{
    protected $sku;

    public function __construct($sku)
    {
        $this->sku = $sku;
    }

    public function checkStock()
    {
        return [
            'sku' => $this->sku,
            'status' => true,
            'qty' => 101
        ];
    }
}

```

`ERP` klasi kodida ko'rganingizdek, bu klas o'zining `checkStock()` metodi orqali omboridagi mahsulot haqidagi ma'lumotni array ko'rinishida olyapti. Ya'ni, tashqi API o'ziga mos kelgan ko'rinishda boshqa nomdagi metod bilan ma'lumotni olyapti. Uni biz xohlaganimizday o'zgartira olmaymiz.

Ayanan shu yerda, Adapter patterini qo'llaymiz!

**Adapter patternini Laravelda ishlatish**

Shu paytgacha omborni tekshirish haqida quyidagi ikkita narsani bilar edik:

* Ombordagi mahsulotni uning sku kodi bo'yicha tekshiramiz.
* Ma'lumotlar bazasidan yoki APIdan olinadigan ma'lumot butun son ko'rinishida bo'lishi kerak.

Birinchi ish, `IStockCheker` interfeysini yaratamiz:

```php
interface StockCheckerInterface
{
    public function getStock($sku);
}

```

Keyin, `IStockChecker` interfeysini ishlatadigan `DatabaseStockCheckAdapter` klasini yaratamiz:

```php
class DatabaseStockCheckAdapter implements StockCheckerInterface
{
    public function getStock($sku)
    {
        $product = Product::whereSku($sku)->first();

        return $product->qty;
    }
}

```

So'ngra, `ErpStockeCheckAdapter` nomli yangi klas yaratamiz. Bu klas ham `IStockChecker` interfeysini ishlatadi:

```php
class ErpStockCheckAdapter implements StockCheckerInterface
{
    public function getStock($sku)
    {
        $erp = new Erp($sku);

        $result = $erp->checkStock();

        return $result['qty'];
    }
}

```

Shu yergacha, umumiy interfeysni ishlatuvchi ikkita adapter klas yaratdik. Endi, umumiy interfeysni shu klaslarga bog'lab qo'yamiz. Bu ishni `AppServiceProvider` klasida bajaramiz:

```php
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(StockCheckerInterface::class, function ($app) {
            switch ($app->make('config')->get('services.stock-checker')) {
                case 'database':
                    return new DatabaseStockCheckAdapter;
                case 'erp':
                    return new ErpStockCheckAdapter;
                default:
                    throw new \RuntimeException("Unknown Stock Checker Service");
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

```

`AppServiceProvider` da `IStockChecker` interfeysni sozlamaga qarab `DatabaseStockCheckAdapter` yoki `ErpStockCheckAdapter` klasiga bog'lab qo'yamiz.

Sozlamani `config/services.php` fayliga joylashtiramiz:

```php
<?php

return [

    'stock-checker' => 'database'
];

```

Va nihoyat, kontrollerimizni `IStockChecker` interfeysiga moslaymiz:

```php
class StockCheckController extends Controller
{
    public function __construct(protected StockCheckerInterface $stockCheckerService)
    {
    }

    public function index(Request $request)
    {
        $sku = $request->input('sku');
        $stock = $this->stockCheckerService->getStock($sku);

        return response()->json($stock);
    }
}

```

**Xulosa**

Adapter patterni mavjud interfeysni o'ziga moslashtiradi. Bu patternning kamchiligi shundaki, agar ikkita klas juda ko'p sondagi metodlarga ega bo'lsa, ularni moslashtirish ancha qiyinlashadi va ba'zida imkonsiz ham bo'lib qoladi.

> Adapter patterndan doimo mijoz va moslashuvchilarda umumiy maqsad bo'lgandagina ishlatish kerak.
