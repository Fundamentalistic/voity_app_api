<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Exception;

define('ROOT_DB', "voity_dev_company");
define('IN_PLACE_NOT_CONFIRMED_USER', 0);
define('IN_PLACE_CONFIRMED_USER', 1);
define('OK', '{"result": 0, "status": "OK"}');
define('VALUE_ERROR', '{"result": -1, "status": "VALUE_ERROR"}');
define('USER_CONFIRM', '{"result": 0, "staus": "CONFIRMED"}');
define('USER_NOT_CONFIRM', '{"result": -1, "staus": "NOT CONFIRMED"}');
define('USER_ALREADY_EXIST', '{"result": -1, "status": "USER_ALREADY_EXIST"}');
define('STATUS_OFICIANT', 1);
define('DENIED', '{"result": -1, "status": "ACCESS DENIED"}');
define('ORDER_STATUS_ACTIVE', 0);
define('ORDER_STATUS_COMPLETE', 1);

define('EJABBED_PATH', '/var/www/');

class place extends Model
{
    protected $fillable = ['id'];
    protected $connection = 'voity_dev_companies';

    /**
     * Переопределение конструктора модели места
     */

    public function __construct(array $attributes = array()){
        parent::__construct($attributes);
        $this->id = $attributes['id'];
        $params = DB::connection('companies')->table('voity_dev_companies.place')->select('*')->where('place_id', $this->id)->get();
        if ( sizeof($params) == 0 ){
            throw new Exception('PlaceNotFoundException: requested place not exists');
        }
        $params = $params[0];
        $this->c_db_name = "voity_dev_city_".$params->{'place_city_id'}."_company_".$params->{'place_company_id'}."_place_".$params->{'place_id'};
        $this->db_connection_params = DB::connection('connections')->table('voity_dev_connections.connections')->select('*')->where('database', $this->c_db_name)->get()[0];
    }

    /**
     * Получение последней версии меню заведения
     */

     public function get_menu(){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        return DB::table('');
     }

    /**
     * Получение коротких данных о месте
     */

    public function getShortData(){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        return DB::table('place')->select('*')->get();
    }

    /**
     * Вхождение пользователя в место
     * Внесение записи в таблицу bindings
     */

    public function user_in($user, $hash){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        if ( DB::table('bindings')->select('*')->where('user_id', $user->user_id)->exists() ){
            return USER_ALREADY_EXIST;
        }
        DB::table('bindings')->insert([
            'user_id' => $user->user_id,
            'qrhash' => $hash
        ]);
        $user->current_place = $this->id;
        $user->save();
        return OK;
    }

    /**
     * Выход пользователя с места
     * Отвязка всех идентификаторов
     */

    public function user_out($user){
        DB::table('bindings')->where('user_id', $user->user_id)->delete();
        $user->current_place = 0;
        $user->save();
        return OK;
    }

    /**
     * Проверка на то, что вход пользователя подтвержден администратором
     * Получение записи о пользователе и его статусе из таблицы bindings
     */

    public function check_confirmation($user){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        $res = DB::table('bindings')->select('status')->where('user_id', $user->user_id)->get();
        if ( sizeof($res) == 0 ){
            return USER_NOT_CONFIRM;
        }else if($res[0]->{'status'} == IN_PLACE_NOT_CONFIRMED_USER){
            return USER_NOT_CONFIRM;
        }else if($res[0]->{'status'} == IN_PLACE_CONFIRMED_USER){
            return USER_CONFIRM;
        }
    }

    /**
     * Проверка на подтверждение с булевым результатом
     */

    public function check_confirmation_boolean($user){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        $res = DB::table('bindings')->select('status')->where('user_id', $user->user_id)->get();
        if ( sizeof($res) == 0 ){
            return FALSE;
        }else if($res[0]->{'status'} == IN_PLACE_NOT_CONFIRMED_USER){
            return FALSE;
        }else if($res[0]->{'status'} == IN_PLACE_CONFIRMED_USER){
            return TRUE;
        }
    }

    /**
     * Проверка привилегий пользователя
     * В случае если пользователь относится к подтвержденным сотрудникам места установа статуса подтвержден по qr коду
     * В противном случае выдача сообщения о запрете доступа
     */

    public function set_confirmation($user, $qrhash, $service_id){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        if ( DB::table('place_users')->select('status')->where('user_id', $user->user_id)->exists() ){
            $administrator = DB::table('place_users')->select('status')->where('user_id', $user->user_id)->get()[0];
            if ( $administrator->{'status'} != IN_PLACE_NOT_CONFIRMED_USER ){
                DB::table('bindings')->where('qrhash', $qrhash)->update([
                    'status' => IN_PLACE_CONFIRMED_USER
                ]);
                return OK;
            }else{
                return DENIED;
            }
        }else{
            return DENIED;
        }
    }

    /**
     * $command = ./ejabberdctl set_room_affiliation bestroom betwalker.ru admin@betwalker.ru member
     * Добавление пользователя в ростер комнаты чата места по qr коду
     */

    private function add_to_chat_room($qrhash){
        $binding_record = DB::table('bindings')->select()->where([ 'qrhash' => $qrhash ])->get()[0];
        $user = User::where('user_id', $binding_record->{'user_id'})->first();
        $jid = $user->{'phone'}.'@'.DOMAIN;
        $command = EJABBED_PATH.'ejabberdctl set_room_affiliation '.$this->db_connection_params->{'database'}.' conference'.DOMAIN.' '.$jid.' member';
        exec($command);
    }

    /**
     * ejabberdctl set_room_affiliation room conference.localhost user123@localhost outcast
     * Удаление пользователя из комнаты путем запрета на общение
     */

     private function remove_user_from_chat_room($qrhash){
        $binding_record = DB::table('bindings')->select()->where([ 'qrhash' => $qrhash ])->get()[0];
        $user = User::where('user_id', $binding_record->{'user_id'})->first();
        $jid = $user->{'phone'}.'@'.DOMAIN;
        $command = EJABBED_PATH.'ejabberdctl set_room_affiliation '.$this->db_connection_params->{'database'}.' conference'.DOMAIN.' '.$jid.' outcast';
        exec($command);
     }

     /**
      * Получение меню текущего места
      */

    public function current_menu_by_inner_level($level, $section){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        if(DB::table('bindings')->select()->where('user_id', Auth::user()->user_id)->get()[0]->{'status'} != 1){
            return DENIED;
        }
        $section_header = DB::table('sections')->select()->where('section_id', $section)->get();
        $section_array_content = DB::table('sections')->select('section_id', 'section_name', 'description_id')->where('parent_id', $section)->get();
        $section_object_content = DB::table('objects')->select()->where('section_id', $section)->get();
        $section_header['content'] = $section_array_content->merge($section_object_content);
        return $section_header;
    }

    /**
     * Выбор из всех мест списка длины не больше $count попадающих в заданный квадрат
     */

    public static function getByCenterLeftAndTop($center, $left, $top, $count = 10000){
        $left = $center - $left; $right = $center + $left; $top = $center + $top; $bottom = $center - $top;
        return DB::connection('companies')->table('voity_dev_companies.place')->select('*')->whereRaw("'longitude' > ".$left." AND 'longitude' < ".$right." AND 'latitude' < ".$top." AND 'latitude' > ".$bottom)->limit($count)->get();
    }

     /*
        Выбор из всех мест списка мест длины $count где айди компании = $cid 
     */

    public static function getByCompanyID($cid, $count = 10000){
        return DB::connection('companies')->select("SELECT * FROM 'place' WHERE 'place_company_id'=".$cid." LIMIT ".$count);
    }
    
    /**
     * Создание нового заказа по идентификаторам объекта
        +--------------+---------------------+------+-----+---------+----------------+
        | Field        | Type                | Null | Key | Default | Extra          |
        +--------------+---------------------+------+-----+---------+----------------+
        | order_id     | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
        | object_id    | bigint(20)          | NO   |     | NULL    |                |
        | table_id     | bigint(20)          | NO   |     | NULL    |                |
        | order_status | int(11)             | NO   |     | NULL    |                |
        +--------------+---------------------+------+-----+---------+----------------+
     */

    public function create_new_order($ids){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        $table_id = $ids->{'table'};
        $insertion_array = [];
        foreach( $ids->{'objects'} as $obj ){
            array_push($insertion_array, [
                'table_id' => $table_id,
                'object_id' => $obj
            ]);
        }
        DB::table('orders')->insert($insertion_array);
        return OK;
    }

    /**
     * Получение содержания заказа в легкой для сервера форме
     */

    public function get_order(){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        return DB::table('orders')->select('*')->where('user_id', Auth::user()->user_id)->where('order_status', ORDER_STATUS_ACTIVE)->get();
    }

    /**
     * Получение содержания заказа в человекочитаемой форме
     */

    public function get_order_hr(){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        //SELECT * FROM orders, objects WHERE orders.user_id=1 AND orders.object_id=objects.object_id
        return DB::select('SELECT * FROM orders, objects WHERE orders.user_id=1 AND orders.order_status='.ORDER_STATUS_ACTIVE.' AND orders.object_id=objects.object_id');
    }

    /**
     * Оплата заказа
     */

    public function internal_payment(){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        $user = Auth::user();
        $records = DB::select('SELECT * FROM orders, objects WHERE orders.user_id='.$user->user_id.' AND orders.order_status='.ORDER_STATUS_ACTIVE.' AND orders.object_id=objects.object_id');
        $sum = 0;
        foreach ( $records as $record ){
            $sum += $record->{'price'};
        }
        if ( $sum > $user->internal_credits ){
            return INSUFFICIENT_CREDITS;
        }
        $user->internal_credits -= $sum;
        $user->save();
        DB::table('orders')->where('user_id', $user->user_id)->update([ 'order_status' => ORDER_STATUS_COMPLETE ]);
        return '{"result": 0, "balance": '.$user->internal_credits.'}';
    }

    /**
     * Получение информации о месте по идентификатору
     */

    public static function get_by_id($id){
        return DB::connection('companies')->table('voity_dev_companies.place')->select('*')->where('place_id', $id)->get();
    }

    /**
     * Отправка рейтинга
     */

    public function send_rating($intval){
        if ( $intval < 1 && $intval > 5){
            return VALUE_ERROR;
        }
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        DB::table('rating')->insert([
            'rating_value' => $intval
        ]);
        return OK;
    }

    /**
     * Отправка коментария
     */

    public function send_comment($comment){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        DB::table('comments')->insert([
            'user_id' => Auth::user()->user_id,
            'comment' => $comment
        ]);
        return OK;
    }

    /**
     * Получение отзывов
     */

    public function get_comments($count = 1000){
        $this->set_new_database_connection($this->db_connection_params->{'database'}, $this->db_connection_params->{'password'});
        return DB::table('comments')->select()->orderBy('comment_id', 'desc')->limit($count)->get();
    }

    /**
     * Выбор базы данных соответствующей месту предоставления услуг для текущей сессии
     */

    private function set_new_database_connection($dbname, $passwd){
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
    }
}
