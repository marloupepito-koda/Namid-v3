<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketBags;
use App\Models\EventInventory;
use App\Models\EventBags;
class TicketBagsController extends Controller
{

     
     public function transfer_ticket_in_another_bag(Request $request){
          $ticket = TicketBags::where('id',$request->ticketid)->first();
          if(intval($ticket->quantity) === intval($request->quantity)){
               TicketBags::where('id',$request->ticketid)->update([
                    'bagid' =>$request->bagid
               ]);
               return response()->json([
               'status' => 'success'
               ]);
          }else{
      
                    $starting1= $request->transfer === false?$request->start:$request->end +1;
                    $ending1= $request->transfer === false?$request->end:$request->endDefault;
                    $quantity1 = $request->transfer === false?($request->end - $request->start) +1:($request->endDefault - $request->end);

                    $starting2= $request->transfer === false?$request->end  +1:$request->start;
                    $ending2= $request->transfer === false?$request->endDefault:$request->end;
                    $quantity2 = $request->transfer === false?($request->endDefault - $request->end):($request->end - $request->start)+1;

                    $result= TicketBags::create([
                         'unitid' => $ticket->unitid,
                         'eventid' => $ticket->eventid,
                         'ticket_type' => $ticket->ticket_type,
                         'bagid' => $request->bagid,
                         'seller' => $ticket->seller,
                         'ticketid' => $ticket->ticketid,
                         'start' => $starting1,
                         'end' => $ending1,
                         'price' => $ticket->price,
                         'bind' => $ticket->bind,
                         'count' => $ticket->count,
                         'date' => $ticket->date,
                         'quantity' =>$quantity1,
                         'status' =>'Unsold',
                    ]);

                    
                    if($result){
                         TicketBags::where('id',$request->ticketid)->update([
                              'start' =>$starting2,
                              'end' =>$ending2,
                              'quantity' =>$quantity2,
                         ]);
                    }
               
               return response()->json([
               'status' => 'success'
               ]);
          }
          
     }
     public function edit_ticket_price_in_bag(Request $request){
          TicketBags::where('id',$request->id)->update([
               'price' => $request->price
          ]);
            return response()->json([
          'status' => 'success'
          ]);
     }
    public function create_ticket_sold(Request $request){

         $total =intval($request->end) - intval($request->start) +1;
         $ticket =  TicketBags::where('id','=',$request->id)->first();
         $totalResult = intval($ticket->quantity) - $total;
          if($totalResult === 0){
               TicketBags::where('id','=',$request->id)->update([
                    'status' =>'Sold'
               ]);
                 return response()->json([
                  'status' => 'success'
                ]);
          }else{
               $newStart= $ticket->start + $total;
               $newEnd= $ticket->start + $total;

                   //this is new create data
                   $result= TicketBags::create([
                         'unitid' => $ticket->unitid,
                         'eventid' => $ticket->eventid,
                         'ticket_type' => $ticket->ticket_type,
                         'bagid' => $ticket->bagid,
                         'seller' => $ticket->seller,
                         'ticketid' => $ticket->ticketid,
                         'start' => $newStart,
                         'end' => $ticket->end,
                         'price' => $ticket->price,
                         'bind' => $ticket->bind,
                         'count' => $ticket->count,
                         'date' => $ticket->date,
                         'quantity' =>$totalResult,
                         'status' =>'Unsold',
                    ]);

                    if($result){
                         //this is old data
                         $update=TicketBags::where('id','=',$request->id)->update([
                         'end' =>$newEnd -1,
                         'status' =>'Sold',
                         'quantity' =>$total,
                         'date' =>$request->date,
                        ]);
                    }

                    

               
                    return response()->json([
                  'status' => 'success'
                ]);


          }
              

    }
    public function get_tickets_in_bag($unitid,$eventid,$bagid,$type){
         if ($type === 'all') {
               $bags = TicketBags::where([
                         ['unitid', '=', $unitid],
                         ['eventid', '=', $eventid],
                         ['bagid', '=', $bagid],
               ])->get();
               return response()->json([
               'status' => $bags
               ]);
          } else if ($type === 'sold') {
                $bags = TicketBags::where([
                         ['unitid', '=', $unitid],
                         ['eventid', '=', $eventid],
                         ['bagid', '=', $bagid],
                         ['status', '=', 'Sold']
               ])->get();
               return response()->json([
               'status' => $bags
               ]);
          } else {
                $bags = TicketBags::where([
                         ['unitid', '=', $unitid],
                         ['eventid', '=', $eventid],
                         ['bagid', '=', $bagid],
                         ['status', '=', 'Unsold']
               ])->get();
               return response()->json([
               'status' => $bags
               ]);
          }
          
     }

    public function add_ticket_in_bag(Request $request){

       $ei = EventInventory::where('id',$request->ticketid)->first();
       $bag = EventBags::where('id',$request->bagid)->first();
       $quantity = intval($request->end )- intval($request->start) +1;
          // this is the inserting ticket in a bag
                $result = TicketBags::create([
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
                    'quantity' =>$quantity,
                    'status' =>$request->status,
          //
                ]);
                if($result){
                     if((intval($request->start) - intval($ei->start)) !== 0){

                         $firstQuantity = ($request->start - $ei->start);
                         $firstStart = $ei->start;
                         $firstEnd = $request->start;
                         EventInventory::where('id',$request->ticketid)->update([
                              'quantity' =>$firstQuantity,
                              'remaining' =>$firstQuantity,
                              'start' =>$firstStart,
                              'end' =>$firstEnd -1
                         ]);
                         
                     }if((intval($ei->end) - intval($request->end))  !== 0){
                         $endQuantity = ($ei->end - $request->end);
                         $endStart = $request->end;
                         $endEnd = $ei->end;

                          EventInventory::create([
                              'unitid' => $ei->unitid,
                              'eventid' =>$ei->eventid,
                              'ticket_type' =>$ei->ticket_type,
                              'ticket_name' =>$ei->ticket_name,
                              'price' =>$ei->price,
                              'bind' =>$ei->bind,
                              'count' =>$ei->count,
                              'start' =>$endStart,
                              'end' =>$endEnd,
                              'date' =>$ei->date,
                              'quantity' =>$endQuantity,
                              'remaining' =>$endQuantity
                         ]);
                     }
                     
                     EventInventory::where('id',$request->ticketid)->update([
                          'quantity' => $ei->quantity - $quantity,
                     ]);
                }
             return response()->json([
                'status' =>'success'
                ]);
            
     }
}
