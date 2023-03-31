**CGI, FastCGI**

CGI (Common Gateway Interface) web texnologiya va protokol bo'lib, web serverni (HTTP server) tashqi dasturlar (masalan, PHP) bilan aloqa qilishini ta'minlaydi. CGI web serverning dinamik kontent yaratish va u bilan ishlash imkoniyatini kuchaytiradi.

CGI interfeys sifatida web server va qo'shimcha o'rnatilgan dinamik kontent yaratuvchi dasturlar orasida ishlaydi. Bu dasturlar CGI skriptlar deb nomlanib, PHP, Perl, Python kabi dasturlash tillari va turli xildagi skriptlarda yozilgan bo'ladi.

**cgi-bin papkasi**

Web serverdan browserga uzatiladigan odatiy web sahifalar, fayllar va barcha dokumentlar home/user/public_html kabi public papkada saqlanadi. Browser biror kontentni so'rab web serverga murojaat qilganida server shu papkani tekshirib, so'ralgan faylni browserga jo'natadi.

Agar CGI serverda o'rnatilgan bo'lsa, cgi-bin papkasi ham o'sha yerga qo'shilgan bo'ladi. Misol uchun, home/user/public_html/cgi-bin. CGI skriptlari shu papkada saqlanadi. Bu papkadagi har bir fayl ishga tushiriluvchi (executable) dastur sifatida ko'riladi. Papkadagi biror faylga murojaat qilingan paytda server faylni o'zini browserga berib yuborish o'rniga skriptga javobgar dasturga so'rov yuboradi. Kiruvchi ma'lumotlar bilan ishlash yakunlanganidan so'ng, skriptga javobgar dastur chiquvchi ma'lumotlarni web serverga qaytaradi. O'z navbatida web server ham natijani HTTP kliyentga beradi.

Misol uchun, http://birorsayt.uz/cgi-bin/file.php CGI skriptiga murojaat qilinganda, server CGI orqali shu skriptni ishlatuvchi PHP dasturni ishga tushiradi. Skriptni ishga tushirgandan keyin olingan ma'lumot dastur tomonidan natija sifatida qaytib web serverga beriladi. Server esa bu natijani browserga uzatadi. Agar serverda CGI bo'lmaganida, browser .php faylda turgan kodni ko'rsatgan bo'lar edi.

**FastCGI**

FastCGI yangiroq web texnologiya bo'lib, CGIning takomillashtirilgan ko'rinishi hisoblanadi. Asosiy vazifasi esa bir xilda qolgan.

CGI va FastCGI haqida ma'lumotlar (ikkalasi orasidagi farqni bilish uchun).

1. **CGI**

CGI har bir HTTP so'rov so'ragan skript bilan ishlovchi dasturni ishga tushiradi. Dastur ishini yakunlab, chiquvchi natijani CGIga qaytargach, CGI dasturni to'xtatadi. Keyingi so'rov kelganda CGI yana dasturni qaytadan ishga tushiradi.

Jarayon qanchalik ko'p yuz bersa, protsessorning hisoblash quvvati yoki RAM xotira kabi resurslar ko'proq sarflanadi. Web sahifaning yuklanish vaqti nafaqat serverning yuklanish vaqtiga, balki dasturning (masalan, PHPning) yuklanish vaqtiga ham bog'liq bo'ladi.

2. **FastCGI**

CGI va FastCGIning farqi FastCGIda ishlab turgan dasturning ishlab turish vaqti uzoqroq bo'lib, darrov to'xtatib qo'yilmaydi. Dastur yakunlanib, natija qaytarilganidan so'ng, jarayon to'xtatilmasdan, keyingi so'rovlar uchun saqlab turiladi. Bu serverning yuklanishini va yuklanish vaqtini kamaytiradi.

Yana bitta farqi, FastCGI remote serverda ham ishlatish mumkin.

CGI dasturlarning asosiy funksiyasi HTTP so'rov ma'lumotlari bilan ishlab, HTTP javob qaytaradi. Bu FastCGIning "Responder" deb nomlangan vazifasi hisoblanadi. Bundan tashqari, dastur Authorizer va Filter vazifalarini ham bajaradi.

**PHP-FPM nima?**

FPMning kengaytmasi FastCGI Process Manager bo'lib, u PHP FastCGIning eng mashhur muqobil ko'rinishi hisoblanadi. PHP web dasturlashda ishlatiladigan eng mashhur dasturlash tillaridan biri hisoblanadi.

PHP HTML kodlarni ham yozish imkoniyati mavjud bo'lgan birinchi dasturlash tillaridan biri. Bu til kros-platform til bo'lib, asosiy ishlatiladigan barcha operatsion tizimlarda ishlaydi.

PHP-FPM PHP FastCGIning eng mashhur muqobili hisoblanadi. Unda yuqori yuklanishli saytlar bilan ishlash uchun qo'shimcha foydali xususiyatlarga ega. Quyidagicha xususiyatlar mavjud:

* Jarayonlarni to'xtatish/boshlashni oson ishlatishni boshqarishning takomillashtirilgan ko'rinishi.
* Workerlarni turli xildagi uid/gid/chroot/environment va turlicha php.ini bilan ishlash imkoniyati. safe_mode bilan almashtiriladi.
* Stdout va stderr larni loglash
* Opcode kesh to'xtab qolganida qayta ishga tushirib yuborish.
* Yuklashlarning (uploads) kuchaytirilgan qo'llab quvvatlanishi.
* Slowlog o'zgaruvchi sozlamalari. odatdagi ishlashdan ko'ra ko'proq vaqt sarflagan funksiyalarni aniqlash.
* php.ini sozlamalar fayliga asosan ishlash.
* FastCGI yaxshilanishi, fastcgi_finish_request(). Video konvertlash yoki statistikani chiqarish kabi uzoq vaqt talab qiladigan ishlarni bajarish davomida barcha ma'lumotlarni yuklab olish va to'xtatish funksiyasi.
* Asosiy statistikalar (Apachening mod_status moduliga o'xshash). **Yangi**!

**Nginx va PHP-FPM - mukammal juftlik**

Nginx stabil yuqori samaradorli web server bo'lib, resurslarni juda kam sarf qiladi. U PHP-FPM bilan mukammal ishlay oladi. Nginx asinxron arxitekturaga ega bo'lib, ancha kengayuvchan hisoblanadi. Shuningdek, Nginxni PHP-FPM bilan ishlatganda, xotiradan foydalanish darajasi bo'yicha samaradorlik sezilarli oshadi.

PHP-FPMdan foydalangan paytda PHP alohida service sifatida ishlaydi. PHPni interpretator tili sifatida foydalanish orqali so'rovlar TCP/IP socket orqali ishlatiladi. Shu sababli, Nginx faqat HTTP so'rovlar bilan, PHP-FPM esa PHP kod bilan shug'ullanadi. Ikkita alohida service bo'lib ishlash samaradorlikni oshirishning asosi hisoblanadi.
