<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<script src="/js/jquery/jquery-1.12.4.min.js"></script>
<script src="/js/layui/layui.js"></script>

<div class="btn-group" data-toggle="buttons">


</div>

<table border="1">
        <tr>
            <td>id</td>
            <td>openid</td>
            <td>用户名称</td>

        </tr>
    @foreach($userInfo as $k=>$v)
        <tr openid="{{$v['openid']}}">
            <td><input type="checkbox" class="check"></td>
            <td>{{$v['openid']}}</td>
            <td>{{$v['nickname']}}</td>
        </tr>
        @endforeach
</table>
<tbody>输入要发送的内容：</tbody>
<input type="text" id="text">
<button id="submit">发送</button>
</body>
</html>
<script>
$(function () {
    layui.use('layer',function(){
        var layer =layui.layer;
    $('#submit').click(function () {
        var check=$('.check');//获取复选框
        var openid='';
        check.each(function (index) {
            if($(this).prop('checked')==true){
                openid+=$(this).parents('tr').attr('openid')+',';
                // console.log(openid);//获取openid
            }
        })
        //去除多余的符号
        openid=openid.substr(0,openid.length-1);
        // console.log(openid);
            //获取文本框的值
        var text=$('#text').val();
        $.post(
            '/admin/sendadd',
            {openid:openid,text:text},
            function (res) {
                if(res.code==1){
                    history.go(0);
                    layer.msg(res.font,{cion:res.code});
                }else{
                    layer.msg(res.font,{cion:res.code});
                }
            },'json'


    )
        })
    })
})




</script>