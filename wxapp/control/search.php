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
        $this->page=40;
        $termKey =$_REQUEST['goods_total'];
        $model_goods = Model('goods');
        $page_nums = !empty($_REQUEST['page_count'])?$_REQUEST['page_count']:$this->page; //每页显示的条数
        $page_curr = !empty($_REQUEST['curpage'])?$_REQUEST['curpage']:1; //当前显示第几页
        $fields = 'goods_commonid,goods_name,goods_price,goods_marketprice,goods_tradeprice,goods_addtime,goods_image,goods_state,promotion_cid';
        $goods_list = $model_goods->getGoodsChoseList($condition, $fields, $this->page);
        /*if($termKey == "1"){
        	switch ($termOrder) {
        		case '1':
        			$goods_list = $model_goods->getGoodsChoseList($condition, $fields, $this->page,'goods_tradeprice desc');
        			break;
        		case '2':
        			$goods_list = $model_goods->getGoodsChoseList($condition, $fields,$this->page,'goods_tradeprice asc');
        			break;
        		case '3':
        			$goods_list = $model_goods->getGoodsChoseList($condition, $fields, $this->page,'goods_price desc');
        			break;
        		case '4':
        			$goods_list = $model_goods->getGoodsChoseList($condition, $fields, $this->page,'goods_price asc');
        			break;
        		default:
        			$goods_list = $model_goods->getGoodsChoseList($condition, $fields, $this->page,'goods_commend desc,goods_addtime desc');
        			break;
        	}
        }else if($termKey == "2"){
        	switch ($termOrder) {
        		case '1':
        			$goods_list = $model_goods->getGoodsChoseList($condition, $fields, $this->page);
        			break;
        		case '2':
        			$goods_list = $model_goods->getGoodsChoseList($condition, $fields, $this->page);
        			break;
        		default:
        			$goods_list = $model_goods->getGoodsChoseList($condition, $fields, $this->page,'goods_commend desc,goods_addtime desc');
        			break;
        	}
        }else if($termKey == "4"){
        			$goods_list = $model_goods->getGoodsChoseList($condition, $fields, $this->page,'goods_price desc');
        			
        }else{
        	$goods_list = $model_goods->getGoodsOnlineList($condition, $fields, $this->page,'goods_commend desc,goods_addtime desc');
        }*/
        // 整理输出的数据格式
        foreach ($goods_list as $key => $value) {
        	$goods_list[$key]['goods_image'] = cthumb($goods_list[$key]['goods_image']);
        }
    output_data($goods_list, "加载成功");
    }
	
    
}