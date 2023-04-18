<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitsController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\UnitInventoryController;
use App\Http\Controllers\EventInventoryController;
use App\Http\Controllers\EventBagsController;
use App\Http\Controllers\TicketBagsController;
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



Route::middleware('auth:sanctum')->get('/user', function (Request $request){
    return $request->user();
});

Route::middleware('auth:sanctum')->get('/authenticated', function (){
    return true;
});

Route::get('{vue?}', function(){
    return view('welcome');
})->where('vue','[\/\w\.-]*');
Route::get('/', function () {
    return view('welcome');
});

Route::post('/add_user_account', [UserController::class, 'add_user_account']);
Route::put('/edit_every_user', [UserController::class, 'edit_every_user']);
Route::delete('/delete_user/{id}', [UserController::class, 'delete_user']);
Route::post('/edit_access_user', [UserController::class, 'edit_access_user']);
Route::post('/ee_login_users', [UserController::class, 'ee_login_users']);
Route::post('/ee_user_logout', [UserController::class, 'ee_user_logout']);

Route::post('/create_unit', [UnitsController::class, 'create_unit']);
Route::put('/edit_unit', [UnitsController::class, 'edit_unit']);

Route::post('/create_event', [EventsController::class, 'create_event']);
Route::put('/update_event', [EventsController::class, 'update_event']);

Route::post('/create_unit_ticket_inventory', [UnitInventoryController::class, 'create_unit_ticket_inventory']);


Route::post('/add_ticket_in_event', [EventInventoryController::class, 'add_ticket_in_event']);
Route::put('/edit_ticket_inventory', [EventInventoryController::class, 'edit_ticket_inventory']);


Route::post('/create_bag', [EventBagsController::class, 'create_bag']);
Route::put('/edit_bag_info', [EventBagsController::class, 'edit_bag_info']);
Route::put('/return_bag', [EventBagsController::class, 'return_bag']);

Route::post('/add_ticket_in_bag', [TicketBagsController::class, 'add_ticket_in_bag']);
Route::post('/create_ticket_sold', [TicketBagsController::class, 'create_ticket_sold']);
Route::put('/edit_ticket_price_in_bag', [TicketBagsController::class, 'edit_ticket_price_in_bag']);
Route::put('/transfer_ticket_in_another_bag', [TicketBagsController::class, 'transfer_ticket_in_another_bag']);
Route::put('/edit_ticket_sold', [TicketBagsController::class, 'edit_ticket_sold']);
Route::post('/uploading_ticket_in_bag', [TicketBagsController::class, 'uploading_ticket_in_bag']);

