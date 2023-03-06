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

Bundan tashqari, biror fikr qanchalik miyamizda ko'p aylansa, undan nimadir foydali narsa chiqishiga shunchalik ko'p ishonib boramiz. Hattoki, agar fikrimiz o'ta ahmoqona bo'lsa ham.

Ikkinchidan,  ba'zi paytlarda hamma narsani ham soddalashtirishning imkoni bo'lmaydi. Shunday paytlarda, murakkablikni to'liq yo'q qilib bo'lmasa ham, iloji boricha kamaytirishga harakat qilish kerak.

Eng asosiysi, ishlayotgan sohangizni qanchalik ko'proq tushunsangiz, undagi muammolarga shunchalik osonroq sodda yechimlarni topasiz. Buning uchun esa, shu soha mutaxasislaridan ko'proq ma'lumot olishga harakat qiling. Doimo sohaga oid bilimlaringizni yangilab turing. Va eng asosiysi, bilimingizga juda katta baho berib yubormang.

Eng oxirgisi, mijozlarni tinglash ham juda muhim hisoblanadi.

# Keraksiz kodni o'chirib tashlang

Kamroq kod tizimni soddaroq ko'rinishda saqlaydi. Ko'pi esa albatta uni murakkablashtirib yuboradi.

Albatta, kodning bir qismini o'zgartirish, uning atrofidagi kodga ham ta'sir qilib, istalmagan natijalarga sabab bo'ladi. Shuning uchun, foydasiz har qanday kodni o'chirib tashlash kerak.

Antuan de Sent-Ekzyuperi aytganidek:

> Mukammallikka hech narsa qo'shmaslik va hech narsani o'chirmaslikka zarurat qolmaganda erishiladi.

Bunday gapdan keyin biror nima deyishning o'zi ortiqcha :)

# O'lik kod

Yaxshigina ishlab turgan tizimingiz keraksiz funksionalliklar bilan to'la bo'lishini xohlarmidingiz? Albatta yo'q!

Bu haqida gaplashishdan oldin, keling, o'lik kod nima ekanligini bilib olaylik. Dastur ishlash davomida foydalanilmaydigan har qanday  kod - bu o'lik kod deyiladi. Misol qilib, e'lon qilib qo'yilgan, lekin ishlatilmagan o'zgaruvchilar, umuman foydalanilmagan metodlar, hech qachon obyekti olinmagan klaslarni olishimiz mumkin.

O'lik kod sababli quyidagilar sodir bo'lishi mumkin:

* Dasturchi proyekt kodi bilan ishlash jarayonida o'lik kodlar uni chalkashishiga olib keladi. Masalan, "bu kod nima maqsadda ishlatilgan?", "O'zi bu kodni o'chirib tashalasam ham bo'ladimi yoki shunday turgani yaxshimi?", "Bu kodni biror foydasi bormi?" kabi savollarga sabab bo'lishi mumkin.
* Boshqa dasturchilar bunday befoyda kodlarni bekorga vaqt sarflab refactoring qilib chiqishi mumkin.
* O'lik kod dastur ishlashini tushunishga xalaqit beradi. Misol uchun siz o'zizdan doimo "bu metodni o'zgartirganimda nega dastur ishlashida hech  qanday o'zgarish bo'lmadi?" deb so'rashingiz mumkin.

Shuning uchun keraksiz o'lik kodlarni o'chirib tashalash ham ortiqcha bosh og'rig'idan xalos qilib, ortiqcha kuch sarflashimizni oldini oladi.

# YAGNI tamoyili

Dasturchi ba'zan vaziyatga qarab, biror bir kodni keyinchalik kerak bo'lib qolishi mumkin deb, yozib qo'yadi. Bunday kodni yozishga uning bir kun kelib ishlatishi mumkin deb o'ylashi sabab bo'ladi.

Aynan mana shunday holatdan, YAGNI tamoyili kelib chiqqan. YAGNI - bu "You Aren't Gonna Need It", ya'ni "Bu sizga kerak bo'lmaydi" degan ma'noni beradi. Va 99% holatda aynan shunday ham bo'ladi: siz kelajakda nima bo'lishini bilmaysiz va natijada har ehtimolga qarshi qandaydir kod yozib qo'yasiz. Keyinchalik esa, sizning taxminlaringiz sodir bo'lmay, yozgan "har ehtimolga qarshi" kodingiz ishlatilmay, qolib ketadi.

Agar kodingizni ko'rib chiqish paytida, qachonlardir mana shunday maqsadda yozib qo'ygan kodingizga duch kelsangiz, uni, albatta, o'chirib tashlang. Agar, yozib qo'ygan kodingiz keyinchalik kerak bo'lish ehtimoli yuqori bo'lsa ham, uni yozib qo'ymang. Chunki, o'sha vaziyatga borguncha, baribir, bu kod aynan yozib qo'ygan holatingizdagiday kerak bo'lmaydi. Ya'ni, uni baribir o'zgartirishingizga to'g'ri keladi.

Bunday vaziyatga quyidagi haqiqatda bo'lib o'tgan suhbatni misol qilib keltirishimiz mumkin:

* 1-dasturchi: Tizimdagi foydalanuvchilar uchun mana bu funksionallik nimaga kerak? Bu funksionallikni frontda biror joyda ishlatilganini ko'rmadim. Lekin, backendda yozib qo'yilgan.
* 2-dasturchi: U hozir ishlatilmaydi. Ammo, albatta, uni keyinchalik ishlatamiz.
* 1-dasturchi: Ammo ..., bu funksionallik 2016-yil yozilgan-ku, hozir esa 2019-yil. Agar 3 yil ichida biror kishiga kerak bo'lmagan bo'lsa, demak kelayotgan 2 yilda ham bundan foydalanmasak kerak. Uni o'chirib tashlashimiz kerak.
* 2-dasturchi: Yo'o'q! Bu funksionallik juda ham murakkab tuzilgan. Uni albatta, keyinchalik ishlatamiz.
* 1-dasturchi: ...ammo, bu kod dasturchilarni chalg'itadi. Meni o'zim bu kodni tushunishim uchun ancha vaqt sarflashimga to'g'ri keldi.
* 2-dasturchi: Yo'q, albatta uni ishlatamiz hali.
* 1-dasturchi: ...

Hech kim kelajakda nima bo'lishini bilmaydi. Ba'zi funksionalliklar bekor qilinishi mumkin yoki doimiy ravishda kechiktirilib borilishi mumkin. Lekin, bunday funksionalliklar ertani o'ylab proyektda qoldirib ketish keraksiz va ortiqcha ishdan boshqa narsa emas.

Amaliyotda, hech qachon ishlatilmagan funksionalliklarni har safar bo'ladigan yangilanishlarga moslash uchun vaqt ketkazishganiga duch kelingan.

# Faqat hozir ishlatish kerak bo'lgan funksionalliklar bilan ishlang

Biz dasturchimiz, folbin emas. Shuning uchun ham, faqat hozirda ishga tushirish kerak bo'lgan funksionalliklar uchun kod yozishimiz kerak. Kelajakdagi funksionalliklar qanday bo'lishini hech kim bilmaydi.

# Sodda arxitektura

> Murakkablik bizni o'ldiradi. U dasturchining hayotini og'irlashtiradi. U mahsulotni rejalashtirishni, yasashni va test qilishni qiyinlashtiradi. Xavfsizlik bo'yicha muammolarga sabab bo'ladi va nihoyat u mijoz hamda administratorga muammolar yaratib beradi.
>
> Ray Ozzie

---- ---- ======== ------ =======

# Lazanya arxitekturasi

Lazanya juda yaxshi arxitektura, lekin u kodbeyz (code base) uchun biroz "qo'rqinchli" hisoblanadi. Xo'sh, lazanya arxitekturasining muammosi nimada? Javob esa - to'g'ridan to'g'ri bog'lanmagan qatlamlarning ko'pligida.

Tasavvur qilaylik, bizda APIdan kiruvchi ma'lumotlarni oladigan, ular ustida biror ishni bajaradigan va ularni biror joyda saqlab qo'yadigan dastur bor bo'lsin. Endi shu ishlarni bajaradigan dasturning qatlamlarini sanab chiqaylik:

1. so'rovlarni qabul qiladigan API
2. APIning wrapper-lari
3. Boshqa ko'rinishdagi kontrollerni yaratib, uni APIning wrapper-iga inject qilib (kiritib) beradigan server factory-lar
4. Zarur bo'lganda ishlatiladigan server factory-lar to'plami (server factory pool).
5. API wrapper-lar interface contruct-lar orqali chaqiradigan kontrollerlar
6. Kiruvchi ma'lumotlarni validation qiladigan (tekshiradigan) qatlam
7. Barcha kontroller orasida kodni tarqatadigan boshqa yana bitta qatlam (barcha kodlarni DRY moslab beradigan)
8. Kontroller-lardan interface construct orqali chaqiriladigan doimiy qatlam (persistent layer)
9. Butun bir ma'lumotning o'zini chiqarib olish uchun kerak bo'ladigan IDlarni olib beruvchi boshqa bir qatlam
10. CRUDni ishlatadigan va asosiy funksionallikni ishga tushiradigan qatlam.

Butun bir manzarani tasavvur qila olmadingizmi? Bu tabiiy.

Endi, tasavvur qiling, qaysidir funksionalliklarni o'zgartirib ko'raylik:

* Barcha qatlamlardan o'tib chiqamiz
* Qilgan o'zgartirishimiz natijasida noto'g'ri ishlab ketgan qatlamni topishga harakat qilamiz.
* Bularning barchasi qanday ishlashini yodda saqlab turishga harakat qilamiz.
* O'zgarishlarni amalga oshiramiz.
* Qaysi qatlam nima ish qilayotganini tushunmay qolishimiz mumkin
* Hammasini yana qaytadan ko'rib chiqamiz.
* 4-qatlamda testlash paytida chiqqan 3 ta xatoni to'g'rilaymiz. 2 kunlik vaqtimiz shu narsaga ketib qoladi.
* Biror kishiga baqirib alamingizdan chiqib olasiz.
* Bunaqangi bekorga vaqt sarflab yurish o'rniga, biror bir tog'ning baland qismida kichkinagina kulbada yashab, qo'y boqib yurishni orzu qilasiz

------- ----- -----------====== =======-  ---------- 

# Abstraksiya va murakkablik

Abstraksiya nima? Abstraksiya - bu murakkablikning ayrim keraksiz ma'lumotlarini yashirgan holda shu murakkablikni tasvirlash.

Abstraksiyani funksiya misolida ko'rsak, kodimizdagi biror funksiyani chaqirganimizda, uning ichida qanday ish bajarilayotganini bilmaymiz. Uni faqat chaqiramiz, kerakli ma'lumotlarni berib yuboramiz va natijani olamiz.

Juda ham ajoyib, to'g'rimi? Tizimda qanaqangi murakkab ishlar bo'layotaganining bizga umuman qizig'i yo'q. Faraz qilaylik, loyli koptok yasayapmizda, barcha loyni chiroyli abstraksiya ichiga yashiryapmiz.

Lekin, aslida bu noto'g'ri.

Oldin aytganimizdek, funksiyalarga abstract holatda munosabatda bo'lamiz. OOPdagi obyektlarga ham xuddi shunday qaraymiz. Hattoki, array kabi foydalanayotgan dasturlash tilimizdagi sodda ma'lumot turlariga ham hayotdagi murakkab narsalarning abstrakt ko'rinishi sifatida qaraymiz.
