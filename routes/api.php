<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\State;
use App\Models\City;
use App\Models\Area;
use App\Http\Controllers\LocationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
 * Location routes for dropdowns without middleware
 */
Route::get('/states/{state}/cities', [LocationController::class, 'getCities']);
Route::get('/cities/{city}/areas', [LocationController::class, 'getAreas']);
