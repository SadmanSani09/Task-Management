<?php

use Illuminate\Support\Facades\Route;
use views\Layouts\app

Route::get('/', function () {
    return view('welcome');
});
