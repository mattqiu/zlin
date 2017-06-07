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

class store_goods_addControl extends BaseSellerControl {
    
    public function __construct() {
        parent::__construct();
        Language::read('member_store_goods_index');
        
    }
    public function indexOp() {
        $this->checkStore();
        $this->add_step_twoOp();
    }
	
    /**
     * 添加商品
     */
    public function add_step_oneOp() {
        // 实例化商品分类模型
        $model_goodsclass = Model('goods_class');
        // 商品分类
        $goods_class = $model_goodsclass->getGoodsClass($_SESSION['store_id'],0,1,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits'],true);

        // 常用商品分类
        $model_staple = Model('goods_class_staple');
        $param_array = array();
        $param_array['member_id'] = $_SESSION['member_id'];
        $staple_array = $model_staple->getStapleList($param_array);

        Tpl::output('staple_array', $staple_array);
        Tpl::output('goods_class', $goods_class);
        Tpl::showpage('store_goods_add.step2');
        //output_data($result);
    }

    public function goods_classOp() {
        // 实例化商品分类模型
        $model_goodsclass = Model('goods_class');
        // 商品分类
        $goods_class = $model_goodsclass->getGoodsClass($_SESSION['store_id'],0,1,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits'],true);

        // 常用商品分类
        $model_staple = Model('goods_class_staple');
        $param_array = array();
        $param_array['member_id'] = $_SESSION['member_id'];
        $staple_array = $model_staple->getStapleList($param_array);

        output_data(array(
            'staple_array'=> $staple_array,
            'goods_class'=> $goods_class
        ));
    }

    /**
     * 添加商品
     */
    public function add_step_twoOp() {
        // 实例化商品分类模型
        $model_goodsclass = Model('goods_class');
        // 现暂时改为从匿名“自营店铺专属等级”中判断
        $editor_multimedia = false;
        if ($this->store_grade['sg_function'] == 'editor_multimedia') {
            $editor_multimedia = true;
        }
		
        //分类
        Tpl::output('step1',false);
        Tpl::output('editor_multimedia', $editor_multimedia);

        $gc_id = intval($_GET['class_id']);

        // 验证商品分类是否存在且商品分类是否为最后一级
        $data = Model('goods_class')->getGoodsClassForCacheModel();
        if (!isset($data[$gc_id]) || isset($data[$gc_id]['child']) || isset($data[$gc_id]['childchild'])) {
            //showDialog(L('store_goods_index_again_choose_category1'));
	        
            Tpl::output('step1',true);
            Tpl::showpage('store_goods_add.step2');
            exit();
        }
        // 如果不是自营店铺或者自营店铺未绑定全部商品类目，读取绑定分类
        /*if (!checkPlatformStoreBindingAllGoodsClass()) {
            $where['class_1|class_2|class_3'] = $gc_id;
            $where['store_id'] = $_SESSION['store_id'];
            $rs = Model('store_bind_class')->getStoreBindClassInfo($where);
            if (empty($rs)) {
                showMessage(L('store_goods_index_again_choose_category2'));
            }
        }*/

        //权限组对应分类权限判断
        if (!$_SESSION['seller_gc_limits']&& $_SESSION['seller_group_id']) {
            $rs = Model('seller_group_bclass')->getSellerGroupBclassInfo(array('group_id'=>$_SESSION['seller_group_id'],'gc_id'=>$gc_id));
            if (!$rs) {
                showMessage('您所在的组无权操作该分类下的商品', '', 'html', 'error');
            }
        }
        
        // 更新常用分类信息
        $goods_class = $model_goodsclass->getGoodsClassLineForTag($gc_id);
        Tpl::output('goods_class', $goods_class);
        Model('goods_class_staple')->autoIncrementStaple($goods_class, $_SESSION['member_id']);

        // 获取类型相关数据
        $typeinfo = Model('type')->getAttr($goods_class['type_id'], $_SESSION['store_id'], $gc_id);
        list($spec_json, $spec_list, $attr_list, $brand_list) = $typeinfo;
        Tpl::output('sign_i', count($spec_list));
        Tpl::output('spec_list', $spec_list);
        Tpl::output('attr_list', $attr_list);
        Tpl::output('brand_list', $brand_list);
        // 自定义属性
        $custom_list = Model('type_custom')->getTypeCustomList(array('type_id' => $goods_class['type_id']));
        Tpl::output('custom_list', $custom_list);

        // 实例化店铺商品分类模型
        $store_goods_class = Model('store_goods_class')->getClassTree(array('store_id' => $_SESSION ['store_id'], 'stc_state' => '1'));
        Tpl::output('store_goods_class', $store_goods_class);

        // 小时分钟显示
        $hour_array = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
        Tpl::output('hour_array', $hour_array);
        $minute_array = array('05', '10', '15', '20', '25', '30', '35', '40', '45', '50', '55');
        Tpl::output('minute_array', $minute_array);

        // 关联版式
        $plate_list = Model('store_plate')->getStorePlateList(array('store_id' => $_SESSION['store_id']), 'plate_id,plate_name,plate_position');
        $plate_list = array_under_reset($plate_list, 'plate_position', 2);
        Tpl::output('plate_list', $plate_list);
        
        // 供货商
        $supplier_list = Model('store_supplier')->getStoreSupplierList(array('sup_store_id' => $_SESSION['store_id']));
        Tpl::output('supplier_list', $supplier_list);

        Tpl::showpage('store_goods_add.step2');
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
        file_put_contents('test.log',"添加商品参数：".json_encode($_POST).PHP_EOL,FILE_APPEND);
        $result =  $logic_goods->saveGoods(
            $_POST,
            $_SESSION['store_id'], 
            $_SESSION['store_name'], 
            $this->store_info['store_state'], 
            $_SESSION['seller_id'], 
            $_SESSION['seller_name'],
            $_SESSION['bind_all_gc']
        );

        if(!$result['state']) {
            showMessage(L('error') . $result['msg'], urlMobile('store_goods_add'), 'html', 'error');
        }

        redirect(urlMobile('store_goods_add', 'add_step_three', array('commonid' => $result['data'])));
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
        $value_array = $model_spec->getSpecValueList(array('sp_value_id' => array('in', $colorid_array), 'store_id' => $_SESSION['store_id']), 'sp_value_id,sp_value_name');
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
                    $tmp_insert['store_id']         = $_SESSION['store_id'];
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
        $logic_goods = Logic('goods');
        
        $result =  $logic_goods->uploadGoodsImage(
            $_POST['name'],
            $_SESSION['store_id'],
            $this->store_grade['sg_album_limit']
        );
        file_put_contents('test.log',"商品图片参数：".json_encode($result).PHP_EOL,FILE_APPEND);
        if(!$result['state']) {
            echo json_encode(array('error' => $result['msg']));die;
        }
        $result['data']['thumb_name']=cthumb($result['data']['name'],'default');
        echo json_encode($result['data']);die;
    }

    /**
     * ajax获取商品分类的子级数据
     */
    public function ajax_goods_classOp() {
        $gc_id = intval($_GET['gc_id']);
        $deep = intval($_GET['deep']);


        $model_goodsclass = Model('goods_class');
        $list = $model_goodsclass->getGoodsClass($_SESSION['store_id'], $gc_id, $deep,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits'],true);
        if (empty($list)) {
            echo 'null';
            exit();
        }
        /**
         * 转码
         */
        if (strtoupper ( CHARSET ) == 'GBK') {
            $list = Language::getUTF8 ( $list );
        }
        echo json_encode($list);
    }
    
    /**
     * ajax获取商品分类的属性数据
     */
    public function ajax_goods_specOp() {
    	$gc_id = intval($_GET['gc_id']);
    	
    	$model_goodsclass = Model('goods_class');
    	// 更新常用分类信息
    	$goods_class = $model_goodsclass->getGoodsClassLineForTag($gc_id); 	
    	// 获取类型相关数据
    	$typeinfo = Model('type')->getAttr($goods_class['type_id'], $_SESSION['store_id'], $gc_id);
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
    	$ajax_recursion_spec = $this->recursionSpec(0, $sign_i);//生成处理规格的JS
    	$list['ajax_recursion_spec'] = $ajax_recursion_spec;
    	if (empty($list)) {
    		echo 'null';
    		exit();
    	}
    	/**
    	 * 转码
    	 */
    	if (strtoupper ( CHARSET ) == 'GBK') {
    		$list = Language::getUTF8 ( $list );
    	}
    	echo json_encode($list);
    }
    /**
     * ajax删除常用分类
     */
    public function ajax_stapledelOp() {
        Language::read ( 'member_store_goods_index' );
        $staple_id = intval($_GET ['staple_id']);
        if ($staple_id < 1) {
            echo json_encode ( array (
                    'done' => false,
                    'msg' => Language::get ( 'wrong_argument' )
            ) );
            die ();
        }
        /**
         * 实例化模型
         */
        $model_staple = Model('goods_class_staple');

        $result = $model_staple->delStaple(array('staple_id' => $staple_id, 'member_id' => $_SESSION['member_id']));
        if ($result) {
            echo json_encode ( array (
                    'done' => true
            ) );
            die ();
        } else {
            echo json_encode ( array (
                    'done' => false,
                    'msg' => ''
            ) );
            die ();
        }
    }
    /**
     * ajax选择常用商品分类
     */
    public function ajax_show_commOp() {
        $staple_id = intval($_GET['stapleid']);

        /**
         * 查询相应的商品分类id
         */
        $model_staple = Model('goods_class_staple');
        $staple_info = $model_staple->getStapleInfo(array('staple_id' => intval($staple_id)), 'gc_id_1,gc_id_2,gc_id_3');
        if (empty ( $staple_info ) || ! is_array ( $staple_info )) {
            echo json_encode ( array (
                    'done' => false,
                    'msg' => ''
            ) );
            die ();
        }

        $list_array = array ();
        $list_array['gc_id'] = 0;
        $list_array['type_id'] = $staple_info['type_id'];
        $list_array['done'] = true;
        $list_array['one'] = '';
        $list_array['two'] = '';
        $list_array['three'] = '';

        $gc_id_1 = intval ( $staple_info['gc_id_1'] );
        $gc_id_2 = intval ( $staple_info['gc_id_2'] );
        $gc_id_3 = intval ( $staple_info['gc_id_3'] );

        /**
         * 查询同级分类列表
         */
        $model_goods_class = Model ( 'goods_class' );
        // 1级
        if ($gc_id_1 > 0) {
            $list_array['gc_id'] = $gc_id_1;
            $class_list = $model_goods_class->getGoodsClass($_SESSION['store_id'],0,1,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits']);
            if (empty ( $class_list ) || ! is_array ( $class_list )) {
                echo json_encode ( array (
                        'done' => false,
                        'msg' => ''
                ) );
                die ();
            }
            foreach ( $class_list as $val ) {
                if ($val ['gc_id'] == $gc_id_1) {
                    $list_array ['one'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:1, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                } else {
                    $list_array ['one'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:1, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                }
            }
        }
        // 2级
        if ($gc_id_2 > 0) {
            $list_array['gc_id'] = $gc_id_2;
            $class_list = $model_goods_class->getGoodsClass($_SESSION['store_id'], $gc_id_1, 2,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits']);
            if (empty ( $class_list ) || ! is_array ( $class_list )) {
                echo json_encode ( array (
                        'done' => false,
                        'msg' => ''
                ) );
                die ();
            }
            foreach ( $class_list as $val ) {
                if ($val ['gc_id'] == $gc_id_2) {
                    $list_array ['two'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:2, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                } else {
                    $list_array ['two'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:2, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                }
            }
        }
        // 3级
        if ($gc_id_3 > 0) {
            $list_array['gc_id'] = $gc_id_3;
            $class_list = $model_goods_class->getGoodsClass($_SESSION['store_id'], $gc_id_2, 3,$_SESSION['seller_group_id'],$_SESSION['seller_gc_limits']);
            if (empty ( $class_list ) || ! is_array ( $class_list )) {
                echo json_encode ( array (
                        'done' => false,
                        'msg' => ''
                ) );
                die ();
            }
            foreach ( $class_list as $val ) {
                if ($val ['gc_id'] == $gc_id_3) {
                    $list_array ['three'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:3, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="classDivClick" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                } else {
                    $list_array ['three'] .= '<li class="" onclick="selClass($(this));" data-param="{gcid:' . $val ['gc_id'] . ', deep:3, tid:' . $val ['type_id'] . '}" nctype="selClass"> <a class="" href="javascript:void(0)"><span class="has_leaf"><i class="icon-double-angle-right"></i>' . $val ['gc_name'] . '</span></a> </li>';
                }
            }
        }
        // 转码
        if (strtoupper ( CHARSET ) == 'GBK') {
            $list_array = Language::getUTF8 ( $list_array );
        }
        echo json_encode ( $list_array );
        die ();
    }
    /**
     * AJAX添加商品规格值
     */
    public function ajax_add_specOp() {
        $name = trim($_GET['name']);
        $gc_id = intval($_GET['gc_id']);
        $sp_id = intval($_GET['sp_id']);
        if ($name == '' || $gc_id <= 0 || $sp_id <= 0) {
            echo json_encode(array('done' => false));die();
        }
        $insert = array(
            'sp_value_name' => $name,
            'sp_id' => $sp_id,
            'gc_id' => $gc_id,
            'store_id' => $_SESSION['store_id'],
            'sp_value_color' => null,
            'sp_value_sort' => 0,
        );
        $value_id = Model('spec')->addSpecValue($insert);
        if ($value_id) {
            echo json_encode(array('done' => true, 'value_id' => $value_id));die();
        } else {
            echo json_encode(array('done' => false));die();
        }
    }
    /**
     * AJAX编辑商品规格值
     */
    public function ajax_edit_specOp() {
    	$name = trim($_GET['name']);
    	$gc_id = intval($_GET['gc_id']);
    	$sp_value_id = intval($_GET['sp_value_id']);
    	if ($name == '' || $gc_id <= 0 || $sp_value_id <= 0) {
    		echo json_encode(array('done' => false));die();
    	}
    	$update = array(
    			'sp_value_name' => $name,
    			'gc_id' => $gc_id
    	);
    	$value_id = Model('spec')->editSpecValue($update,array('sp_value_id' => $sp_value_id));
    	if ($value_id) {
    		echo json_encode(array('done' => true, 'value_id' => $value_id));die();
    	} else {
    		echo json_encode(array('done' => false));die();
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
    		$goods_num = Model('goods')->getGoodsCommonCount(array('store_id' => $_SESSION['store_id']));
    		if ($goods_num >= $goodsLimit) {
    			showMessage(L('store_goods_index_goods_limit') . $goodsLimit . L('store_goods_index_goods_limit1'), 'index.php?act=store_goods_online&op=goods_list', 'html', 'error');
    		}
    	}
    }
    
    /**
     *
	 *
	 *  生成需要的js循环。递归调用    PHP
	 *  形式参考 （ 2个规格）
	 *  $('input[type="checkbox"]').click(function(){
	 *  	str = '';
	 *  	for (var i=0; i<spec_group_checked[0].length; i++ ){
	 *      	td_1 = spec_group_checked[0][i];
	 *      	for (var j=0; j<spec_group_checked[1].length; j++){
	 *          	td_2 = spec_group_checked[1][j];
	 *          	str += '<tr><td>'+td_1[0]+'</td><td>'+td_2[0]+'</td><td><input type="text" /></td><td><input type="text" /></td><td><input type="text" /></td>';
	 *          }
	 *     	}
	 *      $('table[class="spec_table"] > tbody').empty().html(str);
	 *  });
     */
	function recursionSpec($len, $sign,$js_spec = '')
    {
    	//if ($len < $sign) {
	    	// 按规格存储规格值数据
    		for ($len = 0; $len < $sign; $len++) {
	        	$js_spec .= "for (var i_" . $len . "=0; i_" . $len . "<spec_group_checked[" . $len . "].length; i_" . $len . "++){td_" . (intval($len) + 1) . " = spec_group_checked[" . $len . "][i_" . $len . "];";
	            //$len++;
    		}
            //$this->recursionSpec($len, $sign,$js_spec);
    	//} else {
    		$js_spec .= "var tmp_spec_td = new Array();";
			for ($i = 0; $i < $len; $i++) {
            	$js_spec .= "tmp_spec_td[" . ($i) . "] = td_" . ($i + 1) . "[1];";
           	}
         	$js_spec .= "tmp_spec_td.sort(function(a,b){return a-b});";
         	$js_spec .= "var spec_bunch = 'i_';";
       		for ($i = 0; $i < $len; $i++) {
              	$js_spec .= "spec_bunch += tmp_spec_td[" . ($i) . "];";
            }
   			$js_spec .= "str += '<input type=\"hidden\" name=\"spec['+spec_bunch+'][goods_id]\" nc_type=\"'+spec_bunch+'|id\" value=\"\" />';";
     		for ($i = 0; $i < $len; $i++) {
       			$js_spec .= "if (td_" . ($i + 1) . "[2] != null) { str += '<input type=\"hidden\" name=\"spec['+spec_bunch+'][color]\" value=\"'+td_" . ($i + 1) . "[1]+'\" />';}";
            	$js_spec .= "str +='<td><input type=\"hidden\" name=\"spec['+spec_bunch+'][sp_value]['+td_" . ($i + 1) . "[1]+']\" value=\"'+td_" . ($i + 1) . "[0]+'\" />'+td_" . ($i + 1) . "[0]+'</td>';";
         	}
         	$js_spec .= "str +='<td><input class=\"text price\" type=\"text\" name=\"spec['+spec_bunch+'][marketprice]\" data_type=\"marketprice\" nc_type=\"'+spec_bunch+'|marketprice\" value=\"\" /><em class=\"add-on\"><i class=\"icon-renminbi\"></i></em></td>' +
         		'<td><input class=\"text price\" type=\"text\" name=\"spec['+spec_bunch+'][price]\" data_type=\"price\" nc_type=\"'+spec_bunch+'|price\" value=\"\" /><em class=\"add-on\"><i class=\"icon-renminbi\"></i></em></td>' +
          		'<td><input class=\"text stock\" type=\"text\" name=\"spec['+spec_bunch+'][stock]\" data_type=\"stock\" nc_type=\"'+spec_bunch+'|stock\" value=\"\" /></td>' +
       			'<td><input class=\"text sku\" type=\"text\" name=\"spec['+spec_bunch+'][sku]\" data_type=\"sku\" nc_type=\"'+spec_bunch+'|sku\" value=\"\" /></td>' +
              	'<td><input class=\"text sku\" type=\"text\" name=\"spec['+spec_bunch+'][barcode]\" data_type=\"barcode\" nc_type=\"'+spec_bunch+'|barcode\" value=\"\" /></td>' +
             	'</tr>';";
      		for ($i = 0; $i < $len; $i++) {
     			$js_spec .= "}";
         	}
		//}
		return $spec_checked.$js_spec;
	}
}
