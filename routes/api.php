<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\UnitInventoryController;
use App\Http\Controllers\EventInventoryController;
use App\Http\Controllers\EventBagsController;
use App\Http\Controllers\TicketBagsController;

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
Route::get('/get_each_ticket_in_event_inventory/{id}', [EventInventoryController::class, 'get_each_ticket_in_event_inventory']);


Route::get('/get_event_bags/{unitid}/{eventid}/{type}', [EventBagsController::class, 'get_event_bags']);
Route::get('/get_returned_bag/{unitid}/{returned}', [EventBagsController::class, 'get_returned_bag']);


Route::get('/get_tickets_in_bag/{unitid}/{eventid}/{bagid}', [TicketBagsController::class, 'get_tickets_in_bag']);


