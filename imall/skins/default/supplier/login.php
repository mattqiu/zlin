<?php defined('InIMall') or exit('Access Invalid!');?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<title><?php echo $output['setting_config']['site_name']; ?>-供货商管理中心登录</title>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<link href="<?php echo SHOP_SKINS_URL?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL?>/css/supler_center.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL?>/css/home_login.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL?>/css/home_header.css" rel="stylesheet" type="text/css">
<link href="<?php echo RESOURCE_SITE_URL;?>/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo RESOURCE_SITE_URL;?>/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<style>
.ncsc-login-container .login-submit { font: 16px/24px "microsoft yahei"; color: #FFF; background-color: #F39800; width: 320px; height: 44px; border: 0; /*position: absolute; z-index: 1; bottom: 2px; right: 0; */cursor: pointer; margin-top:20px}
.ncsc-login-container .login-submit:hover { background-color:#df1e36; opacity: .8;}
</style>
<script language="JavaScript" type="text/javascript">
window.onload = function() {
    tips = new Array(2);
    tips[0] = document.getElementById("loginBG01");
    tips[1] = document.getElementById("loginBG02");
    index = Math.floor(Math.random() * tips.length);
    tips[index].style.display = "block";
};
$(document).ready(function() {
    //更换验证码
    function change_seccode() {
        $('#codeimage').attr('src', 'index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>&t=' + Math.random());
        $('#captcha').select();
    }

    $('[nctype="btn_change_seccode"]').on('click', function() {
        change_seccode();
    });

    //登陆表单验证
    $("#form_login").validate({
        errorPlacement:function(error, element) {
            element.prev(".repuired").append(error);
        },
        onkeyup: false,
        rules:{
            seller_name:{
                required:true
            },
            password:{
                required:true
            },
            captcha:{
                required:true,
                remote:{
                    url:"index.php?act=seccode&op=check&nchash=<?php echo $output['nchash'];?>",
                    type:"get",
                    data:{
                        captcha:function() {
                            return $("#captcha").val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                            change_seccode();
                        }
                    }
                }
            }
        },
        messages:{
            seller_name:{
                required:"<i class='icon-exclamation-sign'></i>用户名不能为空"
            },
            password:{
                required:"<i class='icon-exclamation-sign'></i>密码不能为空"
            },
            captcha:{
                required:"<i class='icon-exclamation-sign'></i>验证码不能为空",
                remote:"<i class='icon-frown'></i>验证码错误"
            }
        }
    });
	//Hide Show verification code
    $("#hide").click(function(){
        $(".code").fadeOut("slow");
    });
    $("#captcha").focus(function(){
        $(".code").fadeIn("fast");
    });

});
</script>
<style>
    #footer{background: none !important;}
</style>
</head>
<body style="background:#fff">
<div class="header-wrap">
    <header class="public-head-layout wrapper">
        <h1 class="site-logo">
        	<a href="<?php echo SHOP_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/logo.png" class="pngFix"></a>
        </h1>
        <div class="nc-regist-now">
      		<span class="avatar"><img src="<?php echo UPLOAD_SITE_URL;?>/shop/common/default_user_portrait.gif"></span>
   			<span>您好，欢迎来到<?php echo $output['setting_config']['site_name']; ?><br>已注册的供货商请登录，或立即
        		<a title="" href="<?php echo urlShop('supplier_register','register');?>" class="register">注册新供货商</a>
     		</span>
 		</div>
    </header>
</div>
<div style="width:1000px; margin:0px auto">
	<!--<a href="index.php" target="_blank" title="移动电商分销平台"><img src="/images/seller/login/login_logo.png" width="auto" height="50px" style="margin-top:30px;"/></a>-->
	<div style="clear:both"></div>
	<img src="<?php echo SHOP_SKINS_URL;?>/images/seller/login/login_left2.jpg" width="570" height="380" style="margin:90px 0px 50px;float: left;">
	
	<div class="ncsc-login-container" style="border-bottom:#F39800 1px solid; border-top:#F39800 solid 6px; float:right;margin:90px 0px 50px;">
	  <div class="ncsc-login-title">
	    <h2 style="text-align:right; color:#F39800; width:320px">供货商登录</h2>
	    <!-- 
	    <h4><a href="">门店管理登录</a></h4>
	    <span>请使用入驻申请时填写的“供货商用户名”作为登录用户名<br/>登录密码则商城用户密码一致</span>
	     -->
	  </div>
	  <form id="form_login" action="index.php?act=supplier_login&op=login" method="post" >
	    <?php Security::getToken();?>
	    <input name="nchash" type="hidden" value="<?php echo $output['nchash'];?>" />
	    <input type="hidden" name="form_submit" value="ok" />
	    <div class="input">
	      <label>用户名</label>
	      <span class="repuired"></span>
	      <input name="supplier_name" type="text" autocomplete="off" class="text" autofocus>
	      <span class="ico"><i class="icon-user"></i></span> </div>
	    <div class="input">
	      <label>密码</label>
	      <span class="repuired"></span>
	      <input name="password" type="password" autocomplete="off" class="text">
	      <span class="ico"><i class="icon-key"></i></span> </div>
	    <div class="input">
	      <label>验证码</label>
	      <span class="repuired"></span>
	      <input type="text" name="captcha" id="captcha" autocomplete="off" class="text" style="width: 140px;" maxlength="4" size="10" />
	      <div class="code" style="bottom: 2px; left: 180px;">
	        <!-- <div class="arrow"></div> -->
	        <div class="code-img"><a href="javascript:void(0)" nctype="btn_change_seccode"><img src="index.php?act=seccode&op=makecode&nchash=<?php echo $output['nchash'];?>" name="codeimage" border="0" id="codeimage"></a></div>
	        <a href="JavaScript:void(0);" id="hide" class="close" title="<?php echo $lang['login_index_close_checkcode'];?>"><i></i></a> <a href="JavaScript:void(0);" class="change" nctype="btn_change_seccode" title="<?php echo $lang['login_index_change_checkcode'];?>"><i></i></a> </div>
	      <span class="ico"><i class="icon-qrcode"></i></span>
	      <input type="submit" class="login-submit" value="确认登录">
	      <div style="float: right;"><a href="<?php echo urlShop('supplier_login','find_password');?>" style="color: #0066cc !important;">忘记密码？</a></div>
	    </div>
	  </form>
	</div>
	<div style="clear:both"></div>
	<div id="cti">
	  <div class="wrapper">
	    <ul></ul>
	  </div>
	</div>
	<div id="faq">
  		<div class="wrapper"></div>
	</div>
	<div id="footer" class="wrapper">
  		<p></p>
  		Copyright 2007-2016 © <a style="color:#666;" href="http://www.zlin-e.com" target="_blank" title="杭州智琳之家有限公司">杭州智琳之家有限公司</a><br>
  		Powered by <span class="vol"><?php echo $output['setting_config']['shopwwi_version'];?></span> <br>
   </div>
</div>
</body>
</html>
