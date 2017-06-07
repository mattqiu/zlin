<?php defined('InIMall') or exit('Access Invalid!');?>

<?php include template('layout/layout_common');?>
<?php include template('layout/layout_top');?>
<?php include template('layout/layout_header');?>
<?php include template('layout/nav_goods');?>
<?php include template('layout/cur_local');?>
<?php require_once($tpl_file);?>
<?php include template('layout/sidebar_right');?>
<?php include template('layout/layout_footer');?>