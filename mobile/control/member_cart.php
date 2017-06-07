<?php
/**
 * 我的购物车
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

class member_cartControl extends mobileMemberControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 购物车列表
     */
	public function cart_listOp() {
		$cart_list = $this->cart_list_data();
		output_data($cart_list);
	}
	
    public function cart_list_data() {
        $model_cart = Model('cart');

        $condition = array('buyer_id' => $this->member_info['member_id']);
        $cart_list	= $model_cart->listCart('db', $condition);
		// 购物车列表 [得到最新商品属性及促销信息]
		$cart_list = logic('buy_1')->getGoodsCartList($cart_list, $jjgObj);
		
		//购物车商品以店铺ID分组显示,并计算商品小计,店铺小计与总价由JS计算得出
		$sum = 0;
        $store_cart_list = array();
        foreach ($cart_list as $cart) {			
            $cart['goods_total'] = imPriceFormat($cart['goods_price'] * $cart['goods_num']);
			$cart['goods_image_url'] = cthumb($cart['goods_image'], $cart['store_id']);
			$store_cart_list[$cart['store_id']]['goods'][] = $cart;						
			
			$sum += $cart['goods_total'];
        }
		//获取店铺信息                          
		$cart_list = array();
		$model_voucher = Model('voucher');
		$model_store = Model('store');
		foreach ($store_cart_list as $k=>$cart_info) {
			//店铺信息
			$store_info = $model_store->getStoreInfoByID($k);			
			$cart_info['store_id'] = $store_info['store_id'];
			$cart_info['store_name'] = $store_info['store_name'];
			if ($store_info['store_free_price']>0){
				$cart_info['free_freight'] = '满'.$store_info['store_free_price'].'免运费';
			}
			
			//店铺优惠劵
			$voucher = $model_voucher->getVoucherTemplateList(array('voucher_t_store_id'=>$cart_info['store_id']));
            $cart_info['voucher'] = !empty($voucher)?$voucher:NULL;			
			//满即送
						
            $cart_list[] = $cart_info;
        }

		$result = array();
		$result['cart_list'] = $cart_list;
		$result['sum'] = imPriceFormat($sum);		

        return $result;
    }

    /**
     * 购物车添加
     */
    public function cart_addOp() {
        $goods_id = intval($_POST['goods_id']);
        $quantity = intval($_POST['quantity']);
        if($goods_id <= 0 || $quantity <= 0) {
            output_error('参数错误');
        }

        $model_goods = Model('goods');
        $model_cart	= Model('cart');
        $logic_buy_1 = Logic('buy_1');

        $goods_info = $model_goods->getGoodsOnlineInfoAndPromotionById($goods_id);

        //验证是否可以购买
		if(empty($goods_info)) {
            output_error('商品已下架或不存在');
		}

		//抢购
		$logic_buy_1->getGroupbuyInfo($goods_info);
		
		//限时折扣
		$logic_buy_1->getXianshiInfo($goods_info,$quantity);

        if ($goods_info['store_id'] == $this->member_info['myself_store_id']) {
            output_error('不能购买自己发布的商品');
		}
		if(intval($goods_info['goods_storage']) < 1 || intval($goods_info['goods_storage']) < $quantity) {
            output_error('库存不足');
		}

        $param = array();
        $param['buyer_id']	= $this->member_info['member_id'];
        $param['store_id']	= $goods_info['store_id'];
        $param['goods_id']	= $goods_info['goods_id'];
        $param['goods_name'] = $goods_info['goods_name'];
        $param['goods_price'] = $goods_info['goods_price'];
        $param['goods_image'] = $goods_info['goods_image'];
        $param['store_name'] = $goods_info['store_name'];

        $result = $model_cart->addCart($param, 'db', $quantity);
        if($result) {
            output_data('1');
        } else {
            output_error('添加到购物车失败');
        }
    }

    /**
     * 购物车删除
     */
    public function cart_delOp() {
        $cart_id = intval($_POST['cart_id']);

        $model_cart = Model('cart');

        if($cart_id > 0) {
            $condition = array();
            $condition['buyer_id'] = $this->member_info['member_id'];
            $condition['cart_id'] = $cart_id;

            $model_cart->delCart('db', $condition);
        }

        output_data('1');
    }

    /**
     * 更新购物车购买数量
     */
    public function cart_edit_quantityOp() {
		$cart_id = intval(abs($_POST['cart_id']));
		$quantity = intval(abs($_POST['quantity']));
		if(empty($cart_id) || empty($quantity)) {
            output_error('参数错误');
		}

		$model_cart = Model('cart');

        $cart_info = $model_cart->getCartInfo(array('cart_id'=>$cart_id, 'buyer_id' => $this->member_info['member_id']));

        //检查是否为本人购物车
        if($cart_info['buyer_id'] != $this->member_info['member_id']) {
            output_error('参数错误');
        }

        //检查库存是否充足
        if(!$this->_check_goods_storage($cart_info, $quantity, $this->member_info['member_id'])) {
            output_error('库存不足');
        }

		$data = array();
        $data['goods_num'] = $quantity;
        $update = $model_cart->editCart($data, array('cart_id'=>$cart_id));
		if ($update) {
		    $return = array();
            $return['quantity'] = $quantity;
			$return['goods_price'] = imPriceFormat($cart_info['goods_price']);
			$return['total_price'] = imPriceFormat($cart_info['goods_price'] * $quantity);
            output_data($return);
		} else {
            output_error('修改失败');
		}
    }

    /**
     * 检查库存是否充足
     */
    private function _check_goods_storage($cart_info, $quantity, $member_id) {
		$model_goods= Model('goods');
        $model_bl = Model('p_bundling');
        $logic_buy_1 = Logic('buy_1');

		if ($cart_info['bl_id'] == '0') {
            //普通商品
		    $goods_info	= $model_goods->getGoodsOnlineInfoAndPromotionById($cart_info['goods_id']);

		    //抢购
		    $logic_buy_1->getGroupbuyInfo($goods_info);
			if ($goods_info['ifgroupbuy']) {
                if ($goods_info['upper_limit'] && $quantity > $goods_info['upper_limit']) {
                    return false;
                }
            }

		    //限时折扣
		    $logic_buy_1->getXianshiInfo($goods_info,$quantity);
 
		    $quantity = $goods_info['goods_num'];
		    if(intval($goods_info['goods_storage']) < $quantity) {
                return false;
		    }
			$goods_info['cart_id'] = $cart_info['cart_id'];
            $cart_info = $goods_info;
		} else {
		    //优惠套装商品
		    $bl_goods_list = $model_bl->getBundlingGoodsList(array('bl_id' => $cart_info['bl_id']));
		    $goods_id_array = array();
		    foreach ($bl_goods_list as $goods) {
		        $goods_id_array[] = $goods['goods_id'];
		    }
		    $bl_goods_list = $model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_id_array);

		    //如果有商品库存不足，更新购买数量到目前最大库存
		    foreach ($bl_goods_list as $goods_info) {
		        if (intval($goods_info['goods_storage']) < $quantity) {
                    return false;
		        }
		    }
		}
        return true;
    }
	
	/**
     * 购物车商品数量
     */
    public function cart_countOp() {
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }	 
		
		$cart_count = $this->getCartCount($key);
        output_data(array('cart_count'=>$cart_count));
    }
	
	/**-------------------------------------APP客户端---------------------------------------------------**/
	public function app_cart_list() {
        $model_cart = Model('cart');

        $condition = array('buyer_id' => $this->member_info['member_id']);
        $goods_list	= $model_cart->listCart('db', $condition);
        $sum = 0;
        foreach ($goods_list as $key => $value) {
            $goods_list[$key]['goods_image_url'] = cthumb($value['goods_image'], $value['store_id']);
			$goods_list[$key]['img'] = getPhoto_array(cthumb($value['goods_image'],240),cthumb($value['goods_image'],240),urlShop('goods', 'index', array('goods_id' => $value['goods_id'])));
            $goods_list[$key]['goods_sum'] = imPriceFormat($value['goods_price'] * $value['goods_num']);
            $sum += $goods_list[$key]['goods_sum'];
        }
		$total_info = array();
		$total_info['goods_price'] = 0;
		$total_info['market_price'] = 0;
		$total_info['real_goods_count'] = 0;
		$total_info['virtual_goods_count'] = 0;
		$total_info['save_rate'] = 0;
		$total_info['saving'] = 0;
		$total_info['goods_amount'] = imPriceFormat($sum);
		
		$cart_list = array();
		$cart_list['goods_list'] = $goods_list;
		$cart_list['total'] = $total_info;		

        output_data($cart_list);
    }
}