<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CondominoController;
use App\Http\Controllers\CuotaController;
use App\Http\Controllers\DetalleCuotaController;
use App\Http\Controllers\FondoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\FacturaProveedorController;
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

Route::prefix('api')->group( function () {

    //RUTAS ESPECIFICAS
    //Route::post('/user/upload', [UserController::class,'uploadImage']);
    //Route::get('/user/getimage/{filename}',[UserController::class,'getImage']);
    //Route::put('/user/update',[UserController::class,'update']);
    //RUTAS AUTOMATICAS RESTful
    Route::resource('/user', UserController::class,['except'=>['create','edit']]);
    Route::resource('/condomino', CondominoController::class,['except'=>['create','edit']]);
    Route::resource("/cuota", CuotaController::class,['except'=>['create','edit']]);
    Route::resource('/detallecuota', DetalleCuotaController::class,['except'=>['create','edit']]);
    Route::resource('/fondocondominal', FondoController::class,['except'=>['create','edit']]);
    Route::resource('/producto', ProductoController::class,['except'=>['create','edit']]);
    Route::resource('/facturaproveedor',FacturaProveedorController::class,['except'=>['create','edit']]);
    //return view('welcome');
});
