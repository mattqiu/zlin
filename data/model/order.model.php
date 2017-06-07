<?php
/**
 * 订单管理
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
class orderModel extends Model {

    /**
     * 取单条订单信息
     *
     * @param unknown_type $condition
     * @param array $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return unknown
     */
    public function getOrderInfo($condition = array(), $extend = array(), $fields = '*', $order = '',$group = '') {
        $order_info = $this->table('order')->field($fields)->where($condition)->group($group)->order($order)->find();
        if (empty($order_info)) {
            return array();
        }
        if (isset($order_info['order_state'])) {
            $order_info['state_desc'] = orderState($order_info);
        }
        if (isset($order_info['payment_code'])) {
            $order_info['payment_name'] = orderPaymentName($order_info['payment_code']);
        }
        //追加返回订单扩展表信息
        if (in_array('order_common',$extend)) {
            $order_info['extend_order_common'] = $this->getOrderCommonInfo(array('order_id'=>$order_info['order_id']));
            $order_info['extend_order_common']['reciver_info'] = unserialize($order_info['extend_order_common']['reciver_info']);
            $order_info['extend_order_common']['invoice_info'] = unserialize($order_info['extend_order_common']['invoice_info']);
        }

        //追加返回店铺信息
        if (in_array('store',$extend)) {
            $order_info['extend_store'] = Model('store')->getStoreInfo(array('store_id'=>$order_info['store_id']));
        }

        //返回买家信息
        if (in_array('member',$extend)) {
            $order_info['extend_member'] = Model('member')->getMemberInfoByID($order_info['buyer_id']);
        }

        //追加返回商品信息
        if (in_array('order_goods',$extend)) {
            //取商品列表
            $order_goods_list = $this->getOrderGoodsList(array('order_id'=>$order_info['order_id']));//,'goods_type'=>array('neq',5)
            $order_info['extend_order_goods'] = $order_goods_list;
			//取赠品列表
            $order_gifts_list = $this->getOrderGoodsList(array('order_id'=>$order_info['order_id'],'goods_type'=>5));
			$order_info['zengpin_list'] = array();
            foreach ($order_gifts_list as $value) {				
            	$order_info['zengpin_list'][] = $value;
            }
        }

        return $order_info;
    }

    public function getOrderCommonInfo($condition = array(), $field = '*') {
        return $this->table('order_common')->where($condition)->find();
    }

    public function getOrderPayInfo($condition = array(), $master = false) {
        return $this->table('order_pay')->where($condition)->master($master)->find();
    }	

    /**
     * 取得支付单列表
     *
     * @param unknown_type $condition
     * @param unknown_type $pagesize
     * @param unknown_type $filed
     * @param unknown_type $order
     * @param string $key 以哪个字段作为下标,这里一般指pay_id
     * @return unknown
     */
    public function getOrderPayList($condition, $pagesize = '', $filed = '*', $order = '', $key = '') {
        return $this->table('order_pay')->field($filed)->where($condition)->order($order)->page($pagesize)->key($key)->select();
    }
	
	/**
     * 取得订单列表(未被删除)
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getNormalOrderList($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array()){
        $condition['delete_state'] = 0;
        return $this->getOrderList($condition, $pagesize, $field, $order, $limit, $extend);
    }

    /**
     * 取得订单列表(所有)
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getOrderList($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array(), $master = false){
        $list = $this->table('order')->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->master($master)->select();
        if (empty($list)) return array();
        $order_list = array();
        foreach ($list as $order) {
        	if (isset($order['order_state'])) {				
                $order['state_desc'] = orderState($order);
            }
            if (isset($order['payment_code'])) {				
                $order['payment_name'] = orderPaymentName($order['payment_code']);
            }
        	if (!empty($extend)) $order_list[$order['order_id']] = $order;
        }
        if (empty($order_list)) $order_list = $list;

        //追加返回订单扩展表信息
        if (in_array('order_common',$extend)) {
            $order_common_list = $this->getOrderCommonList(array('order_id'=>array('in',array_keys($order_list))));
            foreach ($order_common_list as $value) {
                $order_list[$value['order_id']]['extend_order_common'] = $value;
                $order_list[$value['order_id']]['extend_order_common']['reciver_info'] = @unserialize($value['reciver_info']);
                $order_list[$value['order_id']]['extend_order_common']['invoice_info'] = @unserialize($value['invoice_info']);
            }
        }
        //追加返回店铺信息
        if (in_array('store',$extend)) {
            $store_id_array = array();
            foreach ($order_list as $value) {
            	if (!in_array($value['store_id'],$store_id_array)) $store_id_array[] = $value['store_id'];
            }
            $store_list = Model('store')->getStoreList(array('store_id'=>array('in',$store_id_array)));
            $store_new_list = array();
            foreach ($store_list as $store) {				
            	$store_new_list[$store['store_id']] = $store;
            }
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_store'] = $store_new_list[$order['store_id']];
            }
        }

        //追加返回买家信息
		if (in_array('member',$extend)) {
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_member'] = Model('member')->getMemberInfoByID($order['buyer_id']);
            }
        }        

        //追加返回商品信息
        if (in_array('order_goods',$extend)) {
            //取商品列表
            $order_goods_list = $this->getOrderGoodsList(array('order_id'=>array('in',array_keys($order_list))));//,'goods_type'=>array('neq',5)
            if (!empty($order_goods_list)) {
                foreach ($order_goods_list as $value) {
                    $order_list[$value['order_id']]['extend_order_goods'][] = $value;
					$order_list[$value['order_id']]['goods_number'] = $this->getOrderGoodsSum(array('order_id'=>$value['order_id']));//订单商品总数
                }
            } else {
                $order_list[$value['order_id']]['extend_order_goods'] = array();
            }			
			
			//取赠品列表
            $order_gifts_list = $this->getOrderGoodsList(array('order_id'=>array('in',array_keys($order_list)),'goods_type'=>5));
			$order_list[$value['order_id']]['zengpin_list'] = array();
            foreach ($order_gifts_list as $value) {
                $value['goods_image_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
            	$order_list[$value['order_id']]['zengpin_list'][] = $value;
            }			
        }

        return $order_list;
    }
	
	/**
     * 取得(买/卖家)订单某个数量缓存
     * @param string $type 买/卖家标志，允许传入 buyer、store
     * @param int $id   买家ID、店铺ID
     * @param string $key 允许传入  NewCount、PayCount、SendCount、EvalCount，分别取相应数量缓存，只许传入一个
     * @return array
     */
    public function getOrderCountCache($type, $id, $key) {
        if (!C('cache_open')) return array();
        $type = 'ordercount'.$type;
        $ins = Cache::getInstance('cacheredis');
        $order_info = $ins->hget($id,$type,$key);
        return !is_array($order_info) ? array($key => $order_info) : $order_info;
    }

    /**
     * 设置(买/卖家)订单某个数量缓存
     * @param string $type 买/卖家标志，允许传入 buyer、store
     * @param int $id 买家ID、店铺ID
     * @param array $data
     */
    public function editOrderCountCache($type, $id, $data) {
        if (!C('cache_open') || empty($type) || !intval($id) || !is_array($data)) return ;
        $ins = Cache::getInstance('cacheredis');
        $type = 'ordercount'.$type;
        $ins->hset($id,$type,$data);
    }

    /**
     * 取得买卖家订单数量某个缓存
     * @param string $type $type 买/卖家标志，允许传入 buyer、store
     * @param int $id 买家ID、店铺ID
     * @param string $key 允许传入  NewCount、PayCount、SendCount、EvalCount，分别取相应数量缓存，只许传入一个
     * @return int
     */
    public function getOrderCountByID($type, $id, $key) {
        $cache_info = $this->getOrderCountCache($type, $id, $key);
        
        if (is_string($cache_info[$key])) {
            //从缓存中取得
            $count = $cache_info[$key];
        } else {
            //从数据库中取得
            $field = $type == 'buyer' ? 'buyer_id' : 'store_id';
            $condition = array($field => $id);
            $func = 'getOrderState'.$key;
            $count = $this->$func($condition);
            $this->editOrderCountCache($type,$id,array($key => $count));
        }
        return $count;
    }

    /**
     * 删除(买/卖家)订单全部数量缓存
     * @param string $type 买/卖家标志，允许传入 buyer、store
     * @param int $id   买家ID、店铺ID
     * @return bool
     */
    public function delOrderCountCache($type, $id) {
        if (!C('cache_open')) return true;
        $ins = Cache::getInstance('cacheredis');
        $type = 'ordercount'.$type;
        return $ins->hdel($id,$type);
    }

    /**
     * 待付款订单数量
     * @param unknown $condition
     */
    public function getOrderStateNewCount($condition = array()) {
        $condition['order_state'] = ORDER_STATE_NEW;
        return $this->getOrderCount($condition);
    }

    /**
     * 待发货订单数量
     * @param unknown $condition
     */
    public function getOrderStatePayCount($condition = array()) {
        $condition['order_state'] = ORDER_STATE_PAY;
        return $this->getOrderCount($condition);
    }

    /**
     * 待收货订单数量
     * @param unknown $condition
     */
    public function getOrderStateSendCount($condition = array()) {
        $condition['order_state'] = ORDER_STATE_SEND;
        return $this->getOrderCount($condition);
    }

    /**
     * 待评价订单数量
     * @param unknown $condition
     */
    public function getOrderStateEvalCount($condition = array()) {
        $condition['order_state'] = ORDER_STATE_SUCCESS;
        $condition['evaluation_state'] = 0;
        return $this->getOrderCount($condition);
    }

    /**
     * 交易中的订单数量
     * @param unknown $condition
     */
    public function getOrderStateTradeCount($condition = array()) {
        $condition['order_state'] = array(array('neq',ORDER_STATE_CANCEL),array('neq',ORDER_STATE_SUCCESS),'and');
        return $this->getOrderCount($condition);
    }
    /**
     * 取得订单数量
     * @param unknown $condition
     */
    public function getOrderCount($condition) {
        return $this->table('order')->where($condition)->count();
    }

    /**
     * 取得订单商品表详细信息
     * @param unknown $condition
     * @param string $fields
     * @param string $order
     */
    public function getOrderGoodsInfo($condition = array(), $fields = '*', $order = '') {
        return $this->table('order_goods')->where($condition)->field($fields)->order($order)->find();
    }

    /**
     * 取得订单商品总数
     * @param unknown $condition
     * @param string $fields
     * @param string $order
     */
    public function getOrderGoodsSum($condition = array(),$fields = 'goods_num') {
    	return $this->table('order_goods')->where($condition)->sum($fields);
    }
    /**
     * 取得订单商品表列表
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     * @param string $page
     * @param string $order
     * @param string $group
     * @param string $key
     */
    public function getOrderGoodsList($condition = array(), $fields = '*', $limit = null, $page = null, $order = 'rec_id desc', $group = null, $key = null) {
        return $this->table('order_goods')->field($fields)->where($condition)->limit($limit)->order($order)->group($group)->key($key)->page($page)->select();
    }

    /**
     * 取得订单扩展表列表
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     */
    public function getOrderCommonList($condition = array(), $fields = '*', $order = '', $limit = null) {
        return $this->table('order_common')->field($fields)->where($condition)->order($order)->limit($limit)->select();
    }

    /**
     * 插入订单支付表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderPay($data) {
        return $this->table('order_pay')->insert($data);
    }

    /**
     * 插入订单表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrder($data) {
        $insert = $this->table('order')->insert($data);
        if ($insert) {
            //更新缓存
			if (C('cache_open')) {
                QueueClient::push('delOrderCountCache',array('buyer_id'=>$data['buyer_id'],'store_id'=>$data['store_id']));
			}
        }
        return $insert;
    }

    /**
     * 插入订单扩展表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderCommon($data) {
        return $this->table('order_common')->insert($data);
    }

    /**
     * 插入订单扩展表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderGoods($data) {
        return $this->table('order_goods')->insertAll($data);
    }

	/**
	 * 添加订单日志
	 */
	public function addOrderLog($data) {
	    $data['log_role'] = str_replace(array('buyer','seller','system','admin'),array('买家','商家','系统','管理员'), $data['log_role']);
	    $data['log_time'] = TIMESTAMP;
	    return $this->table('order_log')->insert($data);
	}

	/**
	 * 更改订单信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editOrder($data,$condition,$limit = '') {
		$update = $this->table('order')->where($condition)->limit($limit)->update($data);
		if ($update) {
		    //更新缓存
			if (C('cache_open')) {
		        QueueClient::push('delOrderCountCache',$condition);
			}
		}
		return $update;
	}

	/**
	 * 更改订单扩展信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editOrderCommon($data,$condition) {
	    return $this->table('order_common')->where($condition)->update($data);
	}
	
	/**
	 * 更改订单商品信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editOrderGoods($data,$condition) {
	    return $this->table('order_goods')->where($condition)->update($data);
	}

	/**
	 * 更改订单支付信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editOrderPay($data,$condition) {
		return $this->table('order_pay')->where($condition)->update($data);
	}

	/**
	 * 订单操作历史列表
	 * @param unknown $order_id
	 * @return Ambigous <multitype:, unknown>
	 */
    public function getOrderLogList($condition) {
        return $this->table('order_log')->where($condition)->select();
    }
	
	/**
     * 取得单条订单操作记录
     * @param unknown $condition
     * @param string $order
     */
    public function getOrderLogInfo($condition = array(), $order = '') {
        return $this->table('order_log')->where($condition)->order($order)->find();
    }

    /**
     * 返回是否允许某些操作
     * @param unknown $operate
     * @param unknown $order_info
     */
    public function getOrderOperateState($operate,$order_info){

        if (!is_array($order_info) || empty($order_info)) return false;
		
		if (isset($order_info['if_'.$operate])) {
            return $order_info['if_'.$operate];
        }

        switch ($operate) {		   
            //买家取消订单
        	case 'buyer_cancel':
        		if(($order_info['order_state'] == ORDER_STATE_NEW) || ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY)){
        			$state =  true;
        		}else{
        			$state = false;
        		}
        	   break;

    	   //申请退款
    	   case 'refund_cancel':
    	       $state = $order_info['refund'] == 1 && !intval($order_info['lock_state']) ? true : false;
    	       break;

    	   //商家取消订单
    	   case 'store_cancel':
    	       if(($order_info['order_state'] == ORDER_STATE_NEW) || (in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND)))){
    	       	$state =  true;
    	       }else{
    	       	$state = false;
    	       }
    	       break;

           //平台取消订单
           case 'system_cancel':
	           	if(($order_info['order_state'] == ORDER_STATE_NEW) || ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY)){
	           		$state =  true;
	           	}else{
	           		$state = false;
	           	}
               break;

           //平台收款(下单后7天以内后台可以收款)
           case 'system_receive_pay':
		       $state = in_array($order_info['order_state'],array(ORDER_STATE_NEW,ORDER_STATE_CANCEL)) ? true : false;
               $state = $state && TIMESTAMP - $order_info['add_time'] < 604800 ? true : false;
               break;
        	//商家收款(下单后7天以内后台可以收款)
         	case 'seller_receive_pay':
       			$state = in_array($order_info['order_state'],array(ORDER_STATE_NEW,ORDER_STATE_CANCEL)) ? true : false;
               	$state = $state && ($order_info['payment_code'] == 'online' || $order_info['payment_code'] == 'xjpay' || $order_info['payment_code'] == 'skpay') ? true : false;
               	break;
	       //买家投诉
	       case 'complain':
	           $state = in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND)) ||
	               intval($order_info['finnshed_time']) > (TIMESTAMP - C('complain_time_limit')) ? true : false;
	           break;
		   //调整运费
		   case 'payment':
	           $state = $order_info['order_state'] == ORDER_STATE_NEW && $order_info['payment_code'] == 'online' ? true : false;
	           break;
            //调整价格
        	case 'modify_price':
        	    $state = ($order_info['order_state'] == ORDER_STATE_NEW) ||
        	       ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY) ? true : false;
        	    $state = floatval($order_info['shipping_fee']) > 0 && $state ? true : false;
        	   break;
			//商家发货
            case 'store_send':
                $state = !$order_info['lock_state'] && $order_info['order_state'] == ORDER_STATE_PAY ? true : false;
                break;
        	//发货
        	case 'send':
        	    $state = !$order_info['lock_state'] && $order_info['order_state'] == ORDER_STATE_PAY ? true : false;
        	    break;
        	//商家收货
     		case 'store_receive':
        		if(!$order_info['lock_state'] && $order_info['order_state'] == ORDER_STATE_PAY){
        	  		$state = true;
        	 	}else{
        	    	$state = false;
        	 	}
        		break;
        	//收货
    	    case 'receive':
	        if(!$order_info['lock_state'] && $order_info['order_state'] == ORDER_STATE_SEND){
			$state = true;
		}else{
    	        	$state = false;
		}
    	        break;

    	    //评价
    	    case 'evaluation':
    	        $state = !$order_info['lock_state'] && !$order_info['evaluation_state'] && $order_info['order_state'] == ORDER_STATE_SUCCESS ? true : false;
    	        break;

        	//锁定
        	case 'lock':
        	    $state = intval($order_info['lock_state']) ? true : false;
        	    break;

        	//快递跟踪
        	case 'deliver':
        	    $state = !empty($order_info['shipping_code']) && in_array($order_info['order_state'],array(ORDER_STATE_SEND,ORDER_STATE_SUCCESS)) ? true : false;
        	    break;

        	//放入回收站
        	case 'delete':
        	    $state = in_array($order_info['order_state'], array(ORDER_STATE_CANCEL,ORDER_STATE_SUCCESS)) && $order_info['delete_state'] == 0 ? true : false;
        	    break;

        	//永久删除、从回收站还原
        	case 'drop':
        	case 'restore':
        	    $state = in_array($order_info['order_state'], array(ORDER_STATE_CANCEL,ORDER_STATE_SUCCESS)) && $order_info['delete_state'] == 1 ? true : false;
        	    break;

        	//分享
        	case 'share':
        	    $state = true;
        	    break;

        }
        return $state;

    }
    
    /**
     * 联查订单表订单商品表
     *
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     * @return array
     */
    public function getOrderAndOrderGoodsList($condition, $field = '*', $page = 0, $order = 'rec_id desc') {
        return $this->table('order_goods,order')->join('inner')->on('order_goods.order_id=order.order_id')->where($condition)->field($field)->page($page)->order($order)->select();
    }
    
    /**
     * 订单销售记录 订单状态为20、30、40时
     * @param unknown $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getOrderAndOrderGoodsSalesRecordList($condition, $field="*", $page = 0, $order = 'rec_id desc') {
        $condition['order_state'] = array('in', array(ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS));
        return $this->getOrderAndOrderGoodsList($condition, $field, $page, $order);
    }
	
	/**
     * 取得其它订单类型的信息
     * @param unknown $order_info
     */
    public function getOrderExtendInfo(& $order_info) {
        //取得预定订单数据
        if ($order_info['order_type'] == 2) {
            $result = Logic('order_book')->getOrderBookInfo($order_info);
            //如果是未支付尾款
            if ($result['data']['if_buyer_repay']) {
                $result['data']['order_pay_state'] = false;
            }
            $order_info = $result['data'];
        }
    }
	
	/** ------------------------------推广处理----------------------------------------------//
	 * 统计导购/推广员总为业绩
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getStatisticsCommis($condition = array()) {
		//$statistics_info = $this->field('sum(order_amount) as order_amount, sum(goods_amount) as goods_amount')->where($condition)->find();
		$statistics_info['order_amount'] = $this->table('order')->where($condition)->sum('order_amount');
		$statistics_info['goods_amount'] = $this->table('order')->where($condition)->sum('goods_amount');
		$re_statistics = array();
		$re_statistics['amounts'] = ($statistics_info['goods_amount']>0)?$statistics_info['goods_amount']:0;
		$re_statistics['commis'] = ($statistics_info['order_amount']>0)?$statistics_info['order_amount']:0;
		return $re_statistics;
	}
	
	/** ------------------------------订单推送外部ERP处理----------------------------------------------//
	 * 统计导购/推广员总为业绩
	 *
	 * @param pay_sn 支付单号
	 * @return array 数组格式的返回结果
	 */
	public function push_ikjtao_order($pay_sn) {
		
		if(!empty($pay_sn)){
			$condition['pay_sn'] = $pay_sn;
			//查询出订单详情, 追加返回的表('order_common','order_goods') 
			$orderInfo = $this->getOrderInfo($condition,array('order_common','order_goods'));
			$goods_id = $orderInfo['extend_order_goods']['goods_id'];
			
			//定义该订单商品中是否存在跨境淘的商品
			$ikjt_arr = array();
			$order_goods = array();
			$goodsAmount = 0;
			$goodsPayAmount = 0;
			foreach ($orderInfo['extend_order_goods'] as $key=>$var){
				$goods_id = $var['goods_id'];
				$store_id = $var['store_id'];
				$mdl_goods = Model('goods');
				$goodsInfo = $mdl_goods->getGoodsInfo(array('goods_id'=>$goods_id),'goods_commonid,goods_serial,up_id,up_name');
				$up_id = $goodsInfo['up_id'];
				/*
				 * 1、判断商品商家的ID是否为跨境淘的ID
				 * 若是  则表示该订单存在跨境淘的商品
				 * 若不是 则 2、判断商品的上级 供应商的ID 是否为跨境淘的ID
				 * 若是  则表示该订单存在跨境淘的商品
				 * 若不是 则表示该订单不存在跨境淘的商品
				 */
				if($store_id == C('ikjtao_store_id')){
					$ikjt_arr[$goods_id] = true; //表示存在跨境淘的商品
					$order_goods[$key]['productName'] = $var['goods_name'];
					$order_goods[$key]['productNo'] = $goodsInfo['goods_serial'];
					$goodsAmount += imPriceFormat($var['goods_price']);//商品总金额
					$order_goods[$key]['price'] = $goodspayPrice = imPriceFormat($var['goods_pay_price']); //商品实际支付
					$goodsPayAmount += $goodspayPrice; //商品实际支付的总额
					$order_goods[$key]['qty'] = $var['goods_num'];
				}elseif(!empty($up_id) && $up_id>0 && $up_id == C('ikjtao_store_id')){
					$ikjt_arr[$goods_id] = true; //表示存在跨境淘的商品
					$order_goods[$key]['productName'] = $var['goods_name'];
					$order_goods[$key]['productNo'] = $goodsInfo['goods_serial'];
					$order_goods[$key]['price'] = $goodspayPrice = imPriceFormat($var['goods_pay_price']); //商品实际支付
					$goodsAmount += imPriceFormat($var['goods_price']); //商品总金额
					$goodsPayAmount += $goodspayPrice;//商品实际支付的总额
					$order_goods[$key]['qty'] = $var['goods_num'];
				}else{
					$ikjt_arr[$goods_id] = false; //表示存在跨境淘的商品
				}
			}
			//如果订单中存在其他店铺的商品，则只计算跨境淘的商品实际支付的总额
			if(in_array(false,$ikjt_arr)){
				$orderAmount = $goodsAmount;
				$disAmount = $goodsAmount - $goodsPayAmount;
				$payAmount = $goodsPayAmount;
			}else{
				$orderAmount = imPriceFormat($orderInfo['order_amount']);
				$disAmount = imPriceFormat($orderInfo['extend_order_common']['promotion_total']);
				$payAmount = imPriceFormat($orderInfo['order_amount']);
			}
			/**
			 * 判断该订单商品中是否存在跨境淘的商品，并且后台配置中跨境淘的商家ID不为空
			 */
			if(in_array(true,$ikjt_arr)&&C('ikjtao_store_id')>0){
				
					$reciver_info = $orderInfo['extend_order_common']['reciver_info'];
					$buyer_phone = $reciver_info['mob_phone'];
					$area_info = explode(" ",$reciver_info['area']);
					$province = $area_info[0];
					$city = $area_info[1];
					$district = $area_info[2];
					$address = $reciver_info['street'];
				$member_idcard = $reciver_info['member_idcard'];
				if(empty($member_idcard)){
					$memberInfo = Model('member')->getMemberInfoByID($orderInfo['buyer_id'],'member_idcard');
					$member_idcard = $memberInfo['member_idcard'];
					if(empty($member_idcard)){
						$member_idcard = Logic('connect_api')->getIdCard('1');
						Model('address')->editAddress(array('member_idcard'=>$member_idcard),array('address_id'=>$reciver_info['address_id']));
						$orderInfo['extend_order_common']['reciver_info']['member_idcard'] = $member_idcard;
						$up_reciver_info = serialize($orderInfo['extend_order_common']['reciver_info']);
						$this->editOrder(array('reciver_info'=>$up_reciver_info),array('order_id'=>$orderInfo['order_id']));
				}
				}
				$order_id = $orderInfo['order_id'];
				//组装跨境淘的信息
				$ikj_oInfo = array();
				// 是否含税 说明：为true goods=>price 含税，需保证运费为0 ； 为false 销售单价不含税
				$ikj_oInfo['dutyPaid'] = $dutyPaid = true;
				// 消费税 = 商品累计消费税之和
				$ikj_oInfo['consumptionDutyAmount'] = $consumptionDutyAmount = 0;
				// 增值税 = 商品累计增值税之和
				$ikj_oInfo['addedValueTaxAmount'] = $addedValueTaxAmount = 0;
				// 毛重
				$ikj_oInfo['grossWeight'] = $grossWeight = 0;
				// 保价费（无保价费时自动设置为0）
				$ikj_oInfo['insuranceFee'] = $insuranceFee = 0;
				// 关 税额（免税请设置0）
				$ikj_oInfo['tariffAmount'] = $tariffAmount = 0;
				// 订单号
				$ikj_oInfo['orderNo'] = $orderInfo['order_sn'];
				// 订单总金额=运费+关额+消费税+增值税+保费+商品金额总和
				$ikj_oInfo['orderAmount'] = $orderAmount = $goodsAmount;
				// 运费
				$ikj_oInfo['postage'] = $postage = 0;// $orderInfo['shipping_fee'];
				$payAmount = $payAmount - $orderInfo['shipping_fee'];
				// 税费合计
				$ikj_oInfo['taxAmount'] = $taxAmount = 0;
				// 优惠金额
				$ikj_oInfo['disAmount'] = imPriceFormat($disAmount);
				// 支付金额=订单总金额-优惠金额
				$ikj_oInfo['payAmount'] = imPriceFormat($payAmount);
				// 支付方式
				/**
				 * 01银联在线  是 *注1\02支付宝  是\03盛付通 是 \04建设银行 否\05中国银行 否\06易付宝 是*注1\07农业银行 否\08 京东网银在线 是
				 * 09 国际支付宝 是\10 甬易支付  *是注1\11 富友支付 是\12连连支付 是\13财付通（微信支付）是*注1\14快钱  否\15网易宝 是\16银盈通支付 是
				 * 17鄞州银行 否\18智惠支付\19拉卡拉 否\20北京银联 否\21杭州银行（网银）否\22	银联网络\23重庆易极付\24易宝支付\25广州银联	否
				 * 26上海银联\27通联支付 否\28首信易支付 否\29	浙江银商\30百度钱包  否 *注1
				 * *注1 使用该支付方式显示：“身份证姓名为空，支付单保存失败“情况，需提交消费者姓名和身份证
				 */
				 $payment_name = $orderInfo['payment_name'];
				 switch ($orderInfo['payment_code']) {
					case 'wxpay':
					$paymentMode = 13;
						break;
					case 'alipay':
					$paymentMode = 02;
						break;
					case 'llpay':
					$payment_name = '连连支付';
					$paymentMode = 12;
						break;
					default:
					$payment_name = '百度钱包';
					$paymentMode = 30;
						break;
				}
				$ikj_oInfo['paymentMode'] = $paymentMode;
				// 交易流水号
				$ikj_oInfo['paymentNo'] = !empty($orderInfo['trade_no'])?$orderInfo['trade_no']:$pay_sn;
				// 支付订单号(没有则同orderNo)
				$ikj_oInfo['orderSeqNo'] = $pay_sn; 
				// 买家真实姓名
				$ikj_oInfo['name'] = $orderInfo['extend_order_common']['reciver_name'];
				// 买家真实身份证号，末位为X则大写
				$ikj_oInfo['idNum'] = strtoupper($member_idcard);
				// 下单时间 yyyy-MM-dd HH:mm:ss
				$ikj_oInfo['addTime'] = date('Y-m-d H:i:s',$orderInfo['add_time']);
				if($orderInfo['payment_time']<$orderInfo['add_time']){
					$orderInfo['payment_time'] = $orderInfo['add_time']+60;
				}
				//支付时间 yyyy-MM-dd HH:mm:ss
				$ikj_oInfo['payTime'] = date('Y-m-d H:i:s',$orderInfo['payment_time']);
				//买家账号
				$ikj_oInfo['buyerAccount'] = $buyerAccount = '';
				// 收件人
				$ikj_oInfo['consignee'] = $orderInfo['extend_order_common']['reciver_name'];
				// 收件人手机
				$ikj_oInfo['consigneeMobile'] = $buyer_phone;
				// 省份(北京、天津、上海、重庆直接填城市名)
				$ikj_oInfo['province'] = $province;
				// 城市
				$ikj_oInfo['city'] = $city;
				// 区县
				$ikj_oInfo['district'] = $district;
				// 街道地址(不包含省市区)
				$ikj_oInfo['consigneeAddr'] = $address;
				// 物流名称
				$ikj_oInfo['logisticsName'] = "顺丰速运";
				if(is_array($order_goods)){
					foreach ($order_goods as $okey => $goods){
						// 产品名称
						$ikj_oInfo['goods'][$okey]['productName'] = $goods['productName'];
						// 产品编码
						$ikj_oInfo['goods'][$okey]['productNo'] = $goods['productNo'];
						// 价格
						$ikj_oInfo['goods'][$okey]['price'] = $goods['price'];
						// 数量
						$ikj_oInfo['goods'][$okey]['qty'] = $goods['qty'];
					}
				}
				$res_customs = $this->_api_customs($ikj_oInfo,$orderInfo['payment_code']);
				if(empty($res_customs)){
					$ikj_oInfo['paymentNo'] = $res_customs['transaction_id'];
				}
				require(BASE_DATA_PATH.DS.'api'.DS.'ikjtao'.DS.'index.php');
				$ikjtao_api = new ikjtao_item();
				$ikjtao_result = $ikjtao_api->fetch($ikj_oInfo);
				//echo "<pre>";
				//print_r($ikj_oInfo);
				//echo "<br>";
				$result = FALSE;
				if($ikjtao_result) {
					echo "输出结果是：";print_r($ikjtao_result);
					$result = $ikjtao_result['retNum'];
					$retMsg = $ikjtao_result['retMsg'];
					$retData = $ikjtao_result['retData'];
					
					$data = array();
					$data['order_id'] = $order_id;
					$data['log_role'] = 'seller';
					$data['log_msg'] = "订单推送结果：".$retMsg;
					$data['log_user'] = "接口调用";
					$data['log_orderstate'] = 20;
					$res_log = $this->addOrderLog($data);
					echo $order_id."日志创建".$res_log;
				}
				return $result;
				
			}else{
				return false;
			}
		}
	}
	/**
	 * 第三方在线支付 海关订单推送接口
	 * 宁波海关特别说明：由于宁波海关的接口比较特殊，需要商户先调用我们的申报接口，然后商户再推送订单给宁波海关，这样宁波海关才会来我们系统取支付单信息；如果调用顺序反过来，海关则取不到支付信息。
	 */
	private function _api_customs($order_pay_info,$payment_code) {
		
		$inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_code.DS.'wxcustoms.php';
		if(!is_file($inc_file)){
			echo '支付接口不存在';
		}
		require($inc_file);
		$model_mb_payment = Model('mb_payment');
		$condition = array();
		$condition['payment_code'] = $payment_code;
		$mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition); //支付方式_待修改
		
		if($payment_code == 'wxpay'){
			$appid = $mb_payment_info['payment_config']['wxpay_appid']; //appid
			$secret = $mb_payment_info['payment_config']['wxpay_appsecret']; //app secret
			$mch_id = $mb_payment_info['payment_config']['wxpay_mchid']; //商户号
			$mchkey = $mb_payment_info['payment_config']['wxpay_mchkey'];//商户密钥
			$param = array();
			$param['sign_type'] = 'MD5';
			$param['service_version'] = '1.0';
			$param['input_charset'] = 'GBK';
			//$param['sign_key_index'] = '1';
			//业务参数
			$param['partner'] = $paraSign['partner'] = $mch_id; //商户号
			$param['out_trade_no'] = $paraSign['out_trade_no'] = $order_pay_info['orderSeqNo']; //商户系统内部的订单号 ==商城支付单号,
			$param['transaction_id'] = $paraSign['transaction_id'] = $order_pay_info['paymentNo']; //财付通交易号
			$param['sub_order_no'] = $order_pay_info['orderNo']; //商户子订单号，拆单时需上送。如果传了子单号，必须同时传以下几个参数：fee_type、order_fee、transport_fee、product_fee
			$param['fee_type'] = 'CNY';	//币种,暂只支持CNY
			$param['order_fee'] = $order_pay_info['orderAmount']*100; //应付金额:子单金额
			$param['transport_fee'] = $order_pay_info['taxAmount']*100; //物流费用
			$param['product_fee'] = $order_pay_info['payAmount']*100;//商品价格
			$param['duty'] = 0; //关税: 币种是人民币，以分为单位
			$param['customs'] = $paraSign['customs'] = C('ikjtao_customs'); //海关:0 无需上报海关1广州2杭州3宁波4深圳5郑州(保税物流中心)6重庆7西安8上海9 郑州（综保区）11 广州电子口岸（总署版）
			$param['mch_customs_no'] = $paraSign['mch_customs_no'] = C('ikjtao_customs_no'); //商户海关备案号 宁波富邦电子商务发展有限公司  customs非0，此参数必填
			$param['cert_type'] = $paraSign['cert_type'] = 1; //证件类型:暂只支持1身份证
			$param['cert_id'] = $paraSign['cert_id'] = $order_pay_info['idNum']; //证件号码
			$param['name'] = $paraSign['name'] = iconv("UTF-8", "GBK", $order_pay_info['name']); //姓名
			$param['action_type'] = $paraSign['action_type'] = 1; //操作类型 1 新增 2修改（暂只支持郑州综保区，郑州保税物流中心,广州，杭州，宁波）3 重新推送海关
			$param['business_type'] = 1; //业务类型:1 保税进口2 直邮进口如果不填，默认为1保税进口
			
			$api = new wxcustoms();
			$result = $api->declareorder($param,$mchkey,$paraSign);
		}
		return $result;
	}
}
