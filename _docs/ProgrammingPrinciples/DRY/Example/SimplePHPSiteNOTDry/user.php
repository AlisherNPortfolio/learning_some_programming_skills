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