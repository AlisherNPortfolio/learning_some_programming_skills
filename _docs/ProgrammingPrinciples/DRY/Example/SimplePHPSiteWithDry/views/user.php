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