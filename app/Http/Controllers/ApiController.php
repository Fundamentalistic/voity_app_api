<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function confirm_phone(Request $request){
        if ( Auth::check() ){
            //$confirm_codes = DB::connection('mysql')->table('confirm_codes')->
        }
    }
}
