<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;

// Контроллеры
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdminDrinkController;
use App\Http\Controllers\AdminIngredientController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminStatsController;
use App\Http\Controllers\BarmanController;

// Вспомогательная функция
if (!function_exists('user_is_admin')) {
    function user_is_admin() {
        $user = User::find(session('user_id'));
        return $user && $user->is_admin;
    }
}

// 🔹 Гостевые маршруты
Route::get('/', fn() => redirect()->route('auth.login'));
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/drink/{id}', [CatalogController::class, 'show'])->name('drink');

// 🔹 Аутентификация
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/send-code', [AuthController::class, 'sendCode'])->name('auth.sendCode');
Route::get('/verify', [AuthController::class, 'showVerificationForm'])->name('auth.verify');
Route::post('/verify', [AuthController::class, 'verifyCode'])->name('auth.checkCode');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');

// 🔹 Заказы
Route::get('/order', [OrderController::class, 'index'])->name('order');
Route::post('/order/add', [OrderController::class, 'add'])->name('order.add');
Route::post('/drink/{id}/custom-order', [OrderController::class, 'customOrder'])->name('drink.customOrder');
Route::post('/order/{id}/pay', [OrderController::class, 'pay'])->name('order.pay');
Route::post('/order/{id}/pay/start', [OrderController::class, 'startPayment'])->name('order.pay.start');
Route::get('/payment/fake/{id}', [OrderController::class, 'showFakePayment'])->name('payment.fake');
Route::post('/payment/fake/{id}/confirm', [OrderController::class, 'confirmFakePayment'])->name('payment.fake.confirm');

// 🔹 Избранное
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
Route::get('/favorites/{id}/edit', [FavoriteController::class, 'edit'])->name('favorites.edit');
Route::post('/favorites/{id}', [FavoriteController::class, 'update'])->name('favorites.update');
Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy'])->name('favorites.delete');

// 🔹 Админ: Dashboard
Route::get('/admin', function () {
    abort_unless(user_is_admin(), 403);
    return view('admin.dashboard');
})->name('admin.dashboard');

// 🔹 Админ: Напитки
Route::prefix('admin/drinks')->middleware([])->group(function () {
    Route::get('/', fn() => abort_unless(user_is_admin(), 403) ?: app(AdminDrinkController::class)->index())->name('admin.drinks.index');
    Route::get('/create', fn() => abort_unless(user_is_admin(), 403) ?: app(AdminDrinkController::class)->create())->name('admin.drinks.create');
    Route::post('/', fn(Request $r) => abort_unless(user_is_admin(), 403) ?: app(AdminDrinkController::class)->store($r))->name('admin.drinks.store');
    Route::get('/{id}/edit', fn($id) => abort_unless(user_is_admin(), 403) ?: app(AdminDrinkController::class)->edit($id))->name('admin.drinks.edit');
    Route::post('/{id}', fn(Request $r, $id) => abort_unless(user_is_admin(), 403) ?: app(AdminDrinkController::class)->update($r, $id))->name('admin.drinks.update');
    Route::delete('/{id}', fn($id) => abort_unless(user_is_admin(), 403) ?: app(AdminDrinkController::class)->destroy($id))->name('admin.drinks.destroy');
});

// 🔹 Админ: Ингредиенты
Route::prefix('admin/ingredients')->group(function () {
    Route::get('/', fn() => abort_unless(user_is_admin(), 403) ?: app(AdminIngredientController::class)->index())->name('admin.ingredients.index');
    Route::get('/create', fn() => abort_unless(user_is_admin(), 403) ?: app(AdminIngredientController::class)->create())->name('admin.ingredients.create');
    Route::post('/', fn(Request $r) => abort_unless(user_is_admin(), 403) ?: app(AdminIngredientController::class)->store($r))->name('admin.ingredients.store');
    Route::get('/{id}/edit', fn($id) => abort_unless(user_is_admin(), 403) ?: app(AdminIngredientController::class)->edit($id))->name('admin.ingredients.edit');
    Route::post('/{id}', fn(Request $r, $id) => abort_unless(user_is_admin(), 403) ?: app(AdminIngredientController::class)->update($r, $id))->name('admin.ingredients.update');
    Route::delete('/{id}', fn($id) => abort_unless(user_is_admin(), 403) ?: app(AdminIngredientController::class)->destroy($id))->name('admin.ingredients.destroy');
});

// 🔹 Админ: Пользователи
Route::prefix('admin/users')->group(function () {
    Route::get('/', fn() => abort_unless(user_is_admin(), 403) ?: app(AdminUserController::class)->index())->name('admin.users.index');
    Route::get('/{id}/edit', fn($id) => abort_unless(user_is_admin(), 403) ?: app(AdminUserController::class)->edit($id))->name('admin.users.edit');
    Route::post('/{id}', fn(Request $r, $id) => abort_unless(user_is_admin(), 403) ?: app(AdminUserController::class)->update($r, $id))->name('admin.users.update');
});

// 🔹 Админ: Статистика
Route::get('/admin/stats', fn() => abort_unless(user_is_admin(), 403) ?: app(AdminStatsController::class)->index())->name('admin.stats');

// 🔹 Бармен
Route::prefix('barman')->group(function () {
    Route::get('/dashboard', [BarmanController::class, 'index'])->name('barman.dashboard');
    Route::get('/orders', [BarmanController::class, 'orders'])->name('barman.orders');
    Route::post('/orders/{orderId}/status', [BarmanController::class, 'updateStatus'])->name('barman.orders.updateStatus');
});
