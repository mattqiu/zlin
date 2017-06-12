<?php
/**
 * 店铺装修
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
class store_decorationControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 店铺装修设置
     */
    public function decoration_settingOp() {
        $model_store_decoration = Model('store_decoration');

        $store_decoration_info = $model_store_decoration->getStoreDecorationInfo(array('store_id' => $_SESSION['store_id'],'decoration_type' => 0));
        if(empty($store_decoration_info)) {
            //创建默认装修
            $param = array();
            $param['decoration_name'] = '默认装修';
            $param['store_id'] = $_SESSION['store_id'];
			$param['decoration_type'] = 0;
            $decoration_id = $model_store_decoration->addStoreDecoration($param);
        } else {
            $decoration_id = $store_decoration_info['decoration_id'];
        }

        Tpl::output('store_decoration_switch', $this->store_info['store_decoration_switch']);
        Tpl::output('store_decoration_only', $this->store_info['store_decoration_only']);
        Tpl::output('decoration_id', $decoration_id);

        $this->profile_menu('decoration_setting');
        Tpl::showpage('store_decoration.setting');
    }
	
	/**
     * 店铺装修设置保存
     */
    public function decoration_setting_saveOp() {
        $model_store_decoration = Model('store_decoration');
        $model_store = Model('store');

        $store_decoration_info = $model_store_decoration->getStoreDecorationInfo(array('store_id' => $_SESSION['store_id'],'decoration_type' => 0));
        if(empty($store_decoration_info)) {
            showDialog('参数错误');
        }

        $update = array();
        if(empty($_POST['store_decoration_switch'])) {
            $update['store_decoration_switch'] = 0;
        } else {
            $update['store_decoration_switch'] = $store_decoration_info['decoration_id'];
        }
        $update['store_decoration_only'] = intval($_POST['store_decoration_only']);
        $result = $model_store->editStore($update, array('store_id' => $_SESSION['store_id']));
        if($result) {
            showDialog(L('im_common_save_succ'), '', 'succ');
        } else {
            showDialog(L('im_common_save_fail'));
        }
    }
	
	/**
     * 展厅装修
     */
    public function decoration_customOp() {
        $model_store_decoration = Model('store_decoration');

        $store_decoration_custom = $model_store_decoration->getStoreDecorationList(array('store_id' => $_SESSION['store_id'],'decoration_type' => array('gt',0)));
        Tpl::output('customdecoration_list', $store_decoration_custom);

        $this->profile_menu('decoration_custom');
        Tpl::showpage('store_decoration.custom');
    }
	
	/**
     * 添加展厅装修
     */
    public function custom_addOp() {
        Tpl::showpage('store_decoration.customadd','null_layout');				
    }
	
	/**
     * 修改展厅装修
     */
    public function custom_editOp() {
		$decoration_id = intval($_GET['id']);

        $model_store_decoration = Model('store_decoration');

        $decoration_info = $model_store_decoration->getStoreDecorationInfo(array('decoration_id'=>$decoration_id), $_SESSION['store_id']);
        if($decoration_info) {
            Tpl::output('decoration_info', $decoration_info);			
        } else {
			showDialog('无效展厅!');
        }   
		Tpl::showpage('store_decoration.customadd','null_layout');		     		
    }
	
	/**
     * 保存展厅装修
     */
    public function custom_add_saveOp() {
		$model_store_decoration = Model('store_decoration');
		
		$decoration_id = $_POST['decoration_id'];
        $param = array();
        $param['decoration_name'] = $_POST['decoration_name'];
        $param['store_id'] = $_SESSION['store_id'];
	    $param['decoration_type'] = 1;
		
		if (!$decoration_id){
            $decoration_id = $model_store_decoration->addStoreDecoration($param);
		}else{
			$decoration_id = $model_store_decoration->editStoreDecoration($param,array('decoration_id'=>$decoration_id));
		}
		if($decoration_id) {
			showDialog(Language::get('im_common_save_succ'),'index.php?act=store_decoration&op=decoration_custom','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		} else {
			showDialog(Language::get('im_common_save_fail'));
		}	
    }
	
	/**
     * 删除展厅装修
     */
    public function custom_delOp() {
		$decoration_id = intval($_GET['id']);

        $model_store_decoration = Model('store_decoration');

        $result = $model_store_decoration->delStoreDecoration($decoration_id);
        if($result) {
            showDialog(Language::get('im_common_del_succ'),'index.php?act=store_decoration&op=decoration_custom','succ');
        } else {
            showDialog('无效展厅!');
        }	
    }

    

    /**
     * 装修图库列表
     */
    public function decoration_albumOp() {
        $model_store_decoration_album = Model('store_decoration_album');

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];

        $image_list = $model_store_decoration_album->getStoreDecorationAlbumList($condition, 24, 'upload_time desc');
        Tpl::output('image_list', $image_list);
        Tpl::output('show_page',$model_store_decoration_album->showpage());

        $this->profile_menu('decoration_album');
        Tpl::showpage('store_decoration.album');
    }

    /**
     * 图片上传
     */
    public function decoration_album_uploadOp() {
        $store_id = $_SESSION ['store_id'];

        $data = array();

        //判断装修相册数量限制，预设100
        if($this->store_info['store_decoration_image_count'] > 100) {
            $data['error'] = '相册已满，请首先删除无用图片';
            echo json_encode($data);die;
        }

        //上传图片
        $upload = new UploadFile();
        $upload->set('default_dir', ATTACH_STORE_DECORATION . DS . $store_id);
        $upload->set('max_size', C('image_max_filesize'));
        $upload->set('fprefix', $store_id);
        $result_file = $upload->upfile('file');
        if($result_file) {
            $image = $upload->file_name;
        } else {
            $error = $upload->error;
            $data['error'] = $error;
            echo json_encode($data);die;
        }

        //图片尺寸
        list($width, $height) = getimagesize(BASE_UPLOAD_PATH . DS . ATTACH_STORE_DECORATION . DS . $store_id . DS . $image);

        //图片原始名称
        $image_origin_name_array = explode('.', $_FILES["file"]["name"]);

        //插入相册表
        $param = array();
        $param['image_name'] = $image;
        $param['image_origin_name'] = $image_origin_name_array['0'];
        $param['image_width'] = $width;
        $param['image_height'] = $height;
        $param['image_size'] = intval($_FILES['file']['size']);
        $param['store_id'] = $store_id;
        $param['upload_time'] = TIMESTAMP;
        $result = Model('store_decoration_album')->addStoreDecorationAlbum($param);

        if($result) {
            //装修相册计数加1
            Model('store')->editStore(
                array('store_decoration_image_count' => array('exp', 'store_decoration_image_count+1')),
                array('store_id' => $_SESSION['store_id'])
            );

            $data['image_name'] = $image;
            $data['image_url'] = getStoreDecorationImageUrl($image, $store_id);
        } else {
            $data['error'] = '上传失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 图片删除
     */
    public function decoration_album_delOp() {
        $image_id = intval($_POST['image_id']);

        $data = array();

        $model_store_decoration_album = Model('store_decoration_album');

        //验证图片权限
        $condition = array();
        $condition['image_id'] = $image_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $result = $model_store_decoration_album->delStoreDecorationAlbum($condition);
        if($result) {
            //装修相册计数减1
            if($this->store_info['store_decoration_image_count'] > 0) {
                Model('store')->editStore(
                    array('store_decoration_image_count' => array('exp', 'store_decoration_image_count-1')),
                    array('store_id' => $_SESSION['store_id'])
                );
            }

            $data['message'] = '删除成功';
        } else {
            $data['error'] = '删除失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 店铺装修
     */
    public function decoration_editOp() {
        $decoration_id = intval($_GET['decoration_id']);

        $model_store_decoration = Model('store_decoration');

        $decoration_info = $model_store_decoration->getStoreDecorationInfoDetail($decoration_id, $_SESSION['store_id']);
        if($decoration_info) {
            $this->_output_decoration_info($decoration_info);
        } else {
            showMessage(L('param_error'), '', 'error');
        }

        //设定模板为完成宽度
        Tpl::output('seller_layout_no_menu', true);
        Tpl::showpage('store_decoration.edit');
    }

    /**
     * 输出装修设置
     */
    private function _output_decoration_info($decoration_info) {
        $model_store_decoration = Model('store_decoration');
        $decoration_background_style = $model_store_decoration->getDecorationBackgroundStyle($decoration_info['decoration_setting']);
        Tpl::output('decoration_background_style', $decoration_background_style);
        Tpl::output('decoration_nav', $decoration_info['decoration_nav']);
        Tpl::output('decoration_banner', $decoration_info['decoration_banner']);
        Tpl::output('decoration_setting', $decoration_info['decoration_setting']);
        Tpl::output('block_list', $decoration_info['block_list']);
    }

    /**
     * 保存店铺装修背景设置
     */
    public function decoration_background_setting_saveOp() {
        $decoration_id = intval($_POST['decoration_id']);

        //验证参数
        if($decoration_id <= 0) {
            $data['error'] = L('param_error');
            echo json_encode($data);die;
        }

        $setting = array();
        $setting['background_color'] = $_POST['background_color'];
        $setting['background_image'] = $_POST['background_image'];
        $setting['background_image_repeat'] = $_POST['background_image_repeat'];
        $setting['background_position_x'] = $_POST['background_position_x'];
        $setting['background_position_y'] = $_POST['background_position_y'];
        $setting['background_attachment'] = $_POST['background_attachment'];

        //背景设置保存验证
        $validate_setting = $this->_validate_background_setting_input($decoration_id, $setting);
        if(isset($validate_setting['error'])) {
            $data['error'] = $validate_setting['error'];
            echo json_encode($data);die;
        }

        $data = array();

        $model_store_decoration = Model('store_decoration');

        $condition = array();
        $condition['decoration_id'] = $decoration_id;
        $condition['store_id'] = $_SESSION['store_id'];

        $update = array();
        $update['decoration_setting'] = serialize($setting);

        $result = $model_store_decoration->editStoreDecoration($update, $condition);
        if($result) {
            $data['decoration_background_style'] = $model_store_decoration->getDecorationBackgroundStyle($validate_setting);
        } else {
            $data['error'] = '保存失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 背景设置保存验证
     */
    private function _validate_background_setting_input($decoration_id, $setting) {
        //验证输入
        if($decoration_id <= 0) {
            return array('error', L('param_error'));
        }
        if(!empty($setting['background_color'])) {
            if(strlen($setting['background_color']) > 7) {
                return array('error', '请输入正确的背景颜色');
            }
        } else {
            $setting['background_color'] = '';
        }
        if(!empty($setting['background_image'])) {
            $setting['background_image_url'] = getStoreDecorationImageUrl($setting['background_image'], $_SESSION['store_id']);
            if($setting['background_image_url'] == '') {
                return array('error', '请选择正确的背景图片');
            }
        } else {
            $setting['background_image'] = '';
        }
        if(!in_array($setting['background_image_repeat'], array('no-repeat', 'repeat', 'repeat-x', 'repeat-y'))) {
            $setting['background_image_repeat'] = '';
        }
        if(strlen($setting['background_position_x']) > 8) {
            $setting['background_position_x'] = '';
        }
        if(strlen($setting['background_position_y']) > 8) {
            $setting['background_position_y'] = '';
        }
        if(strlen($setting['background_attachment']) > 8) {
            $setting['background_attachment'] = '';
        }
        return $setting;
    }

    /**
     * 装修导航保存
     */
    public function decoration_nav_saveOp() {
        $decoration_id = intval($_POST['decoration_id']);
        $nav = array();
        $nav['display'] = $_POST['nav_display'];
        $nav['style'] = $_POST['content'];

        $data = array();

        //验证参数
        if($decoration_id <= 0) {
            $data['error'] = L('param_error');
            echo json_encode($data);die;
        }

        $model_store_decoration = Model('store_decoration');

        $condition = array();
        $condition['decoration_id'] = $decoration_id;
        $condition['store_id'] = $_SESSION['store_id'];

        $update = array();
        $update['decoration_nav'] = serialize($nav);

        $result = $model_store_decoration->editStoreDecoration($update, $condition);
        if($result) {
            $data['message'] = '保存成功';
        } else {
            $data['error'] = '保存失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 装修banner保存
     */
    public function decoration_banner_saveOp() {
        $decoration_id = intval($_POST['decoration_id']);
        $banner = array();
        $banner['display'] = $_POST['banner_display'];
        $banner['image'] = $_POST['content'];

        $data = array();

        //验证参数
        if($decoration_id <= 0) {
            $data['error'] = L('param_error');
            echo json_encode($data);die;
        }

        $model_store_decoration = Model('store_decoration');

        $condition = array();
        $condition['decoration_id'] = $decoration_id;
        $condition['store_id'] = $_SESSION['store_id'];

        $update = array();
        $update['decoration_banner'] = serialize($banner);

        $result = $model_store_decoration->editStoreDecoration($update, $condition);
        if($result) {
            $data['message'] = '保存成功';
            $data['image_url'] = getStoreDecorationImageUrl($banner['image'], $_SESSION['store_id']);
        } else {
            $data['error'] = '保存失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 装修添加块
     */
    public function decoration_block_addOp() {
        $decoration_id = intval($_POST['decoration_id']);
        $block_layout = $_POST['block_layout'];

        $data = array();

        $model_store_decoration = Model('store_decoration');

        //验证装修编号
        $condition = array();
        $condition['decoration_id'] = $decoration_id;
        $decoration_info = $model_store_decoration->getStoreDecorationInfo($condition, $_SESSION['store_id']);
        if(!$decoration_info) {
            $data['error'] = L('param_error');
            echo json_encode($data);
        }

        //验证装修块布局
        $block_layout_array = $model_store_decoration->getStoreDecorationBlockLayoutArray();
        if(!in_array($block_layout, $block_layout_array)) {
            $data['error'] = L('param_error');
            echo json_encode($data);
        }

        $param = array();
        $param['decoration_id'] = $decoration_id;
        $param['store_id'] = $_SESSION['store_id'];
        $param['block_layout'] = $block_layout;
        $block_id = $model_store_decoration->addStoreDecorationBlock($param);

        if($block_id) {
            ob_start();
            Tpl::output('block', array('block_id' => $block_id));
            Tpl::showpage('store_decoration_block', 'null_layout');
            $temp = ob_get_contents();
            ob_end_clean();

            $data['html'] = $temp;
        } else {
            $data['error'] = '添加失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 装修块删除
     */
    public function decoration_block_delOp() {
        $block_id = intval($_POST['block_id']);

        $data = array();

        $model_store_decoration = Model('store_decoration');

        $condition = array();
        $condition['block_id'] = $block_id;
        $condition['store_id'] = $_SESSION['store_id'];

        $result = $model_store_decoration->delStoreDecorationBlock($condition);

        if($result) {
            $data['message'] = '删除成功';
        } else {
            $data['error'] = '删除失败';
        }
        echo json_encode($data);die;

    }

    /**
     * 装修块保存
     */
    public function decoration_block_saveOp() {
        $block_id = intval($_POST['block_id']);
        $module_type = $_POST['module_type'];

        $data = array();

        $model_store_decoration = Model('store_decoration');

        //验证模块类型
        $block_type_array = $model_store_decoration->getStoreDecorationBlockTypeArray();
        if(!in_array($module_type, $block_type_array)) {
            $data['error'] = L('param_error');
            echo json_encode($data);
        }

        switch ($module_type) {
            case 'html':
                $content = htmlspecialchars($_POST['content']);
                break;
            default:
                $content = serialize($_POST['content']);
        }

        $condition = array();
        $condition['block_id'] = $block_id;
        $condition['store_id'] = $_SESSION['store_id'];

        $param = array();
        $param['block_content'] = $content;
        $param['block_full_width'] = intval($_POST['full_width']);
        $param['block_module_type'] = $module_type;
        $result = $model_store_decoration->editStoreDecorationBlock($param, $condition);

        if($result) {
            $data['message'] = '保存成功';
            $data['html'] = $this->_get_block_html($content, $module_type);
        } else {
            $data['error'] = '保存失败';
        }
        echo json_encode($data);die;
    }
	
	/**
     * 装修商品块保存
     */
    public function decoration_block_save_goodsOp() {
        $block_id = intval($_POST['block_id']);
        $module_type = $_POST['module_type'];

        $data = array();

        $model_store_decoration = Model('store_decoration');

        //验证模块类型
        $block_type_array = $model_store_decoration->getStoreDecorationGoodsBlockTypeArray();
        if(!in_array($module_type, $block_type_array)) {			
            $data['error'] = L('param_error');
            echo json_encode($data);
        }  

        $condition = array();
        $condition['block_id'] = $block_id;
        $condition['store_id'] = $_SESSION['store_id'];		
		
		$block_info = $model_store_decoration->getStoreDecorationBlockInfo($condition);
		if (!$block_info || empty($block_info) || !is_array($block_info) || $block_info['block_module_type']!='goods'){
			$block_content = array();
		}else{
			$block_content = unserialize($block_info['block_content']);
		}
		$block_content[$module_type]=$_POST['content'];
		$content = serialize($block_content);
		
        $param = array();
        $param['block_content'] = $content;
        $param['block_full_width'] = intval($_POST['full_width']);
        $param['block_module_type'] = 'goods';
        $result = $model_store_decoration->editStoreDecorationBlock($param, $condition);
 
        if($result) {
            $data['message'] = '保存成功';
			unset($block_info['block_content']);
            $data['html'] = $this->_get_block_html($content, 'goods_dsg' ,$block_info);
        } else {			
            $data['error'] = '保存失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 装修块排序
     */
    public function decoration_block_sortOp() {
        $sort_array = explode(',', rtrim($_POST['sort_string'], ','));

        $model_store_decoration = Model('store_decoration');

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];

        $sort = 1;
        foreach ($sort_array as $value) {
            $condition['block_id'] = $value;
            $model_store_decoration->editStoreDecorationBlock(array('block_sort' => $sort), $condition);
            $sort = $sort + 1;
        }

        $data = array();
        $data['message'] = '保存成功';
        echo json_encode($data);die;
    }

    /**
     * 获取页面
     */
    private function _get_block_html($content, $module_type, $block_info=array()) {
        ob_start();
		Tpl::output('block', $block_info);
        Tpl::output('block_content', $content);
        Tpl::showpage('store_decoration_module.' . $module_type, 'null_layout');
        $temp = ob_get_contents();
        ob_end_clean();
        return $temp;
    }

    /**
     * 商品搜索
     */
    public function goods_searchOp() {
        $model_goods = Model('goods');
		
        $condition = array();        
        $condition['goods_name'] = array('like', '%'.$_GET['keyword'].'%');		
		if (OPEN_STORE_EXTENSION_STATE !=10){
		    $condition['store_id'] = $_SESSION['store_id'];            
		}
		$goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, 'goods_id,goods_name,goods_price, goods_marketprice, goods_promotion_price,goods_image', 'goods_id asc', 10);

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('store_decoration_module.goods_search', 'null_layout');
    }
	
	/**
     * 品牌搜索
     */
    public function brand_searchOp() {
		
        $model_brand = Model('brand');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['brand_name'] = array('like', '%'.$_GET['keyword'].'%');		
        $brand_list = $model_brand->getBrandPassedList($condition, '*', 10);		

        Tpl::output('brand_list', $brand_list);
        Tpl::output('show_page', $model_brand->showpage());		
        Tpl::showpage('store_decoration_module.brand_search', 'null_layout');
    }

    /**
     * 更新商品模块的商品价格
     */
    private function _update_module_goods_info($decoration_id, $store_id) {
        $model_store_decoration = Model('store_decoration');

        $condition = array();
        $condition['decoration_id'] = $decoration_id;
        $condition['block_module_type'] = 'goods';
        $condition['store_id'] = $store_id;
        $block_list_goods = $model_store_decoration->getStoreDecorationBlockList($condition);

        if(!empty($block_list_goods) && is_array($block_list_goods)) {
            foreach ($block_list_goods as $block) {
                $goods_array = unserialize($block['block_content']);
				if(!empty($goods_array['goods']) && is_array($goods_array['goods'])) {
                  foreach ($goods_array['goods'] as $goods_key => $goods_value) {

                    //商品信息
                    $goods_info = Model('goods')->getGoodsOnlineInfoByID($goods_value['goods_id']);
                    $new_goods_price = $goods_info['goods_price'];

                    //抢购
                    if (C('groupbuy_allow')) {
                        $groupbuy_info = Model('groupbuy')->getGroupbuyInfoByGoodsCommonID($goods_info['goods_commonid']);
                        if (!empty($groupbuy_info)) {
                            $new_goods_price = $groupbuy_info['groupbuy_price'];
                        }
                    }

                    //限时折扣
                    if (C('promotion_allow') && empty($groupbuy_info)) {
                        $xianshi_info = Model('p_xianshi_goods')->getXianshiGoodsInfoByGoodsID($goods_value['goods_id']);
                        if (!empty($xianshi_info)) {
                           $new_goods_price = $xianshi_info['xianshi_price'];
                        }
                    }

                    $goods_array['goods'][$goods_key]['goods_price'] = $new_goods_price;
                  }
				}

                //更新块数据
                $update = array();
                $update['block_content'] = serialize($goods_array);
                $model_store_decoration->editStoreDecorationBlock($update, array('block_id' => $block['block_id']));
            }
        }
    }

    /**
     * 装修预览
     */
    public function decoration_previewOp() {
        $decoration_id = intval($_GET['decoration_id']);

        $model_store_decoration = Model('store_decoration');

        $decoration_info = $model_store_decoration->getStoreDecorationInfoDetail($decoration_id, $_SESSION['store_id']);
        if($decoration_info) {
            $this->_output_decoration_info($decoration_info);
        } else {
            showMessage(L('param_error'), '', 'error');
        }
        //店铺信息
		$store_decoration_only = false;
		$show_own_copyright = false;
		$store_theme = 'default';
		
		$model_store = Model('store');
        $store_info = $model_store->getStoreOnlineInfoByID($_SESSION['store_id']);        
		//是否只显示店铺装修部分
        if($store_info['store_decoration_switch'] > 0 & $store_info['store_decoration_only'] == 1) {
            $store_decoration_only = true;
        }
		//是否显示店铺版权信息
		if($store_info['show_own_copyright'] == 1) {
            $show_own_copyright = true;
        }
		//店铺模板
		if(!empty($store_info['store_theme'])) {
            $store_theme = $store_info['store_theme'];			
        }
		
		Tpl::setLayout('store_layout');     
        
		Tpl::output('store_theme', $store_theme);
		Tpl::output('show_own_copyright', $store_theme);
		Tpl::output('store_info', $store_info);

        Tpl::showpage('store_decoration.preview');
    }

    /**
     * 装修静态文件生成
     */
    public function decoration_buildOp() {
        //静态文件路径
        $html_path = BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'decoration'.DS.'html'.DS;
        if(!is_dir($html_path)){
            if (!@mkdir($html_path, 0755)){
                $data = array();
                $data['error'] = '页面生成失败';
                echo json_encode($data);die;
            }
        }

        $decoration_id = intval($_GET['decoration_id']);

        //更新商品数据
        $this->_update_module_goods_info($decoration_id, $_SESSION['store_id']);

        $model_store_decoration = Model('store_decoration');

        $decoration_info = $model_store_decoration->getStoreDecorationInfoDetail($decoration_id, $_SESSION['store_id']);
        if($decoration_info) {
            $this->_output_decoration_info($decoration_info);
        } else {
            showMessage(L('param_error'), '', 'error');
        }

        $file_name = md5($_SESSION['store_id']);
		if ($decoration_info['decoration_type']!=0){
			$file_name = $file_name.'_'.$decoration_id;
		}

        ob_start();
		Tpl::showpage('store_decoration.preview', 'null_layout');
        $result = file_put_contents($html_path . $file_name . '.html', ob_get_clean());
        if($result) {
            $data['message'] = '页面生成成功';
        } else {
            $data['error'] = '页面生成失败';
        }
        echo json_encode($data);die;
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string	$menu_type	导航类型
     * @param string 	$menu_key	当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array(
            1=>array('menu_key'=>'decoration_setting','menu_name'=>'门面装修','menu_url'=>urlShop('store_decoration', 'decoration_setting')),
			2=>array('menu_key'=>'decoration_custom','menu_name'=>'展厅装修','menu_url'=>urlShop('store_decoration', 'decoration_custom')),
            3=>array('menu_key'=>'decoration_album','menu_name'=>'装修图库','menu_url'=>urlShop('store_decoration', 'decoration_album')),
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

}
