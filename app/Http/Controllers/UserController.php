<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
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
