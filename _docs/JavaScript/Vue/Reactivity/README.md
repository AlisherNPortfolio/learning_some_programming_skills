# Reaktivlik asoslari (Composition API)

**Reaktiv o'zgaruvchi e'lon qilish**

Vue 3 composition APIda reaktiv obyekt yoki arrayni `reactive()` funksiyasi yordamida e'lon qilishimiz mumkin:

```js
import { reactive } from 'vue';

const state = reactive({ count: 0 });
```

Reaktiv obyektlar JavaScriptdagi proxy obyekti hisoblanib, o'zini oddiy obyekt sifatida namoyon qiladi. Farqi, Vue reaktiv obyektning xususiyatlariga murojaat qilishni va ularni o'zgartirishni boshqara oladi.

Reaktiv o'zgaruvchilarni templateda foydalanish uchun ularni `setup()` funksiyasi ichida e'lon qilib, natija sifatida qaytarib qo'yish kerak bo'ladi:

```js
import { reactive } from 'vue';

export default {
  setup() {
    const state = reactive({ count: 0 });

    return {
      state
    }
  }
}
```

```html
<div>{{ state.count }}</div>
```

Xuddi shunga o'xshab, metodlarni ham e'lon qilib, ularni ham natija ichida qaytaramiz:

```js
import { reactive } from 'vue';

export default {
  setup() {
    const state = reactive({ count: 0 });

    function increment() {
      state.count++;
    }

    return {
      state,
      increment
    }
  }
}
```

`setup()` funksiyasidan natija qilib qaytarilgan funksiyalar odatda event listener sifatida ishlatiladi:

```html
<button @click="increment">
  {{ state.count }}
</button>
```


`b`

`setup()` funskiyasida reaktiv o'zgaruvchi va metodlarni natija qilib qaytarish ortiqcha ishday bo'lib ko'rinishi mumkin. Lekin, bu funksiya faqat build qadami ishlatilmaganida zarur bo'ladi. Single-File Componentlardan (SFCs) foydalanganda bu funksiyani ishlatmasdan shunchaki `<script>` tegida `setup` atributini ishlatsak yetarli:

```html
<script setup>
import { reactive } from 'vue';

const state = reactive({ count: 0 });

function increment() {
  state.count++;
}
</script>
<template>
  <button @click="increment">
    {{ state.count }}
  </button>
</template>
```


**DOMni yangilash vaqti**

Reaktiv o'zgaruvchining qiyamatini o'zgartirganimizda, DOM avtomatik yangilanadi. Biroq, DOMni yangilash sinxron holda yangilanmasligini yodda saqlash zarur. Buning o'rniga, Vue yangilanishlarni o'zgaruvchini necha marta o'zgatirishingizdan qa'tiy nazar har bir komponent faqat bir marta yangilanishini ta'minlash maqsadida yangilanish siklidagi keyingi o'zgarishgacha ("next tick"gacha) buferga saqlab qo'yadi.

O'zgaruvchi qiymati o'zgarganidan keyin  DOM yangilanishini kutishda `nextTick()` global APIsidan foydalanish mumkin:

```js
import { nextTick } from 'vue'

function increment() {
  state.count++
  nextTick(() => {
    // yangilangan DOM bilan ishlasa bo'ladi
  })
}
```


**Chuqur reaktivlik**

Vue-da o'zgaruvchi odatiy holda chuqur reaktiv hisoblanadi. Bu obyekt xususiyatlarini yoki array elementlarini o'zgartirganda ham reaktivlik ishlashini anglatadi:

```js
import { reactive } from 'vue'

const obj = reactive({
  nested: { count: 0 },
  arr: ['foo', 'bar']
})

function mutateDeeply() {
  // odatdagiday ishlayveradi
  obj.nested.count++
  obj.arr.push('baz')
}

```

Bundan tashqari, yuzaki reaktiv obyektlarni ham yaratish mumkin. Yuzaki reaktiv obyektlarda reaktivlik faqat o'zgaruvchining to'liq o'zini o'zgartirganda ishlaydi. 


**Reaktiv Proxy va Orginal**

`reactive()` funksiyasidan qaytgan qiymat orginal obyektning Proxy-i ekanligini unutmaslik kerak. Bunda bu qiymat orginal obyektga teng bo'lmaydi:

```js
const raw = {}
const proxy = reactive(raw)

// proxy orginal raw obyektiga teng emas.
console.log(proxy === raw) // false

```

Bu yerda faqat proxy reaktiv hisoblanadi. Orginal obyektni o'zgartirish hech qanday yangilanishlarga sabab bo'lmaydi. Shuning uchun ham vuening reaktiv tizimidan foydalanishning eng yaxshi yo'li - bu o'zgaruvchining proxy qilingan ko'rinishidan foydalanish.

`reactive()` funksiyada orginal obyektni chaqirish shu obyektning proxyining aynan o'zini qaytarib beradi. Shuningdek, `reactive()` funksiyada proxyni chaqirish, yana shu proxyning o'zini beradi:

```js
// reactive() da raw obyektini chaqirish shu obyektning proxyining o'zini beradi
console.log(reactive(raw) === proxy) // true

// reactive() da proxyni chaqirish ham xuddi shu proxyni qaytaradi
console.log(reactive(proxy) === proxy) // true

```

Bu qoida ichma-ich obyektlar uchun ham ishlaydi. Chuqur reaktivlik sababli, reaktiv obyekt ichidagi ichma-ich obyektlar ham proxy hisoblanadi:

```js
const proxy = reactive({})

const raw = {}
proxy.nested = raw

console.log(proxy.nested === raw) // false

```


`b` **funksiyasidagi cheklovlar**

`reactive()` APIda ikkita cheklov mavjud:

1. U faqat obyekt tiplari (obyektlar, arraylar hamda Map va Set kabi collection tiplar) uchungina ishlaydi. `reactive()` `string`, `number`, yoki `boolean` kabi sodda tiplar bilan ishlamaydi.
2. Vuening reaktivligi xususiyat qiymatini kuzatib turgani uchun, reaktiv obyekt uchun doimo bir xildagi referenceni saqlab turishimiz kerak bo'ladi. Bu esa birinchi elementga bo'lgan reaktiv bog'lanish yo'qotilishi sababli reaktiv obyektlarni osongina o'zgartira olmasligimizni anglatadi:

```js
let state = reactive({ count: 0 })

// yuqoridagi qiymat ({ count: 0 })ni endi kuzatib bo'lmaydi (reaktiv bog'lanish yo'qoldi!)
state = reactive({ count: 1 })

```

Bu yana reaktiv obyekt xususiyatlarini lokal o'zgaruvchilarga o'zlashtirganimizda yoki ajratib olganimizda (destructure qilganimizda), yoki o'sha xususiyatni funksiyaga argument qilib uzatganimizda reaktivlikni yo'qolishini ham anglatadi:

```js
const state = reactive({ count: 0 })

// n state.count dan reaktivligi uzilgan lokal o'zgaruvchi
let n = state.count
// quyidagi o'zgartirish orginal o'zgaruvchiga ta'sir qilmaydi
n++

// quyidagi count ham state.count dan uzilib qoldi.
let { count } = state
// quyidagi o'zgartirish ham orginal o'zgaruvchiga ta'sir qilmaydi
count++

// funksiya oddiy son qabul qiladi va
// state.count da bo'lgan o'zgarishlarni sezmaydi
callSomeFunction(state.count)

```

**`ref()` bilan e'lon qilingan reaktiv o'zgaruvchilar**

`reactive()` dagi cheklovlarni hisobga olib, Vue `ref()` funksiyasiga ham ega. Bu funksiya har qanday tipdagi qiymatlarni qabul qiluvchi reaktiv "refs"ni yaratib beradi:

```js
import { ref } from 'vue'

const count = ref(0)

```

`ref()` biror qiymatni argument sifatida qabul qilib, uni `.value` xususiyatiga ega bo'lgan ref obyektga kiritib beradi:

```js
const count = ref(0)

console.log(count) // { value: 0 }
console.log(count.value) // 0

count.value++
console.log(count.value) // 1

```

Reaktiv obyektdagi xususiyatlarga o'xshab, ref-ning `.value` xususiyati ham reaktiv hisoblanadi. Bundan tashqari, ref obyekt tipini qabul qilganida o'zining `.value` xususiyatini `reactive()` bilan almashtiradi.

Obyekt qiymatini o'zida saqlovchi ref butun obyektni reaktiv ko'rinishda almashtiradi:

```js
const objectRef = ref({ count: 0 })

// quyidagi kod ham reaktiv holatda ishlaydi
objectRef.value = { count: 1 }

```

Reflar reaktivligini yo'qotmasdan funksiyaga argument sifatida uzatilishi yoki o'zgaruvchilarga ajratib olinishi (destructured qilinishi) mumkin:

```js
const obj = {
  foo: ref(1),
  bar: ref(2)
}

// quyidagi funksiya refni qabul qiladi
// qabul qilgan argumentining qiymatiga .value orqali murojaat qiladi
// lekin reaktivlik saqlanib qoladi.
callSomeFunction(obj.foo)

// quyidagi holatda ham o'zgaruvchilar reaktiv holatda qoladi
const { foo, bar } = obj

```

Boshqacha aytganda, `ref()` har qanday tipdagi qiymat uchun reference (murojaat) yaratib, reaktivlikni saqlagan holda uni xohlagan joyiga uzata oladi. Bu xususiyat juda muhim hisoblanadi, chunki undan takrorlanuvchi kodlarni Composable Function-ga chiqarishda ko'p qo'llaniladi.


**Ref-ni templateda ishlatish**

Ref-ni teplateda ishlatganda Vuening o'zi uning qiymatini avtomatik `.value` xususiyatidan chiqarib oladi:

```js
<script setup>
import { ref } from 'vue'

const count = ref(0)

function increment() {
  count.value++
}
</script>

<template>
  <button @click="increment">
    {{ count }} <!-- .value ni ishlatish shart emas -->
  </button>
</template>

```

Lekin, templateda refning qiymatini avtomatik chiqarib olish xususiyati faqat o'zgaruvchining o'zi yuqori darajada bo'lgandagina ishlaydi. Misol uchun, quyidagi koddagi `object` o'zgaruvchisi yuqori darajada hisoblansa, `object.foo` obyektning ichida bo'lgani uchun yuqori daraja hisoblanmaydi.

Shunday qilib, quyidagi kodni misol sifatida ko'raylik:

```js
const object = { foo: ref(1) }
```

Ushbu ifoda kutilganidek ishlamaydi:

```html
{{ object.foo + 1 }}
```

Templateda render qilingan natija `[object Object]1` bo'ladi. Chunki, `object.foo` ref obyekti bo'lib hisoblanadi. Endi bu muammoni `foo`ni yuqori darajadagi xususiyat qilib hal qilamiz:

```js
const { foo } = object
```

```html
{{ foo + 1 }}
```

Ana endi, natija 2 chiqadi.



**Reaktiv obyektlarda refning qiymatini olish**

`ref`ni reaktiv obyektning xususiyati qilib berganda, undan qiymat olinsa yoki unga qiymat berilsa, ref xususiyat o'zgaruvchisi o'zini oddiy xususiyat kabi tutadi:

```js
const count = ref(0)
const state = reactive({
  count
})

console.log(state.count) // 0

state.count = 1
console.log(count.value) // 1

```

Agar reaktiv obyektning xususiyati ref turdagi o'zgaruvchi bo'lsa va unga boshqa yangi ref o'zgaruvchi berilsa, yangi ref o'zgaruvchi eskisining o'rnini egallaydi:

```js
const otherCount = ref(2)

state.count = otherCount
console.log(state.count) // 2
// endi original ref state.count dan uzildi.
console.log(count.value) // 1

```

Refning qiymatini avtomatik `.value`siz olish xususiyati faqat reaktiv obyekt chuqur reaktiv bo'lsagina ishlaydi. Yuzaki reaktiv obyektda bunday xususiyat ishlamaydi.



**Array va Collectionlarda refning qiymatini olish**

Reaktiv obyektlardan farqli, refning qiymatini `.value` xususiyatisiz olish ref reaktiv array va collection tiplarining elementi bo'lganida ishlamaydi:

```js
const books = reactive([ref('Vue 3 Guide')])
// qiymat .value bilan olinadi
console.log(books[0].value)

const map = reactive(new Map([['count', ref(0)]]))
// qiymat .value bilan olinadi
console.log(map.get('count').value)

```
