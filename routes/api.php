<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\User\CategorysController;
use App\Http\Controllers\User\ProductsController;
use App\Http\Controllers\User\OrdersController;
use App\Http\Controllers\WhistlistController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\BuyNowController;
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


// Login Route
Route::post('/signup-user', [AuthController::class, 'signupUser'])->name('user.signup');
Route::post('/signin-user',[AuthController::class, 'signinUser'])->name('user.signin');
Route::get('unauthorized', function () {
    return response()->json(['error' => 'Access Denied!!!'], 401);
})->name('unauthorized');

/*
|--------------------------------------------------------------------------
| Admin Route
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {

    // User Route
    Route::controller(AuthController::class)->group(function(){
        Route::get('/user-list', 'userList')->name('users');
        Route::get('/user-list/{id}', 'userGet')->name('users.get');
        Route::post('/user-update', 'updateUser')->name('user.update');
        Route::delete('/remove-user/{id}', 'removeUser')->name('user.remove');
        Route::post('/forgot-password','forgotPassword')->name('user.forgotpassword');
        Route::post('/update', 'adminProfileUpdate')->name('user.auth-update');
        // Route::get('/old-password', 'oldPassword')->name('get.old-password');
        Route::post('/change-password', 'changePassword')->name('update.change-password');
        Route::post('/logout', 'logout')->name('user.logout');
    });

    // Category Route
    Route::controller(CategoryController::class)->group(function(){
        Route::get('/category','listCategory')->name('category.list');
        Route::post('/category','addCategory')->name('category.add');
        Route::get('/category/{id}','editCategory')->name('category.edit');
        Route::post('/category/{id}', 'updateCategory')->name('category.update');
        Route::delete('/category/{id}','deleteCategory')->name('category.delete');
        Route::get('/category-active', 'activeCategory')->name('category.active');
        Route::get('/category-inactive', 'inActiveCategory')->name('category.inactive');
    });
 
    // Product Route
    Route::controller(ProductController::class)->group(function(){
        Route::get('/product', 'listProduct')->name('product.list');
        Route::get('/active-product', 'activeProduct')->name('product.active');
        Route::get('/inactive-product', 'inActiveProduct')->name('product.in-active');
        Route::post('/product', 'addProduct')->name('product.add');
        Route::get('/product/{id}', 'editProduct')->name('product.edit');
        Route::delete('/product/{id}','deleteProduct')->name('product.delete');
        Route::post('/product/{id}','updateProduct')->name('product.update');
    });

    //Order Route
    Route::controller(OrderController::class)->group(function(){
        Route::get('/order', 'listOrder')->name('order.list');
        Route::get('/single-order/{id}', 'singleOrder')->name('single.order.list');
        Route::post('/update-order-status/{id}','updateOrderStatus')->name('order-status.update');
        Route::get('/cancel-order/{id}', 'cancelOrder')->name('auto.cancel-order');
    });
});

/*
|--------------------------------------------------------------------------
| Fronted Api
|--------------------------------------------------------------------------
*/
// Product Listing Route
Route::get('/product-list', [ProductsController::class, 'productList'])->name('product-list');

// Category Listing Route
Route::get('/category-list', [CategorysController::class, 'categoryList'])->name('category-list');

Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    // Wishlist Route
    Route::controller(WhistlistController::class)->group(function() {
        Route::get('/wishlist','index')->name('wishlist.get');
        Route::post('/add-wishlist', 'addWishlistProduct')->name('product.add-wishlist');
        Route::post('/remove-wishlist', 'removeWishlistProduct')->name('product.remove-wishlist');
    });

    // Add To Cart Route
    Route::controller(CartController::class)->group(function() {
        Route::get('/cart','index')->name('cart.list');
        Route::post('/add-cart', 'addProductCart')->name('add.cart');
        Route::post('/remove-cart-item', 'removeCartProduct')->name('remove.cart.item');
        Route::post('/update-cart','updateCartItem')->name('cart.increment');
        Route::post('/update-flat-rate', 'updateFlatRate')->name('cart.flat-amount');
    });

    //Order 
    Route::controller(OrdersController::class)->group(function() {
        Route::post('/create-order', 'createOrder')->name('order.cerate');
        Route::get('/order-list', 'orderList')->name('orders.list');
        Route::get('/single-order-show/{id}','singleOrderShow')->name('single.order.list');
        Route::post('/order-cancelled/{id}','cancelledOrder')->name('order.cancelled');
        Route::post('/update-status/{id}','updateStatus')->name('status.update');
    });

    // User Profile
    Route::controller(ProfileController::class)->group(function(){
        Route::get('/user-detail', 'userDetail')->name('user.profile.list');
        Route::post('/user-profile-update', 'updateUserProfile')->name('profile.update');
    });

    // Buy Now Add
    Route::controller(BuyNowController::class)->group(function(){
        Route::get('/buynow-list', 'buyNowList')->name('product.buy-now-list');
        Route::post('/product-buynow','productBuyNow')->name('product.buy-now');
        Route::post('/update-buynow-product', 'updateProductBuyNow')->name('product.buy-now-update');
        Route::post('/cancel-buynow', 'cancelBuyNow')->name('product.cancel-buy-now');
        Route::post('/update-buynow-status/{id}', 'updateBuyNowStatus')->name('product.update-buy-now-status');
    });
});
