<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\CheckpowerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DropdownController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\MoveinController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\RequestvisitController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebnewsController;
use App\Http\Controllers\WebsolutionController;
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

Route::get('/', function () {
    return redirect('login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth']], function () {

    Route::controller(DropdownController::class)->group(function () {
        Route::get('getsitecustomer', 'sitecustomer')->name('getsitecustomer');
        Route::get('getfloorcustomer', 'floorcustomer')->name('getfloorcustomer');
        Route::get('getfloor', 'floor')->name('getfloor');
    });

    Route::resource('news', NewsController::class);
    Route::resource('contact', ContactController::class);
    Route::resource('request', RequestvisitController::class);
    Route::resource('rack', RackController::class);
    Route::resource('checkpower', CheckpowerController::class);
    Route::resource('movein', MoveinController::class);

    Route::resource('customer', CustomerController::class);
    Route::get('customer/destroy/{id}', [CustomerController::class, 'destroy'])->name('customer.delete');

    Route::controller(AjaxController::class)->group(function () {
        Route::get('/floor', 'floor')->name('get.floor');
        Route::get('/room', 'room')->name('get.room');
        Route::get('/rack_customer', 'rack_customer')->name('get.rack_customer');
        Route::get('/rack_customer_check', 'rack_customer_check')->name('get.rack_customer_check');
    });

    Route::group(['middleware' => ['role:superadmin']], function () {
        Route::resource('site', SiteController::class);
        Route::resource('floor', FloorController::class);

        Route::resource('users', UserController::class);
        Route::controller(UserController::class)->group(function () {
            Route::get('users/destroy/{id}', 'destroy')->name('users.delete');
        });

        Route::resource('permissions', PermissionController::class);
        Route::resource('roles', RoleController::class);
        Route::controller(RoleController::class)->group(function () {
            Route::get('roles/destroy/{id}', 'destroy')->name('roles.delete');
        });

        //WEB
        Route::resource('webnews', WebnewsController::class);
        Route::get('webnews/destroy/{id}', [WebnewsController::class, 'destroy'])->name('webnews.delete');

        Route::resource('websolution', WebsolutionController::class);
        Route::get('websolution/destroy/{id}', [WebsolutionController::class, 'destroy'])->name('websolution.delete');
    });
});
