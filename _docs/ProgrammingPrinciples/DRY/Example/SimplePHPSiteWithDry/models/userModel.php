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
