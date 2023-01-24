### Event broadcasting

Hozirda, ko'pchilik zamonaviy web ilovalarda WebSocket-lar user interfeyslarda ma'lumotlarni real vaqtda va jonli almashishda foydalaniladi. Serverda ma'lumot yangilanganda, bu haqida mijozga (UI-ga) WebSocket orqali xabar jo'natiladi.

Misol uchun, faraz qilaylik, dasturimiz user ma'lumotlarini CSV faylga eksport qiladi va uni email qilib jo'natsin. Ammo, CSV faylni yaratish bir necha daqiqa vaqt oladi. Shu sababli, biz CSV fayl yaratishni va uni pochtaga jo'natishni queued job ichida qilishni amalga oshiramiz. CSV fayl yaratilib user-ning pochtasiga jo'natgan paytida, `app/Events/UserDataExported` eventini ishga tushirish uchun event broadcasting-dan foydalanishimiz mumkin.
