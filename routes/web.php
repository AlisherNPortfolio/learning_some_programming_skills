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


// *************** simple chat route ****************
Route::get('simple-chat', function () {
    return view('broadcasting.simple-chat');
});
Route::post('/send-message', function (Request $request) {
    event(new SimpleChatEvent($request->username, $request->message));
    return ['success' => true];
});

// ************** private chat route ******************
Route::post('/pusher/auth', [MessageController::class, 'authPusher'])
    ->middleware('auth');
Route::post('/send-private', [MessageController::class, 'send']);
Route::get('message/index', [MessageController::class, 'index']);

if (\Illuminate\Support\Facades\App::environment('local')) { // Bu route faqat dev modeda ishlaydi
    Route::get('/playground', function () {
        $user = \App\Models\User::query()->find(1);
        \Illuminate\Support\Facades\Mail::to($user)
            ->send(new \App\Mail\WelcomeMail($user));

        return null;
    });
}
// ************************* queue, job, worker *************
Route::get('/queue', function () {
    // dispatch(new \App\Jobs\SendVerificationEmail()); // <== Job klas obyektini beramiz
    \App\Jobs\SendVerificationEmail::dispatch();
    // \App\Jobs\SendVerificationEmail::dispatch()
    //     ->onQueue('high-priority');

    return view('queue.index');
});
