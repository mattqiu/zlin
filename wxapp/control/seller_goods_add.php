<?php
/**
 * 商品管理
 * Created by PhpStorm.
 * User: WhartonChan
 * Date: 2016/1/27
 * Time: 12:29
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class seller_goods_addControl extends BaseSellerControl {
    
    public function __construct() {
        parent::__construct();
        
    }
    
    public function indexOp() {
    	
    }
    
    /**
     * 保存商品（商品发布第二步使用）
     */
    public function save_goodsOp() {
        $logic_goods = Logic('goods');
        //以下是以供应商的身份上传的
        if(!empty($_POST['g_costprice'])){
        	$_POST['g_take'] = $_POST['g_costprice']; //商家回款=店铺成本 
        	$_POST['g_gain'] = 0.00; //商家利润=店铺成本-商家回款
        	$_POST['g_discount'] = $_POST['g_price']/$_POST['g_marketprice']; //折扣=会员价/吊牌价
        }
        //商品发布
        if(!empty($_POST['g_state'])){
        	$_POST['g_state'] = 1;
        }else{
        	$_POST['g_state'] = 0;
        }
        //商品推荐
        if(!empty($_POST['g_commend'])){
        	$_POST['g_commend'] = 1;
        }else{
        	$_POST['g_commend'] = 0;
        }
        //是否放入市场
        if(!empty($_POST['is_market'])){
        	$_POST['is_market'] = 1;
        }else{
        	$_POST['is_market'] = 0;
        }
	file_put_contents('test.log',"添加商品参数：".json_encode($_POST).PHP_EOL,FILE_APPEND); 
        $result =  $logic_goods->saveGoods(
            $_POST,
            $this->store_id, 
            $this->store_info['store_name'], 
            $this->store_info['store_state'], 
            $_REQUEST['seller_id'], 
            $_REQUEST['seller_name']
        );
        if(!$result['state']) {
            output_error("添加失败");
        }
        output_data(array('commonid' => $result['data']), "添加成功");
    }

    /**
     * 第三步添加颜色图片
     */
    public function add_step_threeOp() {
        $common_id = intval($_GET['commonid']);
        if ($common_id <= 0) {
            showMessage(L('wrong_argument'), urlMobile('store_goods_add'), 'html', 'error');
        }

        $model_goods = Model('goods');
        $img_array = $model_goods->getGoodsList(array('goods_commonid' => $common_id), 'color_id,min(goods_image) as goods_image', 'color_id');
        // 整理，更具id查询颜色名称
        if (!empty($img_array)) {
            $colorid_array = array();
            $image_array = array();
            foreach ($img_array as $val) {
                $image_array[$val['color_id']][0]['goods_image'] = $val['goods_image'];
                $image_array[$val['color_id']][0]['is_default'] = 1;
                $colorid_array[] = $val['color_id'];
            }
            Tpl::output('img', $image_array);
        }

        $common_list = $model_goods->getGoodsCommonInfoByID($common_id, 'spec_value');
        $spec_value = unserialize($common_list['spec_value']);
        Tpl::output('value', $spec_value['1']);

        $model_spec = Model('spec');
        $value_array = $model_spec->getSpecValueList(array('sp_value_id' => array('in', $colorid_array), 'store_id' => $this->store_id), 'sp_value_id,sp_value_name');
        if (empty($value_array)) {
            $value_array[] = array('sp_value_id' => '0', 'sp_value_name' => '无颜色');
        }
        Tpl::output('value_array', $value_array);

        Tpl::output('commonid', $common_id);
        Tpl::showpage('store_goods_add.step3');
    }

    /**
     * 保存商品颜色图片
     */
    public function save_imageOp(){
        if (chksubmit()) {
            $common_id = intval($_POST['commonid']);
            if ($common_id <= 0 || empty($_POST['img'])) {
                showMessage(L('wrong_argument'));
            }
            $model_goods = Model('goods');
            // 保存
            $insert_array = array();
            foreach ($_POST['img'] as $key => $value) {
                $k = 0;
                foreach ($value as $v) {
                    if ($v['name'] == '') {
                        continue;
                    }
                    // 商品默认主图
                    $update_array = array();        // 更新商品主图
                    $update_where = array();
                    $update_array['goods_image']    = $v['name'];
                    $update_where['goods_commonid'] = $common_id;
                    $update_where['color_id']       = $key;
                    if ($k == 0 || $v['default'] == 1) {
                        $k++;
                        $update_array['goods_image']    = $v['name'];
                        $update_where['goods_commonid'] = $common_id;
                        $update_where['color_id']       = $key;
                        // 更新商品主图
                        $model_goods->editGoods($update_array, $update_where);
                    }
                    $tmp_insert = array();
                    $tmp_insert['goods_commonid']   = $common_id;
                    $tmp_insert['store_id']         = $this->store_id;
                    $tmp_insert['color_id']         = $key;
                    $tmp_insert['goods_image']      = $v['name'];
                    $tmp_insert['goods_image_sort'] = ($v['default'] == 1) ? 0 : intval($v['sort']);
                    $tmp_insert['is_default']       = $v['default'];
                    $insert_array[] = $tmp_insert;
                }
            }
            $rs = $model_goods->addGoodsImagesAll($insert_array);
            if ($rs) {
                redirect(urlMobile('store_goods_add', 'add_step_four', array('commonid' => 0)));
            } else {
                showMessage(L('nc_common_save_fail'));
            }
        }
    }

    /**
     * 商品发布第四步
     */
    public function add_step_fourOp() {
        // 单条商品信息
        $goods_info = Model('goods')->getGoodsInfo(array('goods_commonid' => $_GET['commonid']));

        // 自动发布动态
        $data_array = array();
        $data_array['goods_id'] = $goods_info['goods_id'];
        $data_array['store_id'] = $goods_info['store_id'];
        $data_array['goods_name'] = $goods_info['goods_name'];
        $data_array['goods_image'] = $goods_info['goods_image'];
        $data_array['goods_price'] = $goods_info['goods_price'];
        $data_array['goods_transfee_charge'] = $goods_info['goods_freight'] == 0 ? 1 : 0;
        $data_array['goods_freight'] = $goods_info['goods_freight'];
        $this->storeAutoShare($data_array, 'new');

        Tpl::output('allow_gift', Model('goods')->checkGoodsIfAllowGift($goods_info));
        Tpl::output('goods_id', $goods_info['goods_id']);
        Tpl::showpage('store_goods_add.step4');
    }

    /**
     * 上传图片
     */
    public function image_uploadOp() {
    	// 判断图片数量是否超限
    	$model_album = Model('album');
    	$album_limit = 0;
    	$class_info = $model_album->getOne(array('store_id' => $this->store_id, 'is_default' => 1), 'album_class');
    	// 上传图片
    	$upload = new UploadFile();
    	$upload->set('default_dir', ATTACH_GOODS . DS . $this->store_id . DS . $upload->getSysSetPath());
    	$upload->set('max_size', C('image_max_filesize'));
        
    	$upload->set('thumb_width', GOODS_IMAGES_WIDTH);
    	$upload->set('thumb_height', GOODS_IMAGES_HEIGHT);
    	$upload->set('thumb_ext', GOODS_IMAGES_EXT);
    	$upload->set('fprefix', $this->store_id);
    	$upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
    	$result = $upload->upfile($_POST['name']);
    	if (!$result) {
    		if (strtoupper(CHARSET) == 'GBK') {
    			$upload->error = Language::getUTF8($upload->error);
    		}
    		$output = array();
    		$output['error'] = $upload->error;
    		$output = json_encode($output);
    		exit($output);
        }
	$img_path = $upload->getSysSetPath() . $upload->file_name;
    	// 取得图像大小
    	list($width, $height, $type, $attr) = getimagesize(BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $this->store_id . DS . $img_path);
    	// 存入相册
    	$image = explode('.', $_FILES[$_POST['name']]["name"]);
    	$insert_array = array();
    	$insert_array['apic_name'] = $image['0'];
    	$insert_array['apic_tag'] = '';
    	$insert_array['aclass_id'] = $class_info['aclass_id'];
    	$insert_array['apic_cover'] = $img_path;
    	$insert_array['apic_size'] = intval($_FILES[$_POST['name']]['size']);
    	$insert_array['apic_spec'] = $width . 'x' . $height;
    	$insert_array['upload_time'] = TIMESTAMP;
    	$insert_array['store_id'] = $this->store_id;
    	$model_album->addPic($insert_array);
    	//$data = array ();
    	//$data ['thumb_name'] = cthumb($upload->getSysSetPath() . $upload->thumb_image, 240, $this->store_id);
    	//$data ['name']      = $img_path;
    
    	// 整理为json格式
    	//$output = json_encode($img_path);
    	echo $img_path;
    	exit();
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
    	$stc_id = intval($_REQUEST['stc_id']);//父级分类
    	$condition = array(
    			'stc_id' => $stc_id,
    			'store_id' => $store_id,
    	);
    	$store_goods_class = Model('store_goods_class')->getStoreGoodsClassList($condition);
    
    	output_data(array('classList'=>$store_goods_class,'childCount' =>count($store_goods_class)));
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
     * AJAX查询品牌
     */
    public function ajax_get_brandOp() {
        $type_id = intval($_GET['tid']);
        $initial = trim($_GET['letter']);
        $keyword = trim($_GET['keyword']);
        $type = trim($_GET['type']);
        if (!in_array($type, array('letter', 'keyword')) || ($type == 'letter' && empty($initial)) || ($type == 'keyword' && empty($keyword))) {
            echo json_encode(array());die();
        }

        // 实例化模型
        $model_type = Model('type');
        $where = array();
        $where['type_id'] = $type_id;
        // 验证类型是否关联品牌
        $count = $model_type->getTypeBrandCount($where);
        if ($type == 'letter') {
            switch ($initial) {
                case 'all':
                    break;
                case '0-9':
                    $where['brand_initial'] = array('in', array(0,1,2,3,4,5,6,7,8,9));
                    break;
                default:
                    $where['brand_initial'] = $initial;
                    break;
            }
        } else {
            $where['brand_name|brand_initial'] = array('like', '%' . $keyword . '%');
        }
        if ($count > 0) {
            $brand_array = $model_type->typeRelatedJoinList($where, 'brand', 'brand.brand_id,brand.brand_name,brand.brand_initial');
        } else {
            unset($where['type_id']);
            $brand_array = Model('brand')->getBrandPassedList($where, 'brand_id,brand_name,brand_initial', 0, 'brand_initial asc, brand_sort asc');
        }
        echo json_encode($brand_array);die();
    }
    
    /**
     * 三方店铺验证，商品数量，有效期
     */
    private function checkStore(){
    	$goodsLimit = (int) $this->store_grade['sg_goods_limit'];
    	if ($goodsLimit > 0) {
    		// 是否到达商品数上限
    		$goods_num = Model('goods')->getGoodsCommonCount(array('store_id' => $this->store_id));
    		if ($goods_num >= $goodsLimit) {
    			output_data(array('goodsLimit'=>$goodsLimit));
    		}
    	}
    }    

	/**
	 * 添加商品简易信息库
	 */
	public function add_barcodeOp() {
	
		$model_goods = Model('goods');
		$store_id = $_REQUEST['store_id'];
		$g_barcode = $_REQUEST['g_barcode'];
		if(empty($store_id)){ //没有商家
			output_error("没有获取到店铺信息");
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
		$result['msg'] = "商品条码成功入库";
		output_data($result);
	}
}
