<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;

Auth::routes(['verify' => true]);

Route::middleware(['auth'])->group(function () {
    // Redirect old paths to dashboard
    Route::redirect('/dashboard', url('/'));
    Route::redirect('/password/confirm', url('/'));

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Post Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('posts')->as('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::post('/data', [PostController::class, 'data'])->name('data');
        Route::get('/create', [PostController::class, 'create'])->name('create');
        Route::post('/', [PostController::class, 'store'])->name('store');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Group Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('groups')->as('groups.')->group(function () {
        Route::get('/', [GroupController::class, 'index'])->name('index');
        Route::get('/import', [GroupController::class, 'import'])->name('import');
        Route::post('/import', [GroupController::class, 'storeImport'])->name('storeImport');
        Route::get('/create', [GroupController::class, 'create'])->name('create');
        Route::post('/', [GroupController::class, 'store'])->name('store');
        Route::get('/{group}/edit', [GroupController::class, 'edit'])->name('edit');
        Route::put('/{group}', [GroupController::class, 'update'])->name('update');
        Route::delete('/{group}', [GroupController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Group Categories
    |--------------------------------------------------------------------------
    */
    Route::prefix('categories')->as('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Post Reports
    |--------------------------------------------------------------------------
    */
    Route::prefix('reports')->as('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::post('/data', [ReportController::class, 'data'])->name('data');
        Route::get('/export', [ReportController::class, 'export'])->name('export');
    });

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    */
    Route::prefix('settings')->as('settings.')->group(function () {
        Route::get('/facebook', [SettingController::class, 'facebook'])->name('facebook');
        Route::post('/facebook', [SettingController::class, 'updateFacebook'])->name('facebook.update');
        Route::post('/facebook/test', [SettingController::class, 'testFacebookConnection'])->name('facebook.test');
    });

    Route::namespace('User')->group(
        function () {
            Route::prefix('/password')->as('password.')->group(
                function () {
                    Route::get('/edit', 'ChangePasswordController@edit')->name('edit');
                    Route::post('/', 'ChangePasswordController@update')->name('store');
                }
            );
            Route::middleware('permission:view-role')->group(
                function () {
                    Route::resource('role', 'RoleController')->except(['destroy']);
                    Route::post('/role/data', 'RoleController@data')->name('role.data');
                }
            );
            Route::middleware('permission:view-user')->group(
                function () {
                    Route::resource('user', 'UserController');
                    Route::post('/user/data', 'UserController@data')->name('user.data');
                    Route::post('/user/bulk', 'UserController@bulk')->name('user.bulk');
                }
            );
        }
    );
});
