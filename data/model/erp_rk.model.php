<?php
/**
 * 管理
 * 
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class erp_rkModel extends Model {

	public function __construct(){
		parent::__construct('erp_rk_bill');
	}
	/**
     * 取单条入库信息
     *
     * @param unknown_type $condition
     * @param array $extend 追加返回那些表的信息,如array('rk_goods','store')
     * @return unknown
     */
    public function getRKInfo($condition = array(), $extend = array(), $fields = '*', $order = '',$group = '') {
        $rk_info = $this->table('erp_rk_bill')->field($fields)->where($condition)->group($group)->order($order)->find();
        if (empty($rk_info)) {
            return array();
        }
        if (isset($rk_info['rk_state'])) {
            $rk_info['state_desc'] = $this->rkState($rk_info);
        }
        //追加返回店铺信息
        if (in_array('store',$extend)) {
            $rk_info['extend_store'] = Model('store')->getStoreInfo(array('store_id'=>$rk_info['store_id']));
        }

        //返回导购信息
        if (in_array('seller',$extend)) {
            $rk_info['extend_seller'] = Model('seller')->getSellerInfo(array('seller_id'=>$rk_info['seller_id']));
        }

        //追加返回商品信息
        if (in_array('rk_goods',$extend)) {
            //取商品列表
            $rk_goods_list = $this->getRKGoodsList(array('rk_id'=>$rk_info['rk_id']));//,'goods_type'=>array('neq',5)
            $rk_info['extend_rk_goods'] = $rk_goods_list;
			
        }
        $rk_info['goods_total'] = $this->getRKGoodsSum(array('rk_id'=>$rk_info['rk_id']));//入库商品总数
        return $rk_info;
    }
	
    /**
     * 取得订单状态文字输出形式
     *
     * @param array $rk_info 订单数组
     * @return string $order_state 描述输出
     */
    function rkState($rk_info) {
    	switch ($rk_info['order_state']) {
    		case 0:
    			$rk_state = "已取消";
    			break;
    		case 10:
    			$rk_state = "待复核";
    			break;
    		case 11:
    			$rk_state = "已复核";
    			break;
    		case 20:
    			$rk_state = "已审核";
    			break;
    		case 30:
    			$rk_state = "已入库";
    			break;
    	}
    	return $rk_state;
    }
    
	/**
     * 取得入库列表(未被删除)
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getNormalRKBillList($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array()){
        $condition['delete_state'] = 0;
        return $this->getRKBillList($condition, $pagesize, $field, $order, $limit, $extend);
    }

    /**
     * 取得入库列表(所有)
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getRKBillList($condition, $pagesize = '', $field = '*', $order = 'rk_id desc', $limit = '', $extend = array(), $master = false){
        $list = $this->table('erp_rk_bill')->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->master($master)->select();
        if (empty($list)) return array();
        $rk_list = array();
        foreach ($list as $order) {
        	if (isset($order['rk_state'])) {				
                $order['state_desc'] = rkState($order['rk_state']);
            }
        	if (!empty($extend)) $rk_list[$order['rk_id']] = $order;
        }
        if (empty($rk_list)) $rk_list = $list;

        //追加返回店铺信息
        if (in_array('store',$extend)) {
            $store_id_array = array();
            foreach ($rk_list as $value) {
            	if (!in_array($value['store_id'],$store_id_array)) $store_id_array[] = $value['store_id'];
            }
            $store_list = Model('store')->getStoreList(array('store_id'=>array('in',$store_id_array)));
            $store_new_list = array();
            foreach ($store_list as $store) {				
            	$store_new_list[$store['store_id']] = $store;
            }
            foreach ($rk_list as $order_id => $order) {
                $rk_list[$order_id]['extend_store'] = $store_new_list[$order['store_id']];
            }
        }

        //追加返回买家信息
		if (in_array('seller',$extend)) {
            foreach ($rk_list as $order_id => $order) {
            	$rk_list[$order_id]['extend_seller'] = Model('seller')->getSellerInfo(array('seller_id'=>$order['seller_id']));
            }
        }        

        //追加返回商品信息
        if (in_array('rk_goods',$extend)) {
            //取商品列表
            $rk_goods_list = $this->getRKGoodsList(array('rk_id'=>array('in',array_keys($rk_list))));//,'goods_type'=>array('neq',5)
            if (!empty($rk_goods_list)) {
                foreach ($rk_goods_list as $value) {
                    $rk_list[$value['rk_id']]['extend_rk_goods'][] = $value;
					$rk_list[$value['rk_id']]['goods_number'] = $this->getRKGoodsSum(array('rk_id'=>$value['rk_id']));//入库商品总数
                }
            } else {
                $rk_list[$value['rk_id']]['extend_rk_goods'] = array();
            }	
        }

        return $rk_list;
    }
	
    /**
     * 待复核入库数量
     * @param unknown $condition
     */
    public function getRKStateNewCount($condition = array()) {
        $condition['rk_state'] = 10;
        return $this->getRKBillCount($condition);
    }

    /**
     * 待记账的入库数量
     * @param unknown $condition
     */
    public function getRKStateTradeCount($condition = array()) {
        $condition['rk_state'] = array(array('neq',0),array('neq',30),'and');
        return $this->getRKBillCount($condition);
    }
    /**
     * 取得入库数量
     * @param unknown $condition
     */
    public function getRKBillCount($condition) {
        return $this->table('erp_rk_bill')->where($condition)->count();
    }

    /**
     * 取得入库商品表详细信息
     * @param unknown $condition
     * @param string $fields
     * @param string $order
     */
    public function getRKGoodsInfo($condition = array(), $fields = '*', $order = '') {
        return $this->table('erp_rk_goods')->where($condition)->field($fields)->order($order)->find();
    }

    /**
     * 取得入库商品总数
     * @param unknown $condition
     * @param string $fields
     * @param string $order
     */
    public function getRKGoodsSum($condition = array(),$fields = 'goods_num') {
    	return $this->table('erp_rk_goods')->where($condition)->sum($fields);
    }
    
    /**
     * 取得入库商品表列表
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     * @param string $page
     * @param string $order
     * @param string $group
     * @param string $key
     */
    public function getRKGoodsList($condition = array(), $fields = '*', $limit = null, $page = null, $order = 'rec_id desc', $group = null, $key = null) {
        return $this->table('erp_rk_goods')->field($fields)->where($condition)->limit($limit)->order($order)->group($group)->key($key)->page($page)->select();
    }

    /**
     * 插入入库表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addRKBill($data) {
        $insert = $this->table('erp_rk_bill')->insert($data);
        if ($insert) {
            //更新缓存
			if (C('cache_open')) {
                QueueClient::push('delRKCountCache',array('seller_id'=>$data['seller_id'],'store_id'=>$data['store_id']));
			}
        }
        return $insert;
    }

    /**
     * 删除(买/卖家)订单全部数量缓存
     * @param string $type 买/卖家标志，允许传入 buyer、store
     * @param int $id   买家ID、店铺ID
     * @return bool
     */
    public function delRKCountCache($type, $id) {
    	if (!C('cache_open')) return true;
    	$ins = Cache::getInstance('cacheredis');
    	$type = 'rkcount'.$type;
    	return $ins->hdel($id,$type);
    }
    
    /**
     * 插入入库扩展表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addRKGoods($data) {
        return $this->table('erp_rk_goods')->insertAll($data);
    }

	/**
	 * 添加入库日志
	 */
	public function addRKLog($data) {
	    $data['log_role'] = str_replace(array('store','seller','system','admin'),array('商家','店员','系统','管理员'), $data['log_role']);
	    $data['log_time'] = TIMESTAMP;
	    return $this->table('erp_rk_log')->insert($data);
	}

	/**
	 * 更改入库信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editRKBill($data,$condition,$limit = '') {
		$update = $this->table('erp_rk_bill')->where($condition)->limit($limit)->update($data);
		if ($update) {
		    //更新缓存
			if (C('cache_open')) {
		        QueueClient::push('delRKCountCache',$condition);
			}
		}
		return $update;
	}
	
	/**
	 * 更改入库商品信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editRKGoods($data,$condition) {
	    return $this->table('erp_rk_goods')->where($condition)->update($data);
	}

	/**
	 * 入库操作历史列表
	 * @param unknown $order_id
	 * @return Ambigous <multitype:, unknown>
	 */
    public function getRKLogList($condition) {
        return $this->table('rk_log')->where($condition)->select();
    }
	
	/**
     * 取得单条入库操作记录
     * @param unknown $condition
     * @param string $order
     */
    public function getRKLogInfo($condition = array(), $order = '') {
        return $this->table('rk_log')->where($condition)->order($order)->find();
    }
   
    /**
     * 联查入库表入库商品表
     *
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     * @return array
     */
    public function getRKAndRKGoodsList($condition, $field = '*', $page = 0, $order = 'rec_id desc') {
        return $this->table('erp_rk_goods,erp_rk_bill')->join('inner')->on('rk_goods.rk_id=rk.rk_id')->where($condition)->field($field)->page($page)->order($order)->select();
    }
    
    /**
     * 入库销售记录 入库状态为11、20、30时
     * @param unknown $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getRKAndRKGoodsSalesRecordList($condition, $field="*", $page = 0, $order = 'rec_id desc') {
        $condition['rk_state'] = array('in', array(11, 20, 30));
        return $this->getRKAndRKGoodsList($condition, $field, $page, $order);
    }
	
}
