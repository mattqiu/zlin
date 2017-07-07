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

class ordering_goodsControl extends wxappControl {

    //每页显示商品数
    private $page_size = 24;
    //模型对象
    private $_model_search;
    
    
    /**
     * 查询订货会首页商品列表
     * @author 原作者：张福东
     * @reviser zhangc
     */
    public function indexOp() {
    	
    	$buyer_id =	empty($_REQUEST['member_id']) ? 0 : $_REQUEST['member_id'];//订购人ID
    	$keyword =	empty($_REQUEST['keyword']) ? 0 : $_REQUEST['keyword'];//关键词
        $sort_id = empty($_REQUEST['sort_id']) ? 0 : $_REQUEST['sort_id']; //筛选ID
        $has_id = empty($_REQUEST['has_id']) ? 0 : $_REQUEST['has_id']; //筛选ID
        $filter_type = empty($_REQUEST['filter_type']) ? '' : $_REQUEST['filter_type']; //筛选类型
        $minPrice =	empty($_REQUEST['minPrice']) ? 0 : $_REQUEST['minPrice']; //最小金额
        $maxPrice =	empty($_REQUEST['maxPrice']) ? 0 : $_REQUEST['maxPrice']; //最大金额
        $page = empty($_REQUEST['page']) ? $this->page_size : $_REQUEST['page'];//每页显示的条数
        $page_curr = !empty($_REQUEST['curpage'])?$_REQUEST['curpage']:1; //当前显示第几页
        
        $ordering_id = empty($_REQUEST['ordering_id']) ? 0 : $_REQUEST['ordering_id'];//参加订货会的ID

        //定义查询条件
        $where = '';
        $where .= 'is_ordering = 1';//必须是参加订货会的商品
        //暂时屏蔽，后续时需要开放的$condition['ordering_id'] = $ordering_id;//参加本次订货会的ID
        //根据购买人查询相关订单
        //后续需要添加改购买人是否由下级店长或员工，把员工和店长的订单统计出来即可
        if(!empty($buyer_id)){
        	$orderingWhere = ' WHERE ';
        	$model_ordering = Model('ordering');
        	$condition['buyer_id'] = $buyer_id;
        	$orderingList  = $model_ordering->getOrderingList($condition,'ordering_id');
        	if(count($orderingList)>1){
        		$ordering_id_arr =array();
        		foreach ($orderingList as  $order){
        			$ordering_id_arr[] = $order['ordering_id'];
        		}
        		$ordering_id = implode(",",$ordering_id_arr);
        		$orderingWhere .= ' ordering_id IN ('.$ordering_id.') ';
        	}else{
        		if(!empty($orderingList)){
        		$ordering_id = $orderingList[0]['ordering_id'];
        		}else{
        			$ordering_id = 0;
        	}
        		$orderingWhere .= ' ordering_id = '.$ordering_id;
        	}
        }
        //关键字搜索
        if(!empty($keyword)){
        	$where .= ' AND ( gc.goods_name LIKE "%'.$keyword.'%"' .
        		' OR gc.goods_serial LIKE "%'.$keyword.'%"'.//商品货号
        		' OR gc.goods_jingle LIKE "%'.$keyword.'%") ';//卖点
        }
        //筛选条件未是否订货
        
        switch ($has_id) {
        	case '1':
        		$where .= ' AND gc.goods_commonid IN (SELECT goods_commonid FROM zlin_ordering_goods '.$orderingWhere.' GROUP BY goods_commonid) ';//已经订货的商品
        		break;
        	case '2':
        		$where .= ' AND gc.goods_commonid NOT IN (SELECT goods_commonid FROM zlin_ordering_goods '.$orderingWhere.' GROUP BY goods_commonid) ';//未订货的商品
        		break;
        	default:
        		break;
      	}
        
        
     	//默认查询条件类型是排序
		switch ($sort_id) {
	   		case '1'://订量从高到底
	        	$order = 'og.goods_sum DESC';
	        	break;
        	case '2':
        		$order = 'og.goods_sum ASC';
        		break;
        	case '3':
        		$order = 'gc.goods_price DESC';
        		break;
        	case '4':
        		$order = 'gc.goods_price ASC';
        		break;
        	default:
        		$order = "gc.goods_commonid DESC";
        		break;
	  	}
	  	//获取金额区间
	  	if(!empty($minPrice)){
	  		$where .= ' AND gc.goods_price >= '.$minPrice;
	  	}
	  	if(!empty($maxPrice)){
	  		$where .= ' AND gc.goods_price <= '.$maxPrice;
	  	}
        /**
        *$model = Model();
        *$table = 'goods_common,ordering_goods';
        *$field = 'sum(ordering_goods.goods_num) as goods_sum, goods_common.goods_commonid, goods_common.goods_name, goods_common.goods_serial, goods_common.goods_price, goods_common.goods_image';
        *$group = 'ordering_goods.goods_commonid';
        *$on    = 'goods_common.goods_commonid=ordering_goods.goods_commonid';
        
        *$goods_list = $model->table($table)->field($field)->join('left')->on($on)->where($condition)->group($group)->page($page)->order($order)->select();
        */
	  	$ogoods_sql = 'SELECT og.goods_sum,gc.goods_commonid, gc.goods_name, gc.goods_serial, gc.goods_price, gc.goods_image FROM zlin_goods_common AS gc
			LEFT JOIN (SELECT goods_commonid, SUM(goods_num) AS goods_sum FROM zlin_ordering_goods'.$orderingWhere.' GROUP BY goods_commonid) AS og ON og.goods_commonid = gc.goods_commonid 
			WHERE '.$where.' ORDER BY '.$order.' LIMIT '.($page_curr-1)*$page.','.$page.';';
	  	$goods_list = Model()->query($ogoods_sql);
        // 整理输出的数据格式
        if(!empty($goods_list)){
	        foreach ($goods_list as $key => $value) {
			$goods_list[$key]['goods_sum'] = empty($value['goods_sum'])?0:$value['goods_sum'];
	        	$goods_list[$key]['goods_image'] = cthumb($goods_list[$key]['goods_image']);
	        }
    	}
    	output_data($goods_list, "加载成功");
    }
    
    /**
     * 获取商品详细页
     */
    public function goods_detailOp() {
    	$goods_commonid = intval($_REQUEST['goods_commonid']);
    	// 商品详细信息
    	$model_goods = Model('goods');
    	$goods_detail = $model_goods->getGoodsCommonInfoByID($goods_commonid,'goods_commonid,goods_name,goods_serial,goods_marketprice,goods_price,spec_name,spec_value,store_id,store_name');
    	$spec_name = unserialize($goods_detail['spec_name']);
    	$spec_value = unserialize($goods_detail['spec_value']);
    	// 查询所有规格商品
    	$spec_array = $model_goods->getGoodsSpecListByCommonId($goods_commonid,$goods_detail['store_id']);
    	$spec_list = array();       // 各规格商，js使用
    	$spec_image = array();      // 各规格商品主图，规格颜色图片使用
    	foreach ($spec_array as $key => $value) {
    		$s_array = unserialize($value['goods_spec']);
    		$tmp_array = array();
    		if (!empty($s_array) && is_array($s_array)) {
    			foreach ($s_array as $k => $v) {
    				$tmp_array[] = $k;
    			}
    		}
    		sort($tmp_array);
    		$spec_sign = implode('|', $tmp_array);
    		
    		$tpl_spec = array();
    		$tpl_spec['sign'] = $spec_sign;
    		$spec_list[$spec_sign]['goods_id'] = $value['goods_id'];
            $spec_list[$spec_sign]['goods_price'] = $value['goods_price'];
    		$spec_image[$value['color_id']] = thumb($value, 60);
    	}
    	$goods_detail['spec_list'] = $spec_list;
    	$goods_detail['spec_image'] = $spec_image;
    	$sp_name = ''; //定义横竖排交叉的名称
    	if(!empty($spec_value)){
    		$i = 0;
    		if (!empty($spec_value) && is_array($spec_value)) {
	    		foreach ($spec_value as $skey=>$spec) {
	    			$sp_value[$i]['id'] = $skey;
	    			$sp_value[$i]['let'] = count($spec);
	    			$sp_value[$i]['name'] = $spec;
	    			$i++;
                    foreach ($spec as $svkey => $sp_v) {
                        # code...
                        $obj_sp_value[$skey][$svkey]['sp_v_id'] = $svkey;
                        $obj_sp_value[$skey][$svkey]['sp_v_name'] = $sp_v;
                        $obj_sp_value[$skey][$svkey]['checked'] = '';
                    }
	    		}
    		}
    		// 声明组合SKU数组
    		$colSku = Array();
    		if(count($spec_value) == 1){
    			if($sp_value[0]['let'] > 6){
    				$objRowSpec = ''; //横排属性
    				$objColSpec = $sp_value[0]['name']; //竖排属性
    				foreach($objColSpec as $ckey=>$spec){
    					$colSku[$ckey]['sp_name'][0] = $spec;
    					$colSku[$ckey]['list'][0]['skuid'] = $ckey;
    					$colSku[$ckey]['list'][0]['skuname'] = $spec;
    				}
    			}else{
    				$objRowSpec = $sp_value[0]['name']; //横排属性
    				$objColSpec = ''; //竖排属性
    				foreach($objRowSpec as $rkey=>$spec){
    					$colSku[0]['sp_name'][0] = $spec;
    					$colSku[0]['list'][$rkey]['skuid'] = $rkey;
    					$colSku[0]['list'][$rkey]['skuname'] = $spec;
    				}
    			}
    		}else if(count($spec_value) == 2){
    			// 找到上标最大的数组
    			$maxKey = ($sp_value[0]['let'] >= $sp_value[1]['let']) ? 0 : 1;
    			$minKey = ($sp_value[0]['let'] < $sp_value[1]['let']) ? 0 : 1;
    			if($sp_value[$maxKey]['let'] > 6){
    				$objRowSpec = $sp_value[$minKey]['name']; //横排属性
    				$objColSpec = $sp_value[$maxKey]['name']; //竖排属性
    				$sp_id = $sp_value[$maxKey]['id'];
    			}else{
    				$objRowSpec = $sp_value[$maxKey]['name']; //横排属性
    				$objColSpec = $sp_value[$minKey]['name']; //竖排属性
    				$sp_id = $sp_value[$minKey]['id'];
    			}
    			$sp_name = $spec_name[$sp_id];
    			
    			//按照上标最大数组循环
    			foreach($objColSpec as $dkey=>$maxspec){
    				// 按照需要交错合并的两个数组循环
    				$colSku[$dkey]['sp_name'][0] = $maxspec;
    				$colIdx = 0;
    				foreach($objRowSpec as $xkey=>$minspec){
    					if($xkey>$dkey){
    						$skuid = $dkey.'|'.$xkey;
    						$skyname = $maxspec.'|'.$minspec;
    					}else{
    						$skuid = $xkey.'|'.$dkey;
    						$skyname = $minspec.'|'.$maxspec;
    					}
                        $colSku[$dkey]['list'][$colIdx]['goods_id'] = $spec_list[$skuid]['goods_id'];
    					$colSku[$dkey]['list'][$colIdx]['skuid'] = $skuid;
    					$colSku[$dkey]['list'][$colIdx]['skuname'] = $skyname;
    					$colIdx++;
    				}
    			}
    		}else{
    			//新思路就是直接从$s_array 中获取数量循环出来即可
    			//查出第一排名称即可$objRowSpec
    			$objRowSpec = ''; //横排属性
    			
                foreach ($spec_array as $key => $value) {
                    $s_array = unserialize($value['goods_spec']);
                    $tmp_array = array();
                    if (!empty($s_array) && is_array($s_array)) {
                        foreach ($s_array as $k => $v) {
                            $tmp_array[] = $k;
                            $colSku[$key]['sp_name'][$k] = $v;
	    		}
	    		}
                    $colSku[$key]['list'][0]['goods_id'] = $value['goods_id'];

    			}
    			
    		}
    	}else{
            $spec_value = '';
    	}
        $goods_detail['spec_name'] = $spec_name;
    	$goods_detail['spec_value'] = $obj_sp_value;
    	$goods_detail['sp_name'] = $sp_name;//显示横竖排交叉的名称
    	$goods_detail['colspec'] = $colSku;
    	$goods_detail['rowspec'] = $objRowSpec;
    	$goods_detail['goods_image'] = cthumb($goods_detail['goods_image']);
    	output_data($goods_detail,'成功获取商品信息');
    }
}