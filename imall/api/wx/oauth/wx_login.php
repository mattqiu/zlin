<?php
require_once(BASE_PATH.DS.'api'.DS.'wx'.DS.'comm'.DS."config.php");
$_SESSION['state'] = md5(uniqid(rand(), TRUE)); //CSRF protection
?>
<div class="im-login-content tc" id="login_container"></div>
<script>
$(function(){	
    $.getScript("http://res.wx.qq.com/connect/zh_CN/htmledition/js/wxLogin.js", function(){		
        var obj = new WxLogin({
            id:"login_container", 
            appid: "<?php echo $_SESSION["appid"];?>", 
            scope: "<?php echo $_SESSION["scope"];?>", 
            redirect_uri: "<?php if(empty($_SESSION["callback"])){echo cookie("callback");}else{echo $_SESSION["callback"];}?>",
            state: "<?php echo $_SESSION['state'];?>",
            style: "",
            href: ""
        });
    });
});
</script>
