<?php
/**
 * 分店管理
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class seller_branchControl extends BaseBranchControl {
	protected $branch_count = '';
	protected $apply_count = '';

    public function __construct() {		
    	parent::__construct() ;		
    	Language::read('member_layout');
		
		//分店数
		$this->branch_count = Model('store')->getBranchCount($_SESSION['store_id']);
		if ($this->branch_count<=0){
			$this->branch_count = '';
		}else{
			$this->branch_count = '('.$this->branch_count.')';
		}
		//待处理加盟申请数
		$this->apply_count = Model('apply_information')->getApplyCount(array('ai_target'=>$_SESSION['store_id'],'ai_type'=>5,'ai_dispose'=>0));
		if ($this->apply_count<=0){
			$this->apply_count = '';
		}else{
			$this->apply_count = '('.$this->apply_count.')';
		}
    }	
	/**
	 * 分店列表
	 *
	 */
    public function branch_listOP() {
        $model_branch = Model('store');
		$branch_list = $model_branch->getBranchList($_SESSION['store_id'],10);	
		
		if (!empty($branch_list) && is_array($branch_list)) {
          foreach($branch_list as $k=>$v) {
			  $grade_info=Model('store_grade')->getOneGrade($v['grade_id']);
			  $branch_list[$k]['grade_name']=$grade_info['sg_name'];
			  
			  $member_info=Model('member')->getMemberInfo(array('member_id'=>$v['member_id']),'member_truename,member_mobile,member_qq');
			  $branch_list[$k]['member_truename']=$member_info['member_truename'];
			  $branch_list[$k]['member_mobile']=$member_info['member_mobile'];
			  $branch_list[$k]['member_qq']=$member_info['member_qq'];
		  }
		}	
		Tpl::output('branch_list',$branch_list);
		Tpl::output('page',$model_branch->showpage());		
	
		self::profile_menu('list');		
        Tpl::showpage('seller_branch.list');
    }	

	/**
	 * 编辑
	 */
	public function branch_editOp(){
		$model_branch = Model('store');
        $model_grade = Model('store_grade');		
		
		if (!empty($_GET["branch_id"])){
		  $condition=array();
		  $condition['store_id'] = intval($_GET["branch_id"]);
		  $branchinfo = $model_branch->getStoreInfo($condition);
		  
		  if (empty($branchinfo)){//修改分店信息
			showDialog('非法操作',urlShop('seller_branch', 'branch_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			exit;
		  }
		  $member_info=Model('member')->getMemberInfo(array('member_id'=>$branchinfo['member_id']),'member_truename,member_sex,member_mobile,member_qq,member_email');
		  $branchinfo['member_truename']=$member_info['member_truename'];
		  $branchinfo['member_sex']=$member_info['member_sex'];
		  $branchinfo['member_mobile']=$member_info['member_mobile'];
		  $branchinfo['member_qq']=$member_info['member_qq'];
		  $branchinfo['member_email']=$member_info['member_email'];
		}else{//添加分店
		  if ($this->store_info['branch_limit']>0){
			  $branch_count = $model_branch->getBranchCount($_SESSION['store_id']);
			  if ($branch_count>=$this->store_info['branch_limit']){
				showMessage('你只能开'.$this->store_info['branch_limit'].'家分店，如有疑问请与平台联系！',urlShop('seller_branch', 'branch_list'),'msg_dialog','error');
			    exit;
			  }
		  }
		  
		}
		// 会员级别
		$grade_filter= array();
		$grade_filter['store_type'] = 1;
		$sort=$model_grade->getOneGrade($_SESSION['grade_id']);
		if (!empty($sort)){
		    $grade_filter['sg_sort'] = array('lt', $sort['sg_sort']);
		}
		$grade_list = $model_grade->getGradeList($grade_filter);
        Tpl::output('grade_list', $grade_list);

		$menu_array = array(1=>array('menu_key'=>'list','menu_name'=>'分店管理', 'menu_url'=>'index.php?act=seller_branch&op=branch_list'),);		
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key','list');
				
		Tpl::output('branch_info',$branchinfo);
		Tpl::showpage('seller_branch.edit','null_layout');
	}
	/**
	 * 保存
	 *
	 * @param 
	 * @return 
	 */
	public function branch_saveOp() {
		$data=array();
		// 验证手机号是否正确
		if (isset($_POST['member_mobile'])){
			if (!CheckMobileValidator(trim($_POST['member_mobile']))){
				showDialog('手机号填写不正确');
				exit;
			}
		}   
		if($_POST['branch_id'] == '') {	
		    //验证密码与确认密码
		    if ($_POST['password']!=$_POST['password_confirm']){
				showDialog('密码与确认密码不相同');
				exit;
			}
			$data['member_passwd']=$_POST['password'];
		    // 验证用户名是否重复
		    $check_member_name	= Model('member')->infoMember(array('member_name'=>trim($_POST['seller_name'])));
		    if(is_array($check_member_name) and count($check_member_name) > 0) {
                showDialog('用户名已存在');
			    exit;
		    }
			// 验证卖家是否重复
		    $check_seller_name	= Model('seller')->isSellerExist(array('seller_name'=>$_POST['seller_name']));
		    if($check_seller_name) {
                showDialog('卖家已存在');
			    exit;
		    }

            // 验证邮箱是否重复
		    $check_member_email	= Model('member')->infoMember(array('member_email'=>trim($_POST['member_email'])));
		    if(is_array($check_member_email) and count($check_member_email)>0) {
                showDialog('邮箱已存在');
			    exit;
		    }				
		} else {
			//验证密码与确认密码
		    if (isset($_POST['password']) && !empty($_POST['password'])){
				$data['member_passwd']=md5(trim($_POST['password']));
			}			
		}			
	
		$model_member	= Model('member');			
					
		$data['member_truename']=$_POST['member_truename'];
		$data['member_sex']=$_POST['member_sex'];		
		$data['member_qq']=$_POST['member_qq'];
		$data['member_email']=$_POST['member_email'];				
		$data['member_mobile']=$_POST['member_mobile'];	    
		
		if($_POST['branch_id'] == '') {
			$data['member_name']=$_POST['seller_name'];
		    $data['mc_id']=0;	//会员类型:0表示普通会员,1表示导购员,2表示推广员
		    $data['store_id']=0;			
			
			$member_id = $model_member->addMember($data);
			if($member_id) {				
				if (empty($this->store_info['parent_id']) || $this->store_info['parent_id']=='' || $this->store_info['parent_id']==0){
			        $parent_tree = '|'.$this->store_info['store_id'].'|';
		        }else{										
		            $parent_tree = '|'.trim($this->store_info['parent_tree'],'|').'|'.$this->store_info['store_id'].'|';
		        }
				//开店
 			    $shop_array		= array();
                $shop_array['member_id']	      = $member_id;
                $shop_array['member_name']	      = $data['member_name'];
                $shop_array['seller_name']        = $data['member_name'];
			    $shop_array['grade_id']		      = $_POST['grade_id'];
			    $shop_array['store_name']	      = $_POST['store_name'];
			    $shop_array['sc_id']		      = $this->store_info['sc_id'];
                $shop_array['store_company_name'] = $_POST['store_company_name'];
			    $shop_array['area_info']	      = $_POST['area_info'];
			    $shop_array['store_address']      = $_POST['store_address'];
			    $shop_array['store_zip']	      = '';
			    $shop_array['store_zy']		      = '';
			    $shop_array['store_state']	      = 1;
                $shop_array['store_time']	      = time();
			    $shop_array['parent_id']	      = $_SESSION['store_id'];				
				$shop_array['parent_tree']	      = $parent_tree;							
			    $shop_array['branch_op']	      = 0;
				$shop_array['branch_limit']       = 0;
			    $shop_array['extension_op']       = $this->store_info['extension_op'];
			    $shop_array['promotion_limit']    = $this->store_info['promotion_limit'];
			    $shop_array['saleman_limit']      = $this->store_info['saleman_limit'];						
			    $shop_array['payment_method']	  = $_POST['payment_method'];				
				$shop_array['show_own_copyright'] = 0;
                $shop_array['store_close_info']   = $this->store_info['store_close_info'];
				//自营店铺
				$shop_array['is_own_shop']        = $this->store_info['is_own_shop'];
				$shop_array['bind_all_gc']        = $this->store_info['bind_all_gc'];
				$model_store = Model('store');
			    $store_id = $model_store->addStore($shop_array);					
				
				if($store_id) {
                    //写入卖家帐号
					$model_seller = Model('seller');
                    $seller_array = array();
                    $seller_array['seller_name']     = $shop_array['seller_name'];
                    $seller_array['member_id']       = $shop_array['member_id'];
                    $seller_array['seller_group_id'] = 0;
                    $seller_array['store_id']        = $store_id;
                    $seller_array['is_admin']        = 1;
                    $state = $model_seller->addSeller($seller_array);
                }
				if($state) {
				    // 添加相册默认
				    $album_model = Model('album');
				    $album_arr = array();
				    $album_arr['aclass_name'] = '默认相册';
				    $album_arr['store_id'] = $store_id;
				    $album_arr['aclass_des'] = '';
				    $album_arr['aclass_sort'] = '255';
				    $album_arr['aclass_cover'] = '';
				    $album_arr['upload_time'] = time();
				    $album_arr['is_default'] = '1';
				    $album_model->addClass($album_arr);

				    $model = Model();
				    //插入店铺扩展表
				    $model->table('store_extend')->insert(array('store_id'=>$store_id));
                    //插入店铺绑定分类表
                    $store_bind_class_array = array();
					$model_store_bind_class = Model('store_bind_class');					
                    $store_bind_class = $model_store_bind_class->getStoreBindClassList(array('store_id'=>$_SESSION['store_id']));
                    if (!empty($store_bind_class) && is_array($store_bind_class)) {
                      foreach($store_bind_class as $v) {
                        $store_bind_class_array[] = array(
                            'store_id' => $store_id,
                            'commis_rate' => $v['commis_rate'],
                            'class_1' => $v['class_1'],
                            'class_2' => $v['class_2'],
                            'class_3' => $v['class_3'],
                        );
					  }
					  $model_store_bind_class->addStoreBindClassAll($store_bind_class_array);
                    }
					showDialog('店铺开店成功',urlShop('seller_branch', 'branch_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
                } else {
                    showDialog('店铺开店失败');
                }				
			} else {
				showDialog('店铺开店失败');
			}
		} else {			
			$where=array();
			$where['member_id']=intval($_POST['member_id']);
			$state = $model_member->where($where)->update($data);
			
			$model_store = Model('store');
			$shop_array		= array();			
			$shop_array['store_name']	= $_POST['store_name'];
			$shop_array['grade_id']		= $_POST['grade_id'];
			$shop_array['payment_method']	= $_POST['payment_method'];
			$shop_array['store_company_name'] = $_POST['store_company_name'];
			$shop_array['area_info']	= $_POST['area_info'];
		    $shop_array['store_address']= $_POST['store_address'];
			    
			$where=array();
			$where['store_id']=intval($_POST['branch_id']);	
			$state = $model_store->where($where)->update($shop_array);				
			
			if($state) {
				showDialog('修改成功',urlShop('seller_branch', 'branch_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			} else {
				showDialog('修改失败');
			}
		}
		
	}
	/**
	 * 删除
	 *
	 * @param 
	 * @return 
	 */
	public function branch_delOp() {
		$model_branch	= Model('store');	
	    $store_id=intval($_GET['branch_id']);
		
		if($store_id) {
		  if ($model_branch->delStore(array('store_id'=>$store_id))){
			showDialog('删除成功',urlShop('seller_branch', 'branch_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		  } else {
			showDialog('删除失败',urlShop('seller_branch', 'branch_list'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		  }
		} else {
			showDialog('非法操作',urlShop('seller_branch', 'branch_list'),'error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}
	}
	
	/**
	 * 分店加盟申请列表
	 *
	 */
    public function apply_listOp() {
        $model_apply = Model('apply_information');
		
		$apply_list = $model_apply->getBranchApplyList($_SESSION['store_id']);		
		
		Tpl::output('apply_list',$apply_list);
		Tpl::output('page',$model_apply->showpage());
			
	    self::profile_menu('apply');
        Tpl::showpage('seller_branch_apply.list');
    }
	
	/**
	 * 加盟申请信息
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
	 * 加盟申请信息编辑
	 *
	 */
    public function apply_editOp() {
        $ai_id = $_GET['id'];
		
        $model_apply = Model('apply_information');
		
		$apply_info = $model_apply->getApplyInfo($ai_id);		
		Tpl::output('apply_info',$apply_info);

        Tpl::showpage('seller_branch_apply.edit','null_layout');
    }
	
	/**
	 * 加盟申请信息保存
	 *
	 */
    public function apply_saveOp() {
        $ai_id = $_POST['id'];
		$verify = $_POST['verify'];
		if (empty($ai_id) || $ai_id<=0 || $verify<0 || $verify>2){
			showDialog('参数错误!',urlShop('seller_branch', 'apply_list'));
		}		
		
        $model_apply = Model('apply_information');
		$MemberMsg = 'apply_join_failed';
		
		$apply_info = $model_apply->getApplyInfo($ai_id);
		$member_id = $apply_info['ai_from'];
		if ($verify==2){
			if ($this->store_info['branch_count'] >= $this->store_info['branch_limit']){
				showDialog('您的分店数量已满，审核失败！',urlShop('seller_branch', 'apply_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		    }		
			$seller_info = Model('seller')->getSellerInfo(array('member_id'=>$member_id));
			if (empty($seller_info) || !is_array($seller_info)){
				showDialog('该会员还未开通店铺，审核失败！',urlShop('seller_branch', 'apply_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			}
			$MemberMsg = 'apply_join_success';
			$shop_id = $seller_info['store_id'];
			if (empty($this->store_info['parent_id']) || $this->store_info['parent_id']=='' || $this->store_info['parent_id']==0){
			    $parent_tree = '|'.$this->store_info['store_id'].'|';
		    }else{										
		        $parent_tree = '|'.trim($this->store_info['parent_tree'],'|').'|'.$this->store_info['store_id'].'|';
		    }						
		
		    $shop_array = array();
		    $shop_array['parent_id']       = $this->store_info['store_id'];
		    $shop_array['parent_tree']     = $parent_tree;
			$shop_array['sc_id']		   = $this->store_info['sc_id'];
			$shop_array['is_own_shop']     = $this->store_info['is_own_shop'];
			$shop_array['extension_op']    = $this->store_info['extension_op'];
			$shop_array['promotion_limit'] = $this->store_info['promotion_limit'];
			$shop_array['saleman_limit']   = $this->store_info['saleman_limit'];
				
		    Model('store')->editStore($shop_array,array('store_id'=>$shop_id));
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

        showDialog('审核操作成功!',urlShop('seller_branch', 'apply_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
    }
	
	/**
	 * 删除加盟申请信息
	 *
	 */
    public function apply_delOp() {
		$ai_id = $_GET['id'];
		if (empty($ai_id) || $ai_id<=0){
			showDialog('参数错误!',urlShop('seller_branch', 'apply_list'));
		}
		Model('apply_information')->delApplyInfo(array('ai_id'=>$ai_id));
		showDialog('申请信息删除成功!',urlShop('seller_branch', 'apply_list'));
	}	
	
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_key='') {
        $menu_array	= array();
		$menu_array[1]=array('menu_key'=>'list','menu_name'=>'分店管理'.$this->branch_count, 'menu_url'=>'index.php?act=seller_branch&op=branch_list');
		$menu_array[2]=array('menu_key'=>'apply','menu_name'=>'加盟申请'.$this->apply_count,	'menu_url'=>'index.php?act=seller_branch&op=apply_list');
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}