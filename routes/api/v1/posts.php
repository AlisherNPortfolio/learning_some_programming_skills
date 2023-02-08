<?php

use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::namespace("\App\Http\Controllers")
    ->group(function () {
        Route::get('/posts', 'PostController@index')
            ->name('index');

        Route::get('/posts/{post}', 'PostController@show')
            ->name('show')
            ->where('post', '[0-9]+');

        Route::post('/posts', 'PostController@store')->name('store');
        Route::patch('/posts/{post}', 'PostController@update')->name('update');
        Route::delete('/posts/{post}', 'PostController@destroy')->name('destroy');
    });
