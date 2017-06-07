<?php defined('InIMall') or exit('Access Invalid!');?>

<?php include template('layout/layout_common');?>
<?php include template('layout/layout_top');?>
<?php include template('layout/layout_header');?>
<?php include template('layout/nav_brands');?>
<?php include template('layout/cur_local');?>
<link href="<?php echo SHOP_SKINS_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL;?>/css/brand.css" rel="stylesheet" type="text/css">
<?php require_once($tpl_file);?>
<?php include template('layout/sidebar_right');?>
<?php include template('layout/layout_footer');?>