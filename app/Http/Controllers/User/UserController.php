<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;
use App\Model\User\wxyuyin;
use App\Model\User\Wx;
use App\Model\User\wxtext;
use App\Model\User\wximage;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Model\jssdk;
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
//       echo $content;die;
        $time = date('Y-m-d H:i:s');
        $str = $time . $content . "\n";
        file_put_contents("logs/wx_event.log", $str, FILE_APPEND);
        $data = simplexml_load_string($content);
//        echo 'ToUserName: '. $data->ToUserName;echo '</br>';        // 公众号ID
//        echo 'FromUserName: '. $data->FromUserName;echo '</br>';    // 用户OpenID
//        echo 'CreateTime: '. $data->CreateTime;echo '</br>';        // 时间戳
//        echo 'MsgType: '. $data->MsgType;echo '</br>';              // 消息类型
//        echo 'Event: '. $data->Event;echo '</br>';                  // 事件类型
//        echo 'EventKey: '. $data->EventKey;echo '</br>';
//         echo 'EventKey: '. $data->MediaId;echo '</br>';
//die;
        $wx_id = $data->ToUserName;             // 公众号ID
        $openid = $data->FromUserName;         //用户OpenID
        $event = $data->Event;                 //事件类型
        $MsgType = $data->MsgType;
        $media_id = $data->MediaId;               //媒体文件id
//        消息类型
        if (isset($MsgType)) {        //检查变量是否被设置
            if ($MsgType == 'text') { //文本消息入库
//                //自动回复天气
                if (strpos($data->Content, "+天气")) {
                    //获取城市名字
                    $city = explode('+', $data->Content)[0];
//                    echo "city :".$city;
                    //接口地址
                    $url = "https://api.seniverse.com/v3/weather/now.json?key=SeZT72UG_JcAfRdxv&location=$city&language=zh-Hans&unit=c";
                    $name = file_get_contents($url);
                    $file_name = json_decode($name, true);
                    if (isset($file_name['results'][0]['now'])) {
                        $t_text = $file_name['results'][0]['now']['text'];//天气清空
                        $temperature = $file_name['results'][0]['now']['temperature'];//摄氏度
                        echo '<xml>
                              <ToUserName><![CDATA[' . $openid . ']]></ToUserName>
                              <FromUserName><![CDATA[' . $wx_id . ']]></FromUserName>
                              <CreateTime>' . time() . '</CreateTime>
                              <MsgType><![CDATA[text]]></MsgType>
                              <Content><![CDATA[' . '城市' . $city . '天气情况' . $t_text . '.摄氏度' . $temperature . ']]></Content>
                        </xml>';
                    } else {
                        echo '<xml>
                              <ToUserName><![CDATA[' . $openid . ']]></ToUserName>
                              <FromUserName><![CDATA[' . $wx_id . ']]></FromUserName>
                              <CreateTime>' . time() . '</CreateTime>
                              <MsgType><![CDATA[text]]></MsgType>
                              <Content><![CDATA[' . '此城市天气情况正在路上' . ']]></Content>
                        </xml>';
                    }
                    $a_arr = [
                        'openid' => $openid, //用户id
                        'Content' => $data->Content,//文本信息
                        'CreateTime' => $data->CreateTime,//发送信息事件
                        'd_time' => time(),//当前时间
                        'text' => $file_name['results'][0]['now']['text'],//天气情况
                        'city' => $city,//所在城市
                        'temperature' => $file_name['results'][0]['now']['temperature']//摄氏度
                    ];
                    $textInfo = wxtext::insertGetId($a_arr);
                }

                if ($data->Content == "最新消息") {
                    $wx_text = "最新消息";
                    echo '<xml>
                              <ToUserName><![CDATA[' . $openid . ']]></ToUserName>
                              <FromUserName><![CDATA[' . $wx_id . ']]></FromUserName>
                              <CreateTime>' . time() . '</CreateTime>
                              <MsgType><![CDATA[text]]></MsgType>
                              <Content><![CDATA[' . "$wx_text" . ']]></Content>
                        </xml>';
                } else if ($data->Content == "最新商品") {
                    $title = "劲爆新闻烨氏集团即将-";//标题
                    $textarea = "集团介绍 中国核工业集团有限公司是经国务院批准组建、中央直接管理的国有重要骨干企业,由200多家企事业单位和科研院所组成。国家核科技工业的主体,国家核能发展与...";
                    $url = "http://1809liuziye.comcto.com";
                    $picurl = "http://1809liuziye.comcto.com/img/6ee6ffa61d3ba98dfbba61ee85de93bd.jpg";
                    echo '
                        <xml>
                              <ToUserName><![CDATA[' . $openid . ']]></ToUserName>
                              <FromUserName><![CDATA[' . $wx_id . ']]></FromUserName>
                              <CreateTime>time()</CreateTime>
                              <MsgType><![CDATA[news]]></MsgType>
                              <ArticleCount>1</ArticleCount>
                              <Articles>
                                <item>
                                  <Title><![CDATA[' . $title . ']]></Title>
                                  <Description><![CDATA[' . $textarea . ']]></Description>
                                  <PicUrl><![CDATA[' . $picurl . ']]></PicUrl>
                                  <Url><![CDATA[' . $url . ']]></Url>
                                </item>
                              </Articles>
                            </xml>
                      ';
                }
            } else if ($MsgType == 'voice') {    //语音入库
                $file_name = $this->Wxyy($media_id); //语音的信息
                $b_arr = [
                    'openid' => $openid,    //用户id
                    'msg_type' => 'voice',  // 类型
                    'mediaid' => $data->MediaId, //媒体文件id
                    'format' => $data->Format,     //后缀
                    'MsgId' => $data->MsgId,
                    'file_url' => $file_name,
                ];
                //入库
                $fileyyInfo = wxyuyin::insertGetId($b_arr);
            } else if ($MsgType == 'image') {
                $file_name = $this->WxImage($media_id);
                $c_arr = [
                    'openid' => $openid,//用户id
                    'MsgType' => $data->MsgType,//l类型
                    'file_url' => $file_name,//文件路径信息
                    'MsgId' => $data->MsgId,//msgId
                    'MediaId' => $data->MediaId
                ];
                $imageInfo = wximage::insertGetId($c_arr);
            }
            if ($event == 'subscribe') {
                //扫码关注事件  通过查询数据库  做判断
                $Info = Wx::where(['openid' => $openid])->first();
                if ($Info) {
                    //数据库有值 就说明关注过
                    echo '<xml><ToUserName><![CDATA[' . $openid . ']]></ToUserName><FromUserName><![CDATA[' . $wx_id . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' . '欢迎回来 ' . $Info['nickname'] . ']]></Content></xml>';
                } else {
                    //没有值 添加入库
                    $u = $this->getUserInfo($openid);
                    $add = [
                        'openid' => $u['openid'],
                        'nickname' => $u['nickname'],
                        'sex' => $u['sex'],
                        'city' => $u['city'],
                        'headimgurl' => $u['headimgurl'],
                        'province' => $u['province'],
                        'country' => $u['country'],
                    ];
                    $userInfo = Wx::insertGetId($add);
                    echo '<xml><ToUserName><![CDATA[' . $openid . ']]></ToUserName><FromUserName><![CDATA[' . $wx_id . ']]></FromUserName><CreateTime>' . time() . '</CreateTime><MsgType><![CDATA[text]]></MsgType><Content><![CDATA[' . '欢迎关注 ' . $u['nickname'] . ']]></Content></xml>';

                }
                $updateInfo = Wx::where(['openid' => $openid])->update(['sub_status' => 1]);
            } else if ($event == 'unsubscribe') {
                $updateInfo = Wx::where(['openid' => $openid])->update(['sub_status' => 0]);

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
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.env('WX_APP_ID').'&secret='.env('WX_APP_SECRET');
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
    //图片素材
    public function WxImage($media_id){
        //调用素材接口
        $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->getAccessToken().'&media_id='.$media_id;
        //获取文件名
        $Client=new Client();
        $response = $Client->get($url);
        $file_info = $response->getHeader('Content-disposition'); //通过第三方类库获取文件的详细信息
        $file_name = substr(rtrim($file_info[0],'"'),-20); //把图片多余的符号去除并截取
        $file_url='/wx/image/'.$file_name; //图片的路径+图片
        //保存图片
        $imgInfo=Storage::disk('local')->put($file_url,$response->getBody());
        return $file_name;  //把路径返回回去

    }
    //语音素材
    public function Wxyy($media_id){
        //调用接口
        $url='https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$this->getAccessToken().'&media_id='.$media_id;
        $client=new Client();//引用第三方类库
        $response=$client->get($url); //通过第三类库获取
        $fileinfo=$response->getHeader('Content-disposition');
        $file_name=rtrim(substr($fileinfo[0],-20),'"');
        $wx_image_path = 'wx/images/'.$file_name;
        //保存语音
        $wxy=Storage::disk('local')->put($wx_image_path,$response->getBody());
        return $file_name;
    }
    //群发信息
    public function SendMsg($openid,$content){
        $msg_arr=[
            'touser'=>$openid,
            'msgtype'=>'text',
            'text'=>[
                'content'=>$content
            ],
        ];
        $json_xml=json_encode($msg_arr,JSON_UNESCAPED_UNICODE); //转化为json串
        //使用微信群发接口 使用accessToken
        $url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$this->getAccessToken();
        //使用第三方类库
        $Client= new Client();
        //把数据通过第三方库传过去
        $response=$Client->request('post',$url,[
            'body'=>$json_xml
        ]);
        return $response->getBody();
    }
    public function send(){
        //查询状态为登陆的
        $seInfo=Wx::where(['sub_status'=>1])->get()->toArray();
        //使用array_cloumn根据openid把数据返回某一列
        $openid=array_column($seInfo,'openid');
        //要发送的信息
        $msg="欢迎加入烨氏集团";
        //调用sendMsg把信息返回过去
        $response=$this->SendMsg($openid,$msg);
        echo $response;
    }
//    签名jssdk
    public function fxjssdk(){
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
        return view('wx.user',$data);
    }
    //微信回调
    public function repson(){


    }

}
