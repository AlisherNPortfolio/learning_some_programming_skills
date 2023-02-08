<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')
    ->prefix('test')
    ->name('users.')
    ->namespace("\App\Http\Controllers")
    ->group(function () {
        Route::get('/users', 'UserController@index')
            ->name('index')
            ->withoutMiddleware('auth'); // <== agar guruh uchun qo'llangan middleware-ni olib tashlash kerak bo'lsa

        Route::get('/users/{user}', 'UserController@show')
            ->name('show')
            ->where('user', '[0-9]+'); // <== kiruvchi parametrni regex bilan tekshirish

        Route::post('/users', 'UserController@store')->name('store');
        Route::patch('/users/{user}', 'UserController@update')->name('update');
        Route::delete('/users/{user}', 'UserController@destroy')->name('destroy');
    });

// Yoki tepadagilarni quyidagicha yozsa ham bo'ladi.

// Route::group([
//     'middleware' => ['auth'],
//     'prefix' => 'test',
//     'as' => 'users.',
//     'namespace' => "\App\Http\Controllers"
// ], function () {
//     Route::get('/users', 'UserController@index')->name('index');
//     Route::get('/users', 'UserController@show')->name('show');
//     Route::post('/users', 'UserController@store')->name('store');
//     Route::patch('/users', 'UserController@update')->name('update');
//     Route::delete('/users', 'UserController@destroy')->name('destroy');
// });
