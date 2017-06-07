<?php
require_once "jssdk.php";
$jssdk = new JSSDK("wxc4d6508e8ca8bd59", "1884fe13eddbf207bf11737d5c1e2624");
$signPackage = $jssdk->GetSignPackage();
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-touch-fullscreen" content="yes" />
<meta name="format-detection" content="telephone=no"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<meta name="msapplication-tap-highlight" content="no" />
<meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
<title>扫码购物</title>
<link rel="stylesheet" type="text/css" href="css/base.css">
<link rel="stylesheet" type="text/css" href="css/common.css">
</head>
<body>
<header id="header">
  <div class="header-wrap">
    <div class="header-l">
	  <a href="javascript:history.go(-1)">
		<i class="back"></i>
	  </a>
	</div>
	<div class="header-inp">
	  <i class="icon"></i>
	  <input type="text" class="search-input" value="" oninput="writeClear($(this));" id="keyword" placeholder="请输入搜索关键词" maxlength="50" autocomplete="on" autofocus>
	  <span class="input-del"></span>
	  <i class="scan-icon" id="scanQRCode1"></i> 
	</div>
	<div class="header-r">
	  <a id="header-nav" href="javascript:void(0);" class="search-btn">搜索</a>
	</div>
  </div>
</header>
<div class="qm-main-layout mb-20" id="search_tip_list_container" style="display:none"></div>
<!-- 全文搜索提示 end -->
<div id="store-wrapper">
  <div class="qm-search-layout">
    <dl class="hot-keyword">
      <dt>热门搜索
      <a id="scanQRCode2">
	      	<i class="scan-icon"></i> 
	      	<span>扫一扫</span>
      	</a>
      </dt>
      <dd id="hot_list_container">
      <ul>
  
  	<li><a href="tmpl/product_list.html?keyword=%E7%BE%8A%E7%BB%92%E5%A4%A7%E8%A1%A3%E4%B8%A8%E6%98%A5%E8%8A%82%E6%B4%BB%E5%8A%A8">羊绒大衣丨春节活动</a></li>
  
	</ul>
  	</dd>
    </dl>
    <dl>
      <dt>历史纪录</dt>
      <dd id="search_his_list_container"></dd>
    </dl>
  </div>
</div>
<!-- 全文搜索提示 begin -->
<footer id="footer" class="bottom"></footer>

</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  // 注意：所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。 
  // 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
  // 完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
  wx.config({
	  debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
                'checkJsApi',
                'scanQRCode'
              ]
  });
</script>
<script type="text/javascript" src="js/core/zepto.min.js"></script>
<script type="text/javascript" src="js/core/template.js"></script> 
<script type="text/javascript" src="js/config.js"></script> 
<script type="text/javascript" src="js/common.js"></script> 
<script type="text/javascript" src="js/tmpl/search.js"></script> 
<script type="text/javascript" src="js/footer.js"></script>
<script>
wx.ready(function () {
	//一毫秒触发
	setTimeout(function() {
		//IE
		if(document.all) {
			document.getElementById("scanQRCode2").click();
		}
		// 其它浏览器
		else {
			var e = document.createEvent("MouseEvents");
			e.initEvent("click", true, true);
			document.getElementById("scanQRCode2").dispatchEvent(e);
		}
	}, 1);
  $("#scanQRCode2").click(function() {
		var store_id = getQueryString("store_id");
      wx.scanQRCode({
          // 默认为0，扫描结果由微信处理，1则直接返回扫描结果
          needResult : 1,
          desc : 'scanQRCode desc',
          success : function(res) {
	        	  scancode = JSON.stringify(res.resultStr);
		    	  $.getJSON(ApiUrl + "/index.php?act=goods&op=get_goodsID", {scancode: scancode,store_id:store_id}, function(result) {
				if (!result.error) {					
		                    var data = result.datas;
		                    goods_id = data.goods_id;
		                    window.location.href=WapSiteUrl+"/tmpl/product_detail.html?goods_id="+data.goods_id;
		                }else {
		                    alert(result.error);
		                }
		            })
              //扫码后获取结果参数赋值给Input
	              //var url = res.resultStr;
	              //alert(url);
              //商品条形码，取","后面的
	              //if(url.indexOf(",")>=0){
	              //    var tempArray = url.split(',');
	              //    var tempNum = tempArray[1];
	              //    alert(tempNum);
	              //}else{
	              //    alert(url);
	              //}
          }
      });
  });

	document.querySelector('#scanQRCode1').onclick = function () {
		var store_id = getQueryString("store_id");
	    wx.scanQRCode({
	      needResult: 1,
	      desc: 'scanQRCode desc',
	      success: function (res) {
	          scancode = JSON.stringify(res.resultStr);
	    	  $.getJSON(ApiUrl + "/index.php?act=goods&op=get_goodsID", {scancode: scancode,store_id:store_id}, function(result) {
	                
			if (!result.error) {					
	                    var data = result.datas;
	                    goods_id = data.goods_id;
	                    window.location.href=WapSiteUrl+"/tmpl/product_detail.html?goods_id="+data.goods_id;
	                }else {
	                    alert(result.error);
	                }
	            })
	      }
	    });
	  };
   });
</script>
</html>
<script type="text/html" id="search_tip_list_script">
<ul class="qm-default-list">
  <%for(i = 0; i < list.length; i++){%>
  <li><a href="<%=$buildUrl('keyword',list[i])%>"><%=list[i]%></a></li>
  <%}%>
</ul>
</script>
<script type="text/html" id="hot_list">
<ul>
  <%for(i = 0; i < list.length; i++){%>
  <li><a href="<%=$buildUrl('keyword',list[i])%>"><%=list[i]%></a></li>
  <%}%>
</ul>
</script>
<script type="text/html" id="search_his_list">
<ul>
  <%for(i = 0; i < his_list.length; i++){%>
  <li><a href="<%=$buildUrl('keyword',his_list[i])%>"><%=his_list[i]%></a></li>
  <%}%>
</ul>
<a href="javascript:void(0);" class="clear-history" onclick="clear_his_key(this);">清空历史</a>
</script>