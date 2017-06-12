<?php
/**
 * 统计概述
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e.com Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class statistics_generalControl extends BaseSellerControl {
    public function __construct(){
        parent::__construct();
        Language::read('member_store_statistics');
        import('function.statistics');
		import('function.datehelper');
    }
	/**
	 * 运营概况
	 */
	public function generalOp(){
		$model = Model('stat');
        //存储参数
		$search_arr = $_REQUEST;
		//处理搜索时间
	    $search_arr = $model->dealwithSearchTime($search_arr);
		//获得系统年份
		$year_arr = getSystemYearArr();
		//获得系统本年季度时间段
		$quarter_arr = getSystemQuarterArr();
		//获得系统月份
		$month_arr = getSystemMonthArr();
		//获得本月的周时间段
		$week_arr = getMonthWeekArr($search_arr['week']['current_year'], $search_arr['week']['current_month']);
		Tpl::output('year_arr', $year_arr);
		Tpl::output('quarter_arr', $quarter_arr);
		Tpl::output('month_arr', $month_arr);
		Tpl::output('week_arr', $week_arr);
        Tpl::output('search_arr', $search_arr);
		if(!$search_arr['search_type']){
			$search_arr['search_type'] = 'day';
		}
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($search_arr);
		$stat_arr = array();
		$queryType = 5;		
		if ($search_arr['search_type']=='day'){
			$queryType = 5;
			//构造横轴数据
	        for($i=1; $i<=24; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = $i.':00';
				$stat_in[$i] = 0; //进店人数
				//$stat_out[$i] = 0; //出店人数
				$stat_amount[$i] = 0; //成交金额
				//$stat_goods[$i] = 0; //成交件数
				//$stat_member[$i] = 0; //成交人数
			}
		}elseif($search_arr['search_type']=='week'){
			$queryType = 4;
			//构造横轴数据
			$tmp_weekarr = getSystemWeekArr();
	        for($i=1; $i<=7; $i++){	            
				//横轴
				$stat_arr['xAxis']['categories'][] = $tmp_weekarr[$i];
				$stat_in[$i] = 0; //进店人数
				//$stat_out[$i] = 0; //出店人数
				$stat_amount[$i] = 0; //成交金额
				//$stat_goods[$i] = 0; //成交件数
				//$stat_member[$i] = 0; //成交人数
			}
			unset($tmp_weekarr);
		}elseif($search_arr['search_type']=='month'){
			$queryType = 4;
			//计算横轴的最大量（由于每个月的天数不同）
			$dayofmonth = date('t',$searchtime_arr[0]);
		    //构造横轴数据
			for($i=1; $i<=$dayofmonth; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = $i;
				$stat_in[$i] = 0; //进店人数
				//$stat_out[$i] = 0; //出店人数
				$stat_amount[$i] = 0; //成交金额
				//$stat_goods[$i] = 0; //成交件数
				//$stat_member[$i] = 0; //成交人数
			}
		}elseif($search_arr['search_type']=='quarter'){
			$queryType = 3;
			//构造横轴数据
			$startmonth = intval(date('m', $searchtime_arr[0]));
			$endmonth = intval(date('m', $searchtime_arr[1]));
			for($i=$startmonth; $i<=$endmonth; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = $i.'月';
				$stat_in[$i] = 0; //进店人数
				//$stat_out[$i] = 0; //出店人数
				$stat_amount[$i] = 0; //成交金额
				//$stat_goods[$i] = 0; //成交件数
				//$stat_member[$i] = 0; //成交人数
			}
		}elseif($search_arr['search_type']=='year'){
			$queryType = 3;
			//构造横轴数据
	        for($i=1; $i<=12; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = $i.'月';
				$stat_in[$i] = 0; //进店人数
				//$stat_out[$i] = 0; //出店人数
				$stat_amount[$i] = 0; //成交金额
				//$stat_goods[$i] = 0; //成交件数
				//$stat_member[$i] = 0; //成交人数
			}
		}		
		$beginDate = date("Y-m-d H:i:s", $searchtime_arr[0]);
		$endDate   = date("Y-m-d H:i:s", $searchtime_arr[1]);
		//成交额
		$statistics_info = $model->statGeneralData($_SESSION['store_id'], $beginDate, $endDate, $queryType);
		$stat_info = $statistics_info['stat_info'];
		$total_info = $statistics_info['total_info'];
		if (!empty($stat_info) && is_array($stat_info)){
			foreach ($stat_info as $k=>$v){
				$stat_amount[$k] = floatval($stat_info[$k]['order_amount']); //成交金额
				//$stat_goods[$k] = intval($stat_info[$k]['order_goods']); //成交件数
				//$stat_member[$k] = intval($stat_info[$k]['order_member']); //成交人数
			}
		}
		$model_flow = Model('customer_flow');
		$shopName = !empty($this->store_info['live_store_name'])?$this->store_info['live_store_name']:'曼谷银';	
		$data = $model_flow->findPassengers_byShopNameOrCode($shopName,"",$beginDate, $endDate, $queryType);
		if ($data['status']==1){
			$total = 0;
			if ($search_arr['search_type']=='day'){
			    //构造竖轴数据
				foreach ($data['data'] as $k=>$v){
					//竖轴
                    $i = intval(date('H',strtotime($v['recordTime'])));
				    $stat_in[$i] = $v['inNum']; //进店人数
					//$stat_out[$i] = $v['outNum']; //出店人数
					$total += $v['inNum'];					
			    }
		    }elseif($search_arr['search_type']=='week'){
			    //构造竖轴数据
			    foreach ($data['data'] as $k=>$v){
					//竖轴
                    $i = intval(date('w',strtotime($v['recordTime'])));
					if ($i==0){$i = 7;}
				    $stat_in[$i] = $v['inNum']; //进店人数
					//$stat_out[$i] = $v['outNum']; //出店人数
					$total += $v['inNum'];
			    }
		    }elseif($search_arr['search_type']=='month'){
		        //构造竖轴数据
			    foreach ($data['data'] as $k=>$v){
					//竖轴
                    $i = intval(date('d',strtotime($v['recordTime'])));
				    $stat_in[$i] = $v['inNum']; //进店人数
					//$stat_out[$i] = $v['outNum']; //出店人数
					$total += $v['inNum'];
			    }
		    }elseif($search_arr['search_type']=='quarter'){
			    //构造竖轴数据
			    foreach ($data['data'] as $k=>$v){
					//竖轴
                    $i = intval(date('m',strtotime($v['recordTime'])));
				    $stat_in[$i] = $v['inNum']; //进店人数
					//$stat_out[$i] = $v['outNum']; //出店人数
					$total += $v['inNum'];
			    }
		    }elseif($search_arr['search_type']=='year'){
			    //构造竖轴数据
	            foreach ($data['data'] as $k=>$v){
					//竖轴
                    $i = intval(date('m',strtotime($v['recordTime'])));
				    $stat_in[$i] = $v['inNum']; //进店人数
					//$stat_out[$i] = $v['outNum']; //出店人数		
					$total += $v['inNum'];
			    }
		    }
			$stat_arr['title'] = '经营概况(客流：'.$total.'人次)';
		}else{
			$stat_arr['title'] = '暂无经营概况统计';
		}
        //得到统计图数据
		//图例
		$stat_arr['legend']['enabled'] = true;
		$stat_arr['legend']['layout'] = 'vertical';
		$stat_arr['legend']['align'] = 'right';
		$stat_arr['legend']['verticalAlign'] = 'middle';
		$stat_arr['legend']['borderWidth'] = 0;
		//数据
		$stat_arr['series'][0]['name'] = '客流人数';
		$stat_arr['series'][0]['data'] = array_values($stat_in);
		//$stat_arr['series'][1]['name'] = '出店';
		//$stat_arr['series'][1]['data'] = array_values($stat_out);
		$stat_arr['series'][1]['name'] = '成交金额';
		$stat_arr['series'][1]['data'] = array_values($stat_amount);
		//$stat_arr['series'][2]['name'] = '成交件数';
		//$stat_arr['series'][2]['data'] = array_values($stat_goods);
		//$stat_arr['series'][3]['name'] = '成交人数';
		//$stat_arr['series'][3]['data'] = array_values($stat_member);
		//竖轴说明
        $stat_arr['yAxis'] = '客流及成交量';
		$stat_json = getStatData_LineLabels($stat_arr);		
        Tpl::output('stat_json',$stat_json);
		//统计数据
		$stat_arr = array();
		$stat_arr['customer'] = $total; //客流人数
		$stat_arr['order_amount'] = round($total_info['order_amount'],2); //成交金额
		$stat_arr['order_member'] = $total_info['order_member']; //成交人数
		$stat_arr['order_goods'] = $total_info['order_goods']; //成交件数		
		$stat_arr['stat_rate1'] = $stat_arr['order_goods']>0?round($stat_arr['order_amount']/$stat_arr['order_goods'],2):0; //件单价 成交金额/成交件数
		$stat_arr['stat_rate2'] = $stat_arr['order_member']>0?round($stat_arr['order_amount']/$stat_arr['order_member'],2):0; //客单价 成交金额/成交人数
		$stat_arr['stat_rate3'] = $stat_arr['customer']>0?round($stat_arr['order_member']/$stat_arr['customer'],2):0; //转化率 成交人数/进店人数
		$stat_arr['stat_rate4'] = $this->store_info['live_area_count']>0?round($stat_arr['order_amount']/$this->store_info['live_area_count'],2):0; //坪效比 成交金额/店铺面积
		$stat_arr['stat_rate5'] = $this->store_info['live_employee_count']>0?round($stat_arr['order_amount']/$this->store_info['live_employee_count'],2):0; //人效比 成交金额/店铺人数
		$stat_arr['stat_rate6'] = $stat_arr['order_member']>0?round($stat_arr['order_goods']/$stat_arr['order_member'],2):0; //连单率 成交件数/购买人数
		$stat_arr['stat_rate7'] = $total_info['vip_amount']>0?round($total_info['vip_count']/$total_info['vip_amount'],2):0; //回头率 二次购买人数/VIP人数
		Tpl::output('stat_arr',$stat_arr);
		self::profile_menu('statistics','general');
    	Tpl::showpage('stat.general.index');
	}
	/**
	 * 营销分析
	 */
	public function  surveyOp(){
	    $model = Model('stat');
	    //统计的日期0点
	    $stat_time = strtotime(date('Y-m-d',time())) - 86400;
	    /*
	     * 近30天
	     */
		$stime = $stat_time - (86400*29);//30天前
		$etime = $stat_time + 86400 - 1;//昨天23:59

		$statnew_arr = array();

		//查询订单表下单量、下单金额、下单客户数
		$where = array();
		$where['order_isvalid'] = 1;//计入统计的有效订单
		$where['store_id'] = $_SESSION['store_id'];
		$where['order_add_time'] = array('between',array($stime,$etime));
		$field = ' COUNT(*) as ordernum, SUM(order_amount) as orderamount, COUNT(DISTINCT buyer_id) as ordermembernum, AVG(order_amount) as avgorderamount ';
	    $stat_order = $model->getoneByStatorder($where, $field);
	    $statnew_arr['ordernum'] = ($t = $stat_order['ordernum'])?$t:0;
	    $statnew_arr['orderamount'] = imPriceFormat(($t = $stat_order['orderamount'])?$t:(0));
	    $statnew_arr['ordermembernum'] = ($t = $stat_order['ordermembernum']) > 0?$t:0;
	    $statnew_arr['avgorderamount'] = imPriceFormat(($t = $stat_order['avgorderamount'])?$t:(0));
	    unset($stat_order);

	    //下单高峰期
		$where = array();
		$where['order_isvalid'] = 1;//计入统计的有效订单
		$where['store_id'] = $_SESSION['store_id'];
		$where['order_add_time'] = array('between',array($stime,$etime));
		$field = ' HOUR(FROM_UNIXTIME(order_add_time)) as hourval,COUNT(*) as ordernum ';
		$orderlist = $model->statByStatorder($where, $field, 0, 0, 'ordernum desc,hourval asc', 'hourval');

		foreach ((array)$orderlist as $k=>$v){
		    if ($k < 2){//取前两个订单量高的时间段
		        if (!$statnew_arr['hothour']){
		            $statnew_arr['hothour'] = ($v['hourval'].":00~".($v['hourval']+1).":00");
		        } else {
		            $statnew_arr['hothour'] .= ("，".($v['hourval'].":00~".($v['hourval']+1).":00"));
		        }
		    }
		}
	    unset($orderlist);

	    //查询订单商品表下单商品数
	    $where = array();
		$where['order_isvalid'] = 1;//计入统计的有效订单
		$where['store_id'] = $_SESSION['store_id'];
		$where['order_add_time'] = array('between',array($stime,$etime));
		$field = ' SUM(goods_num) as ordergoodsnum, AVG(goods_pay_price/goods_num) as avggoodsprice ';
	    $stat_ordergoods = $model->getoneByStatordergoods($where, $field);
	    $statnew_arr['ordergoodsnum'] = ($t = $stat_ordergoods['ordergoodsnum'])?$t:0;
	    $statnew_arr['avggoodsprice'] = imPriceFormat(($t = $stat_ordergoods['avggoodsprice'])?$t:(0));
	    unset($stat_ordergoods);

	    //商品总数、收藏量
	    $goods_list = $model->statByGoods(array('store_id'=>$_SESSION['store_id'],'is_virtual'=>0),'COUNT(*) as goodsnum, SUM(goods_collect) as gcollectnum');
	    $statnew_arr['goodsnum'] = ($t = $goods_list[0]['goodsnum']) > 0?$t:0;
	    $statnew_arr['gcollectnum'] = ($t = $goods_list[0]['gcollectnum']) > 0?$t:0;

	    //店铺收藏量
	    $store_list = $model->getoneByStore(array('store_id'=>$_SESSION['store_id']),'store_collect');
	    $statnew_arr['store_collect'] = ($t = $store_list['store_collect']) > 0?$t:0;

	    /*
	     * 销售走势
	     */
		//构造横轴数据
		for($i=$stime; $i<$etime; $i+=86400){
		    //当前数据的时间
		    $timetext = date('n',$i).'-'.date('j',$i);
			//统计图数据
			$stat_list[$timetext] = 0;
			//横轴
			$stat_arr['xAxis']['categories'][] = $timetext;
		}
		$where = array();
		$where['order_isvalid'] = 1;//计入统计的有效订单
		$where['store_id'] = $_SESSION['store_id'];
		$where['order_add_time'] = array('between',array($stime,$etime));
		$field = ' order_add_time,SUM(order_amount) as orderamount,MONTH(FROM_UNIXTIME(order_add_time)) as monthval,DAY(FROM_UNIXTIME(order_add_time)) as dayval ';
	    $stat_order = $model->statByStatorder($where, $field, 0, 0, '','monthval,dayval');
		if($stat_order){
			foreach($stat_order as $k => $v){
			    $stat_list[$v['monthval'].'-'.$v['dayval']] = floatval($v['orderamount']);
			}
		}
		$stat_arr['legend']['enabled'] = false;
		$stat_arr['series'][0]['name'] = '下单金额';
		$stat_arr['series'][0]['data'] = array_values($stat_list);
		//得到统计图数据
    	$stat_arr['title'] = '最近30天销售走势';
        $stat_arr['yAxis'] = '下单金额';
    	$stattoday_json = getStatData_LineLabels($stat_arr);
    	unset($stat_arr);

	    /*
    	 * 7日内商品销售TOP30
    	 */
    	$stime = $stat_time - 86400*6;//7天前0点
		$etime = $stat_time + 86400 - 1;//今天24点
    	$where = array();
		$where['order_isvalid'] = 1;//计入统计的有效订单
		$where['store_id'] = $_SESSION['store_id'];
		$where['order_add_time'] = array('between',array($stime,$etime));
	    $field = ' sum(goods_num) as ordergoodsnum, goods_id, goods_name ';
	    $goodstop30_arr = $model->statByStatordergoods($where, $field, 0, 30,'ordergoodsnum desc', 'goods_id');

	    /**
	     * 7日内同行热卖商品
	     */
	    $where = array();
		$where['order_isvalid'] = 1;//计入统计的有效订单
		$where['order_add_time'] = array('between',array($stime,$etime));
		$where['store_id'] = array('neq',$_SESSION['store_id']);
		if (!checkPlatformStore()) {//如果不是平台店铺，则查询店铺经营类目的同行数据
    		//查询店铺经营类目
    	    $store_bindclass = Model('store_bind_class')->getStoreBindClassList(array('store_id'=>$_SESSION['store_id']));
    	    $goodsclassid_arr = array();
    	    foreach ((array)$store_bindclass as $k=>$v){
    	        if (intval($v['class_3']) > 0){
    	            $goodsclassid_arr[3][] = intval($v['class_3']);
    	        } elseif (intval($v['class_2']) > 0){
    	            $goodsclassid_arr[2][] = intval($v['class_2']);
    	        } elseif (intval($v['class_1']) > 0){
    	            $goodsclassid_arr[1][] = intval($v['class_1']);
    	        }
    	    }
    		//拼接商品分类条件
    		if ($goodsclassid_arr){
    		    ksort($goodsclassid_arr);
    		    $gc_parentidwhere_keyarr = array();
    		    $gc_parentidwhere_arr = array();
        		foreach ((array)$goodsclassid_arr as $k=>$v){
        		    $gc_parentidwhere_keyarr[] = 'gc_parentid_'.$k;
        		    $gc_parentidwhere_arr[] = array('in',$goodsclassid_arr[$k]);
        		}
        		if (count($gc_parentidwhere_keyarr) == 1){
    		        $where[$gc_parentidwhere_keyarr[0]] = $gc_parentidwhere_arr[0];
        		} else {
        		    $gc_parentidwhere_arr['_multi'] = '1';
    		        $where[implode('|',$gc_parentidwhere_keyarr)] = $gc_parentidwhere_arr;
        		}
    		}
		}
	    $field = ' sum(goods_num) as ordergoodsnum, goods_id, goods_name ';
	    $othergoodstop30_arr = $model->statByStatordergoods($where, $field, 0, 30,'ordergoodsnum desc', 'goods_id');

	    Tpl::output('goodstop30_arr',$goodstop30_arr);
	    Tpl::output('othergoodstop30_arr',$othergoodstop30_arr);
    	Tpl::output('stattoday_json',$stattoday_json);
	    Tpl::output('statnew_arr',$statnew_arr);
	    Tpl::output('stat_time',$stat_time);
		self::profile_menu('statistics','survey');
    	Tpl::showpage('stat.general.survey');
	}

	/**
	 * 价格区间设置
	 */
	public function pricesettingOp(){
	    $model = Model('store_extend');
		if (chksubmit()){
			$update_array = array();
			if ($_POST['pricerange']){
			    foreach ((array)$_POST['pricerange'] as $k=>$v){
			        $pricerange_arr[] = $v;
			    }
			    $update_array['pricerange'] = serialize($pricerange_arr);
			} else {
			    $update_array['pricerange'] = '';
			}
			$result = $model->where(array('store_id'=>$_SESSION['store_id']))->update($update_array);
			if ($result === true){
				showMessage(L('im_common_save_succ'));
			}else {
				showMessage(L('im_common_save_fail'));
			}
		}
		$pricerange = $model->getfby_store_id($_SESSION['store_id'],'pricerange');
		$pricerange = $pricerange?unserialize($pricerange):array();
		Tpl::output('pricerange',$pricerange);
		self::profile_menu('setting','pricesetting');
	    Tpl::showpage('stat.pricesetting');
	}

	/**
	 * 订单价格区间设置
	 */
	public function orderprangeOp(){
	    $model = Model('store_extend');
		if (chksubmit()){
			$update_array = array();
			if ($_POST['pricerange']){
			    foreach ((array)$_POST['pricerange'] as $k=>$v){
			        $pricerange_arr[] = $v;
			    }
			    $update_array['orderpricerange'] = serialize($pricerange_arr);
			} else {
			    $update_array['orderpricerange'] = '';
			}
			$result = $model->where(array('store_id'=>$_SESSION['store_id']))->update($update_array);
			if ($result === true){
				showMessage(L('im_common_save_succ'));
			}else {
				showMessage(L('im_common_save_fail'));
			}
		}
		$pricerange = $model->getfby_store_id($_SESSION['store_id'],'orderpricerange');
		$pricerange = $pricerange?unserialize($pricerange):array();
		Tpl::output('pricerange',$pricerange);
		self::profile_menu('setting','orderprange');
	    Tpl::showpage('stat.orderpricerange');
	}

	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
    private function profile_menu($menu_type,$menu_key='') {
		$menu_array	= array();
		switch ($menu_type) {
			case 'setting':
				$menu_array = array(
				    1=>array('menu_key'=>'pricesetting','menu_name'=>'商品价格区间',	'menu_url'=>'index.php?act=statistics_general&op=pricesetting'),
				    2=>array('menu_key'=>'orderprange','menu_name'=>'订单金额区间',	'menu_url'=>'index.php?act=statistics_general&op=orderprange'));
				break;
			case 'statistics':
				$menu_array = array(
				    1=>array('menu_key'=>'general','menu_name'=>'运营概况',	'menu_url'=>'index.php?act=statistics_general&op=general'),
				    2=>array('menu_key'=>'survey','menu_name'=>'营销分析',	'menu_url'=>'index.php?act=statistics_general&op=survey'));
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
