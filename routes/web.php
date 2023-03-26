<?php

use App\Http\Controllers\Blog\Admin\CategoryController;
use App\Http\Controllers\Blog\Admin\MainController;
use App\Http\Controllers\Blog\Admin\OrderConrtroller;
use App\Http\Controllers\Blog\Admin\ProductController;
use App\Http\Controllers\Blog\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

/** Admin Side **/


Route::middleware(['status', 'auth'])->group(function (){
    $groupdData = [
        'namespace' => 'App\Http\Controllers\Blog\Admin',
        'prefix' => 'admin'
    ];


    Route::group($groupdData, function(){


        /** Orders Routes */
        Route::resource('/index', MainController::class)
            ->names('blog.admin.index');

        Route::resource('/orders', OrderConrtroller::class)
            ->names('blog.admin.orders');

        Route::get('/orders/change/{id}', [\App\Http\Controllers\Blog\Admin\OrderConrtroller::class, 'change'])
            ->name('blog.admin.orders.change');

        Route::post('/orders/save/{id}', [\App\Http\Controllers\Blog\Admin\OrderConrtroller::class, 'save'])
            ->name('blog.admin.orders.save');

        Route::delete('/orders/destroy/{id}', [\App\Http\Controllers\Blog\Admin\OrderConrtroller::class, 'destroy'])
            ->name('blog.admin.orders.destroy');

        Route::get('/orders/forcedestroy/{id}', [\App\Http\Controllers\Blog\Admin\OrderConrtroller::class, 'forcedestroy'])
            ->name('blog.admin.orders.forcedestroy');


        /** Category Routes */
        Route::get('/categories/mydel',[CategoryController::class, 'mydel'])
            ->name('blog.admin.categories.mydel');

        Route::resource('/categories', CategoryController::class)
            ->names('blog.admin.categories');

        Route::resource('/users', UserController::class)
            ->names('blog.admin.users');


        /** Product Routes */
        Route::resource('/products',ProductController::class)
            ->names('blog.admin.products');









    });

});

Route::get('user/index', [App\Http\Controllers\Blog\User\MainController::class, 'index']);
