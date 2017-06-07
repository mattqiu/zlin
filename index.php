<?php
/**
 * 入口
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com 
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */ 
$site_url = strtolower('http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/index.php')).'/imall/index.php');
//推广处理
if (!empty($_GET['extension'])){
  $site_url .= '?extension='.$_GET['extension'];  
}
@header('Location: '.$site_url);

