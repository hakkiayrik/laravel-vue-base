<?php

use App\Http\Controllers\Api\v1\Panel\AdminController;
use App\Http\Controllers\Api\v1\Panel\AttributeController;
use App\Http\Controllers\Api\v1\Panel\AuthController;
use App\Http\Controllers\Api\v1\Panel\AvatarController;
use App\Http\Controllers\Api\v1\Panel\CategoryController;
use App\Http\Controllers\Api\v1\Panel\LogController;
use App\Http\Controllers\Api\v1\Panel\MediaController;
use App\Http\Controllers\Api\v1\Panel\PostController;
use App\Http\Controllers\Api\v1\Panel\RoleController;
use App\Http\Controllers\Api\v1\Panel\UserController;
use App\Http\Controllers\Api\v1\Panel\LanguageController;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('Api\v1')->prefix('v1')->group(function () {
	Route::namespace('Panel')->prefix('panel')->group(function () {
		Route::post('login', [AuthController::class, 'login']);
		Route::post('password/forgot', [AuthController::class, 'forgotPassword']);
		Route::get('password/find/{token}', [AuthController::class, 'findToken']);
		Route::match(['PUT', 'PATCH'], 'password/reset', [AuthController::class, 'resetPassword']);

		Route::middleware('auth:panel')->group(function (){

			Route::get('avatar', [AvatarController::class, 'index'])->name('avatar.index');

			Route::get('log', [LogController::class, 'index'])->name('log.index')->middleware(['permission:access-log']);

            Route::get('language', [LanguageController::class, 'index'])->name('language.index')->middleware(['permission:access-language']);
            Route::match('post', 'language/active/{language}', [LanguageController::class, 'updateActive'])->name('language.update-active')->middleware(['permission:edit-language']);

			Route::get('profile', [AuthController::class, 'getUser']);
			Route::match(['PUT', 'PATCH'], 'profile', [AuthController::class, 'updateUser']);
			Route::match(['PUT', 'PATCH'], 'profile/password', [AuthController::class, 'updatePassword']);

			Route::get('role', [RoleController::class, 'index'])->name('role.index')->middleware(['permission:access-role']);
			Route::post('role', [RoleController::class, 'store'])->name('role.store')->middleware(['permission:create-role']);
			Route::get('role/{role}', [RoleController::class, 'show'])->name('role.show')->middleware(['permission:edit-role']);
			Route::match(['PUT', 'PATCH'], 'role/{role}', [RoleController::class, 'update'])->name('role.update')->middleware(['permission:edit-role']);
			Route::delete('role/{role}', [RoleController::class, 'destroy'])->name('role.delete')->middleware(['permission:delete-role']);

			Route::get('admin', [AdminController::class, 'index'])->name('admin.index')->middleware(['permission:access-admin']);
			Route::get('admin/{admin}', [AdminController::class, 'show'])->name('admin.show')->middleware(['permission:edit-admin']);
			Route::post('admin', [AdminController::class, 'store'])->name('admin.store')->middleware(['permission:create-admin']);
			Route::match(['PUT', 'PATCH'], 'admin/{admin}', [AdminController::class, 'update'])->name('admin.update')->middleware(['permission:edit-admin']);
			Route::delete('admin/{admin}', [AdminController::class, 'destroy'])->name('admin.delete')->middleware(['permission:delete-admin']);

			Route::get('user', [UserController::class, 'index'])->name('user.index')->middleware(['permission:access-user']);
			Route::get('user/{user}', [UserController::class, 'show'])->name('user.show')->middleware(['permission:edit-user']);
			Route::match(['PUT', 'PATCH'], 'user/{user}', [UserController::class, 'update'])->name('user.update')->middleware(['permission:edit-user']);
			Route::get('user/log/{user}', [UserController::class, 'userLogs'])->name('user.user-logs')->middleware(['permission:edit-user']);
			Route::delete('user/{user}', [UserController::class, 'destroy'])->name('user.delete')->middleware(['permission:delete-user']);

			Route::get('category', [CategoryController::class, 'index'])->name('category.index')->middleware(['permission:access-category']);
			Route::get('category/{category}', [CategoryController::class, 'show'])->name('category.show')->middleware(['permission:edit-category']);
			Route::post('category', [CategoryController::class, 'store'])->name('category.store')->middleware(['permission:create-category']);
			Route::match(['PUT', 'PATCH'], 'category/{category}', [CategoryController::class, 'update'])->name('category.update')->middleware(['permission:edit-category']);
			Route::delete('category/{category}', [CategoryController::class, 'destroy'])->name('category.delete')->middleware(['permission:delete-category']);
            Route::post('category/update/sort', [CategoryController::class, 'updateDisplayOrder'])->name('category.update-order')->middleware(['permission:edit-category']);

			Route::get('post', [PostController::class, 'index'])->name('post.index')->middleware(['permission:access-post']);
			Route::get('post/{post}', [PostController::class, 'show'])->name('post.show')->middleware(['permission:edit-post']);
			Route::post('post', [PostController::class, 'store'])->name('post.store')->middleware(['permission:create-post']);
			Route::post('post/{post}', [PostController::class, 'update'])->name('post.update')->middleware(['permission:edit-post']);
			Route::delete('post/{post}', [PostController::class, 'destroy'])->name('post.delete')->middleware(['permission:delete-post']);

			Route::get('media', [MediaController::class, 'index'])->name('media.index');
			Route::post('media', [MediaController::class, 'store'])->name('media.store')->middleware(['permission:create-media']);
			Route::delete('media/{media}', [MediaController::class, 'destroy'])->name('media.delete')->middleware(['permission:delete-media']);

            Route::get('attribute/group', [AttributeController::class, 'index'])->name('attribute-group.index')->middleware(['permission:access-attribute']);
            Route::post('attribute/group', [AttributeController::class, 'store'])->name('attribute-group.store')->middleware(['permission:create-attribute']);
            Route::get('attribute/group/{group}', [AttributeController::class, 'show'])->name('attribute-group.show')->middleware(['permission:edit-attribute']);
            Route::match(['PUT', 'PATCH'], 'attribute/group/{group}', [AttributeController::class, 'update'])->name('attribute-group.update')->middleware(['permission:edit-attribute']);
            Route::delete('attribute/group/{group}', [AttributeController::class, 'destroy'])->name('attribute-group.delete')->middleware(['permission:delete-attribute']);

            Route::get('attribute', [AttributeController::class, 'getAttributes'])->name('attribute.index')->middleware(['permission:access-attribute']);
            Route::get('attribute/{attribute}', [AttributeController::class, 'getAttribute'])->name('attribute.show')->middleware(['permission:edit-attribute']);
            Route::post('attribute', [AttributeController::class, 'addAttribute'])->name('attribute-group.store')->middleware(['permission:create-attribute']);
            Route::match(['PUT', 'PATCH'], 'attribute/{group}', [AttributeController::class, 'editAttribute'])->name('attribute.update')->middleware(['permission:edit-attribute']);
            Route::delete('attribute/{attribute}', [AttributeController::class, 'deleteAttribute'])->name('attribute.delete')->middleware(['permission:delete-attribute']);

            Route::post('attribute/add/group/{group}', [AttributeController::class, 'addAttributeToGroup'])->name('attribute-add-group.store')->middleware(['permission:create-attribute']);


            Route::post('logout', [AuthController::class, 'logout']);
		});
	});
});
