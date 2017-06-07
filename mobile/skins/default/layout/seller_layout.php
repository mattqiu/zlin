<?php defined('InIMall') or exit('Access Invalid!');?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?php echo $output['html_title'];?></title>
    <!-- Stylesheets -->
    <script type="text/javascript" src="<?php echo MOBILE_SKINS_URL;?>/js/jquery.js"></script>
    <script>
        var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var MEMBER_SITE_URL = '<?php echo MEMBER_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_RESOURCE_SITE_URL = '<?php echo SHOP_RESOURCE_SITE_URL;?>';var SHOP_TEMPLATES_URL = '<?php echo SHOP_SKINS_URL;?>';</script>


    <link href="<?php echo MOBILE_SKINS_URL;?>/css/base.css" rel="stylesheet">
    <link href="<?php echo MOBILE_SKINS_URL;?>/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="<?php echo MOBILE_SKINS_URL;?>/css/bootstrap-switch.min.css" rel="stylesheet">

    <link href="<?php echo MOBILE_SKINS_URL;?>/css/jquery-confirm.min.css" rel="stylesheet">

    <link href="<?php echo MOBILE_SKINS_URL;?>/css/seller_center.css" rel="stylesheet">
    <link href="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css"/>
<script>
    $.browser = [];
    $.browser.msie = false;
    function showError(msg){
        alert(msg);
    }
</script>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>

    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/member.js" charset="utf-8"></script>
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>

    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
    <!-- Main stylesheet -->
    <link href="<?php echo MOBILE_SKINS_URL;?>/css/style.css" rel="stylesheet">
</head>

<body>
<?php
require_once($tpl_file);

echo '<div id="append_parent"></div>';
include template('layout/footer');
?>