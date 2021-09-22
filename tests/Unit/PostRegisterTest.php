<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostRegisterTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * 
+----------------+--------------+------+-----+---------+----------------+
| Field          | Type         | Null | Key | Default | Extra          |
+----------------+--------------+------+-----+---------+----------------+
| user_id        | int(11)      | NO   | PRI | NULL    | auto_increment |
| login          | varchar(45)  | NO   | UNI | NULL    |                |
| phone          | varchar(15)  | NO   | UNI | NULL    |                |
| password       | varchar(255) | NO   |     | NULL    |                |
| register_time  | time         | NO   |     | NULL    |                |
| last_act_time  | time         | NO   |     | NULL    |                |
| phone_verified | tinyint(4)   | NO   |     | NULL    |                |
| status         | int(11)      | NO   |     | NULL    |                |
| data_id        | int(11)      | NO   |     | NULL    |                |
+----------------+--------------+------+-----+---------+----------------+
     * 
     * 
     * @return void
     */
    public function testRegistration()
    {
        $data = [
            "login" => "username",
            "password" => "testpasswd123",
            "phone" => "+79123451234"
        ];
        var_dump($data);
        User::create([
            'login' => $data['login'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
            'api_token' => Str::random(60),
            'register_time' => time(),
            'last_act_time' => time()
        ]);
        $this->assertTrue(true);
    }
}
