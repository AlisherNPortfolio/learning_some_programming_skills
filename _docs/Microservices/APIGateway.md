# Joining Data among different Microservices

Ma'lumki, Microservicelarda databaselar har bir microservice uchun alohida bo'ladi. Ba'zan, mana shu alohida holda ishlab turgan databaselardagi ayrim jadvallar bilan birgalikda ishlashga to'g'ri kelib qoladi. Misol uchun, `books` jadvalimiz bitta microservice bazasida, `authors` jadvalimiz esa boshqa microservice jadvalida joylashgan. Shunday vaziyat bo'lib qoldiki, bu ikkala jadval ma'lumotlarini `JOIN` yordamida birlashtirib olishimiz kerak. Shu holatda nima qilish kerak?

Tezlik yoki samaradorlik unchalik muhim bo'lmagan joyda kerakli qo'shimcha ma'lumotni Restful API yordamida so'rab olsa ham bo'ladi. Agar turli xil microservicelarga bir qancha so'rov yuborib, bitta natija olish kerak bo'ladigan holatda **API Gateway*** patternidan foydalaniladi.

**Polyglot persistence*** muhitlarida ortiqcha ish qilish zarar qilmaydi. Misol uchun, har safar biror o'zgarish bo'lganida bu haqida boshqa microservicelarga messaging queue yordamida xabar berish mumkin. Bitta microserviceda o'zgarish sodir bo'lganida, boshqa microservicelar kerakli eventni kuzatib turadi va o'zgargan ma'lumotlarni o'zida ham qayd qilib qo'yadi. Shunday qilib, microservicelarga so'rov yuborish o'rniga barcha kerakli ma'lumotlarni kerakli microservicelar uchun bitta maxsus joyda saqlab qo'ysak bo'ladi.

Shuningdek, ma'lumotlarni keshlashni ham unutmaslik kerak. Databasega tez-tez so'rov yuborishni oldini olish uchun Redis yoki Memcached keshlash texnologiyalaridan foydalanish mumkin.

**API GateWay**

Faraz qilaylik, microservice arxitekturasi asosida ishlaydigan onlayn do'kon dasturini yaratyapmiz. Dasturdagi product details sahifasini (mahsulot ma'lumotlari sahifasi) yaratish joyiga kelib qolganmiz. Mahsulot ma'lumotlari sahifasining turlicha ko'rinishini yatishimiz zarur bo'lyapti:

* HTML5/JavaScriptga asoslangan desktop va mobil browserlar uchun UI. HTML serverdagi web-dastur tomonidan generatsiya qilib beriladi.
* Android va iPhonelar uchun klent dasturlar. Bu dasturlar backend bilan REST APIlar yordamida aloqa qiladi.

Bundan tashqari, onlayn do'kon biror mahsuloti haqidagi ma'lumotlardan boshqa tashqi dasturlar foydalanishi uchun REST API ham chiqarib berishimiz kerak.

Mahsulot ma'lumotlari sahifasi mahsulot haqida ko'plab ma'lumotlarni ko'rsatishi kerak. Masalan, Amazon.com saytining POJOs in Action ma'lumot sahifasi quyidagi ma'lumotlarni ko'rsatadi:

* Kitob haqidagi kitob nomi, muallifi, narxi va hokazo kabi asosiy ma'lumotlar.
* Kitobga aloqador bo'lgan xaridlaringiz.
* Do'konda mavjudligi.
* Ushbu kitob bilan birgalikda tez-tez xarid qilingan boshqa mahsulotlar
* Xarid qilish usullari
* Ushbu kitobni sotib olgan boshqa xaridorlaning sotib olgan boshqa mahsulotlari.
* Xaridorlarning kitob haqidagi izohlari.
* Sotuvchining reytingi.
* ...

Bu onlayn do'kon microservice arxitekturasi asosida yaratilgani uchun mahsulot ma'lumotlari turli xil servicelarga ajralgan holda ishlaydi. Misol uchun:

* Product Info Service - mahsulot haqidagi nomi, muallifi kabi asosiy ma'lumotlar.
* Pricing Service - mahsulot narxi.
* Order Service - mahsulot xaridi tarixi
* Inventory Service - mahsulotning sotuvda mavjudligi
* Review Service - xaridorlarning izohlari.

Shunday qilib, bu sahifa barcha ma'lumotlarini turli xildagi servicelardan olib keladi.

**Muammo**

Kliyentlar (UI dasturlar) qanday qilib har bir microservicega murojaat qiladi?


* Microservicelar tomonidan chiqarib berilgan APIlarning turli xil microservicelarda joylashganligi kliyentga ko'p hollarda kerak bo'ladigan ko'rinishdan ko'ra boshqacha bo'ladi. Microservicelar odatda faqat kliyentlar ma'lumotlarni olishlari uchun alohida-alohida APIlarni chiqarib beradi xolos. Misol uchun, yuqorida aytilganidek, kliyent mahsulot ma'lumotlarini turlicha microservicelardan oladi.
* Har xil kliyentlar har xil ma'lumotni so'raydi. Masalan, desktop browserdagi mahsulot ma'lumotlari sahifasi mobil versiyanikidan ko'ra ancha murakkabroq bo'ladi.
* Har xil kliyentlar uchun internet (yoki umuman tarmoq) tezligi har xil bo'ladi. Masalan, mobil internet boshqa internet turlariga qaraganda odatda sekonroq ishlaydi. Va albatta,
