<?php
/**
 * 会员中心--我关注的品牌
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class member_brandfollowControl extends BaseMemberControl{
	public function __construct(){
        parent::__construct();
        Language::read('member_layout,member_member_favorites');
		$lang	= Language::getLangContent();
		
		//导航
		$nav_link = array(
			'0'=>array('title'=>$lang['homepage'],'link'=>SHOP_SITE_URL.'/index.php'),
			'1'=>array('title'=>$lang['im_user_center'],'link'=>SHOP_SITE_URL.'/index.php?act=member_snsindex'),
			'2'=>array('title'=>'我关注的品牌')
		);
		Tpl::output('nav_link_list',$nav_link);
    }
	/**
	 * 我关注的品牌列表
	 *
	 * @param
	 * @return
	 */
	public function indexOp(){
		$history_model = Model('member_browse_history');
		$history_list = $history_model->listHistory();
		
		self::profile_menu('browse_goods','browse_goods');
		Tpl::output('menu_key',"browse_goods");
		Tpl::output('history_list',$history_list);
		Tpl::output('show_page',$history_model->showpage());
		Tpl::showpage('history_goods_list');
	}

	/**
	 * 删除关注品牌
	 *
	 * @param
	 * @return
	 */
	public function delOp(){
		$goods_id=$_GET['goods_id'];
		Model('member_browse_history')->delHistory($goods_id);
		
	}
	
	/**
	 * 添加关注品牌
	 *
	 * @param
	 * @return
	 */
	public function addbrandfollowOp(){
		$brand_id = intval($_GET['bid']);
		if ($brand_id <= 0){
			echo json_encode(array('done'=>false,'msg'=>'非法操作'));
			die;
		}
		$follows_model = Model('brand_follow');
		//判断是否已经收藏
		$follows_info = $follows_model->getOneFollows(array('brand_id'=>"$brand_id",'member_id'=>"{$_SESSION['member_id']}"));
		if(!empty($follows_info)){
			echo json_encode(array('done'=>false,'msg'=>'您已关注此品牌！'));
			die;
		}
		//判断品牌是否为当前会员所有
		$brand_model = Model('brand');
		$brand_info = $brand_model->getOneBrand($brand_id);
		if ($brand_info['store_id'] == $_SESSION['store_id']){
			echo json_encode(array('done'=>false,'msg'=>'不能关注自己店铺的品牌'));
			die;
		}
		//添加收藏
		$insert_arr = array();
		$insert_arr['member_id'] = $_SESSION['member_id'];
		$insert_arr['brand_id'] = $brand_id;
		$insert_arr['follow_time'] = time();
		$result = $follows_model->addFollows($insert_arr);
		if ($result){
			//增加收藏数量
			Model('brand')->where(array('brand_id' => $brand_id))->update(array('brand_follows' => array('exp', 'brand_follows + 1')));
			echo json_encode(array('done'=>true,'msg'=>'关注成功'));
			die;
		}else{
			echo json_encode(array('done'=>false,'msg'=>'关注失败'));
			die;
		}
	}
	
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array = array(
			1=>array('menu_key'=>'browse_goods','menu_name'=>'我关注的品牌',	'menu_url'=>'index.php?act=member_goodsbrowse&op=list'),
		);
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
