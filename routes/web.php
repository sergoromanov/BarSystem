<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\FavoriteController;
use App\Models\User;
use App\Http\Controllers\AdminDrinkController;
use App\Http\Controllers\AdminIngredientController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminStatsController;
use App\Http\Controllers\BarmanController;


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
Route::prefix('admin/drinks')->group(function () {
    Route::get('/', function () {
        $user = \App\Models\User::find(session('user_id'));
        abort_unless($user && $user->is_admin, 403);
        return app(AdminDrinkController::class)->index();
    })->name('admin.drinks.index');

    Route::get('/create', function () {
        $user = \App\Models\User::find(session('user_id'));
        abort_unless($user && $user->is_admin, 403);
        return app(AdminDrinkController::class)->create();
    })->name('admin.drinks.create');

    Route::post('/', function (\Illuminate\Http\Request $request) {
        $user = \App\Models\User::find(session('user_id'));
        abort_unless($user && $user->is_admin, 403);
        return app(AdminDrinkController::class)->store($request);
    })->name('admin.drinks.store');

    Route::get('/{id}/edit', function ($id) {
        $user = \App\Models\User::find(session('user_id'));
        abort_unless($user && $user->is_admin, 403);
        return app(AdminDrinkController::class)->edit($id);
    })->name('admin.drinks.edit');

    Route::post('/{id}', function (\Illuminate\Http\Request $request, $id) {
        $user = \App\Models\User::find(session('user_id'));
        abort_unless($user && $user->is_admin, 403);
        return app(AdminDrinkController::class)->update($request, $id);
    })->name('admin.drinks.update');

    Route::delete('/{id}', function ($id) {
        $user = \App\Models\User::find(session('user_id'));
        abort_unless($user && $user->is_admin, 403);
        return app(AdminDrinkController::class)->destroy($id);
    })->name('admin.drinks.destroy');
});

Route::prefix('admin/ingredients')->group(function () {
    Route::get('/', fn() => abort_unless(user_is_admin(), 403) ?: app(AdminIngredientController::class)->index())->name('admin.ingredients.index');
    Route::get('/create', fn() => abort_unless(user_is_admin(), 403) ?: app(AdminIngredientController::class)->create())->name('admin.ingredients.create');
    Route::post('/', fn(Request $r) => abort_unless(user_is_admin(), 403) ?: app(AdminIngredientController::class)->store($r))->name('admin.ingredients.store');
    Route::get('/{id}/edit', fn($id) => abort_unless(user_is_admin(), 403) ?: app(AdminIngredientController::class)->edit($id))->name('admin.ingredients.edit');
    Route::post('/{id}', fn(Request $r, $id) => abort_unless(user_is_admin(), 403) ?: app(AdminIngredientController::class)->update($r, $id))->name('admin.ingredients.update');
    Route::delete('/{id}', fn($id) => abort_unless(user_is_admin(), 403) ?: app(AdminIngredientController::class)->destroy($id))->name('admin.ingredients.destroy');
});

// вспомогательная функция:
function user_is_admin() {
    $user = \App\Models\User::find(session('user_id'));
    return $user && $user->is_admin;
}


Route::prefix('admin/users')->group(function () {
    Route::get('/', fn() => abort_unless(user_is_admin(), 403) ?: app(AdminUserController::class)->index())->name('admin.users.index');
    Route::get('/{id}/edit', fn($id) => abort_unless(user_is_admin(), 403) ?: app(AdminUserController::class)->edit($id))->name('admin.users.edit');
    Route::post('/{id}', fn(\Illuminate\Http\Request $r, $id) => abort_unless(user_is_admin(), 403) ?: app(AdminUserController::class)->update($r, $id))->name('admin.users.update');
});


Route::get('/admin/stats', fn() => abort_unless(user_is_admin(), 403) ?: app(AdminStatsController::class)->index())
    ->name('admin.stats');


Route::prefix('barman')->group(function () {
    Route::get('/dashboard', [BarmanController::class, 'index'])->name('barman.dashboard');
    Route::get('/orders', [BarmanController::class, 'orders'])->name('barman.orders');
    Route::post('/orders/{orderId}/status', [BarmanController::class, 'updateStatus'])->name('barman.orders.updateStatus');
});

Route::prefix('admin')->group(function () {
    Route::get('/ingredients', [AdminIngredientController::class, 'index'])->name('admin.ingredients.index');
    Route::get('/ingredients/create', [AdminIngredientController::class, 'create'])->name('admin.ingredients.create');
    Route::post('/ingredients', [AdminIngredientController::class, 'store'])->name('admin.ingredients.store');
    Route::get('/ingredients/{id}/edit', [AdminIngredientController::class, 'edit'])->name('admin.ingredients.edit');
    Route::put('/ingredients/{id}', [AdminIngredientController::class, 'update'])->name('admin.ingredients.update');
    Route::delete('/ingredients/{id}', [AdminIngredientController::class, 'destroy'])->name('admin.ingredients.destroy');
});



