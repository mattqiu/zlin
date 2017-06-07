<?php
/**
 * 买家相册
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class sns_albumControl extends mobileMemberControl {
	public function __construct() {
		parent::__construct();
	}

    /**
     * 上传图片
     *
     * @param
     * @return
     */
    public function file_uploadOp() {
		/**
         * 上传个人图片
        */
        if (!empty($_FILES['file']['name'])){
		    $member_id	= $this->member_info['member_id'];
				
		    $model_sns_alumb = Model('sns_album');
            $class_id = $model_sns_alumb->getSnsAlbumClassDefault($member_id);
            if ($member_id <= 0 && $class_id <= 0){
			    output_error('图片上传失败');
            }

            $model = Model();
            // 验证图片数量
            $count = $model->table('sns_albumpic')->where(array('member_id'=>$member_id))->count();
            if(C('malbum_max_sum') != 0 && $count >= C('malbum_max_sum')){
			    output_error('已经超出允许上传图片数量，不能在上传图片！');
            }
			$upload = new UploadFile();
			$upload_dir = ATTACH_MALBUM.DS.$member_id.DS;

            $upload->set('default_dir',$upload_dir.$upload->getSysSetPath());
            $thumb_width    = '240,1024';
            $thumb_height   = '2048,1024';

            $upload->set('max_size',C('image_max_filesize'));
            $upload->set('thumb_width', $thumb_width);
            $upload->set('thumb_height',$thumb_height);
            $upload->set('fprefix',$member_id);
            $upload->set('thumb_ext', '_240,_1024');
            $result = $upload->upfile('file');
			if (!$result){
			    output_error('图片上传失败');
            }

            $img_path = $upload->getSysSetPath().$upload->file_name;
			list($width, $height, $type, $attr) = getimagesize(BASE_UPLOAD_PATH.DS.ATTACH_MALBUM.DS.$member_id.DS.$img_path);
			
			$image = explode('.', $_FILES["file"]["name"]);
			if(strtoupper(CHARSET) == 'GBK'){
                $image['0'] = Language::getGBK($image['0']);
            }

            $insert = array();
            $insert['ap_name']		= $image['0'];
            $insert['ac_id']		= $class_id;
            $insert['ap_cover']		= $img_path;
            $insert['ap_size']		= intval($_FILES['file']['size']);
            $insert['ap_spec']		= $width.'x'.$height;
            $insert['upload_time']	= time();
            $insert['member_id']	= $member_id;
            $result = $model->table('sns_albumpic')->insert($insert);

            $data = array();
            $data['file_url'] = snsThumb($img_path, 240);
            $data['file_name'] = $img_path;
		
            output_data($data); 
		}else{
			output_error('请选择上传文件！');
		}   
	}
}
?>