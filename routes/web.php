<?php

use App\Http\Controllers\ExtensionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('{slug}/send', [ExtensionController::class, 'send'])->name('extensions.send');
Route::get('{slug}', [ExtensionController::class, 'index'])->name('extensions.index');
Route::post('user/edit', [ExtensionController::class, 'edit'])->name('extensions.edit');

