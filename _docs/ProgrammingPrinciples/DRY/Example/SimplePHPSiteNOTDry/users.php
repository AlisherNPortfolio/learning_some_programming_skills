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