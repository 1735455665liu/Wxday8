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
                desc: '集团自成立以来，借助资本的力量，通过十多年的努力，已经跻身成为中国领先的整体安全服务提供商。在中国推进国家治理体系和治理能力现代化的背景下，中城卫保安集团积极配合政府进行社会治安综合治理创新，为平安中国建设添砖加瓦。另外，随着中国的国际社会地位和国家影响力的不断上升，以及在“一带一路”倡议的大背景下，中城卫保安集团有志于在国际舞台上发挥出应有的贡献。\n' +
                    '\n' +
                    '2016年，位于长三角CBD——上海虹桥商务区核心区的中城卫大厦已投入使用，大厦将整合集团研发、结算、指挥、后援、押运、清分等功能，大厦的落成，已成为中城卫保安集团发展史上标志性的里程碑。\n' +
                    '\n' +
                    '中城卫保安集团具备保安服务壹级资质以及安全防范工程设计施工壹级资质。集团曾先后服务过北京奥运会、上海世博会、米兰世博会、广州亚运会、西安园博会、劳伦斯颁奖典礼、第三届丝绸之路国际电影节等。集团国内分支机构众多，已在全国范围内实现保安业务及区域的全覆盖，并已经将业务触角延伸到海外，直接为海外中资机构提供安保服务和技术支持，实现了中国保安行业启动全球服务版图的历史性突破。', // 分享描述
                link: 'http://1809liuziye.comcto.com', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                imgUrl: 'http://1809liuziye.comcto.com/img/6ee6ffa61d3ba98dfbba61ee85de93bd.jpg', // 分享图标
                success: function () {
                    // 设置成功
                }
            })
        })


</script>
</body>
</html>

