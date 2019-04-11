<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function valid(){
        echo $_GET['echostr'];
    }
    public function wxEvent()
    {
//        //接收微信服务器推送
        $content = file_get_contents("php://input");
        var_dump($content);
        $time = date('Y-m-d H:i:s');
        $str = $time . $content . "\n";
        file_put_contents("logs/wx_event.log", $str, FILE_APPEND);
    }

}
