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
//微信首次接入
Route::get('valid','User\UserController@valid');
//接收微信服务器推送事件
Route::post('valid','User\UserController@wxEvent');
//存储信息
Route::get('getAccessToken','User\UserController@getAccessToken');
//测试
Route::get('test','User\UserController@test');

//公众号菜单
Route::post('createMenu','User\UserController@createMenu');


Route::post('WxImage','User\UserController@WxImage');//图片素材



