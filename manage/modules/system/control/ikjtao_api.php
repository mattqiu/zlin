<?php
/**
 * 跨境淘接口
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

class ikjtao_apiControl extends SystemControl{

    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->ikjtao_api_settingOp();
    }

    public function ikjtao_api_settingOp() {
        $model_setting = Model('setting');
        $setting_list = $model_setting->getListSetting();
        Tpl::output('setting',$setting_list);
		Tpl::setDirquna('system');
        Tpl::showpage('ikjtao_api');
    }

    public function ikjtao_api_saveOp() {
        $model_setting = Model('setting');

        $update_array['ikjtao_api_isuse'] = intval($_POST['ikjtao_api_isuse']);
        $update_array['ikjtao_app_key'] = $_POST['ikjtao_app_key'];
        $update_array['ikjtao_secret_key'] = $_POST['ikjtao_secret_key'];
        $update_array['ikjtao_customs'] = $_POST['ikjtao_customs'];
        $update_array['ikjtao_customs_no'] = $_POST['ikjtao_customs_no'];
        $update_array['ikjtao_customs_name'] = $_POST['ikjtao_customs_name'];
        $result = $model_setting->updateSetting($update_array);
        if ($result === true){
            $this->log('跨境淘接口保存', 1);
            showMessage(Language::get('im_common_save_succ'));
        }else {
            $this->log('跨境淘接口保存', 0);
            showMessage(Language::get('im_common_save_fail'));
        }
    }
}
