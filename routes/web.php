<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;

use App\Http\Controllers\RoleController;

use App\Http\Controllers\TypeFreezerController;
use App\Http\Controllers\FreezerController;

use App\Http\Controllers\PlateDimensionController;
use App\Http\Controllers\PlateController;

use App\Http\Controllers\FlavorProductController;
use App\Http\Controllers\StatusProductController;
use App\Http\Controllers\TypeProductController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\OrderEnterpriseController;
use App\Http\Controllers\PurchaseOrderDetailController;

use App\Http\Controllers\TypeCartController;
use App\Http\Controllers\StatusCartController;
use App\Http\Controllers\CartController;

use App\Http\Controllers\SellerController;

use App\Http\Controllers\SellerDailyReportController;
use App\Http\Controllers\SellerDailyReportDetailController;

use App\Http\Controllers\InventoryController;


use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth:web', config('auth_session'), 'verified', 'role:Administrador'])->group(function () {
    Route::resource('/roles', RoleController::class);

    // ? Modulos de Freezer
    Route::resource('/type_freezers', TypeFreezerController::class);
    Route::resource('/freezers', FreezerController::class);

    // ? Modulos de Placas
    Route::resource('/plate_dimensions', PlateDimensionController::class);
    Route::resource('/plates', PlateController::class);

    // ? Modulo de Productos
    Route::resource('/type_products', TypeProductController::class);
    Route::resource('/flavor_products', FlavorProductController::class);
    Route::resource('/status_products', StatusProductController::class);
    Route::resource('/products', ProductController::class);

    // ? Modulo de Pedidos
    Route::resource('/orders_enterprises', OrderEnterpriseController::class);
    Route::resource('/purchase_order_details', PurchaseOrderDetailController::class);

    // ? Modulo de Carritos
    Route::resource('/type_carts', TypeCartController::class);
    Route::resource('/status_carts', StatusCartController::class);
    Route::resource('/carts', CartController::class);

    // ? Modulo de Vendedores
    Route::resource('/seller', SellerController::class);

    // ? Modulo de Reporte de Vendedores
    Route::resource('/seller_daily_reports', SellerDailyReportController::class);
    Route::resource('/seller_daily_report_details', SellerDailyReportDetailController::class);

    // ? Modulo de Inventario
    Route::resource('/inventories', InventoryController::class);
});

Route::middleware(['auth:web', config('auth_session'), 'verified', 'role:Administrador|Gerente'])->group(function (){

});

require __DIR__.'/auth.php';
