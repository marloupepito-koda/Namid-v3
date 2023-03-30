<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\UnitInventoryController;
use App\Http\Controllers\EventInventoryController;
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


Route::get('/get_all_units', [UnitsController::class, 'get_all_units']);
Route::delete('/delete_unit/{id}', [UnitsController::class, 'delete_unit']);

Route::get('/get_events_in_unit/{id}', [EventsController::class, 'get_events_in_unit']);
Route::delete('/delete_event/{id}', [EventsController::class, 'delete_event']);

Route::get('/get_unit_ticket_inventory/{id}', [UnitInventoryController::class, 'get_unit_ticket_inventory']);

Route::get('/get_unit_inventory/{unitid}/{eventid}', [UnitInventoryController::class, 'get_unit_inventory']);
Route::get('/get_each_ticket_in_unit/{ticketid}', [UnitInventoryController::class, 'get_each_ticket_in_unit']);


Route::get('/get_ticket_inventory/{unitid}/{eventid}', [EventInventoryController::class, 'get_ticket_inventory']);
