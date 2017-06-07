<?php
/**
 * 店铺
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class storeControl extends mobileStoreControl{	

	public function __construct() {
		
        parent::__construct();	
		
    }
	
    /**
     * 店铺首页
     */
    public function indexOp()
    {
    	$store_info = $this->store_info;
    	// 如果已登录 判断该店铺是否已被收藏
    	if ($memberId = $this->getMemberIdIfExists()) {
    		$c = (int) Model('favorites')->getStoreFavoritesCountByStoreId($this->store_id, $memberId);
    		$store_info['is_favorate'] = $c > 0;
    	} else {
    		$store_info['is_favorate'] = false;
    	}
    	// 动态评分
    	if ($store_info['is_own_shop']) {
    		$store_info['store_credit_text'] = '官方店铺';
    	} else {
    		$store_info['store_credit_text'] = sprintf(
    				'描述: %0.1f, 服务: %0.1f, 物流: %0.1f',
    				$store_info['store_credit']['store_desccredit']['credit'],
    				$store_info['store_credit']['store_servicecredit']['credit'],
    				$store_info['store_credit']['store_deliverycredit']['credit']
    		);
    	}
    	// 页头背景图
    	$store_info['mb_title_img'] = $store_info['store_banner']? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$store_info['store_banner']: '';
    
    	$goods_list = array();
    	//店铺装修
    	$model_mb_special = Model('mb_special');
    	$store_decoration = $model_mb_special->getStoreMbSpecialIndex($this->store_id);
    	if (empty($store_decoration)){
    		// 轮播
    		$model_mb_ad = Model('mb_ad');
    		$adv_list = array();
    		$mb_ad_list = $model_mb_ad->getMbAdList(array(), null, 'link_sort asc', '*', $this->store_id);
    		foreach ($mb_ad_list as $value) {
    			$adv = array();
    			$adv['type'] = 1;
    			$adv['imgUrl'] = $value['link_pic_url'];
    			if(strstr($value['link_keyword'],"http://")){
    				$adv['link'] =  $value['link_keyword'];
    			}else{
    				$adv['link'] =WAP_SITE_URL+'/tmpl/product_list.html?keyword='+$value['link_keyword'];
    			}
    			$adv_list[] = $adv;
    		}
    		$store_info['mb_sliders'] = $adv_list;
    		/*
    		 $mbSliders = @unserialize($store_info['mb_sliders']);
    		 $store_info['mb_sliders'] = array();
    		 if ($mbSliders) {
    		 foreach ((array) $mbSliders as $s) {
    		 if ($s['img']) {
    		 $s['imgUrl'] = UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$s['img'];
    		 $store_info['mb_sliders'][] = $s;
    		 }
    		 }
    		 }*/
    
    		$goods_fields = $this->getGoodsFields();
    		$goods_list = (array) Model('goods')->getGoodsListByCommonidDistinct(array(
    				'store_id' => $this->store_id,
    				'goods_commend' => 1,
    				// 默认不显示预订商品
    				'is_book' => 0,
    		), $goods_fields, 0, 'goods_id desc', 20);
    
    		$goods_list = $this->_goods_list_extend($goods_list);
    	}
    	//店铺分类导航
    	$navigation = $this->StoreNavigation();
    
    	output_data(array(
    		'store_info' => $store_info,
    		'store_decoration' => $store_decoration,
    		'rec_goods_list_count' => count($goods_list),
    		'rec_goods_list' => $goods_list,
    		'navigation' => $navigation,
    	));
    }
	
    /**
     * 店铺详情页
     */
    public function store_infoOp()
    {
    	$model_mb_user_token = Model('mb_user_token');
    	$key = $_POST['key'];
    	if(empty($key)) {
    		output_error('请登录', array('login' => '0','error_code'=>CODE_InvalidSession));
    	}else{
	    	$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
	    	if(empty($mb_user_token_info)) {
	    		output_error('请登录', array('login' => '0','error_code'=>CODE_InvalidSession));
	    	}
    	}
    	
    	$store_info = $this->store_info;
    	// 如果已登录 判断该店铺是否已被收藏
    	if ($memberId = $this->getMemberIdIfExists()) {
    		$c = (int) Model('favorites')->getStoreFavoritesCountByStoreId($this->store_id, $memberId);
    		$store_info['is_favorate'] = $c > 0;
    	} else {
    		$store_info['is_favorate'] = false;
    	}
    	// 动态评分
    	if ($store_info['is_own_shop']) {
    		$store_info['store_credit_text'] = '官方店铺';
    	} else {
    		$store_info['store_credit_text'] = sprintf(
    				'描述: %0.1f, 服务: %0.1f, 物流: %0.1f',
    				$store_info['store_credit']['store_desccredit']['credit'],
    				$store_info['store_credit']['store_servicecredit']['credit'],
    				$store_info['store_credit']['store_deliverycredit']['credit']
    		);
    	}
    	
    	//取得服务评分数
    	$servicecredit = $store_info['store_credit']['store_servicecredit']['credit'];
    	$store_info['store_servicecredit_text'] = $this->servicecredit_text($servicecredit);
    	//取得店铺图片数
    	$store_info['album_num'] = Model('album')->getAlbumPicCount(array('store_id'=>$this->store_info['store_id']));
    	
	// 页头背景图
    	$store_info['mb_title_img'] = $store_info['store_banner']? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/LOGO/'.$store_info['store_banner']: '';
    	//$store_info['mb_title_img'] = $store_online_info['mb_slide'] ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$store_online_info['mb_slide'] : '';

    	output_data(array('store_info' => $store_info));
    }
    
	/**
     * 店铺简介
     */
    public function store_introOp()
    {
        $store_id = (int) $_REQUEST['store_id'];
        if ($store_id <= 0) {
            output_error('参数错误');
        }
        $store_online_info = Model('store')->getStoreOnlineInfoByID($store_id);
        if (empty($store_online_info)) {
            output_error('店铺不存在或未开启');
        }
        $store_info = $store_online_info;
        //开店时间
        $store_info['store_time_text'] = $store_info['store_time']?@date('Y-m-d',$store_info['store_time']):'';
        // 店铺头像
        $store_info['store_avatar'] = $store_online_info['store_avatar']
            ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$store_online_info['store_avatar']
            : UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_store_avatar');
        //商品数
        $store_info['goods_count'] = (int) $store_online_info['goods_count'];
        //店铺被收藏次数
        $store_info['store_collect'] = (int) $store_online_info['store_collect'];
        //店铺所属分类
        $store_class = Model('store_class')->getStoreClassInfo(array('sc_id' => $store_info['sc_id']));
        $store_info['sc_name'] = $store_class['sc_name'];
        //如果已登录 判断该店铺是否已被收藏
        if ($member_id = $this->getMemberIdIfExists()) {
            $c = (int) Model('favorites')->getStoreFavoritesCountByStoreId($store_id, $member_id);
            $store_info['is_favorate'] = $c > 0?true:false;
        } else {
            $store_info['is_favorate'] = false;
        }
		//实体店导航地址
		$store_info['map_url'] = Model('store_map')->getTXMapUrl($store_id);
		
        // 是否官方店铺
        $store_info['is_own_shop'] = (bool) $store_online_info['is_own_shop'];
        // 页头背景图
        $store_info['mb_title_img'] = $store_online_info['mb_slide'] ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$store_online_info['mb_slide'] : '';
        // 轮播
        $store_info['mb_sliders'] = array();
        $mbSliders = @unserialize($store_online_info['mb_sliders']);
        if ($mbSliders) {
            foreach ((array) $mbSliders as $s) {
                if ($s['img']) {
                    $s['imgUrl'] = UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$s['img'];
                    $store_info['mb_sliders'][] = $s;
                }
            }
        }
        output_data(array('store_info' => $store_info));
	}
	
	
	//店铺分类导航
	public function StoreNavigation() {	
	    //分类导航
		$model_mb_navigation = Model('mb_navigation');
        $navigation_list = $model_mb_navigation->getMobileNavigationList(array('mn_store_id' => $this->store_id));
		//整理图片链接
		$avigation = array();
		if (is_array($navigation_list)){
			$i=0;
			foreach ($navigation_list as $k => $v){
			  if($v['mn_if_show']) {
				$avigation[$i]['title'] = $v['mn_title'];
				if (!empty($v['mn_thumb'])){
					$avigation[$i]['pic_url'] = UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.'/category'.'/'.$v['mn_thumb'];
				}else{
					$avigation[$i]['pic_url'] = UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.'/category'.'/default.png';
				}
      		    if($v['mn_url'] != ''){				  
				  $avigation[$i]['link_url'] = $v['mn_url'];
				}else{
				  $avigation[$i]['link_url'] = WAP_SITE_URL.'/tmpl/show_article.html?mn_id='.$v['mn_id'];
				}				
				$i++;
			  }
			}
		}
		return $avigation;
	}
	
	/**
     * 商品排行
     */
    public function store_goods_rankOp(){
		$store_id = (int) $_REQUEST['store_id'];
        if ($store_id <= 0) {
            output_data(array());
        }
        $ordertype = ($t = trim($_REQUEST['ordertype']))?$t:'salenumdesc';
        $show_num = ($t = intval($_REQUEST['num']))>0?$t:10;

        $where = array();
        $where['store_id'] = $store_id;
        // 默认不显示预订商品
        $where['is_book'] = 0;
        // 排序
        switch ($ordertype) {
            case 'salenumdesc':
                $order = 'sum(goods_salenum) desc';
                break;
            case 'salenumasc':
                $order = 'sum(goods_salenum) asc';
                break;
            case 'collectdesc':
                $order = 'sum(goods_collect) desc';
                break;
            case 'collectasc':
                $order = 'sum(goods_collect) asc';
                break;
            case 'clickdesc':
                $order = 'sum(goods_click) desc';
                break;
            case 'clickasc':
                $order = 'sum(goods_click) asc';
                break;
        }
        if ($order) {
            $order .= ',goods_id desc';
        }else{
            $order = 'goods_id desc';
        }
        $model_goods = Model('goods');
        $goods_fields = $this->getGoodsFields();
        $goods_list = $model_goods->getGoodsListByCommonidDistinct($where, $goods_fields, $order, 0, $show_num);
        $goods_list = $this->_goods_list_extend($goods_list);
        output_data(array('goods_list' => $goods_list,'goods_fields' => $goods_fields));
	}
	
	/**
     * 店铺所有商品
     */
    public function store_goodsOp()
    {
        $store_id = $this->store_id;
        $stc_id = (int) $_REQUEST['stc_id'];
        $keyword = trim((string) $_REQUEST['keyword']);
        $price_from = $_REQUEST['price_from'];
		$price_to = $_REQUEST['price_to'];
		
        $condition = array();
        $condition['store_id'] = $store_id;
        // 默认不显示预订商品
        $condition['is_book'] = 0;

        if ($stc_id > 0){
            $condition['goods_stcids'] = array('like', '%,' . $stc_id . ',%');
        }
        if ($keyword != '') {
            $condition['goods_name|goods_jingle|goods_serial|goods_barcode|goods_body'] = array('like', '%'.$keyword.'%');
        }
		
		if ($price_from > 0){
            $condition['goods_price'] = array('egt', $price_from);
        }
		
		if ($price_to > 0){
            $condition['goods_price'] = array('elt', $price_to);
        }

        // 排序
        $order = (int) $_REQUEST['order'] == 1 ? 'asc' : 'desc';
        switch (trim($_GET['key'])) {
            case '1':
                $order = 'goods_id '.$order;
                break;
            case '2':
                $order = 'goods_promotion_price '.$order;
                break;
            case '3':
                $order = 'sum(goods_salenum) '.$order;
                break;
            case '4':
                $order = 'sum(goods_collect) '.$order;
                break;
            case '5':
                $order = 'sum(goods_click) '.$order;
                break;
            default:
                $order = 'goods_id desc';
                break;
        }
        $model_goods = Model('goods');
        $goods_fields = $this->getGoodsFields();
        $goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, $goods_fields, $order, $this->page);
        $page_count = $model_goods->gettotalpage();
        $goods_list = $this->_goods_list_extend($goods_list);

        output_data(array(
            'goods_list_count' => count($goods_list),
            'goods_list' => $goods_list,
        ), mobile_page($page_count));
    }
	
	/**
     * 店铺最近新品
     */
    public function store_new_goodsOp(){
		$store_id = (int) $_REQUEST['store_id'];
        if ($store_id <= 0) {
            output_data(array('goods_list'=>array()));
        }
        $show_day = ($t = intval($_REQUEST['show_day']))>0?$t:30;
        $where = array();
        $where['store_id'] = $store_id;
        $where['is_book'] = 0;//默认不显示预订商品
        $stime = strtotime(date('Y-m-d',time() - 86400*$show_day));
        $etime = $stime + 86400*($show_day+1);
        $where['goods_addtime'] = array('between',array($stime,$etime));
        $order = 'goods_addtime desc, goods_id desc';
        $model_goods = Model('goods');
        $goods_fields = $this->getGoodsFields();
        $goods_list = $model_goods->getGoodsListByCommonidDistinct($where, $goods_fields, $order, $this->page);
        $page_count = $model_goods->gettotalpage();
        if ($goods_list) {
            $goods_list = $this->_goods_list_extend($goods_list);
            foreach($goods_list as $k=>$v){
                $v['goods_addtime_text'] = $v['goods_addtime']?@date('Y年m月d日',$v['goods_addtime']):'';
                $goods_list[$k] = $v;
            }
        }
        output_data(array('goods_list' => $goods_list),mobile_page($page_count));
	}
	
	/**
     * 店铺商品分类
     */
    public function store_goods_classOp()
    {
        $store_id = (int) $_REQUEST['store_id'];
        if ($store_id <= 0) {
            output_error('参数错误');
        }
        $store_online_info = Model('store')->getStoreOnlineInfoByID($store_id);
        if (empty($store_online_info)) {
            output_error('店铺不存在或未开启');
        }

        $store_info = array();
        $store_info['store_id'] = $store_online_info['store_id'];
        $store_info['store_name'] = $store_online_info['store_name'];

       $store_goods_class = Model('store_goods_class')->getStoreGoodsClassPlainList($store_id);

        output_data(array(
            'store_info' => $store_info,
            'store_goods_class' => $store_goods_class
        ));
    }
	
	/**
     * 店铺活动
     */
    public function store_promotionOp()
    {
        $xianshi_array = Model('p_xianshi');
		$promotion['promotion'] = $condition = array();
		$condition['store_id'] = $_POST["store_id"];
		$xianshi = $xianshi_array->getXianshiList($condition);
		if(!empty($xianshi)){
			foreach($xianshi as $key=>$value){
				$xianshi[$key]['start_time_text'] = date('Y-m-d',$value['start_time']);
				$xianshi[$key]['end_time_text'] = date('Y-m-d',$value['end_time']);
			}		
			$promotion['promotion']['xianshi'] = $xianshi;
		}
		$mansong_array = Model('p_mansong');
		$mansong = $mansong_array->getMansongInfoByStoreID($_POST["store_id"]);
		if(!empty($mansong)){
			$mansong['start_time_text'] = date('Y-m-d',$mansong['start_time']);
			$mansong['end_time_text'] = date('Y-m-d',$mansong['end_time']);
			$promotion['promotion']['mansong'] = $mansong;
		}		
		output_data($promotion);
    }
	
	private function getGoodsFields()
    {
        return implode(',', array(
            'goods_id',
            'goods_commonid',
            'store_id',
        	'up_id',
            'goods_name',
            'goods_price',
            'goods_promotion_price',
            'goods_promotion_type',
            'goods_marketprice',
            'goods_image',
            'goods_salenum',
            'evaluation_good_star',
            'evaluation_count',
            'is_virtual',
            'is_presell',
            'is_fcode',
            'have_gift',
			'goods_jingle',
			'store_name',
			'promotion_amount',
			'is_own_shop',
        ));
    }
	
    /**
     * 商品评价
     */
    public function store_creditOp() {
    	$store_id = intval($_GET['store_id']);
    	if ($store_id <= 0) {
    		output_error('参数错误');
    	}
    	$store_online_info = Model('store')->getStoreOnlineInfoByID($store_id);
    	if (empty($store_online_info)) {
    		output_error('店铺不存在或未开启');
    	}
    
    	output_data(array('store_credit' => $store_online_info['store_credit']));
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
		
		$sole_array = Model('p_sole')->getSoleGoodsList(array('goods_id' => array('in', $goodsid_array)));
        $sole_array = array_under_reset($sole_array, 'goods_id');

        //促销
        //$groupbuy_list = Model('groupbuy')->getGroupbuyListByGoodsCommonIDString(implode(',', $commonid_array));
        //$xianshi_list = Model('p_xianshi_goods')->getXianshiGoodsListByGoodsString(implode(',', $goodsid_array));
        foreach ($goods_list as $key => $value) {
			$goods_list[$key]['sole_flag']      = false;
            $goods_list[$key]['group_flag']     = false;
            $goods_list[$key]['xianshi_flag']   = false;
			
            //Vip 使用云币抵扣 start zhangc
            $goodsVip = Logic('goods')->goodsVip($value['goods_id']);
            $goods_list[$key]['vip_price'] = $goodsVip['vip_price'];
            $goods_list[$key]['vip_points'] = ceil($goodsVip['vip_points']/C("points_trade"));
            
			if (!empty($sole_array[$value['goods_id']])) {
                $goods_list[$key]['goods_price'] = $sole_array[$value['goods_id']]['sole_price'];
                $goods_list[$key]['sole_flag'] = true;
            } else {
            	if($value['goods_promotion_type']>0){
                	$goods_list[$key]['goods_price'] = $value['goods_promotion_price'];
            	}else{
            		$goods_list[$key]['goods_price'] = $value['goods_price'];
            	}
                switch ($value['goods_promotion_type']) {
                    case 1:
                        $goods_list[$key]['group_flag'] = true;
                        break;
                    case 2:
                        $goods_list[$key]['xianshi_flag'] = true;
                        break;
                }
            }
            //抢购
            /*if (isset($groupbuy_list[$value['goods_commonid']])) {
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
            }*/

            //商品图片url
            if(!empty($value['up_id'])){ //是否为分销商品
	            $goods_list[$key]['goods_image_url'] = cthumb($value['goods_image'], 360, $value['up_id']); 
            }else{
            	$goods_list[$key]['goods_image_url'] = cthumb($value['goods_image'], 360, $value['store_id']);
            }

            unset($goods_list[$key]['goods_promotion_type']);
            unset($goods_list[$key]['goods_promotion_price']);
            unset($goods_list[$key]['store_id']);
            unset($goods_list[$key]['goods_commonid']);
            unset($goods_list[$key]['im_distinct']);
        }
        return $goods_list;
    }

	
	/**
     * 头部
     */
	public function publicTopOp() {
		$model_mb_ad = Model('mb_ad');
		
		$datas = array();
		$datas['store_name']=$this->store_info['store_name'];
		//广告
        $adv_list = array();
        $mb_ad_list = $model_mb_ad->getMbAdList(array(), null, 'link_sort asc', '*', $this->store_id);
        foreach ($mb_ad_list as $value) {
            $adv = array();
            $adv['image'] = $value['link_pic_url'];
			if(strstr($value['link_keyword'],"http://")){
              $adv['link_keyword'] =  $value['link_keyword'];
			}else{
			  $adv['link_keyword'] =WAP_SITE_URL+'/tmpl/product_list.html?keyword='+$value['link_keyword'];
			}
            $adv_list[] = $adv;
        }
        $datas['adv_list'] = $adv_list;
		//分类导航
		$model_mb_navigation = Model('mb_navigation');
        $navigation_list = $model_mb_navigation->getMobileNavigationList(array('mn_store_id' => $this->store_id));
		//整理图片链接
		$categroy = array();
		if (is_array($navigation_list)){
			$i=0;
			foreach ($navigation_list as $k => $v){
			  if($v['mn_if_show']) {
				$categroy[$i]['title'] = $v['mn_title'];
				if (!empty($v['mn_thumb'])){
					$categroy[$i]['pic_url'] = UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.'/category'.'/'.$v['mn_thumb'];
				}else{
					$categroy[$i]['pic_url'] = UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.'/category'.'/default.png';
				}
      		    if($v['mn_url'] != ''){				  
				  $categroy[$i]['link_url'] = $v['mn_url'];
				}else{
				  $categroy[$i]['link_url'] = WAP_SITE_URL.'/tmpl/show_article.html?store_id='.$this->store_id.'&mn_id='.$v['mn_id'];
				}				
				$i++;
			  }
			}
		}
		$datas['categroy'] = $categroy;
		$datas['adv_search'] = C('adv_search');
		
        output_data($datas);
	}
	
	/**
     * 首页
     */
	public function decorationOp() {
        $model_mb_special = Model('mb_special'); 
        $data = $model_mb_special->getStoreMbSpecialIndex($this->store_id);
        $this->_output_special($data, $_GET['type']);
	}
	
	/**
     * 输出专题
     */
    private function _output_special($data, $type = 'json', $special_id = 0) {        
        if($type == 'html') {
			$model_special = Model('mb_special');
            $html_path = $model_special->getMbSpecialHtmlPath($special_id);
            if(!is_file($html_path)) {
                ob_start();
                Tpl::output('list', $data);
                Tpl::showpage('mb_special');
                file_put_contents($html_path, ob_get_clean());
            }
            header('Location: ' . $model_special->getMbSpecialHtmlUrl($special_id));
            die;
        } else {
            output_data($data);
        }
    }

    /**
     * 商品列表
     */
    public function goods_listOp() {
        $model_goods = Model('goods');

        //查询条件
        $condition = array();
        if(!empty($_GET['store_id']) && intval($_GET['store_id']) > 0) {
            $condition['store_id'] = $_GET['store_id'];
        } elseif (!empty($_GET['keyword'])) { 
            $condition['goods_name|goods_jingle|goods_serial|goods_barcode|goods_body'] = array('like', '%' . $_GET['keyword'] . '%');
        }

        //所需字段
        $fieldstr = "goods_id,goods_commonid,store_id,up_id,goods_name,goods_price,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count";

        //排序方式
        $order = $this->_goods_list_order($_GET['key'], $_GET['order']);

        $goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, $fieldstr, $order, $this->page);
        $page_count = $model_goods->gettotalpage();

        //处理商品列表(抢购、限时折扣、商品图片)
        $goods_list = $this->_goods_list_extend($goods_list);

        output_data(array('goods_list' => $goods_list), mobile_page($page_count));
    }

    /**
     * 商品列表排序方式
     */
    private function _goods_list_order($key, $order) {
        $result = 'goods_id desc';
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
     * 店铺详细页
     */
    public function store_detailOp() {
        $store_detail['store_pf'] = $this->store_info['store_credit'];		
        $store_detail['store_info'] = $this->store_info;
		$store_detail['store_info']['avatar'] = getMemberAvatarForID($this->store_info['member_id']);
		//推广处理 导购员或推广员链接
		$extension_id = '';
		if ((OPEN_STORE_EXTENSION_STATE > 0 && ($_SESSION['M_mc_id']==1 || $_SESSION['M_mc_id']==2))){	    
		    $extension_id=urlsafe_b64encode($_SESSION['member_id']);
		}
		$store_detail['store_info']['store_url'] = urlShop('show_store', 'index', array('store_id'=>$this->store_info['store_id'],'extension'=>$extension_id));
		$store_detail['store_info']['apply_extension_url'] = intval($_SESSION['is_login']) == 1?'extension_store_apply.html':'extension_store_register.html';

		//判断是否是导购员，如果是则在商品详情页不显示推广链接
		$extension_type = 0;
		if (OPEN_STORE_EXTENSION_STATE == 1){
		    $extension_id=cookie('iMall_extension');			
		    if (!empty($extension_id)){
		        $extension_id=urlsafe_b64decode($extension_id);
		        $extension_type = Model('member')->getMemberTypeByID($extension_id);		  		
		    }
		}
		$store_detail['store_info']['extension_type'] = $extension_type;		

        //店铺详细信息处理
        //$store_detail = $this->_store_detail_extend($store_info);
		
        output_data($store_detail);
    }

    /**
     * 店铺详细信息处理
     */
    private function _store_detail_extend($store_detail) {
        //整理数据
        unset($store_detail['store_info']['goods_commonid']);
        unset($store_detail['store_info']['gc_id']);
        unset($store_detail['store_info']['gc_name']);
        // unset($goods_detail['goods_info']['store_id']);
        // unset($goods_detail['goods_info']['store_name']);
        unset($store_detail['store_info']['brand_id']);
        unset($store_detail['store_info']['brand_name']);
        unset($store_detail['store_info']['type_id']);
        unset($store_detail['store_info']['goods_image']);
        unset($store_detail['store_info']['goods_body']);
        unset($store_detail['store_info']['goods_state']);
        unset($store_detail['store_info']['goods_stateremark']);
        unset($store_detail['store_info']['goods_verify']);
        unset($store_detail['store_info']['goods_verifyremark']);
        unset($store_detail['store_info']['goods_lock']);
        unset($store_detail['store_info']['goods_addtime']);
        unset($store_detail['store_info']['goods_edittime']);
        unset($store_detail['store_info']['goods_selltime']);
        unset($store_detail['store_info']['goods_show']);
        unset($store_detail['store_info']['goods_commend']);

        return $store_detail;
    }
	
	public function show_articleOp() {
        //判断是否为导航页面
        $model_mb_navigation = Model('mb_navigation');
        $mb_navigation_info = $model_mb_navigation->getMobileNavigationInfo(array('mn_id' => intval($_GET['mn_id'])));
        if (!empty($mb_navigation_info) && is_array($mb_navigation_info)){
			output_data(array('info'=>html_entity_decode($mb_navigation_info['mn_content'])));
        }else{
			output_error('参数错误');
		}
    }
	
	/**
     * 页脚
     */
	public function publicFooterOP() {
		$datas = array();
		if ($this->show_own_copyright == true){	
		  $datas['site_name']=$this->store_info['store_name'];
		  $datas['site_url']=MAIN_SITE_URL;
		  $datas['wap_site_url']=WAP_SITE_URL;
		  $datas['ios_app_url']=C('mobile_ios');
		  $datas['android_app_url']=C('mobile_apk');
		  $datas['icp_number']=C('icp_number');
		  $datas['copyright']=html_entity_decode($this->store_info['store_copyright']);	  
		}else{
		  $datas['site_name']=C('site_name');
		  $datas['site_url']=MAIN_SITE_URL;
		  $datas['wap_site_url']=WAP_SITE_URL;
		  $datas['ios_app_url']=C('mobile_ios');
		  $datas['android_app_url']=C('mobile_apk');
		  $datas['icp_number']=C('icp_number');
		  $datas['copyright']='© 2005-2015 '.C('site_name').'版权所有，并保留所有权利';		  
		}
        output_data($datas);
	}
	
	/*分店地址*/
	public function store_o2o_addrOp($store_id){
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
	
	//生成服务星级
	function servicecredit_text($servicecredit){
		if($servicecredit<1){
			$servicecredit_text = '<i class="text-icon icon-star-gray">
    				<i class="text-icon icon-star-half"></i>
    				</i>';
			$servicecredit_text .= '<i class="text-icon icon-star-gray"></i>
    				<i class="text-icon icon-star-gray"></i>
    				<i class="text-icon icon-star-gray"></i>
    				<i class="text-icon icon-star-gray"></i>';
		}elseif($servicecredit>1&&$servicecredit<2){
			$servicecredit_text = '<i class="text-icon icon-star"></i>';
			if(is_int($servicecredit)){//是否为整数
				$servicecredit_text .='<i class="text-icon icon-star-gray"><i class="text-icon icon-star-half"></i></i>';
			}else{
				$servicecredit_text .= '<i class="text-icon icon-star-gray"></i>';
			}
			$servicecredit_text .= '<i class="text-icon icon-star-gray"></i>
    				<i class="text-icon icon-star-gray"></i>
    				<i class="text-icon icon-star-gray"></i>';
		}elseif($servicecredit>2&&$servicecredit<3){
			$servicecredit_text = '<i class="text-icon icon-star"></i>
    				<i class="text-icon icon-star"></i>';
			if(is_int($servicecredit)){//是否为整数
				$servicecredit_text .='<i class="text-icon icon-star-gray"><i class="text-icon icon-star-half"></i></i>';
			}else{
				$servicecredit_text .= '<i class="text-icon icon-star-gray"></i>';
			}
			$servicecredit_text = '<i class="text-icon icon-star-gray"></i>
    				<i class="text-icon icon-star-gray"></i>';
		}elseif($servicecredit>3&&$servicecredit<4){
			$servicecredit_text = '<i class="text-icon icon-star"></i>
    				<i class="text-icon icon-star"></i>
    				<i class="text-icon icon-star"></i>';
			if(is_int($servicecredit)){//是否为整数
				$servicecredit_text .='<i class="text-icon icon-star-gray"><i class="text-icon icon-star-half"></i></i>';
			}else{
				$servicecredit_text .= '<i class="text-icon icon-star-gray"></i>';
			}
			$servicecredit_text .= '<i class="text-icon icon-star-gray"></i>';
		}elseif($servicecredit>4&&$servicecredit<5){
			$servicecredit_text = '<i class="text-icon icon-star"></i>
    				<i class="text-icon icon-star"></i>
    				<i class="text-icon icon-star"></i>
    				<i class="text-icon icon-star"></i>';
			if(is_int($servicecredit)){//是否为整数
				$servicecredit_text .='<i class="text-icon icon-star-gray"><i class="text-icon icon-star-half"></i></i>';
			}else{
				$servicecredit_text .= '<i class="text-icon icon-star-gray"></i>';
			}
		}else{
			$servicecredit_text = '<i class="text-icon icon-star"></i>
    				<i class="text-icon icon-star"></i>
    				<i class="text-icon icon-star"></i>
    				<i class="text-icon icon-star"></i>
					<i class="text-icon icon-star"></i>';
		}
		$servicecredit_text .= '<em class="star-text">'.$servicecredit.'</em>';
		
		return $servicecredit_text;
	}
}
