<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Units;
class UnitsController extends Controller
{

        public function edit_unit(Request $request){
              Units::where('id',$request->id)
                    ->update(
                        $request->validate([
                            'units_name' => ['required'],
                            'units_fullname' => ['required'],
                            'units_shortname' => ['required'],
                            'units_code' => ['required'],
                        ])
                    );
                return response()->json([
                'status' => 'success'
                ]);
        }


        public function delete_unit($id){
            Units::where('id',$id)->delete();
            return response()->json([
                'status' => 'success'
                ]);
        }
      public function get_all_units(){
        $units =Units::all();
        return response()->json([
        'status' => $units
        ]);
      }
      
       public function create_unit(Request $request){

             $units =Units::where('units_name', $request->units_name)->get();
            if(count($units) === 0){
                Units::create(
                $request->validate([
                    'units_name' => ['required'],
                    'units_fullname' => ['required'],
                    'units_shortname' => ['required'],
                    'units_code' => ['required'],
                    'year' => ['required']
                ])
            );
                return response()->json([
                'status' =>'success'
                ]);
            }else{
                return response()->json([
                'status' =>'exist'
                ]);
            } 
       }
}
