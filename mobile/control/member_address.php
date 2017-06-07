<?php
/**
 * 我的地址
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

class member_addressControl extends mobileMemberControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 地址列表
     */
    public function address_listOp() {
		$model_address = Model('address');
        $address_list = $model_address->getAddressList(array('member_id'=>$this->member_info['member_id']));
        output_data(array('address_list' => $address_list));
    }

    /**
     * 地址详细信息
     */
    public function address_infoOp() {
		$address_id = intval($_POST['address_id']);

		$model_address = Model('address');

        $condition = array();
        $condition['address_id'] = $address_id;
        $address_info = $model_address->getAddressInfo($condition);
        if(!empty($address_id) && $address_info['member_id'] == $this->member_info['member_id']) {
            output_data(array('address_info' => $address_info));
        } else {
            output_error('地址不存在');
        }
    }

    /**
     * 删除地址
     */
    public function address_delOp() {
		$address_id = intval($_POST['address_id']);

		$model_address = Model('address');

        $condition = array();
        $condition['address_id'] = $address_id;
        $condition['member_id'] = $this->member_info['member_id'];
        $model_address->delAddress($condition);
        output_data('1');
    }

    /**
     * 新增地址
     */
    public function address_addOp() {
        $model_address = Model('address');

        $address_info = $this->_address_valid();

        $result = $model_address->addAddress($address_info);
        if($result) {
        	$model_member = Model('member');
        	if(empty($this->member_info['member_mobile'])||empty($this->member_info['member_idcard'])){
            	$model_member->editMember(array('member_id'=>$address_info['member_id']),array('member_mobile'=>$address_info['member_mobile'],'member_idcard'=>$address_info['member_idcard']));
            }
        	output_data(array('address_id' => $result));
        } else {
            output_error('保存失败');
        }
    }

    /**
     * 编辑地址
     */
    public function address_editOp() {
        $address_id = intval($_POST['address_id']);

        $model_address = Model('address');

        //验证地址是否为本人
        $address_info = $model_address->getOneAddress($address_id);
        if ($address_info['member_id'] != $this->member_info['member_id']) {
            output_error('参数错误');
        }

        $address_info = $this->_address_valid();

        $result = $model_address->editAddress($address_info, array('address_id' => $address_id));
        if($result) {
        	$model_member = Model('member');
        	if(empty($this->member_info['member_mobile'])||empty($this->member_info['member_idcard'])){
        		$model_member->editMember(array('member_id'=>$address_info['member_id']),array('member_mobile'=>$address_info['member_mobile'],'member_idcard'=>$address_info['member_idcard']));
        	}
            output_data('1');
        } else {
            output_error('保存失败');
        }
    }

    /**
     * 编辑身份证
     */
    public function idcard_editOp() {
    	$member_id = $this->member_info['member_id'];
    	$address_info['member_idcard'] = $_POST['user_idcard'];
    	$model_member = Model('member');
    	$result = $model_member->editMember(array('member_id'=>$member_id),array('member_idcard'=>$address_info['member_idcard']));
    	if($result) {
	    	$model_address = Model('address');
	    	$model_address->editAddress($address_info, array('member_id' => $member_id,'member_idcard'=>array('in',array(0,'',null))));
    		output_data(array('member_idcard'=>$address_info['member_idcard']));
    	} else {
    		output_error('保存失败');
    	}
    }
    /**
     * 验证地址数据
     */
    private function _address_valid() {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$_POST["true_name"],"require"=>"true","message"=>'姓名不能为空'),
            array("input"=>$_POST["area_info"],"require"=>"true","message"=>'地区不能为空'),
            array("input"=>$_POST["address"],"require"=>"true","message"=>'地址不能为空'),
            array("input"=>$_POST['tel_phone'].$_POST['mob_phone'],'require'=>'true','message'=>'联系方式不能为空')
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }

        $data = array();
        $data['member_id'] = $this->member_info['member_id'];
        $data['true_name'] = $_POST['true_name'];
        $city_id = $_POST['area_id'];
        $area_id = $_POST['city_id'];
        if(empty($_POST['area_id']) || empty($city_id)){
        	$area_info = $_POST['area_info'];
        	$area_arr = explode(" ",$area_info);
        	foreach($area_arr as $akey=>$area){
        		$addr[$akey] = Model('area')->getAreaInfo(array('area_name'=>$area),'area_id');
        	}
        	$pra_id = $addr[0]['area_id'];
        	$city_id = $addr[1]['area_id'];
        	$area_id = $addr[2]['area_id'];
        }
        $data['area_id'] = intval($area_id);
        $data['city_id'] = intval($city_id);
        $data['area_info'] = $_POST['area_info'];
        $data['address'] = $_POST['address'];
        $data['tel_phone'] = $_POST['tel_phone'];
        $data['mob_phone'] = $_POST['mob_phone'];
        $data['member_idcard'] = $_POST['member_idcard'];
		$data['is_default'] = $_POST['is_default'];
        return $data;
    }

    /**
     * 地区列表
     */
    public function area_listOp() {
        $area_id = intval($_POST['area_id']);

        $model_area = Model('area');

        $condition = array();
        if($area_id > 0) {
            $condition['area_parent_id'] = $area_id;
        } else {
            $condition['area_deep'] = 1;
        }
        $area_list = $model_area->getAreaList($condition, 'area_id,area_name');
        output_data(array('area_list' => $area_list));
    }

}