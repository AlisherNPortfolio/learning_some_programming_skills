# Proxy

`Proxy` obyekti boshqa bir obyektga proxy yaratish imkonini beradi. Bunda proxy yaratayotgan boshqa obyekt o'zida mavjud bo'lgan fundamental xususiyat va amallarni ishlashini to'xtatib turib uni qayta e'lon qilishi mumkin bo'ladi.

**Izoh**

`Proxy` obyekti original obyekt o'rnida ishlatsa bo'ladigan boshqa bir obyektni yaratib beradi. Yangi yaratilgan obyektda original obyektning xususiyat e'lon qilish, xususiyatlarni olish va ularga qiymat berish kabi fundamental  `Object` amallarini qayta e'lon qilish imkonini beradi. Poxy obyektlar odatda, xususiyatga murojaat qilish, validatsiyalash, formatlash yoki kiruvchi ma'lumotlarini tozalash kabilar uchun foydalaniladi.

Proxy ikkita parametr bilan yaratiladi:

* `target`: proxy-si yaratilayotgan orginal obyekt
* `handler`: qaysi amallar ushlab qolinishini va ushlab qolingan amallarni qanday qilib qayta e'lon qilinishini (qayta yozishini) belgilovchi obyekt.

Misol uchun, quyidagi kod `target` obyekt uchun proxy yaratib beradi:

```js
const target = {
  message1: "hello",
  message2: "everyone"
}

const handler1 = {};

const proxy1 = new Proxy(target, handler1);
```

Handler parametri bo'sh obyekt bo'lgani uchun, yuqorida yaratgan proxy-imiz xuddi orginal obyektday ishlaydi.

```js
console.log(proxy1.message1); // hello
console.log(proxy1.message2); // everyone
```

Proxyni o'zgartirish uchun handler obyektida funksiya e'lon qilib ko'raylik:

```js
const target = {
  message1: "hello",
  message2: "everyone"
}

const handler2 = {
  get(target, prop, receiver) {
    return "world";
  }
};

const proxy2 = new Proxy(target, handler2);
```

Endi, tepadagi kodda, target obyektning xususiyatlarini olib beruvchi `get()` handler funksiyasini ushlab qolib, uni o'zgartirdik.

Handler funksiyalarni `tarps` (tuzoqlar) ham deb atashadi. Bunday atalishiga uning target obyektga bo'lgan murojaatlarni ushlab qolishi sabab bo'lsa kerak. Endi `proxy2` obyekt xususiyatlarini chaqirib ko'raylik:

```js
console.log(proxy2.message1); // world
console.log(proxy2.message2); // world
```

Proxylar Reflect obyekti bilan tez-tez qo'llaniladi. Chunki, Reflect obyekti Proxyning trap funksiyalari nomlari bilan bir xildagi metodlarga ega. Reflectning metodlari o'z nomiga mos kelgan obyektning ichki metodlarini ishga tushirib beradi. Ya'ni, Reflect obyekti qaysi obyekt ichida ishlatilsa, o'zini xuddi shu obyektday tutadi. Misol uchun, agar obyektni o'zgartirib ishlatishni xohlamasak, `Reflect.get` metodini chaqirib ishlatishimiz mumkin:

```js
const target = {
  message1: "hello",
  message2: "everyone"
}

const handler3 = {
  get(target, prop, receiver) {
    if (prop === "message2") {
      return "world";
    }

    return Reflect.get(...arguments);
  }
};

const proxy3 = new Proxy(target, handler3);

console.log(proxy3.message1); // hello
console.log(proxy3.message2); // world
```
