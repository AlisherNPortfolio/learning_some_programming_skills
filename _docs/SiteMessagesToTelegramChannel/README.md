# Website contact form messages to telegram channel

Avval telegramda yangi bot ochiladi

1. Telegramga kirib global qidirishdan `BotFather` boti qidirib topiladi.
2. Botga kirilgandan keyin `/newbot` buyrug'i tanlanadi
3. Yaratmoqchi bo'lgan botimizning nomini yozamiz. Masalan, `MyMessageNotifier`
4. Keyin bot uchun username yoziladi. Username oxiri albatta `_bot` so'zi bilan tugashi kerak. Masalan, `my_message_notifier_bot`
5. Bot yaratilganidan keyin `BotFather` bizga botimizga bog'lanishimiz uchun token beradi. Shu tokenni `.env` faylga `TELEGRAM_BOT_ID` nomi bilan saqlab qo'yamiz.
6. Endi botimizga kirib `start` qilib olamiz (agar botni yo'qotib qo'ygan bo'lsangiz, global qidirishdan bot usernami orqali topsa bo'ladi).
7. 

Keyin esa telegramda yangi kanal ochiladi

1. Telegramda kanal yaratib olinadi (kanal yaratish juda oson. Shuning uchun unga to'xtalib o'tirmaymiz).
2. Keyin kanalimizning `Chat_ID`sini olamiz. Buning uchun kanalimizga oldinroq yaratgan botimizni admin sifatida qo'shamiz.
3. So'ngra oldin yaratgan botimizga avval biror bir xabar yozamiz. Misol uchun, `Hello`.
4. Botga xabar yuborish uchun `https://api.telegram.org/bot<TOKEN>/sendMessage?chat_id=<CHAT_ID>&text=Biror%20Xabar` dan foydalanish mumkin. Bu yerda CHAT_ID yangi ochadigan kanalimizning `@` bilan boshalanadigan nomi. Qaytgan responseda -100 bilan boshlanuvchi `id` bor. Shu id kanalimiz ChatIDsi hisoblanadi. Bu IDni ham `.env` faylga `TELEGRAM_CHANNEL_ID` nom bilan saqlab qo'yamiz.

PHP qismini yozish.

Yuqorida kanal IDsini olish paytimizda `sendMessage` metodi yordamida kanalga xabar yuborishni amalga oshirgan edik. Mana shu qismning o'zi asosiy qismi hisoblanadi. Endi shu APIni PHPga o'tkazib chiqsak bo'ldi.

1. `app/Base/Telegram.php` faylini ochib olamiz.
2. Keyin shu faylga quyidagicha kod yozamiz:

```php
<?php

namespace App\Base;

class Telegram
{
    const BASE_URL = 'https://api.telegram.org/bot'; // telegramning base URLi

    const CHANNEL_URL = '/sendMessage?chat_id='; // telegramning sendMessage metodi va kanal id si uchun URL davomi

    const PARSE_MODE_HTML = 'HTML'; // yuboriladigan xabarni qanaqa ko'rinishda parse qilish. Xabarimiz HTML taglar bilan ketadi.

    private $bot_id; // Botimiz IDsi uchun property

    private $channel_id; // Kanalimiz IDsi uchun property

    public function __construct(string $bot_id, string $channel_id = null)
    {
        $this->bot_id = $bot_id;
        if ($channel_id) {
            $this->channel_id = $channel_id;
        }
    }

    public function getBaseUrl()
    {
        return self::BASE_URL . $this->bot_id;
    }

    private function getChannelUrl()
    {
        return self::CHANNEL_URL . $this->channel_id;
    }

    // xabarni jo'natish metodi
    public function sendMessage(array $text, bool $with_channel, $file = null)
    {
        $url = $this->getBaseUrl();
        if ($with_channel && $this->channel_id) {
            $url .= $this->getChannelUrl();
        } else {
            throw new \Exception('Please, set channel_id or $with_channel flag true');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $text);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}

```

3. Asosiy `Telegram` klasimizni yaratib oldik. Endi undan foydalanib, telegram kanalimizga xabar jo'natamiz. Faraz qilaylik, `ContactsController` klasimizdagi `contact` degan metod yordamida xabar jo'natilsin. Shu metodni yozamiz:

```php
//...   
public function contact(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->validate([
                'name' => 'required|string|min:1|max: 100',
                'who' => 'required|string|in:company,individual',
                'email' => 'required|email',
                'subject' => 'required|string|min:3|max:255',
                'message' => 'nullable|string|max:500'
            ]);

            try {

                $bot_id = env('TELEGRAM_BOT_ID');
                $channel_id = env('TELEGRAM_CHANNEL_ID');

                if ($bot_id && $channel_id) {
                    $text = "<b>" . __('messages.subject') . "</b>: {$data['subject']}" . PHP_EOL .
                        "<b>" . __('messages.sender') . "</b>: {$data['name']}" . PHP_EOL .
                        "<b>" . __('messages.sender_email') . "</b>: <a href=\"mailto:{$data['email']}\">{$data['email']}</a>" .
                        PHP_EOL . "<b>" . __('messages.message') . "</b>: {$data['message']}";

                    $telegram = new Telegram($bot_id, $channel_id);
                    $telegram->sendMessage(['text' => $text, 'parse_mode' => Telegram::PARSE_MODE_HTML], true);
                } else {
                    Log::error("BOT_ID yoki CHANNEL_ID topilmadi. Xabar yuboruvchi: {$data['email']}; Xabar: {$data['subject']}; {$data['message']}");
                }

                return response()->json(['success' => true]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }
```

4. Faqat bu metodni routeda ko'rsatib qo'ysak bo'ldi.

```php
//...
Route::post('/contact-me', [ContactsController::class, 'contact']);
//...
```

> Eslatma: `parse_mode` `html` bo'lganida faqat quyidagicha html taglarni qo'llaydi: `<b>`, `<strong>`, `<i>`, `<em>`, `<u>`, `<ins>`, `<s>`, `<strike>`, `<del>`, `<span>`, `<tg-spoiler>`, `<a>`, `<pre>`, `<code>`.
