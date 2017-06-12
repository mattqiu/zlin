<?php
/**
 * 店铺模型管理
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

class storeModel extends Model {

    /**
     * 自营店铺的ID
     *
     * array(
     *   '店铺ID(int)' => '是否绑定了全部商品类目(boolean)',
     *   // ..
     * )
     */
    protected $ownShopIds;

    public function __construct() {
        parent::__construct('store');
    }

    /**
     * 删除缓存自营店铺的ID
     */
    public function dropCachedOwnShopIds() {
        $this->ownShopIds = null;
        dkcache('own_shop_ids');
    }

    /**
     * 获取自营店铺的ID
     *
     * @param boolean $bind_all_gc = false 是否只获取绑定全部类目的自营店 默认否（即全部自营店）
     * @return array
     */
    public function getOwnShopIds($bind_all_gc = false) {

        $data = $this->ownShopIds;
		

        // 属性为空则取缓存
        if (!$data) {
			
            $data = rkcache('own_shop_ids');
            // 缓存为空则查库
            if (!$data) {
                $data = array();
                $all_own_shops = $this->table('store')->field('store_id,bind_all_gc')->where(array('is_own_shop' => 1,))->select();
                foreach ((array) $all_own_shops as $v) {
                    $data[$v['store_id']] = (int) (bool) $v['bind_all_gc'];
                }

                // 写入缓存
                wkcache('own_shop_ids', $data);
            }

            // 写入属性
            $this->ownShopIds = $data;
        }

        return array_keys($bind_all_gc ? array_filter($data) : $data);
    }

	/**
	 * 查询店铺列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 字段
	 * @param string $limit 取多少条
     * @return array
	 */
    public function getStoreList($condition, $page = null, $order = '', $field = '*', $limit = '') {
        $result = $this->field($field)->where($condition)->order($order)->limit($limit)->page($page)->select();
        return $result;
    }

	/**
	 * 查询有效店铺列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 字段
     * @return array
	 */
    public function getStoreOnlineList($condition, $page = null, $order = '', $field = '*') {
        $condition['store_state'] = 1;
        return $this->getStoreList($condition, $page, $order, $field);
    }

    /**
     * 店铺数量
     * @param array $condition
     * @return int
     */
    public function getStoreCount($condition) {
        return $this->where($condition)->count();
    }

    /**
	 * 按店铺编号查询店铺的信息
     *
	 * @param array $storeid_array 店铺编号
     * @return array
	 */
    public function getStoreMemberIDList($storeid_array, $field = 'store_id,member_id,store_domain') {
        $store_list = $this->table('store')->where(array('store_id'=> array('in', $storeid_array)))->field($field)->key('store_id')->select();
        return $store_list;
    }

    /**
	 * 查询店铺信息
     *
	 * @param array $condition 查询条件
     * @return array
	 */
    public function getStoreInfo($condition, $field = '*') {
        $store_info = $this->field($field)->where($condition)->find();
        if(!empty($store_info)) {
            if(!empty($store_info['store_presales'])) $store_info['store_presales'] = unserialize($store_info['store_presales']);
            if(!empty($store_info['store_aftersales'])) $store_info['store_aftersales'] = unserialize($store_info['store_aftersales']);

            //商品数
            $model_goods = Model('goods');
            $store_info['goods_count'] = $model_goods->getGoodsCommonOnlineCount(array('store_id' => $store_info['store_id']));

            //店铺评价
            $model_evaluate_store = Model('evaluate_store');
            $store_evaluate_info = $model_evaluate_store->getEvaluateStoreInfoByStoreID($store_info['store_id'], $store_info['sc_id']);

            $store_info = array_merge($store_info, $store_evaluate_info);
        }
        return $store_info;
    }

    /**
	 * 通过店铺编号查询店铺信息
     *
	 * @param int $store_id 店铺编号
     * @return array
	 */
    public function getStoreInfoByID($store_id, $field = '*') {
        $prefix = 'store_info';

        $store_info = rcache($store_id, $prefix);
        if(empty($store_info)) {
            $store_info = $this->getStoreInfo(array('store_id' => $store_id), $field);
            $cache = array();
            $cache['store_info'] = serialize($store_info);
            wcache($store_id, $cache, $prefix, 60 * 24);
        } else {
            $store_info = unserialize($store_info['store_info']);
        }
        return $store_info;
    }
	
	/**
	 * 通过店铺编号查询店铺支付方式
     *
	 * @param int $store_id 店铺编号
     * @return array
	 */
    public function getStorePayment_MethodByID($store_id) {
		$condition=array();
		$condition['store_id']=$store_id;		
		$store_info = $this->field('store_id,store_name,parent_id,payment_method')->where($condition)->find();
		$store_PM=array();
		if(empty($store_info) || C('payment_method')==1 || $store_info['payment_method']==1) {
			$store_PM['store_id']=0;
			$store_PM['pay_method']=1;
			$store_PM['pay_store_id']=0;
		}else{
			$store_PM['store_id']=$store_info['store_id'];			
            $store_PM['pay_method']=$store_info['payment_method']==2?2:0;
			$store_PM['pay_store_id']=$store_PM['pay_method']==2?$store_info['parent_id']:$store_info['store_id'];
		}
        return $store_PM;
    }

    public function getStoreOnlineInfoByID($store_id) {
        $store_info = $this->getStoreInfoByID($store_id);
        if(empty($store_info) || $store_info['store_state'] == '0') {
            return array();
        } else {
            return $store_info;
        }
    }

    public function getStoreIDString($condition) {
        $condition['store_state'] = 1;
        $store_list = $this->getStoreList($condition);
        $store_id_string = '';
        foreach ($store_list as $value) {
            $store_id_string .= $value['store_id'].',';
        }
        return $store_id_string;
    }
	//输入店铺ID返回店铺名称
	public function getStoreNameByID($store_id) {
        $store_info = $this->getStoreInfoByID($store_id);
        if(empty($store_info)) {
            return '';
        } else {
            return $store_info['store_name'];
        }
    }

	/*
	 * 添加店铺
     *
	 * @param array $param 店铺信息
	 * @return bool
	 */
    public function addStore($param){
        return $this->insert($param);
    }

	/*
	 * 编辑店铺
     *
	 * @param array $update 更新信息
	 * @param array $condition 条件
	 * @return bool
	 */
    public function editStore($update, $condition){
        //清空缓存
        $store_list = $this->getStoreList($condition);
        foreach ($store_list as $value) {
            dcache($value['store_id'], 'store_info');
        }

        return $this->where($condition)->update($update);
    }

	/*
	 * 删除店铺
     *
	 * @param array $condition 条件
	 * @return bool
	 */
    public function delStore($condition){
        $store_info = $this->getStoreInfo($condition);
        //删除店铺相关图片
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'LOGO'.DS.$store_info['store_label']);
        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'LOGO'.DS.$store_info['store_banner']);
		
		$slide_list = unserialize($store_info['store_slide']);
        if(!empty($slide_list['slide']) && is_array($slide_list['slide'])){
            foreach($slide_list['slide'] as $val){
                @unlink(BASE_UPLOAD_PATH.DS.ATTACH_SLIDE.DS.$val);
            }
        }
		Model('store_bind_class')->where(array('store_id'=>$store_info['store_id']))->delete();
		Model('store_extend')->where(array('store_id'=>$store_info['store_id']))->delete();
		Model('album_class')->where(array('store_id'=>$store_info['store_id']))->delete();
		Model('album')->delAlbum($store_info['store_id']);						
		Model('seller')->where(array('store_id'=>$store_info['store_id']))->delete();
		//$where=array();
		//$where['member_id']=intval($store_info['member_id']);
		//Model('member')->where($where)->delete();
		//Model('sns_albumclass')->where($where)->delete();
        //清空缓存
        dcache($store_info['store_id'], 'store_info');

        return $this->where($condition)->delete();
    }

    /**
     * 完全删除店铺 包括店主账号、店铺的管理员账号、店铺相册、店铺扩展
     */
    public function delStoreEntirely($condition)
    {
        $this->delStore($condition);
		Model('micro_store')->drop($condition);	
        Model('seller_group')->delSellerGroup($condition);
		Model('store_joinin')->drop($condition);
		Model('goods')->delGoodsAll($condition);		
    }

    /**
     * 获取商品销售排行(每天更新一次)
     *
     * @param int $store_id 店铺编号
     * @param int $limit 数量
     * @return array	商品信息
     */
    public function getHotSalesList($store_id, $limit = 5) {
        $prefix = 'store_hot_sales_list_' . $limit;
        if ($isMobile) {
            $prefix .= '_mobile';
        }
        $hot_sales_list = rcache($store_id, $prefix);
        if(empty($hot_sales_list)) {
            $model_goods = Model('goods');
            $where = array(
                'store_id' => $store_id,
            );
            // 手机端不显示预订商品
            if ($isMobile) {
                $where['is_book'] = 0;
            }
            $hot_sales_list = $model_goods->getGoodsListByCommonidDistinct($where, '*', 'sum(goods_salenum) desc', 0, $limit);
            $cache = array();
            $cache['hot_sales'] = serialize($hot_sales_list);
            wcache($store_id, $cache, $prefix, 60 * 24);
        } else {
            $hot_sales_list = unserialize($hot_sales_list['hot_sales']);
        }
        return $hot_sales_list;
    }

    /**
     * 获取商品收藏排行(每天更新一次)
     *
     * @param int $store_id 店铺编号
     * @param int $limit 数量
     * @return array	商品信息
     */
    public function getHotCollectList($store_id, $limit = 5) {
        $prefix = 'store_collect_sales_list_' . $limit;
        $hot_collect_list = rcache($store_id, $prefix);
        if(empty($hot_collect_list)) {
            $model_goods = Model('goods');
            $hot_collect_list = $model_goods->getGoodsListByCommonidDistinct(array('store_id' => $store_id), '*', 'sum(goods_collect) desc', 0, $limit);
            $cache = array();
            $cache['collect_sales'] = serialize($hot_collect_list);
            wcache($store_id, $cache, $prefix, 60 * 24);
        } else {
            $hot_collect_list = unserialize($hot_collect_list['collect_sales']);
        }
        return $hot_collect_list;
    }
	
	/**
     * 获取店铺列表页附加信息
     *
     * @param array $store_array 店铺数组
     * @return array $store_array 包含近期销量和8个推荐商品的店铺数组
     */
    public function getStoreSearchList($store_array) {
    	$store_array_new = array();
    	if(!empty($store_array)){
    		$model = Model();
    		$no_cache_store = array();
    		foreach ($store_array as $value) {
    			//$store_search_info = rcache($value['store_id'],'store_search_info');
    			//print_r($store_array);exit();
    			//if($store_search_info !== FALSE) {
    			//	$store_array_new[$value['store_id']] = $store_search_info;
    			//} else {
    			//	$no_cache_store[$value['store_id']] = $value;
    			//}
    			$no_cache_store[$value['store_id']] = $value;
    		}
    		if(!empty($no_cache_store)) {
    			//获取店铺商品数
    			$no_cache_store = $this->getStoreInfoBasic($no_cache_store);
    			//获取店铺近期销量
    			$no_cache_store = $this->getGoodsCountJq($no_cache_store);
    			//获取店铺推荐商品
    			$no_cache_store = $this->getGoodsListBySales($no_cache_store);
    			//写入缓存
    			foreach ($no_cache_store as $value) {
    				wcache($value['store_id'],$value,'store_search_info');
    			}
    			$store_array_new = array_merge($store_array_new,$no_cache_store);
    		}
    	}
    	return $store_array_new;
    }
    
    /**
     * 获得店铺标志、信用、商品数量、店铺评分等信息
     *
     * @param	array $param 店铺数组
     * @return	array 数组格式的返回结果
     */
    public function getStoreInfoBasic($list,$day = 0){
    	$list_new = array();
    	if (!empty($list) && is_array($list)){
    		foreach ($list as $key=>$value) {
    			if(!empty($value)) {
    				$value['store_logo'] = getStoreLogo($value['store_logo']);
    				//店铺评价
    				$model_evaluate_store = Model('evaluate_store');
    				$store_evaluate_info = $model_evaluate_store->getEvaluateStoreInfoByStoreID($value['store_id'], $value['sc_id']);
    				$value = array_merge($value, $store_evaluate_info);
    
    				if(!empty($value['store_presales'])) $value['store_presales'] = unserialize($value['store_presales']);
    				if(!empty($value['store_aftersales'])) $value['store_aftersales'] = unserialize($value['store_aftersales']);
    				$list_new[$value['store_id']] = $value;
    				$list_new[$value['store_id']]['goods_count'] = 0;
    			}
    		}
    		//全部商品数直接读取缓存
    		if($day > 0) {
    			$store_id_string = implode(',',array_keys($list_new));
    			//指定天数直接查询数据库
    			$condition = array();
    			$condition['goods_show'] = '1';
    			$condition['store_id'] = array('in',$store_id_string);
    			$condition['goods_add_time'] = array('gt',strtotime("-{$day} day"));
    			$model = Model();
    			$goods_count_array = $model->table('goods')->field('store_id,count(*) as goods_count')->where($condition)->group('store_id')->select();
    			if (!empty($goods_count_array)){
    				foreach ($goods_count_array as $value){
    					$list_new[$value['store_id']]['goods_count'] = $value['goods_count'];
    				}
    			}
    		} else {
    			$list_new = $this->getGoodsCountByStoreArray($list_new);
    		}
    	}
    	return $list_new;
    }
    
    /**
     * 获取店铺商品数
     *
     * @param array $store_array 店铺数组
     * @return array $store_array 包含商品数goods_count的店铺数组
     */
    public function getGoodsCountByStoreArray($store_array) {
    	$store_array_new = array();
    	$model = Model();
    	$no_cache_store = '';
    
    	foreach ($store_array as $value) {
    		$goods_count = rcache($value['store_id'],'store_goods_count');
    		if($goods_count !== FALSE) {
    			//有缓存的直接赋值
    			$value['goods_count'] = $goods_count;
    		} else {
    			//没有缓存记录store_id，统计从数据库读取
    			$no_cache_store .= $value['store_id'].',';
    			$value['goods_count'] = '0';
    		}
    		$store_array_new[$value['store_id']] = $value;
    	}
    
    	if(!empty($no_cache_store)) {
    		//从数据库读取店铺商品数赋值并缓存
    		$no_cache_store = rtrim($no_cache_store,',');
    		$condition = array();
    		$condition['goods_state'] = '1';
    		$condition['store_id'] = array('in',$no_cache_store);
    		$goods_count_array = $model->table('goods')->field('store_id,count(*) as goods_count')->where($condition)->group('store_id')->select();
    		if (!empty($goods_count_array)){
    			foreach ($goods_count_array as $value){
    				$store_array_new[$value['store_id']]['goods_count'] = $value['goods_count'];
    				wcache($value['store_id'],$value['goods_count'],'store_goods_count');
    			}
    		}
    	}
    	return $store_array_new;
    }
    
    //获取近期销量
    private function getGoodsCountJq($store_array) {
    	$model = Model();
    	$order_count_array = $model->table('order')->field('store_id,count(*) as order_count')->where(array('store_id'=>array('in',implode(',',array_keys($store_array))),'add_time'=>array('gt',TIMESTAMP-3600*24*90)))->group('store_id')->select();
    	foreach ((array)$order_count_array as $value) {
    		$store_array[$value['store_id']]['num_sales_jq'] = $value['order_count'];
    	}
    	return $store_array;
    }
    
    //获取店铺8个销量最高商品
    private function getGoodsListBySales($store_array) {
    	$model = Model();
    	$field = 'goods_id,store_id,goods_name,goods_image,goods_price,goods_salenum';
    	foreach ($store_array as $value) {
    		$store_array[$value['store_id']]['search_list_goods'] = $model->table('goods')->field($field)->where(array('store_id'=>$value['store_id'],'goods_state'=>1))->order('goods_salenum desc')->limit(8)->select();
    	}
    	return $store_array;
    }
	
    /**
     * 通过店铺编号查询店铺云币方式
     *
     * @param int $store_id 店铺编号
     * @return array
     */
    public function getStorePoints_WayByID($store_id) {
    	$condition=array();
    	$condition['store_id']=$store_id;
    	$store_info = $this->field('store_points_way')->where($condition)->find();
    	$store_points_way = $store_info['store_points_way'];
    	return $store_points_way;
    }
    
    
	//-----------------------------------------------分店处理--------------------------------------------------------------------------------------//
	/**
	 * 查询店铺分店列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 字段
	 * @param string $limit 取多少条
     * @return array
	 */
    public function getBranchList($store_id, $page = null, $order = '', $field = '*', $limit = '') {
		$condition = array();
		$condition['parent_id'] = $store_id;
        $result = $this->field($field)->where($condition)->order($order)->limit($limit)->page($page)->select();
        return $result;
    }

	/**
	 * 查询店铺有效分店列表
     *
	 * @param array $condition 查询条件
	 * @param int $page 分页数
	 * @param string $order 排序
	 * @param string $field 字段
     * @return array
	 */
    public function getBranchOnlineList($store_id, $page = null, $order = '', $field = '*') {
		$condition = array();
		$condition['parent_id'] = $store_id;
        $condition['store_state'] = 1;
        return $this->getStoreList($condition, $page, $order, $field);
    }

    /**
     * 分店数量
     * @param array $condition
     * @return int
     */
    public function getBranchCount($store_id) {
		$condition = array();
		$condition['parent_id'] = $store_id;
        return $this->where($condition)->count();
    }
	
	/**
     * 店铺有效代金劵列表
     * @param array $condition
     * @return int
     */
    public function getVoucherList($store_id) {
		$model = Model('voucher');
		$voucher = array();
		
		$condition = array();
		$condition['voucher_t_store_id'] = $store_id;
		$condition['voucher_t_state'] = 1;
		$condition['voucher_t_end_date'] = array('egt',time());

		$voucher_list = $model->table('voucher_template')->where($condition)->field('voucher_t_id,voucher_t_price,voucher_t_limit,voucher_t_end_date')->order('voucher_t_id desc')->page(10)->select();
        if (!empty($voucher_list)){
			foreach ($voucher_list as $key=>$val){
				$voucher[] = $val;
			}
		}
        return $voucher;
    }
    //-----------------------------------------------实体店地址--------------------------------------------------------------------------------------//
    
    public function storeO2OAddrList($store_id){
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
    	return $addr_list;
    }
    
	//-----------------------------------------------推广管理--------------------------------------------------------------------------------------//	
	/**
     * 推广员限制
     * @param array $condition
     * @return int
     */
    public function getPromotionLimit($store_id = 0) {
		if (OPEN_STORE_EXTENSION_STATE==10){
		    $limit = 0;
		}else{
		    $condition['store_id'] = $store_id;
		    $limit = $this->where($condition)->get_field('promotion_limit');
		}
		return $limit;
    }
	
	/**
     * 推广员最大层数
     * @param array $condition
     * @return int
     */
    public function getPromotionLevel($store_id = 0) {
		if (OPEN_STORE_EXTENSION_STATE==10){
		    $limit = C('gl_promotion_level');
		}else{
		    $condition['store_id'] = $store_id;
		    $limit = $this->where($condition)->get_field('promotion_level');
		}
		return $limit;
    }	
}