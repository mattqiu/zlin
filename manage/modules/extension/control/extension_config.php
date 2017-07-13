<?php
/**
 * 全网推广配置
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class extension_configControl extends BaseExtensionControl {
	private $links = array(
        array('url'=>'act=extension_config&op=config','lang'=>'extension_config'), //平台推广配置
        array('url'=>'act=extension_config&op=manage','lang'=>'extension_manage'), //高管管理补贴
        array('url'=>'act=extension_config&op=perfor','lang'=>'extension_perfor'), //门店租金补贴
		array('url'=>'act=extension_config&op=commislevel','lang'=>'extension_commislevel'),//推广佣金分配
		array('url'=>'act=extension_config&op=commisclass','lang'=>'extension_commisclass'),//推广佣金模板
		//array('url'=>'act=extension_config&op=priceclass','lang'=>"extension_priceclass"),//平台商品定价模版
		//array('url'=>'act=extension_config&op=membergrade','lang'=>'member_grade'), //会员等级制度
		array('url'=>'act=extension_config&op=saleman','lang'=>'extension_saleman'), //导购服务补贴
    );

    public function __construct() {
    	parent::__construct() ;
    	Language::read('extension');
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
		
		$extension_setting = $model_setting->getListSetting();
		
		$gl_promotion_reg = $extension_setting['gl_promotion_reg'];
		$gl_promotion_level = $extension_setting['gl_promotion_level'];
		$gl_promotion_require = $extension_setting['gl_promotion_require'];		
				
		Tpl::output('gl_promotion_reg',$gl_promotion_reg);
		Tpl::output('gl_promotion_level',$gl_promotion_level);
		Tpl::output('gl_promotion_require',$gl_promotion_require);

        Tpl::output('top_link',$this->sublink($this->links,'config'));
        Tpl::setDirquna('extension');
        Tpl::showpage('extension_config.config');
    } 
	
	public function config_saveOp() {	
	    if (chksubmit()){
			$model_setting = Model('setting');
			
			$data = array();
			$data['gl_promotion_reg'] = $_POST['gl_promotion_reg'];
			$data['gl_promotion_level'] = $_POST['gl_promotion_level'];
			$data['gl_promotion_require'] = $_POST['gl_promotion_require'];
			
			$result = $model_setting->updateSetting($data);
			if ($result === true){
				showMessage(Language::get('im_common_save_succ'));
			}else {
				showMessage(Language::get('im_common_save_fail'));
			}	
		}else{
			showMessage('非法操作');
		}
    } 
	
	/**
	 * 管理服务奖励方案
	 *
	 */
    public function manageOp() {
        $model_manage = Model('extension_manageaward');
		$manage_list = $model_manage->getExtensionManageAwardListByStoreID(GENERAL_PLATFORM_EXTENSION_ID);		
		Tpl::output('manage_list',$manage_list);
		
		Tpl::output('top_link',$this->sublink($this->links,'manage'));
		Tpl::setDirquna('extension');
        Tpl::showpage('extension_config.manage');
    }    

	/**
	 * 编辑管理服务奖励方案
	 */
	public function manage_editOp(){
		$model_manage = Model('extension_manageaward');

		$em_id = intval($_GET["em_id"]);
		$manage_info = $model_manage->getExtensionManageAwardByID($em_id);		
		Tpl::output('manage_info',$manage_info);
		
		Tpl::setDirquna('extension');
		Tpl::showpage('extension_config.manage.edit','null_layout');
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
		$data['award_rate']= $_POST['award_rate'];
		$data['award_level'] = $_POST['award_level'];
		$data['points_rate']= $_POST['points_rate'];
		
		if($_POST['em_id'] != '') {
			$where=array();
			$where['em_id']=intval($_POST['em_id']);
			$state = $model_manage->editExtensionManageAward($data, $where);
			if($state) {
				showDialog('修改成功',urlAdminExtension('extension_config', 'manage'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		} else {
			$state = $model_manage->addExtensionManageAward($data);
			if($state) {
				showDialog('添加成功',urlAdminExtension('extension_config', 'manage'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
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
				showDialog('删除成功',urlAdminExtension('extension_config', 'manage'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlAdminExtension('extension_config', 'manage'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
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
        Tpl::showpage('extension_config.perfor');
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
		Tpl::showpage('extension_config.perfor.edit','null_layout');
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
				showDialog('修改成功',urlAdminExtension('extension_config', 'perfor'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		} else {
			$state = $model_perfor->addExtensionPerforAward($data);
			if($state) {
				showDialog('添加成功',urlAdminExtension('extension_config', 'perfor'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
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
				showDialog('删除成功',urlAdminExtension('extension_config', 'perfor'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlAdminExtension('extension_config', 'perfor'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
	
	/**
	 * 佣金分成配置
	 *
	 */
    public function commislevelOp() {		
        $model_level = Model('extension_commis_rate');
		$level_rate = $model_level->getCommisRateInfo(GENERAL_PLATFORM_EXTENSION_ID,array('extend_type'=>0));		
		Tpl::output('level_rate',$level_rate);
		
		if (C('gl_promotion_level') >0 && C('gl_promotion_level')<=8){
		  $promotion_level = C('gl_promotion_level');
		}else{
		  $promotion_level = 3;
		}
		Tpl::output('promotion_level',$promotion_level);
		
		Tpl::output('top_link',$this->sublink($this->links,'commislevel'));
		Tpl::setDirquna('extension');
        Tpl::showpage('extension_config.commislevel');
    } 
	/**
	 * 佣金分成保存
	 *
	 * @param 
	 * @return 
	 */
	public function commislevel_saveOp() {
		$model_rate	= Model('extension_commis_rate');
		
		$data=array();
		$data['rate_manage']=$_POST['rate_manage']?$_POST['rate_manage']:0;
		$data['rate_perfor']=$_POST['rate_perfor']?$_POST['rate_perfor']:0;
		$data['rate_level1']=$_POST['rate_level1']?$_POST['rate_level1']:0;
		$data['rate_level2']=$_POST['rate_level2']?$_POST['rate_level2']:0;
		$data['rate_level3']=$_POST['rate_level3']?$_POST['rate_level3']:0;
		$data['rate_level4']=$_POST['rate_level4']?$_POST['rate_level4']:0;
		$data['rate_level5']=$_POST['rate_level5']?$_POST['rate_level5']:0;
		$data['rate_level6']=$_POST['rate_level6']?$_POST['rate_level6']:0;
		$data['rate_level7']=$_POST['rate_level7']?$_POST['rate_level7']:0;
		$data['rate_level8']=$_POST['rate_level8']?$_POST['rate_level8']:0;		
		
		if($_POST['mcr_id'] != '') {
			$where=array();
			$where['mcr_id']=intval($_POST['mcr_id']);
			$state = $model_rate->where($where)->update($data);
		} else {
			$data['store_id']=GENERAL_PLATFORM_EXTENSION_ID;
			$state = $model_rate->add($data);			
		}
		if($state) {
			showDialog('配置成功',urlAdminExtension('extension_config', 'commislevel'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		} else {
			showDialog('配置失败');
		}
	}
	
	/**
	 * 佣金模板
	 *
	 */
    public function commisclassOp() {
        $model_commis = Model('extension_commis_class');
		$commis_list = $model_commis->getCommisList(GENERAL_PLATFORM_EXTENSION_ID);		
		Tpl::output('commisclass_list',$commis_list);
		
		Tpl::output('top_link',$this->sublink($this->links,'commisclass'));
		Tpl::setDirquna('extension');
        Tpl::showpage('extension_config.commisclass');
    }    

	/**
	 * 编辑佣金模板
	 */
	public function commisclass_editOp(){
		$model_commis = Model('extension_commis_class');

		$commis_id = intval($_GET["commis_id"]);
		$commisclass = $model_commis->getCommisInfo(GENERAL_PLATFORM_EXTENSION_ID,array('commis_id'=>$commis_id));		
		Tpl::output('commis_info',$commisclass);
		
		Tpl::setDirquna('extension');
		Tpl::showpage('extension_config.commisclass.edit','null_layout');
	}
	/**
	 * 保存佣金模板
	 *
	 * @param 
	 * @return 
	 */
	public function commisclass_saveOp() {
		$model_class	= Model('extension_commis_class');
		
		$data=array();
		$data['commis_mode'] = $_POST['commis_mode'];
		$data['store_id']    = GENERAL_PLATFORM_EXTENSION_ID;
		$is_default = $_POST['is_default'];
		if($is_default == 1){
			//如果设置了默认的模板，则需要修改之前的模板
			$model_class->where($data)->update(array('is_default'=>0));
		}
		$data['commis_class']= $_POST['commis_class'];
		$data['commis_rate'] = $_POST['commis_rate'];
		$data['commis_name'] = $_POST['commis_name'];
		$data['is_default']  = $is_default;
		if($_POST['commis_id'] != '') {
			$where=array();
			$where['commis_id']=intval($_POST['commis_id']);
			$state = $model_class->where($where)->update($data);
			if($state) {
				showDialog('修改成功',urlAdminExtension('extension_config', 'commisclass'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		} else {
			$state = $model_class->add($data);
			if($state) {
				showDialog('添加成功',urlAdminExtension('extension_config', 'commisclass'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('添加失败');
			}
		}
	}
	/**
	 * 删除
	 *
	 * @param 
	 * @return 
	 */
	public function commisclass_delOp() {
		$model_class	= Model('extension_commis_class');		
	
		if($_GET['commis_id'] != '') {
			$where=array();
			$where['commis_id']=intval($_GET['commis_id']);
			$state = $model_class->where($where)->delete();
			if($state) {
				showDialog('删除成功',urlAdminExtension('extension_config', 'commisclass'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlAdminExtension('extension_config', 'commisclass'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
	
	/**
	 * 平台定价模板
	 *
	 */
	public function priceclassOp() {
		$model_price = Model('extension_price_class');
		$price_list = $model_price->getPriceList(GENERAL_PLATFORM_EXTENSION_ID);
		Tpl::output('priceclass_list',$price_list);
	
		Tpl::output('top_link',$this->sublink($this->links,'priceclass'));
		Tpl::setDirquna('extension');
		Tpl::showpage('extension_config.priceclass');
	}
	
	/**
	 * 编辑平台定价模板
	 */
	public function priceclass_editOp(){
		$model_price = Model('extension_price_class');
	
		$pid = intval($_GET["pid"]);
		$priceclass = $model_price->getPriceInfo(GENERAL_PLATFORM_EXTENSION_ID,array('pid'=>$pid));
		Tpl::output('price_info',$priceclass);
	
		Tpl::setDirquna('extension');
		Tpl::showpage('extension_config.priceclass.edit','null_layout');
	}
	/**
	 * 保存平台定价模板
	 *
	 * @param
	 * @return
	 */
	public function priceclass_saveOp() {
		$model_class	= Model('extension_price_class');
	
		$data=array();
		$data['pname'] = $_POST['pname'];
		$data['ptype']= $_POST['ptype'];
		$data['profit_rate'] = $_POST['profit_rate'];
		$data['huik_rate'] = $_POST['huik_rate'];
		$data['mall_points'] = $_POST['mall_points'];
		$data['store_subsidy'] = $_POST['store_subsidy'];
		$data['tuig_subsidy'] = $_POST['tuig_subsidy'];
		$data['store_id']    = GENERAL_PLATFORM_EXTENSION_ID;
	
		if($_POST['pid'] != '') {
			$where=array();
			$where['pid']=intval($_POST['pid']);
			$state = $model_class->where($where)->update($data);
			if($state) {
				showDialog('修改成功',urlAdminExtension('extension_config', 'priceclass'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		} else {
			$state = $model_class->add($data);
			if($state) {
				showDialog('添加成功',urlAdminExtension('extension_config', 'priceclass'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('添加失败');
			}
		}
	}
	/**
	 * 删除平台定价模板
	 *
	 * @param
	 * @return
	 */
	public function priceclass_delOp() {
		$model_class	= Model('extension_price_class');
	
		if($_GET['pid'] != '') {
			$where=array();
			$where['pid']=intval($_GET['pid']);
			$state = $model_class->where($where)->delete();
			if($state) {
				showDialog('删除成功',urlAdminExtension('extension_config', 'priceclass'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlAdminExtension('extension_config', 'priceclass'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
	
	/**
	 * 导购服务奖励方案
	 *
	 */
	public function salemanOp() {
		$model_saleman = Model('extension_salemanaward');
		$saleman_list = $model_saleman->getExtensionSalemanAwardListByStoreID(GENERAL_PLATFORM_EXTENSION_ID);
		Tpl::output('saleman_list',$saleman_list);
	
		Tpl::output('top_link',$this->sublink($this->links,'saleman'));
		Tpl::setDirquna('extension');
		Tpl::showpage('extension_config.saleman');
	}
	
	/**
	 * 编辑导购服务奖励方案
	 */
	public function saleman_editOp(){
		$model_saleman = Model('extension_salemanaward');
	
		$sm_id = intval($_GET["sm_id"]);
		$saleman_info = $model_saleman->getExtensionSalemanAwardByID($sm_id);
		Tpl::output('saleman_info',$saleman_info);
	
		Tpl::setDirquna('extension');
		Tpl::showpage('extension_config.saleman.edit','null_layout');
	}
	/**
	 * 保存导购服务奖励方案
	 *
	 * @param
	 * @return
	 */
	public function saleman_saveOp() {
		$model_saleman	= Model('extension_salemanaward');
		
		$data=array();
		$data['store_id']    = GENERAL_PLATFORM_EXTENSION_ID;
		$data['award_name']  = $_POST['award_name'];
		$data['mc_id']       = $_POST['mc_id'];
		$data['serve_nums']  = $_POST['serve_nums'];
		$data['order_nums']  = $_POST['order_nums'];
		$data['achieve_val'] = $_POST['achieve_val'];
		$data['award_rate']	 = $_POST['award_rate'];
		$data['base_salary'] = $_POST['base_salary'];//保底薪资
		
		if($_POST['sm_id'] != '') {
			$where=array();
			$where['sm_id']=intval($_POST['sm_id']);
			$state = $model_saleman->editExtensionSalemanAward($data, $where);
			if($state) {
				showDialog('修改成功',urlAdminExtension('extension_config', 'saleman'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		} else {
			$state = $model_saleman->addExtensionSalemanAward($data);
			if($state) {
				showDialog('添加成功',urlAdminExtension('extension_config', 'saleman'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('添加失败');
			}
		}
	}
		
	/**
	 * 删除导购服务奖励方案
	 *
	 * @param
	 * @return
	 */
	public function saleman_delOp() {
		$model_saleman	= Model('extension_salemanaward');	
		if($_GET['sm_id'] != '') {
			$where=array();
			$where['sm_id']=intval($_GET['sm_id']);
			$state = $model_saleman->delExtensionSalemanAward($where);
			if($state) {
				showDialog('删除成功',urlAdminExtension('extension_config', 'saleman'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlAdminExtension('extension_config', 'saleman'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
	
}