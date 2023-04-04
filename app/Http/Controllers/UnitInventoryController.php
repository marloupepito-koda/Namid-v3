<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnitInventory;
class UnitInventoryController extends Controller
{
    
   
    public function get_each_ticket_in_unit($ticketid){
        $tickets= UnitInventory::where('id',$ticketid)->first();
         return response()->json([
        'status' => $tickets
        ]);
    }
public function get_unit_inventory($unitid,$eventid){
        $tickets= UnitInventory::where([['status','=',null],['unitid','=',$unitid]])->get();
         return response()->json([
        'status' => $tickets
        ]);
   }
    public function get_unit_ticket_inventory($id){
        $tickets= UnitInventory::where('unitid',$id)->get();
         return response()->json([
                'status' =>$tickets
                ]);
    }
     public function create_unit_ticket_inventory(Request $request){
         UnitInventory::create(
                $request->validate([
                    'unitid' => ['required'],
                    'ticket_type' => ['required'],
                    'ticket_name' => ['required'],
                    'bind' => ['required'],
                    'count' => ['required'],
                    'start' => ['required'],
                    'end' => ['required'],
                    'date' => ['required'],
                    'quantity' => ['required']
                ])
            );

             return response()->json([
                'status' =>'success'
                ]);
            
     }
}
