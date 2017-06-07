<?php
//require_once(BASE_PATH.DS.'api'.DS.'wx'.DS.'comm'.DS."config.php");
require_once(BASE_ROOT_PATH.DS.DIR_WAP.DS.'api'.DS.'wx'.DS.'comm'.DS."utils.php");

function get_user_info()
{
    $get_user_info = "https://api.weixin.qq.com/sns/userinfo?"
        . "access_token=" . $_SESSION['access_token']
        . "&openid=" . $_SESSION["openid"]
        . "&format=json";

    $info = get_url_contents($get_user_info);
    $arr = json_decode($info, true);
    $arr = getGBK($arr,CHARSET);

    return $arr;
}

?>
