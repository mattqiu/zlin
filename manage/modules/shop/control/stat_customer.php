<?php
////////////////////////////////////////////////////////////////////
//                          _ooOoo_                               //
//                         o8888888o                              //
//                         88" . "88                              //
//                         (| ^_^ |)                              //
//                         O\  =  /O                              //
//                      ____/`---'\____                           //
//                    .'  \\|     |//  `.                         //
//                   /  \\|||  :  |||//  \                        //
//                  /  _||||| -:- |||||-  \                       //
//                  |   | \\\  -  /// |   |                       //
//                  | \_|  ''\---/''  |   |                       //
//                  \  .-\__  `-`  ___/-. /                       //
//                ___`. .'  /--.--\  `. . ___                     //
//              ."" '<  `.___\_<|>_/___.'  >'"".                  //
//            | | :  `- \`.;`\ _ /`;.`/ - ` : | |                 //
//            \  \ `-.   \_ __\ /__ _/   .-` /  /                 //
//      ========`-.____`-.___\_____/___.-`____.-'========         //
//                           `=---='                              //
//      ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^        //
//         佛祖保佑            永无BUG              永不修改         //
////////////////////////////////////////////////////////////////////
/**
 * 店铺客流统计
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e.com Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */

defined('InIMall') or exit('Access Invalid!');

class stat_customerControl extends SystemControl{
    private $search_arr;//处理后的参数

    public function __construct(){
        parent::__construct();
        Language::read('stat');
        import('function.statistics');
        import('function.datehelper');
        $model = Model('stat');
        //存储参数
        $this->search_arr = $_REQUEST;
        //处理搜索时间
        $this->search_arr = $model->dealwithSearchTime($this->search_arr);
        //获得系统年份
        $year_arr = getSystemYearArr();
		//获得系统本年季度时间段
		$quarter_arr = getSystemQuarterArr();
        //获得系统月份
        $month_arr = getSystemMonthArr();
        //获得本月的周时间段
        $week_arr = getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);
        Tpl::output('year_arr', $year_arr);
		Tpl::output('quarter_arr', $quarter_arr);
        Tpl::output('month_arr', $month_arr);
        Tpl::output('week_arr', $week_arr);
        Tpl::output('search_arr', $this->search_arr);
    }

    public function indexOp() {
        $this->customerflowOp();
    }

    /**
     * 客流统计
     */
    public function customerflowOp(){
        $model = Model('stat');
		if(!$this->search_arr['search_type']){
			$this->search_arr['search_type'] = 'day';
		}
		$search_shopname = '';
		if (!empty($this->search_arr['search_shopname'])){
			$search_shopname = $this->search_arr['search_shopname'];
		}
		//获得搜索的开始时间和结束时间
		$searchtime_arr = $model->getStarttimeAndEndtime($this->search_arr);		
		
		$stat_arr = array();
		$queryType = 5;		
		if ($this->search_arr['search_type']=='day'){
			$queryType = 5;
			//构造横轴数据
	        for($i=1; $i<=24; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = $i.':00';
				$stat_in[$i] = 0;
				$stat_out[$i] = 0;
			}
		}elseif($this->search_arr['search_type']=='week'){
			$queryType = 4;
			//构造横轴数据
			$tmp_weekarr = getSystemWeekArr();
	        for($i=1; $i<=7; $i++){	            
				//横轴
				$stat_arr['xAxis']['categories'][] = $tmp_weekarr[$i];
				$stat_in[$i] = 0;
				$stat_out[$i] = 0;
			}
			unset($tmp_weekarr);
			$searchtime_arr[0] -= 86400; //星期-减一天
		}elseif($this->search_arr['search_type']=='month'){
			$queryType = 4;
			//计算横轴的最大量（由于每个月的天数不同）
			$dayofmonth = date('t',$searchtime_arr[0]);
		    //构造横轴数据
			for($i=1; $i<=$dayofmonth; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = $i;
				$stat_in[$i] = 0;
				$stat_out[$i] = 0;
			}
			$searchtime_arr[0] -= 86400; //减一天
		}elseif($this->search_arr['search_type']=='quarter'){
			$queryType = 3;
			//构造横轴数据
			$startmonth = intval(date('m', $searchtime_arr[0]));
			$endmonth = intval(date('m', $searchtime_arr[1]));
			for($i=$startmonth; $i<=$endmonth; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = $i.'月';
				$stat_in[$i] = 0;
				$stat_out[$i] = 0;
			}
		}elseif($this->search_arr['search_type']=='year'){
			$queryType = 3;
			//构造横轴数据
	        for($i=1; $i<=12; $i++){
				//横轴
				$stat_arr['xAxis']['categories'][] = $i.'月';
				$stat_in[$i] = 0;
				$stat_out[$i] = 0;
			}
		}		
		$beginDate = date("Y-m-d H:i:s", $searchtime_arr[0]);
		$endDate   = date("Y-m-d H:i:s", $searchtime_arr[1]);

		$model_flow = Model('customer_flow');
		//取店铺列表
		$shop_list = array();
		$shop_data = $model_flow->findShops();	
		//echo json_encode($v);	
		if ($shop_data['status']==1){
			foreach ($shop_data['data'] as $k=>$v){				
				$shop_list[$v['shopId']] = $v['shopName'];
			}
		}
		Tpl::output('shop_list', $shop_list);
		//取统计数据	
		$data = $model_flow->findPassengers_byShopNameOrCode($search_shopname,"",$beginDate, $endDate, $queryType);
		if ($data['status']==1){
			$total = 0;
			if ($this->search_arr['search_type']=='day'){
			    //构造竖轴数据
				foreach ($data['data'] as $k=>$v){
					//竖轴
                    $i = intval(date('H',strtotime($v['recordTime'])));
				    $stat_in[$i] = $v['inNum'];
					$stat_out[$i] = $v['outNum'];
					$total += $v['inNum'];					
			    }
		    }elseif($this->search_arr['search_type']=='week'){
			    //构造竖轴数据
			    foreach ($data['data'] as $k=>$v){
					//竖轴
                    $i = intval(date('w',strtotime($v['recordTime'])));
					if ($i==0){$i = 7;}

				    $stat_in[$i] = $v['inNum'];
					$stat_out[$i] = $v['outNum'];
					$total += $v['inNum'];
			    }
		    }elseif($this->search_arr['search_type']=='month'){
		        //构造竖轴数据
			    foreach ($data['data'] as $k=>$v){
					//竖轴
                    $i = intval(date('d',strtotime($v['recordTime'])));

				    $stat_in[$i] = $v['inNum'];
					$stat_out[$i] = $v['outNum'];
					$total += $v['inNum'];
			    }
		    }elseif($this->search_arr['search_type']=='quarter'){
			    //构造竖轴数据
			    foreach ($data['data'] as $k=>$v){
					//竖轴
                    $i = intval(date('m',strtotime($v['recordTime'])));

				    $stat_in[$i] = $v['inNum'];
					$stat_out[$i] = $v['outNum'];
					$total += $v['inNum'];
			    }
		    }elseif($this->search_arr['search_type']=='year'){
			    //构造竖轴数据
	            foreach ($data['data'] as $k=>$v){
					//竖轴
                    $i = intval(date('m',strtotime($v['recordTime'])));

				    $stat_in[$i] = $v['inNum'];
					$stat_out[$i] = $v['outNum'];
					$total += $v['inNum'];
			    }
		    }
			$stat_arr['title'] = '客流统计(入店：'.$total.'人次)';
		}else{
			$stat_arr['title'] = '暂无客流统计';
		}

        //得到统计图数据
		//图例
		$stat_arr['legend']['enabled'] = true;
		//$stat_arr['legend']['layout'] = 'vertical';
		//$stat_arr['legend']['align'] = 'right';
		//$stat_arr['legend']['verticalAlign'] = 'middle';

		$stat_arr['legend']['borderWidth'] = 0;
		//数据
		$stat_arr['series'][0]['name'] = '入店';
		$stat_arr['series'][0]['data'] = array_values($stat_in);
		$stat_arr['series'][1]['name'] = '出店';
		$stat_arr['series'][1]['data'] = array_values($stat_out);
		//竖轴说明
        $stat_arr['yAxis'] = '客流量';
		$stat_json = getStatData_SPLineLabels($stat_arr);		
        Tpl::output('stat_json',$stat_json);

        Tpl::setDirquna('seller');
		Tpl::showpage('stat_customer.index');
    }
}
