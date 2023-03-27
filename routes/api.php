<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ModulePermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * Authetication Routes
 */
Route::controller(AuthController::class)->group(function(){
    Route::post('register','register');
    Route::post('login','login');
    Route::get('verifyuser/{token}','verifyAccount');
    Route::post('forget-password','forgetPassword');
    Route::post('reset-password','resetPassword');
});


Route::middleware('auth:sanctum')->group(function(){
/**
 * User Routes
 */
Route::controller(UserController::class)->group(function(){
    Route::post('user-profile','userProfile');
    Route::post('change-password','changePassword');
    Route::get('get/{id}','get');
    Route::get('logout','logout');
    Route::patch('update','update');
});


/**
 * Module Routes
 */
Route::controller(ModuleController::class)->prefix('module')->group(function () {
    Route::get('/list', 'list');
    Route::post('/create','create');
    Route::patch('/update/{id}','update');
    Route::delete('delete/{id}', 'delete');
    Route::get('get/{id}', 'get');
});

/**
 * Permission Routes
 */
Route::controller(PermissionController::class)->prefix('permission')->group(function () {
    Route::get('/list', 'list');
    Route::post('/create','create');
    Route::patch('/update/{id}','update');
    Route::delete('delete/{id}', 'delete');
    Route::get('get/{id}', 'get');
});

/**
 * ModulePermission Routes
 */
Route::controller(ModulePermissionController::class)->prefix('module-permission')->group(function () {
    Route::get('/list', 'list');
    Route::post('/create','create');
    Route::patch('/update/{id}','update');
    Route::delete('delete/{id}', 'delete');
    Route::get('get/{id}', 'get');
});

/**
 * Role Route
 */
Route::controller(RoleController::class)->prefix('role')->group(function () {
    Route::get('/list', 'list');
    Route::post('/create','create');
    Route::patch('/update/{id}','update');
    Route::delete('delete/{id}', 'delete');
    Route::get('get/{id}', 'get');
});

});
