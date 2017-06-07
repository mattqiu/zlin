<?php
/**
 * 分销市场
 *
 * @license    
 * @link  
 */

defined('InIMall') or exit('Access Invalid!');

class fx_marketControl extends BaseSellerControl {
	//每页显示商品数
	const PAGESIZE = 24;
	
	//模型对象
	private $_model_search;
	
    public function __construct() {
        parent::__construct ();
        Language::read('store_goods_index');
    }
	
    /**
     * 单个商品信息页
     */
    public function indexOp() {
       	
       	
        Tpl::showpage('store_fx_market.index');
    }
    
    /**
     * 商品详情页
     */
    public function goods_detailOp() {
    	
    	$goods_id = intval($_GET['goods_id']);
    
    	// 商品详细信息
    	$model_goods = Model('goods');
    	$goods_detail = $model_goods->getGoodsDetail($goods_id);
    	$goods_info = $goods_detail['goods_info'];
    	if (empty($goods_info)) {
    		showMessage(L('goods_index_no_goods'), '', 'html', 'error');
    	}
    
    	//销售数量
    	$rs = $model_goods->getGoodsList(array('goods_commonid'=>$goods_info['goods_commonid']));
    	$count = 0;
    	foreach($rs as $v){
    		$count += $v['goods_salenum'];
    	}
    	$goods_info['goods_salenum'] = $count;
    	//折扣处理
    	if (empty($goods_info['goods_discount']) || $goods_info['goods_discount']>=10 || $goods_info['goods_discount']<=0){
    		$goods_info['goods_discount'] = round(($goods_info['goods_promotion_price'])/$goods_info['goods_marketprice']*10,1);
    	}
    
    	$this->getStoreInfo($goods_info['store_id']);
    
    	Tpl::output('spec_list', $goods_detail['spec_list']);
    	Tpl::output('spec_image', $goods_detail['spec_image']);
    	Tpl::output('goods_image', $goods_detail['goods_image']);
    	Tpl::output('mansong_info', $goods_detail['mansong_info']);
    	Tpl::output('gift_array', $goods_detail['gift_array']);
    
    	// 生成缓存的键值
    	$hash_key = $goods_info['goods_id'];
    	$_cache = rcache($hash_key, 'product');
    	if (empty($_cache)) {
    		// 查询SNS中该商品的信息
    		$snsgoodsinfo = Model('sns_goods')->getSNSGoodsInfo(array('snsgoods_goodsid' => $goods_info['goods_id']), 'snsgoods_likenum,snsgoods_sharenum');
    		$data = array();
    		$data['likenum'] = $snsgoodsinfo['snsgoods_likenum'];
    		$data['sharenum'] = $snsgoodsinfo['snsgoods_sharenum'];
    		// 缓存商品信息
    		wcache($hash_key, $data, 'product');
    		$_cache = $data;
    	}
    	$goods_info = array_merge($goods_info, $_cache);
    
    	$inform_switch = true;
    	// 检测商品是否下架,检查是否为店主本人
    	if ($goods_info['goods_state'] != 1 || $goods_info['goods_verify'] != 1 || $goods_info['store_id'] == $_SESSION['store_id']) {
    		$inform_switch = false;
    	}
    	Tpl::output('inform_switch',$inform_switch );
    
    	// 如果使用运费模板
    	if ($goods_info['transport_id'] > 0) {
    		// 取得三种运送方式默认运费
    		$model_transport = Model('transport');
    		$transport = $model_transport->getExtendList(array('transport_id' => $goods_info['transport_id'], 'is_default' => 1));
    		if (!empty($transport) && is_array($transport)) {
    			foreach ($transport as $v) {
    				$goods_info[$v['type'] . "_price"] = $v['sprice'];
    			}
    		}
    	}
    
    	Tpl::output('goods', $goods_info);
    
    	$model_plate = Model('store_plate');
    	// 顶部关联版式
    	if ($goods_info['plateid_top'] > 0) {
    		$plate_top = $model_plate->getStorePlateInfoByID($goods_info['plateid_top']);
    		Tpl::output('plate_top', $plate_top);
    	}
    	// 底部关联版式
    	if ($goods_info['plateid_bottom'] > 0) {
    		$plate_bottom = $model_plate->getStorePlateInfoByID($goods_info['plateid_bottom']);
    		Tpl::output('plate_bottom', $plate_bottom);
    	}
    
    	Tpl::output('store_id', $goods_info['store_id']);
    
    	// 输出一级地区
    	$area_list = Model('area')->getTopLevelAreas();
    
    	if (strtoupper(CHARSET) == 'GBK') {
    		$area_list = Language::getGBK($area_list);
    	}
    	Tpl::output('area_list', $area_list);
    
    	//优先得到推荐商品
    	$goods_commend_list = $model_goods->getGoodsOnlineList(array('store_id' => $goods_info['store_id'], 'goods_commend' => 1), 'goods_id,goods_name,goods_jingle,goods_image,store_id,goods_price', 0, 'rand()', 5, 'goods_commonid');
    	Tpl::output('goods_commend',$goods_commend_list);
    
    	// 当前位置导航
    	$nav_link_list = Model('goods_class')->getGoodsClassNav($goods_info['gc_id'], 0);
    	$nav_link_list[] = array('title' => $goods_info['goods_name']);
    	Tpl::output('nav_link_list', $nav_link_list);
    
    	//评价信息
    	$goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($goods_id);
    	Tpl::output('goods_evaluate_info', $goods_evaluate_info);
    
    	$seo_param = array();
    	$seo_param['name'] = $goods_info['goods_name'];
    	$seo_param['key'] = $goods_info['goods_keywords'];
    	$seo_param['description'] = $goods_info['goods_description'];
    	Model('seo')->type('product')->param($seo_param)->show();
    	Tpl::showpage('store_fx_market.goods');
    	
    }
    
    //<!-- 分销选入店铺 start zhang -->
    public function fx_addOp(){
    
    	header("Content-Type: text/html; charset=UTF-8");
    	//获取分销商信息
    	$store_id = $_SESSION['store_id'];
    	$store_name = $_SESSION['store_name'];
    	//商家是否登陆
    	if(empty($store_id)){
    		redirect(urlShop('seller_login', 'show_login'));
    	}
    	$goods_id = $_POST['fx_id'];
    	$model_goods = Model('goods');
    	//获取商品信息
    	$goodsDel = $model_goods->getGoodsDetail($goods_id);//->field('goods_id,goods_commonid,goods_name,is_market,store_id,store_name,goods_image')->find($goods_id);  //查询商品
    	
    	$goods = $goodsDel['goods_info'];
    	$goods_name = $goods['goods_name'];  //商品名称
//     	$is_market = $goods['is_market'];//是否加入市场
    
//     	if($is_market==0 && empty($is_market)){
//     		echo '<script>alert("该商品并未加入分销市场，不能选入店铺！");window.history.go(-1);</script>';exit;
//     	}
    	//echo '<img src="../data/upload/shop/store/goods/'.$goods['store_id'].'/'.$goods['goods_image'].' ">';
    
    	//获取供应商信息
    	$up_name = $goods['store_name'];  //查询商户店铺名称
    	$up_id = $goods['store_id'];
    	$goods_commonid = $goods['goods_commonid'];
    	/**
    	 * //根据商家店铺的ID和商品的公共Id
    	 * 先查询出此商家 关联该商品有多少个属性的商品
    	*/
    	$condition =array();
    	$condition['store_id'] = $up_id;
    	$condition['goods_commonid'] = $goods_commonid;
    	$up_goodsList = $model_goods->getGoodsOnlineList($condition);
    	if($store_id == $up_id){
    		echo '<script>alert("不能分销自己店铺的商品");window.history.go(-1);</script>';exit;
    	}else{
    		/**
    		 * //没看懂暂时屏蔽
    		 $data = array('fd'=>1,'store_id'=>$up_id);
    		 $store = Model('store');
    		 $store->update($data);
    		 */
    		$goods_insert = array();
    		$rs_arr = array();
    		if(is_array($up_goodsList)){
    			foreach ($up_goodsList as $key=>$grow){
    				//根据商品属性和分销商ID、商品的公共ID可以查出此商品是否已经存在
    				$scdtion = array();
    				$scdtion['store_id'] = $store_id;
    				$scdtion['goods_commonid'] = $grow['goods_commonid'];
    				$scdtion['goods_spec'] = $grow['goods_spec'];
    				$sgcount = $model_goods->getGoodsCount($scdtion);
    					
    				if(empty($sgcount)){
    					/**
    					 * 图片复制处理，暂时不需要
    					 *
    					 * 	$exp = explode('.',$goods['goods_image']);
    						$exp[0] .= '_360.';
    						$r = implode($exp);
    						$img = explode('.',$goods['goods_image']);
    						$img[0] .= '_60.';
    						$rl = implode($img);
    						$file = dirname(__FILE__).'../../data/upload/shop/store/goods/'.$store_id;
    
    						if(!file_exists($file)){
    						mk_dir($file);
    						}
    						$image = $file.'/'.$goods['goods_image'];    //供货商图片
    						$image2 = $file.'/'.$r;
    						$image3 = $file.'/'.$rl;
    						recurse_copy($image,$image2);
    						recurse_copy($image,$image3);
    						*/
    					$goods_insert = array_slice($grow,1); //去掉goods_id
    					$goods_insert['store_id'] =$store_id;
    					$goods_insert['store_name'] =$store_name;
    					$goods_insert['up_id'] =$up_id;
    					$goods_insert['up_name'] =$up_name;
    					$goods_insert['baifen'] =0;
    					$goods_insert['is_market'] =0;
    					$goods_insert['goods_addtime']=	time();
    					$res = $model_goods->addGoods($goods_insert);
    					// 生成商品二维码
    					//require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
    					//$PhpQRCode = new PhpQRCode();
    					//$PhpQRCode->BuildGoodsQRCode($store_id,$grow['goods_id'],$grow['goods_image']);
    					if($res){
    						$rs_arr[$key] = 1;
    					}else{
    						$rs_arr[$key] = 0;
    					}
    				}else{
    					//该商品已经重复分销无需再分销
    					$rs_arr[$key] = 0;
    					//echo "=重复商品总数：".$sgcount."<br>".$grow['goods_spec'];
    					//echo '<script>alert("该商品已加入过店铺，不能重复分销");//window.history.go(-1);</script>';
    				}
    			}
    		}
    		if(in_array("1", $rs_arr)){
    			//echo '字符 1 在 $rs_arr 数组中存在';
    			echo '<script>alert("选入店铺成功");window.history.go(-1);</script>';exit;
    		}else{
    			//echo '字符 0 在 $rs_arr 数组中存在';
    			echo '<script>alert("选入店铺失败，可能是该商品已经加入过店铺！");window.history.go(-1);</script>';exit;
    		}
    	}
    
    }
    //<!-- 分销选入店铺 end zhang -->
    
    protected function getStoreInfo($store_id) {
    	$model_store = Model('store');
    	$store_info = $model_store->getStoreOnlineInfoByID($store_id);
    	//print_r($store_info);
    	if(empty($store_info)) {
    		showMessage(L('im_store_close'), '', '', 'error');
    	}
    
    	$this->outputStoreInfo($store_info);
    }
    /**
     * 检查店铺开启状态
     *
     * @param int $store_id 店铺编号
     * @param string $msg 警告信息
     */
    protected function outputStoreInfo($store_info){
    	//店铺分类
    	$goodsclass_model = Model('store_goods_class');
    	$goods_class_list = $goodsclass_model->getShowTreeList($store_info['store_id']);
    	Tpl::output('goods_class_list', $goods_class_list);
    
    	$model_store = Model('store');
    	//$model_seller = Model('seller');
    	if(!$this->store_decoration_only) {
    		//热销排行
    		$hot_sales = $model_store->getHotSalesList($store_info['store_id'], 5);
    		Tpl::output('hot_sales', $hot_sales);
    
    		//收藏排行
    		$hot_collect = $model_store->getHotCollectList($store_info['store_id'], 5);
    		Tpl::output('hot_collect', $hot_collect);
    	}
    
    	//分店
    	if ($store_info['branch_op']==1){
    		$store_info['branch_count'] = $model_store->getBranchCount($store_info['store_id']);
    		$store_info['branch_apply'] = ($store_info['branch_count'] < $store_info['branch_limit'])?1:0;
    	}else{
    		$store_info['branch_count'] = 0;
    		$store_info['branch_apply'] = 0;
    	}
    	$this->store_info['branch_count'] = $store_info['branch_count'];
    	$this->store_info['branch_apply'] = $store_info['branch_apply'];
    	//店铺推广
    	if (OPEN_STORE_EXTENSION_STATE == 1 && $store_info['extension_op']==1){
    		$model_extension	= Model('extension');
    		//推广员
    		$store_info['promotion_count'] = $model_extension->getPromotionCount($store_info['store_id']);
    			
    		if ($store_info['promotion_open']==1){
    			if ($store_info['promotion_limit']<=0){
    				$store_info['promotion_apply'] = 1;
    			}else{
    				$store_info['promotion_apply'] = ($store_info['promotion_count'] < $store_info['promotion_limit'])?1:0;
    			}
    		}else{
    			$store_info['promotion_apply'] = 0;
    		}
    		//导购员
    		$store_info['saleman_count'] = $model_extension->getSalemanCount($store_info['store_id']);
    		if ($store_info['saleman_open']==1){
    			if ($store_info['saleman_limit']<=0){
    				$store_info['saleman_apply'] = 1;
    			}else{
    				$store_info['saleman_apply'] = ($store_info['saleman_count'] < $store_info['saleman_limit'])?1:0;
    			}
    		}else{
    			$store_info['saleman_apply'] = 0;
    		}
    	}else{
    		$store_info['promotion_count'] = 0;
    		$store_info['promotion_apply'] = 0;
    		$store_info['saleman_count'] = 0;
    		$store_info['saleman_apply'] = 0;
    	}
    	//判断是否是导购员，如果是则在商品详情页不显示推广链接
    	$extension_type = 0;
    	$extension_id=cookie('iMall_extension');
    	if (!empty($extension_id)){
    		$extension_id=urlsafe_b64decode($extension_id);
    		$extension_type = Model('member')->getMemberTypeByID($extension_id);
    	}
    	$store_info['extension_type'] = $extension_type;
    	 
    	$this->store_info['promotion_count'] = $store_info['promotion_count'];
    	$this->store_info['promotion_apply'] = $store_info['promotion_apply'];
    	$this->store_info['saleman_count'] = $store_info['saleman_count'];
    	$this->store_info['saleman_apply'] = $store_info['saleman_apply'];
    	$this->store_info['extension_type'] = $store_info['extension_type'];
    
    	Tpl::output('store_info', $store_info);
    	Tpl::output('page_title', $store_info['store_name']);
    }
    /**
     * 商品评论
     */
    public function searchOp() {
    	$this->_model_search = Model('search');
    	 
    	$store_id = $_SESSION['store_id'];
    	if (empty($store_id)) {
    		showMessage("您不是商家，不能进入市场选货", urlShop('seller_login', 'show_login'), 'html', 'error');
    	}
    	//显示左侧分类
    	//默认分类，从而显示相应的属性和品牌
    	$default_classid = intval($_GET['cate_id']);
    	if (intval($_GET['cate_id']) > 0) {
    		$goods_class_array = $this->_model_search->getLeftCategory(array($_GET['cate_id']));
    	} elseif ($_GET['keyword'] != '') {
    		//从TAG中查找分类
    		$goods_class_array = $this->_model_search->getTagCategory($_GET['keyword']);
    		//取出第一个分类作为默认分类，从而显示相应的属性和品牌
    		$default_classid = $goods_class_array[0];
    		$goods_class_array = $this->_model_search->getLeftCategory($goods_class_array, 1);;
    	}else{
    		$goods_class_array = Model('goods_class')->get_all_category();
    	}
    	$goods_class_array = json_encode($goods_class_array);
    	Tpl::output('goods_class_array', $goods_class_array);
    	Tpl::output('default_classid', $default_classid);
    	 
    	//优先从全文索引库里查找
    	list($indexer_ids,$indexer_count) = $this->_model_search->indexerSearch($_GET,self::PAGESIZE);
    	
    	//获得经过属性过滤的商品信息
    	list($goods_param, $brand_array, $initial_array, $attr_array, $checked_brand, $checked_attr) = $this->_model_search->getAttr($_GET, $default_classid);
    	Tpl::output('brand_array', $brand_array);
    	Tpl::output('initial_array', $initial_array);
    	Tpl::output('attr_array', $attr_array);
    	Tpl::output('checked_brand', $checked_brand);
    	Tpl::output('checked_attr', $checked_attr);
    	
    	//处理排序
    	$order = 'is_own_shop desc,goods_id desc';
    	if (in_array($_GET['key'],array('1','2','3'))) {
    		$sequence = $_GET['order'] == '1' ? 'asc' : 'desc';
    		$order = str_replace(array('1','2','3'), array('goods_salenum','goods_click','goods_promotion_price'), $_GET['key']);
    		$order .= ' '.$sequence;
    	}
    	$model_goods = Model('goods');
    	$model_goods_class = Model('goods_class');
    	// 字段
    	$fields = "goods_id,goods_commonid,goods_name,goods_jingle,gc_id,store_id,store_name,goods_price,goods_promotion_price,goods_promotion_type,baifen,is_market,goods_tradeprice,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_good_star,evaluation_count,is_virtual,is_fcode,is_appoint,is_presell,have_gift,store_type,promotion_cid,saleman_cid";
    	
    	$condition = array();
    	//搜索 放入市场的商品
    	$condition['is_market'] = "1";
    	if (is_array($indexer_ids)) {
    	
    		//商品主键搜索
    		$condition['goods_id'] = array('in',$indexer_ids);
    		$goods_list = $model_goods->getGoodsOnlineList($condition, $fields, 0, $order, self::PAGESIZE, null, false);
    	
    		//如果有商品下架等情况，则删除下架商品的搜索索引信息
    		if (count($goods_list) != count($indexer_ids)) {
    			$this->_model_search->delInvalidGoods($goods_list, $indexer_ids);
    		}
    	
    		pagecmd('setEachNum',self::PAGESIZE);
    		pagecmd('setTotalNum',$indexer_count);
    	
    	} else {
    		//执行正常搜索
    		if ($_SESSION['M_grade_level']>0){
    			$condition['view_grade'] = array('elt', $_SESSION['M_grade_level']);
    		}else{
    			$condition['view_grade'] = 0;
    		}
    	
    		if (isset($goods_param['class'])) {
    			$condition['gc_id_'.$goods_param['class']['depth']] = $goods_param['class']['gc_id'];
    		}
    		if (intval($_GET['b_id']) > 0) {
    			$condition['brand_id'] = intval($_GET['b_id']);
    		}
    		if ($_GET['keyword'] != '') {
    			$condition['goods_name|goods_jingle'] = array('like', '%' . $_GET['keyword'] . '%');
    		}
    		if (intval($_GET['area_id']) > 0) {
    			$condition['areaid_1'] = intval($_GET['area_id']);
    		}
    		if (intval($_GET['area_id2']) > 0) {
    			$condition['areaid_2'] = intval($_GET['area_id2']);
    		}
    		if (in_array($_GET['type'], array(1,2,3))) {
    			if ($_GET['type'] == 1) {
    				$condition['is_own_shop'] = 1;
    			} else if ($_GET['type'] == 2) {
    				$condition['store_type'] = 1;
    			} else if ($_GET['type'] == 3) {
    				$condition['store_type'] = 2;
    			}
    		}
    		if ($_GET['gift'] == 1) {
    			$condition['have_gift'] = 1;
    		}
    		if (isset($goods_param['goodsid_array'])){
    			$condition['goods_id'] = array('in', $goods_param['goodsid_array']);
    		}
    		
    		$goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, $fields, $order, self::PAGESIZE);
    	}
    	Tpl::output('show_page1', $model_goods->showpage(4));
    	Tpl::output('show_page', $model_goods->showpage(5));
    	 
    	// 商品多图
    	if (!empty($goods_list)) {
    		$commonid_array = array(); // 商品公共id数组
    		$storeid_array = array();       // 店铺id数组
    		foreach ($goods_list as $value) {
    			$commonid_array[] = $value['goods_commonid'];
    			$storeid_array[] = $value['store_id'];
    		}
    		$commonid_array = array_unique($commonid_array);
    		$storeid_array = array_unique($storeid_array);
    	
    		// 商品多图
    		$goodsimage_more = Model('goods')->getGoodsImageList(array('goods_commonid' => array('in', $commonid_array)));
    	
    		// 店铺
    		$store_list = Model('store')->getStoreMemberIDList($storeid_array);
    		//搜索的关键字
    		$search_keyword = trim($_GET['keyword']);
    		foreach ($goods_list as $key => $value) {
    			// 商品多图
    			foreach ($goodsimage_more as $v) {
    				if ($value['goods_commonid'] == $v['goods_commonid'] && $value['store_id'] == $v['store_id'] && $value['color_id'] == $v['color_id']) {
    					$goods_list[$key]['image'][] = $v;
    				}
    			}
    			// 店铺的开店会员编号
    			$store_id = $value['store_id'];
    			$goods_list[$key]['member_id'] = $store_list[$store_id]['member_id'];
    			$goods_list[$key]['store_domain'] = $store_list[$store_id]['store_domain'];
    	
    			if (empty($goods_list[$key]['goods_discount']) || $goods_list[$key]['goods_discount']>=10 || $goods_list[$key]['goods_discount']<=0){
    				$goods_list[$key]['goods_discount'] = round(($goods_list[$key]['goods_promotion_price'])/$goods_list[$key]['goods_marketprice']*10,1);
    			}
    			if ($goods_list[$key]['goods_discount']>=10 || $goods_list[$key]['goods_discount']<=0){
    				$goods_list[$key]['goods_discount'] = 0;
    			}
    			//将关键字置红
    			if ($search_keyword){
    				$goods_list[$key]['goods_name_highlight'] = str_replace($search_keyword,'<font style="color:#f00;">'.$search_keyword.'</font>',$value['goods_name']);
    			} else {
    				$goods_list[$key]['goods_name_highlight'] = $value['goods_name'];
    			}
    			 
    			//获取商品分类对应的平台扣点
    			$gc_info = $model_goods_class->getGoodsClassInfoById($value['gc_id']);
    			$goods_list[$key]['commis_rate'] = $gc_info['commis_rate'];
    		}
    	}
    	 
    	Tpl::output('goods_list', $goods_list);
    	if ($_GET['keyword'] != ''){
    		Tpl::output('show_keyword',  $_GET['keyword']);
    	} else {
    		Tpl::output('show_keyword',  $goods_param['class']['gc_name']);
    	}
    	
    	
    	Tpl::output('html_title', "市场选货");
    	// 当前位置导航
    	$nav_link_list = $model_goods_class->getGoodsClassNav(intval($_GET['cate_id']));
    	Tpl::output('nav_link_list', $nav_link_list );
    	 
    	// 得到自定义导航信息
    	$nav_id = intval($_GET['nav_id']) ? intval($_GET['nav_id']) : 0;
    	Tpl::output('index_sign', $nav_id);
    	
    	// 地区
    	//$province_array = Model('area')->getTopLevelAreas();
    	//Tpl::output('province_array', $province_array);
    	//addd by yangbaiyan
    	require(BASE_DATA_PATH.'/area/area.php');
    	$area_name ='不限地区';//$lang['goods_class_index_area'];
    	if (intval($_GET['area_id']) > 0) {
    		$area_name = $area_array[$_GET['area_id']]['area_name'];
    	}
    	if (intval($_GET['area_id2']) > 0) {
    		$area_name = $area_array[$_GET['area_id2']]['area_name'];
    	}
    	Tpl::output('area_name', mb_substr($area_name,0,4,'utf-8'));
    	
    	loadfunc('search');
    	
    	// 浏览过的商品
    	$viewed_goods = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'],20);
    	Tpl::output('viewed_goods',$viewed_goods);
    	Tpl::showpage('store_fx_market.search');
    }

    /**
     * 商品评价详细页
     */
    public function comments_listOp() {
        $goods_id = intval($_GET['goods_id']);

        // 商品详细信息
        $model_goods = Model('goods');
        $goods_info = $model_goods->getGoodsInfoByID($goods_id, '*');
        // 验证商品是否存在
        if (empty($goods_info)) {
            showMessage(L('goods_index_no_goods'), '', 'html', 'error');
        }
        Tpl::output('goods', $goods_info);

        $this->getStoreInfo($goods_info['store_id']);

        // 当前位置导航
        $nav_link_list = Model('goods_class')->getGoodsClassNav($goods_info['gc_id'], 0);
        $nav_link_list[] = array('title' => $goods_info['goods_name'], 'link' => urlShop('goods', 'index', array('goods_id' => $goods_id)));
        $nav_link_list[] = array('title' => '商品评价');
        Tpl::output('nav_link_list', $nav_link_list );

        //评价信息
        $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($goods_id);
        Tpl::output('goods_evaluate_info', $goods_evaluate_info);

        $seo_param = array ();

        $seo_param['name'] = $goods_info['goods_name'];
        $seo_param['key'] = $goods_info['goods_keywords'];
        $seo_param['description'] = $goods_info['goods_description'];
        Model('seo')->type('product')->param($seo_param)->show();

        $this->_get_comments($goods_id, $_GET['type'], 20);

        Tpl::showpage('goods.comments_list');
    }

    private function _get_comments($goods_id, $type, $page) {
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
        }

        //查询商品评分信息
        $model_evaluate_goods = Model("evaluate_goods");
        $goodsevallist = $model_evaluate_goods->getEvaluateGoodsList($condition, $page);
        Tpl::output('goodsevallist',$goodsevallist);
        Tpl::output('show_page',$model_evaluate_goods->showpage('5'));
    }

    /**
     * 销售记录
     */
    public function salelogOp() {
        $goods_id    = intval($_GET['goods_id']);
        if ($_GET['vr']) {
            $model_order = Model('vr_order');
            $sales = $model_order->getOrderAndOrderGoodsSalesRecordList(array('goods_id'=>$goods_id), '*', 10);
        } else {
            $model_order = Model('order');
             $sales = $model_order->getOrderAndOrderGoodsSalesRecordList(array('order_goods.goods_id'=>$goods_id), 'order_goods.*, orders.buyer_name, orders.add_time', 10);
        }
        Tpl::output('show_page',$model_order->showpage());
        Tpl::output('sales',$sales);

        Tpl::output('order_type', array(1=>'原价', 2=>'团购', 3=>'折扣', 4=>'套装', 5=>'赠品', 8=>'原价', 9=>'换购'));
        Tpl::output('order_vr_type', array(0=>'原价', 1=>'团购'));
        Tpl::showpage('goods.salelog','null_layout');
    }

    /**
     * 产品咨询
     */
    public function consultingOp() {
        $goods_id = intval($_GET['goods_id']);
        if($goods_id <= 0){
            showMessage(Language::get('wrong_argument'),'','html','error');
        }

        //得到商品咨询信息
        $model_consult = Model('consult');
        $where = array();
        $where['goods_id'] = $goods_id;
        if (intval($_GET['ctid']) > 0) {
            $where['ct_id'] = intval($_GET['ctid']);
        }
        $consult_list = $model_consult->getConsultList($where,'*','10');
        Tpl::output('consult_list',$consult_list);

        // 咨询类型
        $consult_type = rkcache('consult_type', true);
        Tpl::output('consult_type', $consult_type);

        Tpl::output('consult_able',$this->checkConsultAble());
        Tpl::showpage('goods.consulting', 'null_layout');
    }

    /**
     * 产品咨询
     */
    public function consulting_listOp() {
        Tpl::output('hidden_nctoolbar', 1);
        $goods_id    = intval($_GET['goods_id']);
        if($goods_id <= 0){
            showMessage(Language::get('wrong_argument'),'','html','error');
        }

        // 商品详细信息
        $model_goods = Model('goods');
        $goods_info = $model_goods->getGoodsInfoByID($goods_id, '*');
        // 验证商品是否存在
        if (empty($goods_info)) {
            showMessage(L('goods_index_no_goods'), '', 'html', 'error');
        }
        Tpl::output('goods', $goods_info);

        $this->getStoreInfo($goods_info['store_id']);

        // 当前位置导航
        $nav_link_list = Model('goods_class')->getGoodsClassNav($goods_info['gc_id'], 0);
        $nav_link_list[] = array('title' => $goods_info['goods_name'], 'link' => urlShop('goods', 'index', array('goods_id' => $goods_id)));
        $nav_link_list[] = array('title' => '商品咨询');
        Tpl::output('nav_link_list', $nav_link_list);

        //得到商品咨询信息
        $model_consult = Model('consult');
        $where = array();
        $where['goods_id'] = $goods_id;
        if (intval($_GET['ctid']) > 0) {
            $where['ct_id']  = intval($_GET['ctid']);
        }
        $consult_list = $model_consult->getConsultList($where, '*', 0, 20);
        Tpl::output('consult_list',$consult_list);
        Tpl::output('show_page', $model_consult->showpage());

        // 咨询类型
        $consult_type = rkcache('consult_type', true);
        Tpl::output('consult_type', $consult_type);

        $seo_param = array ();
        $seo_param['name'] = $goods_info['goods_name'];
        $seo_param['key'] = $goods_info['goods_keywords'];
        $seo_param['description'] = $goods_info['goods_description'];
        Model('seo')->type('product')->param($seo_param)->show();

        Tpl::output('consult_able',$this->checkConsultAble($goods_info['store_id']));
        Tpl::showpage('goods.consulting_list');
    }

    private function checkConsultAble( $store_id = 0) {
        //检查是否为店主本身
        $store_self = false;
        if(!empty($_SESSION['store_id'])) {
            if (($store_id == 0 && intval($_GET['store_id']) == $_SESSION['store_id']) || ($store_id != 0 && $store_id == $_SESSION['store_id'])) {
                $store_self = true;
            }
        }
        //查询会员信息
        $member_info    = array();
        $member_model = Model('member');
        if(!empty($_SESSION['member_id'])) $member_info = $member_model->getMemberInfoByID($_SESSION['member_id'],'is_allowtalk');
        //检查是否可以评论
        $consult_able = true;
        if((!C('guest_comment') && !$_SESSION['member_id'] ) || $store_self == true || ($_SESSION['member_id']>0 && $member_info['is_allowtalk'] == 0)){
            $consult_able = false;
        }
        return $consult_able;
    }

    /**
     * 商品咨询添加
     */
    public function save_consultOp(){
        //检查是否可以评论
        if(!C('guest_comment') && !$_SESSION['member_id']){
            showDialog(L('goods_index_goods_noallow'));
        }
        $goods_id    = intval($_POST['goods_id']);
        if($goods_id <= 0){
            showDialog(L('wrong_argument'));
        }
        //咨询内容的非空验证
        if(trim($_POST['goods_content'])== ""){
            showDialog(L('goods_index_input_consult'));
        }
        //表单验证
        $result = chksubmit(true,C('captcha_status_goodsqa'),'num');
        if (!$result){
            showDialog(L('invalid_request'));
        } elseif ($result === -11){
            showDialog(L('invalid_request'));
        }elseif ($result === -12){
            showDialog(L('wrong_checkcode'));
        }
        if (process::islock('commit')){
            showDialog(L('nc_common_op_repeat'));
        }else{
            process::addprocess('commit');
        }
        if($_SESSION['member_id']){
            //查询会员信息
            $member_model = Model('member');
            $member_info = $member_model->getMemberInfo(array('member_id'=>$_SESSION['member_id']));
            if(empty($member_info) || $member_info['is_allowtalk'] == 0){
                showDialog(L('goods_index_goods_noallow'));
            }
        }
        //判断商品编号的存在性和合法性
        $goods = Model('goods');
        $goods_info = $goods->getGoodsInfoByID($goods_id, 'goods_name,store_id');
        if(empty($goods_info)){
            showDialog(L('goods_index_goods_not_exists'));
        }
        //判断是否是店主本人
        if($_SESSION['store_id'] && $goods_info['store_id'] == $_SESSION['store_id']) {
            showDialog(L('goods_index_consult_store_error'));
        }
        //检查店铺状态
        $store_model = Model('store');
        $store_info = $store_model->getStoreInfoByID($goods_info['store_id']);
        if($store_info['store_state'] == '0' || intval($store_info['store_state']) == '2' || (intval($store_info['store_end_time']) != 0 && $store_info['store_end_time'] <= time())){
            showDialog(L('goods_index_goods_store_closed'));
        }
        //接收数据并保存
        $input  = array();
        $input['goods_id']          = $goods_id;
        $input['goods_name']        = $goods_info['goods_name'];
        $input['member_id']         = intval($_SESSION['member_id']) > 0?$_SESSION['member_id']:0;
        $input['member_name']       = $_SESSION['member_name']?$_SESSION['member_name']:'';
        $input['store_id']          = $store_info['store_id'];
        $input['store_name']        = $store_info['store_name'];
        $input['ct_id']             = intval($_POST['consult_type_id']);
        $input['consult_addtime']   = TIMESTAMP;
        if (strtoupper(CHARSET) == 'GBK') {
            $input['consult_content']   = Language::getGBK($_POST['goods_content']);
        }else{
            $input['consult_content']   = $_POST['goods_content'];
        }
        $input['isanonymous']       = $_POST['hide_name']=='hide'?1:0;
        $consult_model  = Model('consult');
        if($consult_model->addConsult($input)){
            showDialog(L('goods_index_consult_success'), 'reload', 'succ');
        }else{
            showDialog(L('goods_index_consult_fail'));
        }
    }

    /**
     * 异步显示优惠套装/推荐组合
     */
    public function get_bundlingOp() {
        $goods_id = intval($_GET['goods_id']);
        if ($goods_id <= 0) {
            exit();
        }
        $model_goods = Model('goods');
        $goods_info = $model_goods->getGoodsOnlineInfoByID($goods_id);
        if (empty($goods_info)) {
            exit();
        }

        // 优惠套装
        $array = Model('p_bundling')->getBundlingCacheByGoodsId($goods_id);
        if (!empty($array)) {
            Tpl::output('bundling_array', unserialize($array['bundling_array']));
            Tpl::output('b_goods_array', unserialize($array['b_goods_array']));
        }

        // 推荐组合
        if (!empty($goods_info) && $model_goods->checkIsGeneral($goods_info)) {
            $array = Model('p_combo_goods')->getComboGoodsCacheByGoodsId($goods_id);
            Tpl::output('goods_info', $goods_info);
            Tpl::output('gcombo_list', unserialize($array['gcombo_list']));
        }

        Tpl::showpage('goods_bundling', 'null_layout');
    }

    /**
     * 商品详细页运费显示
     *
     * @return unknown
     */
    public function calcOp(){
        if (!is_numeric($_GET['area_id']) || !is_numeric($_GET['tid'])) return false;
        $freight_total = Model('transport')->calc_transport(intval($_GET['tid']),intval($_GET['area_id']));
        if ($freight_total > 0) {
            if ($_GET['myf'] > 0) {
                if ($freight_total >= $_GET['myf']) {
                    $freight_total = '免运费';
                } else {
                    $freight_total = '运费：'.$freight_total.' 元，店铺满 '.$_GET['myf'].' 元 免运费';
                }      
            } else {
                $freight_total = '运费：'.$freight_total.' 元';
            }
        } else {
            if ($freight_total !== false) {
                $freight_total = '免运费';
            }
        }
        echo $_GET['callback'].'('.json_encode(array('total'=>$freight_total)).')';
    }

    /**
     * 到货通知
     */
    public function arrival_noticeOp() {
        if (!$_SESSION['is_login'] ){
            showMessage(L('wrong_argument'), '', '', 'error');
        }
        $member_info = Model('member')->getMemberInfoByID($_SESSION['member_id'], 'member_email,member_mobile');
        Tpl::output('member_info', $member_info);

        Tpl::showpage('arrival_notice.submit', 'null_layout');
    }

    /**
     * 到货通知表单
     */
    public function arrival_notice_submitOp() {
        $type = intval($_POST['type']) == 2 ? 2 : 1;
        $goods_id = intval($_POST['goods_id']);
        if ($goods_id <= 0) {
            showDialog(L('wrong_argument'), 'reload');
        }
        // 验证商品数是否充足
        $goods_info = Model('goods')->getGoodsInfoByID($goods_id, 'goods_id,goods_name,goods_storage,goods_state,store_id');
        if (empty($goods_info) || ($goods_info['goods_storage'] > 0 && $goods_info['goods_state'] == 1)) {
            showDialog(L('wrong_argument'), 'reload');
        }

        $model_arrivalnotice = Model('arrival_notice');
        // 验证会员是否已经添加到货通知
        $where = array();
        $where['goods_id'] = $goods_info['goods_id'];
        $where['member_id'] = $_SESSION['member_id'];
        $where['an_type'] = $type;
        $notice_info = $model_arrivalnotice->getArrivalNoticeInfo($where);
        if (!empty($notice_info)) {
            if ($type == 1) {
                showDialog('您已经添加过通知提醒，请不要重复添加', 'reload');
            } else {
                showDialog('您已经预约过了，请不要重复预约', 'reload');
            }
        }

        $insert = array();
        $insert['goods_id'] = $goods_info['goods_id'];
        $insert['goods_name'] = $goods_info['goods_name'];
        $insert['member_id'] = $_SESSION['member_id'];
        $insert['store_id'] = $goods_info['store_id'];
        $insert['an_mobile'] = $_POST['mobile'];
        $insert['an_email'] = $_POST['email'];
        $insert['an_type'] = $type;
        $model_arrivalnotice->addArrivalNotice($insert);

        $title = $type == 1 ? '到货通知' : '立即预约';
        $js = "ajax_form('arrival_notice', '". $title ."', '" . urlShop('goods', 'arrival_notice_succ', array('type' => $type)) . "', 480);";
        showDialog('','','js',$js);
    }

    /**
     * 到货通知添加成功
     */
    public function arrival_notice_succOp() {
        // 可能喜欢的商品
        $goods_list = Model('goods_browse')->getGuessLikeGoods($_SESSION['member_id'], 4);
        Tpl::output('goods_list', $goods_list);
        Tpl::showpage('arrival_notice.message', 'null_layout');
    }
    
    /**
     * 显示门店
     */
    public function show_chainOp() {
        $model_chain = Model('chain');
        $model_chain_stock = Model('chain_stock');
        $goods_id = $_GET['goods_id'];
        $stock_list = $model_chain_stock->getChainStockList(array('goods_id' => $goods_id, 'stock' => array('gt', 0)), 'chain_id');
        if (!empty($stock_list)) {
            $chainid_array = array();
            foreach ($stock_list as $val) {
                $chainid_array[] = $val['chain_id'];
            }
            $chain_array = $model_chain->getChainList(array('chain_id' => array('in', $chainid_array)));
            $chain_list = array();
            if (!empty($chain_array)) {
                foreach ($chain_array as $val) {
                    $chain_list[$val['area_id']][] = $val;
                }
            }
            
            Tpl::output('chain_list', json_encode($chain_list));
        }
        Tpl::showpage('goods.show_chain', 'null_layout');
    }
}
