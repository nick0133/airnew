<?php

use App\Exports\ProductsExport;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Models\Configuration;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
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

Route::get('/test', function () {
    $config = Configuration::first();
    $configFilters = collect($config->disable_filters);
    $newDisableFilters = collect(["cocomelon" => "cocomelon", "ct3en" => "ct3en"]);
    $disableFilters = $configFilters->merge($newDisableFilters)->unique()->toArray();
    $config->disable_filters = $disableFilters;
    $config->save();
    dd($config->disable_filters);
});


Route::get('/products/export', function (\Illuminate\Http\Request $request) {
    // Проверяем, что запрос подписан корректно
    if (!$request->hasValidSignature()) {
        abort(403);
    }

    // Получаем ID категории из параметров
    $categoryId = $request->query('category_id');

    // Возвращаем экспорт с категориями
    $uuid = uniqid();
    return Excel::download(new ProductsExport($categoryId), "products-$uuid.xlsx");
})->name('export.products');


Route::get('/', [PagesController::class, 'index'])->name('pages.index');
Route::get('/airsystem-project', [PagesController::class, 'project'])->name('pages.project');
Route::get('/designing-and-installation', [PagesController::class, 'montazh'])->name('pages.montazh');
Route::get('/equipment-rent', [PagesController::class, 'rent'])->name('pages.rent');
Route::get('/service-and-repair', [PagesController::class, 'service'])->name('pages.service');
Route::get('/cart', [PagesController::class, 'cart'])->name('pages.cart');
Route::post('/cart', [PagesController::class, 'submitCart'])->name('pages.submit-cart');
Route::get('/delivery', [PagesController::class, 'delivery'])->name('pages.delivery');
Route::get('/office', [PagesController::class, 'office'])->name('pages.office');
Route::get('/licenses', [PagesController::class, 'licenses'])->name('pages.licenses');


Route::any('/catalog/{slug}/{id?}', [\App\Http\Controllers\CatalogController::class, 'index'])->name('catalog.id');

require __DIR__ . '/auth.php';


Route::prefix('profile')->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

Route::prefix('/api/cart')->group(function () {
    Route::get('/', [CartController::class, 'get']);
    Route::post('/', [CartController::class, 'add']);
    Route::get('/download', [CartController::class, 'download'])->name('cart.download');
    Route::post('/upload', [CartController::class, 'upload'])->name('cart.upload');
});

Route::get('/search', [SearchController::class, 'index'])->name('pages.search');
