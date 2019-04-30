<?php

namespace App\Http\Controllers\jssdk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
class jssdkController extends Controller
{
    //签名jssdk
    public function jssdk(){
        $ticket=getsign();   //jsdk签名
        $timestamp=time();  //当前时间
        $nonceStr=Str::random(10);//随机字符串
        $current_url=$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        //计算拼接签名
        $string1 = "jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$current_url";
        $sign = sha1($string1);
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
   //获取图片信息
    public function getimg(){
//        $media_id=$_GET['media_id'];

        $content=file_get_contents('php://input');
        var_dump($content);
//        var_dump($media_id);die;
//        $url="";


//        echo "<pre>";print_r($_GET);echo '<pre>';
    }

    //

}
