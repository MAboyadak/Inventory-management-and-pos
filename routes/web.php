<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SalesController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\WarehouseController;

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

Route::group([
    'middleware' => 'auth',

], function(){

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    });

    // Users
    Route::get('users',[UsersController::class,'index'])->name('users.index');
    Route::get('users/add',[UsersController::class,'create'])->name('users.create');

    // categories
    Route::get('categories',[CategoriesController::class,'index'])->name('categories.index');
    Route::post('categories',[CategoriesController::class,'store'])->name('categories.store');
    Route::get('categories/{id}/delete',[CategoriesController::class,'delete'])->name('categories.delete');
    Route::get('categories/getdata/{id}',[CategoriesController::class,'getData'])->name('categories.getdata');
    Route::post('categories/{id}/update',[CategoriesController::class,'update'])->name('categories.update');

    // Products
    Route::get('products',[ProductsController::class,'index'])->name('products.index');
    Route::get('products/create',[ProductsController::class,'create'])->name('products.create');
    Route::post('products',[ProductsController::class,'store'])->name('products.store');
    Route::get('products/{id}/delete',[ProductsController::class,'delete'])->name('products.delete');
    Route::get('products/gencode', [ProductsController::class,'generateCode']);
    Route::get('products/print_barcode',[ProductsController::class,'printBarcode'])->name('products.print_barcode');
	Route::get('products/product_search', [ProductsController::class,'productSearch'])->name('products.search');
	Route::get('products/edit/{id}', [ProductsController::class,'edit'])->name('products.edit');
	Route::post('products/update/{id}', [ProductsController::class,'update'])->name('products.update');

    // Route::get('products/getdata/{id}',[ProductsController::class,'getData'])->name('products.getdata');
    // Route::post('products/{id}/update',[ProductsController::class,'update'])->name('products.update');


    // Sales
    Route::get('sales',[SalesController::class,'index'])->name('sales.index');
    Route::get('sales/create',[SalesController::class,'create'])->name('sales.create');
    Route::post('sales',[SalesController::class,'store'])->name('sales.store');
    Route::get('sales/{id}/delete',[SalesController::class,'delete'])->name('sales.delete');
    Route::get('sales/getproduct/{id}', [SalesController::class,'getProduct'])->name('sales.getproduct');
	Route::get('sales/product_search', [SalesController::class,'productSearch'])->name('sales.productSearch');
    // Route::get('sales/gencode', [SalesController::class,'generateCode']);
    // Route::get('sales/print_barcode',[SalesController::class,'printBarcode'])->name('sales.print_barcode');
	// Route::get('sales/product_search', [SalesController::class,'salesearch'])->name('sales.search');
	Route::get('sales/edit/{id}', [SalesController::class,'edit'])->name('sales.edit');
	// Route::post('sales/update/{id}', [SalesController::class,'update'])->name('sales.update');


    // settings
    //warehouses
    Route::get('warehouses',[WarehouseController::class,'index'])->name('warehouses.index');
    Route::post('warehouses',[WarehouseController::class,'store'])->name('warehouses.store');
    Route::get('warehouses/{id}/delete',[WarehouseController::class,'delete'])->name('warehouses.delete');
    Route::get('warehouses/getdata/{id}',[WarehouseController::class,'getData'])->name('warehouses.getdata');
    Route::post('warehouses/{id}/update',[WarehouseController::class,'update'])->name('warehouses.update');
    //

});

Route::get('/', function () {
    return view('welcome');
});

require __DIR__.'/auth.php';
