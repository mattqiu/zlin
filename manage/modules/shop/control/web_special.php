<?php
/**
 * 商城专辑
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

class web_specialControl extends SystemControl{
	private $links = array(
        array('url'=>'act=web_special&op=special_list','text'=>'专辑列表'),
        array('url'=>'act=web_special&op=special_class','text'=>'专辑分类'),
		array('url'=>'act=web_special&op=special_focus','text'=>'焦点区')
    );

    const LINK_WEB_SPECIAL = 'index.php?act=web_special&op=web_special_list';
    //专题状态草稿箱
    const SPECIAL_STATE_DRAFT = 1;
    //专题状态待审核
    const SPECIAL_STATE_PUBLISHED = 2;

	public function __construct(){
		parent::__construct();
		Language::read('cms,web_config');
	}

	public function indexOp() {
        $this->web_special_listOp();
	}

    /**
     * 商城专题列表
     **/
    public function web_special_listOp() {	
		Tpl::output('top_link',$this->sublink($this->links, 'special_list'));
		
		Tpl::setDirquna('shop');
        Tpl::showpage("web_special.list");
    }
	
	/**
     * 输出专辑列表XML数据
     **/
    public function get_special_xmlOp() {		
        $model_special = Model('web_special');
		
        $condition = array();		
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
		
		$page = intval($_POST['rp']);
        if ($page < 1) {
            $page = 15;
        }
		
        $special_list = $model_special->getList($condition, $page, 'special_id desc');
		
		$data = array();
        $data['now_page'] = $model_special->shownowpage();
        $data['total_num'] = $model_special->gettotalnum();
		foreach ($special_list as $v) {
            $param = array();
            $operation = '';
			if ($v['special_state']==2){
				$operation .= '<a class="btn green" href="'.getShopSpecialUrl($v['special_id']).'" target="_blank"><i class="fa fa-list-alt"></i>查看</a>';
			}else{
				$operation .= '<a class="btn green" href="'.urlAdminShop('web_special','web_special_detail',array('special_id'=>$v['special_id'])).'" target="_blank"><i class="fa fa-list-alt"></i>预览</a>';
			}
			$operation .= '<a class="btn orange" href="'.urlAdminShop('web_special','web_special_edit',array('special_id'=>$v['special_id'])).'"><i class="fa fa-pencil-square-o"></i>编辑</a>';
			$operation .= '<a class="btn red" href="javascript:fg_operation_del('.$v['special_id'].');"><i class="fa fa-trash-o"></i>删除</a>';
			$param['operation'] = $operation;
            $param['special_title'] = $v['special_title'];
            $param['special_type'] = $v['special_type']==0?'商品专题':'品牌专题';
			$special_image = $v['special_image'] ? getCMSSpecialImageUrl($v['special_image']) : ADMIN_SKINS_URL . '/images/preview.png';
			$param['special_image'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".$special_image.">\")'><i class='fa fa-picture-o'></i></a>";			
			$param['special_desc'] = $v['special_desc'];
			$param['special_state'] = $v['special_state']==1?'草稿':'已发布';
			
			$data['list'][$v['special_id']] = $param;
		}		
        echo Tpl::flexigridXML($data);exit();
    }	
	
    /**
     * 商城专题添加
     **/
    public function web_special_addOp() {
        $model_special = Model('web_special');
		
		$brand_list = Model('brand')->getBrandPassedList('','brand_id,brand_name');		                              
		Tpl::output('brand_list', $brand_list);
		
		$special_class = Model('web_special_class')->getList();
		Tpl::output('special_class', $special_class);
		
		Tpl::setDirquna('shop');
        Tpl::showpage('web_special.add');
    }

    /**
     * 商城专题编辑
     */
    public function web_special_editOp() {
        $special_id = intval($_GET['special_id']);
        if(empty($special_id)) {
            showMessage(Language::get('param_error'),'','','error');
        }

        $model_special = Model('web_special');
        $special_detail = $model_special->getOne(array('special_id'=>$special_id));
        if(empty($special_detail)) {
            showMessage(Language::get('param_error'),'','','error');
        }
		
		$brand_list = Model('brand')->getBrandPassedList('','brand_id,brand_name');
		Tpl::output('brand_list', $brand_list);
		
		$special_class = Model('web_special_class')->getList();
		Tpl::output('special_class', $special_class);

        Tpl::output('special_detail', $special_detail);
        
		Tpl::setDirquna('shop');
        Tpl::showpage('web_special.add');
    }

    /**
     * 商城专题保存
     **/
    public function web_special_saveOp() {
        $param = array();
        $param['special_title'] = $_POST['special_title'];
        $special_image = $this->web_special_image_upload('special_image');
        if(!empty($special_image)) {
            $param['special_image'] = $special_image; 
            if(!empty($_POST['old_special_image'])) {
                $this->web_special_image_drop($_POST['old_special_image']);
            }
        }
        $special_background = $this->web_special_image_upload('special_background');
        if(!empty($special_background)) {
            $param['special_background'] = $special_background; 
            if(!empty($_POST['old_special_background'])) {
                $this->web_special_image_drop($_POST['old_special_background']);
            }
        }
        if(!empty($_POST['special_image_all'])) {
            $special_image_all = array();
            foreach ($_POST['special_image_all'] as $value) {
                $image = array();
                $image['image_name'] = $value;
                $special_image_all[] = $image;
            }
            $param['special_image_all'] = serialize($special_image_all);
        } else {
            $param['special_image_all'] = '';
        }
		$param['special_class'] = intval($_POST['special_class']);		
		$param['special_type'] = intval($_POST['special_type']);
		$param['special_brand'] = intval($_POST['special_brand']);
		$param['store_id'] = 0;
		$param['special_apply'] = 1;
		$param['special_desc'] = $_POST['special_desc'];
		
        $param['special_margin_top'] = intval($_POST['special_margin_top']);
        $param['special_content'] = $_POST['special_content'];
        $param['special_background_color'] = empty($_POST['special_background_color'])?'#FFFFFF':$_POST['special_background_color'];
        $param['special_repeat'] = empty($_POST['special_repeat'])?'no-repeat':$_POST['special_repeat'];
        $param['special_modify_time'] = time();
        $admin_info = $this->getAdminInfo();
        $param['special_publish_id'] = $admin_info['id'];
        if($_POST['special_state'] == 'publish') {
            $param['special_state'] = 2;
        } else {
            $param['special_state'] = 1;
        }
        $model_special = Model('web_special');
        if(empty($_POST['special_id'])) {
            $result = $model_special->save($param);
        } else {
            $model_special->modify($param, array('special_id'=>$_POST['special_id']));
            $result = $_POST['special_id'];
        }
        if($result) {
            if($_POST['special_state'] == 'publish') {				
                $this->generate_html($result);
            }
            $this->log(Language::get('cms_log_special_save').$result, 1);
            showMessage(Language::get('im_common_save_succ'), self::LINK_WEB_SPECIAL);
        } else {
            $this->log(Language::get('cms_log_special_save').$result, 0);
            showMessage(Language::get('im_common_save_fail'), self::LINK_WEB_SPECIAL);
        }
    }
	
	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 专辑推荐
			 */
			case 'special_recommend':
				$model_special = Model('web_special');
				$update_array = array();
				$where = array();				
				$where['special_id'] = intval($_GET['id']);
				$update_array[$_GET['column']] = trim($_GET['value']);
				$model_special->where($where)->update($update_array);
				//$detail_log = str_replace(array('brand_class','brand_sort','brand_recommend'),array(L('brand_index_class'),L('im_sort'),L('im_recommend')),$_GET['branch']);
				//$this->log(L('im_edit,brand_index_brand').$detail_log.'[ID:'.intval($_GET['id']).']',1);
				echo 'true';exit;
				break;
		}
	}

    /**
     * 专题详细页
     */
    public function web_special_detailOp() {
        $this->get_web_special_detail($_GET['special_id']);
    }

    private function get_web_special_detail($special_id) {
        $model_special = Model('web_special');
        $special_detail = $model_special->getOne(array('special_id'=>$special_id));
        Tpl::output('special_detail', $special_detail);
		
		Tpl::setDirquna('shop');
        Tpl::showpage('web_special.detail', 'null_layout');
    }

    /**
     * 商城生成静态文件
     */
    private function generate_html($special_id) {
        $html_path = BASE_UPLOAD_PATH.DS.ATTACH_CMS.DS.'special_html'.DS;
        if(!is_dir($html_path)){
            if (!@mkdir($html_path, 0755)){
                showMessage(Language::get('cms_special_build_fail'),'','','error');
            }
        }
        ob_start();
        $this->get_web_special_detail($special_id);
        $result = file_put_contents($html_path.md5('special'.$special_id).'.html', ob_get_clean());		
        if(!$result) {			
            showMessage(Language::get('cms_special_build_fail'),'','','error');
        }
    } 

    /**
     * 商城专题删除
     */
    public function web_special_dropOp() {
        $condition = array();
        $condition['special_id'] = array('in', $_REQUEST['special_id']);
        $model_special = Model('web_special');
        $special_list = $model_special->getList($condition);
        if(!empty($special_list)) {
            $html_path = BASE_UPLOAD_PATH.DS.ATTACH_SHOP.DS.'special_html'.DS;
            foreach ($special_list as $value) {
                //删除图片
                $this->web_special_image_drop($value['special_background']);
                $this->web_special_image_drop($value['special_image']);
                $special_image_list = unserialize($value['special_image_all']);
                if(!empty($special_image_list)) {
                    foreach ($special_image_list as $value_image) {
                        $this->web_special_image_drop($value_image['image_name']);
                    }
                }
                //删除静态文件
                $static_file = $html_path.md5('special'.$value['special_id']).'.html';
                if(is_file($static_file)) {
                    unlink($static_file);
                }
            }
        }
        $result = $model_special->drop($condition);
        if($result) {
            $this->log(Language::get('cms_log_special_drop').$_POST['special_id'], 1);
			exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
            //showMessage(Language::get('im_common_del_succ'),'');
        } else {
            $this->log(Language::get('cms_log_special_drop').$_POST['special_id'], 0);
			exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
            //showMessage(Language::get('im_common_del_fail'),'');
        }
    }

    /**
     * 上传图片
     */
    private function web_special_image_upload($image) {
        if(!empty($_FILES[$image]['name'])) {
            $upload	= new UploadFile();
            $upload->set('default_dir',ATTACH_CMS.DS.'special');
            $result = $upload->upfile($image);
            if(!$result) {
                showMessage($upload->error);
            }
            return $upload->file_name;
        }
    }

    /**
     * 图片删除
     */
    private function web_special_image_drop($image) {
        $file = getcmsSpecialImagePath($image);
        if(is_file($file)) {
            unlink($file);
        }
    }

    /**
     * 专题图片上传
     */
    public function special_image_uploadOp() {
        $data = array();
        $data['status'] = 'success';
        if(!empty($_FILES['special_image_upload']['name'])) {
            $upload	= new UploadFile();
            $upload->set('default_dir',ATTACH_CMS.DS.'special');

            $result = $upload->upfile('special_image_upload');
            if(!$result) {
                $data['status'] = 'fail';
                $data['error'] = $upload->error;
            }
            $data['file_name'] = $upload->file_name;
            $data['origin_file_name'] = $_FILES['special_image_upload']['name']; 
            $data['file_url'] = getCMSSpecialImageUrl($upload->file_name);
        }
 		if (strtoupper(CHARSET) == 'GBK'){
			$data = Language::getUTF8($data);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
		}
        echo json_encode($data);
    }

    /**
     * 专题图片删除
     */
    public function special_image_dropOp() {
        $data = array();
        $data['status'] = 'success';
        $this->web_special_image_drop($_GET['image_name']);
        echo json_encode($data);
    }

    /**
     * 图片商品添加
     */
    public function goods_info_by_urlOp() {
        $url = urldecode($_GET['url']);
        if(empty($url)) {
            self::return_json(Language::get('param_error'),'false');
        }
        $model_goods_info = Model('goods_info_by_url');
        $result = $model_goods_info->get_goods_info_by_url($url);
        if($result) {
            self::echo_json($result);
        } else {
            self::return_json(Language::get('param_error'),'false');
        }
    }

    /**
     * 获取专题状态列表
     */
    private function get_special_state_list() {
        $array = array();
        $array[self::SPECIAL_STATE_DRAFT] = Language::get('cms_text_draft');
        $array[self::SPECIAL_STATE_PUBLISHED] = Language::get('cms_text_published');
        return $array;
    }


	private function return_json($message,$result='true') {		
        $data = array();
        $data['result'] = $result;
        $data['message'] = $message;
        self::echo_json($data);
    }

    private function echo_json($data) {
        if (strtoupper(CHARSET) == 'GBK'){
            $data = Language::getUTF8($data);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }
        echo json_encode($data);
    }
	
	/*-------------------------------------专辑分类------------------------------------------*/
	/**
     * 专辑分类列表
     **/
    public function special_classOp() {
		Tpl::output('top_link',$this->sublink($this->links, 'special_class'));       
		
		Tpl::setDirquna('shop');
        Tpl::showpage("web_special_class.list");
    }
	
	/**
     * 输出专辑分类列表XML数据
     **/
    public function get_class_xmlOp() {		
        $model_special = Model('web_special');
		
        $condition = array();		
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
		
		$page = intval($_POST['rp']);
        if ($page < 1) {
            $page = 15;
        }
		$model = Model('web_special_class');
        $list = $model->getList($condition ,$page,'class_sort');
		
		$data = array();
        $data['now_page'] = $model_special->shownowpage();
        $data['total_num'] = $model_special->gettotalnum();
		foreach ($list as $v) {
            $param = array();
            $operation = '';
			$operation .= '<a class="btn orange" href="'.urlAdminShop('web_special','special_class_edit',array('class_id'=>$v['class_id'])).'"><i class="fa fa-pencil-square-o"></i>编辑</a>';
			$operation .= '<a class="btn red" href="javascript:fg_operation_del('.$v['class_id'].');"><i class="fa fa-trash-o"></i>删除</a>';
			$param['operation'] = $operation;
            $param['class_sort'] = $v['class_sort'];
            $param['class_name'] = $v['class_name'];
			
			$data['list'][$v['class_id']] = $param;
		}		
        echo Tpl::flexigridXML($data);exit();
    }	
	
	/**
     * 专辑分类添加
     **/
    public function special_class_addOp() {		
        Tpl::setDirquna('shop');
        Tpl::showpage('web_special_class.add');
    }
	
	/**
     * 专辑分类编辑
     **/
    public function special_class_editOp() {
		$class_id = intval($_GET['class_id']);
        if(empty($class_id)) {
            showMessage('非法参数','','','error');
        }

        $model_class = Model('web_special_class');
        $class_info = $model_class->getOne(array('class_id'=>$class_id));
        if(empty($class_info)) {
            showMessage('非法参数','','','error');
        }
        Tpl::output('class_info', $class_info);		
			
        Tpl::setDirquna('shop');
        Tpl::showpage('web_special_class.add');
    }
	
	/**
     * 专辑分类保存
     **/
    public function web_special_class_saveOp() {
        $obj_validate = new Validate();
        $validate_array = array( 
            array('input'=>$_POST['class_name'],'require'=>'true',"validator"=>"Length","min"=>"1","max"=>"10",'message'=>Language::get('class_name_error')),
            array('input'=>$_POST['class_sort'],'require'=>'true','validator'=>'Range','min'=>0,'max'=>255,'message'=>Language::get('class_sort_error')),
        );
        $obj_validate->validateparam = $validate_array;
        $error = $obj_validate->validate();			
        if ($error != ''){
            showMessage(Language::get('error').$error,'','','error');
        }

        $param = array();
        $param['class_name'] = trim($_POST['class_name']);
        $param['class_sort'] = intval($_POST['class_sort']);
        $model_class = Model('web_special_class');
		if(empty($_POST['class_id'])) {
            $result = $model_class->save($param);
        } else {            
            $result = $model_class->modify($param, array('class_id'=>$_POST['class_id']));
        }        
        if($result) {
            showMessage('操作成功！','index.php?act=web_special&op=special_class');
        } else {
            showMessage('操作失败','index.php?act=web_special&op=special_class','','error');
        }
    }
	
	/**
     * 商城专题分类删除
     */
    public function web_special_class_dropOp() {
		$class_id = intval($_GET['class_id']);
        if(empty($class_id)) {
            showMessage('非法参数','','','error');
        }
		
        $condition = array();
        $condition['class_id'] = $class_id;
        
        $result = Model('web_special_class')->drop($condition);
        if($result) {
			exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
			exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }
		
	/*-------------------------------------商城专辑首页焦点图------------------------------------------*/
	/**
	 * 专辑首页头部切换图设置
	 */
	public function special_focusOp() {
	    $model_web_config = Model('web_config');
	    $web_id = '161';
	    $code_list = $model_web_config->getCodeList(array('web_id'=> $web_id));
	    if(is_array($code_list) && !empty($code_list)) {
			foreach ($code_list as $key => $val) {//将变量输出到页面
				$var_name = $val['var_name'];
				$code_info = $val['code_info'];
				$code_type = $val['code_type'];
				$val['code_info'] = $model_web_config->get_array($code_info,$code_type);
				Tpl::output('code_'.$var_name,$val);
			}
		}
		$screen_adv_list = $model_web_config->getAdvList("screen");//焦点大图广告数据
		Tpl::output('screen_adv_list',$screen_adv_list);
		Tpl::output('top_link',$this->sublink($this->links, 'special_focus'));  
		
		Tpl::setDirquna('shop');
		Tpl::showpage('web_special_focus.edit');
	}
	/**
	 * 更新html内容
	 */
	public function html_updateOp() {
		$model_web_config = Model('web_config');
		$web_id = intval($_GET["web_id"]);
		$web_list = $model_web_config->getWebList(array('web_id'=> $web_id));
		$web_array = $web_list[0];
		if(!empty($web_array) && is_array($web_array)) {
			$model_web_config->updateWebHtml($web_id);
			showMessage(Language::get('im_common_op_succ'));
		} else {
			showMessage(Language::get('im_common_op_fail'));
		}
	}	
	/**
	 * 保存焦点区切换大图
	 */
	public function screen_picOp() {
		$code_id = intval($_POST['code_id']);
		$web_id = intval($_POST['web_id']);
		$model_web_config = Model('web_config');
		$code = $model_web_config->getCodeRow($code_id,$web_id);
		if (!empty($code)) {
			$code_type = $code['code_type'];
			$var_name = $code['var_name'];
			$code_info = $_POST[$var_name];

			$pic_id = intval($_POST['screen_id']);
			if ($pic_id > 0) {
    			$var_name = "screen_pic";
    			$pic_info = $_POST[$var_name];
    			$pic_info['pic_id'] = $pic_id;
    			if (!empty($code_info[$pic_id]['pic_img'])) {//原图片
    			    $pic_info['pic_img'] = $code_info[$pic_id]['pic_img'];
    			}
    			$file_name = 'web-'.$web_id.'-'.$code_id.'-'.$pic_id;
    			$pic_name = $this->_upload_pic($file_name);//上传图片
    			if (!empty($pic_name)) {
    				$pic_info['pic_img'] = $pic_name;
    			}

			    $code_info[$pic_id] = $pic_info;
			    Tpl::output('pic',$pic_info);
			}
			$code_info = $model_web_config->get_str($code_info,$code_type);
			$model_web_config->updateCode(array('code_id'=> $code_id),array('code_info'=> $code_info));

    		Tpl::showpage('web_upload_screen','null_layout');
		}
	}	

	/**
	 * 上传图片
	 */
	private function _upload_pic($file_name) {
	    $pic_name = '';
	    if (!empty($file_name)) {
			if (!empty($_FILES['pic']['name'])) {//上传图片
				$upload = new UploadFile();
				$ext = end(explode('.', $_FILES['pic']['name']));
    			$upload->set('default_dir',ATTACH_EDITOR);
    			$upload->set('file_name',$file_name.".".$ext);
				$result = $upload->upfile('pic');
				if ($result) {
					$pic_name = ATTACH_EDITOR."/".$upload->file_name.'?'.mt_rand(100,999);//加随机数防止浏览器缓存图片
				}
			}
	    }
	    return $pic_name;
	}

    private function show_menu($menu_key) {
        $menu_array = array(
            'special_list'=>array('menu_type'=>'link','menu_name'=>Language::get('im_manage'),'menu_url'=>'index.php?act=web_special&op=web_special_list'),
			'special_focus'=>array('menu_type'=>'link','menu_name'=>'焦点区','menu_url'=>'index.php?act=web_special&op=web_special_focus'),
            'special_add'=>array('menu_type'=>'link','menu_name'=>Language::get('im_new'),'menu_url'=>'index.php?act=web_special&op=web_special_add'),
            'special_edit'=>array('menu_type'=>'link','menu_name'=>Language::get('im_edit'),'menu_url'=>'###'),
        );
        if($menu_key != 'special_edit') {
            unset($menu_array['special_edit']);
        }
        $menu_array[$menu_key]['menu_type'] = 'text';
        Tpl::output('menu',$menu_array);
    }	

}