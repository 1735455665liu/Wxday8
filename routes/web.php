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

//微信接口接入
Route::get('valid','User\UserController@valid');
//接收微信服务器推送事件
Route::post('valid','User\UserController@wxEvent');


Route::get('/weixin/get_access_token','User\UserController@getAccessToken');
Route::get('/weixin/test','User\UserController@test');