<?php
/**
 * 商品列表
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class searchControl extends wxappControl {


    //每页显示商品数
    const PAGESIZE = 24;

    //模型对象
    private $_model_search;

    public function indexOp() {
        Language::read('home_goods_class_index');
        $this->_model_search = Model('search');
        $this->page=20;
        $termKey =$_REQUEST['key'];
        $termOrder =$_REQUEST['order'];

        $model_goods = Model('goods');
        $page_nums = !empty($_REQUEST['page_count'])?$_REQUEST['page_count']:$this->page; //每页显示的条数
        $page_curr = !empty($_REQUEST['curpage'])?$_REQUEST['curpage']:1; //当前显示第几页
        $fields = 'goods_id,goods_commonid,goods_name,goods_price,goods_marketprice,goods_tradeprice,goods_addtime,goods_image,goods_state,goods_collect,goods_salenum,up_id,promotion_cid';
        if($termKey == "1"){
        	switch ($termOrder) {
        		case '1':
        			$goods_list = $model_goods->getGoodsOnlineList($condition, $fields, $this->page,'goods_tradeprice desc');
        			break;
        		case '2':
        			$goods_list = $model_goods->getGoodsOnlineList($condition, $fields,$this->page,'goods_tradeprice asc');
        			break;
        		case '3':
        			$goods_list = $model_goods->getGoodsOnlineList($condition, $fields, $this->page,'goods_price desc');
        			break;
        		case '4':
        			$goods_list = $model_goods->getGoodsOnlineList($condition, $fields, $this->page,'goods_price asc');
        			break;
        		default:
        			$goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, $fields,'goods_commend desc,goods_edittime desc,goods_addtime desc', $this->page);
        			break;
        	}
        }else if($termKey == "2"){
        	switch ($termOrder) {
        		case '1':
        			$goods_list = $model_goods->getGoodsOnlineList($condition, $fields, $this->page);
        			break;
        		case '2':
        			$goods_list = $model_goods->getGoodsOnlineList($condition, $fields, $this->page);
        			break;
        		default:
        			$goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, $fields,'goods_commend desc,goods_edittime desc,goods_addtime desc', $this->page);
        			break;
        	}
        }else if($termKey == "4"){
        			$goods_list = $model_goods->getGoodsChoseList($condition, $fields, $this->page,'goods_price desc');
        			
        }else{
        	$goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, $fields,'goods_commend desc,goods_edittime desc,goods_addtime desc', $this->page);
        }
        
    output_data($goods_list, "加载成功");
    }
	
    
}