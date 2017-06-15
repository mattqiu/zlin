<?php
/**
 * 订单管理
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class orderingModel extends Model {

    public $count;//记录总条数
    public $totalPage;//记录总页数

	/**
     * 取订单信息
     *
     * @param unknown_type $condition
     * @param array $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return unknown
     */
    public function getOrderingInfo($condition = array(),$fields = '*', $order = '') {
        $order_info = $this->table('ordering')->field($fields)->where($condition)->order($order)->select();
        if (empty($order_info)) {
            return array();
        }
        return $order_info;
    }


    /**
     * 取得订货订单商品列表(按照商品公共id进行分组)
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getOrderingList($condition, $pagesize = '', $field = '*',$group = '', $order = 'order_id desc', $limit = '', $extend = array(), $master = false){
        //获取订货订单商品信息
        $list = $this->table('ordering_goods')->field($field)->where($condition)->page($pagesize)->group($group)->order($order)->limit($limit)->master($master)->select();

        //获取总条数
        $this->count = count($this->table('ordering_goods')->field($field)->where($condition)->group($group)->order($order)->limit($limit)->master($master)->select());

        //获取总页数
        if(!$pagesize){//pagesize不存在时表示获取所有数据
            $this->totalPage = 1;//默认页码为1
        }else{
           $this->totalPage = ceil($this->count/ $pagesize); 
        }
        

        if (empty($list)) return array();
        $order_list = array();
        if (empty($order_list)) $order_list = $list;

        
        //追加返回商品公众表信息
        if (in_array('goods_common',$extend)) {
            $goods_commonid_array = array();
            foreach ($order_list as $value) {
                if (!in_array($value['goods_commonid'],$goods_commonid_array)) $goods_commonid_array[] = $value['goods_commonid'];
            }
            $goods_common_list = Model('goods_common')->getGoodsCommonList(array('goods_commonid'=>array('in',$goods_commonid_array)));
            $goods_common_new_list = array();
            foreach ($goods_common_list as $goods_common) {               
                $goods_common_new_list[$goods_common['goods_commonid']] = $goods_common['goods_name'];
            }
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['goods_common_name'] = $goods_common_new_list[$order['goods_commonid']];
            }
        }

        return $order_list;
    }
    /**
     * 添加goods表订单信息
     *
     * @param unknown_type $condition
     * @param array $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return unknown
     */
    public function add_Ordering_Goods($data) {
    	
    	$insert = $this->table('ordering_goods')->insert($data);
    	
    	return $insert;
    }
    /**
     * 添加goods_common表订单信息
     *
     * @param unknown_type $condition
     * @param array $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return unknown
     */
    public function add_Ordering($param) {
    	
    	$insert = $this->table('ordering')->insert($data);
    	
    	return $insert;
    }
    /**
     * 更新ordering_goods表订单信息
     *
     * @param unknown_type $condition
     * @param array $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return unknown
     */
    public function addd_Ordering_Goods($data) {
    	 
    	$updata = $this->table('ordering_goods')->updata($data);
    	 
    	return $updata;
    }

}
