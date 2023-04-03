<?php

namespace App\Http\Controllers;
use App\Models\Events;
use Illuminate\Http\Request;

class EventsController extends Controller
{

        public function get_every_events($unitid,$eventName){
           $events = Events::where([['unitid','=',$unitid],['events_name','=',$eventName]])->first();

            return response()->json([
                'status' => $events
                ]);
        }
    public function update_event(Request $request){
              Events::where('id',$request->id)
                    ->update(
                        $request->validate([
                            'start' => ['required'],
                            'end' => ['required'],
                            'events_name' => ['required'],
                            'events_address' => ['required'],
                        ])
                    );
                return response()->json([
                'status' => 'success'
                ]);
        }

    public function delete_event($id){
         Events::where('id',$id)->delete();
        return response()->json([
        'status' => 'success'
        ]);
    }
    public function get_events_in_unit($id){
        $events= Events::where('unitid',$id)->get();
        return response()->json([
        'status' => $events
        ]);
    }
     public function create_event(Request $request){
              Events::create(
                $request->validate([
                    'unitid' => ['required'],
                    'events_name' => ['required'],
                    'events_address' => ['required'],
                    'start' => ['required'],
                    'end' => ['required'],
                    'year' => ['required']
                ])
              );

              return response()->json([
            'status' => 'success'
            ]);
     }
}
