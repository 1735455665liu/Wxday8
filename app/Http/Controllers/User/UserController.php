<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use App\Model\User\Wx;
class UserController extends Controller
{

    /**
     * 处理首次接入GET请求
     */
    public function valid()
    {
        echo $_GET['echostr'];
    }
    public function atoken()
    {
        echo $this->getAccessToken();
    }
    //接收微信事件推送 POST
    public function wxEvent()
    {
        //接收微信服务器推送
        $content = file_get_contents("php://input");
        $time = date('Y-m-d H:i:s');
        $str = $time . $content . "\n";
        file_put_contents("logs/wx_event.log",$str,FILE_APPEND);
        $data = simplexml_load_string($content);
//        echo 'ToUserName: '. $data->ToUserName;echo '</br>';        // 公众号ID
//        echo 'FromUserName: '. $data->FromUserName;echo '</br>';    // 用户OpenID
//        echo 'CreateTime: '. $data->CreateTime;echo '</br>';        // 时间戳
//        echo 'MsgType: '. $data->MsgType;echo '</br>';              // 消息类型
//        echo 'Event: '. $data->Event;echo '</br>';                  // 事件类型
//        echo 'EventKey: '. $data->EventKey;echo '</br>';
        $wx_id = $data->ToUserName;  // 公众号ID
        $openid = $data->FromUserName;  //用户OpenID
        $event = $data->Event;  //事件类型
        if($event=='subscribe'){
            //根据openid判断用户是否已存在
            //用户关注过
            $local_user = Wx::where(['openid'=>$openid])->first();
            if($local_user){
                echo '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$wx_id.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. '欢迎回来 '. $local_user['nickname'] .']]></Content></xml>';
            }else{
                //首次关注
                //获取用户信息
                $u = $this->getUserInfo($openid);
                //用户信息入库
                $u_info = [
                    'openid'    => $u['openid'],
                    'nickname'  => $u['nickname'],
                    'sex'  => $u['sex'],
                    'province'  => $u['province'],
                    'country'  => $u['country'],
                    'headimgurl'  => $u['headimgurl'],
                ];
                $id = Wx::insertGetId($u_info);
                echo '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$wx_id.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. '欢迎关注 '. $u['nickname'] .']]></Content></xml>';
            }
        }
    }

//    获取微信 AccessToken

    public function getAccessToken()
    {
        //是否有缓存
        $key = 'wx_access_token';
        $token = Redis::get($key);
        if($token){
        }else{
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET');
            $response = file_get_contents($url);
            $arr = json_decode($response,true);
            //缓存 access_token
            Redis::set($key,$arr['access_token']);
            Redis::expire($key,3600); //缓存时间 1小时
            $token = $arr['access_token'];
        }
        return $token;
    }
    public function test()
    {
        $access_token = $this->getAccessToken();
        echo 'token : '. $access_token;echo '</br>';
    }

    // 获取微信用户信息
    public function getUserInfo($openid)
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->getAccessToken().'&openid='.$openid.'&lang=zh_CN';
        $data = file_get_contents($url);
        $u = json_decode($data,true);
        return $u;
    }

    // 创建公众号菜单

    public function createMenu()
    {
        $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $this->getAccessToken();
        // 接口数据
        $post_arr = [ //菜单层级关系
            'button' => [
                [
                    'type' => 'click',
                    'name' => '1809A',
                    'key' => 'key_menu_001'
                ],
                [
                    'type' => 'click',
                    'name' => '我爱北京天安门3',
                    'key' => 'key_menu_002'
                ],
            ]
        ];//处理中文编码
        $json_str = json_encode($post_arr, JSON_UNESCAPED_UNICODE);
        // 发送请求
        $clinet = new Client();
        $response = $clinet->request('POST', $url, [//发送 json字符串
            'body' => $json_str
        ]);
        //处理响应
        $res_str = $response->getBody();
        $arr = json_decode($res_str, true);
        //判断错误信息
        if ($arr['errcode'] > 0) {
            echo "创建菜单失败";
        } else {
            echo "创建菜单成功";
        }
    }
}
