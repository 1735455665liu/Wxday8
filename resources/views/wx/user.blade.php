<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="/img/bitbug_favicon.ico" type="image/x-icon">
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
                desc: '烨氏集团始建于1987年，由香港烨氏控股有限公司全资控股，历经三十余载的稳健发展与沉淀，集团成长为以房地产开发为核心业务，涵盖金融、矿山、酒店、贸易、商业、物管等领域，资产超过1000亿的现代大型综合性企业集团。\n' +
                    '\n' +
                    '烨氏集团扎根深圳辐射珠三角，联袂全球优质资源，领导时代发展步伐，为城市空间事业做出卓著贡献。近10年来，烨氏集团聚焦“城市核心资源运营专家”的发展战略，在房地产综合开发领域，以“匠心·筑经典”为企业理念，在城市核心铸造出一个又一个标杆项目。 2016年，集团开发的烨氏滨城二期项目荣获“全市上半年销售金额冠军”佳绩，成为深圳市首个单盘均价超过10万元/㎡的住宅项目；2013-2016年，集团的销售额持续排名深圳前十。截止2016年底，烨氏集团的储备项目多达50个，土地储备面积超500万㎡。', // 分享描述
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

