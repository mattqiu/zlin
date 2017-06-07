<?php
/**
 * 推广员管理
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class seller_promotionControl extends BaseExtensionControl {
    protected $promotion_count = '';
	protected $appay_count = '';
	
    public function __construct() {
    	parent::__construct() ;
    	Language::read('member_layout');
		
		if ($this->store_info['promotion_count']<=0){
			$this->promotion_count = '';
		}else{
			$this->promotion_count = '('.$this->store_info['promotion_count'].')';
		}
		
		$this->appay_count = Model('apply_information')->getApplyCount(array('ai_target'=>$_SESSION['store_id'],'ai_type'=>2,'ai_dispose'=>0));
		if ($this->appay_count<=0){
			$this->appay_count = '';
		}else{
			$this->appay_count = '('.$this->appay_count.')';
		}
		
    }
	
	/**
	 * 推广员列表
	 *
	 */
    public function promotion_listOp() {
		$fields = 'member_id,member_name,parent_id,parent_tree,mc_id';
        $model_promotion = Model('extension');
		
		//父ID
		$parent_id = $_GET['parent_id']?intval($_GET['parent_id']):0;
		$curr_deep = $_GET['deep']?intval($_GET['deep']):0;
		
		$condition=array();
		if ($parent_id !=0){		
		    $condition['parent_id'] = $parent_id;
		}
		$promotion_list = $model_promotion->getPromotionList($_SESSION['store_id'],$condition,$fields,20,'mc_id desc,ext_id asc',$curr_deep);	
		
		if ($_GET['ajax'] == '1'){
			//转码
			if (strtoupper(CHARSET) == 'GBK'){
				$promotion_list = Language::getUTF8($promotion_list);
			}
			$output = json_encode($promotion_list);
			print_r($output);
			exit;
		}else {
		    Tpl::output('promotion_list',$promotion_list);
		    Tpl::output('page',$model_promotion->showpage());									
			
			self::profile_menu('list');
            Tpl::showpage('seller_promotion.list');
		}		
    } 	
    /**
	 * 推广员信息
	 */
    public function promotion_infoOp(){		
		$promotion_id = intval($_GET["promotion_id"]);
		if (empty($promotion_id) || ($promotion_id<=0)){
			showDialog('非法参数');
		}

		$model_promotion = Model('extension');
		$promotion_info = $model_promotion->getPromotionDetailInfo($promotion_id);		
		
		Tpl::output('promotion_info',$promotion_info);
		Tpl::showpage('seller_promotion.info','null_layout');
	}
	
	/**
	 * 添加推广员
	 */
	public function promotion_addOp(){
		if (!empty($this->store_info['promotion_limit'])&&$this->store_info['promotion_count'] >= $this->store_info['promotion_limit']){
			showMessage('您的店铺推广员已满！', urlShop('seller_promotion','promotion_list'), 'msg_dialog', 'error');
		}
		
		$model_promotion = Model('extension');		
		
        $parent_id = $_GET['parent_id'];		
		$condition=array();
		$condition['mc_id'] = array('lt',10);
		$condition['store_id'] = $_SESSION['store_id'];
		if (isset($parent_id)){		  
		    $condition['member_id'] = intval($parent_id);
		    Tpl::output('parent_id',$parent_id);
		}else{
		    $condition['parent_id'] = 0;
		}
		$promotion_list = $model_promotion->getPromotionList($_SESSION['store_id'],$condition,'member_id,parent_id,member_name');
		Tpl::output('promotion_list',$promotion_list);
		
		self::profile_menu('list');
		Tpl::showpage('seller_promotion.add','null_layout');
	}
	
	/**
	 * 编辑
	 */
	public function promotion_editOp(){
		$model_promotion = Model('member');
        
		$condition=array();
		$condition['mc_id'] = 2;
		$condition['store_id'] = $_SESSION['store_id'];
		$condition['member_id'] = intval($_GET["promotion_id"]);
		$promotioninfo = $model_promotion->getMemberInfo($condition);
		Tpl::output('promotion_info',$promotioninfo);
		
		self::profile_menu('list');
		Tpl::showpage('seller_promotion.edit','null_layout');
	}
	/**
	 * 保存
	 *
	 * @param 
	 * @return 
	 */
	public function promotion_saveOp() {
				
		$data=array();
		$data['member_name']    = $_POST['member_name'];			
		$data['member_truename']= $_POST['member_truename'];
		$data['member_sex']     = $_POST['member_sex'];		
		$data['member_qq']      = $_POST['member_qq'];
		$data['member_email']   = $_POST['member_email'];		
		$data['member_mobile']  = $_POST['member_mobile'];
		$data['mc_id']          = 2; //推广员
		$data['store_id']       = $_SESSION['store_id']; //推广员隶属店铺
			
		// 验证手机号是否正确
		if (isset($data['member_mobile'])){
			if (!CheckMobileValidator(trim($data['member_mobile']))){
				showDialog('手机号填写不正确');
				exit;
			}
		}  
		     
		$model_member	= Model('member');	
		if($_POST['member_id'] == '') {				
		    //验证密码与确认密码
		    if ($_POST['password']!=$_POST['password_confirm']){
				showDialog('密码与确认密码不相同');
				exit;
			}			
		    // 验证用户名是否重复
		    $check_member_name	= $model_member->infoMember(array('member_name'=>trim($data['member_name'])));
		    if(is_array($check_member_name) and count($check_member_name) > 0) {
                showDialog('用户名已存在');
			    exit;
		    }
            // 验证邮箱是否重复
		    $check_member_email	= $model_member->infoMember(array('member_email'=>trim($data['member_email'])));
		    if(is_array($check_member_email) and count($check_member_email)>0) {
                showDialog('邮箱已存在');
			    exit;
		    }
			$data['member_passwd']=$_POST['password'];
			$data['weixin_invitecode']=randStr(6);
				
			$insert_id = $model_member->addMember($data,0);
			if($insert_id) {
				//添加推广员
			    $model_promotion	= Model('extension');		
			    
				$parent_id = intval($_POST['parent_id']);
		        if ($parent_id>0){
			        $parent_info = $model_promotion->getPromotionInfo($_SESSION['store_id'],$parent_id);
			        $parent_id = $parent_info['member_id'];
			        if (empty($parent_info['parent_tree']) || $parent_info['parent_tree']=='' || $parent_info['parent_tree']=='0'){				
			            $parent_tree = '|'.$parent_id.'|';
						$mc_level = 2;
			        }else{
			            //生成层级数
			            $parent_str  = trim($parent_info['parent_tree'],'|');						
			            $parent_list = explode('|',$parent_str);						
			            if (count($parent_list) > ($this->store_info['promotion_level']-2)){							
				            unset($parent_list[0]);							
				            $parent_tree = '|';
				            foreach($parent_list as $v){
					            $parent_tree .= $v . '|';
				            }
				            $parent_tree .= $parent_id.'|';
			            }else{
				            $parent_tree = '|'.$parent_str.'|'.$parent_id.'|';
			            }
						$mc_level = $parent_info['mc_level']+1;  
			        }
					//计算身份类型
					$holder_id = $parent_info['holder_id'];
					$ceo_id = $parent_info['ceo_id'];
					$coo_id = $parent_info['coo_id'];
					$manager_id = $parent_info['manager_id'];							
				    $mc_id = 1;
		        }else{
			        $parent_id = 0;
			        $parent_tree = '';
			        $mc_id = 1;
					$holder_id = 0;
					$ceo_id = 0;
					$coo_id = 0;
					$manager_id = 0;
					$mc_level = 1;					
		        }
		        $promotion = array();
				$promotion['member_id']  = $insert_id;
				$promotion['member_name']= $_POST['member_name'];				
				$promotion['mc_id']      = $mc_id;	//推广员类型:1 普通推广员 2经理 3协理 4首席 5股东 10 导购员
				$promotion['mc_level']   = $mc_level;
		        $promotion['store_id']   = $_SESSION['store_id'];
		        $promotion['parent_id']  = $parent_id;		
		        $promotion['parent_tree']= $parent_tree;				
				$promotion['holder_id']= $holder_id; //股东
				$promotion['ceo_id']= $ceo_id; //首席
				$promotion['coo_id']= $coo_id; //协理
				$promotion['manager_id']= $manager_id; //经理
				$promotion['commis_balance']= 0; //佣金余额
				$promotion['commis_totals']= 0; //累积佣金
				$promotion['createdtime']= TIMESTAMP; //加入时间
				
				$model_promotion->addExtension($promotion);		
			
				showDialog('添加成功',urlShop('seller_promotion', 'promotion_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('添加失败');
			}
		} else {
			//验证密码与确认密码
		    if (isset($_POST['password']) && !empty($_POST['password'])){
				$data['member_passwd']=md5(trim($_POST['password']));
			}			
		    // 验证用户名是否重复
		    $check_member_name	= $model_member->infoMember(array('member_name'=>trim($data['member_name']),'member_id'=>array('neq',intval($_POST['member_id']))));
		    if(is_array($check_member_name) and count($check_member_name) > 0) {
                showDialog('用户名已存在');
			    exit;
		    }
            // 验证邮箱是否重复
		    $check_member_email	= $model_member->infoMember(array('member_email'=>trim($data['member_email']),'member_id'=>array('neq',intval($_POST['member_id']))));
		    if(is_array($check_member_email) and count($check_member_email)>0) {
                showDialog('邮箱已存在');
			    exit;
		    }
			
			$where=array();
			$where['member_id']=intval($_POST['member_id']);
			
			$state = $model_member->where($where)->update($data);
			if($state) {
				$promotion = array();				
				$promotion['member_name']= $_POST['member_name'];
				
				$where=array();
			    $where['member_id']=intval($_POST['member_id']);
				$where['mc_id'] = array('lt',10);
		        $where['store_id'] = $_SESSION['store_id'];
				
				Model('extension')->editExtension($promotion,$where);
				
				showDialog('修改成功',urlShop('seller_promotion', 'promotion_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		}
	}
	
	/**
	 * 抽佣明细
	 *
	 */
    public function promotion_detailOP() {
		$model_detail = Model('extension_commis_detail');
		$promotion_id	 = $_REQUEST['promotion_id'];
		$promotion_info=Model('member')->getMemberInfo(array('member_id'=>$promotion_id),'member_name,member_truename');
		$promotion_name = $promotion_info['member_name'];
		
		$request_date_str = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to']);
		
		$condition = array();
		$condition['store_id'] = $_SESSION['store_id'];		
		if ($request_date_str != ''){			
			$args = explode(',',$request_date_str);
			$condition['add_date'] = array('in',$args);
		}
		$condition['saleman_type'] = 2;

		if (!empty($_GET['give_status']) && $_GET['give_status']<2){			
			$condition['give_status'] = $_GET['give_status'];
		}
		
		if($promotion_id != ''){
			$condition['saleman_id'] = $promotion_id;
			Tpl::output('promotion_id',$promotion_id);
		}
		
		$detail_list = $model_detail->getCommisdetailList($condition,20);		
		Tpl::output('detail_list',$detail_list);
		Tpl::output('show_page',$model_detail->showpage());
		
		self::profile_menu('detail',$promotion_name);
        Tpl::showpage('seller_promotion.detail');
    }    

	/**
	 * 删除
	 *
	 * @param 
	 * @return 
	 */
	public function promotion_delOp() {
		$model_promotion	= Model('extension');		
	
		if($_GET['promotion_id'] != '') {
			$child_count = $model_promotion->countPromotionChild($_SESSION['store_id'],$_GET['promotion_id']);
			if ($child_count>0){
				showDialog('该推广员有下线，请先删除下线！');
			}
			$promotion_id = intval($_GET['promotion_id']);
			$state = $model_promotion->ClearExtensionInfo($promotion_id);
			if($state) {
				showDialog('删除成功',urlShop('seller_promotion', 'promotion_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlShop('seller_promotion', 'promotion_list'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
	
	/**
	 * 开通实体门店
	 *
	 * @param 
	 * @return 
	 */
	public function promotion_openrealOp() {
		$model_promotion	= Model('extension');		
		if($_GET['promotion_id'] != '') {			
			$promotion_id = intval($_GET['promotion_id']);
			
			$promotion = array();				
			$promotion['mc_real']= 10;
				
			$where=array();
			$where['member_id']=$promotion_id;
			$where['mc_id'] = array('lt',10);
		    $where['store_id'] = GENERAL_PLATFORM_EXTENSION_ID;
				
			$state = Model('extension')->editExtension($promotion,$where);

			if($state) {
				showDialog('推广员开通门店成功!',urlShop('seller_promotion', 'promotion_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('开通门店失败');
			}
		} else {
			showDialog('非法操作',urlShop('seller_promotion', 'promotion_list'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
	
	/**
	 * 关闭实体门店
	 *
	 * @param 
	 * @return 
	 */
	public function promotion_closerealOp() {
		$model_promotion	= Model('extension');		
		if($_GET['promotion_id'] != '') {			
			$promotion_id = intval($_GET['promotion_id']);
			
			$promotion = array();				
			$promotion['mc_real']= 1;
				
			$where=array();
			$where['member_id']=$promotion_id;
			$where['mc_id'] = array('lt',10);
		    $where['store_id'] = GENERAL_PLATFORM_EXTENSION_ID;
				
			$state = Model('extension')->editExtension($promotion,$where);

			if($state) {
				showDialog('关闭推广员门店成功!',urlShop('seller_promotion', 'promotion_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('关闭门店失败');
			}
		} else {
			showDialog('非法操作',urlShop('seller_promotion', 'promotion_list'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
	
	/**
	 * 推广员申请列表
	 *
	 */
    public function apply_listOp() {
        $model_apply = Model('apply_information');
		
		$apply_list = $model_apply->getPromotionApplyList($_SESSION['store_id']);		
		
		Tpl::output('apply_list',$apply_list);
		Tpl::output('page',$model_apply->showpage());
			
	    self::profile_menu('apply');
        Tpl::showpage('seller_promotion_apply.list');
    }
	
	/**
	 * 推广员申请信息
	 *
	 */
    public function apply_infoOp() {
		$ai_id = $_GET['id'];
		
        $model_apply = Model('apply_information');
		
		$apply_info = $model_apply->getApplyInfo($ai_id);		
		Tpl::output('apply_info',$apply_info);
			
        Tpl::showpage('seller_apply_infomation','null_layout');
    }
	
	/**
	 * 推广员申请信息编辑
	 *
	 */
    public function apply_editOp() {
        $ai_id = $_GET['id'];
		
        $model_apply = Model('apply_information');
		
		$apply_info = $model_apply->getApplyInfo($ai_id);		
		Tpl::output('apply_info',$apply_info);
		
		$condition=array();
		$condition['mc_id'] = array(array('gt',1),array('lt',10),'and');
		$condition['store_id'] = $_SESSION['store_id'];
		$promotion_list = Model('extension')->getExtensionList($condition,'member_id,member_name,mc_id');
		Tpl::output('promotion_list',$promotion_list);

        Tpl::showpage('seller_promotion_apply.edit','null_layout');
    }
	
	/**
	 * 推广员申请信息保存
	 *
	 */
    public function apply_saveOp() {
        $ai_id = $_POST['id'];
		$verify = $_POST['verify'];
		if (empty($ai_id) || $ai_id<=0 || $verify<0 || $verify>2){
			showDialog('参数错误!',urlShop('seller_promotion', 'apply_list'));
		}		
		
        $model_apply = Model('apply_information');
		$MemberMsg = 'apply_extension_failed';
		
		$apply_info = $model_apply->getApplyInfo($ai_id);
		$member_id = $apply_info['ai_from'];
		if ($verify==2){
			if (!empty($this->store_info['promotion_limit'])&&$this->store_info['promotion_count'] >= $this->store_info['promotion_limit']){
				showDialog('您的店铺推广员已满，审核失败！',urlShop('seller_promotion', 'apply_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		    }
			$model_member = Model('member');
		    $member_org = $model_member->getMemberInfoByID($member_id,'member_id,member_name,mc_id');
			if (empty($member_org) || empty($member_org['member_id']) || $member_org['mc_id']==2){
				showDialog('无效的会员信息！',urlShop('seller_promotion', 'apply_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			}
			
			//添加推广员
			$model_promotion	= Model('extension');		
			    
			$parent_id = intval($_POST['parent_id']);
		    if ($parent_id>0){
			    $parent_info = $model_promotion->getPromotionInfo($_SESSION['store_id'],$parent_id);
			    $parent_id = $parent_info['member_id'];
			    if (empty($parent_info['parent_tree']) || $parent_info['parent_tree']=='' || $parent_info['parent_tree']=='0'){				
			        $parent_tree = '|'.$parent_id.'|';
					$mc_level = 2;
			    }else{
			        //生成层级数
			        $parent_str  = trim($parent_info['parent_tree'],'|');						
			        $parent_list = explode('|',$parent_str);						
			        if (count($parent_list) > ($this->store_info['promotion_level']-2)){							
				        unset($parent_list[0]);							
				        $parent_tree = '|';
				        foreach($parent_list as $v){
					        $parent_tree .= $v . '|';
				        }
				        $parent_tree .= $parent_id.'|';
			        }else{
				        $parent_tree = '|'.$parent_str.'|'.$parent_id.'|';
			        } 
					$mc_level = $parent_info['mc_level']+1; 
			    }
				//计算身份类型
				$holder_id = $parent_info['holder_id'];
			    $ceo_id = $parent_info['ceo_id'];
			    $coo_id = $parent_info['coo_id'];
			    $manager_id = $parent_info['manager_id'];							
			    $mc_id = 1;
		    }else{
			    $parent_id = 0;
			    $parent_tree = '';
			    $mc_id = 1;
			    $holder_id = 0;
				$ceo_id = 0;
				$coo_id = 0;
				$manager_id = 0;
				$mc_level = 1;				
		    }
		    $promotion = array();
			$promotion['member_id']  = $member_org['member_id'];
			$promotion['member_name']= $member_org['member_name'];			
			$promotion['mc_id']      = $mc_id;	//推广员类型:1 普通推广员 2经理 3协理 4首席 5股东 10 导购员
			$promotion['mc_level']   = $mc_level;
			$promotion['mc_real']    = 1; //推广员是否有实体店:1 无实体店 10 有实体店
		    $promotion['store_id']   = $_SESSION['store_id'];
		    $promotion['parent_id']  = $parent_id;		
		    $promotion['parent_tree']= $parent_tree;				
			$promotion['holder_id']= $holder_id; //股东
			$promotion['ceo_id']= $ceo_id; //首席
			$promotion['coo_id']= $coo_id; //协理
			$promotion['manager_id']= $manager_id; //经理
			$promotion['commis_balance']= 0; //本期佣金
			$promotion['manageaward_balance']= 0; //本期高管奖
			$promotion['perforaward_balance']= 0; //本期绩优奖
			$promotion['commis_totals']= 0; //累积佣金
			$promotion['createdtime']= TIMESTAMP; //加入时间
				
			$states = $model_promotion->addExtension($promotion);
		    if ($states){
			    $MemberMsg = 'apply_extension_success';
		        $mc_id = ($apply_info['ai_type']==2)?2:1;
		
		        $member_info = array();
		        $member_info['mc_id'] = $mc_id;
		        $member_info['store_id'] = $_SESSION['store_id'];
		        $model_member->editMember(array('member_id'=>$member_id),$member_info);
			}else{
				showDialog('系统错误，请稍候再试!');
			}			
		}
		
		$apply_info = array();
		$apply_info['ai_dispose'] = $verify;
		$apply_info['ai_replyinfo'] = $_POST['ai_replyinfo'];
		$apply_info['ai_distime'] = time();
		
		$model_apply->editApplyInfo($apply_info,array('ai_id'=>$ai_id));
		
		// 发送买家消息
        $param = array();
        $param['code'] = $MemberMsg;  
        $param['member_id'] = $member_id;
        $param['param'] = array(
            'store_url' => urlShop('show_store', 'index', array('store_id' => $_SESSION['store_id'])),
            'store_name' => $_SESSION['store_name'],
		    'site_name' => C('site_name'),
			'replyinfo' => $_POST['ai_replyinfo'],
			'mail_send_time'=>date("Y-m-d",time())
        );
        QueueClient::push('sendMemberMsg', $param);

        showDialog('审核操作成功!',urlShop('seller_promotion', 'apply_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');		
    }
	
	/**
	 * 删除推广员申请信息
	 *
	 */
    public function apply_delOp() {
		$ai_id = $_GET['id'];
		if (empty($ai_id) || $ai_id<=0){
			showDialog('参数错误!',urlShop('seller_promotion', 'apply_list'));
		}
		Model('apply_information')->delApplyInfo(array('ai_id'=>$ai_id));
		showDialog('申请信息删除成功!',urlShop('seller_promotion', 'apply_list'));
	}	
	
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_key='', $promotion_name='') {
        $menu_array	= array();
		$menu_array[1]=array('menu_key'=>'list','menu_name'=>'推广员管理'.$this->promotion_count,	'menu_url'=>'index.php?act=seller_promotion&op=promotion_list');
		$menu_array[2]=array('menu_key'=>'apply','menu_name'=>'推广申请'.$this->appay_count,	'menu_url'=>'index.php?act=seller_promotion&op=apply_list');
		if ($menu_key=='detail'){
		  $menu_array[3]=array('menu_key'=>'detail','menu_name'=>$promotion_name.'业绩明细',	'menu_url'=>'index.php?act=seller_promotion&op=promotion_detail');
		}
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}