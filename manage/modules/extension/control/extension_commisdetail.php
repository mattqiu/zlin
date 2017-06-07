<?php
/**
 * 推广员、导购员抽佣明细
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class extension_commisdetailControl extends BaseExtensionControl {
	private $links = array(
        array('url'=>'act=extension_commisdetail&op=index','lang'=>'commis_detail'),
    );

    public function __construct() {
    	parent::__construct() ;
    	Language::read('member_layout,extension');
    }	
	/**
	 * 抽佣明细
	 *
	 */
    public function indexOP() {
		$model_detail = Model('extension_commis_detail');		
		
		$request_date_str = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to']);
		
		$condition = array();
		$condition['store_id'] = GENERAL_PLATFORM_EXTENSION_ID;		
		if ($request_date_str != ''){			
			$args = explode(',',$request_date_str);
			$condition['add_date'] = array('in',$args);
		}
		if($_GET['saleman_type'] == 0){			
		  $condition['saleman_type'] = array(array('eq',1),array('eq',2),'or');
		}else if($_GET['saleman_type'] == 1){
			$condition['saleman_type'] = 1;
		}else{
			$condition['saleman_type'] = 2;
		}
		if (isset($_GET['give_status']) && $_GET['give_status']<2){			
			$condition['give_status'] = $_GET['give_status'];
		}
		
		if($_GET['saleman_name'] != ''){
			$condition['saleman_name'] = array('like', '%' . $_GET['saleman_name'] . '%');
		}
		
		$detail_list = $model_detail->getCommisdetailList($condition,20);		
		Tpl::output('detail_list',$detail_list);
		Tpl::output('show_page',$model_detail->showpage());
		
		Tpl::output('top_link',$this->sublink($this->links,'index'));
		Tpl::setDirquna('extension');
        Tpl::showpage('extension_commisdetail.index');
    }    

	/**
	 * 编辑
	 */
	public function commisdetail_editOp(){
		$model_detail = Model('extension_commis_detail');

		$mcd_id = intval($_GET["mcd_id"]);
		$commisdetail = $model_detail->getCommisdetailInfo(GENERAL_PLATFORM_EXTENSION_ID,array('mcd_id'=>$mcd_id));	
		Tpl::output('commis_detail',$commisdetail);
		
		Tpl::setDirquna('extension');
		Tpl::showpage('extension_commisdetail.edit','null_layout');
	}
	/**
	 * 保存
	 *
	 * @param 
	 * @return 
	 */
	public function commisdetail_saveOp() {
		$model_detail	= Model('extension_commis_detail');
		
		$data=array();
		$data['store_id']=GENERAL_PLATFORM_EXTENSION_ID;
		$data['store_name']=C('site_name');
		$data['saleman_id']=$_POST['saleman_id'];		
		$data['saleman_name']=$_POST['saleman_name'];
		//saleman_parent		
		$data['order_id']=$_POST['order_id'];
		$data['order_sn']=$_POST['order_sn'];
		$data['goods_amount']=$_POST['goods_amount'];
		//commis_amount
		$data['commis_rate']=$_POST['commis_rate'];
		//award_totals
		$data['mb_commis_totals']=$_POST['mb_commis_totals'];
		$data['add_time']=TIMESTAMP;
		//add_date
		//give_status
		//give_time
		
		if($_POST['mcd_id'] != '') {
			$where=array();
			$where['mcd_id']=intval($_POST['mcd_id']);
			$state = $model_detail->where($where)->update($data);
			if($state) {
				showDialog('修改成功',urlAdminExtension('extension_commisdetail', 'index'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		} else {
			$data['saleman_type']=2;//推广员
			
			$state = $model_detail->add($data);
			if($state) {
				showDialog('添加成功',urlAdminExtension('extension_commisdetail', 'index'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
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
	public function commisdetail_delOp() {
		$model_detail	= Model('extension_commis_detail');		
	
		if($_GET['mcd_id'] != '') {
			$where=array();
			$where['mcd_id']=intval($_GET['mcd_id']);
			$state = $model_detail->where($where)->delete();
			if($state) {
				showDialog('删除成功',urlAdminExtension('extension_commisdetail', 'index'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlAdminExtension('extension_commisdetail', 'index'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
}