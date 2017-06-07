<?php
/**
 * 店铺申请信息提交
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

class store_addapplyControl extends mobileStoreControl{
	
    protected $member_info = array();
	
	public function __construct() {
	    parent::__construct();        
	}
	
	/**
	 * 申请加盟
	 */
	public function indexOp(){
		$this->check_member_isLogin();
		output_data(array('msg'=>'申请推广员提交成功！请等待店铺管理员审核。'));
		if (empty($this->member_info['myself_store_id']) || $this->member_info['myself_store_id']<0){
			output_error('您还未开通店铺，请先开通平台店铺，再申请加盟！',array('url'=>urlShop('show_joinin','index')));
		}
	
		//检查店铺分店是否达到上限
		if ($this->store_info['branch_apply']==0){
			output_error('加盟店已满，如有疑问请与店铺联系！');
		}
		//是否有推广员推荐 - 推广处理
		$ai_type = $this->get_StoreAi_Type();		
					
		$apply_info = array();
		$apply_info['site_name'] = $this->store_info['store_name'];
		
		$apply_info['ai_from'] = $this->member_info['member_id'];
		$apply_info['ai_target'] = $this->store_id;		

		$apply_info['ai_type'] = $ai_type;
		$apply_info['saleman_open'] = $this->store_info['saleman_open'];
		$apply_info['promotion_open'] = $this->store_info['promotion_open'];
		$apply_info['saleman_apply'] = $this->store_info['saleman_apply'];
		$apply_info['promotion_apply'] = $this->store_info['promotion_apply'];
		$apply_info['extension_adv'] = htmlspecialchars_decode($this->store_info['extension_adv']);
		
		$model_apply = Model('apply_information');
		$state = $model_apply->addApplyInfo($apply_info);
		if($state) {
			// 发送买家消息
			$param = array();
			$param['code'] = 'apply_join';
			$param['member_id'] = $this->member_info['member_id'];
			$param['param'] = array(
					'store_url' => urlShop('show_store', 'index', array('store_id' => $this->store_id)),
					'store_name' => $this->store_info['store_name'],
					'site_name' => C('site_name'),
					'mail_send_time'=>date("Y-m-d",time())
			);
			QueueClient::push('sendMemberMsg', $param);
			// 发送店铺消息
			$param = array();
			$param['code'] = 'new_apply_join';
			$param['store_id'] = $this->store_id;
			$param['param'] = array();
			QueueClient::push('sendStoreMsg', $param);
			output_data(array('msg'=>'申请推广员提交成功！请等待店铺管理员审核。'));
		} else {
			output_error('申请推广员失败,该店铺不支持推广!');
		};
	}
	
	/**
	 * 申请加盟
	 */
	public function apply_joinOp(){
		$this->check_member_isLogin();
		
		if (empty($this->member_info['myself_store_id']) || $this->member_info['myself_store_id']<0){
			output_error('您还未开通店铺，请先开通平台店铺，再申请加盟！',array('url'=>urlShop('show_joinin','index')));
		}
		
		//检查店铺分店是否达到上限
		if ($this->store_info['branch_apply']==0){
			output_error('加盟店已满，如有疑问请与店铺联系！');
		}
		
		//检查是否已经加盟
		if (!empty($this->member_info['mystore_parent_id']) && $this->member_info['mystore_parent_id']>0){
			output_error('您已经加盟了其它的店铺！');
		}
		
		if (!empty($this->member_info['myself_store_id']) && $this->member_info['myself_store_id']>0){
		  //检查申请的店铺是否自己的店铺是同一店铺
		  if ($this->member_info['myself_store_id']==$this->store_id){
			 output_error('不能申请加盟自己的店铺！');
		  }
		}
		
		$apply_info = array();
		$apply_info['member_truename'] = $this->member_info['member_truename'];
		$apply_info['member_email'] = $this->member_info['member_email'];
		$apply_info['member_mobile'] = $this->member_info['member_mobile'];
		$apply_info['member_qq'] = $this->member_info['member_qq'];
		$apply_info['member_areainfo'] = preg_replace("/\s/","",$this->member_info['member_areainfo']);
		$apply_info['ai_from'] = $this->member_info['member_id'];
		$apply_info['ai_target'] = $this->store_id;
		
		$datas = array();
		$datas['apply_info'] = $apply_info;		
		output_data($datas);
	}
	
	/**
	 * 保存申请加盟
	 */
	public function apply_join_saveOp(){
		$this->check_member_isLogin();
		
		//检查店铺分店是否达到上限
		if ($this->store_info['branch_apply']==0){
			output_error('加盟店已满，如有疑问请与店铺联系！');
		}
		
		$ai_addinfo = array();
		$ai_addinfo['truename'] = $_POST['truename'];
		$ai_addinfo['email'] = $_POST['email'];	
		$ai_addinfo['mobile'] = $_POST['mobile'];	
		$ai_addinfo['qq'] = $_POST['qq'];
		$ai_addinfo['areainfo'] = $_POST['areainfo'];
		$ai_addinfo['describe'] = $_POST['describe'];
		
		$data=array();
		$data['ai_from'] = $this->member_info['member_id'];	
		$data['ai_target'] = $this->store_id;
		$data['ai_type'] = 5;		
		$data['ai_addinfo'] = serialize($ai_addinfo); //unserialize
		$data['ai_dispose'] = 0;
		$data['ai_views'] = 0;
		//$data['ai_replyinfo']='';
		$data['ai_addtime'] = time();
		//$data['ai_distime']=NULL;
		
		$model_apply = Model('apply_information');		
		$state = $model_apply->addApplyInfo($data);	
		if($state) {
			// 发送买家消息
            $param = array();
            $param['code'] = 'apply_join';
            $param['member_id'] = $this->member_info['member_id'];
            $param['param'] = array(
                'store_url' => urlShop('show_store', 'index', array('store_id' => $this->store_id)),
                'store_name' => $this->store_info['store_name'],
				'site_name' => C('site_name'),
				'mail_send_time'=>date("Y-m-d",time())
            );			
            QueueClient::push('sendMemberMsg', $param);			
			// 发送店铺消息
            $param = array();
		    $param['code'] = 'new_apply_join';
			$param['store_id'] = $this->store_id;
			$param['param'] = array();
			QueueClient::push('sendStoreMsg', $param);
			output_data(array('msg'=>'申请加盟提交成功！请等待店铺管理员审核。'));
		} else {
			output_error('申请加盟提交失败!');
		};
	}
	
	/**
	 * 申请推广
	 */
	public function apply_extensionOp(){
		//检查店铺分店是否达到上限
		if ($this->store_info['promotion_apply']==0 && $this->store_info['saleman_apply']==0){
			output_error('店铺未开通在线申请或申请人数已满，如有疑问请与店铺联系！');
		}
		
		if (intval($_SESSION['is_login']) != 1){
			$this->reg_extension();
		}else{
			$this->apply_extension();
		}
	}
	
	/**
	 * 注册推广
	 */
	public function reg_extension(){
		Language::read("home_login_register");
		//是否有推广员推荐 - 推广处理
		$ai_type = $this->get_StoreAi_Type();		
					
		$apply_info = array();
		$apply_info['site_name'] = $this->store_info['store_name'];
		
		$apply_info['ai_from'] = $this->member_info['member_id'];
		$apply_info['ai_target'] = $this->store_id;		

		$apply_info['ai_type'] = $ai_type;
		$apply_info['saleman_open'] = $this->store_info['saleman_open'];
		$apply_info['promotion_open'] = $this->store_info['promotion_open'];
		$apply_info['saleman_apply'] = $this->store_info['saleman_apply'];
		$apply_info['promotion_apply'] = $this->store_info['promotion_apply'];
		$apply_info['extension_adv'] = htmlspecialchars_decode($this->store_info['extension_adv']);
		
		$datas = array();
		$datas['apply_info'] = $apply_info;		
		output_data($datas);	
	}
	
	/**
	 * 申请推广
	 */
	public function apply_extension(){	
		//检查是否已经是推广员
		if ($this->member_info['mc_id']==2){
			output_error('您已经是推广员，不能再申请！');
		}
		
		if ($this->member_info['mc_id']==1 && $this->member_info['store_id']==$this->store_id){
		  output_error('你已经是本店铺的导购员了！');
		}		
		
		$apply_info = array();
		$apply_info['site_name'] = $this->store_info['store_name'];
		
		$apply_info['member_truename'] = $this->member_info['member_truename'];
		$apply_info['member_email'] = $this->member_info['member_email'];
		$apply_info['member_mobile'] = $this->member_info['member_mobile'];
		$apply_info['member_qq'] = $this->member_info['member_qq'];
		$apply_info['member_areainfo'] = preg_replace("/\s/","",$this->member_info['member_areainfo']);
		$apply_info['ai_from'] = $this->member_info['member_id'];
		$apply_info['ai_target'] = $this->store_id;
		
		$ai_type = $this->get_StoreAi_Type();
		
		$apply_info['ai_type'] = $ai_type;
		$apply_info['saleman_open'] = $this->store_info['saleman_open'];
		$apply_info['promotion_open'] = $this->store_info['promotion_open'];
		$apply_info['saleman_apply'] = $this->store_info['saleman_apply'];
		$apply_info['promotion_apply'] = $this->store_info['promotion_apply'];
		$apply_info['extension_adv'] = htmlspecialchars_decode($this->store_info['extension_adv']);
		
		$datas = array();
		$datas['apply_info'] = $apply_info;		
		output_data($datas);		
	}
	
	/**
	 * 保存申请推广
	 */
	public function reg_extension_saveOp(){	
	    //重复注册验证
		if (process::islock('reg')){
			output_error(Language::get('im_common_op_repeat'));
		}
		//检查店铺分店是否达到上限
		if ($this->store_info['promotion_apply']==0 && $this->store_info['saleman_apply']==0){
			output_error('店铺未开通在线申请或申请人数已满，如有疑问请与店铺联系！');
		}
		$ai_type = intval($_POST['ai_type']);
		if ($ai_type<1 || $ai_type>3){
			output_error('非法操作！');
		}
		if ($this->store_info['promotion_apply']!=1 && ($ai_type==2 || $ai_type==3)){
			output_error('店铺未开通推广员在线申请或人数已满，如有疑问请与店铺联系！');
		}
		if ($this->store_info['saleman_apply']!=1 && $ai_type==1){
			output_error('店铺未开通导购员在线申请或人数已满，如有疑问请与店铺联系！');
		}
		//检查推广员申请条件
		if ($ai_type==2 || $ai_type==3){
			if ($this->store_info['promotion_require']>0){
				output_error('申请本店推广员需先在本店消费满'.$this->store_info['promotion_require'].'元，请注册会员并在本店消费满：'.$this->store_info['promotion_require'].'元再申请本店推广！');
			}
		}else if ($ai_type==1){
			if ($this->store_info['saleman_require']>0){
				output_error('申请本店导购员需先在本店消费满'.$this->store_info['saleman_require'].'元，请注册会员并在本店消费满：'.$this->store_info['saleman_require'].'元再申请本店推广！');
			}
		}
		
		$model_member	= Model('member');

        $register_info = array();		
        $register_info['member_name']      = $_POST['username'];
        $register_info['member_passwd']    = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['member_mobile']    = $_POST['mobile'];		
        $this->member_info = $model_member->register($register_info);
        if(!isset($this->member_info['error'])) {					
            $token = $model_member->get_mobile_token($this->member_info['member_id'], $this->member_info['member_name'], $_POST['client']);
            if($token) {
				$ai_addinfo = array();
		        $ai_addinfo['truename'] = $_POST['truename'];
		        $ai_addinfo['email'] = $_POST['email'];	
		        $ai_addinfo['mobile'] = $_POST['mobile'];	
		        $ai_addinfo['qq'] = $_POST['qq'];
		        $ai_addinfo['areainfo'] = $_POST['areainfo'];
		        $ai_addinfo['describe'] = $_POST['describe'];
		
		        //是否有推广员推荐 - 推广处理
		        $ai_target = $this->store_id;
		        if ($this->store_info['promotion_apply']==1){
		            $extension_id=cookie('iMall_extension');			
		            if (!empty($extension_id)){
			            $extension_id=urlsafe_b64decode($extension_id);
			            $promotionLevel = Model('extension')->GetPromotionLevel($extension_id);
			            if ($promotionLevel!=0 && $promotionLevel<$this->store_info['promotion_level']){
			                $ai_target = $extension_id;
			                $ai_type = 3;//下级推广员
			            }			
			        }
			    }		
		
		        $data=array();
		        $data['ai_from'] = $this->member_info['member_id'];
		        $data['ai_target'] = $ai_target;
		        $data['ai_type'] = $ai_type;		
		        $data['ai_addinfo'] = serialize($ai_addinfo); //unserialize
		        $data['ai_dispose'] = 0;
		        $data['ai_views'] = 0;
		        //$data['ai_replyinfo']='';
		        $data['ai_addtime'] = time();
		        //$data['ai_distime']=NULL;
		
		        $model_apply = Model('apply_information');		
		        $state = $model_apply->addApplyInfo($data);		
		        if($state) {
			        // 发送买家消息
                    $param = array();
                    $param['code'] = 'apply_extension';
                    $param['member_id'] = $this->member_info['member_id'];
                    $param['param'] = array(
                        'store_url' => urlShop('show_store', 'index', array('store_id' => $this->store_id)),
                        'store_name' => $this->store_info['store_name'],
				        'site_name' => C('site_name'),
				        'mail_send_time'=>date("Y-m-d",time())
                    );
                    QueueClient::push('sendMemberMsg', $param);
			        // 发送店铺消息
                    $param = array();
			        if ($ai_type!=3){
		                $param['code'] = 'new_apply_extension';
			            $param['store_id'] = $ai_target;
			            $param['param'] = array();
			            QueueClient::push('sendStoreMsg', $param);
			        }else{
				        $param = array();
                        $param['code'] = 'new_apply_extension';
                        $param['member_id'] = $ai_target;
                        $param['param'] = array();
                        QueueClient::push('sendMemberMsg', $param);
			        }
					
					
			        output_data(array('msg'=>'推广注册提交成功！请等待管理员审核。','username' => $this->member_info['member_name'], 'key' => $token));
		        } else {
			        output_error('推广注册提交失败!');
		        };
				
				$model_member->createSession($this->member_info,true);
			    process::addprocess('reg');
				
            } else {
                output_error('注册失败');
            }
        } else {
			output_error($this->member_info['error']);
        }		
	}
	/**
	 * 保存申请推广
	 */
	public function apply_extension_saveOp(){
		$this->check_member_isLogin();
		
		//检查店铺分店是否达到上限
		if ($this->store_info['promotion_apply']==0 && $this->store_info['saleman_apply']==0){
			output_error('店铺未开通在线申请或申请人数已满，如有疑问请与店铺联系！');
		}
		$ai_type = intval($_POST['ai_type']);
		if ($ai_type<1 || $ai_type>3){
			output_error('非法操作！');
		}
		if ($this->store_info['promotion_apply']!=1 && ($ai_type==2 || $ai_type==3)){
			output_error('店铺未开通推广员在线申请或人数已满，如有疑问请与店铺联系！');
		}
		if ($this->store_info['saleman_apply']!=1 && $ai_type==1){
			output_error('店铺未开通导购员在线申请或人数已满，如有疑问请与店铺联系！');
		}
		//检查推广员申请条件
		if ($ai_type==2 || $ai_type==3){
			if ($this->store_info['promotion_require']>0){
				$ConsumeTotals = Model('extension')->GetMemberConsumeTotals($this->member_info['member_id'],$this->store_id);
				if ($ConsumeTotals<$this->store_info['promotion_require']){
					output_error('申请本店推广员需先在本店消费满'.$this->store_info['promotion_require'].'元!您目前在本店的消费为：'.$ConsumeTotals.'元');
				}
			}
		}else if ($ai_type==1){
			if ($this->store_info['saleman_require']>0){
				$ConsumeTotals = Model('extension')->GetMemberConsumeTotals($this->member_info['member_id'],$this->store_id);
				if ($ConsumeTotals<$this->store_info['saleman_require']){
					output_error('申请本店导购员需先在本店消费满'.$this->store_info['saleman_require'].'元!您目前在本店的消费为：'.$ConsumeTotals.'元');
				}
			}
		}
		
		$ai_addinfo = array();
		$ai_addinfo['truename'] = $_POST['truename'];
		$ai_addinfo['email'] = $_POST['email'];	
		$ai_addinfo['mobile'] = $_POST['mobile'];	
		$ai_addinfo['qq'] = $_POST['qq'];
		$ai_addinfo['areainfo'] = $_POST['areainfo'];
		$ai_addinfo['describe'] = $_POST['describe'];
		
		//是否有推广员推荐 - 推广处理
		$ai_target = $this->store_id;
		if ($this->store_info['promotion_apply']==1){
		  $extension_id=cookie('iMall_extension');			
		  if (!empty($extension_id)){
			$extension_id=urlsafe_b64decode($extension_id);
			$promotionLevel = Model('extension')->GetPromotionLevel($extension_id);
			if ($promotionLevel!=0 && $promotionLevel<$this->store_info['promotion_level']){
			  $ai_target = $extension_id;
			  $ai_type = 3;//下级推广员
			}
			
		  }
		}		
		
		$data=array();
		$data['ai_from'] = $this->member_info['member_id'];
		$data['ai_target'] = $ai_target;
		$data['ai_type'] = $ai_type;		
		$data['ai_addinfo'] = serialize($ai_addinfo); //unserialize
		$data['ai_dispose'] = 0;
		$data['ai_views'] = 0;
		//$data['ai_replyinfo']='';
		$data['ai_addtime'] = time();
		//$data['ai_distime']=NULL;
		
		$model_apply = Model('apply_information');		
		$state = $model_apply->addApplyInfo($data);		
		if($state) {
			// 发送买家消息
            $param = array();
            $param['code'] = 'apply_extension';
            $param['member_id'] = $this->member_info['member_id'];
            $param['param'] = array(
                'store_url' => urlShop('show_store', 'index', array('store_id' => $this->store_id)),
                'store_name' => $this->store_info['store_name'],
				'site_name' => C('site_name'),
				'mail_send_time'=>date("Y-m-d",time())
            );
            QueueClient::push('sendMemberMsg', $param);
			// 发送店铺消息
            $param = array();
			if ($ai_type!=3){
		        $param['code'] = 'new_apply_extension';
			    $param['store_id'] = $ai_target;
			    $param['param'] = array();
			    QueueClient::push('sendStoreMsg', $param);
			}else{
				$param = array();
                $param['code'] = 'new_apply_extension';
                $param['member_id'] = $ai_target;
                $param['param'] = array();
                QueueClient::push('sendMemberMsg', $param);
			}
			output_data(array('msg'=>'推广申请提交成功！请等待管理员审核。'));
		} else {
			output_error('推广申请提交失败!');
		};
	}
	
	/**
	 * 检查会员是否登录
	 */
	public function check_member_isLogin(){
		$key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
		$model_mb_user_token = Model('mb_user_token');
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if(empty($mb_user_token_info)) {
            output_error('请登录', array('login' => '0'));
        }
        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);
        $this->member_info['client_type'] = $mb_user_token_info['client_type'];
		if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
        } else {
            //读取卖家信息
            $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
			if(!empty($seller_info)) {
                $this->member_info['myself_store_id'] = $seller_info['store_id'];
				$store_info = Model('store')->getStoreInfoByID($seller_info['store_id']);
				if(!empty($store_info)) {
			        $this->member_info['mystore_parent_id'] = $store_info['parent_id'];
				}
			}
        }
	}
	
	/**
	 * 检查并输出店铺可申请推广类型
	 */
	public function get_StoreAi_Type(){
		//是否有推广员推荐 - 推广处理
		$ai_type = 1;
		if ($this->store_info['promotion_apply']==1){
		  $extension_id=cookie('iMall_extension');			
		  if (!empty($extension_id)){		
			$extension_id=urlsafe_b64decode($extension_id);
			$promotionLevel = Model('extension')->GetPromotionLevel($extension_id);
			if ($promotionLevel!=0 && $promotionLevel<$this->store_info['promotion_level']){
			  $ai_type = 3;//下级推广员
			}
		  }
		}
		return $ai_type;
	}
}