<?php
/**
 * 店铺推广配置
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class seller_extensionControl extends BaseExtensionControl {

    public function __construct() {
    	parent::__construct() ;
    	Language::read('member_layout');
    }
	
	
	/**
	 * 推广配置
	 *
	 */
    public function configOP() {		
		Tpl::output('promotion_open',$this->store_info['promotion_open']);
		Tpl::output('promotion_level_op',$this->store_info['promotion_level_op']);
		Tpl::output('promotion_level',$this->store_info['promotion_level']);
		Tpl::output('promotion_require',$this->store_info['promotion_require']);
		Tpl::output('saleman_open',$this->store_info['saleman_open']);
		Tpl::output('saleman_require',$this->store_info['saleman_require']);
		Tpl::output('extension_adv',$this->store_info['extension_adv']);		

		self::profile_menu('config');
        Tpl::showpage('seller_extension.config');
    } 
	
	public function config_saveOP() {	
	    if (chksubmit()){	
		    $model_store = Model('store');
			
			$param = array(
                'promotion_open' => $_POST['promotion_open'],
				'promotion_require' => $_POST['promotion_require'],
                'saleman_open' => $_POST['saleman_open'],
				'saleman_require' => $_POST['saleman_require'],
				'extension_adv' => $_POST['extension_adv']
            );
			if ($this->store_info['promotion_level_op']==1){
				$param['promotion_level'] = $_POST['promotion_level'];
			}
			$model_store->editStore($param, array('store_id' => $_SESSION['store_id']));			
		}
	    showDialog(Language::get('im_common_save_succ'),'index.php?act=seller_extension&op=config','succ');        
    } 
	
	/**
	 * 管理服务奖励方案
	 *
	 */
    public function manageOp() {
        $model_manage = Model('extension_manageaward');
		$manage_list = $model_manage->getExtensionManageAwardListByStoreID($_SESSION['store_id']);		
		Tpl::output('manage_list',$manage_list);
		
		self::profile_menu('manage');
        Tpl::showpage('seller_extension.manage');
    }    

	/**
	 * 编辑管理服务奖励方案
	 */
	public function manage_editOp(){
		$model_manage = Model('extension_manageaward');

		$em_id = intval($_GET["em_id"]);
		$manage_info = $model_manage->getExtensionManageAwardByID($em_id);		
		Tpl::output('manage_info',$manage_info);
		
		self::profile_menu('manage');
		Tpl::showpage('seller_extension.manage.edit','null_layout');
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
		$data['store_id']    =$_SESSION['store_id'];
		$data['award_name']  =$_POST['award_name'];
		$data['mc_id']       =$_POST['mc_id'];
		$data['sub_nums']    =$_POST['sub_nums'];
		$data['child_nums']  =$_POST['child_nums'];	
		$data['order_nums']  = $_POST['order_nums'];	
		$data['achieve_val'] =$_POST['achieve_val'];
		$data['award_rate']=$_POST['award_rate'];
		$data['award_level'] =$_POST['award_level'];
		
		if($_POST['em_id'] != '') {
			$where=array();
			$where['em_id']=intval($_POST['em_id']);
			$state = $model_manage->editExtensionManageAward($data, $where);
			if($state) {
				showDialog('修改成功',urlShop('seller_extension', 'manage'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		} else {
			$state = $model_manage->addExtensionManageAward($data);
			if($state) {
				showDialog('添加成功',urlShop('seller_extension', 'manage'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
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
				showDialog('删除成功',urlShop('seller_extension', 'manage'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlShop('seller_extension', 'manage'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}	
	
	/**
	 * 绩优奖励方案
	 *
	 */
    public function perforOp() {
        $model_perfor = Model('extension_perforaward');
		$perfor_list = $model_perfor->getExtensionPerforAwardListByStoreID($_SESSION['store_id']);		
		Tpl::output('perfor_list',$perfor_list);
		
		self::profile_menu('perfor');
        Tpl::showpage('seller_extension.perfor');
    }    

	/**
	 * 编辑绩优奖励方案
	 */
	public function perfor_editOp(){
		$model_perfor = Model('extension_perforaward');

		$ep_id = intval($_GET["ep_id"]);
		$perfor_info = $model_perfor->getExtensionPerforAwardByID($ep_id);		
		Tpl::output('perfor_info',$perfor_info);
		if ($this->store_info['promotion_level'] >0 && $this->store_info['promotion_level']<=8){
		  $promotion_level = $this->store_info['promotion_level'];
		}else{
		  $promotion_level = 3;
		}
		Tpl::output('promotion_level',$promotion_level);
		
		self::profile_menu('perfor');
		Tpl::showpage('seller_extension.perfor.edit','null_layout');
	}
	/**
	 * 保存绩优奖励方案
	 *
	 * @param 
	 * @return 
	 */
	public function perfor_saveOp() {
		$model_perfor	= Model('extension_perforaward');
		
		if ($this->store_info['promotion_level'] >0 && $this->store_info['promotion_level']<=8){
		  $promotion_level = $this->store_info['promotion_level'];
		}else{
		  $promotion_level = 3;
		}
		
		$data=array();
		$data['store_id']    =$_SESSION['store_id'];
		$data['award_name']  =$_POST['award_name'];
		$data['mc_id']       = $_POST['mc_id'];
		$data['achieve_val'] =$_POST['achieve_val'];
		$data['award_rate']=$_POST['award_rate'];
		$data['award_level'] =$promotion_level;//$_POST['award_level'];
		
		if($_POST['ep_id'] != '') {
			$where=array();
			$where['ep_id']=intval($_POST['ep_id']);
			$state = $model_perfor->editExtensionPerforAward($data, $where);
			if($state) {
				showDialog('修改成功',urlShop('seller_extension', 'perfor'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		} else {
			$state = $model_perfor->addExtensionPerforAward($data);
			if($state) {
				showDialog('添加成功',urlShop('seller_extension', 'perfor'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
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
				showDialog('删除成功',urlShop('seller_extension', 'perfor'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlShop('seller_extension', 'perfor'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
	
	/**
	 * 佣金分成配置
	 *
	 */
    public function commislevelOP() {		
        $model_level = Model('extension_commis_rate');
		$level_rate = $model_level->getCommisRateInfo($_SESSION['store_id']);		
		Tpl::output('level_rate',$level_rate);
		if ($this->store_info['promotion_level'] >0 && $this->store_info['promotion_level']<=8){
		  $promotion_level = $this->store_info['promotion_level'];
		}else{
		  $promotion_level = 3;
		}
		Tpl::output('promotion_level',$promotion_level);
		
		self::profile_menu('commislevel');
        Tpl::showpage('seller_extension.commislevel');
    } 
	/**
	 * 佣金分成保存
	 *
	 * @param 
	 * @return 
	 */
	public function commislevel_saveOP() {
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
			$data['store_id']=$_SESSION['store_id'];
			$state = $model_rate->add($data);			
		}
		if($state) {
			showDialog('配置成功',urlShop('seller_extension', 'commislevel'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
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
		$commis_list = $model_commis->getCommisList($_SESSION['store_id']);		
		Tpl::output('commisclass_list',$commis_list);
		
		self::profile_menu('commisclass');
        Tpl::showpage('seller_extension.commisclass');
    }    

	/**
	 * 编辑佣金模板
	 */
	public function commisclass_editOp(){
		$model_commis = Model('extension_commis_class');

		$commis_id = intval($_GET["commis_id"]);
		$commisclass = $model_commis->getCommisInfo($_SESSION['store_id'],array('commis_id'=>$commis_id));		
		Tpl::output('commis_info',$commisclass);
		
		self::profile_menu('commisclass');
		Tpl::showpage('seller_extension.commisclass.edit','null_layout');
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
		$data['commis_name']=$_POST['commis_name'];
		$data['commis_class']=$_POST['commis_class'];
		$data['commis_rate']=$_POST['commis_rate'];
		$data['store_id']=$_SESSION['store_id'];
		$data['commis_mode'] = $_POST['commis_mode'];
		$data['is_default']  = $_POST['is_default'];
		
		if($_POST['commis_id'] != '') {
			$where=array();
			$where['commis_id']=intval($_POST['commis_id']);
			$state = $model_class->where($where)->update($data);
			if($state) {
				showDialog('修改成功',urlShop('seller_extension', 'commisclass'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		} else {
			$state = $model_class->add($data);
			if($state) {
				showDialog('添加成功',urlShop('seller_extension', 'commisclass'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
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
				showDialog('删除成功',urlShop('seller_extension', 'commisclass'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlShop('seller_extension', 'commisclass'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
	
	
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_key='') {
        $menu_array	= array(
			1=>array('menu_key'=>'config','menu_name'=>'推广配置',	'menu_url'=>'index.php?act=seller_extension&op=config'),
			2=>array('menu_key'=>'manage','menu_name'=>'高管奖励分配方案',	'menu_url'=>'index.php?act=seller_extension&op=manage'),
			3=>array('menu_key'=>'perfor','menu_name'=>'门店补贴分配方案',	'menu_url'=>'index.php?act=seller_extension&op=perfor'),
			4=>array('menu_key'=>'commislevel','menu_name'=>'推广佣金分配',	'menu_url'=>'index.php?act=seller_extension&op=commislevel'),
            5=>array('menu_key'=>'commisclass','menu_name'=>'商品佣金模板',	'menu_url'=>'index.php?act=seller_extension&op=commisclass'),
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}