<?php
/**
 * 城市联盟（合伙人计划）推广配置
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class cityunion_configControl extends BaseExtensionControl {
	private $links = array(
        array('url'=>'act=cityunion_config&op=config','lang'=>'cityunion_config'), //城市联盟推广配置
        array('url'=>'act=cityunion_config&op=manage','lang'=>'cityunion_partner'), //合伙人补贴
        array('url'=>'act=cityunion_config&op=perfor','lang'=>'cityunion_seller'), //商家补贴
		array('url'=>'act=cityunion_config&op=commislevel','lang'=>'cityunion_commislevel'),//推广佣金分配
		array('url'=>'act=cityunion_config&op=commisclass','lang'=>'cityunion_commisclass'),//推广佣金模板
    );

    public function __construct() {
    	parent::__construct() ;
    	Language::read('cityunion');
    }	
	
	public function indexOp() {
        $this->configOp();
    }
	
	/**
	 * 推广配置
	 *
	 */
    public function configOp() {
		$model_setting = Model('setting');
		
		$cityunion_setting = $model_setting->getListSetting();
		
		$gl_partner_reg = $cityunion_setting['gl_partner_reg'];
		$gl_partner_level = $cityunion_setting['gl_partner_level'];
		$gl_invite_money = $cityunion_setting['gl_invite_money'];
		$gl_invite_min = $cityunion_setting['gl_invite_min'];		
				
		Tpl::output('gl_partner_reg',$gl_partner_reg);
		Tpl::output('gl_partner_level',$gl_partner_level);
		Tpl::output('gl_invite_money',$gl_invite_money);
		Tpl::output('gl_invite_min',$gl_invite_min);

        Tpl::output('top_link',$this->sublink($this->links,'config'));
        Tpl::setDirquna('extension');
        Tpl::showpage('cityunion_config.config');
    } 
	
	public function config_saveOp() {	
	    if (chksubmit()){
			$model_setting = Model('setting');
			
			$data = array();
			$data['gl_partner_reg'] = $_POST['gl_partner_reg'];
			$data['gl_partner_level'] = $_POST['gl_partner_level'];//合伙人等级
			$data['gl_invite_money'] = $_POST['gl_invite_money'];//合伙人条件
			$data['gl_invite_min'] = $_POST['gl_invite_min'];//合伙人条件
			
			$result = $model_setting->saveSetting($data);
			if ($result){
				showMessage(Language::get('im_common_save_succ'));
			}else {
				showMessage(Language::get('im_common_save_fail'));
			}	
		}else{
			showMessage('非法操作');
		}
    } 
	
	/**
	 * 合伙人管理服务奖励方案
	 *
	 */
    public function manageOp() {
        $model_manage = Model('extension_manageaward');
		$manage_list = $model_manage->getExtensionManageAwardListByStoreID(GENERAL_PLATFORM_EXTENSION_ID,'*',1);		
		Tpl::output('manage_list',$manage_list);
		
		Tpl::output('top_link',$this->sublink($this->links,'manage'));
		Tpl::setDirquna('extension');
        Tpl::showpage('cityunion_config.manage');
    }    

	/**
	 * 编辑管理服务奖励方案
	 */
	public function manage_editOp(){
		$model_manage = Model('extension_manageaward');

		$em_id = intval($_GET["em_id"]);
		$manage_info = $model_manage->getExtensionManageAwardByID($em_id,'*',1);		
		Tpl::output('manage_info',$manage_info);
		
		Tpl::setDirquna('extension');
		Tpl::showpage('cityunion_config.manage.edit','null_layout');
	}
	/**
	 * 保存管理服务奖励方案
	 *
	 * @param 
	 * @return 
	 */
	public function manage_saveOp() {
		$model_manage	= Model('extension_manageaward');
		
		$data=array();
		$data['store_id']    = GENERAL_PLATFORM_EXTENSION_ID;
		$data['award_name']  = $_POST['award_name'];
		$data['mc_id']       = $_POST['mc_id'];
		$data['sub_nums']    = $_POST['sub_nums'];
		$data['child_nums']  = $_POST['child_nums'];
		$data['order_nums']  = $_POST['order_nums'];
		$data['achieve_val'] = $_POST['achieve_val'];
		$data['award_rate']	 = $_POST['award_rate'];
		$data['award_level'] = $_POST['award_level'];
		$data['points_rate'] = $_POST['points_rate'];
		$data['extend_type'] = 1; //表示城市联盟（B2B）
		if($_POST['em_id'] != '') {
			$where=array();
			$where['em_id']=intval($_POST['em_id']);
			$state = $model_manage->editExtensionManageAward($data, $where);
			if($state) {
				showDialog('修改成功',urlAdminExtension('cityunion_config', 'manage'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		} else {
			$state = $model_manage->addExtensionManageAward($data);
			if($state) {
				showDialog('添加成功',urlAdminExtension('cityunion_config', 'manage'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('添加失败');
			}
		}
	}
	
	/**
	 * 删除管理服务奖励方案
	 *
	 * @param 
	 * @return 
	 */
	public function manage_delOp() {
		$model_manage	= Model('extension_manageaward');		
	
		if($_GET['em_id'] != '') {
			$where=array();
			$where['em_id']=intval($_GET['em_id']);
			$state = $model_manage->delExtensionManageAward($where);
			if($state) {
				showDialog('删除成功',urlAdminExtension('cityunion_config', 'manage'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlAdminExtension('cityunion_config', 'manage'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}	
	
	/**
	 * 绩优奖励方案
	 *
	 */
    public function perforOp() {
        $model_perfor = Model('extension_perforaward');
		$perfor_list = $model_perfor->getExtensionPerforAwardListByStoreID(GENERAL_PLATFORM_EXTENSION_ID);		
		Tpl::output('perfor_list',$perfor_list);
		
		Tpl::output('top_link',$this->sublink($this->links,'perfor'));
		Tpl::setDirquna('extension');
        Tpl::showpage('cityunion_config.perfor');
    }    

	/**
	 * 编辑绩优奖励方案
	 */
	public function perfor_editOp(){
		$model_perfor = Model('extension_perforaward');

		$ep_id = intval($_GET["ep_id"]);
		$perfor_info = $model_perfor->getExtensionPerforAwardByID($ep_id);		
		Tpl::output('perfor_info',$perfor_info);
		
		Tpl::setDirquna('extension');
		Tpl::showpage('cityunion_config.perfor.edit','null_layout');
	}
	/**
	 * 保存绩优奖励方案
	 *
	 * @param 
	 * @return 
	 */
	public function perfor_saveOp() {
		$model_perfor	= Model('extension_perforaward');		
	
		$data=array();
		$data['store_id']    = GENERAL_PLATFORM_EXTENSION_ID;
		$data['award_name']  = $_POST['award_name'];
		$data['mc_id']       = $_POST['mc_id'];
		$data['achieve_val'] = $_POST['achieve_val'];
		$data['award_rate']= $_POST['award_rate'];
		$data['award_level'] = $_POST['award_level'];
		
		if($_POST['ep_id'] != '') {
			$where=array();
			$where['ep_id']=intval($_POST['ep_id']);
			$state = $model_perfor->editExtensionPerforAward($data, $where);
			if($state) {
				showDialog('修改成功',urlAdminExtension('cityunion_config', 'perfor'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		} else {
			$state = $model_perfor->addExtensionPerforAward($data);
			if($state) {
				showDialog('添加成功',urlAdminExtension('cityunion_config', 'perfor'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('添加失败');
			}
		}
	}
	
	/**
	 * 删除绩优奖励方案
	 *
	 * @param 
	 * @return 
	 */
	public function perfor_delOp() {
		$model_perfor	= Model('extension_perforaward');		
	
		if($_GET['ep_id'] != '') {
			$where=array();
			$where['ep_id']=intval($_GET['ep_id']);
			$state = $model_perfor->delExtensionPerforAward($where);
			if($state) {
				showDialog('删除成功',urlAdminExtension('cityunion_config', 'perfor'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlAdminExtension('cityunion_config', 'perfor'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
	
	/**
	 * 职业经理人佣金分成配置
	 *
	 */
    public function commislevelOp() {		
        $model_level = Model('cityunion_commis_rate');
        
        //职业经理人
        $commislevel = $model_level->getCommisRateInfo(GENERAL_PLATFORM_EXTENSION_ID);
        $gl_partner_level = C('gl_partner_level')?C('gl_partner_level'):1;//职业经理人邀请层级数
        if(!empty($commislevel['rate_supplier_level'])){
        	$rate_supplier_level = unserialize($commislevel['rate_supplier_level']);
        }else{
        	for ($i=0;$i<$gl_partner_level;$i++){
        		$rate_supplier_level[$i] = 0;
        	}
        }
        if(!empty($commislevel['rate_trader_level'])){
        	$rate_trader_level = unserialize($commislevel['rate_trader_level']);
        }else{
        	for ($i=0;$i<$gl_partner_level;$i++){
        		$rate_trader_level[$i] = 0;
        	}
        }
        $commislevel['rate_supplier_level'] = $rate_supplier_level;
        $commislevel['rate_trader_level'] = $rate_trader_level;
        
        Tpl::output('level_rate',$commislevel);
        Tpl::output('partner_level',$gl_partner_level);
		Tpl::output('top_link',$this->sublink($this->links,'commislevel'));
		Tpl::setDirquna('extension');
        Tpl::showpage('cityunion_config.commislevel');
    } 
	/**
	 * 佣金分成保存
	 *
	 * @param 
	 * @return 
	 */
	public function commislevel_saveOp() {
		$model_rate	= Model('cityunion_commis_rate');
		
		$data=array();
		$data['rate_manage']		=	$_POST['rate_manage']?$_POST['rate_manage']:0;
		$data['rate_supplier']		=	$_POST['rate_supplier']?$_POST['rate_supplier']:0;//供应商总返佣额
		$data['rate_supplier_level']=	serialize($_POST['rate_supplier_level']);//邀请供应商
		$data['rate_trader']		=	$_POST['rate_trader']?$_POST['rate_trader']:0;//零售商返利
		$data['rate_trader_level']	=	serialize($_POST['rate_trader_level']); //邀请零售商
		if($_POST['mcr_id'] != '') {
			$where=array();
			$where['mcr_id']=intval($_POST['mcr_id']);
			$state = $model_rate->where($where)->update($data);
		} else {
			$data['store_id']=GENERAL_PLATFORM_EXTENSION_ID;
			$state = $model_rate->add($data);
		}
		
		if($state) {
			showDialog('配置成功',urlAdminExtension('cityunion_config', 'commislevel'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		} else {
			showDialog('配置失败');
		}
	}
	
}