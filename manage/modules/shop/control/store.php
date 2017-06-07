<?php
/**
 * 店铺管理界面
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */

defined('InIMall') or exit('Access Invalid!');

class storeControl extends SystemControl{
    const EXPORT_SIZE = 1000;
    public function __construct(){
        parent::__construct();
        Language::read('store,store_grade');
    }

    public function indexOp() {
        $this->storeOp();
    }

    /**
     * 店铺
     */
    public function storeOp(){
        //店铺等级
        $model_grade = Model('store_grade');
        $grade_list = $model_grade->getGradeList(array());
        Tpl::output('grade_list', $grade_list);
		Tpl::setDirquna('shop');
        Tpl::showpage('store.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_store = Model('store');
        // 设置页码参数名称
        $condition = array();
        $condition['is_own_shop'] = 0;
        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        if ($_GET['member_name'] != '') {
            $condition['member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if ($_GET['seller_name'] != '') {
            $condition['seller_name'] = array('like', '%' . $_GET['seller_name'] . '%');
        }
        if ($_GET['grade_id'] != '') {
            $condition['grade_id'] = $_GET['grade_id'];
        }
        if ($_GET['store_state'] != '') {
            $condition['store_state'] = $_GET['store_state'];
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
		if ($_GET['store_type'] != '') {
		  switch ($_GET['store_type']) {
            case 'close':
                $condition['store_state'] = 0;
                break;
            case 'open':
                $condition['store_state'] = 1;
                break;
            case 'expired':
                $condition['store_end_time'] = array('between', array(1, TIMESTAMP));
                $condition['store_state'] = 1;
                break;
            case 'expire':
                $condition['store_end_time'] = array('between', array(TIMESTAMP, TIMESTAMP + 864000));
                $condition['store_state'] = 1;
                break;
          }
		}
        $order = '';
        $param = array('store_id','store_name','member_name','seller_name','store_time','store_end_time','store_state','grade_id','sc_id');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
                $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        //店铺列表
        $store_list = $model_store->getStoreList($condition, $page, $order);

        //店铺等级
        $model_grade = Model('store_grade');
        $grade_list = $model_grade->getGradeList(array());
        $grade_array = array();
        if (!empty($grade_list)){
            foreach ($grade_list as $v){
                $grade_array[$v['sg_id']] = $v['sg_name'];
            }
        }

        //店铺分类
        $model_store_class = Model('store_class');
        $class_list = $model_store_class->getStoreClassList(array(),'',false);
        $class_array = array();
        if (!empty($class_list)) {
            foreach ($class_list as $v) {
                $class_array[$v['sc_id']] = $v['sc_name'];
            }
        }

        $data = array();
        $data['now_page'] = $model_store->shownowpage();
        $data['total_num'] = $model_store->gettotalnum();
        foreach ($store_list as $value) {
            $param = array();
            $store_state = $this->getStoreState($value);
            $operation = "<a class='btn green' href='index.php?act=store&op=store_joinin_detail&member_id=".$value['member_id']."'><i class='fa fa-list-alt'></i>查看</a><span class='btn'><em><i class='fa fa-cog'></i>" . L('im_set') . " <i class='arrow'></i></em><ul><li><a href='index.php?act=store&op=store_edit&store_id=" . $value['store_id'] . "'>编辑店铺信息</a></li><li><a href='index.php?act=store&op=store_bind_class&store_id=" . $value['store_id'] . "'>修改经营类目</a></li>";
            if (str_cut($store_state, 6) == 'expire'  && cookie('remindRenewal'.$value['store_id']) == null) {
                $operation .= "<li><a class='expire' href=". urlAdminShop('store', 'remind_renewal', array('store_id'=>$value['store_id'])). ">提醒商家续费</a></li>";
            }
			$operation .= "<li><a href=\"javascript:void(0)\" onclick=\"if(confirm('真的要删除吗？')){location.href='".urlAdminShop('store', 'store_del', array('store_id'=>$value['store_id']))."'}\">删除店铺信息</a>";
			
            $operation .= "</ul></span>";
            $param['operation'] = $operation;
            $param['store_id'] = $value['store_id'];
            $store_name = "<a class='" . $store_state . "' href='". urlShop('show_store', 'index', array('store_id' => $value['store_id'])) ."' target='blank'>";
            if ($store_state == 'expired') {
                $store_name .= "<i class='fa fa-clock-o' title='该店铺已过期，可从编辑菜单提醒续费'></i>";
            } else if ($store_state == 'expire') {
                $store_name .= "<i class='fa fa-bell-o' title='该店铺即将到期，可从编辑菜单提醒续费'></i>";
            }
            $store_name .= $value['store_name'] . "<i class='fa fa-external-link ' title='新窗口打开'></i></a>";
            $param['store_name'] = $store_name;
            $param['member_id'] = $value['member_name'];
            $param['seller_name'] = $value['seller_name'];
            $param['store_avatar'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getStoreLogo($value['store_avatar']).">\")'><i class='fa fa-picture-o'></i></a>";
            $param['store_label'] = "<a href='javascript:void(0);' class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getStoreLogo($value['store_label'], 'store_logo').">\")'><i class='fa fa-picture-o'></i></a>";
            $param['grade_id'] = $grade_array[$value['grade_id']];
			
			$param['store_type'] = $value['store_type']==2?'企业':'个人';
			$param['branch_op'] = $value['branch_op']?'充许':'不充许';
			if ($value['branch_op']==1){
				$param['branch_limit'] = $value['branch_limit']==0?'不限':$value['branch_limit'];
			}else{
				$param['branch_limit'] = '';
			}
			$param['extension_op'] = $value['extension_op']?'充许':'不充许';
			if ($value['extension_op']==1){
				$param['promotion_limit'] = $value['promotion_limit']==0?'不限':$value['promotion_limit'];
				$param['saleman_limit'] = $value['saleman_limit']==0?'不限':$value['saleman_limit'];
			}else{
				$param['promotion_limit'] = '';
				$param['saleman_limit'] = '';
			}
			$param['payment_method'] = payment_method_name($value['payment_method']);
			
            $param['store_time'] = date('Y-m-d', $value['store_time']);
            $param['store_end_time'] = $value['store_end_time']?date('Y-m-d', $value['store_end_time']):L('no_limit');
            $param['store_state'] = $value['store_state']?L('open'):L('close');
            $param['sc_id'] = $class_array[$value['sc_id']];
            $param['area_info'] = $value['area_info'];
            $param['store_address'] = $value['store_address'];
            $param['store_qq'] = $value['store_qq'];
            $param['store_ww'] = $value['store_ww'];
            $param['store_phone'] = $value['store_phone'];
            $data['list'][$value['store_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
    /**
     * csv导出
     */
    public function export_csvOp() {
        $model_store = Model('store');
        $condition = array();
        $limit = false;
        if ($_GET['id'] != '') {
            $id_array = explode(',', $_GET['id']);
            $condition['store_id'] = array('in', $id_array);
        }
        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        if ($_GET['member_name'] != '') {
            $condition['member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if ($_GET['seller_name'] != '') {
            $condition['seller_name'] = array('like', '%' . $_GET['seller_name'] . '%');
        }
        if ($_GET['grade_id'] != '') {
            $condition['grade_id'] = $_GET['grade_id'];
        }
        if ($_GET['store_state'] != '') {
            $condition['store_state'] = $_GET['store_state'];
        }
        if ($_REQUEST['query'] != '') {
            $condition[$_REQUEST['qtype']] = array('like', '%' . $_REQUEST['query'] . '%');
        }
        $order = '';
        $param = array('store_id','store_name','member_name','seller_name','store_time','store_end_time','store_state','grade_id','sc_id');
        if (in_array($_REQUEST['sortname'], $param) && in_array($_REQUEST['sortorder'], array('asc', 'desc'))) {
            $order = $_REQUEST['sortname'] . ' ' . $_REQUEST['sortorder'];
        }
        if (!is_numeric($_GET['curpage'])){
            $count = $model_store->getStoreCount($condition);
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $array = array();
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=store&op=index');
				Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');
                exit();
            }
        } else {
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $limit = $limit1 .','. $limit2;
        }

        $store_list = $model_store->getStoreList($condition, null, 'store_id desc', '*', $limit);
        $this->createCsv($store_list);
    }
    /**
     * 生成csv文件
     */
    private function createCsv($store_list) {
        //店铺等级
        $model_grade = Model('store_grade');
        $grade_list = $model_grade->getGradeList(array());
        $grade_array = array();
        if (!empty($grade_list)){
            foreach ($grade_list as $v){
                $grade_array[$v['sg_id']] = $v['sg_name'];
            }
        }

        //店铺分类
        $model_store_class = Model('store_class');
        $class_list = $model_store_class->getStoreClassList(array(),'',false);
        $class_array = array();
        if (!empty($class_list)) {
            foreach ($class_list as $v) {
                $class_array[$v['sc_id']] = $v['sc_name'];
            }
        }

        $data = array();
        foreach ($store_list as $value) {
            $param = array();
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = $value['store_name'];
            $param['member_name'] = $value['member_name'];
            $param['seller_name'] = $value['seller_name'];
            $param['store_avatar'] = getStoreLogo($value['store_avatar']);
            $param['store_label'] = getStoreLogo($value['store_label'], 'store_logo');
            $param['grade_id'] = $grade_array[$value['grade_id']];
            $param['store_time'] = date('Y-m-d', $value['store_time']);
            $param['store_end_time'] = $value['store_end_time']?date('Y-m-d', $value['store_end_time']):L('no_limit');
            $param['store_state'] = $value['store_state']?L('open'):L('close');
            $param['sc_id'] = $class_array[$value['sc_id']];
            $param['area_info'] = $value['area_info'];
            $param['store_address'] = $value['store_address'];
            $param['store_qq'] = $value['store_qq'];
            $param['store_ww'] = $value['store_ww'];
            $param['store_phone'] = $value['store_phone'];
            $data[$value['store_id']] = $param;
        }

        $header = array(
                'store_id' => '店铺ID',
                'store_name' => '店铺名称',
                'member_name' => '店主账号',
                'seller_name' => '商家账号',
                'store_avatar' => '店铺头像',
                'store_label' => '店铺LOGO',
                'grade_id' => '店铺等级',
                'store_time' => '开店时间',
                'store_end_time' => '到期时间',
                'store_state' => '当前状态',
                'sc_id' => '店铺分类',
                'area_info' => '所在地区',
                'store_address' => '详细地址',
                'store_qq' => 'QQ',
                'store_ww' => '旺旺',
                'store_phone' => '商家电话'
        );
        //output('store_list' .$_GET['curpage'] . '-'.date('Y-m-d'), $data, $header);
    }

    /**
     * 获得店铺状态
     *  open\正常
     *  close\关闭
     *  expire\即将到期
     *  expired\过期
     */
    private function getStoreState($store_info) {
        $result = 'open';
        if (intval($store_info['store_state']) === 1) {
            $store_end_time = intval($store_info['store_end_time']);
            if ($store_end_time > 0) {
                if ($store_end_time < TIMESTAMP) {
                    $result = 'expired';
                } elseif (($store_end_time - 864000) < TIMESTAMP) {
                    //距离到期10天
                    $result = 'expire';
                }
            }
        } else {
            $result = 'close';
        }
        return $result;
    }

    /**
     * 店铺编辑
     */
    public function store_editOp(){
        $lang = Language::getLangContent();

        $model_store = Model('store');
        //保存
        if (chksubmit()){
            //取店铺等级的审核
            $model_grade = Model('store_grade');
            $grade_array = $model_grade->getOneGrade(intval($_POST['grade_id']));
            if (empty($grade_array)){
                showMessage($lang['please_input_store_level']);
            }
            //结束时间
            $time   = '';
            if(trim($_POST['end_time']) != ''){
                $time = strtotime($_POST['end_time']);
            }
            $update_array = array();
            $update_array['store_name'] = trim($_POST['store_name']);
            $update_array['sc_id'] = intval($_POST['sc_id']);
            $update_array['grade_id'] = intval($_POST['grade_id']);
            $update_array['store_end_time'] = $time;
            $update_array['store_baozh'] = trim($_POST['store_baozh']);//保障服务开关
			$update_array['store_baozhopen'] = trim($_POST['store_baozhopen']);//保证金显示开关
			$update_array['store_baozhrmb'] = trim($_POST['store_baozhrmb']);//新加保证金-金额
			$update_array['store_qtian'] = trim($_POST['store_qtian']);//保障服务-七天退换
			$update_array['store_zhping'] = trim($_POST['store_zhping']);//保障服务-正品保证
			$update_array['store_erxiaoshi'] = trim($_POST['store_erxiaoshi']);//保障服务-两小时发货
			$update_array['store_tuihuo'] = trim($_POST['store_tuihuo']);//保障服务-退货承诺
			$update_array['store_shiyong'] = trim($_POST['store_shiyong']);//保障服务-试用
			$update_array['store_xiaoxie'] = trim($_POST['store_xiaoxie']);//保障服务-消协
			$update_array['store_huodaofk'] = trim($_POST['store_huodaofk']);//保障服务-货到付款
			$update_array['store_shiti'] = trim($_POST['store_shiti']);//保障服务-实体店铺
			
            $result = $model_store->editStore($update_array, array('store_id' => $_POST['store_id']));
            if ($result){
                $url = array(
                array(
                'url'=>'index.php?act=store&op=store',
                'msg'=>$lang['back_store_list'],
                ),
                array(
                'url'=>'index.php?act=store&op=store_edit&store_id='.intval($_POST['store_id']),
                'msg'=>$lang['countinue_add_store'],
                ),
                );
                $this->log(L('im_edit,store').'['.$_POST['store_name'].']',1);
                showMessage($lang['im_common_save_succ'],$url);
            }else {
                $this->log(L('im_edit,store').'['.$_POST['store_name'].']',1);
                showMessage($lang['im_common_save_fail']);
            }
        }
        //取店铺信息
        $store_array = $model_store->getStoreInfoByID($_GET['store_id']);
        if (empty($store_array)){
            showMessage($lang['store_no_exist']);
        }
        //整理店铺内容
        $store_array['store_end_time'] = $store_array['store_end_time']?date('Y-m-d',$store_array['store_end_time']):'';
        //店铺分类
        $model_store_class = Model('store_class');
        $parent_list = $model_store_class->getStoreClassList(array(),'',false);
        //店铺等级
        $model_grade = Model('store_grade');
        $grade_list = $model_grade->getGradeList();
        Tpl::output('grade_list',$grade_list);
        Tpl::output('class_list',$parent_list);
        Tpl::output('store_array',$store_array);
		
		// 佣金提成
        $commis_list = Model('extension_commis_class')->getCommisList(GENERAL_PLATFORM_EXTENSION_ID);
        Tpl::output('commis_list', $commis_list);

        $joinin_detail = Model('store_joinin')->getOne(array('member_id'=>$store_array['member_id']));
        Tpl::output('joinin_detail', $joinin_detail);
		Tpl::setDirquna('shop');
        Tpl::showpage('store.edit');
    }
	
	/**
     * 编辑保存店铺运营
     */
    public function edit_save_operatingOP() {
        if (chksubmit()) {
            $store_id = $_POST['store_id'];			
            if ($store_id <= 0) {
                showMessage(L('param_error'));
            }
			
			$update_array = array();
			$update_array['payment_method'] = intval($_POST['payment_method']);	
			$update_array['branch_op'] = intval($_POST['branch_op']);
			$update_array['branch_limit'] = intval($_POST['branch_limit']);			
			$update_array['extension_op'] = intval($_POST['extension_op']);
			if (intval($_POST['promotion_level']) !=0){
				$update_array['promotion_level_op'] = 0;
			    $update_array['promotion_level'] = intval($_POST['promotion_level']);
			}else{
				$update_array['promotion_level_op'] = 1;
			}			
			$update_array['promotion_limit'] = intval($_POST['promotion_limit']);
			$update_array['saleman_limit'] = intval($_POST['saleman_limit']);
			$update_array['show_own_copyright'] = intval($_POST['show_own_copyright']);			
			$update_array['store_state'] = intval($_POST['store_state']);
			$update_array['store_points_way'] = intval($_POST['store_points_way']); //店铺模式zhangc
			//是否开启会员等级制度
			$update_array['is_membergrade'] = intval($_POST['is_membergrade']);
			
			if ($update_array['store_state'] == 0){   
				//根据店铺状态修改该店铺所有商品状态
				$model_goods = Model('goods');
				$model_goods->editProducesOffline(array('store_id' => $_POST['store_id']));    
				$update_array['store_close_info'] = trim($_POST['store_close_info']);
				$update_array['store_recommend'] = 0;
			}else {
				//店铺开启后商品不在自动上架，需要手动操作
				$update_array['store_close_info'] = '';
				$update_array['store_recommend'] = intval($_POST['store_recommend']);
			}
			
			$model_store = Model('store');
			$result = $model_store->editStore($update_array, array('store_id' => $_POST['store_id']));			
			
            if ($result) {
                showMessage(L('im_common_op_succ'), 'index.php?act=store&op=store');
            } else {
                showMessage(L('im_common_op_fail'));
            }			
		}
	}

    /**
     * 编辑保存注册信息
     */
    public function edit_save_joininOp() {
        if (chksubmit()) {
            $member_id = $_POST['member_id'];
            if ($member_id <= 0) {
                showMessage(L('param_error'));
            }
            $param = array();
            $param['company_name'] = $_POST['company_name'];
            $param['company_province_id'] = intval($_POST['province_id']);
            $param['company_address'] = $_POST['company_address'];
            $param['company_address_detail'] = $_POST['company_address_detail'];
            $param['company_phone'] = $_POST['company_phone'];
            $param['company_employee_count'] = intval($_POST['company_employee_count']);
            $param['company_registered_capital'] = intval($_POST['company_registered_capital']);
            $param['contacts_name'] = $_POST['contacts_name'];
            $param['contacts_phone'] = $_POST['contacts_phone'];
            $param['contacts_email'] = $_POST['contacts_email'];
            $param['business_licence_number'] = $_POST['business_licence_number'];
            $param['business_licence_address'] = $_POST['business_licence_address'];
            $param['business_licence_start'] = $_POST['business_licence_start'];
            $param['business_licence_end'] = $_POST['business_licence_end'];
            $param['business_sphere'] = $_POST['business_sphere'];
            if ($_FILES['business_licence_number_elc']['name'] != '') {
                $param['business_licence_number_elc'] = $this->upload_image('business_licence_number_elc');
            }
            $param['organization_code'] = $_POST['organization_code'];
            if ($_FILES['organization_code_electronic']['name'] != '') {
                $param['organization_code_electronic'] = $this->upload_image('organization_code_electronic');
            }
            if ($_FILES['general_taxpayer']['name'] != '') {
                $param['general_taxpayer'] = $this->upload_image('general_taxpayer');
            }
            $param['bank_account_name'] = $_POST['bank_account_name'];
            $param['bank_account_number'] = $_POST['bank_account_number'];
            $param['bank_name'] = $_POST['bank_name'];
            $param['bank_code'] = $_POST['bank_code'];
            $param['bank_address'] = $_POST['bank_address'];
            if ($_FILES['bank_licence_electronic']['name'] != '') {
                $param['bank_licence_electronic'] = $this->upload_image('bank_licence_electronic');
            }
            $param['settlement_bank_account_name'] = $_POST['settlement_bank_account_name'];
            $param['settlement_bank_account_number'] = $_POST['settlement_bank_account_number'];
            $param['settlement_bank_name'] = $_POST['settlement_bank_name'];
            $param['settlement_bank_code'] = $_POST['settlement_bank_code'];
            $param['settlement_bank_address'] = $_POST['settlement_bank_address'];
            $param['tax_registration_certificate'] = $_POST['tax_registration_certificate'];
            $param['taxpayer_id'] = $_POST['taxpayer_id'];
            if ($_FILES['tax_registration_certif_elc']['name'] != '') {
                $param['tax_registration_certif_elc'] = $this->upload_image('tax_registration_certif_elc');
            }
            $result = Model('store_joinin')->editStoreJoinin(array('member_id' => $member_id), $param);
            if ($result) {
                showMessage(L('im_common_op_succ'), 'index.php?act=store&op=store');
            } else {
                showMessage(L('im_common_op_fail'));
            }
        }
    }

    private function upload_image($file) {
        $pic_name = '';
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH.DS.'store_joinin'.DS;
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        if (!empty($_FILES[$file]['name'])){
            $result = $upload->upfile($file);
            if ($result){
                $pic_name = $upload->file_name;
                $upload->file_name = '';
            }
        }
        return $pic_name;
    }

    /**
     * 店铺经营类目管理
     */
    public function store_bind_classOp() {
        $store_id = intval($_GET['store_id']);

        $model_store = Model('store');
        $model_store_bind_class = Model('store_bind_class');
        $model_goods_class = Model('goods_class');

        $gc_list = $model_goods_class->getGoodsClassListByParentId(0);
        Tpl::output('gc_list',$gc_list);

        $store_info = $model_store->getStoreInfoByID($store_id);
        if(empty($store_info)) {
            showMessage(L('param_error'),'','','error');
        }
        Tpl::output('store_info', $store_info);

        $store_bind_class_list = $model_store_bind_class->getStoreBindClassList(array('store_id'=>$store_id,'state'=>array('in',array(1,2))), null);
        $goods_class = Model('goods_class')->getGoodsClassIndexedListAll();
        for($i = 0, $j = count($store_bind_class_list); $i < $j; $i++) {
            $store_bind_class_list[$i]['class_1_name'] = $goods_class[$store_bind_class_list[$i]['class_1']]['gc_name'];
            $store_bind_class_list[$i]['class_2_name'] = $goods_class[$store_bind_class_list[$i]['class_2']]['gc_name'];
            $store_bind_class_list[$i]['class_3_name'] = $goods_class[$store_bind_class_list[$i]['class_3']]['gc_name'];
        }
        Tpl::output('store_bind_class_list', $store_bind_class_list);
        Tpl::setDirquna('shop');
        Tpl::showpage('store.bind_class');
    }

    /**
     * 添加经营类目
     */
    public function store_bind_class_addOp() {
        $store_id = intval($_POST['store_id']);
        $commis_rate = intval($_POST['commis_rate']);
        if($commis_rate < 0 || $commis_rate > 100) {
            showMessage(L('param_error'), '');
        }
        list($class_1, $class_2, $class_3) = explode(',', $_POST['goods_class']);

        $model_store_bind_class = Model('store_bind_class');

        $param = array();
        $param['store_id'] = $store_id;
        $param['class_1'] = $class_1;
        $param['state'] = 1;
        if(!empty($class_2)) {
            $param['class_2'] = $class_2;
        }
        if(!empty($class_3)) {
            $param['class_3'] = $class_3;
        }

        // 检查类目是否已经存在
        $store_bind_class_info = $model_store_bind_class->getStoreBindClassInfo($param);
        if(!empty($store_bind_class_info)) {
            showMessage('该类目已经存在','','','error');
        }

        $param['commis_rate'] = $commis_rate;
        $result = $model_store_bind_class->addStoreBindClass($param);

        if($result) {
            $this->log('删除店铺经营类目，类目编号:'.$result.',店铺编号:'.$store_id);
            showMessage(L('im_common_save_succ'), '');
        } else {
            showMessage(L('im_common_save_fail'), '');
        }
    }

    /**
     * 删除经营类目
     */
    public function store_bind_class_delOp() {
        $bid = intval($_POST['bid']);

        $data = array();
        $data['result'] = true;

        $model_store_bind_class = Model('store_bind_class');
        $model_goods = Model('goods');

        $store_bind_class_info = $model_store_bind_class->getStoreBindClassInfo(array('bid' => $bid));
        if(empty($store_bind_class_info)) {
            $data['result'] = false;
            $data['message'] = '经营类目删除失败';
            echo json_encode($data);die;
        }

        // 商品下架
        $condition = array();
        $condition['store_id'] = $store_bind_class_info['store_id'];
        $gc_id = $store_bind_class_info['class_1'].','.$store_bind_class_info['class_2'].','.$store_bind_class_info['class_3'];
        $update = array();
        $update['goods_stateremark'] = '管理员删除经营类目';
        $condition['gc_id'] = array('in', rtrim($gc_id, ','));
        $model_goods->editProducesLockUp($update, $condition);

        $result = $model_store_bind_class->delStoreBindClass(array('bid'=>$bid));

        if(!$result) {
            $data['result'] = false;
            $data['message'] = '经营类目删除失败';
        }
        $this->log('删除店铺经营类目，类目编号:'.$bid.',店铺编号:'.$store_bind_class_info['store_id']);
        echo json_encode($data);die;
    }

    public function store_bind_class_updateOp() {
        $bid = intval($_GET['id']);
        if($bid <= 0) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        }
        $new_commis_rate = intval($_GET['value']);
        if ($new_commis_rate < 0 || $new_commis_rate >= 100) {
            echo json_encode(array('result'=>FALSE,'message'=>Language::get('param_error')));
            die;
        } else {
            $update = array('commis_rate' => $new_commis_rate);
            $condition = array('bid' => $bid);
            $model_store_bind_class = Model('store_bind_class');
            $result = $model_store_bind_class->editStoreBindClass($update, $condition);
            if($result) {
                $this->log('更新店铺经营类目，类目编号:'.$bid);
                echo json_encode(array('result'=>TRUE));
                die;
            } else {
                echo json_encode(array('result'=>FALSE,'message'=>L('im_common_op_fail')));
                die;
            }
        }
    }
	
	/**
	 *店铺删除
	 */
	public function store_delOp(){
		if (!empty($_GET['store_id'])){
			if ($_GET['store_id'] == 1){
				showMessage(L('im_common_save_fail'));
			}
			
			$storeId = intval($_GET['store_id']);
			$condition = array(
                'store_id' => $storeId,
            );
			// 完全删除店铺
            Model('store')->delStoreEntirely($condition);

			$this->log(L('im_delete,limit_store').'[ID:'.intval($_GET['store_id']).']',1);
			showMessage(L('im_common_del_succ'), 'index.php?act=store&op=store');
		}else {
			showMessage(L('im_common_del_fail'), 'index.php?act=store&op=store');
		}
	}


    /**
     * 店铺 待审核列表
     */
    public function store_joininOp(){
        Tpl::setDirquna('shop');
        Tpl::showpage('store_joinin');
    }

    /**
     * 输出XML数据
     */
    public function get_joinin_xmlOp() {
        $model_store_joinin = Model('store_joinin');
        // 设置页码参数名称
        $condition = array();
        $condition['joinin_state'] = array('gt',0);
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('member_id', 'member_name', 'sg_id', 'paying_amount', 'joinin_state', 'joinin_year', 'contacts_name', 'contacts_phone'
                ,'contacts_email', 'company_name', 'company_province_id', 'company_phone', 'company_employee_count', 'company_registered_capital'
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        //店铺列表
        $store_list = $model_store_joinin->getList($condition, $page, $order);

        // 开店状态
        $joinin_state_array = $this->get_store_joinin_state();

        $data = array();
        $data['now_page'] = $model_store_joinin->shownowpage();
        $data['total_num'] = $model_store_joinin->gettotalnum();
        foreach ($store_list as $value) {
            $param = array();
            if(in_array(intval($value['joinin_state']), array(STORE_JOIN_STATE_NEW, STORE_JOIN_STATE_PAY))) {
                $operation = "<a class='btn orange' href=\"index.php?act=store&op=store_joinin_detail&member_id=". $value['member_id'] ."\"><i class=\"fa fa-check-circle\"></i>审核</a>";
            } else {
                $operation = "<a class='btn green' href=\"index.php?act=store&op=store_joinin_detail&member_id=". $value['member_id'] ."\"><i class=\"fa fa-list-alt\"></i>查看</a>";
            }
            $param['operation'] = $operation;
            $param['member_id'] = $value['member_id'];
            $param['member_name'] = $value['member_name'];
            $param['sg_id'] = $value['sg_name'];
			$param['store_type'] = $value['store_type']==2?'企业':'个人';
            $param['paying_amount'] = imPriceFormat($value['paying_amount']);
            $param['joinin_state'] = $joinin_state_array[$value['joinin_state']];
            $param['joinin_year'] = $value['joinin_year'];
            $param['contacts_name'] = $value['contacts_name'];
            $param['contacts_phone'] = $value['contacts_phone'];
            $param['contacts_email'] = $value['contacts_email'];
            $param['company_name'] = $value['company_name'];
            $param['company_province_id'] = $value['company_address'] . ' ' . $value['company_address_detail'];
            $param['company_phone'] = $value['company_phone'];
            $param['company_employee_count'] = $value['company_employee_count'];
            $param['company_registered_capital'] = $value['company_registered_capital'];
            $data['list'][$value['member_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 经营类目申请列表
     */
    public function store_bind_class_applay_listOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('store.bind_class_applay_list');
    }

    /**
     * 输出XML数据
     */
    public function get_bind_class_applay_xmlOp() {
        $model_store_bind_class = Model('store_bind_class');
        // 设置页码参数名称
        $condition = array();

        $condition['state'] = array('in', array('0', '1'));
        if ($_GET['state'] != '') {
            $condition['state'] = $_GET['state'];
        }
        if ($_GET['store_id'] != '') {
            $condition['store_id'] = array('like', '%' . $_GET['store_id'] . '%');
        }

        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = 'state asc bid asc';
        $param = array('bid', 'store_id', 'commis_rate', 'class_1', 'class_2', 'class_3', 'state');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        //店铺列表
        $store_bind_class_list = $model_store_bind_class->getStoreBindClassList($condition, $page, $order);
        $storeid_array = array();
        foreach ($store_bind_class_list as $value) {
            $storeid_array[] = $value['store_id'];
        }
        $store_list = Model('store')->getStoreList(array('store_id'=>array('in', $storeid_array)));
        $store_array = array();
        foreach ($store_list as $value) {
            $store_array[$value['store_id']]['store_name'] = $value['store_name'];
            $store_array[$value['store_id']]['seller_name'] = $value['seller_name'];
        }

        //商品分类
        $goods_class = Model('goods_class')->getGoodsClassIndexedListAll();

        // 申请类目状态
        $apply_state = $this->getClassApplyState();

        $data = array();
        $data['now_page'] = $model_store_bind_class->shownowpage();
        $data['total_num'] = $model_store_bind_class->gettotalnum();
        foreach ($store_bind_class_list as $value) {
            $param = array();
			$operation = "<a class='btn red' href='javascript:void(0);' onclick=\"op_del(".$value['store_id'].",".$value['bid'].")\"><i class='fa fa-trash-o'></i>删除</a>";
            if($value['state'] == '0') {
                $operation .= "<a class='btn orange' href='javascript:void(0);' onclick=\"op_verify(".$value['store_id'].",".$value['bid'].")\"><i class='fa fa-check-circle'></i>审核</a>";
			}			
			
            $param['operation'] = $operation;
            $param['store_id'] = $value['store_id'];
            $param['store_name'] = $store_array[$value['store_id']]['store_name'];
            $param['seller_name'] = $store_array[$value['store_id']]['seller_name'];
            $param['commis_rate'] = $value['commis_rate'] . '%';
            $param['state'] = $apply_state[$value['state']];
            $param['class'] = $goods_class[$value['class_1']]['gc_name'] . '(ID:' . $value['class_1'] . ')';
            if ($value['class_2'] > 0) {
                $param['class'] .= '   > ' . $goods_class[$value['class_2']]['gc_name']. '(ID:' . $value['class_2'] . ')';
            }
            if ($value['class_3'] > 0) {
                $param['class'] .= '   > ' . $goods_class[$value['class_3']]['gc_name']. '(ID:' . $value['class_3'] . ')';
            }
            $data['list'][$value['bid']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    private function getClassApplyState() {
        return array('0' => '审核中', '1' => '已审核', '2' => '自营店');
    }


    /**
     * 审核经营类目申请
     */
    public function store_bind_class_applay_checkOp() {
        $model_store_bind_class = Model('store_bind_class');
        $condition = array();
        $condition['bid'] = intval($_GET['bid']);
        $condition['state'] = 0;
		$store_bind_class = $model_store_bind_class->getStoreBindClassInfo($condition);
		if (empty($store_bind_class)){
			exit(json_encode(array('state'=>false,'msg'=>'非法操作！')));
		}
		if ($store_bind_class['class_1']>0 && $store_bind_class['class_2']>0 && $store_bind_class['class_3']>0){
            $result = $model_store_bind_class->editStoreBindClass(array('state'=>1),$condition);
		}else{
			$store_id = $store_bind_class['store_id'];
			$class_1  = $store_bind_class['class_1'];
			$class_2  = $store_bind_class['class_2'];
			$class_3  = $store_bind_class['class_3'];
			$delete_old = false;
			$class_data = array();
			
			$class_list = Model('goods_class')->getCache();
		    if(empty($class_2)) {
			    $child2_list = $class_list['children'][$class_1];
			    if(!empty($child2_list) && is_array($child2_list)){
					$delete_old = true;
				    foreach ($child2_list as $key2=>$gc2) {
					    $child3_list = $class_list['children'][$gc2];
					    if(!empty($child3_list) && is_array($child3_list)){
						    foreach ($child3_list as $key3=>$gc3) {
							    $temp = array();
						        $temp['store_id']    = $store_id;
				  		        $temp['state']       = 1;
				                $temp['class_1']     = $class_1;
				                $temp['class_2']     = $gc2;
				                $temp['class_3']     = $gc3;
					            $temp['commis_rate'] = $class_list['data'][$gc3]['commis_rate'];
							    $class_data[] = $temp;
					        }						
					    }else{
						    $temp = array();
						    $temp['store_id']    = $store_id;
				            $temp['state']       = 1;
				            $temp['class_1']     = $class_1;
				            $temp['class_2']     = $gc2;
				            //$temp['class_3']     = $class_3;
				            $temp['commis_rate'] = $class_list['data'][$gc2]['commis_rate'];
						    $class_data[] = $temp;						
					    }					
				    }				
			    }else{
					//没有下级分类
					$class_data['state']       = 1;
				    $class_data['commis_rate'] = $class_list['data'][$class_1]['commis_rate'];
			    }	        
	        }else{			
	            if(empty($class_3)) {			    
				    $child3_list = $class_list['children'][$class_2];
				    if(!empty($child3_list) && is_array($child3_list)){
						$delete_old = true;
					    foreach ($child3_list as $key3=>$gc3) {
							$temp = array();
						    $temp['store_id']    = $store_id;
				  		    $temp['state']       = 1;
				            $temp['class_1']     = $class_1;
				            $temp['class_2']     = $class_2;
				            $temp['class_3']     = $gc3;
					        $temp['commis_rate'] = $class_list['data'][$gc3]['commis_rate'];
							$class_data[] = $temp;	
					    }						
				    }else{
					    //没有下级分类
						$class_data['state']       = 1;
				        $class_data['commis_rate'] = $class_list['data'][$class_2]['commis_rate'];
			        }
			    }else{
				    //三级分类都存在
					$class_data['state']       = 1;
				    $class_data['commis_rate'] = $class_list['data'][$class_3]['commis_rate'];
			    }
	        }
			if ($delete_old == true){				
				$result = $model_store_bind_class->addStoreBindClassAll($class_data);
				if ($result) {
					$model_store_bind_class->delStoreBindClass($condition);
				}
			}else{				
				$result = $model_store_bind_class->editStoreBindClass($class_data,$condition);
			}
		}
        if ($result) {
            $this->log('审核新经营类目申请，店铺ID：'.$_GET['store_id'],1);
			exit(json_encode(array('state'=>true,'msg'=>'审核成功！')));
        } else {
			exit(json_encode(array('state'=>false,'msg'=>'审核失败！')));
        }
    }

    /**
     * 删除经营类目申请
     */
    public function store_bind_class_applay_delOp() {
        $model_store_bind_class = Model('store_bind_class');
        $condition = array();
        $condition['bid'] = intval($_GET['bid']);
        $del = $model_store_bind_class->delStoreBindClass($condition);
        if ($del) {
            $this->log('删除经营类目，店铺ID：'.$_GET['store_id'],1);
			exit(json_encode(array('state'=>true,'msg'=>'删除成功！')));
        } else {
			exit(json_encode(array('state'=>false,'msg'=>'删除失败！')));
        }
    }

    private function get_store_joinin_state() {
        $joinin_state_array = array(
            STORE_JOIN_STATE_NEW => '新申请',
            STORE_JOIN_STATE_PAY => '已付款',
            STORE_JOIN_STATE_VERIFY_SUCCESS => '待付款',
            STORE_JOIN_STATE_VERIFY_FAIL => '审核失败',
            STORE_JOIN_STATE_PAY_FAIL => '付款审核失败',
            STORE_JOIN_STATE_FINAL => '开店成功',
        );
        return $joinin_state_array;
    }

    /**
     * 店铺续签申请列表
     */
    public function reopen_listOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('store_reopen.list');
    }

    /**
     * 输出XML数据
     */
    public function get_reopen_xmlOp() {
        $model_store_reopen = Model('store_reopen');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('re_id', 're_grade_id', 're_grade_price', 're_year', 're_pay_amount', 're_store_id', 're_store_name', 're_state'
                , 're_create_time', 're_start_time', 're_end_time', 're_pay_cert', 're_pay_cert_explain');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        //店铺列表
        $reopen_list = $model_store_reopen->getStoreReopenList($condition, $page, $order);

        // 续签状态
        $reopen_state_array = $this->getReopenState();

        $data = array();
        $data['now_page'] = $model_store_reopen->shownowpage();
        $data['total_num'] = $model_store_reopen->gettotalnum();
        foreach ($reopen_list as $value) {
            $param = array();
            $operation = '';
            if($value['re_state'] == 1) {
                $operation .= "<a class='btn orange' href=\"javascript:void(0);\" onclick=\"reopen_check('" . $value['re_id'] . "')\"><i class=\"fa fa-check-circle-o\"></i>审核</a>";
            }
            if ($value['re_state'] != 2) {
                $operation .= "<a class='btn green' href=\"javascript:void(0);\" onclick=\"reopen_del('" . $value['re_id'] . "', '" . $value['re_store_id'] . "')\"><i class=\"fa fa-list-alt\"></i>删除</a>";
            }
            if ($value['re_state'] == 2) {
                $operation .= "<span>--</span>";
            }
            $param['operation'] = $operation;
            $param['re_id'] = $value['re_id'];
            $param['re_grade_id'] = $value['re_grade_name'];
            $param['re_grade_price'] = imPriceFormat($value['re_grade_price']);
            $param['re_year'] = $value['re_year'];
            $param['re_pay_amount'] = imPriceFormat($value['re_pay_amount']);
            $param['re_store_id'] = $value['re_store_id'];
            $param['re_store_name'] = $value['re_store_name'];
            $param['re_state'] = $reopen_state_array[$value['re_state']];
            $param['re_create_time'] = date('Y-m-d', $value['re_create_time']);
            $param['re_pay_cert'] = "<a href='".getStoreJoininImageUrl($value['re_pay_cert'])."' target=\"blank\" class='pic-thumb-tip' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getStoreJoininImageUrl($value['re_pay_cert']).">\")'><i class='fa fa-picture-o'></i></a>";
            $param['re_pay_cert_explain'] = $value['re_pay_cert_explain'];
            $param['re_start_time'] = $value['re_start_time'] != '' ? date('Y-m-d', $value['re_start_time']) : '';
            $param['re_end_time'] = $value['re_end_time'] != '' ? date('Y-m-d', $value['re_end_time']) : '';
            $data['list'][$value['re_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    private function getReopenState() {
        return array('0' => '待付款', '1' => '待审核', '2' => '通过审核');
    }

    /**
     * 审核店铺续签申请
     */
    public function reopen_checkOp() {
        $id = intval($_GET['id']);
        if ($id > 0) {
            $model_store_reopen = Model('store_reopen');
            $condition = array();
            $condition['re_id'] = $id;
            $condition['re_state'] = 1;
            //取当前申请信息
            $reopen_info = $model_store_reopen->getStoreReopenInfo($condition);

            //取目前店铺有效截止日期
            $store_info = Model('store')->getStoreInfoByID($reopen_info['re_store_id']);
            $data = array();
            $data['re_start_time'] = strtotime(date('Y-m-d 0:0:0',$store_info['store_end_time']))+24*3600;
            $data['re_end_time'] = strtotime(date('Y-m-d 23:59:59', $data['re_start_time'])." +".intval($reopen_info['re_year'])." year");
            $data['re_state'] = 2;
            $update = $model_store_reopen->editStoreReopen($data,$condition);
            if ($update) {
                //更新店铺有效期
                Model('store')->editStore(array('store_end_time'=>$data['re_end_time']),array('store_id'=>$reopen_info['re_store_id']));
                $msg = '审核通过店铺续签申请，店铺ID：'.$reopen_info['re_store_id'].'，续签时间段：'.date('Y-m-d',$data['re_start_time']).' - '.date('Y-m-d',$data['re_end_time']);
                $this->log($msg,1);
                exit(json_encode(array('state'=>true,'msg'=>'审核成功')));
            } else {
                exit(json_encode(array('state'=>false,'msg'=>'审核失败')));
            }
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'审核失败')));
        }
    }

    /**
     * 删除店铺续签申请
     */
    public function reopen_delOp() {
        $id = intval($_GET['id']);
        if ($id > 0) {
            $model_store_reopen = Model('store_reopen');
            $condition = array();
            $condition['re_id'] = $id;
            $condition['re_state'] = array('in',array(0,1));

            //取当前申请信息
            $reopen_info = $model_store_reopen->getStoreReopenInfo($condition);
            $cert_file = BASE_UPLOAD_PATH.DS.ATTACH_STORE_JOININ.DS.$reopen_info['re_pay_cert'];
            $del = $model_store_reopen->delStoreReopen($condition);
            if ($del) {
                if (is_file($cert_file)) {
                    @unlink($cert_file);
                }
                $this->log('删除店铺续签目申请，店铺ID：'.$_GET['store_id'],1);
                exit(json_encode(array('state'=>true,'msg'=>'审核成功')));
            } else {
                exit(json_encode(array('state'=>false,'msg'=>'审核失败')));
            }
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 审核详细页
     */
    public function store_joinin_detailOp(){
        $model_store_joinin = Model('store_joinin');
        $joinin_detail = $model_store_joinin->getOne(array('member_id'=>$_GET['member_id']));
        $joinin_detail_title = '查看';
        if(in_array(intval($joinin_detail['joinin_state']), array(STORE_JOIN_STATE_NEW, STORE_JOIN_STATE_PAY))) {
            $joinin_detail_title = '审核';
        }
        if (!empty($joinin_detail['sg_info'])) {
            $store_grade_info = Model('store_grade')->getOneGrade($joinin_detail['sg_id']);
            $joinin_detail['sg_price'] = $store_grade_info['sg_price'];
        } else {
            $joinin_detail['sg_info'] = @unserialize($joinin_detail['sg_info']);
            if (is_array($joinin_detail['sg_info'])) {
                $joinin_detail['sg_price'] = $joinin_detail['sg_info']['sg_price'];
            }
        }
        Tpl::output('joinin_detail_title', $joinin_detail_title);
        Tpl::output('joinin_detail', $joinin_detail);
		Tpl::setDirquna('shop');
        Tpl::showpage('store_joinin.detail');
    }

    /**
     * 审核
     */
    public function store_joinin_verifyOp() {
        $model_store_joinin = Model('store_joinin');
        $joinin_detail = $model_store_joinin->getOne(array('member_id'=>$_POST['member_id']));

        switch (intval($joinin_detail['joinin_state'])) {
            case STORE_JOIN_STATE_NEW:
                $this->store_joinin_verify_pass($joinin_detail);
                break;
            case STORE_JOIN_STATE_PAY:
                $this->store_joinin_verify_open($joinin_detail);
                break;
            default:
                showMessage('参数错误','');
                break;
        }
    }

    private function store_joinin_verify_pass($joinin_detail) {
        $param = array();
        $param['joinin_state'] = $_POST['verify_type'] === 'pass' ? STORE_JOIN_STATE_VERIFY_SUCCESS : STORE_JOIN_STATE_VERIFY_FAIL;
        $param['joinin_message'] = $_POST['joinin_message'];
        $param['paying_amount'] = abs(floatval($_POST['paying_amount']));
        $param['store_class_commis_rates'] = implode(',', $_POST['commis_rate']);
        $model_store_joinin = Model('store_joinin');
        $model_store_joinin->modify($param, array('member_id'=>$_POST['member_id']));
        if ($param['paying_amount'] > 0) {
            showMessage('店铺入驻申请审核完成','index.php?act=store&op=store_joinin');
        } else {
            //如果开店支付费用为零，则审核通过后直接开通，无需再上传付款凭证
            $this->store_joinin_verify_open($joinin_detail);
        }
    }

    private function store_joinin_verify_open($joinin_detail) {
        $model_store_joinin = Model('store_joinin');
        $model_store    = Model('store');
        $model_seller = Model('seller');

        //验证商家用户名是否已经存在
        if($model_seller->isSellerExist(array('seller_name' => $joinin_detail['seller_name']))) {
            showMessage('商家用户名已存在','');
        }

        $param = array();
        $param['joinin_state'] = $_POST['verify_type'] === 'pass' ? STORE_JOIN_STATE_FINAL : STORE_JOIN_STATE_PAY_FAIL;
        $param['joinin_message'] = $_POST['joinin_message'];
        $model_store_joinin->modify($param, array('member_id'=>$_POST['member_id']));
        if($_POST['verify_type'] === 'pass') {
			$branch_info=Model('store_grade')->getOneGrade($joinin_detail['sg_id']);
			if(!empty($branch_info) && is_array($branch_info)){
				$branch_op = $branch_info['branch_op'];
				$branch_limit = $branch_info['branch_limit'];				
				$extension_op = $branch_info['extension_op'];
				$promotion_limit = $branch_info['promotion_limit'];
				$saleman_limit = $branch_info['saleman_limit'];
			}else{
				$branch_op=0;
				$branch_limit = 0;				
				$extension_op = 0;
				$promotion_limit = 0;
				$saleman_limit =0;
			}
            //开店
            $shop_array     = array();
            $shop_array['member_id']    = $joinin_detail['member_id'];
            $shop_array['member_name']  = $joinin_detail['member_name'];
            $shop_array['seller_name'] = $joinin_detail['seller_name'];
            $shop_array['grade_id']     = $joinin_detail['sg_id'];
            $shop_array['store_name']   = $joinin_detail['store_name'];
            $shop_array['sc_id']        = $joinin_detail['sc_id'];
            $shop_array['store_company_name'] = $joinin_detail['company_name'];
            $shop_array['province_id']  = $joinin_detail['company_province_id'];
            $shop_array['area_info']    = $joinin_detail['company_address'];
            $shop_array['store_address']= $joinin_detail['company_address_detail'];
            $shop_array['store_zip']    = '';
            $shop_array['store_zy']     = '';
            $shop_array['store_state']  = 1;
            $shop_array['store_time']   = time();
            $shop_array['store_end_time'] = strtotime(date('Y-m-d 23:59:59', strtotime('+1 day'))." +".intval($joinin_detail['joinin_year'])." year");
			$shop_array['is_own_shop'] = 0;
			//我添加的			
			$shop_array['parent_id']	= '';
			$shop_array['branch_op']	= $branch_op;			
			$shop_array['branch_limit']	= $branch_limit;
			$shop_array['extension_op']	= $extension_op;
			$shop_array['promotion_limit']	= $promotion_limit;
			$shop_array['saleman_limit']	= $saleman_limit;
			
			$shop_array['payment_method']	= intval(C('payment_method'))?intval(C('payment_method')):0;
			//$shop_array['sc_bail']	= $joinin_detail['sc_bail'];
			$shop_array['store_type']	= $joinin_detail['store_type'];	
			
            $store_id = $model_store->addStore($shop_array);

            if($store_id) {
                //写入商家账号
                $seller_array = array();
                $seller_array['seller_name'] = $joinin_detail['seller_name'];
                $seller_array['member_id'] = $joinin_detail['member_id'];
                $seller_array['seller_group_id'] = 0;
                $seller_array['store_id'] = $store_id;
                $seller_array['is_admin'] = 1;
                $state = $model_seller->addSeller($seller_array);
            }

            if($state) {
                // 添加相册默认
                $album_model = Model('album');
                $album_arr = array();
                $album_arr['aclass_name'] = Language::get('store_save_defaultalbumclass_name');
                $album_arr['store_id'] = $store_id;
                $album_arr['aclass_des'] = '';
                $album_arr['aclass_sort'] = '255';
                $album_arr['aclass_cover'] = '';
                $album_arr['upload_time'] = time();
                $album_arr['is_default'] = '1';
                $album_model->addClass($album_arr);

                $model = Model();
                //插入店铺扩展表
                $model->table('store_extend')->insert(array('store_id'=>$store_id));
                $msg = Language::get('store_save_create_success');

                //插入店铺绑定分类表
				$class_data = array();
		        $class_list = Model('goods_class')->getCache();
				$store_bind_class = unserialize($joinin_detail['store_class_ids']);
                $store_bind_commis_rates = explode(',', $joinin_detail['store_class_commis_rates']);
                for($i=0, $length=count($store_bind_class); $i<$length; $i++) {
                    list($class_1, $class_2, $class_3) = explode(',', $store_bind_class[$i]);						
		            if(empty($class_2)) {
			            $child2_list = $class_list['children'][$class_1];
			            if(!empty($child2_list) && is_array($child2_list)){
				            foreach ($child2_list as $key2=>$gc2) {
					            $child3_list = $class_list['children'][$gc2];
					            if(!empty($child3_list) && is_array($child3_list)){
						            foreach ($child3_list as $key3=>$gc3) {
							            $temp = array();
						                $temp['store_id']    = $store_id;
				  		                $temp['state']       = 1;
				                        $temp['class_1']     = $class_1;
				                        $temp['class_2']     = $gc2;
				                        $temp['class_3']     = $gc3;
					                    $temp['commis_rate'] = $store_bind_commis_rates[$i];
							            $class_data[] = $temp;
						            }						
					            }else{
						            $temp = array();
						            $temp['store_id']    = $store_id;
				                    $temp['state']       = 1;
				                    $temp['class_1']     = $class_1;
				                    $temp['class_2']     = $gc2;
				                    //$temp['class_3']     = $class_3;
				                    $temp['commis_rate'] = $store_bind_commis_rates[$i];
						            $class_data[] = $temp;						
					            }					
					        }
				
			            }else{
							$temp = array();
						    $temp['store_id']    = $store_id;
				            $temp['state']       = 1;
				            $temp['class_1']     = $class_1;
				            //$temp['class_2']   = $class_2;
				            //$temp['class_3']   = $class_3;
				            $temp['commis_rate'] = $store_bind_commis_rates[$i];
						    $class_data[] = $temp;	
			            }
	        
		            }else{			
	                    if(empty($class_3)) {			    
				            $child3_list = $class_list['children'][$class_2];
				            if(!empty($child3_list) && is_array($child3_list)){
					            foreach ($child3_list as $key3=>$gc3) {
									$temp = array();
						            $temp['store_id']    = $store_id;
				                    $temp['state']       = 1;
				                    $temp['class_1']     = $class_1;
				                    $temp['class_2']     = $class_2;
				                    $temp['class_3']     = $gc3;
				                    $temp['commis_rate'] = $store_bind_commis_rates[$i];
						            $class_data[] = $temp;
					            }						
				            }else{
								$temp = array();
						        $temp['store_id']    = $store_id;
				                $temp['state']       = 1;
				                $temp['class_1']     = $class_1;
				                $temp['class_2']     = $class_2;
				                //$temp['class_3']   = $class_3;
				                $temp['commis_rate'] = $store_bind_commis_rates[$i];
						        $class_data[] = $temp;	
			                }
			            }else{
							$temp = array();
						    $temp['store_id']    = $store_id;
				            $temp['state']       = 1;
				            $temp['class_1']     = $class_1;
				            $temp['class_2']     = $class_2;
				            $temp['class_3']     = $class_3;
				            $temp['commis_rate'] = $store_bind_commis_rates[$i];
						    $class_data[] = $temp;
			            }
	                }
			    }
	            $result = Model('store_bind_class')->addStoreBindClassAll($class_data);	
				
                //插入店铺绑定分类表
                //$store_bind_class_array = array();
                //$store_bind_class = unserialize($joinin_detail['store_class_ids']);
                //$store_bind_commis_rates = explode(',', $joinin_detail['store_class_commis_rates']);
                //for($i=0, $length=count($store_bind_class); $i<$length; $i++) {
                //    list($class1, $class2, $class3) = explode(',', $store_bind_class[$i]);
                //    $store_bind_class_array[] = array(
                //        'store_id' => $store_id,
                //        'commis_rate' => $store_bind_commis_rates[$i],
                //        'class_1' => $class1,
                //        'class_2' => $class2,
                //        'class_3' => $class3,
                //        'state' => 1
                //    );
                //}
                //$model_store_bind_class = Model('store_bind_class');
                //$model_store_bind_class->addStoreBindClassAll($store_bind_class_array);
				
				Model('store_joinin')->modify(array('store_id'=>$store_id), array('member_id'=>$joinin_detail['member_id']));
                showMessage('店铺开店成功','index.php?act=store&op=store_joinin');
            } else {
                showMessage('店铺开店失败','index.php?act=store&op=store_joinin');
            }
        } else {
            showMessage('店铺开店拒绝','index.php?act=store&op=store_joinin');
        }
    }

    /**
     * 提醒续费
     */
    public function remind_renewalOp() {
        $store_id = intval($_GET['store_id']);
        $store_info = Model('store')->getStoreInfoByID($store_id);
        if (!empty($store_info) && $store_info['store_end_time'] < (TIMESTAMP + 864000) && cookie('remindRenewal'.$store_id) == null) {
            // 发送商家消息
            $param = array();
            $param['code'] = 'store_expire';
            $param['store_id'] = intval($_GET['store_id']);
            $param['param'] = array();
            QueueClient::push('sendStoreMsg', $param);

            setIMCookie('remindRenewal'.$store_id, 1, 86400 * 10);  // 十天
            showMessage('消息发送成功');
        }
            showMessage('消息发送失败');
    }

    /**
     * 验证店铺名称是否存在
     */
    public function ckeck_store_nameOp() {
        /**
         * 实例化商家模型
         */
        $where = array();
        $where['store_name'] = $_GET['store_name'];
        $where['store_id'] = array('neq', $_GET['store_id']);
        $store_info = Model('store')->getStoreInfo($where);
        if(!empty($store_info['store_name'])) {
            echo 'false';
        } else {
            echo 'true';
        }
    }
}
