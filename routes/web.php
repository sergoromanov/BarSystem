<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteController;
use App\Models\User;

Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/drink/{id}', [CatalogController::class, 'show'])->name('drink');
Route::get('/', function () {
    return redirect()->route('home');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('doLogin');

Route::get('/order', [OrderController::class, 'index'])->name('order');
Route::post('/order/add', [OrderController::class, 'add'])->name('order.add');
Route::post('/drink/{id}/custom-order', [App\Http\Controllers\OrderController::class, 'customOrder'])->name('drink.customOrder');
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
// Показ формы редактирования
Route::get('/favorites/{id}/edit', [\App\Http\Controllers\FavoriteController::class, 'edit'])->name('favorites.edit');

// Обработка обновления
Route::post('/favorites/{id}', [\App\Http\Controllers\FavoriteController::class, 'update'])->name('favorites.update');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.delete');

Route::post('/order/{id}/pay', [\App\Http\Controllers\OrderController::class, 'pay'])->name('order.pay');
Route::post('/order/{id}/pay/start', [OrderController::class, 'startPayment'])->name('order.pay.start');
Route::get('/payment/fake/{id}', [OrderController::class, 'showFakePayment'])->name('payment.fake');
Route::post('/payment/fake/{id}/confirm', [OrderController::class, 'confirmFakePayment'])->name('payment.fake.confirm');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/send-code', [AuthController::class, 'sendCode'])->name('auth.sendCode');
Route::get('/verify', [AuthController::class, 'showVerificationForm'])->name('auth.verify');
Route::post('/verify', [AuthController::class, 'verifyCode'])->name('auth.checkCode');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

Route::get('/admin', function () {
    $userId = session('user_id');
    $user = User::find($userId);

    if (!$user || !$user->is_admin) {
        abort(403, 'Доступ запрещён');
    }

    return view('admin.dashboard');
})->name('admin.dashboard');
