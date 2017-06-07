<?php
/**
 * 入口文件
 *
 * 统一入口，进行初始化信息
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.SrpingWater.net
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */

error_reporting(E_ALL & ~E_NOTICE);
define('BASE_ROOT_PATH',str_replace('\\','/',dirname(__FILE__)));
/**
 * 安装判断
 */
if (!is_file(BASE_ROOT_PATH."/imall/install/lock") && is_file(BASE_ROOT_PATH."/imall/install/index.php")){
    if (ProjectName != 'shop'){
        @header("location: ../imall/install/index.php");
    }else{
        @header("location: install/index.php");
    }
    exit;
}
define('BASE_COMMON_PATH',BASE_ROOT_PATH.'/common');
define('BASE_DATA_PATH',BASE_ROOT_PATH.'/data');
define("BASE_UPLOAD_PATH", BASE_ROOT_PATH . "/data/upload");
define("BASE_RESOURCE_PATH", BASE_ROOT_PATH . "/data/resource");
define('DS','/');
define('InIMall',true);
define('StartTime',microtime(true));
define('TIMESTAMP',time());
define('DIR_SHOP','imall');
define('DIR_CMS','cms');
define('DIR_CIRCLE','circle');
define('DIR_MICROSHOP','microshop');
define('DIR_ADMIN','admin');
define('DIR_API','api');
define('DIR_MOBILE','mobile');
define('DIR_WAP','wap');
define('DIR_WEIXIN','weixin');
define('DIR_MUSIC','music');

define('DIR_RESOURCE','data/resource');
define('DIR_UPLOAD','data/upload');

define('ATTACH_PATH','shop');
define('ATTACH_COMMON','shop/common');
define('ATTACH_AVATAR','shop/avatar');
define('ATTACH_EDITOR','shop/editor');
define('ATTACH_MEMBERTAG','shop/membertag');
define('ATTACH_STORE','shop/store');
define('ATTACH_GOODS','shop/store/goods');
define('ATTACH_STORE_DECORATION','shop/store/decoration');
define('ATTACH_LOGIN','shop/login');
define('ATTACH_WAYBILL','shop/waybill');
define('ATTACH_ARTICLE','shop/article');
define('ATTACH_BRAND','shop/brand');
define('ATTACH_ADV','shop/adv');
define('ATTACH_ACTIVITY','shop/activity');
define('ATTACH_WATERMARK','shop/watermark');
define('ATTACH_POINTPROD','shop/pointprod');
define('ATTACH_GROUPBUY','shop/groupbuy');
define('ATTACH_LIVE_GROUPBUY','shop/livegroupbuy');
define('ATTACH_SLIDE','shop/store/slide');
define('ATTACH_VOUCHER','shop/voucher');
define('ATTACH_STORE_JOININ','shop/store_joinin');
define('ATTACH_REC_POSITION','shop/rec_position');
define('ATTACH_MOBILE','mobile');

define('ATTACH_CONSUMER_CARD','consumercard');
define('ATTACH_EXTENSION_QRCODE','extension');

define('ATTACH_WEIXIN','weixin');
define('ATTACH_WEIXIN_LINK','weixin/link');
define('ATTACH_WEIXIN_CASE','weixin/case');
define('ATTACH_MATERIAL','Material');
define('ATTACH_TEMP','TempFile');

define('ATTACH_MUSIC','music');

define('ATTACH_CIRCLE','circle');
define('ATTACH_CMS','cms');
define('ATTACH_LIVE','live');
define('ATTACH_MALBUM','shop/member');
define('ATTACH_MICROSHOP','microshop');
define('ATTACH_DELIVERY','delivery');
define('ATTACH_ADMIN_AVATAR','admin/avatar');
define('TPL_SHOP_NAME','default');
define('TPL_CIRCLE_NAME', 'default');
define('TPL_MICROSHOP_NAME', 'default');
define('TPL_CMS_NAME', 'default');
define('TPL_ADMIN_NAME', 'default');
define('TPL_DELIVERY_NAME', 'default');
define('TPL_WEIXIN_NAME', 'default');

define('ADMIN_MODULES_SYSTEM', 'modules/system');
define('ADMIN_MODULES_SHOP', 'modules/shop');
define('ADMIN_MODULES_CMS', 'modules/cms');
define('ADMIN_MODULES_CIECLE', 'modules/circle');
define('ADMIN_MODULES_MICEOSHOP', 'modules/microshop');
define('ADMIN_MODULES_MOBILE', 'modules/mobile');
define('ADMIN_MODULES_CONSUMERCARD', 'modules/consumercard');
define('ADMIN_MODULES_EXTENSION', 'modules/extension');
define('ADMIN_MODULES_QCMANAGE', 'modules/qcmanage');
define('ADMIN_MODULES_CHARITY', 'modules/charity');
define('ADMIN_MODULES_BUSINESS', 'modules/business');

/*
 * 商家入驻状态定义
 */
//新申请
define('STORE_JOIN_STATE_NEW', 10);
//完成付款
define('STORE_JOIN_STATE_PAY', 11);
//初审成功
define('STORE_JOIN_STATE_VERIFY_SUCCESS', 20);
//初审失败
define('STORE_JOIN_STATE_VERIFY_FAIL', 30);
//付款审核失败
define('STORE_JOIN_STATE_PAY_FAIL', 31);
//开店成功
define('STORE_JOIN_STATE_FINAL', 40);

//默认颜色规格id(前台显示图片的规格)
define('DEFAULT_SPEC_COLOR_ID', 1);

/**
 * 商品图片
 */
define('GOODS_IMAGES_WIDTH', '60,240,360,1280');
define('GOODS_IMAGES_HEIGHT', '60,240,360,12800');
define('GOODS_IMAGES_EXT', '_60,_240,_360,_1280');

/**
 *  订单状态
 */
//已取消
define('ORDER_STATE_CANCEL', 0);
//已产生但未支付
define('ORDER_STATE_NEW', 10);
//已支付
define('ORDER_STATE_PAY', 20);
//已发货
define('ORDER_STATE_SEND', 30);
//已收货，交易成功
define('ORDER_STATE_SUCCESS', 40);
//退换货
define('ORDER_STATE_REFUND', 50);
//订单超过N小时未支付自动取消
define('ORDER_AUTO_CANCEL_TIME', 1);
//订单超过N天未收货自动收货
define('ORDER_AUTO_RECEIVE_DAY', 10);

//预订尾款支付期限(小时)
define('BOOK_AUTO_END_TIME', 72);

/**
 * 订单删除状态
 */
//默认未删除
define('ORDER_DEL_STATE_DEFAULT',0);
//已删除
define('ORDER_DEL_STATE_DELETE',1);
//彻底删除
define('ORDER_DEL_STATE_DROP',2);

/**
 * 文章显示位置状态,1默认网站前台,2买家,3卖家,4全站
 * @var unknown
 */
define('ARTICLE_POSIT_SHOP', 1);
define('ARTICLE_POSIT_BUYER', 2);
define('ARTICLE_POSIT_SELLER', 3);
define('ARTICLE_POSIT_ALL', 4);

//兑换码过期后可退款时间，15天
define('CODE_INVALID_REFUND', 15);

//订单结束后可评论时间，15天，60*60*24*15
define('ORDER_EVALUATE_TIME', 1296000);

//是否开启抢购抢购功能
define('OPEN_GROUPBUY_STATE',0);
//是否开启资讯功能
define('OPEN_CMS_STATE',1);
//是否开圈子功能
define('OPEN_CIRCLE_STATE',1);
//是否开微商城功能
define('OPEN_MICROSHOP_STATE',1);
//是否开启闲置/二手市场功能
define('OPEN_MODULE_FLEA_STATE',0);
//是否开启微信公众平台功能
define('OPEN_MODULE_WEIXIN_STATE',1);
//是否开启音乐模块功能
define('OPEN_MODULE_MUSIC_STATE',0);

//是否开启店铺分店功能
define('OPEN_STORE_BRANCH_STATE',1);
//是否开启店铺淘宝客功能
define('OPEN_STORE_TAOBAOKE_STATE',0);
//是否开启店铺推广导购功能 0：不开启 1：单店推广 10：全站推广
define('OPEN_STORE_EXTENSION_STATE',10);
//平台推广ID
define('GENERAL_PLATFORM_EXTENSION_ID',9);
//是否开启消费养老保险卡功能
define('OPEN_CONSUMER_CARD_STATE',0);
//是否开启亲诚币充值功能
define('OPEN_QINCHENG_RECHARGE_STATE',0);
//是否开启公益慈善募捐功能
define('OPEN_CHARITY_FUNDRAISING_STATE',0);
//是否开启商务合作功能
define('OPEN_BUSINESS_COOPERATE_STATE',0);
//是否开云币购买功能
define('OPEN_POINT_PAY_STATE',1);

if (!@include(BASE_DATA_PATH.'/config/config.ini.php')) exit('config.ini.php isn\'t exists!');
if (file_exists(BASE_PATH.'/config/config.ini.php')){
	include(BASE_PATH.'/config/config.ini.php');
}
global $config;

//默认平台店铺id
define('DEFAULT_PLATFORM_STORE_ID', $config['default_store_id']);

define('URL_MODEL',$config['url_model']);
define(SUBDOMAIN_SUFFIX, $config['subdomain_suffix']);

define('MAIN_SITE_URL', $config['main_site_url']);
define('SHOP_SITE_URL', $config['shop_site_url']);
define('SHOP_modules_URL', $config['shop_modules_url']);
define('CMS_SITE_URL', $config['cms_site_url']);
define('CMS_modules_URL', $config['cms_modules_url']);
define('CIRCLE_SITE_URL', $config['circle_site_url']);
define('CIRCLE_modules_URL', $config['circle_modules_url']);
define('MICROSHOP_SITE_URL', $config['microshop_site_url']);
define('MICROSHOP_modules_URL', $config['microshop_modules_url']);
define('ADMIN_SITE_URL', $config['admin_site_url']);
define('ADMIN_modules_URL', $config['admin_modules_url']);
define('MOBILE_SITE_URL', $config['mobile_site_url']);
define('MOBILE_modules_URL', $config['mobile_modules_url']);
define('WAP_SITE_URL', $config['wap_site_url']);
define('WEIXIN_SITE_URL', $config['weixin_site_url']);
define('MUSIC_SITE_URL', $config['music_site_url']);
define('UPLOAD_SITE_URL',$config['upload_site_url']);
define('RESOURCE_SITE_URL',$config['resource_site_url']);
define('DELIVERY_SITE_URL',$config['delivery_site_url']);
define('WXAPP_SITE_URL', $config['wxapp_site_url']);

define('CONSUMERCARD_modules_URL', $config['consumercard_modules_url']);
define('EXTENSION_modules_URL', $config['extension_modules_url']);
define('QCMANAGE_modules_URL', $config['qcmanage_modules_url']);
define('CHARITY_modules_URL', $config['charity_modules_url']);
define('BUSINESS_modules_URL', $config['business_modules_url']);

define('BASE_DATA_PATH',BASE_ROOT_PATH.'/data');
define('BASE_UPLOAD_PATH',BASE_DATA_PATH.'/upload');
define('BASE_RESOURCE_PATH',BASE_DATA_PATH.'/resource');

define('CHARSET',$config['db'][1]['dbcharset']);
define('DBDRIVER',$config['dbdriver']);
define('SESSION_EXPIRE',$config['session_expire']);
define('LANG_TYPE',$config['lang_type']);
define('COOKIE_PRE',$config['cookie_pre']);
define('DBPRE',($config['db'][1]['dbname']).'`.`'.($config['tablepre']));
define('DBNAME',$config['db'][1]['dbname']);

$_GET['act'] = $_GET['act'] ? strtolower($_GET['act']) : ($_POST['act'] ? strtolower($_POST['act']) : null);
$_GET['op'] = $_GET['op'] ? strtolower($_GET['op']) : ($_POST['op'] ? strtolower($_POST['op']) : null);

if (empty($_GET['act'])){
    require_once(BASE_COMMON_PATH.'/kernel/route.php');
    new Route($config);
}
//统一ACTION
$_GET['act'] = preg_match('/^[\w]+$/i',$_GET['act']) ? $_GET['act'] : 'index';
$_GET['op'] = preg_match('/^[\w]+$/i',$_GET['op']) ? $_GET['op'] : 'index';

//对GET POST接收内容进行过滤,$ignore内的下标不被过滤
$ignore = array('article_content','pgoods_body','doc_content','content','sn_content','g_body','store_description','p_content','groupbuy_intro','remind_content','note_content','ref_url','adv_pic_url','adv_word_url','adv_slide_url','appcode','mail_content');
if (!class_exists('Security')) require(BASE_COMMON_PATH.'/libraries/security.php');
$_GET = !empty($_GET) ? Security::getAddslashesForInput($_GET,$ignore) : array();
$_POST = !empty($_POST) ? Security::getAddslashesForInput($_POST,$ignore) : array();
$_REQUEST = !empty($_REQUEST) ? Security::getAddslashesForInput($_REQUEST,$ignore) : array();
$_SERVER = !empty($_SERVER) ? Security::getAddSlashes($_SERVER) : array();

//启用ZIP压缩
if ($config['gzip'] == 1 && function_exists('ob_gzhandler') && $_GET['inajax'] != 1){
	ob_start('ob_gzhandler');
}else {
	ob_start();
}

require_once(BASE_COMMON_PATH.'/libraries/queue.php');
require_once(BASE_COMMON_PATH.'/function/kernel.php');
require_once(BASE_COMMON_PATH.'/kernel/base.php');
require_once(BASE_COMMON_PATH.'/function/goods.php');

//推广处理
if ((OPEN_STORE_EXTENSION_STATE > 0) && !empty($_GET['extension'])){	
  $extension= $_GET['extension'];
  setIMCookie('iMall_extension',$extension,24*3600);  
}

if(function_exists('spl_autoload_register')) {
	spl_autoload_register(array('Base', 'autoload'));
} else {
	function __autoload($class) {
		return Base::autoload($class);
	}
}
?>