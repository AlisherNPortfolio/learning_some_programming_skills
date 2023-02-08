<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::prefix('v1') // api route-da api-dan keyin v1 qo'shish
    ->group(function () {
        // require __DIR__ . '/api/v1/users.php';
        require __DIR__ . '/api/v1/posts.php';
        // require __DIR__ . '/api/v1/comments.php';

        // Yuqoridagidey bittalab chaqirib o'tirmaslik uchun ...
        \App\Helpers\Routes\RouteHelper::includeRouteFiles(__DIR__ . '/api/v1');
    });
