<?php

use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShoppingController;
use App\Models\Product;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home', [
        'products' => Product::count()
    ]);
})->name('home');

Route::get('shopping-cart', [ShoppingController::class, 'shoppingCart'])->name('shopping.cart');

Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::get('inventory', function () {
    return view('administration/inventory');
})->name('inventory.index')->middleware('can:inventory.index');

Route::get('debtors', function () {
    return view('administration/debtors');
})->name('debtors.index')->middleware('can:debtor.index');


Route::get('contact', [ContactController::class, 'form'])->name('contact.form');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('users', [AdministrationController::class, 'userIndex'])->name('users.index')
        ->middleware('can:user.index');

    Route::get('products', [ProductController::class, 'index'])->name('products.index')
        ->middleware('can:product.index');

    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index')
        ->middleware('can:category.index');

    Route::get('promotions', [PromotionController::class, 'index'])->name('promotions.index')
        ->middleware('can:promotion.index');
    Route::get('promotions/create', [PromotionController::class, 'create'])->name('promotions.create')
        ->middleware('can:promotion.create');
    Route::get('promotions/{promotion}/edit', [PromotionController::class, 'edit'])->name('promotions.edit')
        ->middleware('can:promotion.edit');

    Route::get('roles', [RoleController::class, 'index'])->name('roles.index')
        ->middleware('can:role.index');
    Route::get('pages', [AdministrationController::class, 'pages'])->name('pages')
        ->middleware('can:page.index');
    Route::get('sidebar', [AdministrationController::class, 'sidebar'])->name('sidebar')
        ->middleware('can:sidebar.index');
    Route::get('statistics', [AdministrationController::class, 'statistics'])->name('statistics')
        ->middleware('can:statistic.index');

    Route::get('orders', [OrderController::class, 'index'])->name('admin.orders')
        ->middleware('can:order.index');
    Route::get('/my-orders', [OrderController::class, 'clientOrders'])->name('customer.orders')
        ->middleware('can:order.index.own');

    Route::get('payments', [PaymentController::class, 'index'])->name('admin.payments')
        ->middleware('can:payment.index');
    Route::get('/my-payments', [PaymentController::class, 'clientPayments'])->name('customer.payments')
        ->middleware('can:payment.index.own');

    Route::get('contact-messages', [ContactController::class, 'index'])->name('contact.index')
        ->middleware('can:contact.index');
});

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);

Route::post('/callback', [PaymentService::class, 'callback']);

require __DIR__ . '/auth.php';
