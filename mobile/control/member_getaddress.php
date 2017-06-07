<?php

require_once('config/wxaddr.class.php');
$weixin = new class_weixin();
$url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

if (!isset($_GET["code"])){
    $jumpurl = $weixin->oauth2_authorize($url, "snsapi_base", "STATE");
    Header("Location: $jumpurl");
}else{
    $oauth2_access_token = $weixin->oauth2_access_token($_GET["code"]);
    $access_token = $oauth2_access_token['access_token'];
}


$timestamp = strval(time());
$noncestr = $weixin->create_noncestr();

$obj['appId']               = $weixin->appid;
$obj['url']                 = $url;
$obj['timeStamp']           = $timestamp;
$obj['noncestr']            = $noncestr;
$obj['accesstoken']         = $access_token;

$signature  = $weixin->get_biz_sign($obj);
$signPackage = array(
			"appId"     => $weixin->appid,
			"nonceStr"  => $noncestr,
			"timeStamp" => $timestamp,
			"addrSign" => $signature
	);
//echo json_encode($signPackage);
?>
<!DOCTYPE html>
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
	<title>获取共享收货地址</title>
	<link rel="stylesheet" type="text/css" href="../../wap/css/base.css">
	<link rel="stylesheet" type="text/css" href="../../wap/css/member.css">
	<link rel="stylesheet" type="text/css" href="../../wap/css/common.css">
</head>
<body>
<header id="header">
  <div class="header-wrap">
    <div class="header-l"> <a href="address_list.html"> <i class="back"></i> </a> </div>
    <div class="header-title">
      <h1>获取共享收货地址</h1>
    </div>
    <div class="header-r"> <a id="header-nav" href="javascript:void(0);"><i class="save"></i></a> </div>
  </div>
</header>
<div class="qm-main-layout">
  <form name="form1">
    <div class="qm-inp-con">
      <ul class="form-box">
        <li class="form-item">
          <h4>收货人姓名</h4>
          <div class="input-box">
            <input type="text" class="inp" name="true_name" id="true_name" autocomplete="off" oninput="writeClear($(this));"/>
            <span class="input-del"></span> </div>
        </li>
        <li class="form-item">
          <h4>联系手机</h4>
          <div class="input-box">
            <input type="tel" class="inp" name="mob_phone" id="mob_phone" autocomplete="off" oninput="writeClear($(this));"/>
            <span class="input-del"></span> </div>
        </li>
        <li class="form-item">
          <h4>地区</h4>
          <div class="input-box">
		<input name="area_info" type="text" class="inp" id="area_info" autocomplete="off" onchange="btn_check($('form'));" readonly/>
          </div>
        </li>
        <li class="form-item">
          <h4>详细地址</h4>
          <div class="input-box">
            <input type="text" class="inp" name="address" id="address" autocomplete="off" oninput="writeClear($(this));">
            <span class="input-del"></span> </div>
        </li>
        <li class="form-item"  style="display:none;">
          <h4>邮编</h4>
          <div class="input-box">
            <input type="text" class="inp" name="postcode" id="postcode" value="">
            <span class="input-del"></span> </div>
        </li>
	<li class="form-item" style="display:none;">
          <h4>结果</h4>
          <div class="input-box">
            <input type="text" class="inp" name="errmsg" id="errmsg" value="">
        </li>
        <li>
          <h4>默认地址</h4>
          <div class="input-box">
            <label>
              <input type="checkbox" class="checkbox" name="is_default" id="is_default" value="1" />
              <span class="power"><i></i></span> </label>
          </div>
        </li>
      </ul>
      <div class="error-tips"></div>
      <div class="form-btn">
      	<a class="btn" href="javascript:;">保存地址</a>
      </div>
      <div class="form-btn">
	  	<div><a class="btn-l mt5" onclick="callpay()">同步收货地址</a></div>
      </div>
    </div>
    <div style="display:none;">
    	<input type="hidden" name="appId" id="appId" value="<?php echo $obj['appId'];?>"/>
    	<input type="hidden" name="national" id="national" value=""/>
    </div>
  </form>
</div>
<footer id="footer" class="bottom"></footer>
<script type="text/javascript" src="../../wap/js/core/zepto.min.js"></script>
<script type="text/javascript" src="../../wap/js/core/simple-plugin.js"></script> 
<script type="text/javascript" src="../../wap/js/config.js"></script> 
<script type="text/javascript" src="../../wap/js/common.js"></script>
<script type="text/javascript" src="../../wap/js/footer.js"></script> 
<script language="javascript">
            function callpay()
            {
                WeixinJSBridge.invoke('editAddress',{
                    "appId" : "<?php echo $obj['appId'];?>",
                    "scope" : "jsapi_address",
                    "signType" : "sha1",
                    "addrSign" : "<?php echo $signature;?>",
                    "timeStamp" : "<?php echo $timestamp;?>",
                    "nonceStr" : "<?php echo $noncestr;?>",
                },function(res){
		    document.form1.address.value = res.addressDetailInfo;
		    document.form1.national.value = res.nationalCode;
		    document.form1.area_info.value = res.proviceFirstStageName +' '+ res.addressCitySecondStageName +' '+ res.addressCountiesThirdStageName;
                    document.form1.true_name.value  = res.userName;
                    document.form1.mob_phone.value  = res.telNumber;
                    document.form1.postcode.value   = res.addressPostalCode;
                    document.form1.errmsg.value     = res.err_msg;
                });
            }
</script>
<script type="text/javascript">
	$(function() {
    var a = getCookie("key");
    $.sValid.init({
        rules: {
            true_name: "required",
            mob_phone: "required",
            area_info: "required",
            address: "required"
        },
        messages: {
            true_name: "姓名必填！",
            mob_phone: "手机号必填！",
            area_info: "地区必填！",
            address: "街道必填！"
        },
        callback: function(a, e, r) {
            if (a.length > 0) {
                var i = "";
                $.map(e, 
                function(a, e) {
                    i += "<p>" + a + "</p>"
                });
                errorTipsShow(i)
            } else {
                errorTipsHide()
            }
        }
    });
    $("#header-nav").click(function() {
        $(".btn").click()
    });
    $(".btn").click(function() {
        if ($.sValid()) {
            var e = $("#true_name").val();
            var r = $("#mob_phone").val();
            var i = $("#address").val();
            var d = $("#area_info").attr("data-areaid2");
            var t = $("#area_info").attr("data-areaid");
            var n = $("#area_info").val();
            var o = $("#is_default").attr("checked") ? 1: 0;
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=member_address&op=address_add",
                data: {
                    key: a,
                    true_name: e,
                    mob_phone: r,
                    city_id: d,
                    area_id: t,
                    address: i,
                    area_info: n,
                    is_default: o
                },
                dataType: "json",
                success: function(a) {
                    if (a) {
                        location.href = WapSiteUrl + "/tmpl/member/address_list.html";
                    } else {
                        location.href = WapSiteUrl;
                    }
                }
            })
        }
    });
    
    $("#area_info").on("click", 
	    function() {
	        $.areaSelected({
	            success: function(a) {
	                $("#area_info").val(a.area_info).attr({
	                    "data-areaid": a.area_id,
	                    "data-areaid2": a.area_id_2 == 0 ? a.area_id_1: a.area_id_2
	                })
	            }
	        })
    });
    
});
</script>
</body>
</html>