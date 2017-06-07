<?php
/**
 * 我的商城
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

class memberControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 我的商城
     */
	public function indexOp() {
		$model_member = Model('member');
		
        $member_info = array();
		//基本信息
		$member_info['member_id'] = $this->member_info['member_id'];
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['member_grade'] = $this->member_info['member_grade'];
        $member_info['avator'] = getMemberAvatarForID($this->member_info['member_id']);
		//会员等级
		$rank_info = $model_member->getOneMemberGrade(intval($this->member_info['member_exppoints']));		
		$member_info['rank_level'] = $rank_info['level'];	
		$member_info['rank_name'] = $rank_info['level_name'];		
		//收藏
		$model_favorites = Model('favorites');
		$member_info['favorites_goods'] = $model_favorites->getMemberGoodsFavoritesCount($this->member_info['member_id']);
		$member_info['favorites_store'] = $model_favorites->getMemberStoreFavoritesCount($this->member_info['member_id']);

		//财富        
        $member_info['predepoit'] = $this->member_info['available_predeposit'];//余额
		$member_info['voucher'] = Model('voucher')->getCurrentAvailableVoucherCount($this->member_info['member_id']);//代金劵
		$member_info['point'] = $this->member_info['member_points'];//积分		
		//订单
		$model_order = Model('order');
		$model_return = Model('refund_return');
		
		$condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
				
		$member_info['order_await_pay'] = $model_order->getOrderStateNewCount($condition);//待付款
		$member_info['order_await_ship'] = $model_order->getOrderStatePayCount($condition);//等发货
		$member_info['order_shipped'] = $model_order->getOrderStateSendCount($condition);//等收货
		$member_info['order_finished'] = $model_order->getOrderStateEvalCount($condition);//等评价		
		$member_info['order_return'] = $model_return->getRefundReturnCount(array('buyer_id'=>$this->member_info['member_id'],'refund_state'=>array('lt',3)));//退款 退货
		//$member_info['order_refund'] = $model_return->getRefundReturnCount(array('buyer_id'=>$this->member_info['member_id'],'refund_type'=>1,'refund_state'=>array('lg',3)));//退款
		//推广处理
		$extension_info = array();
		if (OPEN_STORE_EXTENSION_STATE > 0 && ($this->member_info['mc_id']==1 || $this->member_info['mc_id']==2)){			
			$model_extension = Model('extension');
			$extension_info = $model_extension->getExtensionByMemberID($this->member_info['member_id'],'mc_id,commis_balance,commis_totals');
			if (!empty($extension_info)){
			    $model_detail = Model('extension_commis_detail');
			    $statistics_all = $model_detail->getStatisticsCommis(NULL,$this->member_info['member_id'],array('give_status'=>1));
			    $statistics_curr = $model_detail->getStatisticsCommis(NULL,$this->member_info['member_id'],array('give_status'=>0));
			    $extension_info['commis_totals'] = ($statistics_all['commis']>0)?$statistics_all['commis']:0;
			    $extension_info['commis_balance'] = ($statistics_curr['commis']>0)?$statistics_curr['commis']:0;				
			}else{
				$extension_info['commis_totals'] = 0;
			    $extension_info['commis_balance'] = 0;
			}
			$extension_info['mc_id']    = $this->member_info['mc_id']?$this->member_info['mc_id']:0;
		    $extension_info['store_id'] = $this->member_info['store_id']>0?$this->member_info['store_id']:0;
		    $extension_info['extension'] = '&extension='.urlsafe_b64encode($this->member_info['member_id']);		
		}else{
			$extension_info['mc_id'] = 0;		
		    $extension_info['store_id'] = 0;
		    $extension_info['extension'] = '';
			$extension_info['commis_totals'] = 0;
			$extension_info['commis_balance'] = 0;
		}
		$member_info['extension_info'] = $extension_info;

        output_data(array('member_info' => $member_info));
	}
	
	/**
     * 获取当前登录会员信息
     */
	public function getinfoOp() {		
		$member_info = 	$this->member_info;
		$model_member = Model('member');
		
		$user_info = array();
		$user_info['id'] = $member_info['member_id'];
		$user_info['name'] = $member_info['member_name'];
		
		$rank_info = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));		
		$user_info['rank_level'] = $rank_info['level'];	
		$user_info['rank_name'] = $rank_info['level_name'];	
		
		$user_info['formated_user_money'] = imPriceFormat($member_info['available_predeposit']);
		$user_info['user_bonus_count'] = Model('voucher')->getCurrentAvailableVoucherCount($member_info['member_id']);
		$user_info['vip_points'] = $member_info['member_points'];
		$user_info['collection_num'] = 0;		
				
		$user_info['mc_id'] = $member_info['mc_id'];
		$user_info['store_id'] = $member_info['store_id'];
				
		$model_order = Model('order');
		$condition = array();
        $condition['buyer_id'] = $member_info['member_id'];
				
		$order_num = array();
		$order_num['await_pay'] = $model_order->getOrderStateNewCount($condition);
		$order_num['await_ship'] = $model_order->getOrderStatePayCount($condition);
		$order_num['shipped'] = $model_order->getOrderStateSendCount($condition);
		$order_num['finished'] = $model_order->getOrderStateEvalCount($condition);	
				
		$user_info['order_num'] = $order_num;
		
		$user_info['bonus_list'] = array();
				
        output_data($user_info);
	}
	
	/**
     * 修改密码
     */
    public function modify_pwdOp() {
        $model_member = Model('member');
		
		$password = $_POST['password'];
		$new_password = $_POST['new_password'];
		
		$member_info = $model_member->getMemberInfo(array('member_id'=>$this->member_info['member_id'],'member_passwd'=>md5($password),'member_id'));
		if (empty($member_info)){
			output_error('原始密码错误!');
		}
		$update	= $model_member->editMember(array('member_id'=>$this->member_info['member_id']),array('member_passwd'=>md5($new_password)));
		if (!$update){
			output_error('系统出错，请稍候再试......!');
		}
		output_data('密码修改成功！');
	}
	
	/**
     * 从qq获取头像
     */
    public function get_avatar_qqOp() {		
		if (empty($this->member_info['member_qqopenid']) || empty($this->member_info['member_qqinfo'])){
			output_error('您还未绑定QQ，请先绑定QQ帐号!');
		}
		$member_avatar = BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_".$this->member_info['member_id'].".jpg";
		$member_images = UPLOAD_SITE_URL.DS.ATTACH_AVATAR.DS.'avatar_'.$this->member_info['member_id'].'.jpg';
		
		$qquser_info = unserialize($this->member_info['member_qqinfo']);
		//复制头像文件
		$avatar	= DownloadRemoteImg($qquser_info['figureurl_qq_2'],$member_avatar);  
		if($avatar) {
			$model_member = Model('member');
			$update_info = array();
		    $update_info['member_avatar'] 	= "avatar_".$this->member_info['member_id'].".jpg";
			$edit_state	= $model_member->editMember(array('member_id'=>$this->member_info['member_id']), $update_info);
			if ($edit_state){
			    output_data($member_images);
		    }else {
			    output_error('系统出错，请稍候再试......!');
		    }
		}else{
			output_error('系统出错，请稍候再试......!');
		}
	}
	
	/**
     * 从微信获取头像
     */
    public function get_avatar_wxOp() {		
		if (empty($this->member_info['member_wxopenid']) || empty($this->member_info['member_wxinfo'])){
			output_error('您还未绑定微信，请先绑定微信帐号!');
		}
		$member_avatar = BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_".$this->member_info['member_id'].".jpg";
		$member_images = UPLOAD_SITE_URL.DS.ATTACH_AVATAR.DS.'avatar_'.$this->member_info['member_id'].'.jpg';
		
		$wxuser_info = unserialize($this->member_info['member_wxinfo']);
		//复制头像文件
		$avatar	= DownloadRemoteImg($wxuser_info['headimgurl'],$member_avatar);
		if($avatar) {
			$model_member = Model('member');
			$update_info = array();
		    $update_info['member_avatar'] 	= "avatar_".$this->member_info['member_id'].".jpg";
			$edit_state	= $model_member->editMember(array('member_id'=>$this->member_info['member_id']), $update_info);
			if ($edit_state){
			    output_data($member_images);
		    }else {
			    output_error('系统出错，请稍候再试......!');
		    }
		}else{
			output_error('系统出错，请稍候再试......!');
		}
	}
	
	/**
     * 上传头像
     *
     * @param
     * @return
     */
    public function avatar_uploadOp() {
		/**
         * 上传个人头像
        */
        if (!empty($_FILES['file']['name'])){
			$member_id	= $this->member_info['member_id'];
			$upload = new UploadFile();
			
            $upload->set('default_dir', ATTACH_AVATAR.DS);
			$upload->set('max_size',C('image_max_filesize'));
			$upload->set('thumb_width', '240');
            $upload->set('thumb_height','240');
            $upload->set('thumb_ext',   'jpg');
			$upload->set('thumb_ext', '_'.$member_id);
            $upload->set('file_name',"avatar.jpg");
            $upload->set('ifremove',true);
            $result = $upload->upfile('file');
            if ($result){
				$full_file = UPLOAD_SITE_URL.DS.ATTACH_AVATAR.DS.'avatar_'.$member_id.'.jpg';
                output_data(array('file_url'=>$full_file,'file_name'=>'avatar_'.$member_id.'.jpg'));
            }else {
                output_error('头像上传失败');
            }
		}else{
			output_error('请选择头像！');
		}   
	}
}