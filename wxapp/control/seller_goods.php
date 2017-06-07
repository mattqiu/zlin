<?php
/**
 * 商家管理
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class seller_goodsControl extends BaseSellerControl {

    public function __construct(){
        parent::__construct();
    }
    
    /**
     * 出售中的商品列表
     */
    public function goods_listOp() {
        $keyword = $_POST['keyword'];
        $goods_type = $_POST['goods_type'];
        $search_type = $_POST['search_type'];

        $model_goods = Model('goods');

        $condition = array();
        $condition['store_id'] = $this->store_info['store_id'];
        if (trim($keyword) != '') {
            switch ($search_type) {
                case 0:
                    $condition['goods_name'] = array('like', '%' . trim($keyword) . '%');
                    break;
                case 1:
                    $condition['goods_serial'] = array('like', '%' . trim($keyword) . '%');
                    break;
                case 2:
                    $condition['goods_commonid'] = intval($keyword);
                    break;
                default:
                	$condition['goods_name|goods_serial|goods_jingle|goods_barcode'] = array('like', '%' . $_REQUEST['keywords'] . '%');
                	break;
            }
        }

        $page_nums = !empty($_REQUEST['page_count'])?$_REQUEST['page_count']:$this->page; //每页显示的条数
        $page_curr = !empty($_REQUEST['curpage'])?$_REQUEST['curpage']:1; //当前显示第几页
        $fields = 'goods_id,goods_commonid,goods_name,goods_price,goods_marketprice,goods_tradeprice,goods_addtime,goods_image,goods_state,goods_collect,goods_salenum,up_id,promotion_cid';
        switch ($goods_type) {
            case 'lockup':
                $goods_list = $model_goods->getGoodsOnlineList($condition, $fields, $this->page);
                break;
            case 'offline':
                $goods_list = $model_goods->getGoodsOnlineList($condition, $fields, $this->page);
                break;
            default:
                $goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, $fields,'goods_commend desc,goods_edittime desc,goods_addtime desc', $this->page);
                break;
        }

        // 计算库存
        $storage_array = $model_goods->calculateStorage($goods_list);

        // 整理输出的数据格式
        foreach ($goods_list as $key => $value) {
        	//推广佣金
        	$promotion_cid = $value['promotion_cid'];
        	$model_extension_commis_class = Model('extension_commis_class');
        	//返佣计算
        	if(!empty($promotion_cid)){//商品详情是否存在佣金模板，有则优先读取商品模板的佣金
        		//店铺是否存在默认的返佣
        		$commis_arr = $model_extension_commis_class->getRebateValueByID($promotion_cid,$value['goods_price'],$value['goods_tradeprice']);
        		//返佣积分
        		$goods_list[$key]['commis_price'] = $commis_price = $commis_arr['commis_price'];
        		//返佣云币
        		$goods_list[$key]['commis_points'] = $commis_points = $commis_arr['commis_points'];
        	}else{
        		$goods_list[$key]['commis_price'] = $commis_price = $model_extension_commis_class->getCommisPriceByDefault($store_id,$value['goods_price'],$value['goods_tradeprice']);//系统默认佣金
        		$goods_list[$key]['commis_points'] = $commis_points = $model_extension_commis_class->getCommisPointsByID($promotion_cid,$value['goods_price'],$value['goods_tradeprice']);
        	}
            $goods_list[$key]['goods_addtime'] = date('Y-m-d', $goods_list[$key]['goods_addtime']);
            $goods_list[$key]['goods_image'] = cthumb($goods_list[$key]['goods_image']);
            $goods_list[$key]['goods_storage'] = empty($value['goods_storage'])?0:$value['goods_storage'];
            $goods_list[$key]['operate_way'] = empty($value['up_id'])?'自营':'代销';
        }

        $page_count = $model_goods->gettotalpage();

        output_data(array('goods_list' => $goods_list),'加载成功！', wxapp_page($page_count));
    }

    /**
     * 扫描商品条形码
     * @param 条形码 scancode
     * @return 返回商品基础信息:goods_price,goods_marketprice,goods_tradeprice
     */
    public function scan_barcodeOp() {
    	$goods_barcode = !empty($_REQUEST['goods_barcode'])?$_REQUEST['goods_barcode']:0;
    	if(strpos($goods_barcode,' ')){
    		$scan_temp = explode(" ",$goods_barcode);
    		$goods_barcode = end($scan_temp);
    	}
    	if(strpos($goods_barcode,',')){
    		$scan_temp = explode(",",$goods_barcode);
    		$goods_barcode = end($scan_temp);
    	}
    	if(empty($goods_barcode)){
    		output_error('条形码不可为空,是否存在特殊字符如：，或 空格');
    	}
    	$gwhere = array();
    	$gwhere['store_id'] = $store_id = $_REQUEST['store_id'];
    	$gwhere['goods_barcode|goods_serial'] = $goods_barcode;
    	// 商品详细信息
    	$model_goods = Model('goods');
    	$goods_info = $model_goods->getGoodsInfo($gwhere,'goods_id,goods_price,goods_name,goods_marketprice,goods_tradeprice,goods_serial,goods_image');
    	if (empty($goods_info)) {
    		if(empty($store_id)){
    			$store_id = 0;
    		}
    		$gbwhere['store_id'] = $store_id;
    		$gbwhere['goods_barcode|goods_gbcode'] = $goods_barcode;
    		$goods_barcode_info = $model_goods->getGoodsBarcodeInfo($gbwhere,'goods_price,goods_name,goods_marketprice,goods_tradeprice');
    		if(empty($goods_barcode_info)){
    			output_error('商品条形码：'.$goods_barcode.'不存在', array('error_code'=>404));
    		}else{
    			if(empty($goods_barcode_info['goods_name'])){
    				$goods_barcode_info['goods_name'] = "临时库的商品，条形码是".$goods_barcode;
    			}
    			$goods_info = $goods_barcode_info;
    			$goods_info['goods_image'] = thumb($goods_info);
    		}
    	}else{
    		$goods_info['goods_image'] = thumb($goods_info);
    	}
    	$goods_info['goods_barcode'] = $goods_barcode;
    	output_data(array('goodsInfo'=>$goods_info));
    }
    /**
     * 商品详细信息
     */
    public function get_goods_infoOp() {
    	$common_id = $_REQUEST['goods_commonid'];
    	$model_goods = Model('goods');
    	$goodscommon_info = $model_goods->getGoodsCommonInfoByID($common_id);
    	if (empty($goodscommon_info)) {
    		output_error('参数错误'.$common_id);
    	}
    	$where = array('goods_commonid' => $common_id, 'store_id' => $this->store_id);
    	$goodscommon_info['g_storage'] = $model_goods->getGoodsSum($where, 'goods_storage');
    	$goodscommon_info['spec_name'] = $spec_name = unserialize($goodscommon_info['spec_name']);
    	$goodscommon_info['spec_value'] = $spec_value = unserialize($goodscommon_info['spec_value']);
    	$goodscommon_info['goods_image_url'] = thumb($goodscommon_info);   
    	foreach ($spec_value as $k => $v ) {
    		if (!empty($spec_name)) {
    			$goods_spec[$k]['sp_id'] = $k;
    			$goods_spec[$k]['sp_name'] = $spec_name[$k];
    			foreach ($v as $key => $val){
    				$goods_spec[$k]['sp_value'][$key]['sp_v_id'] = $key;
    				$goods_spec[$k]['sp_value'][$key]['sp_v_name'] = $val;
    				}
    			}
    		}
    	$spec_list = $model_goods->getGoodsList($where, 'goods_name,goods_price,goods_spec,goods_id,store_id,goods_image,color_id');
    	$spec_arr = array();
    	foreach ($spec_list as $sk => $spv ) {
    		$spv['goods_image'] = thumb($spv);
    		if (!empty($spv['goods_spec'])) {
    			$g_spec = unserialize($spv['goods_spec']);
    			$spec = '';
    			foreach ($g_spec as $gkey => $gval){
    				if(!empty($spec)){
    					$spec = $spec.'_'.$gkey;
    				}else{
    					$spec = $gkey;
    				}
    			}
    			$spv['spec_id'] = $spec;
    		}
    		$spec_arr[$sk] = $spv;
    	}
    	$goodscommon_info['spec'] = $spec_arr;
    	$goodscommon_info['goods_spec'] = $goods_spec;    	

    	output_data(
    	array(
    	'goodscommon_info' => $goodscommon_info,
    	'goods_spec' => $goods_spec,
    	'attr_checked' => $attr_checked,
    	'spec_checked' => $spec_checked,
    	)
    	);
    }
    /**
     * 商品详细信息
     */
    public function goods_infoOp() {
        $common_id = $_REQUEST['goods_commonid'];
        $model_goods = Model('goods');
        $goodscommon_info = $model_goods->getGoodsCommonInfoByID($common_id);
        if (empty($goodscommon_info) || $goodscommon_info['store_id'] != $this->store_info['store_id'] || $goodscommon_info['goods_lock'] == 1) {
            output_error('参数错误');
        }
        $where = array('goods_commonid' => $common_id, 'store_id' => $this->store_info['store_id']);
        $goodscommon_info['g_storage'] = $model_goods->getGoodsSum($where, 'goods_storage');
        $goodscommon_info['spec_name'] = unserialize($goodscommon_info['spec_name']);
        $goodscommon_info['goods_image_url'] = thumb($goodscommon_info);
        
        $where = array('goods_commonid' => $common_id, 'store_id' => $this->store_info['store_id']);
        
        // 取得商品规格的输入值
        $goods_array = $model_goods->getGoodsList($where, 'goods_id,goods_marketprice,goods_price,goods_storage,goods_serial,goods_storage_alarm,goods_spec,goods_barcode');
        $sp_value = array();
        $attr_checked = array();
        $spec_checked = array();
        if (is_array($goods_array) && !empty($goods_array)) {
            $model_type = Model('type');
            // 取得已选择了哪些商品的属性
            $attr_checked_l = $model_type->typeRelatedList(
                'goods_attr_index',
                array('goods_id' => intval($goods_array[0]['goods_id'])),
                'attr_value_id'
            );
            if (is_array($attr_checked_l) && !empty($attr_checked_l)) {
                foreach($attr_checked_l as $val) {
                    $array = array();
                    $array['attr_id'] = $val['attr_id'];
                    $array['attr_value_id'] = $val['attr_value_id'];
                    $attr_checked[] = $array;
                }
            }
        
            foreach ( $goods_array as $k => $v ) {
                $a = unserialize($v['goods_spec']);
                if (!empty($a)) {
                    foreach ($a as $key => $val){
                        $spec_checked[$key]['id'] = $key;
                        $spec_checked[$key]['name'] = $val;
                    }
                    $matchs = array_keys($a);
                    sort($matchs);
                    $id = str_replace ( ',', '', implode ( ',', $matchs ) );
                    $sp_value ['i_' . $id . '|marketprice'] = $v['goods_marketprice'];
                    $sp_value ['i_' . $id . '|price'] = $v['goods_price'];
                    $sp_value ['i_' . $id . '|id'] = $v['goods_id'];
                    $sp_value ['i_' . $id . '|stock'] = $v['goods_storage'];
                    $sp_value ['i_' . $id . '|alarm'] = $v['goods_storage_alarm'];
                    $sp_value ['i_' . $id . '|sku'] = $v['goods_serial'];
                    $sp_value ['i_' . $id . '|barcode'] = $v['goods_barcode'];
                }
            }
        }

        $goods_class = Model('goods_class')->getGoodsClassLineForTag($goodscommon_info['gc_id']);
        
        $model_type = Model('type');
        // 获取类型相关数据
        $typeinfo = $model_type->getAttr($goods_class['type_id'], $this->store_info['store_id'], $goodscommon_info['gc_id']);
        list($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;

        // 自定义属性
        $custom_list = Model('type_custom')->getTypeCustomList(array('type_id' => $goods_class['type_id']));
        $custom_list = array_under_reset($custom_list, 'custom_id');
        

        output_data(
            array(
                'goodscommon_info' => $goodscommon_info,
                'sp_value' => $sp_value,
                'attr_checked' => $attr_checked,
                'spec_checked' => $spec_checked,
                'spec_json' => $spec_json,
                'spec_list' => $spec_list,
                'attr_list' => $attr_list
            )
        );
    }

    /**
     * 商品详细信息
     */
    public function goods_image_infoOp() {
        $common_id = $_POST['goods_commonid'];
        $model_goods = Model('goods');

        $common_list = $model_goods->getGoodsCommonInfoByID($common_id, 'store_id,goods_lock,spec_value,is_virtual,is_fcode,is_presell');
        if ($common_list['store_id'] != $this->store_info['store_id'] || $common_list['goods_lock'] == 1) {
            output_error('参数错误');
        }
        
        $spec_value = unserialize($common_list['spec_value']);
        // 商品图片
        $image_list = $model_goods->getGoodsImageList(array('goods_commonid' => $common_id));
        $image_array = array();
        if (!empty($image_list)) {
            foreach ($image_list as $val) {
                $val['goods_image_url'] = cthumb($val['goods_image'], 240);
                $image_array[$val['color_id']]['color_id'] = $val['color_id'];
                $image_array[$val['color_id']]['spec_name'] = $spec_value['1'][$val['color_id']];
                $image_array[$val['color_id']]['images'][] = $val;
            }
        }

        output_data(
            array(
                'image_list' => array_values($image_array)
            ));
    }
    /**
     * 商品编辑保存
     */
    public function goods_editOp() {
        $logic_goods = Logic('goods');
    
        unset($_POST['key']);
        $result = $logic_goods->updateGoods(
            $_POST,
            $this->seller_info['store_id'],
            $this->store_info['store_name'],
            $this->store_info['store_state'],
            $this->seller_info['seller_id'],
            $this->seller_info['seller_name'],
            $this->store_info['bind_all_gc']
        );
    
        if(!$result['state']) {
            output_error($result['msg']);
        }
    
        output_data(array('common_id' => $result['data']));
    }
    
    /**
     * 商品图片保存
     */
    public function goods_edit_image() {
        $common_id = intval($_POST['goods_commonid']);
        $rs = Logic('goods')->editSaveImage($_POST['img'], $common_id, $this->store_info['store_id'], $this->seller_info['seller_id'],  $this->seller_info['seller_name']);
        if(!$rs['state']) {
            output_error($rs['msg']);
        }
        output_data('1');
    }

    /**
     * 商品上架
     */
    public function goods_showOp() {
        if ($this->store_info['store_state'] != 1) {
            output_error('店铺正在审核中或已经关闭，不能上架商品');
        }
        $result = Logic('goods')->goodsShow($_POST['commonids'], $this->store_info['store_id'], $this->seller_info['seller_id'], $this->seller_info['seller_name']);
        if(!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
    
    /**
     * 商品下架
     */
    public function goods_unshowOp() {
        $result = Logic('goods')->goodsUnShow($_POST['commonids'], $this->store_info['store_id'], $this->seller_info['seller_id'], $this->seller_info['seller_name']);
        if(!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
    
    /**
     * 商品删除
     */
    public function goods_dropOp() {
        $result = Logic('goods')->goodsDrop($_POST['commonids'], $this->store_info['store_id'], $this->seller_info['seller_id'], $this->seller_info['seller_name']);
        if (!$result['state']) {
            output_error($result['msg']);
        }
        output_data('1');
    }
    
    /**
     * 上传 商品图片
     */
    public function image_uploadOp() {
    	
    	// 判断图片数量是否超限
    	$model_album = Model('album');
    	$album_limit = $this->store_grade['sg_album_limit'];
    	if ($album_limit > 0) {
    		$album_count = $model_album->getCount(array('store_id' => $_SESSION['store_id']));
    		if ($album_count >= $album_limit) {
    			$error = L('store_goods_album_climit');
    			if (strtoupper(CHARSET) == 'GBK') {
    				$error = Language::getUTF8($error);
    			}
    			output_error($error.'=='.$_SESSION['store_id']);
    		}
    	}
    	$class_info = $model_album->getOne(array('store_id' => $_SESSION['store_id'], 'is_default' => 1), 'album_class');
    	 
    	/**
    	 * 上传店铺图片
    	 */
    	
    	if (!empty($_FILES['goods_image']['name'])){
    		$upload = new UploadFile();
    			
    		$upload->set('default_dir', ATTACH_GOODS . DS . $_SESSION ['store_id'] . DS . $upload->getSysSetPath());
	    	$upload->set('max_size', C('image_max_filesize'));
	    	
	    	$upload->set('thumb_width', GOODS_IMAGES_WIDTH);
	    	$upload->set('thumb_height', GOODS_IMAGES_HEIGHT);
	    	$upload->set('thumb_ext', GOODS_IMAGES_EXT);
	    	$upload->set('fprefix', $_SESSION['store_id']);
	    	$upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
	    	$result = $upload->upfile('goods_image');
	    	if ($result){
	    		
    			$img_path = $upload->getSysSetPath() . $upload->file_name;
    			// 取得图像大小
    			list($width, $height, $type, $attr) = getimagesize(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $_SESSION['store_id'] . DS . $img_path);
    			// 存入相册
    			$image = explode('.', $_FILES['goods_image']["name"]);
    			$insert_array = array();
    			$insert_array['apic_name'] = $image['0'];
    			$insert_array['apic_tag'] = '';
    			$insert_array['aclass_id'] = $class_info['aclass_id'];
    			$insert_array['apic_cover'] = $img_path;
    			$insert_array['apic_size'] = intval($_FILES['goods_image']['size']);
    			$insert_array['apic_spec'] = $width . 'x' . $height;
    			$insert_array['upload_time'] = TIMESTAMP;
    			$insert_array['store_id'] = $_SESSION['store_id'];
    			$model_album->addPic($insert_array);
    			
    			$data = array ();
    			$data['pic'] = cthumb($upload->getSysSetPath() . $upload->thumb_image, 60, $_SESSION['store_id']);
    			$data['file_name']      = $img_path;
    			output_data($data);
    		}else {
    			output_error('上传图片尺寸过大！');
    		}
    	}else{
    		output_error('请选择上传图片！');
    	}
    }
    
    /**
     * 上传商品详情图片
     *
     */
    public function upload_picOp() {
    	 
    	// 判断图片数量是否超限
    	$model_album = Model('album');
    	$album_limit = $this->store_grade['sg_album_limit'];
    	if ($album_limit > 0) {
    		$album_count = $model_album->getCount(array('store_id' => $_SESSION['store_id']));
    		if ($album_count >= $album_limit) {
    			$error = L('store_goods_album_climit');
    			if (strtoupper(CHARSET) == 'GBK') {
    				$error = Language::getUTF8($error);
    			}
    			output_error($error.'=='.$_SESSION['store_id']);
    		}
    	}
    	$class_info = $model_album->getOne(array('store_id' => $_SESSION['store_id'], 'is_default' => 1), 'album_class');
    
    	/**
    	 * 上传店铺图片
    	*/
    	
    	if (!empty($_FILES['body_pic']['name'])){
    		$upload = new UploadFile();
    		 
    		$upload->set('default_dir', ATTACH_GOODS . DS . $_SESSION ['store_id'] . DS . $upload->getSysSetPath());
    		$upload->set('max_size', C('image_max_filesize'));
    	  
    		$upload->set('thumb_ext', '_wap');
    		$upload->set('fprefix', $_SESSION['store_id']);
    		$upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
    		$result = $upload->upfile('body_pic');
    		if ($result){
    	   
    			$img_path = $upload->getSysSetPath() . $upload->file_name;
    			// 取得图像大小
    			list($width, $height, $type, $attr) = getimagesize(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $_SESSION['store_id'] . DS . $img_path);
    			// 存入相册
    			$image = explode('.', $_FILES['body_pic']["name"]);
    			$insert_array = array();
    			$insert_array['apic_name'] = $image['0'];
    			$insert_array['apic_tag'] = '';
    			$insert_array['aclass_id'] = $class_info['aclass_id'];
    			$insert_array['apic_cover'] = $img_path;
    			$insert_array['apic_size'] = intval($_FILES['body_pic']['size']);
    			$insert_array['apic_spec'] = $width . 'x' . $height;
    			$insert_array['upload_time'] = TIMESTAMP;
    			$insert_array['store_id'] = $_SESSION['store_id'];
    			$model_album->addPic($insert_array);
    			 
    			$data = array ();
    			$data['pic'] 		= UPLOAD_SITE_URL.DS. ATTACH_GOODS.DS. $_SESSION['store_id'] . DS . $img_path;
    			$data['file_name']  = $img_path;
    			$data['store_id']   = $_SESSION['store_id'];
    			output_data($data);
    		}else {
    			output_error('上传图片尺寸过大！');
    		}
    	}else{
    		output_error('请选择上传图片！');
    	}
    }
    
    
    /**
     * ajax获取商品分类的子级数据
     */
    public function ajax_goods_classOp() {
    	$gc_id = intval($_REQUEST['gc_id']);//父级分类
    	$deep = intval($_REQUEST['deep']);//深度
    	$seller_group_id = 0;//对应权限 $_GET['seller_group_id']
    	$seller_gc_limits = 0;//查询数量 $_GET['seller_gc_limits']
    	$model_goodsclass = Model('goods_class');
    	$childCount = $model_goodsclass->getGoodsClassCount(array('gc_parent_id'=>$gc_id));
    	if(empty($childCount)){
    		//商品没有更多的子类
    		output_data(array('childCount' =>$childCount));
    	}
    	$classList = $model_goodsclass->getGoodsClass($this->store_id, $gc_id, $deep,$seller_group_id,$seller_gc_limits,true);
    	if (empty($classList)) {
    		output_error('该分类没有更多的子类！');
    	}
    	output_data(array('classList'=>$classList,'childCount' =>$childCount));
    }
    /**
     * 店铺商品分类
     */
    public function store_goods_classOp()
    {
    	$store_id = $this->store_id;
    	$stc_p_id = intval($_REQUEST['stc_id']);//父级分类
    	$condition = array(
    		'stc_parent_id' => $stc_p_id,
    		'store_id' => $store_id,
    	);
    	$store_goods_class = Model('store_goods_class')->getStoreGoodsClassList($condition);
    	$sgc_count = count($store_goods_class);
	if (empty($sgc_count)) {
			output_data(array('childCount' =>$sgc_count));
    	}
    	if (empty($store_goods_class)) {
    		output_error('店铺没有还未新建商品分类！');
    	}
    	output_data(array('sgclassList'=>$store_goods_class,'childCount' =>$sgc_count));
    }
    /**
     * 添加店铺商品分类
     */
    public function add_store_gclassOp(){
    	$class_array = array();
    	$class_array['stc_name']      = $_POST['stc_name'];
    	$class_array['stc_parent_id'] = $stc_id = $_POST['stc_id'];
    	$class_array['stc_state']     = 1;
    	$class_array['store_id']      = $this->store_id;
    	$class_array['stc_sort']      = 50;
    	$state = Model('store_goods_class')->addStoreGoodsClass($class_array);
    	if($state){
    		//因为店铺内部的分类只有两层深度，所以再连续添加分类时当$stc_id不为0的时候已经时第二层了
    		if(!empty($stc_id)){
    			$state = $stc_id;
    		}
    		output_data(array('stc_id'=>$state),"添加成功");
    	}else{
    		output_error("添加失败");
    	}
    }
    /**
     * ajax获取商品分类的属性数据
     */
    public function ajax_goods_specOp() {
    	$gc_id = intval($_REQUEST['gc_id']);
    	$model_goodsclass = Model('goods_class');
    	// 更新常用分类信息
    	$goods_class = $model_goodsclass->getGoodsClassLineForTag($gc_id);
    	// 获取类型相关数据
    	$typeinfo = Model('type')->getAttr($goods_class['type_id'], $this->store_id, $gc_id);
    	list($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;
    	// 自定义属性
    	$custom_list = Model('type_custom')->getTypeCustomList(array('type_id' => $goods_class['type_id']));
    	$sign_i = count($spec_list);
    	$list = array();
    	$list['sign_i'] 	= $sign_i;
    	$list['spec_list'] 	= $spec_list;
    	$list['attr_list'] 	= $attr_list;
    	$list['brand_list'] = $brand_list;
    	$list['custom_list'] = $custom_list;
    	if (empty($list)) {
    		output_error('该分类没有关联商品属性！');
    	}
    	output_data($list);
    }
    /**
     * AJAX添加商品规格值
     */
    public function ajax_add_specOp() {
    	$sp_value_name = trim($_REQUEST['sp_value_name']);
    	$gc_id = intval($_REQUEST['gc_id']);
    	$sp_id = intval($_REQUEST['sp_id']);
    	if ($sp_value_name == '' || $gc_id <= 0 || $sp_id <= 0) {
    		output_error('规格名称不可为空或未选中对应的分类');
    	}
    	$insert = array(
    			'sp_value_name' => $sp_value_name,
    			'sp_id' => $sp_id,
    			'gc_id' => $gc_id,
    			'store_id' => $this->store_id,
    			'sp_value_color' => null,
    			'sp_value_sort' => 0,
    	);
    	$specValue= Model('spec')->specValueOne($insert);
    	if(!empty($specValue)){
    		output_error('该规格名称已经存在，不可添加重复的规格名！');
    	}
    	$value_id = Model('spec')->addSpecValue($insert);
    	if ($value_id) {
    		output_data(array('done' => true, 'value_id' => $value_id),'添加成功');
    	} else {
    		output_error('添加失败！');
    	}
    }
    /**
     * AJAX编辑商品规格值
     */
    public function ajax_edit_specOp() {
    	$sp_value_name = trim($_REQUEST['sp_value_name']);
    	$gc_id = intval($_REQUEST['gc_id']);
    	$sp_value_id = intval($_REQUEST['sp_value_id']);
    	if ($sp_value_name == '' || $gc_id <= 0 || $sp_value_id <= 0) {
    		output_error('规格名称不可为空或未选中对应的分类');
    	}
    	$param = array(
    		'sp_value_name' => $sp_value_name,
    		'gc_id' => $gc_id,
    		'store_id' => $this->store_id,
    	);
    	$specValue= Model('spec')->specValueOne($param);
    	if(!empty($specValue)){
    		output_error('该规格名称已经存在，不可重复规格名！');
    	}
    	$update = array(
    			'sp_value_name' => $sp_value_name,
    			'gc_id' => $gc_id
    	);
    	$value_id = Model('spec')->editSpecValue($update,array('sp_value_id' => $sp_value_id));
    	if ($value_id) {
    		output_data(array('done' => true, 'value_id' => $value_id),'修改成功');
    	} else {
    		output_error('修改失败！');
    	}
    }
    /**
     * 添加商品简易信息库
     */
    public function add_goods_simpleOp() {
    	$model_goods = Model('goods');
    	$store_id = $_REQUEST['store_id'];
    	$g_barcode = $_REQUEST['g_barcode'];
    	if(empty($store_id)){ //没有商家
    		output_error("没有获取到店铺信息");
    	}
    	if(empty($g_barcode)){
    		output_error("商品条码不可为空");
    	}
    	if(empty($_POST['g_marketprice'])){
    		output_error("吊牌价不可为空");
    	}
    	if(empty($_POST['g_price'])){
    		output_error("会员价不可为空");
    	}
    	if(empty($_POST['g_tradeprice'])){
    		output_error("批发价不可为空");
    	}
    	$gwhere['store_id'] = $store_id;
    	$gwhere['goods_barcode|goods_gbcode'] = $g_barcode;
    	$gbarcode_info = $model_goods->getGoodsBarcodeInfo($gwhere,'bid');
    	if(!empty($gbarcode_info)){
    		output_error("商品条码已存在");
    	}
    	$goods_costprice = $_REQUEST['g_marketprice'] * 0.25; //暂时定死
    	if($goods_costprice>$_REQUEST['g_price']){
    		$goods_array['goods_costprice'] = $_POST['g_price'];
    	}else{
    		$goods_array['goods_costprice'] = $goods_costprice;
    	}
    	$goods_array['goods_name'] 		= $_POST['g_name'];
    	$goods_array['goods_barcode'] 		= $g_barcode;
    	$goods_array['goods_marketprice'] 	= $_POST['g_marketprice'];
    	$goods_array['goods_price'] 		= $_POST['g_price'];
    	$goods_array['goods_discount'] 		= $_POST['g_discount'];
    	$goods_array['goods_tradeprice'] 	= $_POST['g_tradeprice'];
    	$goods_array['goods_gbcode'] 		= $_POST['g_gbcode'];
    	$goods_array['store_id'] 			= $store_id;
    	$goods_array['saleman_id'] 			= $_REQUEST['member_id'];
    	$goods_array['commis_amount'] 		= $_POST['commis_amount'];
    	$goods_array['rebate_amount'] 		= $_POST['rebate_amount'];
    
    	$bid = $model_goods->addGoodsBarcode($goods_array);
    	if(empty($bid)) {
    		output_error("商品条码入库失败");
    	}
    	$result['bid'] = $bid;
    	$result['sucMsg'] = "商品条码成功入库";
    	output_data($result);
    }
    public function goods_addOp() {
    	$logic_goods = Logic('goods');
    	$s_num = $_POST['s_num'];
    	if(!empty($s_num)&&$s_num>0){
    		$goods_array['g_price'] = $_POST['spec'][0]['price'];
    	}
    	$gi_num = $_POST['gi_num'];
    	$goods_array = $_POST;
    	$image_all = '';
    	if(!empty($gi_num)&&$gi_num>1){
    		$goods_array['image_path'] = $_POST['goods_image'][0];
    		for ($i=0;$i<$gi_num;$i++){
    			$image_all .= $_POST['goods_image'][$i].',';
    		}
    	}
    	$goods_array['image_all'] = $image_all;
    	//商品详情
    	$bi_num = $_POST['bi_num'];
    	$bodyimg_all = '';
    	if(!empty($bi_num)&&$bi_num>1){
    		for ($i=0;$i<$bi_num;$i++){
    			$bodyimg_all .= '<img src="'.UPLOAD_SITE_URL.DS. ATTACH_GOODS.DS. $this->seller_info['store_id'] . DS .$_POST['body_img'][$i].'"/>';
    		}
    	}
    	$goods_array['g_body'] = $goods_array['m_body'] = '<p>'.$_POST['g_body'] .'</p>'. $bodyimg_all;
    	$result = $logic_goods->saveGoods(
    			$goods_array,
    			$this->seller_info['store_id'],
    			$this->store_info['store_name'],
    			$this->store_info['store_state'],
    			$this->seller_info['seller_id'],
    			$this->seller_info['seller_name'],
    			$this->store_info['bind_all_gc']
    	);
    	if(!$result['state']) {
    		output_error($result['msg']);
    	}
    	output_data($result);
    }
}
