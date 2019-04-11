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

Route::get('/', function () {
    return view('welcome');
});




Route::get('/test/redis/aaa','Test\TestController@aaa');

Route::get('/test/atoken','User\UserController@atoken');
//微信首次接入
Route::get('/weixin/valid','User\UserController@valid');
//接收微信服务器推送事件
Route::post('/weixin/valid','User\UserController@wxEvent');
Route::get('/weixin/create_menu','User\UserController@createMenu');     //创建公众号菜单
Route::get('/weixin/get_access_token','User\UserController@getAccessToken');
Route::get('/weixin/test','User\UserController@test');