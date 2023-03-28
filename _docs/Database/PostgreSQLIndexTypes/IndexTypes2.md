# PostgreSQL index turlari

**Kirish**

PostgreSQLda index turlari 6 ga bo'lingan: B-Tree, Has, GIN, BRIN, SP-GiST va GiST indexlar. Har bir index so'rovdan turlicha saqlash strukturasi va algoritm yordamida ma'lumotlarni oladi. PostgreSQLda indexlar jadvaldan ma'lumotlarni eng tez olish usuli hisoblanadi. Index yaratish uchun `CREATE INDEX` ifodasidan foylaniladi. Bunda, ifoda davomidan qaysi index turini yaratayotganimizni ko'rsatib ketishimiz kerak bo'ladi. Agar, index turini ko'rsatib qo'ymagan bo'lsak, PostgreSQL standart B-Tree indexni yaratib ketadi.

Index yaratish sintaksisi quyidagicha bo'ladi:

```pgsql
CREATE INDEX (index_nomi) on (jadval_nomi) USING (index_turi) (jadval_ustuni_nomi)
```

yoki

```pgsql
CREATE INDEX (index_nomi) on (jadval_nomi) (ustun_nomi1, ustun_nomi2, ustun_nomi3)
```


**Index turlari**

PostgreSQLda indexlar jadvaldan ma'lumotlarni tezroq olish uchun ishlatiladi. Indexlarning o'zi xuddi kitobdagi mundarija bilan bir xilda ishlaydi. PostgreSQL index select so'rovining tezligini oshirish uchun ishlatiladi. Shu bilan birga indexlar `where` qismida ma'lumotlarni filterlab olish uchun ham foydalaniladi. Lekin, indexlar ma'lumotlarni yozishda va yangilashda ishni sekinlashishiga sabab bo'ladi.

PostgreSQLda quyidagicha index turlari mavjud:

* B-Tree index
* Hash index
* Space partitioned GiST index (SP-GiST)
* Block Range Index (BRIN)
* Generalized inverted index (GIN)
* Generalized inverted search tree index (GiST)



1. **B-tree index**

B-tree index tartiblangan ma'lumotlar bilan ishlovchi o'zini o'zi balansaydigan daraxt sifatida e'lon qiladi. Bu esa unga ma'lumot qo'shish, o'chirish va ma'lumotlarni olish imkonini beradi. So'rovda <=, =, <, >=, IN, Between, IS NOT NULL, IS NULL kabi taqqoslash operatorlari qatnashganda query planner B-Tree indexini ko'rib chiqadi.

Agar PostgreSQLda pattern o'zgarmas bo'lsa, query planner `like` va `~` operatorlarini ham tekshiradi. B-tree index sintaksisi:

```pgsql
CREATE INDEX btree_idx on test_idx USING BTREE (id);
```

Yuqoridagi kodda `test_idx` jadvalidagi `id` ustuni uchun index yaratdik. Shuningdek, yangi yaratilgan indexga `btree_idx` nomini berdik.

2. **Hash index**

Hash index faqat tenglikni solishtirish operatori (=) bilan ishlaydi. Hash index transaction uchun xavli hisoblanadi, u oqim (streaming) yoki faylga asoslangan replikatsiyada replikatsiya bo'lmaydi.

Hash index yaratish:

```pgsql
CREATE INDEX hash_idx on test_idx USING HASH (student_id);
```

Yuqoridagi kodda `test_idx` jadvalining `student_id` ustuni uchun hash index yaratdik. Hash indexga `hash_idx` deb nom berdik.

3. **GIN index**

GIN indexdan jadval ustunida bittadan ko'p qiymatlarni saqlagan bo'lsak foydalanamiz. Bittadan ko'p qiymatga misol qilib array, jsonb va range turlarini keltirish mumkin.

GIN index yaratish:

```pgsql
CREATE INDEX GIN_idx1 ON student USING GIN (to_tsvector('english', student_name));
```

Yuqoridagi kodda, `student` jadvalidagi `student_name` ustuni uchun GIN index yaratdik. Bu indexga `GIN_idx1` deb nom berdik.

4. **GiST index**

PostgreSQLda GiST index yordamida umumiy daraxt strukturasini (general tree structure) qurish mumkin. GiST indexi PostgreSQLda geometrik ma'lumot turlari va to'liq ma'lumot qidirish (full data search) foydali hisoblanadi. GiST index bir nechta tugun qiymatlardan tashkil topgan bo'ladi. GiST indexning tuguni daraxt ko'rinishida quriladi.

GiST index yaratish:

```pgsql
CREATE INDEX gist_idx_test ON GIST_IDX USING gist(circle_dim);
```

Yuqoridagi kodda, `GIST_IDX` jadvalidagi `circle_dim` ustuni uchun `gist_idx_test` nomli GiST index yaratdik.

5. **SP-GiST index**

SP-GiST indexi bo'laklarga ajratilgan qidiruv daraxti bilan ishlaydi. Bu index tabiiy klasterlash elementi bilan ishlashda juda foydali hisoblanadi.

SP-GiST index yaratish:

```pgsql
CREATE INDEX spgist_idx ON spgist_table USING SPGiST (phone_number);
```

Yuqoridagi kodda, `spgist_table` jadvalining `phone_number` ustuni uchun `spgist_idx` nomi SP-GiST index yaratilyapti.

6. **BRIN index**

B-Tree bilan taqqoslaganda ancha kichik bo'lib, samaradorligi ham ancha yuqori hisoblanadi. BRIN indexidan gorizontal qismlarga ajratmasdan turib B-Tree indexini ishlatib bo'lmaydigan katta jadvallar bilan ishlashda foydalaniladi.

BRIN index yaratish:

```pgsql
CREATE INDEX brin_idx ON test_idx USING BRIN(phone);
```
