<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventInventory;
use App\Models\UnitInventory;
class EventInventoryController extends Controller
{
     public function get_each_ticket_in_event_inventory($id){
        $tickets= EventInventory::where('id',$id)->first();
         return response()->json([
        'status' => $tickets
        ]);
    }
      public function edit_ticket_inventory(Request $request){
          EventInventory::where('id',$request->id)
            ->update(
                $request->validate([
                    'start' => ['required'],
                    'end' => ['required'],
                    'price' => ['required'],
                    'count' => ['required'],
                ])
            );
      }
    public function get_ticket_inventory($unitid,$eventid){
    $event = EventInventory::where([['status','=',null],['unitid','=',$unitid],['eventid','=',$eventid]])->get();
            return response()->json([
                        'status' => $event
                        ]);
        }
   public function add_ticket_in_event(Request $request){
        $unitticket = UnitInventory::where('id',$request->id)->first();
         $quantity = intval($request->end) - intval($request->start)+1;
             EventInventory::create([
                    'unitid' => $unitticket->unitid,
                    'eventid' =>$request->id,
                    'ticket_type' =>$unitticket->ticket_type,
                    'ticket_name' =>$unitticket->ticket_name,
                    'price' =>$request->price,
                    'bind' =>$unitticket->bind,
                    'count' =>$unitticket->count,
                    'start' =>$request->start,
                    'end' =>$request->end,
                    'date' =>$request->date,
                    'quantity' =>$quantity,
                    'remaining' =>$quantity
                ]);

         return response()->json([
        'status' => $unitticket
        ]);
   }
}
