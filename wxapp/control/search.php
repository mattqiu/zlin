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
        $minPrice =$_REQUEST['minPrice'];
        $maxPrice =$_REQUEST['maxPrice'];
        $goods_total = $_REQUEST['goods_total'];
        //$condition['buyer_id'] = intval($_REQUEST['buyer_id']);
        $model_goods = Model('goods');
        $model_ordering = Model('ordering');
        $page_nums = !empty($_REQUEST['page_count'])?$_REQUEST['page_count']:$this->page; //每页显示的条数
        $page_curr = !empty($_REQUEST['curpage'])?$_REQUEST['curpage']:1; //当前显示第几页
        $fields = 'goods_commonid,goods_name,goods_price,goods_image,goods_total,goods_state,promotion_cid';
        if(empty($minPrice)){
        	$minPrice = 0;
        }
        if(empty($maxPrice)){
        	$maxPrice = 1000000;
        }
        switch ($goods_total) {
        	case '1':
        		$order = 'goods_total desc';
        		$goods_list = $model_goods->getGoodsOrderList($condition, $fields,$order, $this->page);
        		break;
        	case '2':
        		$order = 'goods_total asc';
        		$goods_list = $model_goods->getGoodsOrderList($condition, $fields,$order, $this->page);
        		break;
        	case '3':
        		$order = 'goods_price desc';
        		$goods_list = $model_goods->getGoodsOrderList($condition, $fields,$order, $this->page);
        		break;
        	case '4':
        		$order = 'goods_price asc';
        		$goods_list = $model_goods->getGoodsOrderList($condition, $fields,$order, $this->page);
        		break;
        	case '5':
        		$order = 'goods_total desc,goods_price desc';
        		$goods_list = $model_goods->getGoodsOrderList($condition, $fields,$order, $this->page);
        		break;
        	case '6':
        		$fields ='goods_commonid';
        		$condition['buyer_id'] = intval($_REQUEST['buyer_id']);
        		$goods_commonid_list = $model_ordering->getBuyOrderingInfo($condition,$fields);

        		foreach ($goods_commonid_list as $key =>$val){
        				$buy_commonid_info = $val;
        				$order_info[] = $model_goods ->getGoodsOrderList($buy_commonid_info);
        			      			
        		}

        		foreach ($order_info as $key =>$val){
        			foreach ($val as $key =>$value){
        				$goods_list[] = $value;
        			}
        		}

        		output_data($goods_list,'成功获取商品信息');
        		break;
        		
        	case '7':
        		$field ='goods_commonid';
        		$condition['buyer_id'] = intval($_REQUEST['buyer_id']);
        		$goods_commonid_list = $model_ordering->getBuyOrderingInfo($condition,$field);//已经购买的goods_commonid
        		$all_commonid_list = $model_goods ->getGoodsCommonidList($field);//所有商品goods_commonid
        		
        		foreach($goods_commonid_list as $key=>$val){
        			foreach($val as $k=>$v){
        				$new_goods_commonid_list[] = $v;
        			}
        		}
        		foreach($all_commonid_list as $key=>$val){
        			foreach($val as $k=>$v){
        				$new_all_commonid_list[] = $v;
        			}
        		}

        		$residue = array_diff($new_all_commonid_list, $new_goods_commonid_list);
        		foreach ($residue as $value){
        			$residue_commonid['goods_commonid'] = $value;
        			$residue_info[] = $model_goods ->getGoodsOrderList($residue_commonid);
        		}
        		foreach($residue_info as $key=>$val){
        			foreach($val as $k=>$v){
        				$goods_list[] = $v;
        			}
        		}
        		
        		output_data($goods_list,'成功获取商品信息');
        		break;
        		case '8':
        			$field ='goods_commonid';
        			$condition['buyer_id'] = intval($_REQUEST['buyer_id']);
        			$goods_commonid_list = $model_ordering->getBuyOrderingInfo($condition,$field);//已经购买的goods_commonid
        			$all_commonid_list = $model_goods ->getGoodsCommonidList($field);//所有商品goods_commonid
        		
        			foreach($goods_commonid_list as $key=>$val){
        				foreach($val as $k=>$v){
        					$new_goods_commonid_list[] = $v;
        				}
        			}
        			foreach($all_commonid_list as $key=>$val){
        				foreach($val as $k=>$v){
        					$new_all_commonid_list[] = $v;
        				}
        			}
        		
        			$residue = array_diff($new_all_commonid_list, $new_goods_commonid_list);
        			foreach ($residue as $value){
        				$residue_commonid['goods_commonid'] = $value;
        				$residue_info[] = $model_goods ->getGoodsOrderList($residue_commonid);
        			}
        			foreach($residue_info as $key=>$val){
        				foreach($val as $k=>$v){
        					$goods_list[] = $v;
        				}
        			}
        		
        			output_data($goods_list,'成功获取商品信息');
        			break;
        	default:
        		$goods_list = 'goods_total desc,goods_price desc';
        		break;
        }
    

        
        // 整理输出的数据格式
        foreach ($goods_list as $key => $value) {
        	$goods_list[$key]['goods_image'] = cthumb($goods_list[$key]['goods_image']);
        }
    output_data($goods_list, "加载成功");
    }
	
    
}