<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

define("PDO_OK", '{"result": 0, "reason": "PDO_OK"}');
define("PDO_ERROR", '{"result": -1, "reason": "PDO ERROR"}');
define("AUTH_ERROR", '{"result": -2, "reason": "AUTH ERROR"}');
define("INSUFFICIENT_CREDITS", '{"result": -1, "reason": "INSUFFICIENT_CREDITS"}');
define("PLACE_NOT_EXISTS", '{"result": -1, "reason": "PLACE_NOT_EXISTS"}');
define("EJABBERD_PATH", "/opt/ejabberd-19.09.1/bin/");
define('DOMAIN', 'betwalker.ru');

class PlaceController extends Controller
{

    public function main(Request $request){

    }

    public function get_places_by_quad(Request $request){ 
        $center = floatval($request->center);
        $left = floatval($request->left);
        $top = floatval($request->top);
        if ( empty($request->count) ){
            return \App\place::getByCenterLeftAndTop($center, $left, $top);          //($center, $left, $top, $count = 10000)
        }else{
            $count = $request->count;
            return \App\place::getByCenterLeftAndTop($center, $left, $top, $count);  //($center, $left, $top, $count = 10000)
        }
        
    }

    public function create_new_place(Request $request){
        $dbname = "voity_dev_city_".$request->city."_company_".$request->company."_place_".$request->place;
        $pdo = DB::connection('mysql')->getPdo();
        $res = $pdo->query('CREATE DATABASE `'.$dbname.'`');
        if ( $res ){
            $passwd = 'OPENCTLadmin138'; //Str::random(50);
            DB::connection('connections')->table('voity_dev_connections.connections')->insert([
                'host' => '127.0.0.1',
                'port' => 3306,
                'database' => $dbname,
                'username' => 'voity',
                'password' => $passwd,
                'city_id' => $request->city,
                'place_id' => $request->place,
                'company_id' => $request->company
            ]);

            config([
                'database.connections.'.$dbname => [
                    'driver'    => 'mysql',
                    'host'      => '127.0.0.1',
                    'database'  => $dbname,
                    'username'  => 'voity',
                    'password'  => $passwd,
                    'charset'   => 'utf8',
                    'collation' => 'utf8_general_ci',
                    'prefix'    => ''
                ],
                'database.default' => $dbname
                ]);

            //Создание структуры таблиц для новой базы данных
            $this->create_tables($dbname);
            //Создание новой комнаты в чате для нового места
            $this->create_new_chatroom($dbname);

            return PDO_OK;
        }else{
            return PDO_ERROR; 
        }
    }

    public function getPlaceShortData(Request $request){
        $place = new \App\place([
            'id' => $request->place
        ]);
        return $place->getShortData();
    }

    public function user_in_place(Request $request){
        if ( Auth::check() ){
            try{
                $place = new \App\place([
                    'id' => $request->place
                ]);
            }catch(\Exception $e){
                return PLACE_NOT_EXISTS;
            }
            $user = Auth::user();
            return $place->user_in($user, $request->qr);
        }else{
            return AUTH_ERROR;
        }
    }

    public function user_in_check(Request $request){
        if ( Auth::check() ){
            $place = new \App\place([
                'id' => $request->place
            ]);
            return $place->check_confirmation(Auth::user());
        }else{
            return AUTH_ERROR;
        }
    }

    public function place_load_menu(Request $request){
        if ( Auth::check() && Auth::user()->current_place == intval($request->place)){
            $place = new \App\place([
                'id' => $request->place
            ]);
            return $place->check_confirmation(Auth::user());
        }else{
            return AUTH_ERROR;
        }
    }

    public function place_get_menu_by_inner_level(Request $request){
        if ( Auth::check() && Auth::user()->current_place == intval($request->place)){
            $place = new \App\place([
                'id' => $request->place
            ]);
            if ( $place->check_confirmation_boolean(Auth::user()) ){
                return $place->current_menu_by_inner_level($request->level, $request->section);
            }else{
                return AUTH_ERROR;
            }
        }else{
            return AUTH_ERROR;
        }
    }

    public function history(Request $request){
        if ( Auth::check() ){
            return Auth::user();
        }else{
            return "NOT AUTH";
        }
    }

    public function create_new_order(Request $request){
        if ( Auth::check() && Auth::user()->current_place == intval($request->place)){
            $place = new \App\place([
                'id' => $request->place
            ]);
            if ( $place->check_confirmation_boolean(Auth::user()) ){
                return $place->create_new_order($request);
            }else{
                return AUTH_ERROR;
            }
            
        }else{
            return AUTH_ERROR;
        }
    }

    public function get_current_order_l(Request $request){
        if ( Auth::check() && Auth::user()->current_place == intval($request->place)){
            $place = new \App\place([
                'id' => $request->place
            ]);
            if ( $place->check_confirmation_boolean(Auth::user()) ){
                return $place->get_order();
            }else{
                return AUTH_ERROR;
            }
        }else{
            return AUTH_ERROR;
        }
    }

    public function get_current_order_hr(Request $request){
        if ( Auth::check() && Auth::user()->current_place == intval($request->place)){
            $place = new \App\place([
                'id' => $request->place
            ]);
            if ( $place->check_confirmation_boolean(Auth::user()) ){
                return $place->get_order_hr();
            }else{
                return AUTH_ERROR;
            }
        }else{
            return AUTH_ERROR;
        }
    }

    public function test(Request $request){
        if ( Auth::check() && Auth::user()->current_place == intval($request->place)){
            $place = new \App\place([
                'id' => $request->place
            ]);
            return $place->current_menu_by_inner_level($request->level, $request->section);
        }else{
            return AUTH_ERROR;
        }
    }

    private function create_new_chatroom($room_name){   
        $command = EJABBERD_PATH.'ejabberdctl create_room '.$room_name.' conference.'.DOMAIN.' '.DOMAIN;
        exec($command);
    }   

    public function send_comment(Request $request){
        if ( Auth::check() ){
            $place_id = Auth::user()->current_place;
            if ( $place_id == 0 ){
                return AUTH_ERROR;
            }
            $place = new \App\place([
                'id' => $place_id
            ]);
            if ( !empty($request->rating) ){
                $place->send_rating($request->rating);
            }
            return $place->send_comment($request->comment);
        }else{
            return AUTH_ERROR;
        }
    }

    public function get_comments(Request $request){
        $place = new \App\place([
            'id' => $request->place
        ]);
        if ( !empty($place->count) ){
            return $place->get_comments($count);
        }
        return $place->get_comments();
    }

    /**
     * Обертка оплаты заказа
     */

    public function payment(Request $request){
        if ( Auth::check() ){
            $place_id = Auth::user()->current_place;
            if ( $place_id == 0 ){
                return AUTH_ERROR;
            }
            $place = new \App\place([
                'id' => $place_id
            ]);
            return $place->internal_payment();
        }else{
            return AUTH_ERROR;
        }
    }

    private function create_tables($dbname){
        Schema::create($dbname.'.place', function ($table) {
            $table->integer('place_vc_id');
            $table->string('address');
            $table->string('common_chat_hash');
            $table->string('mean_rating');
        });

        Schema::create($dbname.'.objects', function ($table) {
            $table->bigIncrements('object_id');
            $table->string('object_name');
            $table->string('price');
            $table->string('short_desk');
            $table->bigInteger('section_id');
            $table->bigInteger('description_id');
        });

        Schema::create($dbname.'.sections', function ($table) {
            $table->bigIncrements('section_id');
            $table->bigInteger('parent_id');
            $table->string('section_name');
            $table->bigInteger('description_id');
        });

        Schema::create($dbname.'.descriptions', function ($table) {
            $table->bigIncrements('description_id');
            $table->longText('section_name');
        });

        Schema::create($dbname.'.rating', function ($table) {
            $table->bigIncrements('rating_id');
            $table->bigInteger('rating_value');
        });

        Schema::create($dbname.'.orders', function ($table) {
            $table->bigIncrements('order_id');
            $table->bigInteger('object_id');
            $table->bigInteger('table_id');
            $table->integer('order_status')->default(0);
        });

        Schema::create($dbname.'.tables', function ($table) {
            $table->bigIncrements('table_id');
            $table->integer('table_status');
            $table->integer('table_number');
        });

        Schema::create($dbname.'.tables_schema', function ($table) {
            $table->bigIncrements('table_schema_id');
            $table->integer('status');
            $table->longText('image');
            $table->json('points_dictionaty');
        });

        Schema::create($dbname.'.place_users', function ($table) {
            $table->bigIncrements('place_user_id');
            $table->integer('user_id');
            $table->string('qrhash');
            $table->integer('status');
        });

        Schema::create($dbname.'.bindings', function ($table) {
            $table->bigIncrements('binding_id');
            $table->integer('user_id')->unique();
            $table->integer('table_id');
            $table->string('qrhash');
            $table->integer('status');
        });

        Schema::create($dbname.'.comments', function ($table) {
            $table->bigIncrements('comment_id');
            $table->integer('user_id');
            $table->integer('moderation')->default(0);
            $table->string('comment', 10000);
        });
        
    }
}
