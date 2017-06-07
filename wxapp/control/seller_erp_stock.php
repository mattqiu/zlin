<?php
/**
 * 库存管理
 *
 * @copyright  Copyright (c) 2007-2017 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class seller_erp_stockControl extends BaseSellerControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 商品库存列表
     */
    public function goods_stockOp() {
    	
    	$keyword = $_REQUEST['keyword'];
    	$goods_type = $_REQUEST['goods_type'];
    	$search_type = $_REQUEST['search_type'];
    	
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $this->store_id;
        if (trim($keyword) != '') {
            switch ($search_type) {
                case 0:
                    $condition['goods_name'] = array('like', '%' . trim($keyword) . '%');
                    break;
                case 1:
                    $condition['goods_serial'] = array('like', '%' . trim($keyword) . '%');
                    break;
                case 2:
                    $condition['goods_commonid'] = intval($keyword);
                    break;
                default:
                	$condition['goods_name|goods_serial|goods_jingle|goods_barcode'] = array('like', '%' . $_REQUEST['keywords'] . '%');
                	break;
            }
        }
        
        $fields = 'goods_id,goods_commonid,goods_name,goods_serial,goods_price,goods_marketprice,goods_addtime,goods_image,goods_storage';
        switch ($goods_type) {
            case 'lockup':
                $goods_list = $model_goods->getGoodsOnlineList($condition, $fields, $this->page);
                break;
            case 'offline':
                $goods_list = $model_goods->getGoodsOnlineList($condition, $fields, $this->page);
                break;
            default:
                $goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, $fields,'goods_commend desc,goods_edittime desc,goods_addtime desc', $this->page);
                break;
        }

        // 计算库存
        $storage_array = $model_goods->calculateStorage($goods_list);
        $goods_list['store_storage'] = $store_storage = $model_goods->getGoodsSum(array('store_id' => $this->store_id), 'goods_storage');
        // 整理输出的数据格式
        foreach ($goods_list as $key => $value) {
        	$where = array('goods_commonid' => $value['goods_commonid'], 'store_id' => $this->store_id);
        	$goods_list[$key]['gcommon_storage'] = $gcommon_storage = $model_goods->getGoodsSum($where, 'goods_storage');
        	$goods_list[$key]['goods_prop'] = empty($gcommon_storage)? 0 : sprintf("%.2f",($gcommon_storage/$store_storage)*100);
            $goods_list[$key]['goods_addtime'] = date('Y-m-d', $goods_list[$key]['goods_addtime']);
            $goods_list[$key]['goods_image'] = cthumb($goods_list[$key]['goods_image']);
            $goods_list[$key]['goods_serial'] = empty($value['goods_serial'])?'无货号':$value['goods_serial'];
            $goods_list[$key]['goods_storage'] = empty($value['goods_storage'])?0:$value['goods_storage'];
        }

        $page_count = $model_goods->gettotalpage();

        output_data(array('goods_list' => $goods_list),'成功加载商品列表！', wxapp_page($page_count));
    }
    /**
     * 商品具体SKU列表
     */
    public function goods_skuOp() {
    	$goods_commonid = $_REQUEST['goods_commonid'];    	 
    	$model_goods = Model('goods');
    	$condition = array();
    	$condition['store_id'] = $this->store_id;
    	$condition['goods_commonid'] = $goods_commonid;
    	$fields = 'goods_id,goods_name,goods_serial,goods_barcode,goods_price,goods_marketprice,goods_addtime,goods_storage,goods_spec,spec_name';
    	$goods_list = $model_goods->getGoodsOnlineList($condition, $fields, $this->page);
    	if(!empty($goods_list) && !empty($goods_commonid)){
    	// 整理输出的数据格式
    	foreach ($goods_list as $key => $value) {
    		$where = array('goods_commonid' => $value['goods_commonid'], 'store_id' => $this->store_id);
    		$goods_list[$key]['gcommon_storage'] = $gcommon_storage = $model_goods->getGoodsSum($where, 'goods_storage');
    		$goods_list[$key]['goods_prop'] = empty($gcommon_storage)? 0 : sprintf("%.2f",($gcommon_storage/$store_storage)*100);
    		$goods_list[$key]['goods_addtime'] = date('Y-m-d', $goods_list[$key]['goods_addtime']);
    		$goods_list[$key]['goods_image'] = cthumb($goods_list[$key]['goods_image']);
    		$goods_list[$key]['goods_serial'] = empty($value['goods_serial'])?'无货号':$value['goods_serial'];
    		$goods_list[$key]['goods_barcode'] = empty($value['goods_barcode'])?'无条码':$value['goods_barcode'];
    		$goods_list[$key]['goods_storage'] = empty($value['goods_storage'])?0:$value['goods_storage'];
    		$goods_list[$key]['spec_name'] = unserialize($value['spec_name']);
    		$goods_list[$key]['goods_spec'] = unserialize($value['goods_spec']);
    	}
    	$page_count = $model_goods->gettotalpage();
    	output_data(array('sku_list' => $goods_list),'成功加载商品SKU！', wxapp_page($page_count));
    	}else{
    		output_error("该商品没有更多的SKU".$goods_commonid);
    	}
    }
	
}