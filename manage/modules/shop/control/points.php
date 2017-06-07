<?php
/**
 * 积分管理
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */

defined('InIMall') or exit('Access Invalid!');

class pointsControl extends SystemControl{
    const EXPORT_SIZE = 5000;
    public function __construct(){
        parent::__construct();
        Language::read('points');
        //判断系统是否开启积分功能
        if (C('points_isuse') != 1){
            showMessage(Language::get('admin_points_unavailable'),'index.php?act=setting','','error');
        }
    }

    public function indexOp() {
        $this->pointslogOp();
    }

    /**
     * 积分添加
     */
    public function addpointsOp(){
        if (chksubmit()){

            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["member_id"], "require"=>"true", "message"=>Language::get('admin_points_member_error_again')),
                array("input"=>$_POST["pointsnum"], "require"=>"true",'validator'=>'Compare','operator'=>' >= ','to'=>1,"message"=>Language::get('admin_points_points_min_error'))
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error,'','','error');
            }
            //查询会员信息
            $obj_member = Model('member');
            $member_id = intval($_POST['member_id']);
            $member_info = $obj_member->getMemberInfo(array('member_id'=>$member_id));

            if (!is_array($member_info) || count($member_info)<=0){
                showMessage(Language::get('admin_points_userrecord_error'),'index.php?act=points&op=addpoints','','error');
            }

            $pointsnum = intval($_POST['pointsnum']);
            if ($_POST['operatetype'] == 2 && $pointsnum > intval($member_info['member_points'])){
                showMessage(Language::get('admin_points_points_short_error').$member_info['member_points'],'index.php?act=points&op=addpoints','','error');
            }

            $obj_points = Model('points');
            $insert_arr['pl_memberid'] = $member_info['member_id'];
            $insert_arr['pl_membername'] = $member_info['member_name'];
            $admininfo = $this->getAdminInfo();
            $insert_arr['pl_adminid'] = $admininfo['id'];
            $insert_arr['pl_adminname'] = $admininfo['name'];
            if ($_POST['operatetype'] == 2){
                $insert_arr['pl_points'] = -$_POST['pointsnum'];
            }else {
                $insert_arr['pl_points'] = $_POST['pointsnum'];
            }
            if ($_POST['pointsdesc']){
                $insert_arr['pl_desc'] = trim($_POST['pointsdesc']);
            } else {
                $insert_arr['pl_desc'] = Language::get('admin_points_system_desc');
            }
            $result = $obj_points->savePointsLog('system',$insert_arr,true);
            if ($result){
                $this->log(L('admin_points_mod_tip').$member_info['member_name'].'['.(($_POST['operatetype'] == 2)?'':'+').strval($insert_arr['pl_points']).']',null);
                showMessage(Language::get('im_common_save_succ'),'index.php?act=points&op=addpoints');
            }else {
                showMessage(Language::get('im_common_save_fail'),'index.php?act=points&op=addpoints','','error');
            }
        }else {
			Tpl::setDirquna('shop');
            Tpl::showpage('points.add');
        }
    }
    public function checkmemberOp(){
        $name = trim($_GET['name']);
        if (!$name){
            echo ''; die;
        }
        /**
         * 转码
         */
        if(strtoupper(CHARSET) == 'GBK'){
            $name = Language::getGBK($name);
        }
        $obj_member = Model('member');
        $member_info = $obj_member->getMemberInfo(array('member_name'=>$name));
        if (is_array($member_info) && count($member_info)>0){
            if(strtoupper(CHARSET) == 'GBK'){
                $member_info['member_name'] = Language::getUTF8($member_info['member_name']);
            }
            echo json_encode(array('id'=>$member_info['member_id'],'name'=>$member_info['member_name'],'points'=>$member_info['member_points']));
        }else {
            echo ''; die;
        }
    }
    /**
     * 积分日志列表
     */
    public function pointslogOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('points.log');
    }

    /**
     * 规则设置
     */
    public function settingOp() {
        Language::read('setting');
        $model_setting = Model('setting');
        if (chksubmit()){
            $update_array = array();
            $update_array['points_reg'] = intval($_POST['points_reg'])?$_POST['points_reg']:0;
            $update_array['points_login'] = intval($_POST['points_login'])?$_POST['points_login']:0;            
            $update_array['points_orderrate'] = intval($_POST['points_orderrate'])?$_POST['points_orderrate']:0;
            $update_array['points_ordermax'] = intval($_POST['points_ordermax'])?$_POST['points_ordermax']:0;
			$update_array['points_comments'] = intval($_POST['points_comments'])?$_POST['points_comments']:0;
			$update_array['points_trade'] = intval($_POST['points_trade'])?$_POST['points_trade']:0;
			
			$points_sign = array();			
			$points_sign[1] = intval($_POST['points_sign_1'])?$_POST['points_sign_1']:0;
			$points_sign[2] = intval($_POST['points_sign_2'])?$_POST['points_sign_2']:0;
			$points_sign[3] = intval($_POST['points_sign_3'])?$_POST['points_sign_3']:0;
			$points_sign[4] = intval($_POST['points_sign_4'])?$_POST['points_sign_4']:0;
			$points_sign[5] = intval($_POST['points_sign_5'])?$_POST['points_sign_5']:0;
			$points_sign[6] = intval($_POST['points_sign_6'])?$_POST['points_sign_6']:0;
			$points_sign[7] = intval($_POST['points_sign_7'])?$_POST['points_sign_7']:0;
			
			$update_array['points_sign'] = serialize($points_sign);
            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log('积分设置',1);
                showMessage(L('im_common_save_succ'));
            }else {
                showMessage(L('im_common_save_fail'));
            }
        }
		
        $list_setting = $model_setting->getListSetting();
		$list_setting['points_sign'] = $list_setting['points_sign']?unserialize($list_setting['points_sign']):array();
		
        Tpl::output('list_setting',$list_setting);
		Tpl::setDirquna('shop');
        Tpl::showpage('points.setting');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] =  $_POST['query'];
        }
        $order = '';
        $param = array('pl_id','pl_memberid','pl_membername','pl_points','pl_addtime');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $condition['order'] = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page   = new Page();
        $page->setEachNum(!empty($_POST['rp']) ? intval($_POST['rp']) : 15);
        $page->setStyle('admin');
        $points_model = Model('points');
        $list_log = $points_model->getPointsLogList($condition,$page,'*','');
		if (empty($list_log)) $list_log = array();
        $stage_arr = $this->get_state();
        $data = array();
        $data['now_page'] = $page->get('now_page');;
        $data['total_num'] = $page->get('total_num');
        foreach ($list_log as $value) {
            $param = array();
            $param['operation'] = "--";
            $param['pl_id'] = $value['pl_id'];
            $param['pl_memberid'] = $value['pl_memberid'];
            $param['pl_membername'] = $value['pl_membername'];
            $param['pl_points'] = $value['pl_points'];
			$param['pl_stage'] = $stage_arr[$value['pl_stage']];
			$param['pl_addtime'] = date('Y-m-d H:i:s', $value['pl_addtime']);
            $param['pl_desc'] = $value['pl_desc'];
            $param['pl_adminname'] = $value['pl_adminname'];
            $data['list'][$value['pl_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    private function get_state() {
        $array = array();
        $array['regist'] = L('admin_points_stage_regist');
        $array['login'] = L('admin_points_stage_login');
        $array['comments'] = L('admin_points_stage_comments');
        $array['order'] = L('admin_points_stage_order');
        $array['system'] = L('admin_points_stage_system');
        $array['pointorder'] = L('admin_points_stage_pointorder');
        $array['app'] = L('admin_points_stage_app');
        return $array;
    }
}
