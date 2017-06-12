<?php
/**
 * 默认展示页面
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class indexControl extends BaseHomeControl{
	
	public function indexOp(){
		Language::read('home_index_index');
		Tpl::output('index_sign','index');

		//抢购专区
		Language::read('member_groupbuy');
        $model_groupbuy = Model('groupbuy');
        $group_list = $model_groupbuy->getGroupbuyCommendedList(4);
		Tpl::output('group_list', $group_list);
		
		//友情链接
		$model_link = Model('link');
		$link_list = $model_link->getLinkList($condition,$page);
		/**
		 * 整理图片链接
		 */
		if (is_array($link_list)){
			foreach ($link_list as $k => $v){
				if (!empty($v['link_pic'])){
					$link_list[$k]['link_pic'] = UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/common/'.DS.$v['link_pic'];
				}
			}
		}
		Tpl::output('$link_list',$link_list);
		
		//限时折扣
        $model_xianshi_goods = Model('p_xianshi_goods');
        $xianshi_item = $model_xianshi_goods->getXianshiGoodsCommendList(4);
		Tpl::output('xianshi_item', $xianshi_item);
		
		//板块信息
		$model_web_config = Model('web_config');
		$web_html = $model_web_config->getWebHtml('index');
		Tpl::output('web_html',$web_html);

		Model('seo')->type('index')->show();
		Tpl::showpage('index');
	}

	//json输出商品分类
	public function josn_classOp() {
		/**
		 * 实例化商品分类模型
		 */
		$model_class		= Model('goods_class');
		$goods_class		= $model_class->getGoodsClassListByParentId(intval($_GET['gc_id']));
		$array				= array();
		if(is_array($goods_class) and count($goods_class)>0) {
			foreach ($goods_class as $val) {
				$array[$val['gc_id']] = array('gc_id'=>$val['gc_id'],'gc_name'=>htmlspecialchars($val['gc_name']),'gc_parent_id'=>$val['gc_parent_id'],'commis_rate'=>$val['commis_rate'],'gc_sort'=>$val['gc_sort']);
			}
		}
		/**
		 * 转码
		 */
		if (strtoupper(CHARSET) == 'GBK'){
			$array = Language::getUTF8(array_values($array));//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
		} else {
			$array = array_values($array);
		}
		echo $_GET['callback'].'('.json_encode($array).')';
	}
	
	//闲置物品地区json输出
	public function flea_areaOp() {
		if(intval($_GET['check']) > 0) {
			$_GET['area_id'] = $_GET['region_id'];
		}
		if(intval($_GET['area_id']) == 0) {
			return ;
		}
		$model_area	= Model('flea_area');
		$area_array			= $model_area->getListArea(array('flea_area_parent_id'=>intval($_GET['area_id'])),'flea_area_sort desc');
		$array	= array();
		if(is_array($area_array) and count($area_array)>0) {
			foreach ($area_array as $val) {
				$array[$val['flea_area_id']] = array('flea_area_id'=>$val['flea_area_id'],'flea_area_name'=>htmlspecialchars($val['flea_area_name']),'flea_area_parent_id'=>$val['flea_area_parent_id'],'flea_area_sort'=>$val['flea_area_sort']);
			}
			/**
			 * 转码
			 */
			if (strtoupper(CHARSET) == 'GBK'){
				$array = Language::getUTF8(array_values($array));//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
			} else {
				$array = array_values($array);
			}
		}
		if(intval($_GET['check']) > 0) {//判断当前地区是否为最后一级
			if(!empty($array) && is_array($array)) {
				echo 'false';
			} else {
				echo 'true';
			}
		} else {
			echo json_encode($array);
		}
	}

	//json输出闲置物品分类
	public function josn_flea_classOp() {
		/**
		 * 实例化商品分类模型
		 */
		$model_class		= Model('flea_class');
		$goods_class		= $model_class->getClassList(array('gc_parent_id'=>intval($_GET['gc_id'])));
		$array				= array();
		if(is_array($goods_class) and count($goods_class)>0) {
			foreach ($goods_class as $val) {
				$array[$val['gc_id']] = array('gc_id'=>$val['gc_id'],'gc_name'=>htmlspecialchars($val['gc_name']),'gc_parent_id'=>$val['gc_parent_id'],'gc_sort'=>$val['gc_sort']);
			}
		}
		/**
		 * 转码
		 */
		if (strtoupper(CHARSET) == 'GBK'){
			$array = Language::getUTF8(array_values($array));//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
		} else {
			$array = array_values($array);
		}
		echo json_encode($array);
	}

    /**
     * json输出地址数组 原data/resource/js/area_array.js
     */
    public function json_areaOp()
    {
        echo $_GET['callback'].'('.json_encode(Model('area')->getAreaArrayForJson()).')';
    }

	//判断是否登录
	public function loginOp(){
		echo ($_SESSION['is_login'] == '1')? '1':'0';
	}

	/**
	 * 头部最近浏览的商品
	 */
	public function viewed_infoOp(){
	    $info = array();
		if ($_SESSION['is_login'] == '1') {
		    $member_id = $_SESSION['member_id'];
		    $info['m_id'] = $member_id;
		    if (C('voucher_allow') == 1) {
		        $time_to = time();//当前日期
    		    $info['voucher'] = Model()->table('voucher')->where(array('voucher_owner_id'=> $member_id,'voucher_state'=> 1,
    		    'voucher_start_date'=> array('elt',$time_to),'voucher_end_date'=> array('egt',$time_to)))->count();
		    }
    		$time_to = strtotime(date('Y-m-d'));//当前日期
    		$time_from = date('Y-m-d',($time_to-60*60*24*7));//7天前
		    $info['consult'] = Model()->table('consult')->where(array('member_id'=> $member_id,
		    'consult_reply_time'=> array(array('gt',strtotime($time_from)),array('lt',$time_to+60*60*24),'and')))->count();
		}
		$goods_list = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'],5);
		if(is_array($goods_list) && !empty($goods_list)) {
		    $viewed_goods = array();
		    foreach ($goods_list as $key => $val) {
		        $goods_id = $val['goods_id'];
		        $val['url'] = urlShop('goods', 'index', array('goods_id' => $goods_id));
		        $val['goods_image'] = thumb($val, 60);
		        $viewed_goods[$goods_id] = $val;
		    }
		    $info['viewed_goods'] = $viewed_goods;
		}
		if (strtoupper(CHARSET) == 'GBK'){
			$info = Language::getUTF8($info);
		}
		echo json_encode($info);
	}
	/**
	 * 查询每月的周数组
	 */
	public function getweekofmonthOp(){
	    import('function.datehelper');
	    $year = $_GET['y'];
	    $month = $_GET['m'];
	    $week_arr = getMonthWeekArr($year, $month);
	    echo json_encode($week_arr);
	    die;
	}
		

	private function data2xml($xml, $data, $item = 'item') {
		foreach ($data as $key => $value) {
			/* 指定默认的数字key */
			is_numeric($key) && $key = $item;
		
			/* 添加子元素 */
			if(is_array($value) || is_object($value)){
				$child = $xml->addChild($key);
				$this->data2xml($child, $value, $item);
		} else {
				if(is_numeric($value)){
					$child = $xml->addChild($key, $value);
				}else{
					$child = $xml->addChild($key);
					$node  = dom_import_simplexml($child);
					$node->appendChild($node->ownerDocument->createCDATASection($value));
				}
			}
			}
		}
	
	public function testOp(){
	
	$model_order = Model('order');
	$condition['store_id'] = 133;
        //角色ID：0 表示当前导购 ；1 表示全店
        $condition['saleman_id'] = 10626;
        $order_list = $model_order->getOrderList($condition, 20, 'order_id,order_sn,pay_sn,pay_method,pay_store_id,add_time,payment_code,order_amount,order_state,saleman_id,saleman_name,buyer_id,buyer_name', 'order_id desc','',array('order_goods'));
	$new_order_list = array();
        foreach ($order_list as $okey=>$value) {
        	$new_order_list[$okey] = $value;
			if(empty($value['pay_method'])=='1'){
				$value['pay_store_name'] = "平台";
			}
			if(empty($value['saleman_name'])){
				if(!empty($value['saleman_id'])){
					$seller_condition['store_id'] = $this->store_id;
					$seller_condition['member_id'] = $value['saleman_id'];
					$sellerInfo = Model('seller')->getSellerInfo($seller_condition,'nick_name,seller_name');
					if(!empty($sellerInfo)){
						$new_order_list[$okey]['saleman_name'] = empty($sellerInfo['nick_name'])?$sellerInfo['seller_name']:$sellerInfo['nick_name'];
					}else{
						$new_order_list[$okey]['saleman_name'] = "店长";
					}
				}else{
					$new_order_list[$okey]['saleman_name'] = "店长";
				}
			}
        	$new_order_list[$okey]['buyer_name'] = $value['buyer_name'];//购买人昵称
        	$new_order_list[$okey]['buyer_avatar'] = getMemberAvatarForID($value['buyer_id']);//购买人的头像        	
			$new_order_list[$okey]['add_time'] = date('Y-m-d H:i',$value['add_time']);
			$new_order_list[$okey]['payment_name'] = $value['payment_name'];
			$new_order_list[$okey]['state_name'] = $value['state_desc']; //订单状态名称转换			
        }
	echo "<pre>";
	print_r($new_order_list);
	exit;
	
	$order_goods_list = $model_order->getOrderGoodsList(array('order_id'=>730));//,'goods_type'=>array('neq',5)
    	echo "订单总数：".count($order_goods_list);
	echo "<br>";
	foreach ($order_goods_list as $okey=>$value) {
		//$order_list[$value['order_id']][] = $value;
		$goods_number = $model_order->getOrderGoodsSum(array('order_id'=>$value['order_id']));
	}
	echo "商品数：".$goods_number;
	echo "<br>";
	echo "总数：".count($goods_number);
	echo "<br>";
	echo "<pre>";
	print_r($order_goods_list);
	exit;
    	/*
		$model_type = Model('type');
		// 获取类型相关数据
		$typeinfo = $model_type->getAttr(40, 133, 1);
		list($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;
		echo "<pre>spec_json : ";
		print_r($spec_json);
		echo "<br>spec_list : ";
		print_r($spec_list);
		echo "<br>";
		echo "<br>";
		echo "<br>";
		$a = unserialize('a:2:{i:1493;s:6:"灰色";i:600;s:2:"XL";}');
		foreach ($a as $key => $val){
			$spec_checked[$key]['name'] = $val;
		}
		$te = array_slice($a,0);
		print_r($te);
		echo "====".$a[1493];
		/**
		 * 测试会员充值卡，之和的流程是否正确
		 */
		/*
		$logic_payment = Logic('payment');
		$out_trade_no = '790532218893160844'; 
		$trade_no = '666666';
			$paymentCode = 'wxpay';
		
		
		
		
			$result = $logic_payment->getPdOrderInfo($out_trade_no);
			echo "<br><pre>返回结果是";
			 
			$order_info = $result['data'];
			$payment_info['payment_code'] = $paymentCode;
			$paymentName = orderPaymentName($paymentCode);
			$payment_info['payment_name'] = $paymentName;
			$result = $logic_payment->updatePdOrder($out_trade_no, $trade_no, $payment_info, $result['data']);
		echo "<br>============".$result;
		echo "============";
		exit;
		echo "<pre>";
			
			 echo "<br><pre>返回结果是";
			 print_r($result);
			 	
			$api_pay_amount = $order_info['order_amount'];
			$log_buyer_id = $order_info['buyer_id'];
			$log_buyer_name = $order_info['buyer_name'];
			$log_desc = '虚拟订单使用'.orderPaymentName($paymentCode).'成功支付，支付单号：'.$out_trade_no;
		
		exit;
		*/
		/*$condition = array();
		 
		$model_rc = Model('rechargecard');
		$list = $model_rc->getVipCardList($condition,'*','',$this->page,'id desc','denomination');
		
		$page_count = $model_rc->gettotalpage();
		echo "<pre>";
		print_r($list);
		echo "<br>".$page_count;
		exit;
		//查询支付单信息
		$model_order= Model('order');
		//取子订单列表
        $condition = array();
        $condition['pay_sn'] = "440526546061084001";
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $order_list = $model_order->getOrderList($condition,'','order_id,order_state,payment_code,order_amount,points_amount,rcb_amount,pd_amount,order_sn,store_id,store_name,add_time,shipping_fee,pay_sn,pay_method, pay_store_id','','',array(),true);
        if (empty($order_list)) {
            echo '未找到需要支付的订单'.$pay_sn;
        }
        echo '<pre>'.$pay_sn;
        print_r($order_list);
        exit;
		/*
		
		$model_order = Model('order');
		
		$model_voucher = Model('voucher');
		//赠送对应的代金券
                $OrderGoodsList = $model_order->getOrderGoodsList(array('order_id'=>400),'goods_id,buyer_id');
                $goodsIdArr = array();
                foreach ($OrderGoodsList as $ogoods){
                	$goodsIdArr[] = $ogoods['goods_id'];
                	$member_id = $ogoods['buyer_id'];
                }
                if(in_array('114971',$goodsIdArr)){
                	$model_voucher = Model('voucher');
                	$voucher_where['voucher_t_id'] = 15;
                	$voucher_where['voucher_store_id'] = 100;
                	$voucherCodeArr = $model_voucher->getVoucherUnusedList($voucher_where,'voucher_id');
                	$where['voucher_id'] = $voucherCodeArr[0]['voucher_id'];
		            $update_arr = array();
		            $update_arr['voucher_owner_id'] = $_SESSION['member_id'];
		            $update_arr['voucher_owner_name'] = $_SESSION['member_name'];
		            $update_arr['voucher_active_date'] = time();
		            $result = $model_voucher->editVoucher($update_arr, $where, $_SESSION['member_id']);
                }
 		print_r("结果：=".$result);exit;
		
		
		$pay_sn = '140524764645482626';
		//查询支付单信息
		$model_order= Model('order');
		//如果是跨境淘商品，需要将商品同步给跨境淘ERP zhangc  2016-07-25
		if(C("ikjtao_api_isuse")){
			$ikjtao_res = $model_order->push_ikjtao_order($pay_sn);
		}
		*/
	}
}