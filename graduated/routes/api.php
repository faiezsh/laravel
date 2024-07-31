<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\GategoryController;
use App\Http\Controllers\Model3DController;
use App\Http\Controllers\recommendationSystem;
use App\Http\Controllers\RoomModel3DController;
use App\Http\Controllers\SupgategoryController;
use App\Http\Controllers\WallColorsController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
/**
 * User and company
 */
Route::post('/auth/register', 'App\Http\Controllers\Api\AuthController@creatcompany');
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/showUser', [AuthController::class, 'showUser'])->middleware('auth:sanctum');
Route::post('/auth/register/company', [AuthController::class, 'createUser'])->middleware('auth:sanctum');
Route::get('/showUserSup', [AuthController::class, 'showUser_subCompany'])->middleware('auth:sanctum');
Route::get('/showCompany', [AuthController::class, 'showCompany'])->middleware('auth:sanctum');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
/**
 * Category
 */

Route::post('/addCategory', [GategoryController::class, 'addGategory']);
Route::get('/showCategory', [GategoryController::class, 'showGategory']);
/**
 * SupCategory
 */
Route::post('/addSupCategory', [SupgategoryController::class, 'addSupGategory'])->middleware('auth:sanctum');
Route::get('/show_supCategory/{name}', [SupgategoryController::class, 'show_supCategory'])->middleware('auth:sanctum');
Route::get('/show_supCategory', [SupgategoryController::class, 'show_sup'])->middleware('auth:sanctum');
/**
 * Model3D
 */
Route::post('/addModel', [Model3DController::class, 'addModel3D'])->middleware('auth:sanctum');
Route::get('/get_models/{name}', [Model3DController::class, 'get_models'])->middleware('auth:sanctum');
Route::get('/showModel/{id}', [Model3DController::class, 'showModel3D'])->middleware('auth:sanctum');
Route::get('/get_model/{id}', [Model3DController::class, 'get_Modelby_ID'])->middleware('auth:sanctum');
Route::get('/models', [Model3DController::class, 'models'])->middleware('auth:sanctum');
/**
 * WallColor
 */
Route::post('/addcolor', [WallColorsController::class, 'addColor'])->middleware('auth:sanctum');
Route::get('/showcolor', [WallColorsController::class, 'showColor'])->middleware('auth:sanctum');
/**
 * RoomModel3D
 */
Route::post('/addRoom', [RoomModel3DController::class, 'roomDesign'])->middleware('auth:sanctum');
Route::get('/designDisplay/{roll}', [RoomModel3DController::class, 'designDisplay'])->middleware('auth:sanctum');
Route::get('/showdesignDisplay/{userName}', [RoomModel3DController::class, 'showdesignDisplay'])->middleware('auth:sanctum');
Route::get('/showdesignDisplaybyUser', [RoomModel3DController::class, 'showdesignDisplaybyUser'])->middleware('auth:sanctum');
Route::get('/get_room/{id}', [RoomModel3DController::class, 'get_room'])->middleware('auth:sanctum');

/**
 * generate room
 */
Route::post('generate', [recommendationSystem::class, 'generateRoom'])->middleware('auth:sanctum');
