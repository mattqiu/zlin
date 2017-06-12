<?php
/**
 * 商家入住
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

class store_joininControl extends BaseStoreJoinControl {

    private $joinin_detail = NULL;
	private $reg_store_type = 0;
	private $Link_pre = '';

	public function __construct() {
		parent::__construct();
		
		$this->checkLogin();

        $model_seller = Model('seller');
        $seller_info = $model_seller->getSellerInfo(array('member_id' => $_SESSION['member_id']));
		if(!empty($seller_info)) {
            @header('location: index.php?act=seller_login');
		}
		
		if($_GET['op'] != 'check_seller_name_exist' && $_GET['op'] != 'checkname') {
            $this->check_joinin_state();
        }		
        //平台客服电话
        $phone_array = explode(',',C('site_phone'));
        Tpl::output('phone_array',$phone_array);
		
        $model_help = Model('help');
        $condition = array();
        $condition['type_id'] = '99';//默认显示入驻流程;
        $list = $model_help->getShowStoreHelpList($condition);
        Tpl::output('list',$list);//左侧帮助类型及帮助
        Tpl::output('show_sign','joinin');
        Tpl::output('html_title',C('site_name').' - '.'商家入驻');
        Tpl::output('article_list','');//底部不显示文章分类
	}

    private function check_joinin_state() {
        $model_store_joinin = Model('store_joinin');
        $joinin_detail = $model_store_joinin->getOne(array('member_id'=>$_SESSION['member_id']));
        if(!empty($joinin_detail)) {
            $this->joinin_detail = $joinin_detail;
			$this->reg_store_type = $joinin_detail['store_type'];
			if ($this->reg_store_type==1){$this->Link_pre='_p';}
			
            switch (intval($joinin_detail['joinin_state'])) {
                case STORE_JOIN_STATE_NEW:
                    $this->show_join_message('入驻申请已经提交，请等待管理员审核', FALSE, 3,4);
                    break;
                case STORE_JOIN_STATE_PAY:
                    $this->show_join_message('付款凭证已经提交，请等待管理员核对后为您开通店铺', FALSE, 4,4);
                    break;
                case STORE_JOIN_STATE_VERIFY_SUCCESS:
                    if(!in_array($_GET['op'], array('pay', 'pay_save', 'pay_p', 'pay_save_p'))) {
                        $this->show_join_message('审核成功，请完成付款，付款后点击下一步提交付款凭证', SHOP_SITE_URL.DS.'index.php?act=store_joinin&op=pay'.$this->Link_pre,3,4,'下一步，上传付款凭证');
                    }
                    break;
                case STORE_JOIN_STATE_VERIFY_FAIL:
                    if(!in_array($_GET['op'], array('step1', 'step2', 'step3', 'step4', 'step1_p', 'step2_p', 'step3_p', 'step4_p'))) {
                        $this->show_join_message('审核失败:'.$joinin_detail['joinin_message'], SHOP_SITE_URL.DS.'index.php?act=store_joinin&op=step1'.$this->Link_pre,3,4,'重新申请');
                    }
                    break;
                case STORE_JOIN_STATE_PAY_FAIL:
                    if(!in_array($_GET['op'], array('pay', 'pay_save', 'pay_p', 'pay_save_p'))) {
                        $this->show_join_message('付款审核失败:'.$joinin_detail['joinin_message'], SHOP_SITE_URL.DS.'index.php?act=store_joinin&op=pay'.$this->Link_pre,4,4,'重新上传付款凭证');
                    }
                    break;
                case STORE_JOIN_STATE_FINAL:
				    Tpl::output('store_id', $joinin_detail['store_id']);
				    $this->joinin_final();
                    //@header('location: index.php?act=seller_login');
                    break;
            }
        }
    }

	public function indexOp() {
		$this->step0Op();
	}

    public function stepOp() {
        Tpl::showpage('store_joinin_apply.step');
    }
	
	//企业入驻申请
	public function step0Op() {
        $model_document = Model('document');
        $document_info = $model_document->getOneByCode('open_store');
		
        Tpl::output('agreement', $document_info['doc_content']);
        Tpl::output('step', 0);
		Tpl::output('sub_step', 0);
        Tpl::showpage('store_joinin_apply');
    }

    public function step1Op() {	
        Tpl::output('step', 1);
		Tpl::output('sub_step', 1);
        Tpl::showpage('store_joinin_apply');
    }

    public function step2Op() {
        if(!empty($_POST)) {
            $param = array();
            $param['member_name'] = $_SESSION['member_name'];   
            $param['company_name'] = $_POST['company_name'];
            $param['company_address'] = $_POST['company_address'];
            $param['company_address_detail'] = $_POST['company_address_detail'];
            $param['company_phone'] = $_POST['company_phone'];
            $param['company_employee_count'] = intval($_POST['company_employee_count']);
            $param['company_registered_capital'] = intval($_POST['company_registered_capital']);
            $param['contacts_name'] = $_POST['contacts_name'];
            $param['contacts_phone'] = $_POST['contacts_phone'];
            $param['contacts_email'] = $_POST['contacts_email'];
            $param['business_licence_number'] = $_POST['business_licence_number'];
            $param['business_licence_address'] = $_POST['business_licence_address'];
            $param['business_licence_start'] = $_POST['business_licence_start'];
            $param['business_licence_end'] = $_POST['business_licence_end'];
            $param['business_sphere'] = $_POST['business_sphere'];
            $param['business_licence_number_electronic'] = $this->upload_image('business_licence_number_electronic');
            $param['organization_code'] = $_POST['organization_code'];
            $param['organization_code_electronic'] = $this->upload_image('organization_code_electronic');
            $param['general_taxpayer'] = $this->upload_image('general_taxpayer');

            $this->step2_save_valid($param);
			
			$model_store_joinin = Model('store_joinin');
            $joinin_info = $model_store_joinin->getOne(array('member_id' => $_SESSION['member_id']));
            if(empty($joinin_info)) {
                $param['member_id'] = $_SESSION['member_id'];   
                $model_store_joinin->save($param);
            } else {
                $model_store_joinin->modify($param, array('member_id'=>$_SESSION['member_id']));
            }
        }
        Tpl::output('step', 2);
		Tpl::output('sub_step', 2);
        Tpl::showpage('store_joinin_apply');
    }

    private function step2_save_valid($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['company_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"公司名称不能为空且必须小于50个字"),
            array("input"=>$param['company_address'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"公司地址不能为空且必须小于50个字"),
            array("input"=>$param['company_address_detail'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"公司详细地址不能为空且必须小于50个字"),
            array("input"=>$param['company_phone'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"公司电话不能为空"),
            array("input"=>$input['company_employee_count'], "require"=>"true","validator"=>"Number","员工总数不能为空且必须是数字"),
            array("input"=>$input['company_registered_capital'], "require"=>"true","validator"=>"Number","注册资金不能为空且必须是数字"),
            array("input"=>$param['contacts_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"联系人姓名不能为空且必须小于20个字"),
            array("input"=>$param['contacts_phone'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"联系人电话不能为空"),
            array("input"=>$param['contacts_email'], "require"=>"true","validator"=>"email","message"=>"电子邮箱不能为空"),
            array("input"=>$param['business_licence_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"营业执照号不能为空且必须小于20个字"),
            array("input"=>$param['business_licence_address'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"营业执照所在地不能为空且必须小于50个字"),
            array("input"=>$param['business_licence_start'], "require"=>"true","message"=>"营业执照有效期不能为空"),
            array("input"=>$param['business_licence_end'], "require"=>"true","message"=>"营业执照有效期不能为空"),
            array("input"=>$param['business_sphere'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"500","message"=>"法定经营范围不能为空且必须小于50个字"),
            array("input"=>$param['business_licence_number_electronic'], "require"=>"true","message"=>"营业执照电子版不能为空"),
            array("input"=>$param['organization_code'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"组织机构代码不能为空且必须小于20个字"),
            array("input"=>$param['organization_code_electronic'], "require"=>"true","message"=>"组织机构代码电子版不能为空"),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }

    public function step3Op() {
        if(!empty($_POST)) {
            $param = array();
            $param['bank_account_name'] = $_POST['bank_account_name'];
            $param['bank_account_number'] = $_POST['bank_account_number'];
            $param['bank_name'] = $_POST['bank_name'];
            $param['bank_code'] = $_POST['bank_code'];
            $param['bank_address'] = $_POST['bank_address'];
            $param['bank_licence_electronic'] = $this->upload_image('bank_licence_electronic');
            if(!empty($_POST['is_settlement_account'])) {
                $param['is_settlement_account'] = 1;
                $param['settlement_bank_account_name'] = $_POST['bank_account_name'];
                $param['settlement_bank_account_number'] = $_POST['bank_account_number'];
                $param['settlement_bank_name'] = $_POST['bank_name'];
                $param['settlement_bank_code'] = $_POST['bank_code'];
                $param['settlement_bank_address'] = $_POST['bank_address'];
            } else {
                $param['is_settlement_account'] = 2;
                $param['settlement_bank_account_name'] = $_POST['settlement_bank_account_name'];
                $param['settlement_bank_account_number'] = $_POST['settlement_bank_account_number'];
                $param['settlement_bank_name'] = $_POST['settlement_bank_name'];
                $param['settlement_bank_code'] = $_POST['settlement_bank_code'];
                $param['settlement_bank_address'] = $_POST['settlement_bank_address'];

            }
            $param['tax_registration_certificate'] = $_POST['tax_registration_certificate'];
            $param['taxpayer_id'] = $_POST['taxpayer_id'];
            $param['tax_registration_certificate_electronic'] = $this->upload_image('tax_registration_certificate_electronic');

            $this->step3_save_valid($param);

            $model_store_joinin = Model('store_joinin');
            $model_store_joinin->modify($param, array('member_id'=>$_SESSION['member_id']));
        }

        //商品分类
        $gc	= Model('goods_class');
        $gc_list	= $gc->getGoodsClassListByParentId(0);
        Tpl::output('gc_list',$gc_list);

        //店铺等级
		$model_grade = Model('store_grade');
		$grade_list = $model_grade->getGradeList(array('store_type'=>2));  //($setting = rkcache('store_grade')) ? $setting : rkcache('store_grade',true);
		//附加功能
		if(!empty($grade_list) && is_array($grade_list)){
			foreach($grade_list as $key=>$grade){
				$sg_function = explode('|',$grade['sg_function']);
				if (!empty($sg_function[0]) && is_array($sg_function)){
					foreach ($sg_function as $key1=>$value){
						if ($value == 'editor_multimedia'){
							$grade_list[$key]['function_str'] .= '富文本编辑器';
						}
					}
				}else {
					$grade_list[$key]['function_str'] = '无';
				}
			}
		}
		Tpl::output('grade_list', $grade_list);

        //店铺分类 
        $model_store = Model('store_class');
        $store_class = $model_store->getStoreClassList(array(),'',false);
        if (!empty($store_class) && is_array($store_class)){
            foreach ($store_class as $k => $v){
                $store_class[$k]['sc_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['sc_name'];
            }
        }
        Tpl::output('store_class', $store_class);

        Tpl::output('step', 3);
		Tpl::output('sub_step', 3);
        Tpl::showpage('store_joinin_apply');
    }

    private function step3_save_valid($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['bank_account_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"银行开户名不能为空且必须小于50个字"),
            array("input"=>$param['bank_account_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"银行账号不能为空且必须小于20个字"),
            array("input"=>$param['bank_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"开户银行支行名称不能为空且必须小于50个字"),
            array("input"=>$param['bank_code'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"支行联行号不能为空且必须小于20个字"),
            array("input"=>$input['bank_address'], "require"=>"true","开户行所在地不能为空"),
            array("input"=>$input['bank_licence_electronic'], "require"=>"true","开户银行许可证电子版不能为空"),
            array("input"=>$param['settlement_bank_account_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"银行开户名不能为空且必须小于50个字"),
            array("input"=>$param['settlement_bank_account_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"银行账号不能为空且必须小于20个字"),
            array("input"=>$param['settlement_bank_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"开户银行支行名称不能为空且必须小于50个字"),
            array("input"=>$param['settlement_bank_code'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"支行联行号不能为空且必须小于20个字"),
            array("input"=>$input['settlement_bank_address'], "require"=>"true","开户行所在地不能为空"),
            array("input"=>$param['tax_registration_certificate'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"税务登记证号不能为空且必须小于20个字"),
            array("input"=>$param['taxpayer_id'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"纳税人识别号"),
            array("input"=>$param['tax_registration_certificate_electronic'], "require"=>"true","message"=>"税务登记证号电子版不能为空"),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }

    public function check_seller_name_existOp() {
        $condition = array();
        $condition['seller_name'] = $_GET['seller_name'];

        $model_seller = Model('seller');
        $result = $model_seller->isSellerExist($condition);

        if($result) {
            echo 'true';
        } else {
            echo 'false';
        }
    }


    public function step4Op() {
        $store_class_ids = array();
        $store_class_names = array();
        if(!empty($_POST['store_class_ids'])) {
            foreach ($_POST['store_class_ids'] as $value) {
                $store_class_ids[] = $value;
            }
        }
        if(!empty($_POST['store_class_names'])) {
            foreach ($_POST['store_class_names'] as $value) {
                $store_class_names[] = $value;
            }
        }		
		
		//取最小级分类最新分佣比例
        $sc_ids = array();
        foreach ($store_class_ids as $v) {
            $v = explode(',',trim($v,','));
            if (!empty($v) && is_array($v)) {
                $sc_ids[] = end($v);
            }
        }
        if (!empty($sc_ids)) {
            $store_class_commis_rates = array();
            $goods_class_list = Model('goods_class')->getGoodsClassListByIds($sc_ids);
            if (!empty($goods_class_list) && is_array($goods_class_list)) {
                $sc_ids = array();
                foreach ($goods_class_list as $v) {
                    $store_class_commis_rates[] = $v['commis_rate'];
                }
            }
        }
		
        $param = array();
        $param['seller_name'] = $_POST['seller_name'];
        $param['store_name'] = $_POST['store_name'];
        $param['store_class_ids'] = serialize($store_class_ids);
        $param['store_class_names'] = serialize($store_class_names);
		$param['joinin_year'] = intval($_POST['store_joinin_times']);
        $param['joinin_state'] = STORE_JOIN_STATE_NEW;
        $param['store_class_commis_rates'] = implode(',', $store_class_commis_rates);
		$param['store_joinin_times'] = intval($_POST['store_joinin_times']);
		
		//取店铺等级信息
        $grade_list = rkcache('store_grade',true);
        if (!empty($grade_list[$_POST['sg_id']])) {
            $param['sg_id'] = $_POST['sg_id'];
            $param['sg_name'] = $grade_list[$_POST['sg_id']]['sg_name'];
            $param['sg_info'] = serialize(array('sg_price' => $grade_list[$_POST['sg_id']]['sg_price']));
        }

        //取最新店铺分类信息
        $store_class_info = Model('store_class')->getStoreClassInfo(array('sc_id'=>intval($_POST['sc_id'])));
        if ($store_class_info) {
            $param['sc_id'] = $store_class_info['sc_id'];
            $param['sc_name'] = $store_class_info['sc_name'];
            $param['sc_bail'] = $store_class_info['sc_bail'];
        }

        $this->step4_save_valid($param);
		//费用
		$sg_price=$grade_list[$_POST['sg_id']]['sg_price'];
		$sg_confirm=$grade_list[$_POST['sg_id']]['sg_confirm'];
		//保证金
		$sc_bail=$param['sc_bail'];
		//总费用
		$cash_total=$sg_price*$param['joinin_year']+$sc_bail;
		
		$param['paying_amount'] = $cash_total;
		$param['store_type'] = 2;
		$param['sg_price'] = $sg_price;
		$param['sc_bail'] = $sc_bail;
		$param['cash_total'] = $cash_total;
		
		//检查是否是免费用、免申核的店铺
		if ($cash_total=0 && $sg_confirm==0){
			$param['joinin_state'] = STORE_JOIN_STATE_FINAL;
		}

        $model_store_joinin = Model('store_joinin');
        $model_store_joinin->modify($param, array('member_id'=>$_SESSION['member_id']));
		
		if ($param['joinin_state'] == STORE_JOIN_STATE_FINAL){
			$this->joinin_detail['seller_name']=$param['seller_name'];
			$this->joinin_detail['store_name']=$param['store_name'];
			$this->joinin_detail['store_class_ids']=$param['store_class_ids'];
			$this->joinin_detail['store_class_names']=$param['store_class_names'];
			$this->joinin_detail['sg_name']=$param['sg_name'];			
			$this->joinin_detail['sg_id']=$param['sg_id'];
			$this->joinin_detail['store_joinin_times']=$param['store_joinin_times'];
			$this->joinin_detail['sc_name']=$param['sc_name'];
			$this->joinin_detail['sc_id']=$param['sc_id'];
			$this->joinin_detail['joinin_state']=$param['joinin_state'];
			$this->joinin_detail['store_type']=$param['store_type'];
			$this->joinin_detail['sg_price']=$param['sg_price'];
			$this->joinin_detail['sc_bail']=$param['sc_bail'];
			$this->joinin_detail['cash_total']=$param['cash_total'];
		    $this->joinin_final($this->joinin_detail);
		}else{
            @header('location: index.php?act=store_joinin');
		}
    }

    private function step4_save_valid($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['store_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"店铺名称不能为空且必须小于50个字"),
            array("input"=>$param['sg_id'], "require"=>"true","message"=>"店铺等级不能为空"),
			array("input"=>$param['store_joinin_times'], "require"=>"true","message"=>"入驻时长必须选择"),
            array("input"=>$param['sc_id'], "require"=>"true","message"=>"店铺分类不能为空"),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }

    public function payOp() {
        Tpl::output('joinin_detail', $this->joinin_detail);
        Tpl::output('step', 4);
		Tpl::output('sub_step', 5);
        Tpl::showpage('store_joinin_apply');
    }

    public function pay_saveOp() {
        $param = array();
        $param['paying_money_certificate'] = $this->upload_image('paying_money_certificate');
        $param['paying_money_certificate_explain'] = $_POST['paying_money_certificate_explain'];
        $param['joinin_state'] = STORE_JOIN_STATE_PAY;

        if(empty($param['paying_money_certificate'])) {
            showMessage('请上传付款凭证','','','error');
        }

        $model_store_joinin = Model('store_joinin');
        $model_store_joinin->modify($param, array('member_id'=>$_SESSION['member_id']));

        @header('location: index.php?act=store_joinin');
    }

    //个人入驻申请
	public function step0_pOP() {
        $model_document = Model('document');
        $document_info = $model_document->getOneByCode('open_store_p');
		
        Tpl::output('agreement', $document_info['doc_content']);
        Tpl::output('step', 0);
		Tpl::output('sub_step', 0);
        Tpl::showpage('store_joinin_apply_p');
    }

    public function step1_pOP() {
        Tpl::output('step', 1);
		Tpl::output('sub_step', 1);
        Tpl::showpage('store_joinin_apply_p');
    }

    public function step2_pOP() {
        if(!empty($_POST)) {
            $param = array();
            $param['member_name'] = $_SESSION['member_name'];   
			$param['contacts_name'] = $_POST['contacts_name'];
			$param['company_address'] = $_POST['company_address'];
            $param['company_address_detail'] = $_POST['company_address_detail'];			
            $param['store_owner_card'] = $_POST['store_owner_card'];
			$param['owner_card_electronic'] = $this->upload_image('owner_card_electronic');
			$param['owner_card_front_pic'] = $this->upload_image('owner_card_front_pic');
			$param['owner_card_back_pic'] = $this->upload_image('owner_card_back_pic');
            $param['contacts_phone'] = $_POST['contacts_phone'];
            $param['contacts_email'] = $_POST['contacts_email'];

            $this->step2_save_valid_p($param);

            $model_store_joinin = Model('store_joinin');
            $joinin_info = $model_store_joinin->getOne(array('member_id' => $_SESSION['member_id']));
            if(empty($joinin_info)) {
                $param['member_id'] = $_SESSION['member_id'];   
                $model_store_joinin->save($param);
            } else {
                $model_store_joinin->modify($param, array('member_id'=>$_SESSION['member_id']));
            }
        }
        Tpl::output('step', 2);
		Tpl::output('sub_step', 2);
        Tpl::showpage('store_joinin_apply_p');
    }

    private function step2_save_valid_p($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
		    array("input"=>$param['contacts_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"真实姓名不能为空且必须小于20个字"),
			array("input"=>$param['company_address'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"所在区域地址不能为空且必须小于50个字"),
            array("input"=>$param['company_address_detail'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"详细地址不能为空且必须小于50个字"),
			array("input"=>$param['store_owner_card'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"18","message"=>"身份证号码不能为空且必须小于18个字"),
			array("input"=>$param['owner_card_electronic'], "require"=>"true","message"=>"手持身份证照片不能为空"),
			array("input"=>$param['owner_card_front_pic'], "require"=>"true","message"=>"身份证正面照片不能为空"),
			array("input"=>$param['owner_card_back_pic'], "require"=>"true","message"=>"身份证背面照片不能为空"),
			array("input"=>$param['contacts_phone'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"联系电话不能为空"),
            array("input"=>$param['contacts_email'], "require"=>"true","validator"=>"email","message"=>"电子邮箱不能为空"),            
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }

    public function step3_pOP() {
        if(!empty($_POST)) {
            $param = array();
            $param['is_settlement_account'] = 2;
            $param['settlement_bank_account_name'] = $_POST['settlement_bank_account_name'];
            $param['settlement_bank_account_number'] = $_POST['settlement_bank_account_number'];
            $param['settlement_bank_name'] = $_POST['settlement_bank_name'];
            $param['settlement_bank_code'] = $_POST['settlement_bank_code'];
            $param['settlement_bank_address'] = $_POST['settlement_bank_address'];
			
            $this->step3_save_valid_p($param);

            $model_store_joinin = Model('store_joinin');
            $model_store_joinin->modify($param, array('member_id'=>$_SESSION['member_id']));
        }

        //商品分类
        $gc	= Model('goods_class');
        $gc_list	= $gc->getGoodsClassListByParentId(0);
        Tpl::output('gc_list',$gc_list);

        //店铺等级
		$model_grade = Model('store_grade');
		$grade_list = $model_grade->getGradeList(array('store_type'=>1));  //($setting = rkcache('store_grade')) ? $setting : rkcache('store_grade',true);
		//附加功能
		if(!empty($grade_list) && is_array($grade_list)){
			foreach($grade_list as $key=>$grade){
				$sg_function = explode('|',$grade['sg_function']);
				if (!empty($sg_function[0]) && is_array($sg_function)){
					foreach ($sg_function as $key1=>$value){
						if ($value == 'editor_multimedia'){
							$grade_list[$key]['function_str'] .= '富文本编辑器';
						}
					}
				}else {
					$grade_list[$key]['function_str'] = '无';
				}
			}
		}
		Tpl::output('grade_list', $grade_list);

        //店铺分类 
        $model_store = Model('store_class');
        $store_class = $model_store->getStoreClassList(array(),'',false);
        if (!empty($store_class) && is_array($store_class)){
            foreach ($store_class as $k => $v){
                $store_class[$k]['sc_name'] = str_repeat("&nbsp;",$v['deep']*2).$v['sc_name'];
            }
        }
        Tpl::output('store_class', $store_class);

        Tpl::output('step', 3);
		Tpl::output('sub_step', 3);
        Tpl::showpage('store_joinin_apply_p');
    }

    private function step3_save_valid_p($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['settlement_bank_account_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"银行开户名不能为空且必须小于50个字"),
            array("input"=>$param['settlement_bank_account_number'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"银行账号不能为空且必须小于20个字"),
            array("input"=>$param['settlement_bank_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"开户银行支行名称不能为空且必须小于50个字"),
            array("input"=>$param['settlement_bank_code'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"20","message"=>"支行联行号不能为空且必须小于20个字"),
            array("input"=>$input['settlement_bank_address'], "require"=>"true","开户行所在地不能为空"),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }

    public function step4_pOP() {
        $store_class_ids = array();
        $store_class_names = array();
        if(!empty($_POST['store_class_ids'])) {
            foreach ($_POST['store_class_ids'] as $value) {
                $store_class_ids[] = $value;
            }
        }
        if(!empty($_POST['store_class_names'])) {
            foreach ($_POST['store_class_names'] as $value) {
                $store_class_names[] = $value;
            }
        }
		//取最小级分类最新分佣比例
        $sc_ids = array();
        foreach ($store_class_ids as $v) {
            $v = explode(',',trim($v,','));
            if (!empty($v) && is_array($v)) {
                $sc_ids[] = end($v);
            }
        }
        if (!empty($sc_ids)) {
            $store_class_commis_rates = array();
            $goods_class_list = Model('goods_class')->getGoodsClassListByIds($sc_ids);
            if (!empty($goods_class_list) && is_array($goods_class_list)) {
                $sc_ids = array();
                foreach ($goods_class_list as $v) {
                    $store_class_commis_rates[] = $v['commis_rate'];
                }
            }
        }
		
		$param = array();
        $param['seller_name'] = $_POST['seller_name'];
        $param['store_name'] = $_POST['store_name'];
        $param['store_class_ids'] = serialize($store_class_ids);
        $param['store_class_names'] = serialize($store_class_names);
		$param['joinin_year'] = intval($_POST['store_joinin_times']);
        $param['joinin_state'] = STORE_JOIN_STATE_NEW;
        $param['store_class_commis_rates'] = implode(',', $store_class_commis_rates);
		$param['store_joinin_times'] = intval($_POST['store_joinin_times']);
		
		//取店铺等级信息
        $grade_list = rkcache('store_grade',true);
        if (!empty($grade_list[$_POST['sg_id']])) {
            $param['sg_id'] = $_POST['sg_id'];
            $param['sg_name'] = $grade_list[$_POST['sg_id']]['sg_name'];
            $param['sg_info'] = serialize(array('sg_price' => $grade_list[$_POST['sg_id']]['sg_price']));
        }

        //取最新店铺分类信息
        $store_class_info = Model('store_class')->getStoreClassInfo(array('sc_id'=>intval($_POST['sc_id'])));
        if ($store_class_info) {
            $param['sc_id'] = $store_class_info['sc_id'];
            $param['sc_name'] = $store_class_info['sc_name'];
            $param['sc_bail'] = $store_class_info['sc_bail'];
        }

        $this->step4_save_valid_p($param);
		//费用
		$sg_price=$grade_list[$_POST['sg_id']]['sg_price'];
		$sg_confirm=$grade_list[$_POST['sg_id']]['sg_confirm'];
		//保证金
		$sc_bail=$param['sc_bail'];
		//总费用
		$cash_total=$sg_price*$param['joinin_year']+$sc_bail;

		//检查是否是免费用、免申核的店铺
		if ($cash_total==0 && $sg_confirm==0){
			$param['joinin_state'] = STORE_JOIN_STATE_FINAL;
		}
        $param['paying_amount'] = $cash_total;
		$param['store_type'] = 1;
		$param['sg_price'] = $sg_price;
		$param['sc_bail'] = $sc_bail;
		$param['cash_total'] = $cash_total;

        $model_store_joinin = Model('store_joinin');
        $model_store_joinin->modify($param, array('member_id'=>$_SESSION['member_id']));
        
		if ($param['joinin_state'] == STORE_JOIN_STATE_FINAL){
			$this->joinin_detail['seller_name']=$param['seller_name'];
			$this->joinin_detail['store_name']=$param['store_name'];
			$this->joinin_detail['store_class_ids']=$param['store_class_ids'];
			$this->joinin_detail['store_class_names']=$param['store_class_names'];
			$this->joinin_detail['sg_name']=$param['sg_name'];			
			$this->joinin_detail['sg_id']=$param['sg_id'];
			$this->joinin_detail['store_joinin_times']=$param['store_joinin_times'];
			$this->joinin_detail['sc_name']=$param['sc_name'];
			$this->joinin_detail['sc_id']=$param['sc_id'];
			$this->joinin_detail['joinin_state']=$param['joinin_state'];
			$this->joinin_detail['store_type']=$param['store_type'];
			$this->joinin_detail['sg_price']=$param['sg_price'];
			$this->joinin_detail['sc_bail']=$param['sc_bail'];
			$this->joinin_detail['cash_total']=$param['cash_total'];
		    $this->joinin_final($this->joinin_detail);
		}else{
            @header('location: index.php?act=store_joinin');
		}
    }

    private function step4_save_valid_p($param) {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
            array("input"=>$param['store_name'], "require"=>"true","validator"=>"Length","min"=>"1","max"=>"50","message"=>"店铺名称不能为空且必须小于50个字"),
            array("input"=>$param['sg_id'], "require"=>"true","message"=>"店铺等级不能为空"),
			array("input"=>$param['store_joinin_times'], "require"=>"true","message"=>"入驻时长必须选择"),
            array("input"=>$param['sc_id'], "require"=>"true","message"=>"店铺分类不能为空"),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            showMessage($error);
        }
    }

    public function pay_pOP() {
        Tpl::output('joinin_detail', $this->joinin_detail);
        Tpl::output('step', 4);
		Tpl::output('sub_step', 5);
        Tpl::showpage('store_joinin_apply_p');
    }

    public function pay_save_pOP() {
        $param = array();
        $param['paying_money_certificate'] = $this->upload_image('paying_money_certificate');
        $param['paying_money_certificate_explain'] = $_POST['paying_money_certificate_explain'];
        $param['joinin_state'] = STORE_JOIN_STATE_PAY;

        if(empty($param['paying_money_certificate'])) {
            showMessage('请上传付款凭证','','','error');
        }

        $model_store_joinin = Model('store_joinin');
        $model_store_joinin->modify($param, array('member_id'=>$_SESSION['member_id']));

        @header('location: index.php?act=store_joinin');
    }
	
	//公用函数
	public function joinin_final($joinin_detail=NULL) {
		if (!empty($joinin_detail)){
            if($joinin_detail['joinin_state']==STORE_JOIN_STATE_FINAL) {
				$model_store	= Model('store');
                $model_seller = Model('seller');
				
				$branch_info=Model('store_grade')->getOneGrade($joinin_detail['sg_id']);
			    if(!empty($branch_info) && is_array($branch_info)){
				    $branch_op=$branch_info['branch_op'];
			    }else{
				    $branch_op=0;
			    }
                //开店
 			    $shop_array		= array();
                $shop_array['member_id']	= $joinin_detail['member_id'];
                $shop_array['member_name']	= $joinin_detail['member_name'];
                $shop_array['seller_name'] = $joinin_detail['seller_name'];
			    $shop_array['grade_id']		= $joinin_detail['sg_id'];
			    //$shop_array['store_owner_card']= empty($joinin_detail['store_owner_card'])?'':$joinin_detail['store_owner_card'];
			    //$shop_array['name_auth']       = empty($joinin_detail['store_owner_card'])?'0':'1';
			    $shop_array['store_name']	= $joinin_detail['store_name'];
			    $shop_array['sc_id']		= $joinin_detail['sc_id'];
                $shop_array['store_company_name'] = $joinin_detail['company_name'];
			    $shop_array['province_id']  = $joinin_detail['company_province_id'];  
			    $shop_array['area_info']	= $joinin_detail['company_address'];
			    $shop_array['store_address']= $joinin_detail['company_address_detail'];
			    $shop_array['store_zip']	= '';
			    //$shop_array['store_tel']	= '';
			    $shop_array['store_zy']		= '';
			    $shop_array['store_state']	= 1;
                $shop_array['store_time']	= time();
				//$shop_array['store_end_time'] = strtotime(date('Y-m-d 23:59:59', strtotime('+1 day'))." +".intval($joinin_detail['joinin_year'])." year");
			    //我添加的			
			    $shop_array['parent_id']	= '';
			    $shop_array['branch_op']	= $branch_op;
			    $shop_array['payment_method']	= intval(C('payment_method'))?intval(C('payment_method')):0;
			    //$shop_array['sc_bail']	= $joinin_detail['sc_bail'];
			    $shop_array['store_type']	= $joinin_detail['store_type'];
			    $store_id = $model_store->addStore($shop_array);

                if($store_id) {
                    //写入卖家帐号
                    $seller_array = array();
                    $seller_array['seller_name'] = $joinin_detail['seller_name'];
                    $seller_array['member_id'] = $joinin_detail['member_id'];
                    $seller_array['seller_group_id'] = 0;
                    $seller_array['store_id'] = $store_id;
                    $seller_array['is_admin'] = 1;
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
					$class_data = array();
		            $class_list = Model('goods_class')->getCache();
                    $store_bind_class = unserialize($joinin_detail['store_class_ids']);
                    for($i=0, $length=count($store_bind_class); $i<$length; $i++) {
                        list($class_1, $class_2, $class_3) = explode(',', $store_bind_class[$i]);
						
		                if(empty($class_2)) {
			                $child2_list = $class_list['children'][$class_1];
			                if(!empty($child2_list) && is_array($child2_list)){
				                foreach ($child2_list as $key2=>$gc2) {
					                $child3_list = $class_list['children'][$gc2];
					                if(!empty($child3_list) && is_array($child3_list)){
						                foreach ($child3_list as $key3=>$gc3) {
							                $temp = array();
						                    $temp['store_id']    = $store_id;
				  		                    $temp['state']       = 1;
				                            $temp['class_1']     = $class_1;
				                            $temp['class_2']     = $gc2;
				                            $temp['class_3']     = $gc3;
					                        $temp['commis_rate'] = $class_list['data'][$gc3]['commis_rate'];
							                $class_data[] = $temp;
						                }						
					                }else{
						                $temp = array();
						                $temp['store_id']    = $store_id;
				                        $temp['state']       = 1;
				                        $temp['class_1']     = $class_1;
				                        $temp['class_2']     = $gc2;
				                        //$temp['class_3']     = $class_3;
				                        $temp['commis_rate'] = $class_list['data'][$gc2]['commis_rate'];
						                $class_data[] = $temp;						
					                }					
					            }
				
			                }else{
								$temp = array();
						        $temp['store_id']    = $store_id;
				                $temp['state']       = 1;
				                $temp['class_1']     = $class_1;
				                //$temp['class_2']   = $class_2;
				                //$temp['class_3']   = $class_3;
				                $temp['commis_rate'] = $class_list['data'][$class_1]['commis_rate'];
						        $class_data[] = $temp;
			                }
	        
		                }else{			
	                        if(empty($class_3)) {			    
				                $child3_list = $class_list['children'][$class_2];
				                if(!empty($child3_list) && is_array($child3_list)){
					                foreach ($child3_list as $key3=>$gc3) {
										$temp = array();
						                $temp['store_id']    = $store_id;
				                        $temp['state']       = 1;
				                        $temp['class_1']     = $class_1;
				                        $temp['class_2']     = $class_2;
				                        $temp['class_3']     = $gc3;
				                        $temp['commis_rate'] = $class_list['data'][$gc3]['commis_rate'];
						                $class_data[] = $temp;
					                }						
				                }else{
									$temp = array();
						            $temp['store_id']    = $store_id;
				                    $temp['state']       = 1;
				                    $temp['class_1']     = $class_1;
				                    $temp['class_2']     = $class_2;
				                    //$temp['class_3']   = $class_3;
				                    $temp['commis_rate'] = $class_list['data'][$class_2]['commis_rate'];
						            $class_data[] = $temp;
			                    }
			                }else{
								$temp = array();
						        $temp['store_id']    = $store_id;
				                $temp['state']       = 1;
				                $temp['class_1']     = $class_1;
				                $temp['class_2']     = $class_2;
				                $temp['class_3']     = $class_3;
				                $temp['commis_rate'] = $class_list['data'][$class_3]['commis_rate'];
						        $class_data[] = $temp;
			                }
	                    }
					}
	                $result = Model('store_bind_class')->addStoreBindClassAll($class_data);
		
                    //$store_bind_class_array = array();
                    //$store_bind_class = unserialize($joinin_detail['store_class_ids']);
                    //$store_bind_commis_rates = explode(',', $joinin_detail['store_class_commis_rates']);
                    //for($i=0, $length=count($store_bind_class); $i<$length; $i++) {
                    //    list($class1, $class2, $class3) = explode(',', $store_bind_class[$i]);
                    //    $store_bind_class_array[] = array(
                    //        'store_id' => $store_id,
                    //        'commis_rate' => $store_bind_commis_rates[$i],
                    //        'class_1' => $class1,
                    //        'class_2' => $class2,
                    //        'class_3' => $class3,
					//		'state'   => 1
                    //    );
                    //}
                    //$model_store_bind_class = Model('store_bind_class');
                    //$model_store_bind_class->addStoreBindClassAll($store_bind_class_array);				
			    }
				Model('store_joinin')->modify(array('store_id'=>$store_id), array('member_id'=>$_SESSION['member_id']));
				Tpl::output('store_id', $store_id);
			}
		}
		
		Tpl::output('step', 5);
		Tpl::output('sub_step', 'OK');
		Tpl::showpage('store_joinin_apply');	
	}	
	
	private function show_join_message($message, $btn_next = FALSE, $step = 3, $sub_step =4, $btn_caption='下一步') {
		Tpl::output('joinin_detail', $this->joinin_detail);
        Tpl::output('joinin_message', $message);
        Tpl::output('btn_next', $btn_next);
		Tpl::output('btn_caption', $btn_caption);
        Tpl::output('step', $step);
		Tpl::output('sub_step', $sub_step);
        Tpl::showpage('store_joinin_apply'.$this->Link_pre);
    }

    private function upload_image($file) {
        $pic_name = '';
        $upload = new UploadFile();
        $uploaddir = ATTACH_PATH.DS.'store_joinin'.DS;
        $upload->set('default_dir',$uploaddir);
        $upload->set('allow_type',array('jpg','jpeg','gif','png'));
        if (!empty($_FILES[$file]['name'])){
            $result = $upload->upfile($file);
            if ($result){
                $pic_name = $upload->file_name;
                $upload->file_name = '';
            }
        }
        return $pic_name;
    }

	/**
	 * 检查店铺名称是否存在
	 *
	 * @param 
	 * @return 
	 */
	public function checknameOp() {
		/**
		 * 实例化卖家模型
		 */
		$model_store	= Model('store');
		$store_name = $_GET['store_name'];
		$store_info = $model_store->getStoreInfo(array('store_name'=>$store_name));
		if(!empty($store_info['store_name']) && $store_info['member_id'] != $_SESSION['member_id']) {
			echo 'false';
		} else {
			echo 'true';
		}
	}
}
