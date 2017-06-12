<?php
/**
 * 店铺申请信息提交
 *
 * 
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class store_addapplyControl extends BaseStoreControl{

	public function __construct() {
	    parent::__construct();
	}
	
	
	/**
	 * 申请加盟
	 */
	public function apply_joinOp(){
		//会员验证
        $this->checkLogin();
		
		if (empty($_SESSION['store_id']) || $_SESSION['store_id']<0){
			showDialog('您还未开通店铺，请先开通平台店铺，再申请加盟！',urlShop('show_joinin','index'));
		}
		
		//检查店铺分店是否达到上限
		if ($this->store_info['branch_apply']==0){
			showDialog('加盟店已满，如有疑问请与店铺联系！');
		}
		
		//检查是否已经加盟
		if (!empty($_SESSION['S_parent_id']) && $_SESSION['S_parent_id']>0){
			showDialog('您已经加盟了其它的店铺！');
		}
		
		if (!empty($_SESSION['store_id']) && $_SESSION['store_id']>0){
		  //检查申请的店铺是否自己的店铺是同一店铺
		  if ($_SESSION['store_id']==$this->store_id){
			 showDialog('不能申请加盟自己的店铺！');
		  }
		  //检查是否已经是加盟店铺了
          $store_info = Model('store')->getStoreInfoByID($_SESSION['store_id']);
		  if (!empty($store_info) && is_array($store_info)){
			  $parent_id = $store_info['parent_id'];
			  if ($parent_id>0){
				  showDialog('您已经加盟了其它的店铺！');
			  }
		  }
		}
		$apply_info = Model('member')->getMemberInfoByID($_SESSION['member_id']);
		Tpl::output('apply_info', $apply_info);
		
		$this->Get_StoreFavoritesInfo();		
		
		Tpl::showpage('store_apply.join');
	}
	
	/**
	 * 保存申请加盟
	 */
	public function apply_join_saveOp(){
		//会员验证
        $this->checkLogin();
		//检查店铺分店是否达到上限
		if ($this->store_info['branch_apply']==0){
			showDialog('加盟店已满，如有疑问请与店铺联系！');
		}
		
		$ai_addinfo = array();
		$ai_addinfo['truename'] = $_POST['truename'];
		$ai_addinfo['email'] = $_POST['email'];	
		$ai_addinfo['mobile'] = $_POST['mobile'];	
		$ai_addinfo['qq'] = $_POST['qq'];
		$ai_addinfo['areainfo'] = $_POST['areainfo'];
		$ai_addinfo['describe'] = $_POST['describe'];
		
		$data=array();
		$data['ai_from'] = $_SESSION['member_id'];	
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
            $param['member_id'] = $_SESSION['member_id'];
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
			
			showDialog('申请加盟提交成功！请等待店铺管理员审核。',urlShop('member', 'home', array('store_id'=>$this->store_id)),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		} else {
			showDialog('申请加盟提交失败!');
		};
	}
	
	/**
	 * 申请推广
	 */
	public function apply_extensionOp(){
		//检查店铺分店是否达到上限
		if ($this->store_info['promotion_apply']==0 && $this->store_info['saleman_apply']==0){
			showDialog('店铺未开通在线申请或申请人数已满，如有疑问请与店铺联系！');
		}
		
		if ($_SESSION['is_login'] !== '1'){
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
		$this->out_StoreAi_Type();		
		$this->Get_StoreFavoritesInfo();
					
		Tpl::showpage('store_register.extension');	
	}
	
	/**
	 * 申请推广
	 */
	public function apply_extension(){
		//检查是否已经是推广员
		if ($_SESSION['M_mc_id']==2){
			showDialog('您已经是推广员，不能再申请！');
		}
		
		if ($_SESSION['M_mc_id']==1 && $_SESSION['M_store_id']==$this->store_id){
		  showDialog('你已经是本店铺的导购员了！');
		}		
		
		$apply_info = Model('member')->getMemberInfoByID($_SESSION['member_id']);
		Tpl::output('apply_info', $apply_info);
		
		//是否有推广员推荐 - 推广处理
		$this->out_StoreAi_Type();		
		$this->Get_StoreFavoritesInfo();
						
		Tpl::showpage('store_apply.extension');		
	}
	
	/**
	 * 保存注册推广
	 */
	public function reg_extension_saveOp(){
		//检查店铺分店是否达到上限
		if ($this->store_info['promotion_apply']==0 && $this->store_info['saleman_apply']==0){
			showDialog('店铺未开通在线申请或申请人数已满，如有疑问请与店铺联系！');
		}		
		$ai_type = intval($_POST['ai_type']);
		if ($ai_type<1 || $ai_type>3){
			showDialog('非法操作！');
		}
		if ($this->store_info['promotion_apply']!=1 && ($ai_type==2 || $ai_type==3)){
			showDialog('店铺未开通推广员在线申请或人数已满，如有疑问请与店铺联系！');
		}
		if ($this->store_info['saleman_apply']!=1 && $ai_type==1){
			showDialog('店铺未开通导购员在线申请或人数已满，如有疑问请与店铺联系！');
		}
		//检查推广员申请条件
		if ($ai_type==2 || $ai_type==3){
			if ($this->store_info['promotion_require']>0){
				showDialog('申请本店推广员需先在本店消费满'.$this->store_info['promotion_require'].'元，请注册会员并在本店消费满：'.$this->store_info['promotion_require'].'元再申请本店推广！');
			}
		}else if ($ai_type==1){
			if ($this->store_info['saleman_require']>0){
				showDialog('申请本店导购员需先在本店消费满'.$this->store_info['saleman_require'].'元，请注册会员并在本店消费满：'.$this->store_info['saleman_require'].'元再申请本店推广！');
			}
		}
		
		//重复注册验证
		if (process::islock('reg')){
			showDialog(Language::get('im_common_op_repeat'));
		}
		Language::read("home_login_register");
		$lang	= Language::getLangContent();
		
		$model_member	= Model('member');
		$register_info = array();
        $register_info['member_name'] = $_POST['user_name'];
        $register_info['member_passwd'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['email'] = $_POST['email'];
		$register_info['mc_id'] = 2;
		$register_info['store_id'] = $this->store_id;
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $model_member->createSession($member_info,true);
			process::addprocess('reg');

			// cookie中的cart存入数据库
			Model('cart')->mergecart($member_info,$_SESSION['store_id']);

			// cookie中的浏览记录存入数据库
			Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);
			
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
			        $ai_target = $extension_id;
			        $ai_type = 3;//下级推广员			
		        }
		    }
		
		    $data=array();
		    $data['ai_from'] = $_SESSION['member_id'];	
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
                $param['member_id'] = $_SESSION['member_id'];
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
				$ref_url = (strstr($_POST['ref_url'],'logout')=== false && !empty($_POST['ref_url']) ? $_POST['ref_url'] : 'index.php?act=member_information&op=member');	
			    showDialog('推广注册提交成功！请等待管理员审核。',$ref_url,'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		    } else {
			    showDialog('推广注册提交失败!');
		    };			
        } else {
			showDialog($member_info['error']);
        }
	}
	
	/**
	 * 保存申请推广
	 */
	public function apply_extension_saveOp(){
		//会员验证
        $this->checkLogin();
		//检查店铺分店是否达到上限
		if ($this->store_info['promotion_apply']==0 && $this->store_info['saleman_apply']==0){
			showDialog('店铺未开通在线申请或申请人数已满，如有疑问请与店铺联系！');
		}		
		$ai_type = intval($_POST['ai_type']);
		if ($ai_type<1 || $ai_type>3){
			showDialog('非法操作！');
		}
		if ($this->store_info['promotion_apply']!=1 && ($ai_type==2 || $ai_type==3)){
			showDialog('店铺未开通推广员在线申请或人数已满，如有疑问请与店铺联系！');
		}
		if ($this->store_info['saleman_apply']!=1 && $ai_type==1){
			showDialog('店铺未开通导购员在线申请或人数已满，如有疑问请与店铺联系！');
		}
		//检查推广员申请条件
		if ($ai_type==2 || $ai_type==3){
			if ($this->store_info['promotion_require']>0){
				$ConsumeTotals = Model('extension')->GetMemberConsumeTotals($_SESSION['member_id'],$this->store_id);
				if ($ConsumeTotals<$this->store_info['promotion_require']){
					showDialog('申请本店推广员需先在本店消费满'.$this->store_info['promotion_require'].'元!您目前在本店的消费为：'.$ConsumeTotals.'元');
				}
			}
		}else if ($ai_type==1){
			if ($this->store_info['saleman_require']>0){
				$ConsumeTotals = Model('extension')->GetMemberConsumeTotals($_SESSION['member_id'],$this->store_id);
				if ($ConsumeTotals<$this->store_info['saleman_require']){
					showDialog('申请本店导购员需先在本店消费满'.$this->store_info['saleman_require'].'元!您目前在本店的消费为：'.$ConsumeTotals.'元');
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
			$ai_target = $extension_id;
			$ai_type = 3;//下级推广员		
		  }
		}
		
		$data=array();
		$data['ai_from'] = $_SESSION['member_id'];	
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
            $param['member_id'] = $_SESSION['member_id'];
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
			showDialog('推广申请提交成功！请等待管理员审核。',urlShop('member', 'home', array('store_id'=>$this->store_id)),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		} else {
			showDialog('推广申请提交失败!');
		};
	}	
	
	/**
	 * 检查并输出店铺可申请推广类型
	 */
	public function out_StoreAi_Type(){
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
		Tpl::output('ai_type', $ai_type);
	}
	
	/**
	 * 店铺收藏信息
	 */
	public function Get_StoreFavoritesInfo(){
		// 最多收藏的会员
		$favorites = Model('favorites')->getStoreFavoritesList(array('fav_id' => $this->store_id), '*', 0, 'fav_time desc', 8);
		if (!empty($favorites)) {
		    $memberid_array = array();
		    foreach ($favorites as $val) {
		        $memberid_array[] = $val['member_id'];
		    }
		    $favorites_list = Model('member')->getMemberList(array('member_id' => array('in', $memberid_array)), 'member_id,member_name,member_avatar');
		    Tpl::output('favorites_list', $favorites_list);
		}
	}

}