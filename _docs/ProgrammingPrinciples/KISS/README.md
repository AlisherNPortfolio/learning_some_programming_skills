# KISS tamoyili (Keep It Simple, Stupid)

Biror tizimni yaratishimizni oddiylikni saqlay olish juda muhim hisoblanadi. Lekin, muammo shundaki, oddiylikni saqlashning o'zi biz o'ylaganchalik oson emas.

Endi, shu oddiylikka qanday erishish mumkinligi haqida gaplashamiz.

# Oddiy... yoki oson?

Eng avvalo, oson va oddiy tushunchalari orasidagi farqni bilib olishimiz kerak. "Oddiy" so'zining izohli lug'atdagi ma'nosiga qaraydigan bo'lsak, unga quyidagicha tushuntirish berilgan:

> Sodda, murakkab bo'lmagan, jo'n, odatdagi yoki kundalik me'yordagi.

Ancha tushunarli bo'lgan bo'lsa kerak. Ya'ni, agar tizimimiz faqat bir nechta qismdan iborat bo'lsa, uni oddiy tizim desak bo'ladi. Oddiyroq aytganda, tizimimiz juda ko'p sondagi bir biriga bog'langan qismlardan tashkil topmagan.

Endi, oson so'zining ma'nosini ko'raylik:

> Qiyin emas; jo'n; bajarilish jihatidan uncha qiyin, murakkab bo'lmagan; uncha ko'p mehnat, kuch yoki bilim talab qilmaydigan.

Faraz qilaylik, siz ikkilik sanoq tizimida ikkita sonni qo'shmoqchisiz. Bu siz uchun oson emas. Chunki, sizda bu bo'yicha ba'zi bilimlar yetishmaydi. Ammo, bunday qo'shishni qismlarga ajratadigan bo'lsak, amal unchalik ko'p ham qismdan tashkil topmagan. Shunday qilib, bu amal ***oddiy***, lekin bajarish uchun ***qiyin*** hisoblanadi.

Boshqa misolni ko'raylik: faraz qilaylik, sizda o'zaro bog'liq holda ishlaydigan 18237 ta klasdan iborat tizim bor bo'lsin. Bunday tizim juda ***murakkab* **hisoblanadi. Hattoki, siz tizimdagi bitta algoritmni juda yaxshi tushunsangiz ham, bu tizim murakkabligicha qoladi.

Xulosa qilib aytadigan bo'lsak, murakkablikning ikkita xususiyati bo'ladi:

* tizim juda ko'p qismlardan tashkil topgan bo'ladi
* Tizimning qismlari o'zaro bog'liqlikda ishlaydi.

# Oddiylik nega kerak?

Dasturchi bo'lish nega qiyin? Buni sodda qilib tushuntiramiz: o'zgarishlar.

Agar yaratayotgan dasturingiz hech qachon boshqa o'zgarmasligi aniq bo'lsa, bu maqolani o'qishingiz shart emas. Yana ham aniqrog'i, dasturlash sohasidagi birorta tamoyilni o'qishingiz shart emas. Chunki, buning sizga foydasi tegmaydi. Shunchaki, biror tilda protsedurali kod yozib qo'ying. Uni hech qanaqa tamoyil asosida yozib o'tirmang. Sababi, baribir dasturingizning kodini hech kim o'qimaydi.

Aslida, kodning o'zgarmasligi - bu afsona. Har qanday dastur kodi o'zgaradi. Hech bo'lmaganda siz ishlatgan kutubxonalar o'zgaradi. Natijada, sizning dasturingiz kodi ham o'zgaradi. Kodni o'zgartirish uchun esa, tizimning qismlari o'zaro qanday bog'liqlikda ishlayotganini bilishingiz kerak bo'ladi. Buni bilish uchun esa, uni qandaydir darajada miyangizda tasavvur qilishingiz kerak bo'ladi.

Tizimning tuzilishiga qarab miyangizdagi bunday tasavvur aniq yoki chalkash ko'rinishda bo'lib qolishi mumkin. Ya'ni tizim qismlari to'g'ri tashkillashtirilgan bo'lsa, uni miyangizda tasavvur qilish oson bo'ladi, teskari holatda esa, buning aksi.

> Murakkablikni boshqarish dasturlashning asosi hisoblanadi. Miyamizda to'g'ridan to'g'ri saqlanadigan batafsil ma'lumotlarning mutlaq miqdori bo'yicha chegaralangan bo'lamiz.
>
> Brayn Kernigan (Brian Kernighan)



Endi, birorta yomon kod yozadigan dasturchining qilgan projectini ko'raylik. Bu project bir qancha yashirin bog'liqliklar va qayerlardadir ishlatilgan global o'zgaruvchilarga to'la. Siz bu projectda nimanidir o'zgartirmoqchi bo'lsangiz, uning ta'siri peojectning boshqa qismlariga qanday ta'sir qilishini bilmaysiz. Projectni miyangizda tasavvur qilib ko'rishga harakat qilib, faqat asabingiz buzilishiga sabab bo'lasiz. Natijada shu dasturchini o'zidan tortib boshqa avlodlarigacha lanatlab chiqasiz.

# Ishning murakkabligi

Tizimning murakkabligi dasturchining dasturga yangi funksionallik qo'shishiga ketadigan vaqtga ham ta'sir qiladi. Bu esa juda muhim. Hozirgi kunda, kompaniyalar muvaffaqiyatga erishishi uchun moslashuvchanlik va tezlikka ko'proq e'tibor qaratishadi.

Lekin, biz doim ham murakkablikdan qochib qutula olmaymiz. Talabga qarab, murakkab bo'lgan dasturlarni ham qilishga majburmiz. Misol uchun, onlayn do'kon yaratish uchun buyurtma oldik. Ushbu projectni qilishda murakkablikdan qochib, mahsulotlar, buyurtmalar, yetkazib berish kabi qismlarni projectga qo'shmaslikning iloji yo'q.

Shuning uchun ham, dasturchi sifatida, quyidagilarni yodda tutishingiz kerak:

1. Agar murakkablikdan qochishning imkoni bo'lsa, iloji boricha undan qochishga harakat qiling
2. Agar murakkablikdan qochishning iloji bo'lmasa, uni to'g'ri tashkillashtirishingizga amin bo'ling. Ya'ni, murakkab holat yoki jarayonni shunday tashkillashtiringki, keyinchalik u bilan ishlashda qiyinchiliklarga uchramang.


# Soddaroq talablar

Qilinadigan projectdagi talab qilingan ishlarning murakkabligini kamaytirish uchun quyidagicha takliflarni bermoqchiman:

1. Agar buyurtma qilingan project bo'yicha dasturchi va buyurtmachi o'rtasida uchrashuv tashkillashtirilmagan bo'lsa, buni albatta amalga oshirish shart. Dasturlashni tushunmaydigan boshliqlar yoki menejerlar tomonidan berilgan projectni ko'r ko'rona boshlamang. Balki ular olgan buyurtmalari qanaqa ekanligini tushunishmagan bo'lishi mumkin. Agar dasturchi buyurtmachi bilan gaplashadigan bo'lsa, qilinadigan projectdagi barcha murakkabliklarni tushunib, hisobga olib ketadi. Bu esa albatta projectning sifatli va o'z vaqtida bajarilishini ta'minlaydi.
2. Buyurtmachilar bilan bo'ladigan uchrashuvlar paytida, agar biror murakkab funksionallik qilish kerakligi so'ralsa, avvalo bu funksionallik nega kerak bo'lishini so'rab oling. Bu funksionallik foydalanuvchiga qanday qulaylik yaratib beradi. Balki, bu funksionallik o'rniga boshqa oddiyroq yechimlar bordir. Yoki bunday funksionallik projectga umuman kerak emasdir.
3. Agar rostdan ham shu funksionallik kerak bo'lsa (albatta bunday holat deyarli doimo bo'ladi), uni soddaroq ko'rinishda bajarishga yordam beradigan fikr bor bo'lishi mumkin. Misol uchun, foydalanuvchilarni ro'yxatga oluvchi formada 20 ta ma'lumot kiritiluvchi maydon o'rniga 10 tasi yetarli bo'lishi mumkin. Bundan tashqari, murakkab funksionallik o'rniga taklif qilingan sodda yechim buyurtmachiga ko'proq ma'qul kelishi mumkin.

Talab qilingan murakkabliklarni to'g'ridan to'g'ri qisqartirish qiyin. Xo'sh nega?
Birinchidan, ko'pchilik buyurtmachilar o'zlarining fikrlariga yopishib olishadi. Bu tabiiy. Chunki, biz doimo o'zimizning fikrlarimizni himoya qilishga harakat qilamiz. Aniqrog'i, bizning fikrimiz dunyoni o'zgartirib yuboradi deb o'ylaymiz.

Bundan tashqari, biror fikr qanchalik miyamizda ko'p aylansa, undan nimadir foydali narsa chiqishiga shunchalik ko'p ishonib boramiz. Hattoki, agar fikrimiz o'ta ahmoqona bo'lsa ham
