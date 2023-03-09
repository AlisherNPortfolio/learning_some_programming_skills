# DRY (Don't Repeat Yourself)

(Dasturchilar va testerlar uchun yaxshi maslahat)

DRY - bu dasturlash tamoyillaridan (programming principle) biri hisoblanib, refactoring qilish va keyinchalik support qilishni qiyinlashtiradigan kodning duplicationini (takrorlanishini) oldini olish uchun qo'llaniladi. Ya'ni, tushunarliroq qilib aytadigan bo'lsak, bir xil vazifa bajaradigan va bir xil ko'rinishda yozilgan bir nechta qator kodlarni projectda ishlatish DRY tamoyilini buzadi.

# Misol

Faraz qilaylik, oddiy PHP dastur yaratyapsiz. Bu dasturda 3ta php script fayllar bor bo'lsin: bosh sahifa - `index.php`, barcha foydalanuvchilarning ro'yxatini chiqaruvchi sahifa - `users.php` va faqat bitta foydalanuvchining ma'lumotlarini chiqaruvchi `user.php` fayli.

#### DRY asosida yozilMAgan kod

Bunday kod quyidagicha ko'rinishda bo'lishi mumkin

`index.php`:

```php
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bosh sahifa</title>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Bosh sahifa</a></li>
                <li><a href="users.php">Foydalanuvchilar</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h1>Bosh sahifa</h1>
    </section>
</body>

</html>
```

`user.php`:

```php
<?php
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $users = json_decode(file_get_contents('users.json'))->users;

    foreach ($users as $user) {
        if ($user->id == $_GET['id']) {
            $currentUser = $user;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($currentUser) ? $currentUser->username : 'User page'; ?></title>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Bosh sahifa</a></li>
                <li><a href="users.php">Foydalanuvchilar</a></li>
            </ul>
        </nav>
    </header>
    <section>
        <?php if (isset($currentUser)) : ?>
            <p>id: <?php echo $currentUser->id ?> - username: <?php echo $currentUser->username; ?></p>
        <?php else : ?>
            <p>User not found</p>
        <?php endif; ?>
    </section>
</body>

</html>
```

`users.json`:

```json
{
    "users": [
        {
            "id": 1,
            "username": "user 1"
        },
        {
            "id": 2,
            "username": "user 2"
        },
        {
            "id": 3,
            "username": "user 3"
        }
    ]
}

```

`users.php`:

```php
<?php
$users = json_decode(file_get_contents('users.json'))->users;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="idnex.php">Bosh sahifa</a></li>
                <li><a href="users.php">Foydalanuvchilar</a></li>
            </ul>
        </nav>
    </header>

    <section>
        <h1>Barch Foydalanuvchilar</h1>
        <?php if (isset($users)) : ?>
            <ul>
                <?php foreach ($users as $user) : ?>
                    <li>id: <?php echo $user->id; ?> - username: <?php echo $user->username; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </section>
</body>

</html>
```


`index.php` faylida menyu va section qismlari bor.

`users.php` faylida xuddi shunday kodlar va yana `users.json` faylidan olingan foydalanuvchilar ro'xyati mavjud. Bu ma'lumotlarni PHPdagi `file_get_contents()` funksiyasi yordamida olib, ularni `json_decode()` yordamida konvertlab olyapmiz. Oxirida ularni ro'yxat qilib chiqarib beryapmiz.

`user.php` da esa, faqat biror foydalanuvchining `id` sini yuborib, shu `id` bo'yicha ro'yxat ichidan olib, chiqarib beryapmiz.

#### Bu kodlarda DRYga mos kelmaydigan nima bor?

1. HTML taglar.

E'tibor bergan bo'lsangiz, barcha sahifalarda section qismidan tashqari qolgan barcha HTML taglar bir xil. Biz har safar yangi sahifa ochganda, shunchaki oldingisidan barcha HTML taglarni ko'chirib olib, keyin faqat section qismini o'zgartiryapmiz va biroz PHP kodlarni yozyapmiz.

2. Foydalanuvchilarning ma'lumotlarini olishda

Foydalanuvchilarning ma'lumotlarini doimo bir xil ko'rinishda - `json_decode(file_get_contents('users.json'))->users` deb olyapmiz (`users.php` va `user.php` fayllarida). Tasavvur qilaylik, agar `users.json` faylida `users` ni boshqa nomga o'zgartirsak, `users.json` faylidan ma'lumot olgan har bir php sahifamizda o'zgarish qilishimizga to'g'ri keladi. Haqiqiy PHP projectlarda ma'lumotlarni databasedagi biror jadvaldan olamiz. Shu sababli, agar DRYga amal qilinmasa, query yozilgan har bir fayl va kodda berilgan jadval nomini o'zgartirib chiqishimiz kerak bo'ladi.

#### DRY asosida yozilgan kod

1. Umumiy template (qolip) yaratamiz.

Oldinroq aytganimizdek, sahifalarimizdagi section qismidan boshqa barcha HTML taglar bir xilda yozilyapti. Shuning uchun ham, o'zgarmaydigan qismlarni alohida fayllarga olib chiqib, ularni shunchaki require qilib qo'yamiz. Tepadagi misolda ko'radigan bo'lsak, `header.php` va `footer.php` fayllarini yaratib, takrorlanuvchi qismlarni shu fayllarga olib o'tamiz. Natijada, agar saytimizga yana bitta sahifa qo'shmoqchi bo'lsak, unga o'tuvchi menyuni `header.php`ga yozib qo'ysak yetarli.

2. Foydalanuvchi ma'lumotlarini olib beruvchi model yozamiz.

Buning uchun, projectimizning papkasida models nomli papkani yaratib, unda `userModel.php` nomli faylni ochamiz. Bu faylda `getAllUsers()` va `getUserById()` nomli ikkita funksiya yozamiz. Bu funksiyalar yordamida bir xildagi kodlarni qayta-qayta yozmasdan, xohlagan faylimizda foydalanuvchilar haqidagi ma'lumotlarni olishimiz mumkin bo'ladi.

DRY asosida yozilgan project fayllari.

`templates/footer.php`:

```php
</section>
</body>

</html>
```

`templates/header.php`:

```php
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bosh sahifa</title>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">Bosh sahifa</a></li>
                <li><a href="users.php">Foydalanuvchilar</a></li>
            </ul>
        </nav>
    </header>

    <section>
```

`views/index.php`

```php
<?php
$title = "Bosh sahifa";
?>

<?php require('../templates/header.php'); ?>

<h1>Bosh sahifa kontenti</h1>

<?php require('../templates/footer.php'); ?>
```

`views/user.php`:

```php
<?php

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    require('../models/userModel.php');
    $currentUser = getUserById($_GET['id']);
    $title = isset($currentUser) ? $currentUser->username : null;
}
?>

<?php require('../templates/header.php'); ?>

<?php if (isset($currentUser)) : ?>
    <p>id: <?php echo $currentUser->id; ?> - username: <?php echo $currentUser->username; ?></p>
<?php else : ?>
    <p>Foydalanuvchi topilmadi</p>

<?php endif; ?>

<?php require('../templates/footer.php'); ?>
```

`models/userModel.php`:

```php
<?php
function getAllUsers()
{
    return json_decode(file_get_contents('users.json'))->users;
}

function getUserById($userId)
{
    $allUsers = getAllUsers();
    foreach ($allUsers as $user) {
        if ($user->id == $userId) {
            return $user;
        }
    }

    return null;
}

```

`views/users.php`:

```php
<?php
require('../models/userModel.php');
$users = getAllUsers();
$title = 'Foydalanuvchilar';
?>

<?php require('../templates/header.php'); ?>

<h1>Barcha foydalanuvchilar</h1>

<?php if (isset($users)) : ?>
    <ul>
        <?php foreach ($users as $user) : ?>
            <li>id : <?= $user->id ?> - username : <?= $user->username ?> </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php require('../templates/footer.php'); ?>
```

DRY tamoyili asosida yozganimizdan keyin, kodlarimiz ancha yaxshi ko'rinishga keldi. Hozir ko'rganimiz kichkina dastur edi. DRYni ishlatmasa ham kodni davom ettirishda va tushunishda unchalik muammo paydo bo'lmasligi mumkin. Lekin, katta projectlarda kod bilan oson va tushunarli ishlash uchun, hamda dastur samaradorligini oshirish uchun DRY tamoyilidan foydalanish shart hisoblanadi.

DRY tamoyili faqat PHP dasturlash tiliga tegishli emas. Bu tamoyilni har qanday tilda dastur yaratishda foydalansa bo'ladi.
