<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use App\Model\User\Wx;
use GuzzleHttp\Client;
class UserController extends Controller
{

    public function valid(){
        echo $_GET['echostr'];
    }
    // 处理首次接入GET请求
    //接收微信事件推送 POST
    public function wxEvent()
    {
        //接收微信服务器推送
        $content = file_get_contents("php://input");
        $time = date('Y-m-d H:i:s');
        $str = $time . $content . "\n";
        file_put_contents("logs/wx_event.log",$str,FILE_APPEND);
        $data = simplexml_load_string($content);

        echo 'ToUserName: '. $data->ToUserName;echo '</br>';        // 公众号ID
        echo 'FromUserName: '. $data->FromUserName;echo '</br>';    // 用户OpenID
        echo 'CreateTime: '. $data->CreateTime;echo '</br>';        // 时间戳
        echo 'MsgType: '. $data->MsgType;echo '</br>';              // 消息类型
        echo 'Event: '. $data->Event;echo '</br>';                  // 事件类型
        echo 'EventKey: '. $data->EventKey;echo '</br>';

        $wx_id = $data->ToUserName;             // 公众号ID
        $openid = $data->FromUserName;          //用户OpenID
        $event = $data->Event;          //事件类型

//        var_dump($u);
        if($event=='subscribe'){
            //扫码关注事件  通过查询数据库  做判断
            $Info=Wx::where(['openid'=>$openid])->first();
            if($Info){
                //数据库有值 就说明关注过
                echo '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$wx_id.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. '欢迎回来 '. $Info['nickname'] .']]></Content></xml>';
            }else{
                //没有值 添加入库
                $u=$this->getUserInfo($openid);
                $add=[
                    'openid'    => $u['openid'],
                    'nickname'  => $u['nickname'],
                    'sex'  => $u['sex'],
                    'city'  => $u['city'],
                    'headimgurl'  => $u['headimgurl'],
                    'province'  => $u['province'],
                    'country'  => $u['country'],
                ];
                $userInfo=Wx::insertGetId($add);
                echo '<xml><ToUserName><![CDATA['.$openid.']]></ToUserName><FromUserName><![CDATA['.$wx_id.']]></FromUserName><CreateTime>'.time().'</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA['. '欢迎关注 '. $u['nickname'] .']]></Content></xml>';
            }
        }








    }
    //根据access_koken获取用户信息 存到Redis中
    public function getAccessToken(){
        //是否有缓存
        $key="wx_accsstoken";
        $token=Redis::get($key);
        if($token){

        }else{
            //通过Taccess_token获取信息
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APPID').'&secret='.env('WX_APPSECRET');
            $response=file_get_contents($url);
            $arr=json_decode($response,true);
            //设置
            Redis::set($key,$arr['access_token']);
            Redis::expire($key,7200);  //缓存2小时
            $token=$arr['access_token'];
        }
        return $token;

    }
    //获取用户信息
    public function getUserInfo($openid){
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->getAccessToken().'&openid='.$openid.'&lang=zh_CN';
        $date=file_get_contents($url);
        $u=json_decode($date,true);
//        var_dump($arr);
        return $u;
    }
    public function test(){
        $access_token=$this->getAccessToken();
        echo'token :'.$access_token;echo'<br>';
    }





    //创建公众号菜单
    public function createMenu(){
        //1、调用公众号菜单的接口
        $url=' https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$this->getAccessToken();
        //接口数据
        $p_arr=[

            "button"=>[
                [
                    "type"=>"click",
                    "name"=>"烨氏了解一下",
                    "key"=>"V1001_TODAY_MUSIC"
                ],
                "name"=>"本人",
                "sub_button"=>[
                    "type"=>"view",
                    "name"=>"缺点",
                    "url"=>"http://www.soso.com/"
                ],
                [
                    "type"=>"miniprogram",
                    "name"=>"颜值高",
                    "url"=>"http://mp.weixin.qq.com",
                    "appid"=>"wx286b93c14bbf93aa",
                    "pagepath"=>"pages/lunar/index"
                ],
                [
                    "type"=>"click",
                    "name"=>"还是高",
                    "key"=>"V1001_GOOD"
                ]
            ]
        ];
        //处理中文编码
        $json_str=json_encode($p_arr,JSON_UNESCAPED_UNICODE);
        //发送请求
        $cli= new Client();
        $response=$cli->request('POST',$url,[
            'body' => $json_str
        ]);
        //处理响应
        $res_str=$response->getBody();
        $arr =json_decode($res_str,true);
        //判断错误信息
        if($arr['errcode']>0){
            echo "创建菜单失败";
        }else{
            echo "创建菜单成功";
        }





    }
}
