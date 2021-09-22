<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function(){
    return view('admin');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::middleware('auth:api')->post('"/voity/user/confirm/phone"', 'ApiController@confirm_phone');
Route::get('/referral/registration', 'ReferralController@intermediate_registration');
Route::middleware('auth:api')->get('/referral/generate', 'ReferralController@generate');

/*GET PLACE HISTORY*/
Route::middleware('auth:api')->post('/place/history', 'PlaceController@history');

//USERS_MODULE_API//////////////////////////////////////////////////////////////////////////
/*
COMPLETE:
    1) Сделать миграции MySQL для двух баз данных               +
    2) Сделать контроллер регистрации пользователей             +
    3) Авторизация пользователя по API KEY                      +

Ключевые точки
    1) Пользователи                                             +
    2) Компании и общее представление мест, внешних и своих     +
    2.1) Формирование шаблонов главного меню для типов бизнеса  
         и их частичная кастомизация
    2.2) Управление акциями и афишей 
    2.2.1) Получение акция в заданном квадрате                  +
    2.2.2) Создание акций
    3) Свои места
    3.1) Чат внутри своих мест                                  +
    3.1.1) Создание новой комнаты при создании нового места     +
    3.1.2) Добавление пользователя в комнату при входе          +
    3.1.3) Система разрешений на чат функционал                 (на стороне клиента как настройки оболочки на реакцию)
    3.1.4) Удаление пользователя при выходе                     +
    3.1.5) Формирование личного чата при желании                (Средствами внешней библиотеки на стороне клиента. Формирование запроса на подписку)
    3.2) Управление заказами                                           
    3.2.1) Получение содержания заказа и цены                   +
    3.2.2) Получение счета                                      +
    3.2.3) Оплата внутренней валютой                            +
    3.2.4) Начисление внутренней валюты на счет места
    3.2.4) Создание заказов                                     +
    3.3) Отзывы и рейтинг
    3.3.1) Отправка отзыва                                      +
    3.3.2) Модерация отзыва                                     +
    3.3.3) Удаление отзыва                                      (Средствами администрирования mysql)
    3.3.4) Получение отзывов                                    +
    3.4) Управление меню
    3.4.1) Загрузка меню посредством json массива               ~
    3.4.2) Загрузка меню посредством csv таблицы
    3.4.3) Выдача меню кусочно                                  +
    3.4.3.1) По секциям                                         +
    3.4.5) Получение статуса обновления меню
    4) Реферальная программа                                    +
    5) Работа с картой и координатами                           +
    6) Бальная система
    6.1) Выставление счетов и оплата баллами                    ~

1) Меню в любом состоянии пользователя
2) Возможность заказа только в случае подтверждения
3) Проработать вопрос с оплатой картой
4) Частичная оплата бонусами
5) Акции в контексте соотношения оплаты баллы\реальные деньги


TO DO:
    1) Макет подтверждения номера телефона
    2) Модуль рефералов
    3) Модуль моих мест и истории мест (зависит от модуля компаний и мест)
    4) Сделать формирование новых соединений для базы данных для городов
    5) Сделать функции наполнения городов
    6) Сделать правильное формирование sql в модели мест

    1) Получение списка мест по квадрату координат                                                      PlaceController@get_places_by_quad(Request)     18
    2) Получение коротких данных об объекте                                                             PlaceController@getPlaceShortData               70                                   
    3) Вход в объект                                                                                    PlaceController@user_in_place                   78
    4) При входе в объект:
    4.1) Формирование, загрузка и получение меню
    4.2) Получение схемы объектов услуг с сопроводительной информацией ( Стол = объект услуги )
    4.2.1) Список и метки свободных и занятых объектов услуг
    4.2.2) Список пользователей за нужным столом если они разрешили
    4.3) Поелучение списка всех пользователей в объекте
    4.4) Формирование QR кода привязки
    4.5) Доступ к общему чату места после входа. Формирование личного чата по согласию                  place@add_to_chat_room                          131
    4.5.1) Исключение из чата при выходе пользователя из места
    4.6) Подтверждение присутствия данного пользователя за столом администратором   
    4.7) Создание новой комнаты чата при создании нового места                                          PlaceCOntroller@create_new_chatroom             119
    5) API админ апанели управляющих бизнесом
    5.1) Создание новых мест                                                                            PlaceController@create_new_place                31
    5.2) Загрузка шаблонов меню и создание нового меню
    5.3) Добавление описаний к объекту услуг
    5.4) Формирование акций на месте
    6) Формирование типов главного меню, их шаблонов, типов кнопок. Хранение и использование
    7) Обновление API ключа раз в сутки

ControllerList:
    1) PhoneConfirmController
    2) 
Обязательные параметры
1) Имя пользователя базы данных voity
2) Настройка констант RegisterController
3) PlaceController настройка констант чата
*/
/////////////////////////////////////////////////////////////////////////////////////////////

Route::post('/place/new', 'PlaceController@create_new_place');                                          //Создание нового места
Route::post('/place/get/by/quadrat', 'PlaceController@get_places_by_quad');                             //Получение списка мест в заданном квадрате
Route::post('/place/short/data', 'PlaceController@getPlaceShortData');
Route::middleware('auth:api')->post('/place/in', 'PlaceController@user_in_place');                      //Вход в место
Route::middleware('auth:api')->get('/place/in/check', 'PlaceController@user_in_check');                 //Проверка подтверждения привязки администатором места     
Route::middleware('auth:api')->post('/place/in/confirm', 'PlaceController@place_in_confirm');           //Подтверждение нахождения пользователя за объектом услуги

//Маршруты меню
Route::middleware('auth:api')->post('/place/load/menu', 'PlaceController@place_load_menu');             //Загрузка нового меню в базу
Route::middleware('auth:api')->post('/place/get/menu', 'PlaceController@place_get_menu');               //Получение текущего меню из конкретного места

//Тестовый маршрут
Route::middleware('auth:api')->get('/place/get/menu', 'PlaceController@test');

//Создание нового заказа
Route::middleware('auth:api')->post('/orders/create', 'PlaceController@create_new_order');              //Создание заказа
Route::middleware('auth:api')->get('/orders/get/l', 'PlaceController@get_current_order_l');             //Получение данных текущего заказа в упрощенной для сервера
Route::middleware('auth:api')->get('/orders/get/hr', 'PlaceController@get_current_order_hr');           //Получение данных текущего заказа в человекочитаемой форме

//Комментарии
Route::middleware('auth:api')->post('/place/comments/send', 'PlaceController@send_comment');
Route::get('/place/comments/get', 'PlaceController@get_comments');    

//Акции
Route::get('/city/actions/quad', 'CityController@get_current_happenings_by_quad');

//Оплата
Route::middleware('auth:api')->post('/place/payment', 'PlaceController@payment');

/*Сделать метод получения главного меню места, цветовой схемы и логотипа с картинками*/

Route::middleware('auth:api')->get('/place/main', 'PlaceController@main');
/* */
?>