# Laravel Docker

Docker - bu kompyuter ichida ishlovchi  virtual kompyuter. Docker-da biror bir umumiy maqsadda ishlatilinadigan dasturlar uchun muhit yaratish mumkin. Misol uchun, oddiy PHP dasturlash tilida qilingan project uchun web server ko'tarishimiz mumkin. Bunda, project uchun kerak bo'ladigan dasturlar - php interpretator, web server, ma'lumotlar bazasi kabi dasturlar shu virtual kompyuterda o'rnatilgan bo'ladi. Docker yordamida har bir project uchun alohida bir nechta virtual kompyuter yaratishimiz mumkin. Bu virtual kompyuterlar bir biridan mustaqil ravishda, bir biriga xalaqit bermagan holda ishlaydi, chunki ular alohida izolyatsiyalangan holda ko'tarilgan bo'ladi.

> Virtual kompyuter (mashina) resurslarni (RAM, CPU kabilarni) ishlab turgan kompyuterning hardware-idan oladi.

Docker virtual mashinasida ishlatiladigan dasturlar docker image deyiladi. Ularni docker hub-dan (hub.docker.com saytidan) olish mumkin.

Yuqorida aytilgan virtual mashina - bu docker container hisoblanadi va bir nechta docker container-lar bir biridan izolyatsiyalangan bo'ladi. Docker container yaratishda ishchi muhit uchun kerak bo'ladigan dasturlar (masalan, php interpretator, MySQL yoki PostgreSQL MBBT, nginx web server) alohida o'rnatilishi mumkin.
