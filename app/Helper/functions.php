<?php
use Illuminate\Support\Facades\Redis;
//自动加载文件

//获取asscc_token
function Getasstoken(){
    $key="asscc_token";
    $keytoken=Redis::get($key);
    if($keytoken){      //判断是否存在缓存 如果有返回给服务器
        return $keytoken;
    }else{              //没有则添加缓存
        $url='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APP_ID').'&secret='.env('WX_APP_SECRET');
        $file=json_decode(file_get_contents($url),true);
        if(isset($file['access_token'])){
            Redis::set($key,$file['access_token']);
            Redis::expire($key,3600);
            return $file['access_token'];
        }else{
            return false;
        }


    }




}
//计算签名
function getsign(){

    $key="ticket";
    //获取access_token
   $keyticket=Redis::get($key);
   if($keyticket){
       return $keyticket;     //如果有就存到缓存中
   }else{
       $accesstoken=Getasstoken();
       $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$accesstoken.'&type=jsapi';
       $reposen=json_decode(file_get_contents($url),true); //转换为数组
        if(isset($reposen['ticket'])){
            Redis::set($key,$reposen['ticket']);    //存入缓存
            Redis::expire($key,3600);   //过期时间为3600秒
            return $reposen['ticket'];
        }else{
            return false;
        }
   }

}

function fial($value){
    $arr=[
        'font'=>$value,
        'code'=>1
    ];
    echo json_encode($arr);


}
//错误提示
function errores($value){

    $arr=[
        'font'=>$value,
        'code'=>2
    ];
    echo json_encode($arr);exit;


}







?>