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
<script src="http://res2.wx.qq.com/open/js/jweixin-1.4.0.js "></script>
<script>
    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: "{{$js_jssdk['appId']}}", // 必填，公众号的唯一标识
        timestamp:"{{$js_jssdk['timestamp']}}", // 必填，生成签名的时间戳
        nonceStr: "{{$js_jssdk['nonceStr']}}", // 必填，生成签名的随机串
        signature: "{{$js_jssdk['signature']}}",// 必填，签名
        jsApiList: ['updateAppMessageShareData'] // 必填，需要使用的JS接口列表
    });

    //自动加载
        wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
            wx.updateAppMessageShareData({
                title: '烨氏你可以了解一下', // 分享标题
                desc: '123', // 分享描述
                link: 'http://1809liuziye.comcto.com/fxjssdk', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://1809liuziye.comcto.com/img/6ee6ffa61d3ba98dfbba61ee85de93bd.jpg', // 分享图标
                success: function () {
                    // 设置成功
                }
            })
        })


</script>
</body>
</html>

