<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', fn () => redirect()->route('dashboard.index'));

Auth::routes(['register' => false, 'confirm' => false, 'reset' => false]);


Route::middleware('auth')->prefix('/dashboard')->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard.index');
    Route::get('/ui', 'DashboardController@ui');
    //****************************************************** */
    //CRUD for Users
    Route::group(['middleware' => ['permission:super-admin']], function () {
        Route::get('/users', 'UserController@index')->name('users.index');
        Route::get('/users/create', 'UserController@create')->name('users.create');
        Route::post('/users', 'UserController@store')->name('users.store');
        Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit');
        Route::put('/users/{user}', 'UserController@update')->name('users.update');
        Route::delete('/users/{user}', 'UserController@destroy')->name('users.destroy');
        //
    });

    //********************************************************** */

    //*************************************************************** */
    Route::group(['middleware' => ['permission:super-admin|admin']], function () {
        Route::get('/categories', 'CategoryController@index')->name('categories.index');
        Route::get('/categories/create', 'CategoryController@create')->name('categories.create');
        Route::post('/categories', 'CategoryController@store')->name('categories.store');
        Route::get('/categories/{category}/edit', 'CategoryController@edit')->name('categories.edit');
        Route::put('/categories/{category}', 'CategoryController@update')->name('categories.update');
        Route::delete('/categories/{category}', 'CategoryController@destroy')->name('categories.destroy');
        Route::get('/get/subcategories/{id}', 'CategoryController@getSubcategories')->name('categories.getsubcategories');

        //***************************************************************************** */

        Route::get('/subcategories', 'SubcategoryController@index')->name('subcategories.index');
        Route::get('/subcategories/create', 'SubcategoryController@create')->name('subcategories.create');
        Route::post('/subcategories', 'SubcategoryController@store')->name('subcategories.store');
        Route::get('/subcategories/{subcategory}/edit', 'SubcategoryController@edit')->name('subcategories.edit');
        Route::put('/subcategories/{subcategory}', 'SubcategoryController@update')->name('subcategories.update');
        Route::delete('/subcategories/{subcategory}', 'SubcategoryController@destroy')->name('subcategories.destroy');
        // Route::post('/subcategories/update/{subcategory}', 'SubcategoryController@update')->name('subcategories@update');
    });

    //****************************************************************************** */
    Route::group(['middleware' => ['permission:super-admin|admin|marketer']], function () {
        Route::get('/products', 'ProductController@index')->name('products.index')->middleware('permission:super-admin');
        ;
        Route::get('/products/create', 'ProductController@create')->name('products.create');
        Route::post('/products', 'ProductController@store')->name('products.store');
        Route::get('/products/{product}/edit', 'ProductController@edit')->name('products.edit');
        Route::put('/products/{product}', 'ProductController@update')->name('products.update');
        Route::delete('/products/{product}', 'ProductController@destroy')->name('products.destroy');
        Route::post('/products/update/{product}', 'ProductController@update')->name('products.update');

        //***************************************************************************************** */

        Route::resource('/orders', 'OrderController');
        Route::resource('/order/details', 'OrderController');
        Route::resource('/order/details', 'OrderController');
        //******************************************************************************* */

        //************************************* *************/
        // CRUD for customer service
        Route::get('/customer/services/create', 'CustomerServiceController@create')->name('customer.services.create');
        Route::get('/customer/services', 'CustomerServiceController@index')->name('customer.services.index');
        Route::get('/customer/services/{order}', 'CustomerServiceController@show')->name('customer.services.show');
        Route::post('/customer/services', 'CustomerServiceController@store')->name('customer.services.store');
        Route::get('/customer/services/{order}/edit', 'CustomerServiceController@edit')->name('customer.services.edit');
        Route::put('/customer/services/{order}', 'CustomerServiceController@update')->name('customer.services.update');
    });
    //******************************************************** */

    Route::group(['middleware' => ['permission:super-admin|admin|delivery-man']], function () {
        // CRUD for deliveries
        //******************************************************* */

        Route::get('/deliveries', 'DeliveryController@index')->name('deliveries.index');
        // Route::get('/deliveries/create', 'DeliveryController@create')->name('deliveries.create');
        Route::post('/deliveries', 'DeliveryController@store')->name('deliveries.store');
        Route::get('/deliveries/{order}', 'DeliveryController@show')->name('deliveries.show');
        // Route::get('/deliveries/{order}/edit', 'DeliveryController@edit')->name('deliveries.edit');
        // Route::put('/deliveries/{order}', 'DeliveryController@update')->name('deliveries.update');
        // Route::delete('/deliveries/{order}', 'DeliveryController@destroy')->name('deliveries.destroy');

        //************************************************************** */


        Route::get('/markters', 'MarkterController@index')->name('markters.index');
        Route::get('/markters/{order}', 'MarkterController@show')->name('markters.show');
        // Route::get('/markters/{order}/edit','MarkterController@edit')->name('markters.edit');
    });
});