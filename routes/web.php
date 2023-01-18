<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetUploadContorller;
use App\Http\Controllers\BisnisUnitController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CategoriesgroupController;
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
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
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


Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::controller(DropdownController::class)->group(function () {
        Route::get('getsitecustomer', 'sitecustomer')->name('getsitecustomer');
        Route::get('getfloorcustomer', 'floorcustomer')->name('getfloorcustomer');
        Route::get('getfloor', 'floor')->name('getfloor');
        Route::get('getroom', 'room')->name('getroom');
    });

    Route::resource('news', NewsController::class);
    Route::resource('contact', ContactController::class);
    Route::resource('request', RequestvisitController::class);
    Route::resource('rack', RackController::class);
    Route::resource('checkpower', CheckpowerController::class);
    Route::resource('movein', MoveinController::class);
    Route::resource('categories', CategoriesController::class);
    Route::resource('categoriesgroup', CategoriesgroupController::class);
    Route::resource('brand', BrandController::class);
    Route::resource('bisnisunit', BisnisUnitController::class);
    Route::resource('vendor', VendorController::class);

    //asset
    Route::resource('asset', AssetController::class);
    Route::prefix('asset')->controller(AssetController::class)->group(function () {
        Route::post('restore', 'restore')->name('asset.restore');
        Route::post('forcedelete', 'forcedelete')->name('asset.forcedelete');
        // Route::post('upload', 'upload')->name('asset.upload');
        // Route::post('import', 'import')->name('asset.import');
        // Route::post('export', 'export')->name('asset.export');
    });

    Route::prefix('assetupload')->controller(AssetUploadContorller::class)->group(function () {
        Route::get('index', 'index')->name('assetupload.index');
        Route::post('show', 'show')->name('assetupload.show');
        Route::post('showerror', 'showerror')->name('assetupload.showerror');
        Route::post('delete', 'delete')->name('assetupload.delete');
        Route::post('import', 'import')->name('assetupload.import');
        Route::post('export', 'export')->name('assetupload.export');
    });


    Route::resource('customer', CustomerController::class);
    Route::get('customer/destroy/{id}', [CustomerController::class, 'destroy'])->name('customer.delete');

    Route::controller(AjaxController::class)->group(function () {
        Route::get('/floor', 'floor')->name('get.floor');
        Route::get('/room', 'room')->name('get.room');
        Route::get('/rack_customer', 'rack_customer')->name('get.rack_customer');
        Route::get('/rack_customer_check', 'rack_customer_check')->name('get.rack_customer_check');
    });


    ///SUPERADMIN Role
    Route::group(['middleware' => ['role:superadmin|admin']], function () {
        Route::resource('site', SiteController::class);
        Route::resource('floor', FloorController::class);
        Route::resource('room', RoomController::class);

        //restore and forcedelete
        Route::prefix('categoriesgroup')->controller(CategoriesgroupController::class)->group(function () {
            Route::post('restore', 'restore')->name('categoriesgroup.restore');
            Route::post('orcedelete', 'forcedelete')->name('categoriesgroup.forcedelete');
        });

        Route::prefix('categories')->controller(CategoriesController::class)->group(function () {
            Route::post('restore', 'restore')->name('categories.restore');
            Route::post('forcedelete', 'forcedelete')->name('categories.forcedelete');
        });
        Route::prefix('brand')->controller(BrandController::class)->group(function () {
            Route::post('restore', 'restore')->name('brand.restore');
            Route::post('forcedelete', 'forcedelete')->name('brand.forcedelete');
        });
        Route::prefix('vendor')->controller(VendorController::class)->group(function () {
            Route::post('restore', 'restore')->name('vendor.restore');
            Route::post('forcedelete', 'forcedelete')->name('vendor.forcedelete');
        });
        Route::prefix('bisnisunit')->controller(BisnisUnitController::class)->group(function () {
            Route::post('restore', 'restore')->name('bisnisunit.restore');
            Route::post('forcedelete', 'forcedelete')->name('bisnisunit.forcedelete');
        });

        Route::prefix('site')->controller(SiteController::class)->group(function () {
            Route::post('restore', 'restore')->name('site.restore');
            Route::post('forcedelete', 'forcedelete')->name('site.forcedelete');
        });
        Route::prefix('floor')->controller(FloorController::class)->group(function () {
            Route::post('restore', 'restore')->name('floor.restore');
            Route::post('forcedelete', 'forcedelete')->name('floor.forcedelete');
        });
        Route::prefix('room')->controller(RoomController::class)->group(function () {
            Route::post('restore', 'restore')->name('room.restore');
            Route::post('forcedelete', 'forcedelete')->name('room.forcedelete');
        });


        //master
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
