<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JobController;
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

/**
 * UnAuthetication Routes
 */
Route::controller(AuthController::class)->group(function(){
    Route::post('register','register');
    Route::post('login','login');
    Route::get('verifyuser/{token}','verifyAccount');
    Route::post('forget-password','forgetPassword');
    Route::post('reset-password','resetPassword');
});

/**
 * Authetication Routes
 */
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
    Route::post('update','update');
    Route::delete('delete','delete');
});
/**
 * Job Module Route With Permission Middleware
 */
Route::controller(JobController::class)->prefix('job')->group(function(){
    Route::get('/view', 'view')->middleware(['permission:job,view']);
    Route::post('/create','create')->middleware(['permission:job,create']);
    Route::patch('/update/{id}','update')->middleware(['permission:job,update']);
    Route::delete('/delete/{id}', 'delete')->middleware(['permission:job,delete']);
    Route::get('get/{id}','get')->middleware('permission:job,view');
});
/**
 * employee Route With Permission Middleware
 */
Route::controller(EmployeeController::class)->prefix('employee')->group(function(){
    Route::get('/view', 'list')->middleware(['permission:employee,view']);
    Route::post('/create','create')->middleware(['permission:employee,create']);
    Route::patch('/update/{id}','update')->middleware(['permission:employee,update']);
    Route::delete('/delete/{id}', 'delete')->middleware(['permission:employee,delete']);
    Route::get('/get/{id}','get')->middleware('permission:employee,view');
});

/**
 * Company Route With Permission Middleware
 */
Route::controller(CompanyController::class)->prefix('company')->group(function(){
    Route::get('/view', 'list')->middleware(['permission:company,view']);
    Route::post('/create','create')->middleware(['permission:company,create']);
    Route::patch('/update/{id}','update')->middleware(['permission:company,update']);
    Route::delete('/delete/{id}', 'delete')->middleware(['permission:company,delete']);
    Route::get('/get/{id}','get')->middleware('permission:company,view');
});
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

