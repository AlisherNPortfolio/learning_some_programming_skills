# Queue

Project qilish paytida bajarilishiga odatdagi so'rov bajarilishiga ketadiganidan ko'ra ko'proq vaqt talab qiladigan ishlar, misol uchun CSV faylni yuklab, uni pars qilib, serverda saqlash, ko'p uchrab turadi. Bu muammolar Laravelda hal qilingan. Bajarilishini uzoq vaqt kutish kerak bo'lgan ish/jarayonlarni job (biror ish) ko'rinishida yaratib, uni queue-ga berib qo'yiladi. Job-lar navbat bilan queue-dan olinib, background-da bajarib ketilaveradi.

Laravel queue-lar turlicha vositalar bilan bajarilishi mumkin. Misol uchun, Amazon SQS, Redis yoki relation database-ni laravel queue uchun ishlatsak bo'ladi.

Laravelda queue-ning barcha sozlamalari `config/queue.php` faylida saqlanadi. Bu faylda queue-ning vosita muhitlarga (m: Redisga) ulanish sozlamalari bo'ladi.
