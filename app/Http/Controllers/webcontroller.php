<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class webcontroller extends Controller
{
    public function login(){
        if(!Auth::check()){
            return response()->json([
                "message"=>"Unauthorized"
            ],401);
        }
   
    }
}
