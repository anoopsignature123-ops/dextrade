<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes Configuration
|--------------------------------------------------------------------------
*/

// Public Root Coming Soon Page
Route::get('/', HomeController::class)->name('home');

// Load Modular Admin & User Route Files
// require __DIR__.'/admin.php';
// require __DIR__.'/user.php';