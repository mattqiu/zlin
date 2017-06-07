<?php
/**
 * 会员流览记录管理
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
class member_browse_historyModel extends Model {

    public function __construct() {
       parent::__construct('member_browse_history'); 
    }

	/**
	 * 检查浏览记录内商品是否存在
	 *
	 * @param
	 */
	public function checkHistory($memberid='',$goodsid='') {  
		return $this->where(array('member_id'=>$memberid,'goods_id'=>$goodsid))->find();
	}
	
	/**
	 * 取得 单条浏览记录信息
	 * @param unknown $condition
	 * @param string $field
	 */
	public function getHistoryInfo($condition = array(), $field = '*') {
	   return $this->field($field)->where($condition)->find();    
	}

	/**
	 * 将商品添加到浏览记录中
	 *
	 * @param array	$data	商品数据信息
	 * @param string $save_type 保存类型，可选值 db,cookie,cache
	 * @param int $quantity 购物数量
	 */	
	public function addHistory($goods_info = array()) {
		$this->_addHistoryCookie($goods_info);		
        if ($_SESSION['is_login']=='1'){
			$this->_addHistoryDb($goods_info);
		}
	}

	/**
	 * 添加数据库浏览记录
	 *
	 * @param unknown_type $goods_info
	 * @param unknown_type $quantity
	 * @return unknown
	 */
	private function _addHistoryDb($goods_info = array(),$quantity) {
	    //验证浏览记录商品是否已经存在
		$array    = array();
		$array['member_id']	= $_SESSION['member_id'];
		$array['goods_id']	= $goods_info['goods_id'];
		$array['goods_name'] = $goods_info['goods_name'];
		$array['goods_price'] = $goods_info['goods_price'];
		$array['goods_marketprice']   = $goods_info['goods_marketprice'];
		$array['goods_image'] = $goods_info['goods_image'];
		$array['gc_id'] = $goods_info['gc_id'];
		$array['store_id'] = $goods_info['store_id'];
		$array['store_name'] = $goods_info['store_name'];
		$array['fav_time']	= time();
			
    	$check_history	= $this->checkHistory($_SESSION['member_id'],$goods_info['goods_id']);
    	if (!empty($check_history)){
			$bh_id=$check_history['bh_id'];
			$result = $this->where(array('bh_id'=>$bh_id))->update($array);
		}else{
		    $result = $this->insert($array);
		}
		$time_from = time()-60*60*24*30;
		$this->where(array('member_id'=>$_SESSION['member_id'],'fav_time'=>array('lt',$time_to)))->delete();

		return $result;
	}
	/**
	 * 添加到cookie浏览记录,最多保存6个商品
	 *
	 * @param unknown_type $goods_info
	 * @param unknown_type $quantity
	 * @return unknown
	 */
	private function _addHistoryCookie($goods_info = array(), $quantity = null) {
    	$cookievalue = $goods_info ['goods_id']. '-' . $goods_info ['store_id'];
        if (cookie('viewed_goods')) {
            $string_viewed_goods = decrypt(cookie('viewed_goods'), MD5_KEY);
            if (get_magic_quotes_gpc()) {
                $string_viewed_goods = stripslashes($string_viewed_goods); // 去除斜杠
            }
            $vg_ca = @unserialize($string_viewed_goods);
            $sign = true;
            if ( !empty($vg_ca) && is_array($vg_ca)) {
                foreach ($vg_ca as $vk => $vv) {
                    if ($vv == $cookievalue) {
                        $sign = false;
                    }
                }
            } else {
                $vg_ca = array();
            }
            
            if ($sign) {
                if (count($vg_ca) >= 6) {
                    $vg_ca[] = $cookievalue;
                    array_shift($vg_ca);//删除数组中的第一个元素,并返回被删除元素的值
                } else {
                    $vg_ca[] = $cookievalue;
                }
            }
        } else {
            $vg_ca[] = $cookievalue;
        }
        $vg_ca = encrypt(serialize($vg_ca), MD5_KEY);
        setIMCookie('viewed_goods', $vg_ca);
		return true;
	}

	/**
	 * 浏览记录列表 
	 *
	 * @param string $type 存储类型 db,cache,cookie
	 * @param unknown_type $condition
	 */	
	public function listHistory() {
		$history_list = $this->where(array('member_id' => $_SESSION['member_id']))->page(8)->order('fav_time desc')->select();
		return $history_list;        
	}

	/**
	 * 删除浏览记录
	 * 
	 * @param string $type 存储类型 db,cache,cookie
	 * @param unknown_type $condition
	 */
	public function delHistory($goods_id='') {
		if ($goods_id=='all'){
			setIMCookie('viewed_goods','',-3600);
			$this->where(array('member_id' => $_SESSION['member_id']))->delete();
			$result = true;
		}else{
			$history_str = get_magic_quotes_gpc() ? stripslashes(cookie('viewed_goods')) : cookie('viewed_goods');
        	$history_str = base64_decode(decrypt($history_str));
        	$history_array = @unserialize($history_str);
			
			if ( !empty($history_array) && is_array($history_array)) {
                foreach ($history_array as $vk => $vv) {
					$array = explode('-', $vv);
                    if ($array[0] == $goods_id) {
                        unset($history_array[$vk]);
                    }
                }
            }
			$vg_ca = encrypt(serialize($history_array), MD5_KEY);
            setIMCookie('viewed_goods', $vg_ca);
			
			$result = $this->where(array('member_id' => $_SESSION['member_id'],'goods_id'=>$goods_id))->delete();
		}
		$data=array();
		$data['done'] = $result;
		
		if (strtoupper(CHARSET) == 'GBK'){
			$data = Language::getUTF8($data);
		}
		echo json_encode($data);
		exit;
	}

	/**
	 * 清空购物车
	 *
	 * @param string $type 存储类型 db,cache,cookie
	 * @param unknown_type $condition
	 */
	public function clearCart($type, $condition = array()) {
	    if ($type == 'cache') {
            $obj_cache = Cache::getInstance(C('cache.type'));
            $obj_cache->rm($_COOKIE['PHPSESSID'],'cart_');
	    } elseif ($type == 'cookie') {
            setIMCookie('cart','',-3600);
	    } else if ($type == 'db') {
	        //数据库暂无浅清空操作
	    }
	}
}
