<?php
/**
 * 二维码生成
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

class qrcode_buildControl extends SystemControl{

    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->qrcode_buildOp();
    }

    public function qrcode_buildOp() {
        $qrcode_url = $_GET['qrcode_url'];
        Tpl::output('qrcode_url',$qrcode_url);
		Tpl::setDirquna('system');
        Tpl::showpage('qrcode_build');
    }

    public function qrcode_build_saveOp() {
        $qrcode_url = $_POST['qrcode_url'];
		$qrcode_size = intval($_POST['qrcode_size']);
		if (empty($qrcode_url)){
			showDialog('请输入二维码的URL');
		}
		//上传logo文件
		if (!empty($_FILES['qrcode_logo']['tmp_name'])){
			$upload = new UploadFile();
			
			$org_name = $_FILES['qrcode_logo']['name'];	
		    $tmp_ext = explode(".", $org_name);
		    $tmp_ext = $tmp_ext[count($tmp_ext) - 1];
		    $tmp_ext = strtolower($tmp_ext);
		    if ($tmp_ext != 'jpg' && $tmp_ext != 'png'){
				showDialog('logo不是图片文件');
			}
			$upload->set('default_dir',ATTACH_TEMP);
            $upload->set('file_name', '');

            $result = $upload->upfile('qrcode_logo',false,false);
			$logo_filename = '';
            if ($result){
                $logo_filename = BASE_UPLOAD_PATH.DS.ATTACH_TEMP.DS.$upload->file_name;
            }else {
                showDialog('logo文件上传失败');
            }
		}
		//生成二维码
		require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
		$PhpQRCode = new PhpQRCode();
		$qrcode_filename = $PhpQRCode->BuildGeneralQRCode($qrcode_url,$logo_filename,$qrcode_size);		
		
        if (!empty($qrcode_filename)){
            showDialog('二维码制作成功!','index.php?act=qrcode_build&op=qrcode_build&qrcode_url='.$qrcode_filename);
        }else {
            showDialog('二维码制作失败!');
        }
    }
}
