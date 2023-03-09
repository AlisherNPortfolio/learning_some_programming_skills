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