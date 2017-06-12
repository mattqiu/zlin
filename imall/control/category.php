<?php
/**
 * 前台分类
 *
 * 
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class categoryControl extends BaseHomeControl {
	/**
	 * 分类列表
	 */
	public function indexOp(){
		Language::read('home_category_index');
		$lang	= Language::getLangContent();
		//导航
		$nav_link = array(
			'0'=>array('title'=>$lang['homepage'],'link'=>SHOP_SITE_URL),   
			'1'=>array('title'=>$lang['category_index_goods_class'])
		);
		Tpl::output('nav_link_list',$nav_link);
		
		Tpl::output('html_title',C('site_name').' - '.Language::get('category_index_goods_class'));
		Tpl::showpage('category');
	}
}
