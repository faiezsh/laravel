<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\GategoryController;
use App\Http\Controllers\Model3DController;
use App\Http\Controllers\Model3DDSController;
use App\Http\Controllers\Model3DsupgategoryController;
use App\Http\Controllers\OfersController;
use App\Http\Controllers\RoomModel3DController;
use App\Http\Controllers\RoomShepersController;
use App\Http\Controllers\SupgategoryController;
use App\Http\Controllers\WallColorsController;

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

