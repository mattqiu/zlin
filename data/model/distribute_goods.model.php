<?php
/**
 * 微店商品
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class distribute_goodsModel extends Model{
    
	/**
     * 微店商品列表
     *
     * @param array $condition
     * @param treing $field
     * @param int $page
     * @param string $order
     * @return array
     */
    public function getDistributeGoodsList($condition, $field = '*', $page = 0 , $order = 'dis_id desc', $limit = 0) {
        return $this->table('distribute_goods')->where($condition)->order($order)->page($page)->limit($limit)->select();
    }

    /**
     * 取单个微店商品的内容
     *
     * @param array $condition 查询条件
     * @return array 数组类型的返回结果
     */
    public function getOneDistributeGoods($condition) {
        return $this->table('distribute_goods')->where($condition)->find();
    }

    /**
     * 获取商品微店收藏数
     *
     * @param int $storeId
     *
     * @return int
     */
    public function getGoodsDistributeCountByGoodsId($goods_id, $member_id = 0)
    {
        $where = array(
            'goods_id' => $goods_id,
        );

        if ($member_id > 0) {
            $where['member_id'] = (int) $member_id;
        }

        return (int) $this->table('distribute_goods')->where($where)->count();
    }	

	/**
     * 获取微店商品数
     *
     * @param int $storeId
     *
     * @return int
     */
    public function getMicroshopGoodsCount($member_id = 0)
    {
        $where = array(
            'member_id' => $member_id,
        );
        return (int) $this->table('distribute_goods')->where($where)->count();
    }

    /**
     * 新增微店商品
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function addDistributeGoods($param) {
        if (empty($param)) {
            return false;
        }
        $goods_id = intval($param['goods_id']);
        $model_goods = Model('goods');
        $fields = 'goods_id,store_id,goods_name,goods_image,goods_price,goods_promotion_price';
        $goods = $model_goods->getGoodsInfoByID($goods_id,$fields);
        $param['goods_name'] = $goods['goods_name'];
        $param['goods_image'] = $goods['goods_image'];
        $param['dis_price'] = $goods['goods_promotion_price'];//商品收藏时价格
        $param['dis_msg'] = $goods['goods_promotion_price'];//收藏备注，默认为收藏时价格，可修改
        $param['gc_id'] = $goods['gc_id'];

        $store_id = intval($goods['store_id']);
        $model_store = Model('store');
        $store = $model_store->getStoreInfoByID($store_id);
		$param['store_id'] = $store['store_id'];
        $param['store_name'] = $store['store_name'];        
        $param['sc_id'] = $store['sc_id'];
		
		$param['dis_time'] = TIMESTAMP;

        return $this->table('distribute_goods')->insert($param);
    }

    /**
     * 修改记录
     *
     * @param
     * @return bool
     */
    public function editDistributeGoods($condition, $data) {
        if (empty($condition)) {
            return false;
        }
        if (is_array($data)) {
            $result = $this->table('distribute_goods')->where($condition)->update($data);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 删除
     *
     * @param array $condition 查询条件
     * @return bool 布尔类型的返回结果
     */
    public function delDistributeGoods($condition) {
        if (empty($condition)) {
            return false;
        }
        return $this->table('distribute_goods')->where($condition)->delete();
    }
}