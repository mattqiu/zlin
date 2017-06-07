<?php
  require '../../../mobile/config/config.ini.php';
  require_once "../../jssdk.php";
  $jssdk = new JSSDK(WX_APIID, WX_APPSECRET);
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
	<meta name="msapplication-tap-highlight" content="no" />
	<meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
	<title>快速录入商品条形码</title>
	<link rel="stylesheet" type="text/css" href="../../css/base.css">
	<link rel="stylesheet" type="text/css" href="../../css/seller.css">
	<link rel="stylesheet" type="text/css" href="../../css/common.css">
	<link rel="stylesheet" type="text/css" href="../../css/seller/mammon.css">
</head>
<body>
<header id="header" class="seller-header">
  <div class="header-wrap">
    <div class="header-l"> <a href="seller.html"> <i class="back"></i> </a> </div>
    <div class="header-title">
      <h1>录入商品条码</h1>
    </div>
    <div class="header-r"><a id="header-nav" title="添加条码"><i class="save">添加条码</i></a></div>
  </div>
</header>
<div class="qm-main-layout">
  <div class="qm-inp-con">
  	<form action="" method ="" id="agoodsFrom">
  	  <input type="hidden" id="by_val" name="by_val" value=""/>
      <ul class="form-box">        
        <li class="form-item">
          <h4>*商品条码</h4>
          <div class="input-box">
            <input type="text" id="g_barcode" name="g_barcode" class="inp" autocomplete="off" placeholder="请输入商品条形码" oninput="writeClear($(this));" onfocus="writeClear($(this));"/>
            <i class="scan-icon" id="scanQRCode1"></i>
            <span class="input-del code"></span>
          </div>
        </li>
        <li class="form-item">
          <h4>*吊牌价</h4>
          <div class="input-box">
            <input type="text" id="g_marketprice" name="g_marketprice" class="inp" autocomplete="off" placeholder="请输入商品吊牌价" oninput="writeClear($(this));" onfocus="writeClear($(this));"/>
            <span class="input-del code"></span>
          </div>
        </li>
        <li class="form-item">
          <h4>折扣</h4>
          <div class="input-box">
            <input type="text" id="g_discount" name="g_discount" class="inp" autocomplete="off" placeholder="" oninput="writeClear($(this));" onfocus="writeClear($(this));"/>%
            <span class="input-del code"></span>
          </div>
        </li>
        <li class="form-item">
          <h4>*会员价</h4>
          <div class="input-box">
            <input type="text" id="g_price" name="g_price" class="inp" autocomplete="off" placeholder="请输入商品名称" oninput="writeClear($(this));" onfocus="writeClear($(this));"/>
            <span class="input-del code"></span>
          </div>
        </li>
        <li class="form-item">
          <h4>批发价</h4>
          <div class="input-box">
            <input type="text" id="g_tradeprice" name="g_tradeprice" class="inp" autocomplete="off" placeholder="请输入商品批发价" oninput="writeClear($(this));" onfocus="writeClear($(this));"/>
            <span class="input-del code"></span>
          </div>
        </li>
        <li class="form-item">
          <h4>商品国标码</h4>
          <div class="input-box">
            <input type="text" id="g_gbcode" name="g_gbcode" class="inp" autocomplete="off" placeholder="请输入商品国标码" oninput="writeClear($(this));" onfocus="writeClear($(this));"/>
            <i class="scan-icon" id="scanQRCode2"></i>
            <span class="input-del code"></span>
          </div>
        </li>
        <li class="form-item">
          <h4>*上级返佣</h4>
          <div class="input-box">
            <input type="text" id="commis_amount" name="commis_amount" class="inp" autocomplete="off" placeholder="请输入上级管理奖的返佣" oninput="writeClear($(this));" onfocus="writeClear($(this));"/>
            <span class="input-del code"></span>
          </div>
        </li>
        <li class="form-item">
          <h4>购买人返利</h4>
          <div class="input-box">
            <input type="text" id="rebate_amount" name="rebate_amount" class="inp" autocomplete="off" placeholder="请输入客户购买后返利" oninput="writeClear($(this));" onfocus="writeClear($(this));"/>
            <span class="input-del code"></span>
          </div>
        </li>
      </ul>
      <div class="mt5"></div>
     
      <div class="error-tips"></div>
      <div class="form-btn ">
      <input type="hidden" name="key" id="gc_key" value="" />
      <a href="javascript:void(0);" class="btn-l" id="feedbackbtn">提交</a>
      </div>
    </form>
    
    <div class="register-mobile-tip"> 小提示：条形码、吊牌价、会员价必填，批发价的作用是 会员价-批发价之间的差价将作为会员返现和高管的奖励。</div>
  </div>
</div>
<footer id="footer" class="bottom"></footer>
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
<script type="text/javascript" src="../../js/config.js"></script> 
<script type="text/javascript" src="../../js/core/zepto.min.js"></script> 
<script type="text/javascript" src="../../js/core/template.js"></script> 
<script type="text/javascript" src="../../js/common.js"></script> 
<script type="text/javascript" src="../../js/core/simple-plugin.js"></script> 
<script type="text/javascript" src="../../js/tmpl/seller/add_barcode.js"></script>
<script type="text/javascript" src="../../js/footer.js"></script>
<script>

var g_barcode = getQueryString("g_barcode");
var store_id = getQueryString("store_id");
if(g_barcode!=''){
	$('#get_barcode').val(g_barcode);
	if(g_barcode.indexOf(',')>=0){
		codearr = g_barcode.split(',');
		g_barcode = codearr[0];
	}
	$('#g_barcode').val(g_barcode);
}
wx.ready(function () {
	document.querySelector('#scanQRCode1').onclick = function () {
	    wx.scanQRCode({
	      	needResult: 1,
	      	desc: 'scanQRCode desc',
			success: function (res) {
				scancode = res.resultStr;
				//扫码后获取结果参数赋值给Input
              	if(scancode.indexOf(",")>=0){
                 	var tempArray = scancode.split(',');
                 	var g_barcode = tempArray[1];
              	}else{
              		var g_barcode = '';
              	}
	          	$('#g_barcode').val(g_barcode);
	      	}
		});
	};
	document.querySelector('#scanQRCode2').onclick = function () {
	    wx.scanQRCode({
	      	needResult: 1,
	      	desc: 'scanQRCode desc',
			success: function (res) {
				scancode = res.resultStr;
				//扫码后获取结果参数赋值给Input
	          	if(scancode.indexOf(",")>=0){
	             	var tempArray = scancode.split(',');
	             	var g_barcode = tempArray[1];
	          	}else{
	          		var g_barcode = '';
	          	}
	          	$('#g_gbcode').val(g_barcode);
	      	}
	    });
	}
});
</script>
</body>
</html>