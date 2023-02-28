<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Modules\Blog\Controllers\BlogController;
use App\Modules\Category\Controllers\CategoryController;
use App\Modules\Role\Controllers\RoleController;
use App\Modules\Comment\Controllers\CommentController;
use App\Modules\User\Controllers\UserController;
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

Route::post('login', [LoginController::class, 'login'])->name('login');
Route::post('register', [RegisterController::class, 'register'])->name(
    'register'
);
Route::group(
    ['middleware' => ['decoder', 'RemoteAuth', 'Permission']],
    function () {
        Route::post('register-admin', [
            RegisterController::class,
            'registerAdmin',
        ])->name('user-create');

        //Blog List
        Route::get('blog/index', [BlogController::class, 'index'])->name(
            'blogs-list'
        );

        // BLOGS by admin and manager
        Route::post('blog/create', [BlogController::class, 'store'])->name(
            'blogs-create'
        );
        Route::delete('blog/destroy/{tableId}', [
            BlogController::class,
            'destroy',
        ])->name('blogs-delete');
        Route::put('blog/update/{tableId}', [
            BlogController::class,
            'update',
        ])->name('blogs-edit');
        Route::get('blog/show/{tableId}', [
            BlogController::class,
            'show',
        ])->name('blogs-show');
        Route::get('blog/search', [BlogController::class, 'search'])->name(
            'blogs-search'
        );

        // Blog by User and manager
        Route::post('blog/create-by-user', [
            BlogController::class,
            'store',
        ])->name('blog-create');
        Route::delete('blog/destroy-by-user/{tableId}', [
            BlogController::class,
            'destroyByUser',
        ])->name('blog-delete');
        Route::put('blog/update-by-user/{tableId}', [
            BlogController::class,
            'update',
        ])->name('blog-edit');

        //Category
        Route::get('category/index', [
            CategoryController::class,
            'index',
        ])->name('category-list');
        Route::post('category/create', [
            CategoryController::class,
            'store',
        ])->name('category-create');
        Route::delete('category/destroy/{tableId}', [
            CategoryController::class,
            'destroy',
        ])->name('category-delete');
        Route::put('category/update/{tableId}', [
            CategoryController::class,
            'update',
        ])->name('category-edit');
        Route::get('category/show/{tableId}', [
            CategoryController::class,
            'show',
        ])->name('category-show');
        Route::get('category/search', [
            CategoryController::class,
            'search',
        ])->name('category-search');

        //Comment
        Route::post('comment/create', [
            CommentController::class,
            'store',
        ])->name('comment-create');
        Route::delete('comment/destroy/{tableId}', [
            CommentController::class,
            'destroy',
        ])->name('comment-delete');
        Route::put('comment/update/{tableId}', [
            CommentController::class,
            'update',
        ])->name('comment-edit');

        //Roles
        Route::get('role/index', [RoleController::class, 'index'])->name(
            'role-list'
        );
        Route::post('role/create', [RoleController::class, 'store'])->name(
            'role-create'
        );
        Route::delete('role/destroy/{tableId}', [
            RoleController::class,
            'destroy',
        ])->name('role-delete');
        Route::put('role/update/{tableId}', [
            RoleController::class,
            'update',
        ])->name('role-edit');

        // User
        Route::get('user/index', [UserController::class, 'index'])->name(
            'user-list'
        );
        Route::delete('user/destroy/{tableId}', [
            UserController::class,
            'destroy',
        ])->name('user-delete');
        Route::put('user/update', [UserController::class, 'update'])->name(
            'role-edit'
        );
    }
);

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
