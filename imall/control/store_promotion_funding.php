<?php
/**
 * 用户中心-限时折扣 
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class store_promotion_fundingControl extends BaseSellerControl {

    const LINK_FUNDING_LIST = 'index.php?act=store_promotion_funding&op=funding_list';
    const LINK_FUNDING_MANAGE = 'index.php?act=store_promotion_funding&op=funding_manage&funding_id=';

    public function __construct() {
        parent::__construct() ;

        //读取语言包
        Language::read('member_layout,promotion_funding');
        //检查限时折扣是否开启
        if (intval(C('promotion_allow')) !== 1){
            showMessage(Language::get('promotion_unavailable'),'index.php?act=store','','error');
        }

    }

    public function indexOp() {
        $this->funding_listOp();
    }

    /**
     * 发布的限时折扣活动列表
     **/
    public function funding_listOp() {
        $model_funding = Model('p_funding');
        $model_funding_goods = Model('p_funding_goods');
        $model_funding_quota = Model('p_funding_quota');
        
        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $current_funding_quota = $model_funding_quota->getFundingQuotaCurrent($_SESSION['store_id']);
            Tpl::output('current_funding_quota', $current_funding_quota);
        }

        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        if(!empty($_GET['funding_name'])) {
            $condition['funding_name'] = array('like', '%'.$_GET['funding_name'].'%');
        }
        if(!empty($_GET['state'])) {
            $condition['state'] = intval($_GET['state']);
        }
        $funding_list = $model_funding->getFundingList($condition, 10, 'state desc, end_time desc');
        Tpl::output('list', $funding_list);
        $fundingGoods_list = $model_funding_goods->getFundingGoodsExtendList($condition, 10, 'state desc, end_time desc');
        Tpl::output('goods_list', $fundingGoods_list);
        Tpl::output('show_page', $model_funding->showpage());
        Tpl::output('funding_state_array', $model_funding->getFundingStateArray());

        self::profile_menu('funding_list');
        Tpl::showpage('store_promotion_funding.list');
    }

    /**
     * 添加限时折扣活动
     **/
    public function funding_addOp() {
        if (checkPlatformStore()) {
            Tpl::output('isOwnShop', true);
        } else {
            $model_funding_quota = Model('p_funding_quota');
            $current_funding_quota = $model_funding_quota->getFundingQuotaCurrent($_SESSION['store_id']);
            if(empty($current_funding_quota)) {
                showMessage(Language::get('funding_quota_current_error1'),'','','error');
            }
            Tpl::output('current_funding_quota',$current_funding_quota);
        }

        //输出导航
        self::profile_menu('funding_add');
        Tpl::showpage('store_promotion_funding.add');

    }

    /**
     * 保存添加的限时折扣活动
     **/
    public function funding_saveOp() {
    	
    	$goods_id = intval($_POST['funding_goods_id']);
    	if(empty($goods_id)) {
    		showDialog(Language::get('param_error'));
    	}
        //验证输入
        $funding_name = trim($_POST['funding_name']);
        $start_time = strtotime($_POST['start_time']);
        $end_time = strtotime($_POST['end_time']);
        $lower_limit = intval($_POST['lower_limit']);
        if($lower_limit <= 0) {
            $lower_limit = 1;
        }
        if(empty($funding_name)) {
            showDialog(Language::get('funding_name_error'));
        }
        if($start_time >= $end_time) {
            showDialog(Language::get('greater_than_start_time'));
        }

        if (!checkPlatformStore()) {
            //获取当前套餐
            $model_funding_quota = Model('p_funding_quota');
            $current_funding_quota = $model_funding_quota->getFundingQuotaCurrent($_SESSION['store_id']);
            if(empty($current_funding_quota)) {
                showDialog('没有可用限时折扣套餐,请先购买套餐');
            }
            $quota_start_time = intval($current_funding_quota['start_time']);
            $quota_end_time = intval($current_funding_quota['end_time']);
            if($start_time < $quota_start_time) {
                showDialog(sprintf(Language::get('funding_add_start_time_explain'),date('Y-m-d',$current_funding_quota['start_time'])));
            }
            if($end_time > $quota_end_time) {
                showDialog(sprintf(Language::get('funding_add_end_time_explain'),date('Y-m-d',$current_funding_quota['end_time'])));
            }
        }
        loadadv();
        //生成活动
        $model_funding = Model('p_funding');
        $model_f_goods= Model('p_funding_goods');
        $model_goods = Model('goods');
        $param = array();
        $param['funding_name'] = $funding_name;
        $param['funding_price'] = floatval($_POST['funding_price']);
        $param['goods_image'] = $_POST['goods_image'];
        //$param['quota_id'] = $current_funding_quota['quota_id'] ? $current_funding_quota['quota_id'] : 0;
        $param['start_time'] = $start_time;
        $param['end_time'] = $end_time;
        $param['store_id'] = $_SESSION['store_id'];
        $param['store_name'] = $_SESSION['store_name'];
        $param['virtual_quantity'] = intval($_POST['virtual_quantity']);
        $param['lower_limit'] = $lower_limit;
        $result = $model_funding->addFunding($param);
        if($result) {
            $this->recordSellerLog('添加限时折扣活动，活动名称：'.$funding_name.'，活动编号：'.$result);
            // 添加计划任务
            $this->addcron(array('exetime' => $param['end_time'], 'exeid' => $result, 'type' => 7), true);
            showDialog(Language::get('funding_add_success'),self::LINK_FUNDING_MANAGE.$result,'succ','',3);
        }else {
            showDialog(Language::get('funding_add_fail'));
        }
    }

    /**
     * 编辑限时折扣活动
     **/
    public function funding_editOp() {
        $model_funding = Model('p_funding');

        $funding_info = $model_funding->getFundingInfoByID($_GET['funding_id']);
        if(empty($funding_info) || !$funding_info['editable']) {
            showMessage(L('param_error'),'','','error');
        }

        Tpl::output('funding_info', $funding_info);

        //输出导航
        self::profile_menu('funding_edit');
        Tpl::showpage('store_promotion_funding.add');
    }

    /**
     * 编辑保存限时折扣活动
     **/
    public function funding_edit_saveOp() {
        $funding_id = $_POST['funding_id'];

        $model_funding = Model('p_funding');
        $model_funding_goods = Model('p_funding_goods');

        $funding_info = $model_funding->getFundingInfoByID($funding_id, $_SESSION['store_id']);
        if(empty($funding_info) || !$funding_info['editable']) {
            showMessage(L('param_error'),'','','error');
        }

        //验证输入
        $funding_name = trim($_POST['funding_name']);
        $lower_limit = intval($_POST['lower_limit']);
        if($lower_limit <= 0) {
            $lower_limit = 1;
        }
        if(empty($funding_name)) {
            showDialog(Language::get('funding_name_error'));
        }

        //生成活动
        $param = array();
        $param['funding_name'] = $funding_name;
        $param['funding_title'] = $_POST['funding_title'];
        $param['funding_explain'] = $_POST['funding_explain'];
        $param['lower_limit'] = $lower_limit;
        $result = $model_funding->editFunding($param, array('funding_id'=>$funding_id));
        $result1 = $model_funding_goods->editFundingGoods($param, array('funding_id'=>$funding_id));
        if($result && $result) {
            $this->recordSellerLog('编辑限时折扣活动，活动名称：'.$funding_name.'，活动编号：'.$funding_id);
            showDialog(Language::get('im_common_op_succ'),self::LINK_FUNDING_LIST,'succ','',3);
        }else {
            showDialog(Language::get('im_common_op_fail'));
        }
    }

    /**
     * 限时折扣活动删除
     **/
    public function funding_delOp() {
        $funding_id = intval($_POST['funding_id']);

        $model_funding = Model('p_funding');

        $data = array();
        $data['result'] = true;

        $funding_info = $model_funding->getFundingInfoByID($funding_id, $_SESSION['store_id']);
        if(!$funding_info) {
            showDialog(L('param_error'));
        }

        $model_funding = Model('p_funding');
        $result = $model_funding->delFunding(array('funding_id'=>$funding_id));

        if($result) {
            $this->recordSellerLog('删除限时折扣活动，活动名称：'.$funding_info['funding_name'].'活动编号：'.$funding_id);
            showDialog(L('im_common_op_succ'), urlShop('store_promotion_funding', 'funding_list'), 'succ');
        } else {
            showDialog(L('im_common_op_fail'));
        }
    }
    
    public function funding_goods_infoOp() {
    	$goods_commonid = intval($_GET['goods_commonid']);
    
    	$data = array();
    	$data['result'] = true;
    
    	$model_goods = Model('goods');
    
    	$condition = array();
    	$condition['goods_commonid'] = $goods_commonid;
    	$goods_list = $model_goods->getGoodsOnlineList($condition);
    
    	if(empty($goods_list)) {
    		$data['result'] = false;
    		$data['message'] = L('param_error');
    		echo json_encode($data);die;
    	}
    
    	$goods_info = $goods_list[0];
    	$data['goods_id'] = $goods_info['goods_id'];
    	$data['goods_name'] = $goods_info['goods_name'];
    	$data['goods_price'] = $goods_info['goods_price'];
    	$data['goods_image'] = thumb($goods_info, 240);
    	$data['goods_href'] = urlShop('goods', 'index', array('goods_id' => $goods_info['goods_id']));
    
    	if ($goods_info['is_virtual']) {
    		$data['is_virtual'] = 1;
    		$data['virtual_indate'] = $goods_info['virtual_indate'];
    		$data['virtual_indate_str'] = date('Y-m-d H:i', $goods_info['virtual_indate']);
    		$data['virtual_limit'] = $goods_info['virtual_limit'];
    	}
    
    	echo json_encode($data);die;
    }
	
    /**
     * 上传图片
     **/
    public function image_uploadOp() {
    	if(!empty($_POST['old_goods_image'])) {
    		$this->_image_del($_POST['old_goods_image']);
    	}
    	$this->_image_upload('goods_image');
    }
    
    private function _image_upload($file) {
    	$data = array();
    	$data['result'] = true;
    	if(!empty($_FILES[$file]['name'])) {
    		$upload	= new UploadFile();
    		$uploaddir = ATTACH_PATH.DS.'goods'.DS.$_SESSION['store_id'].DS;
    		$upload->set('default_dir', $uploaddir);
    		$upload->set('thumb_width',	'480,296,168');
    		$upload->set('thumb_height', '480,296,168');
    		$upload->set('thumb_ext', '_max,_mid,_small');
    		$upload->set('fprefix', $_SESSION['store_id']);
    		$result = $upload->upfile($file);
    		if($result) {
    			$data['file_name'] = $upload->file_name;
    			$data['origin_file_name'] = $_FILES[$file]['name'];
    			$data['file_url'] = gthumb($upload->file_name, 'mid');
    		} else {
    			$data['result'] = false;
    			$data['message'] = $upload->error;
    		}
    	} else {
    		$data['result'] = false;
    	}
    	echo json_encode($data);die;
    }
    
    /**
     * 图片删除
     */
    private function _image_del($image_name) {
    	list($base_name, $ext) = explode(".", $image_name);
    	$base_name = str_replace('/', '', $base_name);
    	$base_name = str_replace('.', '', $base_name);
    	list($store_id) = explode('_', $base_name);
    	$image_path = BASE_UPLOAD_PATH.DS.ATTACH_GROUPBUY.DS.$store_id.DS;
    	$image = $image_path.$base_name.'.'.$ext;
    	$image_small = $image_path.$base_name.'_small.'.$ext;
    	$image_mid = $image_path.$base_name.'_mid.'.$ext;
    	$image_max = $image_path.$base_name.'_max.'.$ext;
    	@unlink($image);
    	@unlink($image_small);
    	@unlink($image_mid);
    	@unlink($image_max);
    }
    
    
    /**
     * 选择活动商品
     **/
    public function search_goodsOp() {
    	$model_goods = Model('goods');
    	$condition = array();
    	$condition['store_id'] = $_SESSION['store_id'];
    	$condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
    	$goods_list = $model_goods->getGeneralGoodsCommonList($condition, '*', 8);
    
    	Tpl::output('goods_list', $goods_list);
    	Tpl::output('show_page', $model_goods->showpage());
    	Tpl::showpage('store_promotion_funding.search', 'null_layout');
    }
    
    /**
     * 限时折扣活动管理
     **/
    public function funding_manageOp() {
        $model_funding = Model('p_funding');
        $model_funding_goods = Model('p_funding_goods');

        $funding_id = intval($_GET['funding_id']);
        $funding_info = $model_funding->getFundingInfoByID($funding_id, $_SESSION['store_id']);
        if(empty($funding_info)) {
            showDialog(L('param_error'));
        }
        Tpl::output('funding_info',$funding_info);

        //获取限时折扣商品列表
        $condition = array();
        $condition['funding_id'] = $funding_id;
        $funding_goods_list = $model_funding_goods->getFundingGoodsExtendList($condition);
        Tpl::output('funding_goods_list', $funding_goods_list);

        //输出导航
        self::profile_menu('funding_manage');
        Tpl::showpage('store_promotion_funding.manage');
    }


    /**
     * 限时折扣套餐购买
     **/
    public function funding_quota_addOp() {
        //输出导航
        self::profile_menu('funding_quota_add');
        Tpl::showpage('store_promotion_funding_quota.add');
    }

    /**
     * 限时折扣套餐购买保存
     **/
    public function funding_quota_add_saveOp() {

        $funding_quota_quantity = intval($_POST['funding_quota_quantity']);

        if($funding_quota_quantity <= 0 || $funding_quota_quantity > 12) {
            showDialog(Language::get('funding_quota_quantity_error'));
        }

        //获取当前价格
        $current_price = intval(C('promotion_funding_price'));

        //获取该用户已有套餐
        $model_funding_quota = Model('p_funding_quota');
        $current_funding_quota= $model_funding_quota->getFundingQuotaCurrent($_SESSION['store_id']);
        $add_time = 86400 *30 * $funding_quota_quantity;
        if(empty($current_funding_quota)) {
            //生成套餐
            $param = array();
            $param['member_id'] = $_SESSION['member_id'];
            $param['member_name'] = $_SESSION['member_name'];
            $param['store_id'] = $_SESSION['store_id'];
            $param['store_name'] = $_SESSION['store_name'];
            $param['start_time'] = TIMESTAMP;
            $param['end_time'] = TIMESTAMP + $add_time;
            $model_funding_quota->addFundingQuota($param);
        } else {
            $param = array();
            $param['end_time'] = array('exp', 'end_time + ' . $add_time);
            $model_funding_quota->editFundingQuota($param, array('quota_id' => $current_funding_quota['quota_id']));
        }

        //记录店铺费用
        $this->recordStoreCost($current_price * $funding_quota_quantity, '购买限时折扣');

        $this->recordSellerLog('购买'.$funding_quota_quantity.'份限时折扣套餐，单价'.$current_price.$lang['im_yuan']);

        showDialog(Language::get('funding_quota_add_success'),self::LINK_FUNDING_LIST,'succ');
    }

    /**
     * 选择活动商品
     **/
    public function goods_selectOp() {
        $model_goods = Model('goods');
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['goods_name'] = array('like', '%'.$_GET['goods_name'].'%');
        $goods_list = $model_goods->getGoodsListForPromotion($condition, '*', 10, 'funding');

        Tpl::output('goods_list', $goods_list);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::showpage('store_promotion_funding.goods', 'null_layout');
    }

    /**
     * 限时折扣商品添加
     **/
    public function funding_goods_addOp() {
        $goods_id = intval($_POST['goods_id']);
        $funding_id = intval($_POST['funding_id']);
        $funding_price = floatval($_POST['funding_price']);

        $model_goods = Model('goods');
        $model_funding = Model('p_funding');
        $model_funding_goods = Model('p_funding_goods');

        $data = array();
        $data['result'] = true;

        $goods_info = $model_goods->getGoodsInfoByID($goods_id);
        if(empty($goods_info) || $goods_info['store_id'] != $_SESSION['store_id']) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);die;
        }

        $funding_info = $model_funding->getFundingInfoByID($funding_id, $_SESSION['store_id']);
        if(!$funding_info) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);die;
        }

        //检查商品是否已经参加同时段活动
        $condition = array();
        $condition['end_time'] = array('gt', $funding_info['start_time']);
        $condition['goods_id'] = $goods_id;
        $funding_goods = $model_funding_goods->getFundingGoodsExtendList($condition);
        if(!empty($funding_goods)) {
            $data['result'] = false;
            $data['message'] = '该商品已经参加了同时段活动';
            echo json_encode($data);die;
        }

        //添加到活动商品表
        $param = array();
        $param['funding_id'] = $funding_info['funding_id'];
        $param['funding_name'] = $funding_info['funding_name'];
        $param['funding_title'] = $funding_info['funding_title'];
        $param['funding_explain'] = $funding_info['funding_explain'];
        $param['goods_id'] = $goods_info['goods_id'];
        $param['store_id'] = $goods_info['store_id'];
        $param['goods_name'] = $goods_info['goods_name'];
        $param['goods_price'] = $goods_info['goods_price'];
        $param['funding_price'] = $funding_price;
        $param['goods_image'] = $goods_info['goods_image'];
        $param['start_time'] = $funding_info['start_time'];
        $param['end_time'] = $funding_info['end_time'];
        $param['lower_limit'] = $funding_info['lower_limit'];

        $result = array();
        $funding_goods_info = $model_funding_goods->addFundingGoods($param);
        if($funding_goods_info) {
            $result['result'] = true;
            $data['message'] = '添加成功';
            $data['funding_goods'] = $funding_goods_info;
            // 自动发布动态
            // goods_id,store_id,goods_name,goods_image,goods_price,goods_freight,funding_price
            $data_array = array();
            $data_array['goods_id']         = $goods_info['goods_id'];
            $data_array['store_id']         = $_SESSION['store_id'];
            $data_array['goods_name']       = $goods_info['goods_name'];
            $data_array['goods_image']      = $goods_info['goods_image'];
            $data_array['goods_price']      = $goods_info['goods_price'];
            $data_array['goods_freight']    = $goods_info['goods_freight'];
            $data_array['funding_price']    = $funding_price;
            $this->storeAutoShare($data_array, 'funding');
            $this->recordSellerLog('添加限时折扣商品，活动名称：'.$funding_info['funding_name'].'，商品名称：'.$goods_info['goods_name']);

            // 添加任务计划
            $this->addcron(array('type' => 2, 'exeid' => $goods_info['goods_id'], 'exetime' => $param['start_time']));
        } else {
            $data['result'] = false;
            $data['message'] = L('param_error');
        }
        echo json_encode($data);die;
    }

    /**
     * 限时折扣商品价格修改
     **/
    public function funding_goods_price_editOp() {
        $funding_goods_id = intval($_POST['funding_goods_id']);
        $funding_price = floatval($_POST['funding_price']);

        $data = array();
        $data['result'] = true;

        $model_funding_goods = Model('p_funding_goods');

        $funding_goods_info = $model_funding_goods->getFundingGoodsInfoByID($funding_goods_id, $_SESSION['store_id']);
        if(!$funding_goods_info) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);die;
        }

        $update = array();
        $update['funding_price'] = $funding_price;
        $condition = array();
        $condition['funding_goods_id'] = $funding_goods_id;
        $result = $model_funding_goods->editFundingGoods($update, $condition);

        if($result) {
            $funding_goods_info['funding_price'] = $funding_price;
            $funding_goods_info = $model_funding_goods->getFundingGoodsExtendInfo($funding_goods_info);
            $data['funding_price'] = $funding_goods_info['funding_price'];
            $data['funding_discount'] = $funding_goods_info['funding_discount'];

            // 添加对列修改商品促销价格
            QueueClient::push('updateGoodsPromotionPriceByGoodsId', $funding_goods_info['goods_id']);

            $this->recordSellerLog('限时折扣价格修改为：'.$funding_goods_info['funding_price'].'，商品名称：'.$funding_goods_info['goods_name']);
        } else {
            $data['result'] = false;
            $data['message'] = L('im_common_op_succ');
        }
        echo json_encode($data);die;
    }

    /**
     * 限时折扣商品删除
     **/
    public function funding_goods_deleteOp() {
        $model_funding_goods = Model('p_funding_goods');
        $model_funding = Model('p_funding');

        $data = array();
        $data['result'] = true;

        $funding_goods_id = intval($_POST['funding_goods_id']);
        $funding_goods_info = $model_funding_goods->getFundingGoodsInfoByID($funding_goods_id);
        if(!$funding_goods_info) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);die;
        }

        $funding_info = $model_funding->getFundingInfoByID($funding_goods_info['funding_id'], $_SESSION['store_id']);
        if(!$funding_info) {
            $data['result'] = false;
            $data['message'] = L('param_error');
            echo json_encode($data);die;
        }

        if(!$model_funding_goods->delFundingGoods(array('funding_goods_id'=>$funding_goods_id))) {
            $data['result'] = false;
            $data['message'] = L('funding_goods_delete_fail');
            echo json_encode($data);die;
        }

        // 添加对列修改商品促销价格
        QueueClient::push('updateGoodsPromotionPriceByGoodsId', $funding_goods_info['goods_id']);

        $this->recordSellerLog('删除限时折扣商品，活动名称：'.$funding_info['funding_name'].'，商品名称：'.$funding_goods_info['goods_name']);
        echo json_encode($data);die;
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string	$menu_type	导航类型
     * @param string 	$menu_key	当前导航的menu_key
     * @param array 	$array		附加菜单
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array(
            1=>array('menu_key'=>'funding_list','menu_name'=>Language::get('promotion_active_list'),'menu_url'=>'index.php?act=store_promotion_funding&op=funding_list'),
        );
        switch ($menu_key){
        	case 'funding_add':
                $menu_array[] = array('menu_key'=>'funding_add','menu_name'=>Language::get('promotion_join_active'),'menu_url'=>'index.php?act=store_promotion_funding&op=funding_add');
        		break;
        	case 'funding_edit':
                $menu_array[] = array('menu_key'=>'funding_edit','menu_name'=>'编辑活动','menu_url'=>'javascript:;');
        		break;
        	case 'funding_quota_add':
                $menu_array[] = array('menu_key'=>'funding_quota_add','menu_name'=>Language::get('promotion_buy_product'),'menu_url'=>'index.php?act=store_promotion_funding&op=funding_quota_add');
        		break;
        	case 'funding_manage':
                $menu_array[] = array('menu_key'=>'funding_manage','menu_name'=>Language::get('promotion_goods_manage'),'menu_url'=>'index.php?act=store_promotion_funding&op=funding_manage&funding_id='.$_GET['funding_id']);
        		break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}