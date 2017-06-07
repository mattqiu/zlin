<?php
/**
 * 店铺商品搜索
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

class store_searchControl extends BaseStoreControl {
	public function __construct(){
		parent::__construct();
		
		// 浏览过的商品
        $viewed_goods = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'],10);
        Tpl::output('viewed_goods',$viewed_goods);
	}
	
	public function indexOp(){
		$condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
        if (trim($_GET['keyword']) != '') {
            $condition['goods_name'] = array('like', '%'.trim($_GET['keyword']).'%');
        }

		// 排序
        $order = $_GET['order'] == 1 ? 'asc' : 'desc';
		switch (trim($_GET['key'])){
			case '1':
				$order = 'goods_id '.$order;
				break;
			case '2':
				$order = 'goods_promotion_price '.$order;
				break;
			case '3':
				$order = 'sum(goods_salenum) '.$order;
				break;
			case '4':
				$order = 'sum(goods_collect) '.$order;
				break;
			case '5':
				$order = 'sum(goods_click) '.$order;
				break;
			default:
				$order = 'goods_id desc';
				break;
		}

		//查询分类下的子分类
		if (intval($_GET['stc_id']) > 0){
		    $condition['goods_stcids'] = array('like', '%,' . intval($_GET['stc_id']) . ',%');
		}

		$model_goods = Model('goods');
		$fieldstr = "goods_id,goods_commonid,goods_name,goods_jingle,store_id,store_name,goods_price,goods_promotion_price,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_good_star,evaluation_count,goods_promotion_type";

        $recommended_goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, $fieldstr, $order, 8); 
        $recommended_goods_list = $this->getGoodsMore($recommended_goods_list);
		Tpl::output('recommended_goods_list',$recommended_goods_list[1]);
        loadfunc('search');

		//输出分页
		Tpl::output('show_page',$model_goods->showpage());
		$stc_class = Model('store_goods_class');
		$stc_info = $stc_class->getStoreGoodsClassInfo(array('stc_id' => intval($_GET['stc_id'])));		
		Tpl::output('stc_name',$stc_info['stc_name']);
		Tpl::output('page','index');

		Tpl::showpage('goods_list');
	}
	
	private function getGoodsMore($goods_list1, $goods_list2 = array()) {
        if (!empty($goods_list2)) {
            $goods_list = array_merge($goods_list1, $goods_list2);
        } else {
            $goods_list = $goods_list1;
        }
        // 商品多图
        if (!empty($goods_list)) {
            $goodsid_array = array();       // 商品id数组
            $commonid_array = array(); // 商品公共id数组
            $storeid_array = array();       // 店铺id数组
            foreach ($goods_list as $value) {
                $goodsid_array[] = $value['goods_id'];
                $commonid_array[] = $value['goods_commonid'];
                $storeid_array[] = $value['store_id'];
            }
            $goodsid_array = array_unique($goodsid_array);
            $commonid_array = array_unique($commonid_array);

            // 商品多图
            $goodsimage_more = Model('goods')->getGoodsImageList(array('goods_commonid' => array('in', $commonid_array)));

            foreach ($goods_list1 as $key => $value) {
                // 商品多图
                foreach ($goodsimage_more as $v) {
                    if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $value['color_id'] == $v['color_id']) {
                        $goods_list1[$key]['image'][] = $v;
						if (count($goods_list1[$key]['image'])==1 || $v['is_default']==1){
							$goods_list1[$key]['goods_image'] = $v['goods_image'];
						}
                    }
                }
            }

            if (!empty($goods_list2)) {
                foreach ($goods_list2 as $key => $value) {
                    // 商品多图
                    foreach ($goodsimage_more as $v) {
                        if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $value['color_id'] == $v['color_id']) {
                            $goods_list2[$key]['image'][] = $v;
                        }
                    }
                }
            }
        }
        return array(1=>$goods_list1,2=>$goods_list2);
    }
}
?>