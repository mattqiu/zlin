<?php
/**
 * 商品
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class goodsControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }

    /**
     * 商品列表
     */
    public function goods_listOp() {
        $model_goods = Model('goods');
        $model_search = Model('search');
		$_GET['is_book'] = 0;

        //查询条件
        $condition = array();
		// ==== 暂时不显示定金预售商品，手机端未做。  ====
        $condition['is_book'] = 0;
		if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {
            $condition['gc_id'] = $_GET['gc_id'];
        } elseif (!empty($_GET['keyword'])) {
            $condition['goods_name|goods_jingle|goods_serial|goods_barcode|goods_body'] = array('like', '%' . $_GET['keyword'] . '%');
			
			if (cookie('his_sh') == '') {
                $his_sh_list = array();
            } else {
                $his_sh_list = explode('~', cookie('his_sh'));
            }
            if (strlen($_GET['keyword']) <= 30 && !in_array($_GET['keyword'],$his_sh_list)) {
				//在数组开头手插入一个元素
                if (array_unshift($his_sh_list, $_GET['keyword']) > 8) {
					//删除最后一个数组元素
                    array_pop($his_sh_list);
                }
            }
            setIMCookie('his_sh', implode('~', $his_sh_list),2592000); //添加历史纪录
        } elseif (!empty($_GET['barcode'])) {
            $condition['goods_barcode'] = $_GET['barcode'];
        } elseif (!empty($_GET['b_id']) && intval($_GET['b_id'] > 0)) {
            $condition['brand_id'] = intval($_GET['b_id']);
        }
		if(!empty($_GET['store_id']) && intval($_GET['store_id']) > 0) {
			$condition['store_id'] = $_GET['store_id'];
		}
		
		//店铺服务
		if ($_GET['ci'] && $_GET['ci'] != 0) {
            //处理参数
            $search_ci= $_GET['ci'];
            $search_ci_arr = explode('_',$search_ci);
            $search_ci_str = $search_ci.'_';
            $indexer_searcharr['search_ci_arr'] = $search_ci_arr;
        }

		if (!empty($_GET['price_from']) && intval($_GET['price_from'] > 0)) {
            $condition['goods_price'][] = array('egt',intval($_GET['price_from']));
        }
		if (!empty($_GET['price_to']) && intval($_GET['price_to'] > 0)) {
            $condition['goods_price'][] = array('elt',intval($_GET['price_to']));
        }
		if (intval($_GET['area_id']) > 0) {
			$condition['areaid_1'] = intval($_GET['area_id']);
		}
		
		//赠品
		if ($_GET['gift'] == 1) {
			$condition['have_gift'] = 1;
		}
		//团购
		if ($_GET['groupbuy'] == 1) {
			$condition['goods_promotion_type'][] = 1;
		}
		//限时折扣
		if ($_GET['xianshi'] == 1) {
			$condition['goods_promotion_type'][] = 2;
		}
		//虚拟
		if ($_GET['virtual'] == 1) {
			$condition['is_virtual'] = 2;
		}

        //所需字段
		$fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_price,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count,goods_promotion_price,goods_promotion_type";

        // 添加3个状态字段
		$fieldstr .= ',is_virtual,is_presell,is_fcode,have_gift,goods_jingle,store_name,promotion_amount,is_own_shop';

        //排序方式
        $order = $this->_goods_list_order($_GET['key'], $_GET['order']);

        //优先从全文索引库里查找
        list($indexer_ids,$indexer_count) = $model_search->indexerSearch($_GET,$this->page);
        if (is_array($indexer_ids)) {
            //商品主键搜索
            $goods_list = $model_goods->getGoodsOnlineList(array('goods_id'=>array('in',$indexer_ids)), $fieldstr, 0, $order, $this->page, null, false);

            //如果有商品下架等情况，则删除下架商品的搜索索引信息
            if (count($goods_list) != count($indexer_ids)) {
                $model_search->delInvalidGoods($goods_list, $indexer_ids);
            }
            pagecmd('setEachNum',$this->page);
            pagecmd('setTotalNum',$indexer_count);
        } else {
            $goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, $fieldstr, $order, $this->page);			
        }
        $page_count = $model_goods->gettotalpage();

        //处理商品列表(抢购、限时折扣、商品图片)
        $goods_list = $this->_goods_list_extend($goods_list);

        output_data(array('goods_list' => $goods_list), mobile_page($page_count));
    }

    /**
     * 分销商品列表
     */
    public function fxgoods_listOp() {
    	$model_goods = Model('goods');
    	$model_search = Model('search');
    	$_GET['is_book'] = 0;
    
    	//查询条件
    	$condition = array();
    	// ==== 暂时不显示定金预售商品，手机端未做。  ====
    	$condition['is_book'] = 0;
    	if(!empty($_GET['gc_id']) && intval($_GET['gc_id']) > 0) {
    		$condition['gc_id'] = $_GET['gc_id'];
    	} elseif (!empty($_GET['keyword'])) {
    		$condition['goods_name|goods_jingle|goods_serial|goods_barcode|goods_body'] = array('like', '%' . $_GET['keyword'] . '%');
    			
    		if (cookie('his_sh') == '') {
    			$his_sh_list = array();
    		} else {
    			$his_sh_list = explode('~', cookie('his_sh'));
    		}
    		if (strlen($_GET['keyword']) <= 30 && !in_array($_GET['keyword'],$his_sh_list)) {
    			//在数组开头手插入一个元素
    			if (array_unshift($his_sh_list, $_GET['keyword']) > 8) {
    				//删除最后一个数组元素
    				array_pop($his_sh_list);
    			}
    		}
    		setIMCookie('his_sh', implode('~', $his_sh_list),2592000); //添加历史纪录
    	} elseif (!empty($_GET['barcode'])) {
    		$condition['goods_barcode'] = $_GET['barcode'];
    	} elseif (!empty($_GET['b_id']) && intval($_GET['b_id'] > 0)) {
    		$condition['brand_id'] = intval($_GET['b_id']);
    	}
    	if(!empty($_GET['store_id']) && intval($_GET['store_id']) > 0) {
    		$condition['store_id'] = $_GET['store_id'];
    	}
    
    	//店铺服务
    	if ($_GET['ci'] && $_GET['ci'] != 0) {
    		//处理参数
    		$search_ci= $_GET['ci'];
    		$search_ci_arr = explode('_',$search_ci);
    		$search_ci_str = $search_ci.'_';
    		$indexer_searcharr['search_ci_arr'] = $search_ci_arr;
    	}
    
    	if (!empty($_GET['price_from']) && intval($_GET['price_from'] > 0)) {
    		$condition['goods_price'][] = array('egt',intval($_GET['price_from']));
    	}
    	if (!empty($_GET['price_to']) && intval($_GET['price_to'] > 0)) {
    		$condition['goods_price'][] = array('elt',intval($_GET['price_to']));
    	}
    	if (intval($_GET['area_id']) > 0) {
    		$condition['areaid_1'] = intval($_GET['area_id']);
    	}
    
    	//赠品
    	if ($_GET['gift'] == 1) {
    		$condition['have_gift'] = 1;
    	}
    	//团购
    	if ($_GET['groupbuy'] == 1) {
    		$condition['goods_promotion_type'][] = 1;
    	}
    	//限时折扣
    	if ($_GET['xianshi'] == 1) {
    		$condition['goods_promotion_type'][] = 2;
    	}
    	//虚拟
    	if ($_GET['virtual'] == 1) {
    		$condition['is_virtual'] = 2;
    	}
    	$condition['baifen'] = array('gt',0);
    	//所需字段
    	$fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_price,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count,goods_promotion_price,goods_promotion_type";
    
    	// 添加3个状态字段
    	$fieldstr .= ',is_virtual,is_presell,is_fcode,have_gift,goods_jingle,store_name,promotion_amount,is_own_shop,baifen';
    
    	//排序方式
    	$order = $this->_goods_list_order($_GET['key'], $_GET['order']);
    
    	//优先从全文索引库里查找
    	list($indexer_ids,$indexer_count) = $model_search->indexerSearch($_GET,$this->page);
    	if (is_array($indexer_ids)) {
    		//商品主键搜索
    		$goods_list = $model_goods->getGoodsOnlineList(array('goods_id'=>array('in',$indexer_ids)), $fieldstr, 0, $order, $this->page, null, false);
    
    		//如果有商品下架等情况，则删除下架商品的搜索索引信息
    		if (count($goods_list) != count($indexer_ids)) {
    			$model_search->delInvalidGoods($goods_list, $indexer_ids);
    		}
    		pagecmd('setEachNum',$this->page);
    		pagecmd('setTotalNum',$indexer_count);
    	} else {
    		$goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, $fieldstr, $order, $this->page);
    	}
    	$page_count = $model_goods->gettotalpage();
    
    	//处理商品列表(抢购、限时折扣、商品图片)
    	$goods_list = $this->_goods_list_extend($goods_list);
    
    	output_data(array('goods_list' => $goods_list), mobile_page($page_count));
    }
    
    /**
     * 商品列表排序方式
     */
    private function _goods_list_order($key, $order) {
        $result = 'is_own_shop desc,goods_id desc';
        if (!empty($key)) {

            $sequence = 'desc';
            if($order == 1) {
                $sequence = 'asc';
            }

            switch ($key) {
                //销量
                case '1' :
                    $result = 'goods_salenum' . ' ' . $sequence;
                    break;
                //浏览量
                case '2' :
                    $result = 'goods_click' . ' ' . $sequence;
                    break;
                //价格
                case '3' :
                    $result = 'goods_price' . ' ' . $sequence;
                    break;
            }
        }
        return $result;
    }

    /**
     * 处理商品列表(抢购、限时折扣、商品图片)
     */
    private function _goods_list_extend($goods_list) {
        //获取商品列表编号数组
        $commonid_array = array();
        $goodsid_array = array();
        foreach($goods_list as $key => $value) {
            $commonid_array[] = $value['goods_commonid'];
            $goodsid_array[] = $value['goods_id'];
        }

        //促销
        $groupbuy_list = Model('groupbuy')->getGroupbuyListByGoodsCommonIDString(implode(',', $commonid_array));
        $xianshi_list = Model('p_xianshi_goods')->getXianshiGoodsListByGoodsString(implode(',', $goodsid_array));
        foreach ($goods_list as $key => $value) {
            //抢购
            if (isset($groupbuy_list[$value['goods_commonid']])) {
                $goods_list[$key]['goods_price'] = $groupbuy_list[$value['goods_commonid']]['groupbuy_price'];
                $goods_list[$key]['group_flag'] = true;
            } else {
                $goods_list[$key]['group_flag'] = false;
            }

            //限时折扣
            if (isset($xianshi_list[$value['goods_id']]) && !$goods_list[$key]['group_flag']) {
                $goods_list[$key]['goods_price'] = $xianshi_list[$value['goods_id']]['xianshi_price'];
                $goods_list[$key]['xianshi_flag'] = true;
            } else {
                $goods_list[$key]['xianshi_flag'] = false;
            }

            //商品图片url
            $goods_list[$key]['goods_image_url'] = cthumb($value['goods_image'], 360, $value['store_id']);
			
			if (empty($value['goods_discount']) || $value['goods_discount']>=10 || $value['goods_discount']<=0){
				$goods_list[$key]['goods_discount'] = round($value['goods_price']/$value['goods_marketprice']*10,1);
			}
			if ($goods_list[$key]['goods_discount']>=10 || $goods_list[$key]['goods_discount']<=0){
				$goods_list[$key]['goods_discount'] = 0;
			}
			
			//计算分销商品市场的利润
			$goods_list[$key]['profit_price'] = $value['goods_marketprice']*$value['baifen']*0.01;
            unset($goods_list[$key]['store_id']);
            unset($goods_list[$key]['goods_commonid']);
            unset($goods_list[$key]['im_distinct']);
        }

        return $goods_list;
    }

    /**
     * 商品详细页
     */
    public function goods_detailOp() {
        $goods_commonid = intval($_GET['goods_commonid']);
        // 商品详细信息
        $model_goods = Model('goods');
        $goods_detail = $model_goods->getGoodsDetail($goods_commonid);
        if (empty($goods_detail['goods_info'])) {
            output_error('商品不存在');
        }
		
		//销售数量		
		$rs = $model_goods->getGoodsList(array('goods_commonid'=>$goods_detail['goods_info']['goods_commonid']),'goods_salenum');
		$count = 0;
		foreach($rs as $v){
			$count += $v['goods_salenum'];
		}
		$goods_detail['goods_info']['goods_salenum'] = $count;
		
		//折扣处理
		if (empty($goods_detail['goods_info']['goods_discount']) || $goods_detail['goods_info']['goods_discount']>=10 || $goods_detail['goods_info']['goods_discount']<=0){
			$goods_detail['goods_info']['goods_discount'] = round(($goods_detail['goods_info']['goods_promotion_price'])/$goods_detail['goods_info']['goods_marketprice']*10,1);
		}
		
		$memberId = $this->getMemberIdIfExists();
		
		//获取会员等级
		if(!empty($_SESSION['M_grade_level'])){
			$goods_detail['member_grade'] = $_SESSION['M_grade_level'];
		}else{
			$model_member = Model('member');
			$member_info = $model_member->getMemberInfoByID($memberId,'mc_id,member_points,member_exppoints');
			$goods_detail['member_grade'] = $model_member->getOneMemberGradeLevel($member_info['member_exppoints']);
		}
		
		//Vip 使用云币抵扣 start zhangc
		$goodsVip = Logic('goods')->goodsVip($goods_id,$memberId);
		$goods_detail['goods_info']['vip_price'] = $goodsVip['vip_price'];
		$goods_detail['goods_info']['vip_points'] = ceil($goodsVip['vip_points']/C("points_trade"));
		$goods_detail['goods_info']['rebate_price'] = $goodsVip['rebate_price'];
		$goods_detail['goods_info']['rebate_points'] = ceil($goodsVip['rebate_points']);
		
        //推荐商品
        $model_store = Model('store');
        $hot_sales = $model_store->getHotSalesList($goods_detail['goods_info']['store_id'], 8, true);
        $goodsid_array = array();
        foreach($hot_sales as $value) {
            $goodsid_array[] = $value['goods_id'];
        }
		//手机专享价
        $sole_array = Model('p_sole')->getSoleGoodsList(array('goods_id' => array('in', $goodsid_array)));
        $sole_array = array_under_reset($sole_array, 'goods_id');
        $goods_commend_list = array();
        foreach($hot_sales as $value) {
            $goods_commend = array();
            $goods_commend['goods_id'] = $value['goods_id'];
            $goods_commend['goods_name'] = $value['goods_name'];
            $goods_commend['goods_price'] = $value['goods_price'];
            $goods_commend['goods_promotion_price'] = $value['goods_promotion_price'];
            if (!empty($sole_array[$value['goods_id']])) {
                $goods_commend['goods_promotion_price'] = $sole_array[$value['goods_id']]['sole_price'];
            }
            $goods_commend['goods_image_url'] = cthumb($value['goods_image'], 240);
            $goods_commend_list[] = $goods_commend;
        }        
        $goods_detail['goods_commend_list'] = $goods_commend_list;
		
		//店铺详细信息
        $store_info = $model_store->getStoreInfoByID($goods_detail['goods_info']['store_id']);
		
        $goods_detail['store_info']['store_id'] = $store_info['store_id'];
        $goods_detail['store_info']['store_name'] = $store_info['store_name'];
        $goods_detail['store_info']['member_id'] = $store_info['member_id'];
        $goods_detail['store_info']['member_name'] = $store_info['member_name'];
        $goods_detail['store_info']['avatar'] = getMemberAvatarForID($store_info['member_id']);
		$goods_detail['store_info']['goods_count'] = $store_info['goods_count'];
		$goods_detail['store_info']['store_collect'] = $store_info['store_collect']; //店铺收藏数量

		$goods_detail['store_info']['store_points_way'] = $store_info['store_points_way']; //店铺云币方式
        if ($store_info['is_own_shop']) {
            $goods_detail['store_info']['store_credit'] = array(
                'store_desccredit' => array (
                    'text' => '描述',
                    'credit' => 5,
                    'percent' => '----',
                    'percent_class' => 'equal',
                    'percent_text' => '平',
                ),
                'store_servicecredit' => array (
                    'text' => '服务',
                    'credit' => 5,
                    'percent' => '----',
                    'percent_class' => 'equal',
                    'percent_text' => '平',
                ),
                'store_deliverycredit' => array (
                    'text' => '物流',
                    'credit' => 5,
                    'percent' => '----',
                    'percent_class' => 'equal',
                    'percent_text' => '平',
                ),
            );
        } else {
            $storeCredit = array();
            $percentClassTextMap = array(
                'equal' => '平',
                'high' => '高',
                'low' => '低',
            );
            foreach ((array) $store_info['store_credit'] as $k => $v) {
                $v['percent_text'] = $percentClassTextMap[$v['percent_class']];
                $storeCredit[$k] = $v;
            }
            $goods_detail['store_info']['store_credit'] = $storeCredit;
        }		
		$goods_detail['store_info']['promotion_open'] = $store_info['promotion_open']; //在线申请推广员 0:关闭 1:开启
		$goods_detail['store_info']['saleman_open'] = $store_info['saleman_open']; //在线申请导购员 0:关闭 1:开启				
		$goods_detail['store_info']['branch_op'] = $store_info['branch_op']; //充许开分店 0:不充许 1:充许
		//分店		
		if ($store_info['branch_op']==1){
		    $goods_detail['store_info']['branch_count'] = $model_store->getBranchCount($store_info['store_id']);
			$goods_detail['store_info']['branch_apply'] = ($goods_detail['store_info']['branch_count'] < $store_info['branch_limit'])?1:0;
		}else{
			$goods_detail['store_info']['branch_count'] = 0;
			$goods_detail['store_info']['branch_apply'] = 0;
		}		
		
		//店铺推广
		if (OPEN_STORE_EXTENSION_STATE == 1 && $store_info['extension_op']==1){
			$model_extension	= Model('extension');
			//推广员
			$goods_detail['store_info']['promotion_count'] = $model_extension->getPromotionCount($store_info['store_id']);							
		    if ($store_info['promotion_open']==1){
				if ($store_info['promotion_limit']<=0){
					$goods_detail['store_info']['promotion_apply'] = 1;
				}else{
			        $goods_detail['store_info']['promotion_apply'] = ($goods_detail['store_info']['promotion_count'] < $store_info['promotion_limit'])?1:0;
				}
		    }else{
			    $goods_detail['store_info']['promotion_apply'] = 0;
		    }
			//导购员
		    $goods_detail['store_info']['saleman_count'] = $model_extension->getSalemanCount($store_info['store_id']);
		    if ($store_info['saleman_open']==1){
				if ($store_info['saleman_limit']<=0){		
			        $goods_detail['store_info']['saleman_apply'] = 1;
				}else{
					$goods_detail['store_info']['saleman_apply'] = ($goods_detail['store_info']['saleman_count'] < $store_info['saleman_limit'])?1:0;
				}
		    }else{
			    $goods_detail['store_info']['saleman_apply'] = 0;
		    }						
		}else{
			$goods_detail['store_info']['promotion_count'] = 0;
			$goods_detail['store_info']['promotion_apply'] = 0;
			$goods_detail['store_info']['saleman_count'] = 0;
			$goods_detail['store_info']['saleman_apply'] = 0;
		}
		//判断是否是导购员，如果是则在商品详情页不显示推广链接
		$extension_type = 0;
		if (OPEN_STORE_EXTENSION_STATE > 0){
		    $extension_id=$_SESSION['member_id'];//cookie('iMall_extension');		
		    if (!empty($extension_id)){
		        //$extension_id=urlsafe_b64decode($extension_id);
		        $extension_type = Model('member')->getMemberTypeByID($extension_id);	
			}
		}
		$goods_detail['store_info']['extension_type'] = $extension_type;
		$goods_detail['store_info']['area_info'] = $store_info['area_info']; //地区内容
		$goods_detail['store_info']['cash_deposit'] = $store_info['cash_deposit']; //保证金
		$goods_detail['store_info']['store_baozhrmb'] = $store_info['store_baozhrmb']; //保证金
		
		// 如果已登录 判断该商品是否已被收藏
        if ($memberId = $this->getMemberIdIfExists()) {
            $c = (int) Model('favorites')->getGoodsFavoritesCountByGoodsId($goods_id, $memberId);
            $goods_detail['is_favorate'] = $c > 0;
            $goods_detail['cart_count'] = Model('cart')->countCartByMemberId($memberId);
			
			//分销
			if ($_SESSION['M_mc_id']==1 || $_SESSION['M_mc_id']==2){
			    $goods_detail['is_distribute'] = 1;
			}
        }
		
		//运费信息
		$area_id = 0;		
		$goods_hair_info = $this->_calc($area_id,$goods_id);		
        $goods_detail['goods_hair_info'] = $goods_hair_info;		
		//店铺代金劵
		//$voucher = $model_store->getVoucherList($goods_detail['goods_info']['store_id']);
        //$goods_detail['voucher'] = $voucher;		
		//评价信息		
        $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($goods_id);
        $goods_detail['goods_evaluate_info'] = $goods_evaluate_info;
		//评价列表		
		$where = array();
		$where['geval_goodsid'] = $goods_id;
        $goods_eval_list = Model('evaluate_goods')->getEvaluateGoodsList($where,10,'geval_id desc','geval_scores,geval_addtime_date,geval_frommembername,geval_content');
        $goods_detail['goods_eval_list'] = $goods_eval_list;
		
        //商品详细信息处理
        $goods_detail = $this->_goods_detail_extend($goods_detail);

        if($goods_detail){
			$model_goods_browse = Model('goods_browse')->addViewedGoods($goods_id,$memberId); //加入浏览历史数据库
			output_data($goods_detail);
		}
    }

    /**
     * 商品详细信息处理
     */
    private function _goods_detail_extend($goods_detail) {
        //整理商品规格
        unset($goods_detail['spec_list']);
        $goods_detail['spec_list'] = $goods_detail['spec_list_mobile'];
        unset($goods_detail['spec_list_mobile']);

        //整理商品图片
        unset($goods_detail['goods_image']);
        $goods_detail['goods_image'] = implode(',', $goods_detail['goods_image_mobile']);
        unset($goods_detail['goods_image_mobile']);

        //商品链接
		//推广处理 导购员或推广员链接
		$extension_id = '';
		if ((OPEN_STORE_EXTENSION_STATE > 0 && ($_SESSION['M_mc_id']==1 || $_SESSION['M_mc_id']==2))){		    
		    $extension_id=urlsafe_b64encode($_SESSION['member_id']);
		}
		$goods_detail['goods_info']['goods_url'] = urlShop('goods' , 'index', array('goods_id'=>$goods_detail['goods_info']['goods_id'],'extension'=>$extension_id));
		//推广链接
		$goods_detail['store_info']['apply_extension_url'] = intval($_SESSION['is_login']) == 1?'extension_store_apply.html':'extension_store_register.html';
        //$goods_detail['goods_info']['goods_url'] = WAP_SITE_URL . '/tmpl/product_detail.html?goods_id=' . $goods_detail['goods_info']['goods_id']; 
		

        //整理数据
        unset($goods_detail['goods_info']['goods_commonid']);
        unset($goods_detail['goods_info']['gc_id']);
        unset($goods_detail['goods_info']['gc_name']);
        //unset($goods_detail['goods_info']['store_id']);
        unset($goods_detail['goods_info']['store_name']);
        unset($goods_detail['goods_info']['brand_id']);
        unset($goods_detail['goods_info']['brand_name']);
        unset($goods_detail['goods_info']['type_id']);
        unset($goods_detail['goods_info']['goods_image']);
        //unset($goods_detail['goods_info']['goods_body']);
        unset($goods_detail['goods_info']['goods_state']);
        unset($goods_detail['goods_info']['goods_stateremark']);
        unset($goods_detail['goods_info']['goods_verify']);
        unset($goods_detail['goods_info']['goods_verifyremark']);
        unset($goods_detail['goods_info']['goods_lock']);
        unset($goods_detail['goods_info']['goods_addtime']);
        unset($goods_detail['goods_info']['goods_edittime']);
        unset($goods_detail['goods_info']['goods_selltime']);
        unset($goods_detail['goods_info']['goods_show']);
        unset($goods_detail['goods_info']['goods_commend']);
        unset($goods_detail['goods_info']['explain']);
        //unset($goods_detail['goods_info']['cart']);
        unset($goods_detail['goods_info']['buynow_text']);
        unset($goods_detail['groupbuy_info']);
        unset($goods_detail['xianshi_info']);

        return $goods_detail;
    }

    /**
     * 商品详细页
     */
    public function goods_bodyOp() {
        header("Access-Control-Allow-Origin:*");
        $goods_id = intval($_GET ['goods_id']);

        $model_goods = Model('goods');

        $goods_info = $model_goods->getGoodsInfoByID($goods_id, 'goods_commonid');
        $goods_common_info = $model_goods->getGoodsCommonInfoByID($goods_info['goods_commonid']);
        $Iptstore = "<input type='hidden' name='store_id' id='store_id' value=".$goods_common_info['store_id']." >";
        $goods_common_info['goods_body'] = $Iptstore.$goods_common_info['goods_body'];
        Tpl::output('goods_common_info', $goods_common_info);
        Tpl::showpage('goods_body');
    }
	
	/**
     * 商品搜索自动匹配
     */
    public function auto_completeOp() {		
		$data = array();
		if ($_GET['term'] != '' && cookie('his_sh') != '') {
            $corrected = explode('~', cookie('his_sh'));			
            if ($corrected != '' && count($corrected) > 0) {				
                foreach ($corrected as $word)
                {    
				    if (stristr($word,$_GET['term'])){
                         $data[] = $word;
				    }
                }                
            }
        }
        if (!C('fullindexer.open')){
			output_data(array('list'=>$data));
		}
		//output_error('1000');
        try {
            require(BASE_DATA_PATH.'/api/xs/lib/XS.php');
            $obj_doc = new XSDocument();
            $obj_xs = new XS(C('fullindexer.appname'));
            $obj_index = $obj_xs->index;
            $obj_search = $obj_xs->search;
            $obj_search->setCharset(CHARSET);
            $corrected = $obj_search->getExpandedQuery($_GET['term']);
            if (count($corrected) !== 0) {
                $data = array();
                foreach ($corrected as $word)
                {
                    $row['id'] = $word;
                    $row['label'] = $word;
                    $row['value'] = $word;
                    $data[] = $row;
                }
                output_data($data);
            }
        } catch (XSException $e) {
            if (is_object($obj_index)) {
                $obj_index->flushIndex();
            }
			output_error($e->getMessage());
        }		
	}	
		
	/**
     * 商品详细页运费显示
     *
     * @return unknown
     */
    public function calcOp(){
        $area_id = intval($_GET['area_id']);
        $goods_id = intval($_GET['goods_id']);
        output_data($this->_calc($area_id, $goods_id));
    }

    public function _calc($area_id,$goods_id,$buy_num=1){
        $goods_info = Model('goods')->getGoodsInfo(array('goods_id'=>$goods_id),'transport_id,store_id,goods_freight');
        $store_info = Model('store')->getStoreInfoByID($goods_info['store_id']);
        if ($area_id <= 0) {
            if (strpos($store_info['deliver_region'],'|')) {
                $store_info['deliver_region'] = explode('|', $store_info['deliver_region']);
                $store_info['deliver_region_ids'] = explode(' ', $store_info['deliver_region'][0]);
            }
            $area_id = intval($store_info['deliver_region_ids'][1]);
            $area_name = $store_info['deliver_region'][1];
        }
        if ($goods_info['transport_id'] && $area_id > 0) {
            $freight_total = Model('transport')->calc_transport(intval($goods_info['transport_id']),$buy_num,$area_id);
            if ($freight_total > 0) {
                if ($store_info['store_free_price'] > 0) {
                    if ($freight_total >= $store_info['store_free_price']) {
                        $freight_total = '免运费';
                    } else {
                        $freight_total = '运费：'.$freight_total.' 元，店铺满 '.$store_info['store_free_price'].' 元 免运费';
                    }
                } else {
                    $freight_total = '运费：'.$freight_total.' 元';
                }
            } else {
                if ($freight_total === false) {
                    $if_store = false;
                }
                $freight_total = '免运费';
            }     
        } else {
            $freight_total = $goods_info['goods_freight'] > 0 ? '运费：'.$goods_info['goods_freight'].' 元' : '免运费';
        }
        return array('content'=>$freight_total,'if_store_cn'=>$if_store === false ? '无货' : '有货','if_store'=>$if_store === false ? false : true,'area_name'=>$area_name ? $area_name : '全国');
    }

	/*分店地址*/
    public function store_o2o_addrOp(){
        $store_id = intval($_GET ['store_id']);
        $model_store_map = Model('store_map');
        $addr_list_source = $model_store_map->getStoreMapList($store_id);
        foreach ($addr_list_source as $k => $v) {
            $addr_list_tmp = array();
            $addr_list_tmp['key'] = $k;
            $addr_list_tmp['map_id'] = $v['map_id'];
            $addr_list_tmp['name_info'] = $v['name_info'];
            $addr_list_tmp['address_info'] = $v['address_info'];
            $addr_list_tmp['phone_info'] = $v['phone_info'];
            $addr_list_tmp['bus_info'] = $v['bus_info'];
            $addr_list_tmp['province'] = $v['baidu_province'];
            $addr_list_tmp['city'] = $v['baidu_city'];
            $addr_list_tmp['district'] = $v['baidu_district'];
            $addr_list_tmp['street'] = $v['baidu_street'];
            $addr_list_tmp['lng'] = $v['baidu_lng'];
            $addr_list_tmp['lat'] = $v['baidu_lat'];
            $addr_list[] = $addr_list_tmp;
        }
        output_data(array('addr_list'=>$addr_list));
    }
	
	/**
     * 商品评价
     */
    public function goods_evaluateOp() {
		$goods_id = intval($_GET['goods_id']);
		if($goods_id <=0){
			output_error('产品不存在');
		}
		
		$goods_info = Model('goods')->getGoodsInfo(array('goods_id'=>$goods_id),'store_id');
        $goodsevallist = $this->_get_comments($goods_id, $_GET['type'], $this->page);
        $store_id = $goods_info['store_id'];
		$model_evaluate_goods = Model("evaluate_goods");
		$page_count = $model_evaluate_goods->gettotalpage();		
		output_data(array('goods_eval_list'=>$goodsevallist),mobile_page($page_count));
	
	}

	private function store_id($goods_id, $type, $page) {
        $condition = array();
        $condition['geval_goodsid'] = $goods_id;
        switch ($type) {
            case '1':
                $condition['geval_scores'] = array('in', '5,4');
                Tpl::output('type', '1');
                break;
            case '2':
                $condition['geval_scores'] = array('in', '3,2');
                Tpl::output('type', '2');
                break;
            case '3':
                $condition['geval_scores'] = array('in', '1');
                Tpl::output('type', '3');
                break;
			case '4':
                $condition['geval_image'] = array('neq', '');
                Tpl::output('type', '4');
                break;
			case '5':
                $condition['geval_content_again'] = array('neq', '');
                Tpl::output('type', '5');
                break;
        }

        //查询商品评分信息
        $model_evaluate_goods = Model("evaluate_goods");
        $goodsevallist = $model_evaluate_goods->getEvaluateGoodsList($condition, $page);
        foreach($goodsevallist as $key=>$value){
			$goodsevallist[$key]['member_avatar'] = getMemberAvatarForID($value['geval_frommemberid']);
		}
		return $goodsevallist;
    }
    
    /**
     * 获取商品详细
     */
    public function goods_infoOp() {
    	$goods_id = intval($_GET ['goods_id']);
    	// 商品详细信息
    	$model_goods = Model('goods');
    	$goods_info = $model_goods->getGoodsInfo(array('goods_id'=>$goods_id),'store_id');
    	if (empty($goods_info)) {
    		output_error('商品不存在'.$goods_id);
    	}
    	echo $goods_info['store_id'];
    }
    /**
     * 根据商品条形码，获取商品ID
     */
    public function get_goodsIDOp() {
    	$gscancode = htmlspecialchars_decode($_GET['scancode']); //获取的格式:"3533631781002"
    	$scan_temp = explode(",",$gscancode);//处理后的格式:3533631781002"
	$scancode = substr($scan_temp[1], 0, -1);
		$store_id = $_GET['store_id'];
		$gwhere = array();
		if(!empty($store_id)){
			$gwhere['store_id'] = $store_id;
		}
		$gwhere['goods_barcode'] = $scancode;
    	// 商品详细信息
    	$model_goods = Model('goods');
    	$goods_info = $model_goods->getGoodsInfo($gwhere,'goods_id');
    	if (empty($goods_info)&&!empty($store_id)) {
    		$goods_info = $model_goods->getGoodsInfo(array('goods_barcode'=>$scancode),'goods_id');
		if(empty($goods_info)){
		   output_error('此店铺并未上架该商品，商品条形码是：'.$scancode."，请联系店长~");
		}else{
	    		output_error($store_id.'商品条形码不存在：'.$scancode);
	    	}
    	}
    	output_data(array('goods_id'=>$goods_info['goods_id']));
    }
    
}