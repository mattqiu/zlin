<?php
/**
 * chat
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class Chat {
	public static function getChatHtml($layout){
		$web_html = '';
		if ($layout != 'layout/msg_layout.php' && $layout != 'layout/store_joinin_layout.php'){
			$config_file = BASE_ROOT_PATH.DS.'iMchat'.DS.'config'.DS."config.ini.php";
			require_once $config_file;
			$avatar = getMemberAvatar($_SESSION['avatar']);
			$imhash = getIMhash();
			$formhash = Security::getTokenValue();
			$css_url = CHAT_SKINS_URL;
			$app_url = APP_SITE_URL;
			$chat_url = CHAT_SITE_URL;
			$node_url = NODE_SITE_URL;
			$shop_url = SHOP_SITE_URL;
			
			$web_html = <<<EOT
					<link href="{$css_url}/css/chat.css" rel="stylesheet" type="text/css">
					<link href="{$css_url}/css/home_login.css" rel="stylesheet" type="text/css">
					<div style="clear: both;"></div>
					<div id="web_chat_dialog" style="display: none;float:right;">
					</div>
					<a id="chat_login" href="javascript:void(0)" style="display: none;"></a> 
					<script type="text/javascript">
					var APP_SITE_URL = '{$app_url}';
					var CHAT_SITE_URL = '{$chat_url}';
					var SHOP_SITE_URL = '{$shop_url}';
					var connect_url = "{$node_url}";
					
					var layout = "{$layout}";
					var act_op = "{$_GET['act']}_{$_GET['op']}";
					var user = {};
					
					user['u_id'] = "{$_SESSION['member_id']}";
					user['u_name'] = "{$_SESSION['member_name']}";
					user['s_id'] = "{$_SESSION['store_id']}";
					user['s_name'] = "{$_SESSION['store_name']}";
					user['avatar'] = "{$avatar}";
					
					$("#chat_login").im_login({
					  action:'/index.php?act=login',
					  imhash:'{$imhash}',
					  formhash:'{$formhash}'
					});
					</script>
EOT;
			if (defined('APP_ID') && APP_ID != 'shop'){
                $web_html .= '<link href="' . RESOURCE_SITE_URL . '/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">';
				$web_html .= '<script type="text/javascript" src="'.RESOURCE_SITE_URL.'/js/perfect-scrollbar.min.js"></script>';
				$web_html .= '<script type="text/javascript" src="'.RESOURCE_SITE_URL.'/js/jquery.mousewheel.js"></script>';
			}
			$web_html .= '<script type="text/javascript" src="'.RESOURCE_SITE_URL.'/js/jquery.charCount.js" charset="utf-8"></script>';
			$web_html .= '<script type="text/javascript" src="'.RESOURCE_SITE_URL.'/js/jquery.smilies.js" charset="utf-8"></script>';
			$web_html .= '<script type="text/javascript" src="'.CHAT_RESOURCE_URL.'/js/user.js" charset="utf-8"></script>';
			
		}
		if ($layout == 'layout/seller_layout.php'){
		    $web_html .= '<script type="text/javascript" src="'.CHAT_RESOURCE_URL.'/js/store.js" charset="utf-8"></script>';
		    $seller_smt_limits = '';
		    if (!empty($_SESSION['seller_smt_limits']) && is_array($_SESSION['seller_smt_limits'])) {
		        $seller_smt_limits = implode(',', $_SESSION['seller_smt_limits']);
		    }
			$web_html .= <<<EOT
					<script type="text/javascript">
					$(function(){
						$("#jquery_jplayer_1").jPlayer({
							ready: function () {
								$(this).jPlayer("setMedia", {
									mp3: CHAT_SITE_URL+"/resource/message.mp3",
									autoPlay:false,
								});
							},
							swfPath: CHAT_SITE_URL+"/resource/js",
							supplied: "mp3"
						});
					});
					user['seller_id'] = "{$_SESSION['seller_id']}";
					user['seller_name'] = "{$_SESSION['seller_name']}";
					user['seller_is_admin'] = "{$_SESSION['seller_is_admin']}";
					var smt_limits = "{$seller_smt_limits}";
					</script>
EOT;
			$web_html .= '<div id="jquery_jplayer_1" class="jp-jplayer"></div>';			
		}

		return $web_html;
	}
}
?>
