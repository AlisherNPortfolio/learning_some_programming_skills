<?php

use App\Events\SimpleChatEvent;
use App\Http\Controllers\Broadcasting\MessageController;
use App\Http\Controllers\WebAuth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login-post', [AuthController::class, 'login'])->name('login-post');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('register-post', [AuthController::class, 'register'])->name('register-post');
Route::get('dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');


Route::get('message/index', [MessageController::class, 'index']);
Route::post('message/send', [MessageController::class, 'send']);

// *************** simple chat route ****************
Route::get('simple-chat', function () {
    return view('broadcasting.simple-chat');
});
Route::post('/send-message', function (Request $request) {
    event(new SimpleChatEvent($request->username, $request->message));
    return ['success' => true];
});
