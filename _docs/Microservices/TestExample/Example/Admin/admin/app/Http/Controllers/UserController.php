<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function random()
    {
        $user = User::query()->inRandomOrder()->first();

        return [
            'id' => $user->id,
        ];
    }
}
