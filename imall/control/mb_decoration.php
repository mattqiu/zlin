<?php
/**
 * 手机首页装修
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
class mb_decorationControl extends BaseSellerControl{
	public function __construct(){
		parent::__construct();
		Language::read('member_store_index');
	}

    /**
     * 编辑首页
     */
    public function index_editOp() {
        $model_mb_special = Model('mb_special');		
		
		$special_id = '';
		$MbSpecialInfo = $model_mb_special->getMbSpecialList(array('special_type'=>0),$_SESSION['store_id']);        
		if (empty($MbSpecialInfo) || !is_array($MbSpecialInfo)){
			$param = array();
            $param['special_desc'] = $_SESSION['store_name'];
			$param['special_type'] = 0;
            $special_id = $model_mb_special->addMbSpecial($param,$_SESSION['store_id']);
		}else{
		    $special_id = $MbSpecialInfo[0]['special_id'];
		}
		$special_item_list = $model_mb_special->getMbSpecialItemListByID($special_id);
        Tpl::output('list', $special_item_list);
        Tpl::output('page', $model_mb_special->showpage(2));

        Tpl::output('module_list', $model_mb_special->getMbSpecialModuleList());
        Tpl::output('special_id', $special_id);

        self::profile_menu('mb_decoration');
        Tpl::showpage('mb_decoration_item.list');
    }

    /**
     * 专题项目添加
     */
    public function special_item_addOp() {
        $model_mb_special = Model('mb_special');

        $param = array();
        $param['special_id'] = $_POST['special_id'];
        $param['item_type'] = $_POST['item_type'];
        //广告只能添加一个
        if($param['item_type'] == 'adv_list') {
            $result = $model_mb_special->isMbSpecialItemExist($param,$_SESSION['store_id']);
            if($result) {
                echo json_encode(array('error' => '广告条板块只能添加一个'));die;
            }
        }

        $item_info = $model_mb_special->addMbSpecialItem($param,$_SESSION['store_id']);
        if($item_info) {
            echo json_encode($item_info);die;
        } else {
            echo json_encode(array('error' => '添加失败'));die;
        }
    }

    /**
     * 专题项目删除
     */
    public function special_item_delOp() {
        $model_mb_special = Model('mb_special');

        $condition = array();
        $condition['item_id'] = $_POST['item_id'];

        $result = $model_mb_special->delMbSpecialItem($condition, $_POST['special_id']);
        if($result) {
            echo json_encode(array('message' => '删除成功'));die;
        } else {
            echo json_encode(array('error' => '删除失败'));die;
        }
    }

    /**
     * 专题项目编辑
     */
    public function special_item_editOp() {
        $model_mb_special = Model('mb_special');

        $item_info = $model_mb_special->getMbSpecialItemInfoByID($_GET['item_id']);
        Tpl::output('item_info', $item_info);
        
		self::profile_menu('mb_decoration');
        Tpl::showpage('mb_decoration_item.edit');
    }

    /**
     * 专题项目保存
     */
    public function special_item_saveOp() {
        $model_mb_special = Model('mb_special');
        $result = $model_mb_special->editMbSpecialItemByID(array('item_data' => $_POST['item_data']), $_POST['item_id'], $_POST['special_id']); 

        if($result) {
            showDialog(L('im_common_save_succ'), urlShop('mb_decoration', 'index_edit'));
        } else {
            showDialog(L('im_common_save_succ'), '');
        }
    }

    /**
     * 图片上传
     */
    public function special_image_uploadOp() {
        $data = array();
        if(!empty($_FILES['special_image']['name'])) {
            $prefix = 's' . $_POST['special_id'];
            $upload	= new UploadFile();
            $upload->set('default_dir', ATTACH_MOBILE . DS . 'special' . DS . $prefix);
            $upload->set('fprefix', $prefix);
            $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));

            $result = $upload->upfile('special_image');
            if(!$result) {
                $data['error'] = $upload->error;
            }
            $data['image_name'] = $upload->file_name;
            $data['image_url'] = getMbSpecialImageUrl($data['image_name']);
        }
        echo json_encode($data);
    }

    /**
     * 商品列表
     */
    public function goods_listOp() {
        $model_goods = Model('goods');

        $condition = array();
        if(!empty($_GET['keyword'])){
        	$condition['goods_name|goods_serial|goods_barcode|goods_body'] = array('like', '%' . $_GET['keyword'] . '%');
        }else{
        	$condition['goods_id'] = array("gt",0);
        }
		if (!empty($_SESSION['store_id'])){
		    $condition['store_id'] = $_SESSION['store_id'];
		}

        $goods_list = $model_goods->getGoodsListByCommonidDistinct($condition, 'goods_id,goods_name,goods_promotion_price,goods_image', 'goods_id asc', 10);
        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('mb_special_widget.goods', 'null_layout');
    }

    /**
     * 更新项目排序
     */
    public function update_item_sortOp() {
        $item_id_string = $_POST['item_id_string'];
        $special_id = $_POST['special_id'];
        if(!empty($item_id_string)) {
            $model_mb_special = Model('mb_special');
            $item_id_array = explode(',', $item_id_string);
            $index = 0;
            foreach ($item_id_array as $item_id) {
                $result = $model_mb_special->editMbSpecialItemByID(array('item_sort' => $index), $item_id, $special_id);
                $index++;
            }
        }
        $data = array();
        $data['message'] = '操作成功';
        echo json_encode($data);
    }

    /**
     * 更新项目启用状态
     */
    public function update_item_usableOp() {
        $model_mb_special = Model('mb_special');
        $result = $model_mb_special->editMbSpecialItemUsableByID($_POST['usable'], $_POST['item_id'], $_POST['special_id']);
        $data = array();
        if($result) {
            $data['message'] = '操作成功';
        } else {
            $data['error'] = '操作失败';
        }
        echo json_encode($data);
    }

    /**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_key='') {
		Language::read('member_layout');
        $menu_array = array(
            1=>array('menu_key'=>'mb_decoration','menu_name'=>'手机店铺装修','menu_url'=>'index.php?act=index_edit'),
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}