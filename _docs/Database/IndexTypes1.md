# PostgreSQL Index Turlari

PostgreSQLda **B-tree**, **Hash**, **GiST**, **SP-GiST** va **BRIN** kabi bir qancha index turlari mavjud. Har bir indeks turli xildagi so'rovlar uchun turli xildagi saqlash strukturasi va algoritmdan foydalanadi.

Index turini ko'rsatmasdan yozilgan `CREATE INDEX` ifodasi standard holatda B-tree index turini ishlatib ketadi. Bunga sabab esa, bu turdagi index turi ko'pchilik umumiy so'rovlarga juda mos keladi.

**B-Tree indexlar**

B-tree - bu tartiblangan ma'lumotlarni saqlaydigan va logarifmik vaqt ichida qidirish, qo'shish, o'chirish va ketma-ket murojaat qilishga imkon beradigan o'zini o'zi balanslovchi tree (daraxt).

PostgreSQL query planneri (so'rov rejalashtiruvchisi) quyidagi operatorlardan birini ishlatib taqqoslashda qatnashgan index ustunlarni B-tree indexidan foydalanib ko'rib chiqadi:

```pgsql
<
<=
=
>=
BETWEEN
IN
IS NULL
IS NOT NULL
```

Bundan tashqari, agar pattern o'zgarmas va patternnig boshlanishida anchor qatnashgan bo'lsa, query planner `LIKE` va `~` kabi solishtirish patterni operatori qatnashgan so'rovlar uchun B-tree indexidan foydalanadi. Misol uchun:

```pgsql
ustun_nomi LIKE 'foo%' 
ustun_nomi LKE 'bar%' 
ustun_nomi  ~ '^foo'
```

Shu bilan birga, agar pattern yuqori/quyi registrga o'tishiga ta'sir qilmaydigan\, alifbo harfi bo'lmagan belgilar bilan boshlanadigan pattern bo'lsa, query planner `ILIKE` va `~*` operatorlar uchun B-tree indexlaridan foydalani ko'rib chiqadi.

Agar PostgreSQLdagi ma'lumotlar omborini optimizatsiya qilishni boshlagan bo'lsangiz, B-tree sizga eng mos tanlov bo'lishi mumkin.

**Hash indexlar**

Hash indexlar faqat tenglikni tekshirish operatori (=) bilan ishlaydi. Bu esa tenglik operatoridan foydalanib taqqoslash amalga oshirilayotgan indexlangan ustunlar uchun query planner doim hash indexdan foydalanadi.

Hash index yaratish uchun so'rovning `USING` qismida  `CREATE INDEX` ifodasini `HASH` index turi bilan ishlatiladi:

```pgsql
CREATE INDEX index_nomi 
ON jadval_nomi USING HASH (indexlanayotgan_ustun);
```

**GIN indexlari**

GINning kengaytmasi **g**eneralized **in**verted indexes bo'lib, tarjimasi umumlashtirilib teskari qilingan indexlar degani. Odatda, GIN deb ataladi.

GIN indexlar bitta ustunda bir nechta qiymat saqlash kerak bo'lganda ishlatiladi. Misol uchun, bitta ustunda hstore, array, jsonb va oraliq turlari (range types) saqlanishi mumkin.

**BRIN**

BRINning kengaytmasi block range indexes, ya'ni blok diapazoni indekslari bo'ladi. BRIN B-tree bilan solishtirganda ancha kichikroq va ishlashi bo'yicha samaraliroq hisoblanadi.

BRIN gorizontal bo'lmasdan turib B-treedan foydalanib bo'lmaydigan katta jadvallarda index ishlatish imkonini beradi.

BRIN ko'pincha chiziqli saralashga ega bo'lgan ustun uchun qo'llaniladi. Misol uchun, buyurtmalar (orders) jadvalining buyurtma yaratilgan sana (created_date) ustunida ishlatish mumkin.



**GiST Indexlar**

GiST ning kengaytmasi Generalized Search Tree (umumlashtirilgan qidiruv daraxti) deyiladi. GiST indexlar umumiy daraxt tuzilmalarini qurish imkonini beradi. GiST indexlar geometrik ma'lumot turlari va full-text search bilan ishlaganda foydali hisoblanadi.


**SP-GiST indexlar**

SP-GiST ning kengaytmasi space-partitioned GiST (joy bo'yicha qismlarga ajratilgan GiST). SP-GiST turli xildagi balanslanmagan ma'lumot tuzilmalarini ishlab chiqishda foydali bo'lgan qismlarga ajratilgan qidiruv daraxtida ishlatiladi.

SP-GiST indexlar tabiiy klasterlanadigan elementlarga ega ma'lumotlar uchun, shuningdek teng balanslanmagan daraxt uchun juda foydali hisoblanadi. Bunday ma'lumotlarga misol qiliob, GIS, multimedia, telefonlarni yo'naltirish va IP routinglarni keltirish mumkin.
