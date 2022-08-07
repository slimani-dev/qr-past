<?php

use App\Http\Controllers\PastController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', [PastController::class, 'index'])->name('welcome');
Route::resource('/pasts', PastController::class);
Route::post('/pasts/{past}/file', [PastController::class, 'file']);
Route::delete('/pasts/{past}/file/{id}', [PastController::class, 'deleteFile']);
Route::get('/thanks', [PastController::class, 'thanks'])->name('thank-you');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/auth.php';
