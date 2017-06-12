<?php
/**
 * 导购员管理
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class seller_salemanControl extends BaseExtensionControl {
	protected $promotion_count = '';
	protected $appay_count = '';

    public function __construct() {
    	parent::__construct() ;
    	Language::read('member_layout');
		
		if ($this->store_info['saleman_count']<=0){
			$this->saleman_count = '';
		}else{
			$this->saleman_count = '('.$this->store_info['saleman_count'].')';
		}
		
		$this->appay_count = Model('apply_information')->getApplyCount(array('ai_target'=>$_SESSION['store_id'],'ai_type'=>1,'ai_dispose'=>0));
		if ($this->appay_count<=0){
			$this->appay_count = '';
		}else{
			$this->appay_count = '('.$this->appay_count.')';
		}
    }	
	/**
	 * 导购员列表
	 *
	 */
    public function saleman_listOp() {
        $model_saleman = Model('extension');
		
		$condition=array();
		$saleman_list = $model_saleman->getSaleManList($_SESSION['store_id'],20);		
		Tpl::output('saleman_list',$saleman_list);
		Tpl::output('page',$model_saleman->showpage());
		
		self::profile_menu('list');
        Tpl::showpage('seller_saleman.list');
    }   
	
	/**
	 * 导购员信息
	 */
    public function saleman_infoOp(){		
		$saleman_id = intval($_GET["saleman_id"]);
		if (empty($saleman_id) || ($saleman_id<=0)){
			showDialog('非法参数');
		}

		$model_saleman = Model('extension');
		$saleman_info = $model_saleman->getSaleManDetailInfo($_SESSION['store_id'],$saleman_id);		
		
		Tpl::output('saleman_info',$saleman_info);
		Tpl::showpage('seller_saleman.info','null_layout');
	} 

	/**
	 * 编辑
	 */
	public function saleman_editOp(){	
		if (!isset($_GET["saleman_id"])){
		  if (!empty($this->store_info['saleman_limit'])&&$this->store_info['saleman_count'] >= $this->store_info['saleman_limit']){
			showMessage('您的店铺导购员已满！', urlShop('seller_saleman','saleman_list'), 'msg_dialog', 'error');
		  }
		}
		
		$model_saleman = Model('member');
        
		$condition=array();
		$condition['mc_id'] = 1;
		$condition['store_id'] = $_SESSION['store_id'];
		$condition['member_id'] = intval($_GET["saleman_id"]);
		$salemaninfo = $model_saleman->getMemberInfo($condition);
		
		Tpl::output('saleman_info',$salemaninfo);
		
		self::profile_menu('list');		
		Tpl::showpage('seller_saleman.edit','null_layout');
	}
	/**
	 * 保存
	 *
	 * @param 
	 * @return 
	 */
	public function saleman_saveOp() {		
		
		$data=array();
		$data['member_name']    = $_POST['member_name'];			
		$data['member_truename']= $_POST['member_truename'];
		$data['member_sex']     = $_POST['member_sex'];		
		$data['member_qq']      = $_POST['member_qq'];
		$data['member_email']   = $_POST['member_email'];		
		$data['member_mobile']  = $_POST['member_mobile'];
		$data['mc_id']          = 1; //会员类型:0表示普通会员,1表示导购员,2表示推广员
		$data['store_id']       = $_SESSION['store_id']; //导购员隶属店铺		
	
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
			    $model_saleman	= Model('extension');			    

		        $saleman = array();
				$saleman['member_id']  = $insert_id;
				$saleman['member_name']= $_POST['member_name'];				
				$saleman['mc_id']      = 10;	//推广员类型:1 普通推广员 2经理 3协理 4首席 5股东 10 导购员
				$promotion['mc_level'] = 0;
		        $saleman['store_id']   = $_SESSION['store_id'];
		        $saleman['parent_id']  = '';		
		        $saleman['parent_tree']= '|'.$saleman['store_id'].'|';				
				$saleman['holder_id']= 0; //股东
				$saleman['ceo_id']= 0; //首席
				$saleman['coo_id']= 0; //协理
				$saleman['manager_id']= 0; //经理
				$saleman['commis_balance']= 0; //佣金余额
				$saleman['commis_totals']= 0; //累积佣金
				$saleman['createdtime']= TIMESTAMP; //加入时间
				
				$model_saleman->addExtension($saleman);
			
				showDialog('添加成功',urlShop('seller_saleman', 'saleman_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
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
				$saleman = array();				
				$saleman['member_name']= $_POST['member_name'];
				
				$where=array();
			    $where['member_id']=intval($_POST['member_id']);
				$where['mc_id'] = 10;
		        $where['store_id'] = $_SESSION['store_id'];
				
				Model('extension')->editExtension($saleman,$where);
				
				showDialog('修改成功',urlShop('seller_saleman', 'saleman_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		}
	}
	
	/**
	 * 抽佣明细
	 *
	 */
    public function saleman_detailOP() {
		$model_detail = Model('extension_commis_detail');
		$saleman_id	 = $_REQUEST['saleman_id'];
		$saleman_info=Model('member')->getMemberInfo(array('member_id'=>$saleman_id),'member_name,member_truename');
		$saleman_name = $saleman_info['member_name'];
		
		$request_date_str = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to']);
		
		$condition = array();
		$condition['store_id'] = $_SESSION['store_id'];		
		if ($request_date_str != ''){			
			$args = explode(',',$request_date_str);
			$condition['add_date'] = array('in',$args);
		}
		$condition['saleman_type'] = 1;

		if (!empty($_GET['give_status']) && $_GET['give_status']<2){			
			$condition['give_status'] = $_GET['give_status'];
		}
		
		if($saleman_id != ''){
			$condition['saleman_id'] = $saleman_id;
			Tpl::output('saleman_id',$saleman_id);
		}
		
		$detail_list = $model_detail->getCommisdetailList($condition,20);		
		Tpl::output('detail_list',$detail_list);
		Tpl::output('show_page',$model_detail->showpage());
		
		self::profile_menu('detail',$saleman_name);
        Tpl::showpage('seller_saleman.detail');
    }    
	/**
	 * 删除
	 *
	 * @param 
	 * @return 
	 */
	public function saleman_delOp() {
		$model_saleman	= Model('extension');		
	
		if($_GET['saleman_id'] != '') {
			$saleman_id = intval($_GET['saleman_id']);
			$state = $model_saleman->ClearExtensionInfo($saleman_id);
			if($state) {
				showDialog('删除成功',urlShop('seller_saleman', 'saleman_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('删除失败');
			}
		} else {
			showDialog('非法操作',urlShop('seller_saleman', 'saleman_list'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
	
	/**
	 * 导购员申请列表
	 *
	 */
    public function apply_listOp() {
        $model_apply = Model('apply_information');
		
		$apply_list = $model_apply->getSalemanApplyList($_SESSION['store_id']);		
		
		Tpl::output('apply_list',$apply_list);
		Tpl::output('page',$model_apply->showpage());
			
	    self::profile_menu('apply');
        Tpl::showpage('seller_saleman_apply.list');
    }
	
	/**
	 * 导购员申请信息
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
	 * 导购员申请信息编辑
	 *
	 */
    public function apply_editOp() {
        $ai_id = $_GET['id'];
		
        $model_apply = Model('apply_information');
		
		$apply_info = $model_apply->getApplyInfo($ai_id);		
		Tpl::output('apply_info',$apply_info);

        Tpl::showpage('seller_saleman_apply.edit','null_layout');
    }
	
	/**
	 * 导购员申请信息保存
	 *
	 */
    public function apply_saveOp() {
        $ai_id = $_POST['id'];
		$verify = $_POST['verify'];
		if (empty($ai_id) || $ai_id<=0 || $verify<0 || $verify>2){
			showDialog('参数错误!',urlShop('seller_saleman', 'apply_list'));
		}		
		
        $model_apply = Model('apply_information');
		$MemberMsg = 'apply_extension_failed';
		
		$apply_info = $model_apply->getApplyInfo($ai_id);
		$member_id = $apply_info['ai_from'];
		if ($verify==2){
			if (!empty($this->store_info['saleman_limit'])&&$this->store_info['saleman_count'] >= $this->store_info['saleman_limit']){
				showDialog('您的店铺导购员已满，审核失败！',urlShop('seller_saleman', 'apply_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		    }	
			
			$model_member = Model('member');
		    $member_org = $model_member->getMemberInfoByID($member_id,'member_id,member_name,mc_id');
			if (empty($member_org) || empty($member_org['member_id']) || $member_org['mc_id']==1){
				showDialog('无效的会员信息！',urlShop('seller_saleman', 'apply_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			}	
			
			//添加推广员
			$model_saleman	= Model('extension');			    

		    $saleman = array();
	        $saleman['member_id']  = $member_org['member_id'];
			$saleman['member_name']= $member_org['member_name'];			
			$saleman['mc_id']      = 10;	//推广员类型:1 普通推广员 2经理 3协理 4首席 5股东 10 导购员
			$promotion['mc_level'] = 0;
		    $saleman['store_id']   = $_SESSION['store_id'];
		    $saleman['parent_id']  = '';		
		    $saleman['parent_tree']= '|'.$saleman['store_id'].'|';				
		    $saleman['holder_id']= 0; //股东
			$saleman['ceo_id']= 0; //首席
			$saleman['coo_id']= 0; //协理
			$saleman['manager_id']= 0; //经理
			$saleman['commis_balance']= 0; //佣金余额
			$saleman['commis_totals']= 0; //累积佣金
			$saleman['createdtime']= TIMESTAMP; //加入时间
				
			$states = $model_saleman->addExtension($saleman);
			if ($states){  
			    $MemberMsg = 'apply_extension_success';
		        $mc_id = ($apply_info['ai_type']==2)?2:1;
		
		        $member_info = array();
		        $member_info['mc_id'] = $mc_id;
		        $member_info['store_id'] = $_SESSION['store_id'];
		        $model_member->editMember(array('member_id'=>$member_id),$member_info);
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

        showDialog('审核操作成功!',urlShop('seller_saleman', 'apply_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
    }
	
	/**
	 * 删除导购员申请信息
	 *
	 */
    public function apply_delOp() {
		$ai_id = $_GET['id'];
		if (empty($ai_id) || $ai_id<=0){
			showDialog('参数错误!',urlShop('seller_saleman', 'apply_list'));
		}
		Model('apply_information')->delApplyInfo(array('ai_id'=>$ai_id));
		showDialog('申请信息删除成功!',urlShop('seller_saleman', 'apply_list'));
	}	
	
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_key='',$saleman_name='') {
        $menu_array	= array();
		$menu_array[1]=array('menu_key'=>'list','menu_name'=>'导购员管理'.$this->saleman_count,	'menu_url'=>'index.php?act=seller_saleman&op=saleman_list');
		$menu_array[2]=array('menu_key'=>'apply','menu_name'=>'导购申请'.$this->appay_count,	'menu_url'=>'index.php?act=seller_saleman&op=apply_list');
		if ($menu_key=='detail'){
		  $menu_array[3]=array('menu_key'=>'detail','menu_name'=>$saleman_name.'业绩明细',	'menu_url'=>'index.php?act=seller_saleman&op=saleman_detail');
		}
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
    
    /**
     * 会员列表
     *
     */
    public function seller_memberOp() {
    	$model_saleman = Model('extension');
    
    	$condition=array();
    	$saleman_list = $model_saleman->getMemberList($_SESSION['store_id'],20);
    	Tpl::output('saleman_list',$saleman_list);
    	Tpl::output('page',$model_saleman->showpage());
    
    	Tpl::showpage('seller_member.list');
    }
}