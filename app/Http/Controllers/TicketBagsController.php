<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TicketBags;
use App\Models\EventInventory;
use App\Models\EventBags;
use App\Models\Events;
use App\Models\Units;
use Illuminate\Support\Facades\DB;
class TicketBagsController extends Controller
{

     public function get_committee_settlement($unitid,$eventid){
           
             $sold = TicketBags::where([['eventid','=' , $eventid],['unitid','=' , $unitid],['status','=','Sold']])->get();
             return response()->json([
                'status' =>  $sold,
                ]);
     }
     
      public function search_spot_transaction($unitid,$eventid,$start,$end){
           $events= Events::where('id', '=' ,$eventid)->first();
               $units = Units::where('id', '=' ,$unitid)->first();
               $sold = DB::table('event_bags')
               ->whereBetween('ticket_bags.date', [$start, $end])
               ->join('ticket_bags', 'event_bags.unitid', '=', 'ticket_bags.unitid')
               ->where([['ticket_bags.unitid','=',$unitid],['ticket_bags.eventid','=',$eventid],['ticket_bags.status','=','sold']])
               ->get();
               
               return response()->json([
                    'status' => $units,
                    'transaction' =>$units,
                    'sold' =>$sold,
               ]);
      }
          public function get_event_ticket_sold_history($unitid,$eventid){
            $result = DB::table('event_bags')
            ->join('ticket_bags', 'event_bags.unitid', '=', 'ticket_bags.unitid')
            ->where([['ticket_bags.unitid','=',$unitid],['ticket_bags.eventid','=',$eventid],['ticket_bags.status','=','sold']])
            ->get();
               return response()->json([
               'status' => $result
               ]);
          }
     
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


     $ticket =  TicketBags::where('id','=',$request->id)->first();
     
     
          
          if((intval($request->start) - intval($ticket->start)) === 0 && (intval($ticket->end) - intval($request->end))  !== 0){
                   
                       $result= TicketBags::create([
                         'unitid' => $ticket->unitid,
                         'eventid' => $ticket->eventid,
                         'ticket_type' => $ticket->ticket_type,
                         'bagid' => $ticket->bagid,
                         'seller' => $ticket->seller,
                         'ticketid' => $ticket->ticketid,
                         'start' => $request->end+1,
                         'end' => $ticket->end,
                         'price' => $ticket->price,
                         'bind' => $ticket->bind,
                         'count' => $ticket->count,
                         'date' => $ticket->date,
                         'quantity' =>$ticket->end - $request->end +1,
                         'status' =>'Unsold',
                    ]);

                    if($result){
                         $update=TicketBags::where('id','=',$request->id)->update([
                         'end' =>$request->end,
                         'status' =>'Sold',
                         'quantity' =>intval($request->end) - intval($request->start) +1,
                         'date' =>$request->date,
                        ]);
                    }
          }else if((intval($request->start) - intval($ticket->start)) !== 0 && (intval($ticket->end) - intval($request->end))  === 0){
              
                  $result= TicketBags::create([
                    'unitid' => $ticket->unitid,
                    'eventid' => $ticket->eventid,
                    'ticket_type' => $ticket->ticket_type,
                    'bagid' => $ticket->bagid,
                    'seller' => $ticket->seller,
                    'ticketid' => $ticket->ticketid,
                    'start' => $ticket->start,
                    'end' => $request->start -1,
                    'price' => $ticket->price,
                    'bind' => $ticket->bind,
                    'count' => $ticket->count,
                    'date' => $ticket->date,
                    'quantity' =>$request->start - $ticket->start,
                    'status' =>'Unsold',
               ]);
               

               if($result){
                    $update=TicketBags::where('id','=',$request->id)->update([
                    'start' =>$request->start,
                    'status' =>'Sold',
                    'quantity' =>$request->end - $request->start + 1,
                    'date' =>$request->date,
                   ]);
               }   
          }else if((intval($request->start) - intval($ticket->start)) !== 0 && (intval($ticket->end) - intval($request->end))  !== 0 ){
               
                    $update=TicketBags::where('id','=',$request->id)->update([
                    'start' =>$request->start,
                    'end' =>$request->end,
                    'status' =>'Sold',
                    'quantity' =>$request->end - $request->start + 1,
                    'date' =>$request->date,
                   ]);

                   $firstQuantity = ($request->start - $ticket->start) ;
                   $firstStart = $ticket->start;
                   $firstEnd = $request->start -1;

                TicketBags::create([
                    'unitid' => $ticket->unitid,
                    'eventid' => $ticket->eventid,
                    'ticket_type' => $ticket->ticket_type,
                    'bagid' => $ticket->bagid,
                    'seller' => $ticket->seller,
                    'ticketid' => $ticket->ticketid,
                    'start' => $firstStart,
                    'end' =>$firstEnd,
                    'price' => $ticket->price,
                    'bind' => $ticket->bind,
                    'count' => $ticket->count,
                    'date' => $ticket->date,
                    'quantity' =>$firstQuantity,
                    'status' =>'Unsold',
               ]);

               $endQuantity = ($ticket->end - $request->end);
               $endStart = $request->end + 1;
               $endEnd = $ticket->end;

               TicketBags::create([
                    'unitid' => $ticket->unitid,
                    'eventid' => $ticket->eventid,
                    'ticket_type' => $ticket->ticket_type,
                    'bagid' => $ticket->bagid,
                    'seller' => $ticket->seller,
                    'ticketid' => $ticket->ticketid,
                    'start' => $endStart,
                    'end' =>$endEnd,
                    'price' => $ticket->price,
                    'bind' => $ticket->bind,
                    'count' => $ticket->count,
                    'date' => $ticket->date,
                    'quantity' =>$endQuantity,
                    'status' =>'Unsold',
               ]);
               
          }else{
                       TicketBags::where('id','=',$request->id)->update([
                         'status' =>'Sold',
                         'date' =>$request->date,
                        ]);
          }
     
      return response()->json([
           'status' =>(intval($ticket->end) - intval($request->end)) 
         ]); 
              

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
                if($quantity === $ei->quantity){
                    EventInventory::where('id',$request->ticketid)->update([
                         'status' => 'Used',
                    ]);
                }else if((intval($request->start) - intval($ei->start)) === 0 && (intval($ei->end) - intval($request->end))  !== 0){  
                    EventInventory::where('id',$request->ticketid)->update([
                         'quantity' =>$request->end -$request->start +1,
                         'remaining' =>$request->end -$request->start +1,
                         'start' =>$request->start,
                         'end' =>$request->end,
                         'status' =>'Used'
                    ]);

                    EventInventory::create([
                         'unitid' => $request->unitid,
                         'eventid' => $request->eventid,
                         'ticket_type' => $ei->ticket_type,
                         'ticket_name' => $ei->ticket_name,
                         'bagid' => $request->bagid,
                         'seller' => $bag->seller,
                         'ticketid' => $request->ticketid,
                         'start' => $request->end+1,
                         'end' => $ei->end,
                         'price' => $ei->price,
                         'bind' => $ei->bind,
                         'count' => $ei->count,
                         'date' => $request->date,
                         'quantity' =>$ei->end - $request->end,
                         'remaining' =>$ei->end - $request->end,
                     ]);
                }else if((intval($request->start) - intval($ei->start)) !== 0 && (intval($ei->end) - intval($request->end)) === 0){
                    EventInventory::where('id',$request->ticketid)->update([
                         'quantity' =>$request->end -$request->start +1,
                         'remaining' =>$request->end -$request->start +1,
                         'start' =>$request->start,
                         'end' =>$request->end,
                         'status' =>'Used'
                    ]);

                    EventInventory::create([
                         'unitid' => $request->unitid,
                         'eventid' => $request->eventid,
                         'ticket_type' => $ei->ticket_type,
                         'ticket_name' => $ei->ticket_name,
                         'bagid' => $request->bagid,
                         'seller' => $bag->seller,
                         'ticketid' => $request->ticketid,
                         'start' => $ei->start,
                         'end' => $request->start - 1,
                         'price' => $ei->price,
                         'bind' => $ei->bind,
                         'count' => $ei->count,
                         'date' => $request->date,
                         'quantity' =>$request->start - $ei->start,
                         'remaining' =>$request->start - $ei->start,
                     ]);
                }else if((intval($request->start) - intval($ei->start)) !== 0 && (intval($ei->end) - intval($request->end))  !== 0 ){
                    EventInventory::where('id',$request->ticketid)->update([
                         'quantity' =>$request->end -$request->start +1,
                         'remaining' =>$request->end -$request->start +1,
                         'start' =>$request->start,
                         'end' =>$request->end,
                         'status' =>'Used'
                    ]);

                    $firstQuantity = ($request->start - $ei->start) ;
                    $firstStart = $ei->start;
                    $firstEnd = $request->start -1;

                    EventInventory::create([
                         'unitid' => $request->unitid,
                         'eventid' => $request->eventid,
                         'ticket_type' => $ei->ticket_type,
                         'ticket_name' => $ei->ticket_name,
                         'bagid' => $request->bagid,
                         'seller' => $bag->seller,
                         'ticketid' => $request->ticketid,
                         'start' => $firstStart,
                         'end' => $firstEnd,
                         'price' => $ei->price,
                         'bind' => $ei->bind,
                         'count' => $ei->count,
                         'date' => $request->date,
                         'quantity' =>$firstQuantity,
                         'remaining' =>$firstQuantity,
                     ]);

                    $endQuantity = ($ei->end - $request->end);
                    $endStart = $request->end + 1;
                    $endEnd = $ei->end;

                    EventInventory::create([
                         'unitid' => $request->unitid,
                         'eventid' => $request->eventid,
                         'ticket_type' => $ei->ticket_type,
                         'ticket_name' => $ei->ticket_name,
                         'bagid' => $request->bagid,
                         'seller' => $bag->seller,
                         'ticketid' => $request->ticketid,
                         'start' => $endStart,
                         'end' => $endEnd,
                         'price' => $ei->price,
                         'bind' => $ei->bind,
                         'count' => $ei->count,
                         'date' => $request->date,
                         'quantity' =>$endQuantity,
                         'remaining' =>$endQuantity,
                     ]);

                }
             return response()->json([
                'status' =>'success'
                ]);
            
     }
}
