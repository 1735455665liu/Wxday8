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
Route::get('/info', function () {
    phpinfo();
});

//获取回调地址
Route::get('/wx/authorize', function () {
    echo urlencode($_GET['url']);
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
Route::post('Wxyy','User\UserController@wxyy');//语音素材
Route::post('SendMsg','User\UserController@SendMsg');//群发信息
Route::get('send','User\UserController@send');  //群发请求
Route::get('wx/test','Wx\WxPayController@t_test');  //扫码支付
Route::post('/weixin/pay/notify','Wx\WxPayController@notify');  //请求回调
Route::get('/jssdk','jssdk\jssdkController@jssdk');  //上传图片jssdk
Route::get('/getimg','jssdk\jssdkController@getimg');  //接受数据
Route::post('/wx_text','jssdk\jssdkController@wx_text');  //图文消息
Route::get('/fxjssdk','User\UserController@fxjssdk');  //分享jssdk

Route::get('/wxweb/u','User\UserController@repson');  //微信网页授权回调
Route::get('/getgoods','User\UserController@getgoods');  //扫码跳转


//文件上传
Route::get('/myfile','GoodsController@myfilezZ');  //扫码跳转

Route::post('/msgMenu','User\UserController@msgMenu');  //自定义菜单



Route::get('/wxfl','User\UserController@wxfl');  //今日福利
Route::get('/wxhui','User\UserController@wxhui');  //微信回调


Route::post('/jihua','User\UserController@jihua');  //计划任务
