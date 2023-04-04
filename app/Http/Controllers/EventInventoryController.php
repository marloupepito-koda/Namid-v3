<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventInventory;
use App\Models\UnitInventory;
class EventInventoryController extends Controller
{
   
   public function search_ticket_where($id){
       $tickets= EventInventory::where('ticketid',$id)->first();
         return response()->json([
        'status' => $tickets
        ]);
   }
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
        $ui = UnitInventory::where('id',$request->id)->first();
         $quantity = intval($request->end) - intval($request->start)+1;

         if(intval($quantity) === intval($ui->quantity)){
            UnitInventory::where('id',$request->id)->update([
                'status' =>'Used'
            ]);
         }else if((intval($request->start) - intval($ui->start)) === 0 && (intval($ui->end) - intval($request->end))  !== 0){  
                 UnitInventory::where('id',$request->id)->update([
                         'quantity' =>$request->end -$request->start +1,
                         'start' =>$request->start,
                         'end' =>$request->end,
                         'status' =>'Used'
                    ]);

                    UnitInventory::create([
                        'unitid' => $ui->unitid,
                         'ticket_type' => $ui->ticket_type,
                         'ticket_name' => $ui->ticket_name,
                         'start' => $request->end+1,
                         'end' => $ui->end,
                         'price' => $ui->price,
                         'bind' => $ui->bind,
                         'count' => $ui->count,
                         'date' => $ui->date,
                         'quantity' =>$ui->end - $request->end,
                     ]);
         }else if((intval($request->start) - intval($ui->start)) !== 0 && (intval($ui->end) - intval($request->end)) === 0){
                     UnitInventory::where('id',$request->id)->update([
                         'quantity' =>$request->end -$request->start +1,
                         'start' =>$request->start,
                         'end' =>$request->end,
                         'status' =>'Used'
                    ]);

                     UnitInventory::create([
                         'unitid' => $ui->unitid,
                         'ticket_type' => $ui->ticket_type,
                         'ticket_name' => $ui->ticket_name,
                         'start' => $ui->start,
                         'end' => $request->start - 1,
                         'price' => $ui->price,
                         'bind' => $ui->bind,
                         'count' => $ui->count,
                         'date' => $request->date,
                         'quantity' =>$request->start - $ui->start,
                     ]);
         }else if((intval($request->start) - intval($ui->start)) !== 0 && (intval($ui->end) - intval($request->end))  !== 0 ){
                    UnitInventory::where('id',$request->id)->update([
                         'quantity' =>$request->end -$request->start +1,
                         'start' =>$request->start,
                         'end' =>$request->end,
                         'status' =>'Used'
                    ]);

                    $firstQuantity = ($request->start - $ui->start) ;
                    $firstStart = $ui->start;
                    $firstEnd = $request->start -1;

                    UnitInventory::create([
                         'unitid' => $ui->unitid,
                         'ticket_type' => $ui->ticket_type,
                         'ticket_name' => $ui->ticket_name,
                         'start' => $firstStart,
                         'end' => $firstEnd,
                         'price' => $ui->price,
                         'bind' => $ui->bind,
                         'count' => $ui->count,
                         'date' => $request->date,
                         'quantity' =>$firstQuantity,
                     ]);

                    $endQuantity = ($ui->end - $request->end);
                    $endStart = $request->end + 1;
                    $endEnd = $ui->end;


                    UnitInventory::create([
                         'unitid' => $ui->unitid,
                         'ticket_type' => $ui->ticket_type,
                         'ticket_name' => $ui->ticket_name,
                         'start' => $endStart,
                         'end' => $endEnd,
                         'price' => $ui->price,
                         'bind' => $ui->bind,
                         'count' => $ui->count,
                         'date' => $request->date,
                         'quantity' =>$endQuantity,
                     ]);
         }
             EventInventory::create([
                    'unitid' => $ui->unitid,
                    'ticketid' => $ui->id,
                    'eventid' =>$request->eventid,
                    'ticket_type' =>$ui->ticket_type,
                    'ticket_name' =>$ui->ticket_name,
                    'price' =>$request->price,
                    'bind' =>$ui->bind,
                    'count' =>$ui->count,
                    'start' =>$request->start,
                    'end' =>$request->end,
                    'date' =>$request->date,
                    'quantity' =>$quantity,
                    'remaining' =>$quantity,
                    'numbered' =>$request->numbered,
                    'iatp' =>$request->iatp,
                    'tt' =>$request->tt,
                    'pop' =>$request->pop
                ]);

         return response()->json([
        'status' => $ui
        ]);
   }
}
