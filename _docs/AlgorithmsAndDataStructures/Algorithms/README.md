# Algoritm


Algoritm – bu muammoni hal qilish uchun aniq ketma-ketlikda berilgan ko'rsatmalar to'plami.

Bitta muammoga bir nechta yechim – algoritm berilishi mumkin. Bu algoritmlar orasidan eng yaxshisini tanlay bilish kerak bo'ladi.

Yaxshi algoritm belgilari:

* Kirish (muammo) va chiqish (yechim) aniq berilgan bo'lishi kerak. Masalan, agar biz sonning kvadratini hisoblovchi algoritm tuzsak, bunda doimo kirish sifatida 2 sonini bersak, algoritm bizga 4 sonini berishi kerak. Ya'ni, algoritm doimo bitta kirishga bitta aniq o'zgarmas chiqishga ega bo'lishi kerak.
* Algoritmning har bir bosqichi aniq va ravshan bo'lishi kerak.
* Algoritm muammoni hal qilishning turli usullari orasida eng samaralisi bo'lishi kerak.
* Algoritm kompyuter kodi bo'lmasligi kerak. Buning o'rniga algoritm turli xil dasturlash tillarida ishlatilishi mumkin bo'lishi kerak (universal ko'rinishda)


Misollar

Foydalanuvchi kiritgan ikki sonni qo'shish algoritmi.

1. Start
2. son1, son2 va summa o’zgaruvchilarni yaratamiz
3. son1 va son2 qiymatlarni qabul qilamiz
4. son1 va son2 larni qo'shamiz, natijani summa ga yuklaymiz: summa=son1+son2
5. summa ni foydalanuvchiga qaytaramiz
6. stop

Uchta sondan eng kattasini topish algoritmi:


1. start
2. a, b va c o'zgaruvchilarni yaratamiz
3. a, b va c ga qiymatlar beramiz
4. agar a > b bo'lsa

   1. agar a > c bo'lsa
      1. a eng katta son deb qaytaramiz
   2. aks holda
      1. c eng katta son deb qaytramiz
5. aks holda (ya'ni a < b) bo'lsa

   1. agar b>c bo'lsa
      1. b eng katta son deb qaytaramiz
   2. aks holda
      1. c eng katta son deb qaytar

N faktorialni hisoblash algoritmi:


1. start
2. N, factorial va i o'zgaruvchilarini yaratamiz
3. Factorial va i ga 1 qiymatni yuklaymiz
4. Foydalanuvchidan N qiymatini qabul qilamiz
5. i=N bo'lguncha quyidagi qadamlarni
   takrorlaymiz:
   1. factorial=factorial*i
   2. i=i+1
3. factorial qiymatni qaytar
