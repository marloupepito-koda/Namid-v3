<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Units;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
         public function add_user_account(Request $request){
              $request->validate([
            'name' => ['required'],
            'email' => ['required'],
            'job' => ['required'],
            'password' => ['required'],
        ]);

            $client=Units::where('units_name','=',$request->unitName)->first();
            if($request->job === 'Administrator' || $request->job === 'Ticket Office Manager'){
                  $users = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'unit_name' => $request->job ==='Administrator'?'':$request->unitName,
                    'usertype' => $request->job,
                    'unit_id' => $request->job ==='Administrator'?null: $client->client_id,
                    'password' => Hash::make($request->password),
                    'access1' => 'On',
                    'access2' => 'On',
                    'access3' => 'On',
                    'access4' => 'On',
                    'access5' => 'On',
                    'access6' => 'On',
                    'access7' => 'On',
                    'access8' => 'On',
                    'access9' => 'On',
                    'access10' => 'On',
                    'access11' => 'On',
                    'access12' => 'On',
                    'access13' => 'On',
                    'access14' => 'On',
                    'access15' => 'On',
                ]);
            }else if($request->job === 'Ticket Office Staff'){
                  $users = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'unit_name' => $request->unitName,
                    'usertype' => $request->job,
                    'unit_id' => $client->client_id,
                    'password' => Hash::make($request->password),
                    'access1' => 'Off',
                    'access2' => 'Off',
                    'access3' => 'On',
                    'access4' => 'On',
                    'access5' => 'Off',
                    'access6' => 'Off',
                    'access7' => 'Off',
                    'access8' => 'On',
                    'access9' => 'Off',
                    'access10' => 'Off',
                    'access11' => 'Off',
                    'access12' => 'Off',
                    'access13' => 'On',
                    'access14' => 'Off',
                    'access15' => 'Off',
                ]);
            }else{

                 $users = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'unit_name' => $request->unitName,
                    'usertype' => $request->job,
                    'unit_id' => $client->client_id,
                    'password' => Hash::make($request->password),
                    'access1' => 'Off',
                    'access2' => 'Off',
                    'access3' => 'Off',
                    'access4' => 'Off',
                    'access5' => 'Off',
                    'access6' => 'Off',
                    'access7' => 'Off',
                    'access8' => 'On',
                    'access9' => 'Off',
                    'access10' => 'Off',
                    'access11' => 'Off',
                    'access12' => 'Off',
                    'access13' => 'On',
                    'access14' => 'Off',
                    'access15' => 'Off',
                ]);

            }
          
            $user = User::all();
            return response()->json([
                'status' =>  $user,
                'status2' => $users
                ]);
         }
         public function delete_user($id){
            $user = User::find($id);
              $user->delete();
                return response()->json([
                'status' =>$user,
                ]);
         }
        public function edit_every_user(Request $request){
         
        $user = Units::where('units_name','=',$request->unitName)->first();
        
        if($request->password === '' || $request->password === null || $request->password === ' '){
            User::where('id','=',$request->id)
            ->update([
             'unitid' => $request->job === 'Administrator'?1:$user->client_id,
             'name' => $request->name,
             'email' => $request->email,
             'usertype' => $request->job,
             'unitname' => $request->job === 'Administrator'?'':$request->unitName,
            ]);
        }else{
            User::where('id','=',$request->id)
            ->update([
             'unitid' => $request->job === 'Administrator'?1:$user->client_id,
             'name' => $request->name,
             'email' => $request->email,
             'usertype' => $request->job,
             'unitname' => $request->job === 'Administrator'?'':$request->unitName,
             'password' => Hash::make($request->password),
            ]);
        }
        }
        public function get_every_users($id){
             $user = User::where('id','=',$id)->first();
             $unit = Units::where('id','=',$user->unitid)->first();
              return response()->json([
                'status' =>$user,
                'unit' => $unit
                ]);
        }
     public function edit_access_user(Request $request){
    
        User::where('id', $request->id)
          ->update($request->validate([
            'access1' => ['required'],
            'access2' => ['required'],
            'access3' => ['required'],
            'access4' => ['required'],
            'access5' => ['required'],
            'access6' => ['required'],
            'access7' => ['required'],
            'access8' => ['required'],
            'access9' => ['required'],
            'access10' => ['required'],
            'access11' => ['required'],
            'access12' => ['required'],
            'access13' => ['required'],
            'access14' => ['required'],
            'access15' => ['required'],
        ]));

          $user = User::where('id','=',$request->id)->first();
         return response()->json([
                'status' =>  $user,
                ]);

     }
      public function get_all_users(){
        $users = User::all();
         return response()->json([
        'status' => $users
        ]);
      }
    public function ee_login_users(Request $request){
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);
        if(Auth::attempt($request->only('email','password'))){
            return response()->json(Auth::user(), 200);
        }
        throw ValidationException::withMessages([
            'email'=>['the provided cridentials is incorrect!']
        ]);
    }
    public function ee_user_logout(){
        
        Auth::logout();
        

    }

}
