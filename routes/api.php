<?php

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
/**Routes for buyers */
Route::resource('buyers', 'Buyer\BuyerController',['only'=>['index','show']]);

/**Routes for categories */
Route::resource('categories', 'Category\CategoryController',['except'=>['create','edit']]);

//**Routes for Products */
Route::resource('products', 'Product\ProductController',['only'=>['index','show']]);

/**Routes for Transactions*/
Route::resource('transactions', 'Transaction\TransactionController',['only'=>['index','show']]);

/**Routes for seller */
Route::resource('sellers', 'Seller\SellerController',['only'=>['index','show']]);

/**Routes for User */
Route::resource('users', 'User\UserController',['except'=>['create','edit']]);
