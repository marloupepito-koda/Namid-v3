<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventBags;
class EventBagsController extends Controller
{

    
        public function return_bag(Request $request){
            if($request->return === 'back'){
                $bags= EventBags::where('id','=',$request->id)->update([
                    'returned' =>null
                ]);
             return response()->json([
                'status' => $bags
                ]);
            }else{
                 $bags= EventBags::where('id','=',$request->id)->update([
                    'returned' =>$request->return
                ]);
            }
            
        }

      public function get_returned_bag($unitid,$type){
        $bags= EventBags::where([['unitid','=',$unitid],['returned','=',$type]])->get();

        return response()->json([
                'status' => $bags
                ]);
      }
    
        public function edit_bag_info(Request $request){
            EventBags::where('id',$request->id)
            ->update(
                $request->validate([
                    'seller' => ['required'],
                    'bag_name' => ['required'],
                    'location' => ['required'],
                ])
            );
        }
    public function get_event_bags($unitid,$eventid,$type){
       $bags =  EventBags::where([['unitid','=',$unitid],['eventid','=',$eventid],['returned','=',null]])->get();

        return response()->json([
                'status' => $bags
                ]);
    }
      public function create_bag(Request $request){
                EventBags::create($request->validate([
                    'unitid' => ['required'],
                    'eventid' => ['required'],
                    'bag_name' => ['required'],
                    'date' => ['required'],
                ]));

                return response()->json([
                'status' => 'success'
                ]);
      }
}
