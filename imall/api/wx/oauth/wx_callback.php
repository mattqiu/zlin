<?php 
require_once(BASE_PATH.DS.'api'.DS.'wx'.DS.'comm'.DS."config.php");
require_once(BASE_PATH.DS.'api'.DS.'wx'.DS.'comm'.DS."utils.php");

function wx_callback()
{
    //debug
    //print_r($_REQUEST);
    //print_r($_SESSION);	
    if($_REQUEST['state'] == $_SESSION['state']){ //csrf
    
        $token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?grant_type=authorization_code&"
            . "appid=" . $_SESSION["appid"]. "&secret=" . $_SESSION["appkey"]. "&code=" . $_REQUEST["code"];

        $response = get_url_contents($token_url);	
		
		$params = json_decode($response,true);	
        if ($params["errcode"])
        {
            echo "<h3>error:</h3>" . $params["errcode"];
            echo "<h3>msg  :</h3>" . $params["errmsg"];
            exit;
        }        
        //debug
        //print_r($params);
        //set access token to session		
        $_SESSION["access_token"] = $params["access_token"];
		$_SESSION["openid"] = $params["openid"];
		$_SESSION["unionid"] = $params["unionid"];
    }else{		
        echo("The state does not match. You may be a victim of CSRF.");
    }
}

//微信登录成功后的回调地址,主要保存access token
wx_callback();

@header('location: index.php?act=connect_wx');
exit;
?>
