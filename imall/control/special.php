<?php
/**
 * 商城专辑
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

class specialControl extends BaseHomeControl{

    public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','special');
    }

    public function indexOp() {
		//板块信息
		$model_web_config = Model('web_config');
		$web_html = $model_web_config->getWebHtml('special');
		Tpl::output('web_html',$web_html);
		
        $this->special_listOp();
    }

    /**
     * 专题列表
     */
    public function special_listOp() {
		//专题分类
		$special_class = Model('web_special_class')->getList();
		Tpl::output('special_class', $special_class);         
		
        $model_special = Model('web_special');
		//专辑列表
		$where = array();
		if (!empty($_GET['class'])){
			$where['special_class']=$_GET['class'];
		}		
		$where['special_apply']=1;
		$where['special_state'] = 2;
		$special_list = $model_special->getList($where, 20, 'special_id desc');
		
		$special_list1=array();
		$special_list2=array();
		$special_list3=array();
		$special_list4=array();

		if (!empty($special_list) && is_array($special_list)){
			$i=0;
			foreach ($special_list as $key=>$special) {
				switch ($i%4){
					case 0 :
					  $special_list1[]=$special;
					  break;
				    case 1 :
					  $special_list2[]=$special;
					  break;
					case 2 :
					  $special_list3[]=$special;
					  break;
					case 3 :
					  $special_list4[]=$special;
					  break;
				}				
				$i++;
			}
		}
		Tpl::output('special_list1',$special_list1);
		Tpl::output('special_list2',$special_list2);
		Tpl::output('special_list3',$special_list3);
		Tpl::output('special_list4',$special_list4);
		
		Tpl::output('show_page', $model_special->showpage());	
		//推荐专题
		$where['special_recommend'] = 1;        
		$special_r = $model_special->getList($where,12, 'special_id desc');
		Tpl::output('special_r',$special_r);        

        Tpl::showpage('special_list');
    }

    /**
     * 专题详细页
     */
    public function special_detailOp() {
        Tpl::output('index_sign', 'special');
        Tpl::showpage('special_detail');
    }
    
    
    /**
     * 开业活动
     */
    public function bundlingOp() {
    	
    	$model_bundling = Model('p_bundling');
    	$condition = array(
    			'bl_is_extension' => 1   //推广员套餐
    	);
    	
    	$p_bundingList = $model_bundling->getBundlingOpenList($condition);
    	foreach ($p_bundingList as $key=>$p_bunding){
    		$bg_count = $model_bundling->getBundlingGoodsCount(array('bl_id'=>$p_bunding['bl_id']));
    		$pb_goodsList = $model_bundling->getBundlingGoodsList(array('bl_id'=>$p_bunding['bl_id']));
    		$model_goods = Model('goods');
    		foreach ($pb_goodsList as $gkey=>$pb_goods){
    			$goods_price = $model_goods->getGoodsList(array('goods_id'=>$pb_goods['goods_id']),'goods_price');
    			$pb_goodsList[$gkey]['goods_price'] = $goods_price[0]['goods_price'];
    		}
    		$p_bundingList[$key]['bundlingGoodsList'] = $pb_goodsList;
    		$p_bundingList[$key]['bg_count'] = $bg_count;
    		if(($bg_count%3)==0){
    			$p_bundingList[$key]['bg_classid'] = 3;
    		}elseif(($bg_count%2)==0){
    			$p_bundingList[$key]['bg_classid'] = 2;
    		}else{
    			$p_bundingList[$key]['bg_classid'] = 5;
    		}
    	}
    	//print_r($p_bundingList);
    	Tpl::output('bundlingList', $p_bundingList);
    	Tpl::output('index_sign', 'special');
    	Tpl::showpage('bundling');
    }
}
