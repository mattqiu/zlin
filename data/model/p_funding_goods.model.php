<?php
/**
 * 限时折扣活动商品模型 
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
class p_funding_goodsModel extends Model{

    const FUNDING_GOODS_STATE_CANCEL = 0;
    const FUNDING_GOODS_STATE_NORMAL = 1;

    public function __construct(){
        parent::__construct('p_funding_goods');
    }

	/**
	 * 读取限时折扣商品列表
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 所需字段
     * @param int $limit 个数限制
     * @return array 限时折扣商品列表
	 *
	 */
	public function getFundingGoodsList($condition, $page=null, $order='', $field='*', $limit = 0) {
        return $funding_goods_list = $this->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
	}

	/**
	 * 读取限时折扣商品列表
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 所需字段
     * @param int $limit 个数限制
     * @return array 限时折扣商品列表
	 *
	 */
	public function getFundingGoodsExtendList($condition, $page=null, $order='', $field='*', $limit = 0) {
        $funding_goods_list = $this->getFundingGoodsList($condition, $page, $order, $field, $limit);
        if(!empty($funding_goods_list)) {
            for($i=0, $j=count($funding_goods_list); $i < $j; $i++) {
                $funding_goods_list[$i] = $this->getFundingGoodsExtendInfo($funding_goods_list[$i]);
            }
        }
        return $funding_goods_list;
	}

    /**
	 * 根据条件读取限制折扣商品信息
	 * @param array $condition 查询条件
     * @return array 限时折扣商品信息
	 *
	 */
    public function getFundingGoodsInfo($condition) {
        $result = $this->where($condition)->find();
        return $result;
    }

    /**
	 * 根据限时折扣商品编号读取限制折扣商品信息
	 * @param int $funding_goods_id
     * @return array 限时折扣商品信息
	 *
	 */
    public function getFundingGoodsInfoByID($funding_goods_id, $store_id = 0) {
        if(intval($funding_goods_id) <= 0) {
            return null;
        }

        $condition = array();
        $condition['funding_goods_id'] = $funding_goods_id;
        $funding_goods_info = $this->getFundingGoodsInfo($condition);

        if($store_id > 0 && $funding_goods_info['store_id'] != $store_id) {
            return null;
        } else {
            return $funding_goods_info;
        }
    }

    /**
     * 增加限时折扣商品 
     * @param array $funding_goods_info
     * @return bool
     *
     */
    public function addFundingGoods($funding_goods_info){
        $funding_goods_info['state'] = self::FUNDING_GOODS_STATE_NORMAL;
        $funding_goods_id = $this->insert($funding_goods_info);
        
        // 删除商品限时折扣缓存
        $this->_dGoodsFundingCache($funding_goods_info['goods_id']);
        
        $funding_goods_info['funding_goods_id'] = $funding_goods_id;
        $funding_goods_info = $this->getFundingGoodsExtendInfo($funding_goods_info);
        return $funding_goods_info;
    }

    /**
     * 更新
     * @param array $update
     * @param array $condition
     * @return bool
     *
     */
    public function editFundingGoods($update, $condition){
        $result = $this->where($condition)->update($update);
        if ($result) {
            $funding_goods_list = $this->getFundingGoodsList($condition, null, '', 'goods_id');
            if (!empty($funding_goods_list)) {
                foreach ($funding_goods_list as $val) {
                    // 删除商品限时折扣缓存
                    $this->_dGoodsFundingCache($val['goods_id']);
                    // 插入对列 更新促销价格
                    QueueClient::push('updateGoodsPromotionPriceByGoodsId', $val['goods_id']);
                }
            }
        }
        return $result;
    }

    /**
     * 删除
     * @param array $condition
     * @return bool
     *
     */
    public function delFundingGoods($condition){
        $funding_goods_list = $this->getFundingGoodsList($condition, null, '', 'goods_id');
        $result = $this->where($condition)->delete();
        if ($result) {
            if (!empty($funding_goods_list)) {
                foreach ($funding_goods_list as $val) {
                    // 删除商品限时折扣缓存
                    $this->_dGoodsFundingCache($val['goods_id']);
                    // 插入对列 更新促销价格
                    QueueClient::push('updateGoodsPromotionPriceByGoodsId', $val['goods_id']);
                }
            }
        }
        return $result;
    }

    /**
     * 获取限时折扣商品扩展信息
     * @param array $funding_info
     * @return array 扩展限时折扣信息
     *
     */
    public function getFundingGoodsExtendInfo($funding_info) {
        $funding_info['goods_url'] = urlShop('goods', 'index', array('goods_id' => $funding_info['goods_id']));
        $funding_info['image_url'] = cthumb($funding_info['goods_image'], 60, $funding_info['store_id']);
        $funding_info['funding_price'] = imPriceFormat($funding_info['funding_price']);
        $funding_info['funding_discount'] = number_format($funding_info['funding_price'] / $funding_info['goods_price'] * 10, 1).'折';
        return $funding_info;
    }

    /**
     * 获取推荐限时折扣商品
     * @param int $count 推荐数量
     * @return array 推荐限时活动列表
     *
     */
    public function getFundingGoodsCommendList($count = 4) {
        $condition = array();
        $condition['state'] = self::FUNDING_GOODS_STATE_NORMAL;
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        $funding_list = $this->getFundingGoodsExtendList($condition, null, 'funding_recommend desc', '*', $count);
        return $funding_list;
    }

    /**
     * 根据商品编号查询是否有可用限时折扣活动，如果有返回限时折扣活动，没有返回null
     * @param int $goods_id
     * @return array $funding_info
     *
     */
    public function getFundingGoodsInfoByGoodsID($goods_id) {
        $info = $this->_rGoodsFundingCache($goods_id);
        if(empty($info)) {
            $condition['state'] = self::FUNDING_GOODS_STATE_NORMAL;
            $condition['end_time'] = array('gt', TIMESTAMP);
            $condition['goods_id'] = $goods_id;
            $funding_goods_list = $this->getFundingGoodsExtendList($condition, null, 'start_time asc', '*', 1);
            $info['info'] = serialize($funding_goods_list[0]);
            $this->_wGoodsFundingCache($goods_id, $info);
        }
        $funding_goods_info = unserialize($info['info']);
        if (!empty($funding_goods_info) && ($funding_goods_info['start_time'] > TIMESTAMP || $funding_goods_info['end_time'] < TIMESTAMP)) {
            $funding_goods_info = array();
        }
        return $funding_goods_info;
    }

    /**
     * 根据商品编号查询是否有可用限时折扣活动，如果有返回限时折扣活动，没有返回null
     * @param string $goods_string 商品编号字符串，例：'1,22,33'
     * @return array $funding_goods_list
     *
     */
    public function getFundingGoodsListByGoodsString($goods_string) {
        $funding_goods_list = $this->_getFundingGoodsListByGoods($goods_string);
        $funding_goods_list = array_under_reset($funding_goods_list, 'goods_id');
        return $funding_goods_list;
    }

    /**
     * 根据商品编号查询是否有可用限时折扣活动，如果有返回限时折扣活动，没有返回null
     * @param string $goods_id_string
     * @return array $funding_info
     *
     */
    private function _getFundingGoodsListByGoods($goods_id_string) {
        $condition = array();
        $condition['state'] = self::FUNDING_GOODS_STATE_NORMAL;
        $condition['start_time'] = array('lt', TIMESTAMP);
        $condition['end_time'] = array('gt', TIMESTAMP);
        $condition['goods_id'] = array('in', $goods_id_string);
        $funding_goods_list = $this->getFundingGoodsExtendList($condition, null, 'funding_goods_id desc', '*');
        return $funding_goods_list;
    }
    
    /**
     * 读取商品限时折扣缓存
     * @param int $goods_id
     * @return array/bool
     */
    private function _rGoodsFundingCache($goods_id) {
        return rcache($goods_id, 'goods_funding');
    }
    
    /**
     * 写入商品限时折扣缓存
     * @param int $goods_id
     * @param array $info
     * @return boolean
     */
    private function _wGoodsFundingCache($goods_id, $info) {
        return wcache($goods_id, $info, 'goods_funding');
    }
    
    /**
     * 删除商品限时折扣缓存
     * @param int $goods_id
     * @return boolean
     */
    private function _dGoodsFundingCache($goods_id) {
        return dcache($goods_id, 'goods_funding');
    }
}