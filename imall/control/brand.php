<?php
/**
 * 前台品牌分类
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

class brandControl extends BaseBrandControl {
	
	public function __construct() {
        parent::__construct();
        Tpl::output('index_sign','brand');
    }
	
	public function indexOp(){
		//读取语言包
		Language::read('home_brand_index');        
        Tpl::output('html_title',Language::get('brand_index_brand_list'));
		//板块信息
		$model_web_config = Model('web_config');
		$web_html = $model_web_config->getWebHtml('brand');
		Tpl::output('web_html',$web_html);
		
		$special_model = Model('web_special');
		//推荐专题
		$where = array();
		$where['special_recommend'] = 1;		
		$special_r = $special_model->getSpecialInfoList($where,12);
		Tpl::output('special_r',$special_r);
		//专辑列表
		$where = array();
		if (!empty($_GET['class'])){
			$where['special_class']=$_GET['class'];
		}		
		$special_list = $special_model->getSpecialInfoList($where,32);		
		
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
		
		Tpl::output('showpage',Model()->showpage());		
		//专题分类
		$special_class = Model('web_special_class')->getList();
		Tpl::output('special_class', $special_class);		
		//页面输出		
		Model('seo')->type('brand')->show();
		Tpl::showpage('brand');
	}	
	/**
	 * 品牌分类列表
	 */
	public function categoryOP(){
		//读取语言包
		Language::read('home_brand_index'); 
		$lang	= Language::getLangContent();      
        Tpl::output('html_title',$lang['brand_index_brand_list']);	
		
		$parent_id = 0;
		$brand_name = '';
		$cate_id=$_GET['cate_id'];
		$where =array();
		$where['brand_apply'] = 1;
		if ($cate_id<0){
			$where['class_id']='';
		}
		if (!empty($cate_id) && $cate_id>=0){
			$class_list = array();
			$goods_class = rkcache('goods_class') ? rkcache('goods_class') : rkcache('goods_class', true);
			if (!empty($goods_class[$cate_id])){
				$parent_id = $goods_class[$cate_id]['gc_parent_id']==0?$cate_id:$goods_class[$cate_id]['gc_parent_id'];
				$brand_name = $goods_class[$cate_id]['gc_name'];
				
				$classstr=$goods_class[$cate_id]['gc_id'];
				if(!empty($goods_class[$cate_id]['child'])){
					$classstr=$classstr.','.$goods_class[$cate_id]['child'];
				}
				if (!empty($goods_class[$cate_id]['childchild'])){
					$classstr=$classstr.','.$goods_class[$cate_id]['childchild'];
				}
				$class_list = explode(',',$classstr);
			}
			if (!empty($class_list)){
				$where['class_id']=array('in', $class_list);
			}
		}else{
			$brand_name = empty($cate_id)?'全部品牌':'其它品牌';
		}
		$model_brand = Model('brand');
		$brand_list = $model_brand->where($where)->page(10)->select();
		Tpl::output('showpage', $model_brand->showpage());
		Tpl::output('parent_id', $parent_id);
		Tpl::output('brand_name', $brand_name);
		// 字段
        $fieldstr = "goods_id,goods_commonid,goods_name,goods_jingle,store_id,store_name,goods_price,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_good_star,evaluation_count";
        $order = 'goods_salenum desc';
		foreach ($brand_list as $key=>$value) {
	      // 条件
          $where = array();
		  if ($_SESSION['M_grade_level']>0){
			$where['view_grade'] = array('elt', $_SESSION['M_grade_level']);
		  }else{
			$where['view_grade'] = 0;
		  }				
          $where['brand_id'] = $value['brand_id'];

          $model_goods = Model('goods');
          $goods_list = $model_goods->getGoodsListByCommonidDistinct($where, $fieldstr, $order, 4);
		  $brand_list[$key]['goods']=$goods_list;
		}
		Tpl::output('brand_lists', $brand_list);
		/**
		 * 分类导航
		 */
		$nav_link = array();
		$nav_link[0]=array('title'=>$lang['homepage'],'link'=>'index.php');
		$nav_link[1]=array('title'=>'品牌','link'=>'index.php?act=brand&op=index');
		$nav_link[2]=array('title'=>$brand_name);
		Tpl::output('nav_link_list',$nav_link);
		//页面输出
		Tpl::output('index_sign','brand');
		Model('seo')->type('brand')->show();
		Tpl::showpage('brand_category');		
	}
	/**
	 * 品牌商品列表
	 */
	public function listOp(){
		Language::read('home_brand_index');
		$lang	= Language::getLangContent();
		/**
		 * 验证品牌
		 */
		$model_brand = Model('brand');
		$brand_id = intval($_GET['brand']);
		$brand_lise = $model_brand->getOneBrand($brand_id);
		if(!$brand_lise){
			showMessage($lang['wrong_argument'],'index.php','html','error');
		}

		/**
		 * 获得推荐品牌
		 */
		$brand_class = Model('brand');
		//获得列表
		$brand_r_list = $brand_class->getBrandList(array(
			'brand_recommend'=>1,
			'field'=>'brand_id,brand_name,brand_pic',
			'brand_apply'=>1,
			'limit'=>'0,10'
		));
		Tpl::output('brand_r',$brand_r_list);

        // 得到排序方式
        $order = 'goods_id desc';
        if (!empty($_GET['key'])) {
            $order_tmp = trim($_GET['key']);
            $sequence = $_GET['order'] == 1 ? 'asc' : 'desc';
            switch ($order_tmp) {
                case '1' : // 销量
                    $order = 'goods_salenum' . ' ' . $sequence;
                    break;
                case '2' : // 浏览量
                    $order = 'goods_click' . ' ' . $sequence;
                    break;
                case '3' : // 价格
                    $order = 'goods_price' . ' ' . $sequence;
                    break;
            }
        }

        // 字段
        $fieldstr = "goods_id,goods_commonid,goods_name,goods_jingle,store_id,store_name,goods_price,goods_promotion_price,goods_marketprice,goods_storage,goods_image,goods_freight,goods_salenum,color_id,evaluation_good_star,evaluation_count";
        // 条件
        $where = array();
		if ($_SESSION['M_grade_level']>0){
			$where['view_grade'] = array('elt', $_SESSION['M_grade_level']);
		}else{
			$where['view_grade'] = 0;
		}
				
        $where['brand_id'] = $brand_id;
        if (intval($_GET['area_id']) > 0) {
            $where['areaid_1'] = intval($_GET['area_id']);
        }
		if (intval($_GET['area_id2']) > 0) {
            $where['areaid_2'] = intval($_GET['area_id2']);
        }
				
        if (in_array($_GET['type'], array(1,2,3))) {
            if ($_GET['type'] == 1) {
                $where['store_id'] = DEFAULT_PLATFORM_STORE_ID;
            } else if ($_GET['type'] == 2) {
                $where['store_type'] = 1;
            } else if ($_GET['type'] == 3) {
                $where['store_type'] = 2;
            }
        }

        $model_goods = Model('goods');
        $goods_list = $model_goods->getGoodsListByCommonidDistinct($where, $fieldstr, $order, 24);
        Tpl::output('show_page1', $model_goods->showpage(4));
        Tpl::output('show_page', $model_goods->showpage(5));
        // 商品多图
        if (!empty($goods_list)) {
            $goodsid_array = array();       // 商品id数组
            $commonid_array = array(); // 商品公共id数组
            $storeid_array = array();       // 店铺id数组
            foreach ($goods_list as $value) {
                $goodsid_array[] = $value['goods_id'];
                $commonid_array[] = $value['goods_commonid'];
                $storeid_array[] = $value['store_id'];
            }
            $goodsid_array = array_unique($goodsid_array);
            $commonid_array = array_unique($commonid_array);
            $storeid_array = array_unique($storeid_array);
            // 商品多图
            $goodsimage_more = $model_goods->getGoodsImageList(array('goods_commonid' => array('in', $commonid_array)));
            // 店铺
            $store_list = Model('store')->getStoreMemberIDList($storeid_array);
            // 抢购
            $groupbuy_list = Model('groupbuy')->getGroupbuyListByGoodsCommonIDString(implode(',', $commonid_array));
            // 限时折扣
            $xianshi_list = Model('p_xianshi_goods')->getXianshiGoodsListByGoodsString(implode(',', $goodsid_array));
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
                // 抢购
                if (isset($groupbuy_list[$value['goods_commonid']])) {
                    $goods_list[$key]['goods_price'] = $groupbuy_list[$value['goods_commonid']]['groupbuy_price'];
                    $goods_list[$key]['group_flag'] = true;
                }
                if (isset($xianshi_list[$value['goods_id']]) && !$goods_list[$key]['group_flag']) {
                    $goods_list[$key]['goods_price'] = $xianshi_list[$value['goods_id']]['xianshi_price'];
                    $goods_list[$key]['xianshi_flag'] = true;
                }
            }
        }
        Tpl::output('goods_list', $goods_list);

        // 地区
        require(BASE_DATA_PATH.'/area/area.php');
		$area_name ='不限地区';//$lang['goods_class_index_area'];
		if (intval($_GET['area_id']) > 0) {
            $area_name = $area_array[$_GET['area_id']]['area_name'];
        }
		if (intval($_GET['area_id2']) > 0) {
            $area_name = $area_array[$_GET['area_id2']]['area_name'];
        }				
        Tpl::output('area_name', $area_name);
        
        loadfunc('search');
		/**
		 * 分类导航
		 */
		$nav_link = array(
			0=>array(
				'title'=>$lang['homepage'],
				'link'=>'index.php'
			),
			1=>array(
				'title'=>$lang['brand_index_all_brand'],
				'link'=>'index.php?act=brand&op=category'
			),
			2=>array(
				'title'=>$brand_lise['brand_name']
			)
		);
		Tpl::output('nav_link_list',$nav_link);
		/**
		 * 页面输出
		 */
		Tpl::output('index_sign','brand');


		Model('seo')->type('brand_list')->param(array('name'=>$brand_lise['brand_name']))->show();
		Tpl::showpage('brand_goods');
	}
}
