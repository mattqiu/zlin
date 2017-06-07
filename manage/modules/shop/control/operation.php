<?php
/**
 * 网站设置
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

class operationControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('setting');
    }

    public function indexOp() {
        $this->settingOp();
    }

    /**
     * 基本设置
     */
    public function settingOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(

            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $update_array = array();
				$update_array['payment_method'] = $_POST['payment_method'];  
                $update_array['pointshop_isuse'] = $_POST['pointshop_isuse'];
				$update_array['points_isuse'] = $_POST['points_isuse'];
				$update_array['pointprod_isuse'] = $_POST['pointprod_isuse'];
				$update_array['experience_isuse'] = $_POST['experience_isuse'];
                $result = $model_setting->updateSetting($update_array);
                if ($result === true){
                    $this->log(L('im_edit,im_operation,im_operation_set'),1);
                    showMessage(L('im_common_save_succ'));
                }else {
                    showMessage(L('im_common_save_fail'));
                }
            }
        }
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);
		Tpl::setDirquna('shop');
        Tpl::showpage('operation.setting');
    }
}
