<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteController;

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/drink/{id}', [CatalogController::class, 'show'])->name('drink');
Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('doLogin');
Route::get('/home', function () {
    $user = \App\Models\User::find(session('user_id'));
    return view('home', compact('user'));
})->name('home');
Route::get('/order', [OrderController::class, 'index'])->name('order');
Route::post('/order/add', [OrderController::class, 'add'])->name('order.add');
Route::post('/drink/{id}/custom-order', [App\Http\Controllers\OrderController::class, 'customOrder'])->name('drink.customOrder');
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
// Показ формы редактирования
Route::get('/favorites/{id}/edit', [\App\Http\Controllers\FavoriteController::class, 'edit'])->name('favorites.edit');

// Обработка обновления
Route::post('/favorites/{id}', [\App\Http\Controllers\FavoriteController::class, 'update'])->name('favorites.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
Route::post('/order/{id}/pay', [\App\Http\Controllers\OrderController::class, 'pay'])->name('order.pay');
