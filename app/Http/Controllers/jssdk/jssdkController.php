<?php

namespace App\Http\Controllers\jssdk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class jssdkController extends Controller
{
    //


    public function jssdk(){

        $asscctoken=Getasstoken();
        var_dump($asscctoken);
        die;

    $js_jssdk=[
        "appId"=>env('WX_APP_ID'),//公总号id
        "timestamp"=>'',//生成签名时间戳
        "nonceStr"=>'',//生成签名的随机字符串
        "signature"=>'',//签名
    ];


        $data=[
            'js_jssdk'=>$js_jssdk
        ];
        return view('wx.jssdk',$data);
    }
}
