<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CityController extends Controller
{

    public function get_current_happenings_by_quad(Request $request){
        $left = $request->left; $right = $request->right; $top = $request->top; $bottom = $request->bottom;
        $query = "SELECT * FROM voity_dev_companies.`place`, voity_dev_companies.`happenings` WHERE place.longitude < ".$right." AND place.longitude > ".$left." AND place.latitude < ".$top." AND place.latitude > ".$bottom." AND place.place_id=happenings.h_place_id";
        return DB::connection('companies')->select($query);
    }

    
    
}