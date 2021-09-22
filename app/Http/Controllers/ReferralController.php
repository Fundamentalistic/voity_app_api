<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    public function intermediate_registration(Request $request){
        $ip = $_SERVER['REMOTE_ADDR'];
        $referhash = $request->hash;
        $linkid = $request->linkid;
        DB::table('intermetiate_registration')->insert([
            'ip' => $ip,
            'hash' => $referhash
        ]);
        $link = json_decode(file_get_contents('referral.settings.json'))->{'actual_appstore_link'};
        return redirect($link);
    }

    public function generate(Request $request){
        $user = Auth::user();
        $referral_record = DB::table('user_referral_hashes')->select('*')->where('urh_user_id', $user->user_id)->get();
        if ( sizeof($referral_record) == 0 ){
            $hash = Str::random(80);
            DB::table('user_referral_hashes')->insert([
                'urh_user_id' => $user->user_id,
                'hash' => $hash
            ]);
        }else{
            $hash = $referral_record[0]->{'hash'};
        }
        $response = '{"hash": "'.$hash.'"}';
        return $response;
    }
}
