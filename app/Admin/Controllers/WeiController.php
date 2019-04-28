<?php

namespace App\Admin\Controllers;

use App\Model\User\Wx;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use GuzzleHttp\Client;
class WeiController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Wx);

        $grid->id('Id');
        $grid->openid('Openid');
        $grid->nickname('Nickname');
        $grid->sex('Sex');
        $grid->city('City');
        $grid->province('Province');
        $grid->country('Country');
        $grid->headimgurl('Headimgurl');
        $grid->sub_status('Sub status');
        $grid->footer(function ($query) {
            return 'footer';
        });
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
        protected function detail($id)
    {
        $show = new Show(Wx::findOrFail($id));

        $show->id('Id');
        $show->openid('Openid');
        $show->nickname('Nickname');
        $show->sex('Sex');
        $show->city('City');
        $show->province('Province');
        $show->country('Country');
        $show->headimgurl('Headimgurl');
        $show->sub_status('Sub status');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Wx);

        $form->text('openid', 'Openid');
        $form->text('nickname', 'Nickname');
        $form->text('sex', 'Sex');
        $form->text('city', 'City');
        $form->text('province', 'Province');
        $form->text('country', 'Country');
        $form->text('headimgurl', 'Headimgurl');
        $form->switch('sub_status', 'Sub status')->default(1);

        return $form;
    }

    public function send(Content $content){

        //查询用户数据把数据传到前端处理
        $userInfo=Wx::all()->toArray();
        return $content
            ->header('用户')
            ->description('发送消息')
            ->body(view('admin.wx.wei',['userInfo'=>$userInfo]));
    }
    public function sendadd(){
        $val=request()->input();
        //获取文本框的值
        $content=$val['text'];
        //处理openid的值,把,号去掉并转换成数组
        $openid=explode(',',$val['openid']);
        //获取token
        $token=Getasstoken();
        $Cuzzle=new Client();//引用第三方类库
        $url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=".$token;
        $msg_arr=[
            'touser'=>$openid,
            'msgtype'=>'text',
            'text'=>[
                'content'=>$content
            ],
        ];
        $arr=json_encode($msg_arr,JSON_UNESCAPED_UNICODE);
        $response=$Cuzzle->request('post',$url,[
            'body'=>$arr
        ]);
            $res=$response->getBody();
            $arr=json_decode($res,true);
            if($arr['errcode']>0){
                    fial('发送成功');
            }else{
                   errores('发送失败');
            }
//            var_dump($arr);die;
    }
}
