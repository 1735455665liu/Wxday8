<?php

namespace App\Http\Controllers\jssdk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
class jssdkController extends Controller
{
    //


    public function jssdk(){

        $ticket=getsign();   //jsdk签名
        $timestamp=time();  //当前时间
        $nonceStr=Str::random(10);//随机字符串
        $url=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];



        //计算拼接签名
        $strsha="jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$timestamp&signature=$url";
        var_dump($strsha);
        //加密签名
        $sign=sha1($strsha);
        $js_jssdk=[
            "appId"=>env('WX_APP_ID'),//公总号id
            "timestamp"=>$timestamp,//生成签名时间戳
            "nonceStr"=>$nonceStr,//生成签名的随机字符串
            "signature"=>$sign,//签名
        ];
        $data=[
            'js_jssdk'=>$js_jssdk
        ];
        return view('wx.jssdk',$data);
    }
}
