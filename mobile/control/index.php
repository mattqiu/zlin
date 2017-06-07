<?php
/**
 * 手机端首页
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class indexControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }
	
	/**
     * wap头部
     */
	public function publicTopOp() {
		$datas = array();
		$datas['site_name']= C('site_name');
		
		$topadv = $this->GetTopAdv_data();
		$datas['adv_list'] = $topadv;
		$quicklink = $this->QuickLink_data();
		$datas['categroy'] = $quicklink;
		$datas['adv_search'] = C('adv_search');
		
		output_data($datas);
	}
	
	//头部广告
	public function GetTopAdv_data() {
		$model_mb_ad = Model('mb_ad');				
		//广告
        $adv_list = array();
        $mb_ad_list = $model_mb_ad->getMbAdList(array(), null, 'link_sort asc');
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
		
		return $adv_list;	
	}	
	
	//分类快捷链接
	public function QuickLink_data() {		
		$model_mb_category = Model('mb_category');
		$model_goods_class = Model('goods_class');	
		//分类
		$mb_categroy = $model_mb_category->getLinkList(array());
		$class_list = $model_goods_class->getGoodsClassForCacheModel();
		//整理图片链接
		$categroy = array();
		if (is_array($mb_categroy)){
			$i=0;
			foreach ($mb_categroy as $k => $v){
				$categroy[$i]['title'] = $class_list[$v['gc_id']]['gc_name'];
				$categroy[$i]['link_url'] = WAP_SITE_URL.'/tmpl/product_list.html?gc_id='.$v['gc_id'];
				if (!empty($v['gc_thumb'])){
					$categroy[$i]['pic_url'] = UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.'/category'.'/'.$v['gc_thumb'];
				}else{
					$categroy[$i]['pic_url'] = UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.'/category'.'/default.png';
				}
				$i++;				
			}
		}		
		return $categroy;       
	}
	
	//首页分类导航
	public function homenavigationOP() {	
	    $datas = array();
	    //分类导航
		$model_mb_navigation = Model('mb_navigation');
        $navigation_list = $model_mb_navigation->getMobileNavigationList(array('mn_store_id' => 0));
		//整理图片链接
		$navigation = array();
		if (is_array($navigation_list)){
			$i=0;
			foreach ($navigation_list as $k => $v){
			  if($v['mn_if_show']) {
				$navigation[$i]['title'] = $v['mn_title'];
				if (!empty($v['mn_thumb'])){
					$navigation[$i]['pic_url'] = UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.'/category'.'/'.$v['mn_thumb'];
				}else{
					$navigation[$i]['pic_url'] = UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.'/category'.'/default.png';
				}
      		    if($v['mn_url'] != ''){				  
				  $navigation[$i]['link_url'] = $v['mn_url'];
				}else{
				  $navigation[$i]['link_url'] = WAP_SITE_URL.'/tmpl/show_article.html?mn_id='.$v['mn_id'];
				}				
				$i++;
			  }
			}
		}
		$datas['navigation'] = $navigation;
		output_data($datas);
	}
	/**
     * 页脚
     */
	public function publicFooterOP() {
		$datas = $this->publicFooter_data();
		output_data($datas);
	}
	public function publicFooter_data() {	
		$footer = array();
		$footer['site_name']=C('site_name');
		$footer['site_url']=MAIN_SITE_URL;
		$footer['wap_site_url']=WAP_SITE_URL;
		$footer['ios_app_url']=C('mobile_ios');
		$footer['android_app_url']=C('mobile_apk');
		$footer['icp_number']=C('icp_number');
		$footer['copyright']='© 2005-2016 '.C('site_name').'版权所有，并保留所有权利';
        
		return $footer;
	}
	
	/**
     * 全局底部导航
     */
	public function publicGlobalNavOP() {
		$datas = array();
		
		$globalnav = $this->GetGlobalNav_data();
		$cartnum = $this->GetCartCount_data();
		
		$datas = array_merge($globalnav,$cartnum);
		
		output_data($datas);
	}
	//底部导航
	public function GetGlobalNav_data() {
		$globalnav['wap_site_url']=WAP_SITE_URL;

        return $globalnav;
	}
	//购物车数量
	public function GetCartCount_data() {		
		$model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
		$cartnum['cart_num'] = $this->getCartCount($key);

        return $cartnum;
	}	
	
	/**
     * 分类菜单
     */
	public function publicMainMenuOP() {
		$datas = array();
		$datas['goods_class'] = $this->publicGoodClass_data();
		output_data($datas);
	}
	//商品分类
	public function publicGoodClass_data() {
		$goods_class = array();
		
		$store_id = $_GET['store_id'];		
		if (!empty($store_id) && $store_id>0){			
			$store_goods_class = Model('store_goods_class')->getShowTreeList($store_id);
				
			if(!empty($store_goods_class) && is_array($store_goods_class)){
			  $i=0;
              foreach($store_goods_class as $value){
			    $goods_class[$i]['gc_id'] = $value['stc_id'];
			    $goods_class[$i]['gc_name'] = $value['stc_name'];
			    $goods_class[$i]['store_id'] = $store_id;
                if(!empty($value['children']) && is_array($value['children'])){
				  $j = 0;			
                  foreach($value['children'] as $child){
				    $goods_class[$i]['child2'][$j]['gc_id'] = $child['stc_id'];
				    $goods_class[$i]['child2'][$j]['gc_name'] = $child['stc_name'];
				    $goods_class[$i]['child2'][$j]['store_id'] = $store_id;
					$j++;
                  }
                }
				$i++;
              }			  
			}             
		}else{
            $goods_class = Model('goods_class')->get_all_goods_class();			   
		}			

        return $goods_class;
	}

    /**
     * 首页板块数据
     */
	public function indexOp() {	
	    $datas = $this->index_data();
		if($_GET['type'] == 'html') {
			$this->_output_html($datas);
		}else{
		    output_data($datas);
		}
	}
	
	public function index_data() {	
        $model_mb_special = Model('mb_special'); 
        $plates = $model_mb_special->getMbSpecialIndex();
        return $plates;
	}

    /**
     * 专题
     */
	public function specialOp() {
		$datas = $this->special_data($_GET['special_id']);
		if($_GET['type'] == 'html') {
			$this->_output_html($datas,$_GET['special_id']);
		}else{
		    output_data($datas);
		}
	}
	public function special_data($special_id = 0) {
        $model_mb_special = Model('mb_special');
		
		$special_info = $model_mb_special->getMbSpecialInfoByID($special_id);
        $plates = $model_mb_special->getMbSpecialItemUsableListByID($special_id);
		$special_info['list'] = $plates;
        return $special_info;
	}
	
    /**
     * 输出专题
     */
    private function _output_html($datas, $special_id = 0) {
        $model_special = Model('mb_special');
        
        $html_path = $model_special->getMbSpecialHtmlPath($special_id);
        if(!is_file($html_path)) {
            ob_start();
            Tpl::output('list', $datas);
            Tpl::showpage('mb_special');
            file_put_contents($html_path, ob_get_clean());
        }
        header('Location: ' . $model_special->getMbSpecialHtmlUrl($special_id));
    }

    /**
     * android客户端版本号
     */
	public function apk_versionOp() {
		$datas = array();
		$datas = $this->apk_version_data();
		output_data($datas);
	}
    public function apk_version_data() {
		$version = C('mobile_apk_version');
		$url = C('mobile_apk');
        if(empty($version)) {
           $version = '';
        }
        if(empty($url)) {
            $url = '';
        }
		$APK_info = array();
		$APK_info['version'] = $version;
		$APK_info['url'] = $url;

        return $APK_info;
    }
	
	/**
     * 平台配置信息
     */
	public function site_configOp() {
		$datas = array();
		$datas = $this->site_config_data();
		output_data($datas);
	}
    public function site_config_data() {
		$config = array();
		$config['service_phone'] = C('site_phone');//服务电话
		$config['site_url'] = MAIN_SITE_URL; //平台网址		
		$config['shop_desc'] = ''; //平台说明
		$config['shop_closed'] = C('site_status')==1?0:1; //平台是否关闭
		$config['close_comment'] = C('statistics_code'); //平台关闭说明
		$config['shop_address'] = ''; //平台地址
		$config['shop_reg_closed'] = C('captcha_status_register')==1?0:1; //平台是否关闭注册
		$config['goods_url'] = ""; //平台商品地址
		$config['alipay_notify_url'] = ''; //平台支付宝回调地址

        return $config;
    }
	
	/**
     * 最新商品
     */
	public function newgoodsOp() {
		$datas = array();
		$datas = $this->NewGoods_data();
		output_data($datas);
	}
	
    public function NewGoods_data() {
		$model_goods = Model('goods');
		//所需字段
        $fieldstr = "goods_id,goods_commonid,goods_name,goods_image,goods_price,goods_promotion_price,goods_marketprice,goods_jingle,goods_salenum,evaluation_good_star,evaluation_count,store_id";
		$condition = array();		
		$goods_list = $model_goods->getGoodsOnlineList($condition,$fieldstr,10);
		
		if (!empty($goods_list) && is_array($goods_list)){
			foreach($goods_list as $key => $value){
				if ($value['goods_promotion_price']<=0){
					$goods_list[$key]['goods_promotion_price'] = $value['goods_price'];
				}
				$goods_list[$key]['img'] = getPhoto_array(cthumb($value['goods_image'],240),cthumb($value['goods_image'],240),urlShop('goods', 'index', array('goods_id' => $value['goods_id'])));
			}
		}		
        return $goods_list;
    }
	
	/**
     * 推荐商品
     */
	public function promotegoodsOp() {
		$datas = array();
		$datas = $this->PromoteGoods_data();
		output_data($datas);
	}
	
    public function PromoteGoods_data() {
		$model_goods = Model('goods');
		//所需字段
        $fieldstr = "goods_id,goods_commonid,goods_name,goods_image,goods_price,goods_promotion_price,goods_marketprice,goods_jingle,goods_salenum,evaluation_good_star,evaluation_count,store_id";
		$condition = array();
		$condition['goods_commend']	= 1;
		$goods_list = $model_goods->getGoodsOnlineList($condition,$fieldstr,10);
		
		if (!empty($goods_list) && is_array($goods_list)){
			foreach($goods_list as $key => $value){
				if ($value['goods_promotion_price']<=0){
					$goods_list[$key]['goods_promotion_price'] = $value['goods_price'];
				}
			}
		}		
        return $goods_list;
    }
	
	/**
     * 推荐商品
     */
	public function promotestoresOp() {
		$datas = array();
		$datas = $this->PromoteStores_data();
		output_data($datas);
	}
	
    public function PromoteStores_data() {
		$model_stores = Model('store');
		//所需字段
        $fieldstr = "store_id,store_name,grade_id,store_type,store_address,store_label,store_recommend,store_credit,store_desccredit,store_collect,store_sales,live_store_address";
		$condition = array();
		$stores_list = $model_stores->getStoreOnlineList($condition,$fieldstr,10);
		
		if (!empty($stores_list) && is_array($stores_list)){
			foreach($stores_list as $key => $value){				
				$stores_list[$key]['store_logo'] = getStoreLogo($value['store_label']);
				$stores_list[$key]['store_url'] = urlShop('show_store', 'index', array('store_id'=>$value['store_id']));
			}
		}		
        return $stores_list;
    }
	
	//获取默认搜索关键词
	public function search_hot_infoOP(){
		$hot_info = array();
		$hot_info['name'] = C('adv_search');
		$hot_info['value'] = C('adv_search');
		
		$datas = array();
		$datas['hot_info'] = $hot_info;
		output_data($datas);		
	}
	
	/**
     * 默认搜索词列表
     */
    public function search_key_listOp() {
		//热门搜索
        $list = @explode(',',C('hot_search'));
        if (!$list || !is_array($list)) { 
            $list = array();
        }
        foreach ($list as $k=>$v) {
			if (empty($v)){
				unset($list[$k]);
			}
		}
        //历史搜索
        if (cookie('his_sh') != '') {
            $his_search_list = explode('~', cookie('his_sh'));
        }

        $data['list'] = $list;
		$data['his_list'] = is_array($his_search_list) ? $his_search_list : array();
		output_data($data);
    }
	
	/**
     * 清除历史搜索记录
     */
    public function search_history_clearOp() {
        //历史搜索
        if (cookie('his_sh') != '') {
            setIMCookie('his_sh', ''); //清除历史搜索记录
        }
		output_data(1);
    }	
	
	//高级搜索参数
	public function search_advOP(){
		$model_area = Model('area');
		$area_list = array();	
		$area_arr = $model_area->getTopLevelAreas();
		foreach ($area_arr as $k=>$v) {
			$area_info = array();
			$area_info['area_id'] = $k;
			$area_info['area_name'] = $v;
			$area_list[] = $area_info;
		}
		
		$contract_list = array();
		
		$datas = array();
		$datas['area_list'] = $area_list; //一级地区
		$datas['contract_list'] = array(); //店铺服务
		output_data($datas);		
	}
	/**-------------------------------------APP客户端---------------------------------------------------**/
	//APP头部广告
	public function APP_GetTopAdv_data() {
		$model_mb_ad = Model('mb_ad');				
		//广告
        $adv_list = array();
        $mb_ad_list = $model_mb_ad->getMbAdList(array(), null, 'link_sort asc');
        foreach ($mb_ad_list as $value) {
            $adv = array();
			if(strstr($value['link_keyword'],"http://")){
              $link_keyword =  $value['link_keyword'];
			}else{
			  $link_keyword =WAP_SITE_URL+'/tmpl/product_list.html?keyword='+$value['link_keyword'];
			}
			//app部分		
			$adv['action_id'] = 0;
			$adv['action'] = '';
			$adv['url'] = $link_keyword;
			$adv['description'] = '';			
			$adv['image'] = getPhoto_array($value['link_pic_url'],$value['link_pic_url'],$link_keyword);
			
            $adv_list[] = $adv;
        }       
		
		return $adv_list;	
	}
	
	/**
     * 首页数据
     */
	public function app_homedataOp() {
		$datas = array();
		$datas['site_name']= C('site_name');
		//广告
		$datas['player'] = $this->APP_GetTopAdv_data();
		//快捷导航
		$datas['quicklyenter'] = $this->QuickLink_data();
		//购物车商品数量
		$datas['cart_num'] = $this->GetCartCount_data();
		//新品推荐
		$datas['new_goods'] = $this->NewGoods_data();		
		
		output_data($datas);
	}
	
	/**
     * 板块信息
     */
	public function app_categoryOp() {
		$datas = array();		
		
		//板块信息
		$plats_list = $this->index_data();		
		
		if (!empty($plats_list) && is_array($plats_list)){
			foreach($plats_list as $k => $value){				
			  foreach($value as $key => $plats){

				$plats_data = array();
				switch ($key) {
                    case 'home1':
					    $item = array();					    
						$item['urltype'] = $plats['type'];
						$item['urldata'] = $plats['data'];
						$item['url'] = '';
						$item['img'] = getPhoto_array($plats['image'],$plats['image'],'');
						$plats_data[0] = $item;
                        break;
                    case 'home2':
					    $item = array();					    
						$item['urltype'] = $plats['square_type'];
						$item['urldata'] = $plats['square_data'];
						$item['url'] = '';
						$item['img'] = getPhoto_array($plats['square_image'],$plats['square_image'],'');
						$plats_data[0] = $item;
						
						$item = array();					    
						$item['urltype'] = $plats['rectangle1_type'];
						$item['urldata'] = $plats['rectangle1_data'];
						$item['url'] = '';
						$item['img'] = getPhoto_array($plats['rectangle1_image'],$plats['rectangle1_image'],'');
						$plats_data[1] = $item;
						
						$item = array();					    
						$item['urltype'] = $plats['rectangle2_type'];
						$item['urldata'] = $plats['rectangle2_data'];
						$item['url'] = '';
						$item['img'] = getPhoto_array($plats['rectangle2_image'],$plats['rectangle2_image'],'');
						$plats_data[2] = $item;						
					    
					    break;
                    case 'home4':
						$item = array();					    
						$item['urltype'] = $plats['rectangle1_type'];
						$item['urldata'] = $plats['rectangle1_data'];
						$item['url'] = '';
						$item['img'] = getPhoto_array($plats['rectangle1_image'],$plats['rectangle1_image'],'');
						$plats_data[0] = $item;
						
						$item = array();					    
						$item['urltype'] = $plats['rectangle2_type'];
						$item['url_data'] = $plats['rectangle2_data'];
						$item['url'] = '';
						$item['img'] = getPhoto_array($plats['rectangle2_image'],$plats['rectangle2_image'],'');
						$plats_data[1] = $item;
						
						$item = array();					    
						$item['urltype'] = $plats['square_type'];
						$item['urldata'] = $plats['square_data'];
						$item['url'] = '';
						$item['img'] = getPhoto_array($plats['square_image'],$plats['square_image'],'');
						$plats_data[2] = $item;
                        break;
                    case 'goods':                        
						if (!empty($plats['item']) && is_array($plats['item'])){							
                            foreach ($plats['item'] as $k => $value) {
								$good = array();		                
								$good['urltype'] = 'goods';
						        $good['urldata'] = $value['goods_id'];
								$good['url'] = '';
						        $good['img'] = getPhoto_array($value['goods_image'],$value['goods_image'],'');
								$good['goodinfo'] = $value;				
								$plats_data[] = $good;
                            }
						}
                        break;
                   	case 'goods1':
                        	if (!empty($plats['item']) && is_array($plats['item'])){
                        		foreach ($plats['item'] as $k => $value) {
                        			$good = array();
                        			$good['urltype'] = 'goods';
                        			$good['urldata'] = $value['goods_id'];
                        			$good['url'] = '';
                        			$good['img'] = getPhoto_array($value['goods_image'],$value['goods_image'],'');
                        			$good['goodinfo'] = $value;
                        			$plats_data[] = $good;
                        		}
                        	}
                        	break;
                    default: //home3 
					    $plats_type = 'home3';                       
						if (!empty($plats['item']) && is_array($plats['item'])){
                            foreach ($plats['item'] as $k => $value) {										
				                $item = array();
								$item['urltype'] = $value['type'];
						        $item['urldata'] = $value['data'];
								$item['url'] = '';
								$item['img'] = getPhoto_array($value['image'],$value['image'],'');
						
								$plats_data[] = $item;
                            }
						}
						break;
                }
				$plats_item = array();		
				$plats_item['plates_type'] = $key;
				$plats_item['title'] = $plats['title'];
				$plats_item['plates_data'] = $plats_data;
				
				$datas[] = $plats_item;
			  }
			}
		}
		//推荐商品
		$promotegoods = $this->PromoteGoods_data();
		if (!empty($promotegoods) && is_array($promotegoods)){
			$plates_data = array();						
            foreach ($promotegoods as $k => $value) {
			    $good = array();		                
				$good['urltype'] = 'goods';
				$good['urldata'] = $value['goods_id'];
				$good['url'] = '';
				$good['img'] = getPhoto_array(cthumb($value['goods_image'],240),cthumb($value['goods_image'],240),urlShop('goods', 'index', array('goods_id' => $value['goods_id'])));
				$good['goodinfo'] = $value;				
				$plates_data[] = $good;
            }
			$plates_item = array();		
		    $plates_item['plates_type'] = 'goodslist';
		    $plates_item['title'] = '你喜欢的商品';
		    $plates_item['plates_data'] = $plates_data;
		    $datas[] = $plates_item;
		}
		
		//推荐店铺
		$promotestores = $this->PromoteStores_data();
		if (!empty($promotestores) && is_array($promotestores)){
			$plates_data = array();						
            foreach ($promotestores as $k => $value) {
			    $store = array();		                
				$store['urltype'] = 'stores';
				$store['urldata'] = $value['store_id'];
				$store['url'] = '';
				$store['img'] = getPhoto_array($value['store_logo'],$value['store_logo'],$value['store_url']);
				$store['storeinfo'] = $value;				
				$plates_data[] = $store;
            }
			$plates_item = array();		
		    $plates_item['plates_type'] = 'storelist';
		    $plates_item['title'] = '为你推荐的店铺';
		    $plates_item['plates_data'] = $plates_data;
		    $datas[] = $plates_item;
		}	
		
		output_data($datas);
	}
}