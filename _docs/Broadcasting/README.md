### Event broadcasting

Hozirda, ko'pchilik zamonaviy web ilovalarda WebSocket-lar user interfeyslarda ma'lumotlarni real vaqtda va jonli almashishda foydalaniladi. Serverda ma'lumot yangilanganda, bu haqida mijozga (UI-ga) WebSocket orqali xabar jo'natiladi.

Misol uchun, faraz qilaylik, dasturimiz user ma'lumotlarini CSV faylga eksport qiladi va uni email qilib jo'natsin. Ammo, CSV faylni yaratish bir necha daqiqa vaqt oladi. Shu sababli, biz CSV fayl yaratishni va uni pochtaga jo'natishni **queued job** ichida amalga oshiramiz. CSV fayl yaratilib user-ning pochtasiga jo'natgan paytida, dasturimizning JavaScript qismi orqali qabul qilinadigan `app/Events/UserDataExported` eventini ishga tushirish uchun event broadcasting-dan foydalanishimiz mumkin. Bu eventni qabul qilganimizdan keyin, user-ga sahifani yangilamasdan turib uning CSV faylini pochtaga jo'natilgani haqida xabar berishimiz mumkin.

Mana shunda ko'rinishda dastur qismlarini yozishimiz uchun Laravel service tomonida ishlaydigan event-larni WebSocket orqali "broadcast" qilish imkonini beradi. Laravel event-larni broadcasting qilish bizga bir xildagi event nomlari va ma'lumotlarni dasturning Laravel server qismi va JavaScript UI qismi orasida tarqatishga imkon beradi.

Broadcasting tushunchasi asosi sodda: frontend-da mijoz biror kanalga ulangan paytda Laravel dasturi backend-da shu kanallar orqali event-larni "broadcast" qiladi (event-larni kanallarga joylashtiradi). Bu event-lar frontend-dan olish mumkin bo'lgan biror qo'shimcha ma'lumotlarni o'zida saqlashi ham mumkin.

##### Mavjud driverlar.

Odatiy holda, Laravel ikkita server tomonida ishlovchi driverga ega: Pusher Channels va Ably. Lekin, bulardan tashqari, laravel-websockets va soketi kabi dasturchilar jamiyatlari (community) tomonidan taqdim qilingan qo'shimcha bepul driver-lar ham mavjud.

#### Server tomonida o'rnatilishi

Laravel event broadcasting-ni amalga oshirishni boshlashdan avval Laravel project-ida ba'zi sozlashlarni amalga oshirish kerak bo'ladi. Shuningdek, ayrim qo'shimcha paketlarni ham o'rnatishga to'g'ri keladi.

Event broadcasting server tomonidagi broadcasting driver orqali amalga oshiriladi. Laravel Echo (JavaScript kutubxonasi) esa UI-da turib broadcast qilingan event-larni ushlab oladi.

##### Sozlamalar

Project-ning barcha broadcasting-ga bog'liq sozlamalari `config/broadcasting.php` faylida saqlanadi. Laravel broadcasting uchun bir qancha tayyor driver-larga ega: `Pusher Channels`, `Redis` va `log` driver (`log` driver local serverda kod yozish va debug qilish paytida ishlatiladi). Shu bilan birga, `null` driver yordamida testlash payti broadcasting-ni o'chirib qo'yish (disable qilish) uchun foydalaniladi.  Yuqoridagi har bir driver uchun namunaviy sozlamalar `config/broadcasting.php` faylida berilgan.

##### Broadcast Service Provider

Event-larni broadcasting qilishdan oldin `App/Providers/BroadcastServiceProvider`ni project-ga ulab olish (register qilish) kerak bo'ladi. Yangi Laravel freymvorklarda buni qilish uchun `config/app.php` sozlamalar faylidagi `providers` arrayida yuqoridagi `BroadcastServiceProvider`ni izohdan chiqarib qo'yishimiz yetarli. `BroadcastServiceProvider`da broadcast avtorizatsiya route-lari va callback-larni register qilish kodlari turadi.

##### Queue sozlamalari

Yuqoridagi sozlamalardan tashqari, yana queue worker-ning ham kerakli sozlamalarini to'g'rilash va uni ishga tushirish kerak bo'ladi. Event-larni broadcasting qilish queued job-lar yordamida amalga oshirilishi sababli so'rov javobining vaqtiga jiddiy ta'sir ko'rsatmaydi.

#### Pusher channels

Agar event-larni Pusher Channel yordamida broadcast qilmoqchi bo'lsak, avval Pusher Channel-ning PHP SDKsini composer yordamida o'rnatib olishimiz kerak: `composer require pusher/pusher-php-server`

Keyin, Pusher Channels-ga kerakli key va ID sozlamalarni `config/broadcasting.php`da sozlash kerak bo'ladi. O'zi odatda Pusher Channels-ning namunaviy key, secret va app ID-lari bu faylda berilgan bo'ladi. O'zimizning sozlamalarimizni esa `.env` faylida `PUSHER_APP_KEY`, `PUSHER_APP_SECRET` va `PUSHER_APP_ID` o'zgaruvchilariga berishimiz kerak:

```apache
PUSHER_APP_ID=your-pusher-app-id
PUSHER_APP_KEY=your-pusher-key
PUSHER_APP_SECRET=your-pusher-secret
PUSHER_APP_CLUSTER=mt1
```

`config/broadcasting.php` faylidagi `pusher` sozlamasi `cluster` kabi qo'shimcha sozlamalarni ham qo'shish imkonini beradi.

> Barcha kerakli sozlamalarni pusher saytida ro'yxatdan o'tib, yangi app yaratilganda chiqarib berilgan sozlamalardan olish mumkin.

Endi, `.env` faylidagi broadcast driver-ni `pusher`ga o'zgartiramiz:

```apache
BROADCAST_DRIVER=pusher
```

Va nihoyat, frontend tomonida broadcast event-larni qabul qiladigan Laravel Echo-ni o'rnatib, sozlasak ham bo'ladi.

##### Pusherga muqobil ochiq-kodli paketlar

Laravel-websockets va soketi paketlari Laravel uchun Pusher-ga muqobil sifatida WebSocket serverlarini yaratib beradi. Bu paketlar  bepul hisoblanadi va Laravelning broadcasting qilish imkoniyatini to'liq ishlatishga imkon beradi. Ular haqida ko'proq ma'lumot olish uchun [muqobil ochiq-kodli dasturlar](###Muqobil ochiq-kodli dasturlar) bo'limiga o'ting.

#### Ably

Agar event-larni Ably yordamida broadcast qilmoqchi bo'lsangiz, Ably composer yordamida PHP SDKsini o'rnatishingiz kerak bo'ladi: `composer requireably/ably-php`

Keyin, Ably-ning kirish ma'lumotlarini (credentials) `config/broadcasting.php` sozlama fayliga yozish kerak bo'ladi. Ably-ning namunaviy sozlamalari bu faylda oldindan yozib qo'yilgan. O'zimizning Ably kirish ma'lumotlarimizni `.env` faylidagi `ABLY_KEY` o'zgaruvchisiga berib qo'yishimiz kerak:

```apache
ABLY_KEY=your-ably-key
```

Nihoyat, oxirida frontend tomonida broadcast event-larni qabul qiluvchi Laravel Echo-ni o'rnatamiz va uning sozlamalarini yozib qo'yamiz.

### Muqobil ochiq-kodli dasturlar

##### PHP

Laravel-websockets paketi toza PHP-da yozilgan, Pusher bilan ishlay oladigan WebSocket paket hisoblanadi. Ko'proq ma'lumot olish uchun [rasmiy sayt](https://beyondco.de/docs/laravel-websockets/getting-started/introduction)iga o'ting.

##### Node

Soketi Node asosida yozilgan va Pusher bilan ishlay oladigan WebSocket server hisoblanadi. Bu paket aslida µWebSockets.js ustiga qurilgan bo'lib kengayuvchan va juda tez ishlaydi. Ko'proq ma'lumot olish uchun [rasmiy sayt](https://docs.soketi.app/)iga o'ting.

### Mijoz tomonida o'rnatish

#### Pusher channels

Laravel Echo JavaScript kutubxona bo'lib, kanalga ulanish va serverda driver tomonidan broadcast qilingan eventlarni kuzatib turish imkonini beradi. Echo-ni npm paket menejeri yordamida o'rnatiladi. Pusher Channels broadcaster ishlatilganligi sababli ushbu misolda, `pusher-js` paketni ham o'rnatamiz: `npm install--save-devlaravel-echopusher-js`

Echo o'rnatilganidan keyin, frontend qismida JavaScript dasturda (masalan, Vue.js) Echo obyektini yaratish mumkin. Echo obyektini `resources/js/bootstrap.js` faylida yaratish mumkin. Odatiy holatda, bu faylda Echo uchun kerakli barcha sozlamalar qilingan. Faqat, ularni izohdan chiqarish kerak bo'ladi:

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
 
window.Pusher = Pusher;
 
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});
```

Kodni izohdan chiqarib, Echo-ni keragicha sozlab bo'lgandan keyin dastur frontend-ini compile qilinadi: `npm run dev`

##### Mavjud mijoz obyektidan foydalanish

Agar sizda oldindan sozlangan Pusher Channels-ning frontend qismida Echo obyekti mavjud bo'lsa, uni Echo-ga `client` sozlamasi orqali ishlatish mumkin:

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
 
const options = {
    broadcaster: 'pusher',
    key: 'your-pusher-channels-key'
}
 
window.Echo = new Echo({
    ...options,
    client: new Pusher(options.key, options)
});
```

##### Abbly

Laravel Echo JavaScript kutubxona bo'lib, kanalga ulanish va serverda driver tomonidan broadcast qilingan eventlarni kuzatib turish imkonini beradi. Echo-ni npm paket menejeri yordamida o'rnatiladi. Pusher Channels broadcaster ishlatilganligi sababli ushbu misolda, `pusher-js` paketni ham o'rnatamiz.

Event-larni broadcast qilish uchun Ably-dan foydalanayotgan bo'lsak ham, nega `pusher-js` JavaScript kutubxonasini o'rnatish kerakligiga hayron bo'layotgan bo'lishingiz mumkin. Ably Pusher-ga mos bo'lgan ko'rinishni ham o'z ichiga olgan. Bu esa unga frontend tomonidagi dasturda event-larni kuzatib turish uchun Pusher protokolidan foydalanishga imkon beradi:

`npm install--save-dev laravel-echo pusher-js`

> Pusher bilan ishlashni davom ettirishdan oldin Ably dastur sozlamalarida Pusher protokoli bilan ishlashga ruxsat berib qo'yish kerak bo'ladi. Bunga ruxsat berish Ably dastur sozlamalaridagi "Protocol Adapter Settings" qismida amalga oshiriladi

Echo o'rnatilganidan keyin, frontend qismida JavaScript dasturda (masalan, Vue.js) Echo obyektini yaratish mumkin. Echo obyektini `resources/js/bootstrap.js` faylida yaratish mumkin. Odatiy holatda, bu faylda Echo uchun kerakli barcha sozlamalar qilingan. Faqat, ularni izohdan chiqarish kerak bo'ladi:

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
 
window.Pusher = Pusher;
 
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_ABLY_PUBLIC_KEY,
    wsHost: 'realtime-pusher.ably.io',
    wsPort: 443,
    disableStats: true,
    encrypted: true,
});
```
