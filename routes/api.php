<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WhistlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/*
|--------------------------------------------------------------------------
| Auth Route API
|--------------------------------------------------------------------------
*/
Route::get('/user-list', [AuthController::class, 'userList'])->name('users');
Route::get('/user-list/{id}', [AuthController::class, 'userList'])->name('users');
Route::post('/signup-user', [AuthController::class, 'signupUser'])->name('user.signup');
Route::post('/signin-user',[AuthController::class, 'signinUser'])->name('user.signin');
Route::post('/forgot-password',[AuthController::class, 'forgotPassword'])->name('user.forgotpassword');

/*
|--------------------------------------------------------------------------
| Wishlist Route API
|--------------------------------------------------------------------------
*/
Route::get('/wishlist', [WhistlistController::class, 'index'])->name('wishlist.get')->middleware('auth:sanctum');
Route::post('/add-wishlist', [WhistlistController::class, 'addWishlistProduct'])->name('product.add-wishlist')->middleware('auth:sanctum');
Route::post('/remove-wishlist', [WhistlistController::class, 'removeWishlistProduct'])->name('product.remove-wishlist')->middleware('auth:sanctum');

/*
|--------------------------------------------------------------------------
| Product Route API
|--------------------------------------------------------------------------
*/
Route::get('/product', [ProductController::class, 'listProduct'])->name('product.list');
Route::post('/product', [ProductController::class, 'addProduct'])->name('product.add');
Route::get('/product/{id}', [ProductController::class, 'editProduct'])->name('product.edit');
Route::delete('/product/{id}',[ProductController::class, 'deleteProduct'])->name('product.delete');
Route::post('/product/{id}', [ProductController::class, 'updateProduct'])->name('product.update');

/*
|--------------------------------------------------------------------------
| Category Route API
|--------------------------------------------------------------------------
*/
Route::get('/category', [CategoryController::class, 'listCategory'])->name('category.list');
Route::post('/category', [CategoryController::class,'addCategory'])->name('category.add');
Route::get('/category/{id}', [CategoryController::class, 'editCategory'])->name('category.edit');
Route::post('/category/{id}', [CategoryController::class, 'updateCategory'])->name('category.update');
Route::delete('/category/{id}', [CategoryController::class, 'deleteCategory'])->name('category.delete');