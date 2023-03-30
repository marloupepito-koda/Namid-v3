<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketBags;
use App\Models\EventInventory;
use App\Models\EventBags;
class TicketBagsController extends Controller
{
    public function get_tickets_in_bag($unitid,$eventid,$bagid){
         $bags =  TicketBags::where([['unitid','=',$unitid],['eventid','=',$eventid],['bagid','=',$bagid]])->get();
         return response()->json([
                'status' => $bags
                ]);
     }

    public function add_ticket_in_bag(Request $request){

       $ei = EventInventory::where('id',$request->ticketid)->first();
       $bag = EventBags::where('id',$request->bagid)->first();
            TicketBags::create([
                    'unitid' => $request->unitid,
                    'eventid' => $request->eventid,
                    'ticket_type' => $ei->ticket_type,
                    'bagid' => $request->bagid,
                    'seller' => $bag->seller,
                    'ticketid' => $request->ticketid,
                    'start' => $request->start,
                    'end' => $request->end,
                    'price' => $ei->price,
                    'bind' => $ei->bind,
                    'count' => $ei->count,
                    'date' => $request->date,
                    'quantity' =>intval($request->end )- intval($request->start) +1,
                    'status' =>$request->status,
                ]);

             return response()->json([
                'status' =>'success'
                ]);
            
     }
}
