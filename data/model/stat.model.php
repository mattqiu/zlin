<?php
/**
 * 统计管理
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e.com Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class statModel extends Model{
    /**
     * 查询新增会员统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @param boolean $lock 是否锁定
     * @return array
     */
    public function statByMember($where, $field = '*', $page = 0, $order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('member')->field($field)->where($where)->page($page[0],$page[1])->order($order)->group($group)->select();
            } else {
                return $this->table('member')->field($field)->where($where)->page($page[0])->order($order)->group($group)->select();
            }
        } else {
            return $this->table('member')->field($field)->where($where)->page($page)->order($order)->group($group)->select();
        }  
    }
	/**
     * 查询单条会员统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @return array
     */
    public function getoneByMember($where, $field = '*', $order = '', $group = '') {
        return $this->table('member')->field($field)->where($where)->order($order)->group($group)->find();
    }
	/**
     * 查询单条店铺统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @return array
     */
    public function getoneByStore($where, $field = '*', $order = '', $group = '') {
        return $this->table('store')->field($field)->where($where)->order($order)->group($group)->find();
    }
	/**
     * 查询店铺统计
     */
    public function statByStore($where, $field = '*', $page = 0, $limit = 0, $order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('store')->field($field)->where($where)->page($page[0],$page[1])->limit($limit)->group($group)->order($order)->select();
            } else {
                return $this->table('store')->field($field)->where($where)->page($page[0])->limit($limit)->group($group)->order($order)->select();
            }
        } else {
            return $this->table('store')->field($field)->where($where)->page($page)->limit($limit)->group($group)->order($order)->select();
        }
    }
    /**
     * 查询新增店铺统计
     */
	public function getNewStoreStatList($condition, $field = '*', $page = 0, $order = 'store_id desc', $limit = 0, $group = '') {
        return $this->table('store')->field($field)->where($condition)->page($page)->limit($limit)->group($group)->order($order)->select();
    }
    
    /**
     * 查询会员列表
     */
    public function getMemberList($where, $field = '*', $page = 0, $order = 'member_id desc', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('member')->field($field)->where($where)->page($page[0],$page[1])->group($group)->order($order)->select();
            } else {
                return $this->table('member')->field($field)->where($where)->page($page[0])->group($group)->order($order)->select();
            }
        } else {
            return $this->table('member')->field($field)->where($where)->page($page)->group($group)->order($order)->select();
        }
    }
    
    /**
     * 调取店铺等级信息
     */
    public function getStoreDegree(){
    	$tmp = $this->table('store_grade')->field('sg_id,sg_name')->where(true)->select();
    	$sd_list = array();
    	if(!empty($tmp)){
	    	foreach ($tmp as $k=>$v){
	    		$sd_list[$v['sg_id']] = $v['sg_name'];
	    	}
    	}
    	return $sd_list;
    }
    
    /**
     * 查询会员统计数据记录
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByStatmember($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('stat_member')->field($field)->where($where)->page($page[0],$page[1])->limit($limit)->order($order)->group($group)->select();
            } else {
                return $this->table('stat_member')->field($field)->where($where)->page($page[0])->limit($limit)->order($order)->group($group)->select();
            }
        } else {
            return $this->table('stat_member')->field($field)->where($where)->page($page)->limit($limit)->order($order)->group($group)->select();
        }
    }
    
    /**
     * 查询商品数量
     */
    public function getGoodsNum($where){
    	$rs = $this->field('count(*) as allnum')->table('goods_common')->where($where)->select();
    	return $rs[0]['allnum'];
    }
    /**
     * 获取积分数据
     */
    public function getPredepositInfo($condition, $field = '*', $page = 0, $order = 'lg_add_time desc', $limit = 0, $group = ''){
    	return $this->table('pd_log')->field($field)->where($condition)->page($page)->limit($limit)->group($group)->order($order)->select();
    }
    /**
     * 获取结算数据
     */
    public function getBillList($condition,$type,$have_page=true, $order = 'ob_no desc'){
    	switch ($type){
            case 'os'://平台
                return $this->field('sum(os_order_totals) as oot,sum(os_order_return_totals) as oort,sum(os_commis_totals-os_commis_return_totals) as oct,sum(os_store_cost_totals) as osct,sum(os_result_totals) as ort')->table('order_statis')->where($condition)->select();
                break;
            case 'ob'://店铺
                $page = $have_page?15:'';
                return $this->field('order_bill.*,store.member_name')->table('order_bill,store')->join('left join')->on('order_bill.ob_store_id=store.store_id')->where($condition)->page($page)->order($order)->select();
                break;
        }
    }
	/**
     * 查询订单及订单商品的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByOrderGoods($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('order_goods,order')->field($field)->join('left')->on('order_goods.order_id=order.order_id')->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                return $this->table('order_goods,order')->field($field)->join('left')->on('order_goods.order_id=order.order_id')->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }  
        } else {
            return $this->table('order_goods,order')->field($field)->join('left')->on('order_goods.order_id=order.order_id')->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
    }
	/**
     * 查询订单及订单商品的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByOrderLog($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('order_log,order')->field($field)->join('left')->on('order_log.order_id = order.order_id')->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                return $this->table('order_log,order')->field($field)->join('left')->on('order_log.order_id = order.order_id')->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }  
        } else {
            return $this->table('order_log,order')->field($field)->join('left')->on('order_log.order_id = order.order_id')->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
    }
	/**
     * 查询退款退货统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByRefundreturn($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('refund_return')->field($field)->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                return $this->table('refund_return')->field($field)->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }
        } else {
            return $this->table('refund_return')->field($field)->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
    }
	/**
     * 查询店铺动态评分统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByStoreAndEvaluatestore($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = ''){
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('evaluate_store,store')->field($field)->join('left')->on('evaluate_store.seval_storeid=store.store_id')->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                return $this->table('evaluate_store,store')->field($field)->join('left')->on('evaluate_store.seval_storeid=store.store_id')->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }
        } else {
            return $this->table('evaluate_store,store')->field($field)->join('left')->on('evaluate_store.seval_storeid=store.store_id')->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
    }
    /**
     * 处理搜索时间
     */
    public function dealwithSearchTime($search_arr){
        //初始化时间
        //天
        if(!$search_arr['search_time']){
            $search_arr['search_time'] = date('Y-m-d', time()); //- 86400
        }
        $search_arr['day']['search_time'] = strtotime($search_arr['search_time']);//搜索的时间

        //周
        if(!$search_arr['searchweek_year']){
            $search_arr['searchweek_year'] = date('Y', time());
        }
        if(!$search_arr['searchweek_month']){
            $search_arr['searchweek_month'] = date('m', time());
        }
        if(!$search_arr['searchweek_week']){
            $searchweek_weekarr = getWeek_SdateAndEdate(time());
            $search_arr['searchweek_week'] = implode('|', $searchweek_weekarr);
            $searchweek_week_edate_m = date('m', strtotime($searchweek_weekarr['edate']));
            if($searchweek_week_edate_m <> $search_arr['searchweek_month']){
                $search_arr['searchweek_month'] = $searchweek_week_edate_m;
            }
        }
        $weekcurrent_year = $search_arr['searchweek_year'];
        $weekcurrent_month = $search_arr['searchweek_month'];
        $weekcurrent_week = $search_arr['searchweek_week'];
        $search_arr['week']['current_year'] = $weekcurrent_year;
        $search_arr['week']['current_month'] = $weekcurrent_month;
        $search_arr['week']['current_week'] = $weekcurrent_week;

        //月
        if(!$search_arr['searchmonth_year']){
            $search_arr['searchmonth_year'] = date('Y', time());
        }
        if(!$search_arr['searchmonth_month']){
            $search_arr['searchmonth_month'] = date('m', time());
        }
        $monthcurrent_year = $search_arr['searchmonth_year'];
        $monthcurrent_month = $search_arr['searchmonth_month'];
        $search_arr['month']['current_year'] = $monthcurrent_year;
        $search_arr['month']['current_month'] = $monthcurrent_month;
		//季度
		if(!$search_arr['searchquarter_year']){
            $search_arr['searchquarter_year'] = date('Y', time());
        }
        if(!$search_arr['searchquarter_quarter']){
            $search_arr['searchquarter_quarter'] = ceil(intval(date('m', time()))/3);
        }
        $quartercurrent_year = $search_arr['searchquarter_year'];
        $quartercurrent_quarter = $search_arr['searchquarter_quarter'];
        $search_arr['quarter']['current_year'] = $quartercurrent_year;
        $search_arr['quarter']['current_quarter'] = $quartercurrent_quarter;
		//年
		if(!$search_arr['searchyear_year']){
            $search_arr['searchyear_year'] = date('Y', time());
        }
        $yearcurrent_year = $search_arr['searchyear_year'];
        $search_arr['year']['current_year'] = $yearcurrent_year;
        return $search_arr;
    }
    /**
     * 获得查询的开始和结束时间
     */
    public function getStarttimeAndEndtime($search_arr){
		//日
        if($search_arr['search_type'] == 'day'){
            $stime = $search_arr['day']['search_time'];//今天0点
            $etime = $search_arr['day']['search_time'] + 86400 - 1;//今天24点
        }
		//周
        if($search_arr['search_type'] == 'week'){
            $current_weekarr = explode('|', $search_arr['week']['current_week']);
            $stime = strtotime($current_weekarr[0]);
            $etime = strtotime($current_weekarr[1])+86400-1;
        }
		//月
        if($search_arr['search_type'] == 'month'){
            $stime = strtotime($search_arr['month']['current_year'].'-'.$search_arr['month']['current_month']."-01 0 month");
            $etime = getMonthLastDay($search_arr['month']['current_year'],$search_arr['month']['current_month'])+86400-1;
        }
		//季
		if($search_arr['search_type'] == 'quarter'){
			if ($search_arr['quarter']['current_quarter'] == 1){
				$stime = strtotime($search_arr['quarter']['current_year'].'-01-01 00:00:00');
                $etime = strtotime($search_arr['quarter']['current_year'].'-03-31 23:59:59');
			}elseif($search_arr['quarter']['current_quarter'] == 2){
				$stime = strtotime($search_arr['quarter']['current_year'].'-04-01 00:00:00');
                $etime = strtotime($search_arr['quarter']['current_year'].'-06-30 23:59:59');
			}elseif($search_arr['quarter']['current_quarter'] == 3){
				$stime = strtotime($search_arr['quarter']['current_year'].'-07-01 00:00:00');
                $etime = strtotime($search_arr['quarter']['current_year'].'-09-30 23:59:59');
			}else{
				$stime = strtotime($search_arr['quarter']['current_year'].'-10-01 00:00:00');
                $etime = strtotime($search_arr['quarter']['current_year'].'-12-31 23:59:59');
			}
        }
		//年
		if($search_arr['search_type'] == 'year'){
            $stime = mktime(0,0,0,1,1, $search_arr['year']['current_year']);
            $etime = mktime(23,59,59,12,31, $search_arr['year']['current_year']); 
        }
        return array($stime,$etime);
    }
	/**
     * 查询会员统计数据单条记录
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @return array
     */
    public function getOneStatmember($where, $field = '*', $order = '', $group = ''){
        return $this->table('stat_member')->field($field)->where($where)->group($group)->order($order)->find();
    }
	/**
     * 更新会员统计数据单条记录
     * 
     * @param array $condition 条件
     * @param array $update_arr 更新数组
     * @return array
     */
    public function updateStatmember($where,$update_arr){
        return $this->table('stat_member')->where($where)->update($update_arr);
    }
	/**
     * 统计经营概况
     * 
     * @param $store_id 店铺ID
     * @param $beginDate 统计开始时间
     * @return $endDate 统计结束时间
	 * @return $queryType 统计时间类型   1：年 2：季度 3：月 4：日  5：小时
     */
    public function statGeneralData($store_id, $beginDate, $endDate, $queryType){
		$beginTime = strtotime($beginDate);
		$endTime = strtotime($endDate);
		$query_arr = array();
        switch ($queryType) {
			case 5: //小时
			    $begin_h = intval(date("H",$beginTime));
				$end_h = intval(date("H",$endTime));
				for($i=$begin_h; $i<=$end_h; $i++){
					$query_arr[$i+1]['begin'] = $beginTime + $i*60*60;
					$query_arr[$i+1]['end'] = $beginTime + ($i+1)*60*60 - 1;
				}
			    break;
			case 4: //日
			    $i = 0;
				$start_Time = $beginTime;
			    while ($start_Time<=$endTime) {
					$i++;				
					$query_arr[$i]['begin'] = $start_Time;
					$query_arr[$i]['end'] = $start_Time + 60*60*24 - 1;					
					$start_Time += 60*60*24;
				}
			    break;
			case 3: //月
				$start_Time = $beginTime;
			    while ($start_Time<=$endTime) {
					$m = intval(date('m',$start_Time));			
					$query_arr[$m]['begin'] = $start_Time;
					$query_arr[$m]['end'] = strtotime("+1 months", $start_Time)-1;
					$start_Time = strtotime("+1 months", $start_Time);
				}
			    break;
			case 2: //季
			    break;
			case 1: //年
			    break;
		}
		$stat_info = array();
		//分时间段统计数据
		$order_amount = 0; //成交金额
		$order_goods = 0; //成交件数
		$order_member = 0; //成交人数
		$vip_amount = 0; //VIP人数
		$vip_count = 0; //二次购买人数
		if (!empty($query_arr) && is_array($query_arr)){
			foreach($query_arr as $k=>$v){
				$where = array();
				if (!empty($store_id)){
				    $where['store_id'] = $store_id;
				}
				$where['payment_time'] = array('between',array($v['begin'],$v['end']));
				//成交金额 成交人数
				$fields = 'SUM(order_amount) as order_amount, COUNT(DISTINCT buyer_id) as order_member';
				$data_value = $this->table('order')->field($fields)->where($where)->find();
				if (!empty($data_value)){					
					$stat_info[$k]['order_amount'] = empty($data_value['order_amount'])?0:$data_value['order_amount'];
					$stat_info[$k]['order_member'] = empty($data_value['order_member'])?0:$data_value['order_member'];
					$order_amount += $stat_info[$k]['order_amount'];
					$order_member += $stat_info[$k]['order_member'];
				}else{
					$stat_info[$k]['order_amount'] = 0;
					$stat_info[$k]['order_member'] = 0;
				}
				//成交件数
				$where = array();
				if (!empty($store_id)){
				    $where['order.store_id'] = $store_id;
				}
				$where['order.payment_time'] = array('between',array($v['begin'],$v['end']));
				$fields = 'SUM(order_goods.goods_num) as order_goods';
				$data_value = $this->table('order,order_goods')->field($fields)->join('left')->on('order.order_id=order_goods.order_id')->where($where)->find();
				if (!empty($data_value)){
					$stat_info[$k]['order_goods'] = empty($data_value['order_goods'])?0:$data_value['order_goods'];
					$order_goods += $stat_info[$k]['order_goods'];
				}else{
					$stat_info[$k]['order_goods'] = 0;
				}
			}
		}
		//VIP人数
		$where = array();
		if (!empty($store_id)){
		    $where['store_id'] = $store_id;
		}
		$fields = 'COUNT(member_id) as vip_amount';
		$data_value = $this->table('member')->field($fields)->where($where)->find();
		if (!empty($data_value)){
			$vip_amount = $data_value['vip_amount'];
		}
		//二次购买人数
		$where = array();
		if (!empty($store_id)){
		    $where['store_id'] = $store_id;
		}
		$fields = 'COUNT(buyer_id) as vip_count';
		$data_value = $this->table('order')->field($fields)->group('buyer_id')->where($where)->select();
		if (!empty($data_value)){
			foreach($data_value as $k=>$v){
				if ($v['vip_count']>1){
					$vip_count += 1;
				}
			}
		}
		$total_info = array();
		$total_info['order_amount'] = $order_amount;
		$total_info['order_goods'] = $order_goods;
		$total_info['order_member'] = $order_member;
		$total_info['vip_amount'] = $vip_amount;
		$total_info['vip_count'] = $vip_count;
		return array('total_info'=>$total_info, 'stat_info'=>$stat_info);			
    }
	/**
     * 查询订单的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByOrder($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('order')->field($field)->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                return $this->table('order')->field($field)->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }   
        } else {
            return $this->table('order')->field($field)->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
    }
	/**
     * 查询云币的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByPointslog($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('points_log')->field($field)->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                return $this->table('points_log')->field($field)->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }
        } else {
            return $this->table('points_log')->field($field)->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
    }
	/**
     * 删除会员统计数据记录
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function delByStatmember($where = array()) {
        $this->table('stat_member')->where($where)->delete();   
    }
    /**
     * 查询订单商品缓存的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @return array
     */
    public function getoneByStatordergoods($where, $field = '*', $order = '', $group = '') {
        return $this->table('stat_ordergoods')->field($field)->where($where)->group($group)->order($order)->find();
    }
	/**
     * 查询订单商品缓存的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByStatordergoods($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('stat_ordergoods')->field($field)->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                return $this->table('stat_ordergoods')->field($field)->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }   
        } else {
            return $this->table('stat_ordergoods')->field($field)->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
    }
	/**
     * 查询订单缓存的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @return array
     */
    public function getoneByStatorder($where, $field = '*', $order = '', $group = '') {
        return $this->table('stat_order')->field($field)->where($where)->group($group)->order($order)->find();
    }
	/**
     * 查询订单缓存的统计
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByStatorder($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('stat_order')->field($field)->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                return $this->table('stat_order')->field($field)->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }   
        } else {
            return $this->table('stat_order')->field($field)->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
    }
	/**
     * 查询商品列表
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByGoods($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('goods')->field($field)->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                return $this->table('goods')->field($field)->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }   
        } else {
            return $this->table('goods')->field($field)->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
    }
    
    /**
     * 查询流量统计单条记录
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @return array
     */
    public function getoneByFlowstat($tablename = 'flowstat', $where, $field = '*', $order = '', $group = '') {
        return $this->table($tablename)->field($field)->where($where)->group($group)->order($order)->find();
    }
	/**
     * 查询流量统计记录
     * 
     * @param array $condition 条件
     * @param string $field 字段
     * @param string $group 分组
     * @param string $order 排序
     * @param int $limit 限制
     * @param int $page 分页
     * @return array
     */
    public function statByFlowstat($tablename = 'flowstat', $where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table($tablename)->field($field)->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                return $this->table($tablename)->field($field)->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }   
        } else {
            return $this->table($tablename)->field($field)->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
    }
}