<?php

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

use App\Http\Controllers\AccessController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\OriginController;
use App\Http\Controllers\EntryController;


Route::get('/login', [AccessController::class, 'login'])->name('login');
Route::post('/authentication', [AccessController::class, 'authentication']);
Route::get('/change-password', [AccessController::class, 'change_password'])->middleware('auth');
Route::post('/new-password', [AccessController::class, 'new_password'])->middleware('auth');
Route::get('/', [AccessController::class, 'index'])->name('home')->middleware('auth');
Route::post('/logout', [AccessController::class, 'logout'])->middleware('auth');

Route::prefix('user')->group(function(){
    Route::get('/', [UserController::class, 'index']);
    Route::get('/create', [UserController::class, 'create']);
    Route::post('/store', [UserController::class, 'store']);
    Route::get('/{id}', [UserController::class, 'edit']);
    Route::put('/update/{id}', [UserController::class, 'update']);
    Route::put('/function/{id}', [UserController::class, 'function']);
});

Route::prefix('module')->middleware('auth')->group(function(){
    Route::get('/', [ModuleController::class, 'index']);
    Route::get('/create', [ModuleController::class, 'create']);
    Route::post('/store', [ModuleController::class, 'store']);
    Route::get('/{id}', [ModuleController::class, 'edit']);
    Route::put('/update/{id}', [ModuleController::class, 'update']);
    Route::put('/destroy/{id}', [ModuleController::class, 'destroy']);
});

Route::prefix('patient')->middleware('auth')->group(function(){
    Route::get('/', [PatientController::class, 'index']);
    Route::get('/create', [PatientController::class, 'create']);
    Route::post('/store', [PatientController::class, 'store']);
    Route::get('/{id}', [PatientController::class, 'edit']);
    Route::put('/update/{id}', [PatientController::class, 'update']);
    Route::put('/active/{id}', [PatientController::class, 'active']);
});

Route::prefix('origin')->middleware('auth')->group(function(){
    Route::get('/', [OriginController::class, 'index']);
    Route::get('/create', [OriginController::class, 'create']);
    Route::post('/store', [OriginController::class, 'store']);
    Route::get('/{id}', [OriginController::class, 'edit']);
    Route::put('/update/{id}', [OriginController::class, 'update']);
    Route::put('/active/{id}', [OriginController::class, 'active']);
});

Route::prefix('entry')->middleware('auth')->group(function(){
    Route::get('load-patient', [EntryController::class, 'loadPatient'])->name('loadPatient');
    Route::get('/', [EntryController::class, 'index']);
    Route::post('/store/{id}', [EntryController::class, 'store']);
    Route::get('/pediatric/{id}', [EntryController::class, 'bpa_pediatric']);
    Route::get('/adult/{id}', [EntryController::class, 'bpa_adult']);
    Route::get('/obstetric/{id}', [EntryController::class, 'bpa_obstetric']);
});
