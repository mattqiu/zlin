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

class goodsControl extends wxappHomeControl{

	public function __construct() {
        parent::__construct();
    }

    /**
     * 商品列表
     */
    public function goods_listOp() {
        $model_goods = Model('goods');
        $model_search = Model('search');
		$_REQUEST['is_book'] = 0;

        //查询条件
        $condition = array();
		// ==== 暂时不显示定金预售商品，手机端未做。  ====
        $condition['is_book'] = 0;
		if(!empty($_REQUEST['gc_id']) && intval($_REQUEST['gc_id']) > 0) {
            $condition['gc_id'] = $_REQUEST['gc_id'];
        } elseif (!empty($_REQUEST['keyword'])) {
            $condition['goods_name|goods_jingle|goods_serial|goods_barcode|goods_body'] = array('like', '%' . $_REQUEST['keyword'] . '%');
			
            if (strlen($_REQUEST['keyword']) <= 30 && !in_array($_REQUEST['keyword'],$his_sh_list)) {
				//在数组开头手插入一个元素
                if (array_unshift($his_sh_list, $_REQUEST['keyword']) > 8) {
					//删除最后一个数组元素
                    array_pop($his_sh_list);
                }
            }
            setIMCookie('his_sh', implode('~', $his_sh_list),2592000); //添加历史纪录
        } elseif (!empty($_REQUEST['barcode'])) {
            $condition['goods_barcode'] = $_REQUEST['barcode'];
        } elseif (!empty($_REQUEST['b_id']) && intval($_REQUEST['b_id'] > 0)) {
            $condition['brand_id'] = intval($_REQUEST['b_id']);
        }
		if(!empty($_REQUEST['store_id']) && intval($_REQUEST['store_id']) > 0) {
			$condition['store_id'] = $_REQUEST['store_id'];
		}
		
		//店铺服务
		if ($_REQUEST['ci'] && $_REQUEST['ci'] != 0) {
            //处理参数
            $search_ci= $_REQUEST['ci'];
            $search_ci_arr = explode('_',$search_ci);
            $search_ci_str = $search_ci.'_';
            $indexer_searcharr['search_ci_arr'] = $search_ci_arr;
        }

		if (!empty($_REQUEST['price_from']) && intval($_REQUEST['price_from'] > 0)) {
            $condition['goods_price'][] = array('egt',intval($_REQUEST['price_from']));
        }
		if (!empty($_REQUEST['price_to']) && intval($_REQUEST['price_to'] > 0)) {
            $condition['goods_price'][] = array('elt',intval($_REQUEST['price_to']));
        }
		if (intval($_REQUEST['area_id']) > 0) {
			$condition['areaid_1'] = intval($_REQUEST['area_id']);
		}
		
		//赠品
		if ($_REQUEST['gift'] == 1) {
			$condition['have_gift'] = 1;
		}
		//团购
		if ($_REQUEST['groupbuy'] == 1) {
			$condition['goods_promotion_type'][] = 1;
		}
		//限时折扣
		if ($_REQUEST['xianshi'] == 1) {
			$condition['goods_promotion_type'][] = 2;
		}
		//虚拟
		if ($_REQUEST['virtual'] == 1) {
			$condition['is_virtual'] = 2;
		}

        //所需字段
		$fieldstr = "goods_id,goods_commonid,store_id,goods_name,goods_price,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count,goods_promotion_price,goods_promotion_type";

        // 添加3个状态字段
		$fieldstr .= ',is_virtual,is_presell,is_fcode,have_gift,goods_jingle,store_name,promotion_amount,is_own_shop';

        //排序方式
        $order = $this->_goods_list_order($_REQUEST['key'], $_REQUEST['order']);

        //优先从全文索引库里查找
        list($indexer_ids,$indexer_count) = $model_search->indexerSearch($_REQUEST,$this->page);
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
        //header("content-type:text/html;charset=utf8");
        $goods_commonid = intval($_REQUEST['goods_commonid']);
        // 商品详细信息
        $model_goods = Model('goods');
        $goods_detail = $model_goods->getGoodsDetail($goods_commonid);
        $condition['store_id'] = $goods_detail['store_id'];

        //$goods_detail['store_info'] = $model_goods->getStoreDetail($condition);
        $store_info = $model_goods->getStoreDetail($condition);
        $spec_name  = unserialize($goods_detail['spec_name']);
        $spec_value = unserialize($goods_detail['spec_value']);
        foreach ($spec_value as $key => $value) {
            $spec_value[$key] = implode(',',$value);
        }
        //print_r(array_merge($spec_name));
        //print_r(array_merge($spec_value));
        $goods_detail['spec_name'] = array_merge($spec_name);
        $goods_detail['spec_value'] = array_merge($spec_value);
/*        foreach ($spec_name as $key => $value) {
            $spec_name[$key] = $value.':'.implode(',',$spec_value[$key]);
        }*/
        //print_r($spec_name);
        $all_info['goods_detail'] = $goods_detail;
        $all_info['store_info'] = $store_info;
        
        //print_r($all_info);
        output_data($all_info,'成功获取商品信息');
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
        if(!empty($goods_detail['goods_image_mobile'])){
        	$goods_detail['goods_image'] = implode(',', $goods_detail['goods_image_mobile']);
        	unset($goods_detail['goods_image_mobile']);
        }
        

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
        $goods_id = intval($_REQUEST ['goods_id']);

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
		if ($_REQUEST['term'] != '' && cookie('his_sh') != '') {
            $corrected = explode('~', cookie('his_sh'));			
            if ($corrected != '' && count($corrected) > 0) {				
                foreach ($corrected as $word)
                {    
				    if (stristr($word,$_REQUEST['term'])){
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
            $corrected = $obj_search->getExpandedQuery($_REQUEST['term']);
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
        $area_id = intval($_REQUEST['area_id']);
        $goods_id = intval($_REQUEST['goods_id']);
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
        $store_id = intval($_REQUEST ['store_id']);
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
		$goods_id = intval($_REQUEST['goods_id']);
		if($goods_id <=0){
			output_error('产品不存在');
		}
		
		$goods_info = Model('goods')->getGoodsInfo(array('goods_id'=>$goods_id),'store_id');
        $goodsevallist = $this->_REQUEST_comments($goods_id, $_REQUEST['type'], $this->page);
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
    	$goods_id = intval($_REQUEST ['goods_id']);
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
    	$gscancode = htmlspecialchars_decode($_REQUEST['scancode']); //获取的格式:"3533631781002"
    	$scan_temp = explode(",",$gscancode);//处理后的格式:3533631781002"
	$scancode = substr($scan_temp[1], 0, -1);
		$store_id = $_REQUEST['store_id'];
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