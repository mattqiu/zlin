<?php
/**
 * 用户中心抽佣统计
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

class statistics_commisControl extends BaseExtensionControl {
	public function __construct() {
		parent::__construct();
		Language::read('member_store_statistics');
	}
	
	public function commis_statisticsOp() {
		self::statistics('店铺','','commis_statistics');
	}
	public function promotion_statisticsOp() {
		self::statistics('推广员','2','promotion_statistics');
	}
	public function saleman_statisticsOp() {
		self::statistics('导购员','1','saleman_statistics');
	}	

	/**
	 * 抽佣统计
	 *
	 * @param 
	 * @return 
	 */
	public function statistics($title='',$type='',$menukey='') {
		if($_GET['type'] == 'today'){	
			$main_title = '今日'.$title.'抽佣排名';
			$sub_title  = date('Y-m-d',time());
		}elseif ($_GET['type'] == 'month'){
			$year  = date('Y',time());
			$month = date('m',time());
			$day31 = array('01','03','05','07','08','10','12');
			if(in_array($month, $day31)){
				$daynum = 31;
			}else{
				if($month == '02'){
					//二月判断是否是闰月
					if ($year%4==0 && ($year%100!=0 || $year%400==0)){
						$daynum = 29;
					}else{
						$daynum = 28;
					}
				}else{
					$daynum = 30;
				}
			}
			$main_title = intval($month).'月份'.$title.'抽佣排名';
			$sub_title  = $year.'.'.$month.'.01-'.$year.'.'.$month.'.'.$daynum;
		}elseif ($_GET['type'] == 'year'){
			$year = date('Y',time());
			$main_title = $year.'年度'.$title.'抽佣排名';
			$sub_title  = $year.'.01-'.$year.'.12';
		}elseif($_GET['type'] == 'week'){
			//默认显示本周统计
			$day = date('l',time());
			switch ($day){
				case 'Monday':
					$sub_title = date('Y.m.d',time()).'-'.date('Y.m.d',time()+86400*6);
					break;
				case 'Tuesday':
					$sub_title = date('Y.m.d',time()-86400).'-'.date('Y.m.d',time()+86400*5);
					break;
				case 'Wednesday':
					$sub_title = date('Y.m.d',time()-86400*2).'-'.date('Y.m.d',time()+86400*4);
					break;
				case 'Thursday':
					$sub_title = date('Y.m.d',time()-86400*3).'-'.date('Y.m.d',time()+86400*3);
					break;
				case 'Friday':
					$sub_title = date('Y.m.d',time()-86400*4).'-'.date('Y.m.d',time()+86400*2);
					break;
				case 'Saturday':
					$sub_title = date('Y.m.d',time()-86400*5).'-'.date('Y.m.d',time()+86400);
					break;
				case 'Sunday':
					$sub_title = date('Y.m.d',time()-86400*6).'-'.date('Y.m.d',time());
					break;
			}
			$main_title = '本周'.$title.'抽佣排名';
		}else{				
			if($_GET['add_time_from'] != '' && $_GET['add_time_to'] != ''){
				$from = $_GET['add_time_from'];
				$to = $_GET['add_time_to'];
				$main_title = $title.'抽佣排名搜索结果';
				$sub_title  = substr($from,0,4).'.'.substr($from,4,2).'.'.substr($from,6,2).'-'.substr($to,0,4).'.'.substr($to,4,2).'.'.substr($to,6,2);
			}else{
				$main_title = $title.'抽佣排名搜索结果';
				$sub_title  = '';				
			}
		}
		
		$request_date_str = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to']);
		
		$give_status = 0;
		if (!empty($_GET['give_status']) && $_GET['give_status']<2){
			$give_status = $_GET['give_status'];
		}
		$total_field = 'mb_commis_totals';
		
		$flow_tablename = 'extension_commis_detail';
		$model = Model();
		$table = $flow_tablename.',member';
		$field = 'sum('.$flow_tablename.'.'.$total_field.') as sum,'.$flow_tablename.'.saleman_id,member.member_name,member.member_truename';
		$where = $flow_tablename.".store_id = '".$_SESSION['store_id']."' and ".$flow_tablename.".give_status = ".$give_status." and ".$flow_tablename.".saleman_type < 3 ";
		if ($request_date_str !=''){
		    $where .= " and ".$flow_tablename.".add_date in (".$request_date_str.")";
		}
		if ($type!=''){
			$where .= " and ".$flow_tablename.".saleman_type =".$type;
		}			
		if($_GET['saleman_name'] != ''){
			$where .= " and (".$flow_tablename.".saleman_id like '%".$_GET['saleman_name']."%' or ".$flow_tablename.".saleman_name like '%".$_GET['saleman_name']."%')";
		}
		$group = $flow_tablename.'.saleman_id';
		$on    = 'member.member_id='.$flow_tablename.'.saleman_id';
		$order = 'sum desc';
		$page = '10';
		$flow_list = $model->table($table)->field($field)->join('left')->on($on)->where($where)->group($group)->page($page)->order($order)->select();		

		//模版输出
		Tpl::output('flow_list',$flow_list);
		Tpl::output('show_page',$model->showpage());
		
		Tpl::output('main_title',$main_title);
		Tpl::output('sub_title',$sub_title);
		
		Tpl::output('op_key',$menukey);
		
		self::profile_menu($menukey);
		Tpl::showpage('commis_statistics');
	}

	
	public function commis_paihangOp() {
		self::paihang('店铺','','commis_paihang');
	}
	public function promotion_paihangOp() {
		self::paihang('推广员','2','promotion_paihang');
	}
	public function saleman_paihangOp() {
		self::paihang('导购员','1','saleman_paihang');
	}		
	
	/**
	 * 抽佣排名
	 *
	 * @param 
	 * @return 
	 */
	public function paihang($title='',$type='',$menukey='') {
		if($_GET['type'] == 'today'){	
			$main_title = '今日'.$title.'抽佣排名';
			$sub_title  = date('Y-m-d',time());
		}elseif ($_GET['type'] == 'month'){
			$year  = date('Y',time());
			$month = date('m',time());
			$day31 = array('01','03','05','07','08','10','12');
			if(in_array($month, $day31)){
				$daynum = 31;
			}else{
				if($month == '02'){
					//二月判断是否是闰月
					if ($year%4==0 && ($year%100!=0 || $year%400==0)){
						$daynum = 29;
					}else{
						$daynum = 28;
					}
				}else{
					$daynum = 30;
				}
			}
			$main_title = intval($month).'月份'.$title.'抽佣排名';
			$sub_title  = $year.'.'.$month.'.01-'.$year.'.'.$month.'.'.$daynum;
		}elseif ($_GET['type'] == 'year'){
			$year = date('Y',time());
			$main_title = $year.'年度'.$title.'抽佣排名';
			$sub_title  = $year.'.01-'.$year.'.12';
		}elseif($_GET['type'] == 'week'){
			//默认显示本周商品流量排名
			$day = date('l',time());
			switch ($day){
				case 'Monday':
					$sub_title = date('Y.m.d',time()).'-'.date('Y.m.d',time()+86400*6);
					break;
				case 'Tuesday':
					$sub_title = date('Y.m.d',time()-86400).'-'.date('Y.m.d',time()+86400*5);
					break;
				case 'Wednesday':
					$sub_title = date('Y.m.d',time()-86400*2).'-'.date('Y.m.d',time()+86400*4);
					break;
				case 'Thursday':
					$sub_title = date('Y.m.d',time()-86400*3).'-'.date('Y.m.d',time()+86400*3);
					break;
				case 'Friday':
					$sub_title = date('Y.m.d',time()-86400*4).'-'.date('Y.m.d',time()+86400*2);
					break;
				case 'Saturday':
					$sub_title = date('Y.m.d',time()-86400*5).'-'.date('Y.m.d',time()+86400);
					break;
				case 'Sunday':
					$sub_title = date('Y.m.d',time()-86400*6).'-'.date('Y.m.d',time());
					break;
			}
			$main_title = '本周'.$title.'抽佣排名';
		}else{				
			if($_GET['add_time_from'] != '' && $_GET['add_time_to'] != ''){		
				$from = $_GET['add_time_from'];
				$to = $_GET['add_time_to'];
				$main_title = $title.'抽佣排名搜索结果';
				$sub_title  = substr($from,0,4).'.'.substr($from,4,2).'.'.substr($from,6,2).'-'.substr($to,0,4).'.'.substr($to,4,2).'.'.substr($to,6,2);
			}else{
				$main_title = $title.'抽佣排名搜索结果';
				$sub_title  = '';				
			}
		}
		
		$request_date_str = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to']);
		
		$give_status = 0;
		if (!empty($_GET['give_status']) && $_GET['give_status']<2){
			$give_status = $_GET['give_status'];
		}
		$total_field = 'mb_commis_totals';
		
		$flow_tablename = 'extension_commis_detail';
		$model = Model();
		$table = $flow_tablename.',member';
		$field = 'sum('.$flow_tablename.'.'.$total_field.') as sum,'.$flow_tablename.'.saleman_id,member.member_name,member.member_truename';
		$where = $flow_tablename.".store_id = '".$_SESSION['store_id']."' and ".$flow_tablename.".give_status = ".$give_status." and ".$flow_tablename.".saleman_type < 3 ";
		if ($request_date_str !=''){
		    $where .= " and ".$flow_tablename.".add_date in (".$request_date_str.")";
		}
		if ($type!=''){
			$where .= " and ".$flow_tablename.".saleman_type =".$type;
		}
		if($_GET['saleman_name'] != ''){
			$where .= " and (".$flow_tablename.".saleman_id like '%".$_GET['saleman_name']."%' or ".$flow_tablename.".saleman_name like '%".$_GET['saleman_name']."%')";
		}
		
		$group = $flow_tablename.'.saleman_id';
		$on    = 'member.member_id='.$flow_tablename.'.saleman_id';
		$order = 'sum desc';
		$limit = '10';
		$flow_array = $model->table($table)->field($field)->join('left')->on($on)->where($where)->group($group)->limit($limit)->order($order)->select();
		//处理数组信息
		$result_saleman_name_str = '';
		$result_saleman_truename_str = '';
		$result_commisnum_str = '';
		if(!empty($flow_array)){
			foreach ($flow_array as $k=>$v){
				//还原被转换的双引号，加斜线防止中间有单引号使页面的JS数组出错
				$result_saleman_name_str .= "'".addslashes(html_entity_decode($v['member_name'])).'('.addslashes(html_entity_decode($v['member_truename'])).")',";
				//$result_saleman_truename_str .= "'".addslashes(html_entity_decode($v['member_truename']))."',";//还原被转换的双引号，加斜线防止中间有单引号使页面的JS数组出错
				$result_commisnum_str .= $v['sum'].',';
			}
		}
		$result_saleman_name_str = trim($result_saleman_name_str,',');
		$result_saleman_truename_str = trim($result_saleman_truename_str,',');
		$result_commisnum_str = trim($result_commisnum_str,',');

		//模版输出
		Tpl::output('result_saleman_name_str',$result_saleman_name_str);
		Tpl::output('result_saleman_truename_str',$result_saleman_truename_str);
		Tpl::output('result_commisnum_str',$result_commisnum_str);
		
		Tpl::output('main_title',$main_title);
		Tpl::output('sub_title',$sub_title);
		
		Tpl::output('op_key',$menukey);
		
		self::profile_menu($menukey);
		Tpl::showpage('commis_paihang');
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
			1=>array('menu_key'=>'commis_statistics','menu_name'=>'抽佣统计',	'menu_url'=>'index.php?act=statistics_commis&op=commis_statistics'),
			2=>array('menu_key'=>'commis_paihang','menu_name'=>'抽佣排行',	'menu_url'=>'index.php?act=statistics_commis&op=commis_paihang'),
            3=>array('menu_key'=>'promotion_statistics','menu_name'=>'推广员抽佣',	'menu_url'=>'index.php?act=statistics_commis&op=promotion_statistics'),
			4=>array('menu_key'=>'promotion_paihang','menu_name'=>'推广员抽佣排行',	'menu_url'=>'index.php?act=statistics_commis&op=promotion_paihang'),
            5=>array('menu_key'=>'saleman_statistics','menu_name'=>'导购员抽佣',	'menu_url'=>'index.php?act=statistics_commis&op=saleman_statistics'),
			6=>array('menu_key'=>'saleman_paihang','menu_name'=>'导购员抽佣排行',	'menu_url'=>'index.php?act=statistics_commis&op=saleman_paihang'),
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
