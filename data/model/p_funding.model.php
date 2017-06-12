<?php
/**
 * 限时折扣活动模型 
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
class p_fundingModel extends Model{

    const FUNDING_STATE_NORMAL = 1;
    const FUNDING_STATE_CLOSE = 2;
    const FUNDING_STATE_CANCEL = 3;

    private $funding_state_array = array(
        0 => '全部',
        self::FUNDING_STATE_NORMAL => '正常',
        self::FUNDING_STATE_CLOSE => '已结束',
        self::FUNDING_STATE_CANCEL => '管理员关闭'
    );

    public function __construct(){
        parent::__construct('p_funding');
    }

	/**
     * 读取限时折扣列表
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 所需字段
     * @return array 限时折扣列表
	 *
	 */
	public function getFundingList($condition, $page=null, $order='', $field='*') {
        $funding_list = $this->field($field)->where($condition)->page($page)->order($order)->select();
        if(!empty($funding_list)) {
            for($i =0, $j = count($funding_list); $i < $j; $i++) {
                $funding_list[$i] = $this->getFundingExtendInfo($funding_list[$i]);
            }
        }
        return $funding_list;
	}

    /**
	 * 根据条件读取限制折扣信息
	 * @param array $condition 查询条件
     * @return array 限时折扣信息
	 *
	 */
    public function getFundingInfo($condition) {
        $funding_info = $this->where($condition)->find();
        $funding_info = $this->getFundingExtendInfo($funding_info);
        return $funding_info;
    }

    /**
	 * 根据限时折扣编号读取限制折扣信息
	 * @param array $funding_id 限制折扣活动编号
	 * @param int $store_id 如果提供店铺编号，判断是否为该店铺活动，如果不是返回null
     * @return array 限时折扣信息
	 *
	 */
    public function getFundingInfoByID($funding_id, $store_id = 0) {
        if(intval($funding_id) <= 0) {
            return null;
        }

        $condition = array();
        $condition['funding_id'] = $funding_id;
        $funding_info = $this->getFundingInfo($condition);
        if($store_id > 0 && $funding_info['store_id'] != $store_id) {
            return null;
        } else {
            return $funding_info;
        }
    }

    /**
     * 限时折扣状态数组
     *
     */
    public function getFundingStateArray() {
        return $this->funding_state_array;
    }

	/*
	 * 增加 
	 * @param array $param
	 * @return bool
     *
	 */
    public function addFunding($param){
        $param['state'] = self::FUNDING_STATE_NORMAL;
        return $this->insert($param);	
    }

    /*
	 * 更新
	 * @param array $update
	 * @param array $condition
	 * @return bool
     *
	 */
    public function editFunding($update, $condition){
        return $this->where($condition)->update($update);
    }

	/*
	 * 删除限时折扣活动，同时删除限时折扣商品
	 * @param array $condition
	 * @return bool
     *
	 */
    public function delFunding($condition){
        $funding_list = $this->getFundingList($condition);
        $funding_id_string = '';
        if(!empty($funding_list)) {
            foreach ($funding_list as $value) {
                $funding_id_string .= $value['funding_id'] . ',';
            }
        }

        //删除限时折扣商品
        if($funding_id_string !== '') {
            $model_funding_goods = Model('p_funding_goods');
            $model_funding_goods->delFundingGoods(array('funding_id'=>array('in', $funding_id_string)));
        }

        return $this->where($condition)->delete();
    }

	/*
	 * 取消限时折扣活动，同时取消限时折扣商品 
	 * @param array $condition
	 * @return bool
     *
	 */
    public function cancelFunding($condition){
        $funding_list = $this->getFundingList($condition);
        $funding_id_string = '';
        if(!empty($funding_list)) {
            foreach ($funding_list as $value) {
                $funding_id_string .= $value['funding_id'] . ',';
            }
        }

        $update = array();
        $update['state'] = self::FUNDING_STATE_CANCEL;

        //删除限时折扣商品
        if($funding_id_string !== '') {
            $model_funding_goods = Model('p_funding_goods');
            $model_funding_goods->editFundingGoods($update, array('funding_id'=>array('in', $funding_id_string)));
        }

        return $this->editFunding($update, $condition);
    }

    /**
     * 获取限时折扣扩展信息，包括状态文字和是否可编辑状态
     * @param array $funding_info
     * @return string
     *
     */
    public function getFundingExtendInfo($funding_info) {
        if($funding_info['end_time'] > TIMESTAMP) {
            $funding_info['funding_state_text'] = $this->funding_state_array[$funding_info['state']];
        } else {
            $funding_info['funding_state_text'] = '已结束';
        }

        if($funding_info['state'] == self::FUNDING_STATE_NORMAL && $funding_info['end_time'] > TIMESTAMP) {
            $funding_info['editable'] = true;
        } else {
            $funding_info['editable'] = false;
        }

        return $funding_info;
    }

    /**
     * 过期修改状态
     */
    public function editExpireFunding($condition) {
        $condition['end_time'] = array('lt', TIMESTAMP);
        
        // 更新商品促销价格
        $fundinggoods_list = Model('p_funding_goods')->getFundingGoodsList($condition);
        if (!empty($fundinggoods_list)) {
            $goodsid_array = array();
            foreach ($fundinggoods_list as $val) {
                $goodsid_array[] = $val['goods_id'];
            }
            // 更新商品促销价格，需要考虑抢购是否在进行中
            QueueClient::push('updateGoodsPromotionPriceByGoodsId', $goodsid_array);
        }
        $condition['state'] = self::FUNDING_STATE_NORMAL;
        
        $updata = array();
        $update['state'] = self::FUNDING_STATE_CLOSE;
        $this->editFunding($update, $condition);
        return true;
    }

}
