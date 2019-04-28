<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->get('/file','WxController@file');  //素材添加
    $router->post('wximg'   ,'WxController@index'); //素材
//    $router->resource('/wximg', WxController::class);
    $router->get('/send','WeiController@send');//消息管理
    $router->post('/sendadd','WeiController@sendadd');//消息管理
    $router->resource('/users', UserController::class);//用户管理
    $router->resource('/maclist',macController::class);//素材管理

});
