<?php
/**
 * 我的分销
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class member_distributeControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 添加分销商品
     */
    public function distribute_addOp() {		
		$goods_id = intval($_POST['goods_id']);
		if ($goods_id <= 0){
            output_error('参数错误');
		}
		$distribute_model = Model('distribute_goods');
		//判断是否已经添加
        $distribute_info = $distribute_model->getOneDistributeGoods(array('goods_id'=>$goods_id,'member_id'=>$this->member_info['member_id']));		
		if(!empty($distribute_info)) {
            output_error('您已经添加了该商品');
		}
		//判断商品是否为当前会员所有
		$goods_model = Model('goods');
		$goods_info = $goods_model->getGoodsInfoByID($goods_id);
		//添加分销商品
		$insert_arr = array();
        $insert_arr['member_id'] = $this->member_info['member_id'];
        $insert_arr['member_name'] = $this->member_info['member_name'];
        $insert_arr['goods_id'] = $goods_id;
        $result = $distribute_model->addDistributeGoods($insert_arr);

		if ($result){			
			output_data('添加成功');          
		}else{
            output_error('添加失败');
		}
    }

    /**
     * 删除分销商品
     */
    public function distribute_delOp() {
		$goods_id = intval($_POST['goods_id']);
        if ($goods_id <= 0){
            output_error('参数错误');
        }

        $model_distribute = Model('distribute_goods');
        $model_goods = Model('goods');

        $condition = array();
        $condition['goods_id'] = $goods_id;
        $condition['member_id'] = $this->member_info['member_id'];

        //判断分销商品是否存在
        $distribute_info = $model_distribute->getOneDistributeGoods($condition);
        if(empty($distribute_info)) {
            output_error('收藏删除失败');
        }

        $model_distribute->delDistributeGoods($condition);

        output_data('1');
    }
	
	/**
     * 上传背景
     *
     * @param
     * @return
     */
    public function backgroud_uploadOp() {
		/**
         * 上传个人头像
        */
        if (!empty($_FILES['file']['name'])){
			$member_id	= $this->member_info['member_id'];
			$upload = new UploadFile();
			
            $upload->set('default_dir', ATTACH_MICROSHOP_AVATAR.DS);
			$upload->set('max_size',C('image_max_filesize'));
			$upload->set('thumb_width', '420');
            $upload->set('thumb_height','220');
            $upload->set('thumb_ext',   'jpg');
			$upload->set('thumb_ext', '_'.$member_id);
            $upload->set('file_name',"avatar.jpg");
            $upload->set('ifremove',true);
            $result = $upload->upfile('file');
            if ($result){
				$full_file = UPLOAD_SITE_URL.DS.ATTACH_MICROSHOP_AVATAR.DS.'avatar_'.$member_id.'.jpg';
                output_data(array('file_url'=>$full_file,'file_name'=>'avatar_'.$member_id.'.jpg'));
            }else {
                output_error('头像上传失败');
            }
		}else{
			output_error('请选择头像！');
		}   
	}
}