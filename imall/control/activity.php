<?php
/**
 * 活动
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

class activityControl extends BaseActivityControl {
	
	public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','activity');
    }
	/**
	 * 单个活动信息页activity_id
	 */
	public function indexOp(){
		//查询活动信息
		$activity_id = intval($_GET['activity_id']);
		if($activity_id<=0){
			$this->activity_listOp();
		}else{
			$this->activity_detailOp($activity_id);
		}		
	}
	
	public function activity_listOp(){		
		$model_activity = Model('activity');
		//即将开始的活动
		$page	= new Page();
		$page->setStyle('admin');
		$page->setEachNum(2);		
		
		$where = array();
		$where['be_to_on'] = true;
		$be_activity_list=$model_activity->getList($where,$page);
		Tpl::output('be_activity_list',$be_activity_list);
		//正在进行的活动	
		$page->setEachNum(8);
			
		$where = array();
		$where['opening'] = true;
		$activity_list=$model_activity->getList($where,$page);
		if (!empty($activity_list) && is_array($activity_list)){
		  $goods_model = Model('activity_detail');
	      foreach ($activity_list as $k => $v){
			$activity_id = $v['activity_id'];
		    $goods = $goods_model->getGoodsList(array('order'=>'activity_detail.activity_detail_sort asc','activity_id'=>"$activity_id",'goods_show'=>'1','activity_detail_state'=>'1'),2);
			array_splice($goods, 2);
			$activity_list[$k]['goods']=$goods;
	      }
        }
		
		Tpl::output('activity_list',$activity_list);	
		Tpl::output('show_page', $page->show());
		//已经结束的活动
		$page->setEachNum(4);
		
		$where = array();
		$where['activity_enddate_greater'] = time();
		$close_activity_list=$model_activity->getList($where,$page);
		Tpl::output('close_activity_list',$close_activity_list);
		
		Tpl::showpage('activity_index');		
	}
	/**
	 * 添加点赞
	 *
	 * @param
	 * @return
	 */
	public function addactivityfollowOp(){
		$activity_id = intval($_GET['bid']);
		if ($activity_id <= 0){
			echo json_encode(array('done'=>false,'msg'=>'非法操作'));
			die;
		}
		$activity_model = Model('activity');
		
		$result = $activity_model->add_follows($activity_id);
		if ($result){
			echo json_encode(array('done'=>true,'msg'=>'点赞成功'));
			die;
		}else{
			echo json_encode(array('done'=>false,'msg'=>'点赞失败'));
			die;
		}
	}
	
	public function activity_detailOp($activity_id){
		//读取语言包
		Language::read('home_activity_index');
		//查询活动信息
		$activity	= Model('activity')->getOneById($activity_id);
		if(empty($activity) || $activity['activity_type'] != '1' || $activity['activity_state'] != 1){
			showMessage(Language::get('activity_index_activity_not_exists'),'index.php','html','error');//'指定活动并不存在'
		}		
		Tpl::output('activity',$activity);
		if (!($activity['activity_start_date']>time() || $activity['activity_end_date']<time())){		
		  //查询活动内容信息
		  $page	= new Page();
		  $page->setStyle('admin');
		  $page->setEachNum(20);
		
		  $list	= array();
		  $list	= Model('activity_detail')->getGoodsList(array('order'=>'activity_detail.activity_detail_sort asc','activity_id'=>"$activity_id",'goods_show'=>'1','activity_detail_state'=>'1'),$page);		
		  Tpl::output('list',$list);
		  Tpl::output('show_page', $page->show());	
		  // 浏览数加1
          Model('activity')->add_views($activity_id);
		}
		Tpl::output('html_title',C('site_name').' - '.$activity['activity_title']);
		Tpl::showpage('activity_show');
	}	
	
	public function xianshi_listOp(){
		
		$model_xianshi_goods = Model('p_xianshi_goods');
		//即将开始的限时折扣
		$condition = array();
        $condition['state'] = 1;
        $condition['start_time'] = array('gt', TIMESTAMP);
        
        $be_xianshi_list = $model_xianshi_goods->getXianshiGoodsList($condition,4,'start_time asc','*');
		Tpl::output('be_xianshi_list', $be_xianshi_list);
		
		//正在进行的限时折扣
		$condition = array();
        $condition['state'] = 1;
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        
        $xianshi_list = $model_xianshi_goods->getXianshiGoodsList($condition,16,'end_time asc','*');
		Tpl::output('xianshi_list', $xianshi_list);
		
		//已经结束的限时折扣
		$condition = array();
        $condition['state'] = 1;
        $condition['end_time'] = array('lt', TIMESTAMP);
        
        $ov_xianshi_list = $model_xianshi_goods->getXianshiGoodsList($condition,4,'end_time desc','*');
		Tpl::output('ov_xianshi_list', $ov_xianshi_list);
		
		Tpl::output('html_title',C('site_name').' - 限时折扣');
		Tpl::showpage('activity_xianshi');		
	}
}
