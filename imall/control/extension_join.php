<?php
/**
 * 推广员申请
 *
 * 
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class extension_joinControl extends BaseMemberControl{

	public function __construct() {
	    parent::__construct();
		
		if (OPEN_STORE_EXTENSION_STATE != 10 || C('gl_promotion_reg')!=1){
			showDialog('平台未开通推广员在线申请功能，如有疑问请与平台联系！');
		}
		//检查是否已经是推广员
		if ($_SESSION['M_mc_id']==2){
			showDialog('您已经是推广员，不能再申请！');
		}
	}
	
	/**
	 * 申请推广
	 */
	public function apply_extensionOp(){		
		$apply_info = Model('member')->getMemberInfoByID($_SESSION['member_id']);
		Tpl::output('apply_info', $apply_info);		
						
		Tpl::showpage('extension_join.index','null_layout');		
	}
	
	/**
	 * 保存申请推广
	 */
	public function apply_extension_saveOp(){		
		//检查推广员申请条件
		if (C('gl_promotion_require')>0){
			$ConsumeTotals = Model('extension')->GetMemberConsumeTotals($_SESSION['member_id']);
			if ($ConsumeTotals<C('gl_promotion_require')){
				showDialog('申请平台推广员需先在平台消费满'.C('gl_promotion_require').'元方可申请!您目前在平台的消费为：'.$ConsumeTotals.'元');
			}
		}
		$ai_addinfo = array();
		$ai_addinfo['truename'] = $_POST['truename'];
		$ai_addinfo['email'] = $_POST['email'];	
		$ai_addinfo['mobile'] = $_POST['mobile'];	
		$ai_addinfo['qq'] = $_POST['qq'];
		$ai_addinfo['areainfo'] = $_POST['areainfo'];
		$ai_addinfo['describe'] = $_POST['describe'];
		
		//是否有推广员推荐 - 推广处理
		$ai_target = GENERAL_PLATFORM_EXTENSION_ID;
		$ai_type = 2;//平台推广员
		$extension_id=cookie('iMall_extension');			
		if (!empty($extension_id)){
	        $extension_id=urlsafe_b64decode($extension_id);
		    $ai_target = $extension_id;
			$ai_type = 3;//下级推广员		    
		}
		
		$data=array();
		$data['ai_from'] = $_SESSION['member_id'];	
		$data['ai_target'] = $ai_target;
		$data['ai_type'] = $ai_type;		
		$data['ai_addinfo'] = serialize($ai_addinfo); //unserialize
		$data['ai_dispose'] = 0;
		$data['ai_views'] = 0;
		//$data['ai_replyinfo']='';
		$data['ai_addtime'] = time();
		//$data['ai_distime']=NULL;
		
		$model_apply = Model('apply_information');		
		$state = $model_apply->addApplyInfo($data);		
		if($state) {
			showDialog('推广申请提交成功！请等待管理员审核。',urlShop('member', 'home'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		} else {
			showDialog('推广申请提交失败!');
		};
	}	
}