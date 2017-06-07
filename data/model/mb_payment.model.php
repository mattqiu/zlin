<?php
/**
 * 手机支付方式
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
class mb_paymentModel extends Model {

    //开启状态标识
    const STATE_OPEN = 1;
	//总平台手机支付
    const INDEX_GENERAL_ID = 0;

    public function __construct() {
        parent::__construct('mb_payment');
    }
	
	/**
	 * 初始化店铺支付方式
	 *
	 * @param array $param 更新数据
	 * @return bool 布尔类型的返回结果
	 */
	public function InitPayment($store_id,$token='shop'){
		$data = array();		
		
		$data['payment_code'] = 'alipay';
		$data['payment_name'] = '支付宝';
		$data['payment_config'] = '';
		$data['payment_state'] = '0';
		$data['store_id'] = empty($store_id)?0:$store_id;
		$data['token'] = $token;
		$check = $this->add($data);		

		$data['payment_code'] = 'wxpay';
		$data['payment_name'] = '微信支付';
		$data['payment_config'] = '';
		$data['payment_state'] = '0';
		$data['store_id'] = empty($store_id)?0:$store_id;
		$data['token'] = $token;
		$check = $this->add($data);		
		
		$data['payment_code'] = 'llpay';
		$data['payment_name'] = '连连支付';
		$data['payment_config'] = 'a:4:{s:13:"llpay_partner";s:0:"";s:13:"llpay_encrypt";s:0:"";s:9:"llpay_rsa_key";s:0:"";s:10:"llpay_md5_key";s:0:"";}';
		$data['payment_state'] = '0';
		$data['store_id'] = empty($store_id)?0:$store_id;
		$data['token'] = $token;
		$check = $this->add($data);
		
		return $check;
	}

	/**
	 * 读取单行信息
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getMbPaymentInfo($condition = array(),$store_id = self::INDEX_GENERAL_ID,$token='root') {
		$condition['store_id'] = $store_id;
		$condition['token']=$token;	
		$payment_info = $this->where($condition)->find();
        if (!empty($payment_info['payment_config'])) {			
            $payment_info['payment_config'] = unserialize($payment_info['payment_config']);				
			
        }
        if (isset($payment_info['payment_config']) && !is_array($payment_info['payment_config'])) {
            $payment_info['payment_config'] = array();
			
        }

        return $payment_info;
	}

	/**
	 * 读开启中的取单行信息
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getMbPaymentOpenInfo($condition = array(),$store_id = self::INDEX_GENERAL_ID,$token='root') {
	    $condition['payment_state'] = self::STATE_OPEN;
        return $this->getMbPaymentInfo($condition,$store_id,$token);
	}

	/**
	 * 读取多行
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getMbPaymentList($condition = array(),$store_id = self::INDEX_GENERAL_ID,$token='root'){
		$condition['store_id'] = $store_id;
		$condition['token']=$token;	
        $payment_list = $this->where($condition)->select();
        foreach ($payment_list as $key => $value) {
            if($value['payment_state'] == self::STATE_OPEN) {
                $payment_list[$key]['payment_state_text'] = '开启中';
            } else {
                $payment_list[$key]['payment_state_text'] = '关闭中';
            }
        }
        return $payment_list;
	}

	/**
	 * 读取开启中的支付方式
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getMbPaymentOpenList($condition = array(),$store_id = self::INDEX_GENERAL_ID,$token='root'){
	    $condition['payment_state'] = self::STATE_OPEN;
	    return $this->getMbPaymentList($condition,$store_id,$token);
	}

	/**
	 * 更新信息
	 *
	 * @param array $param 更新数据
	 * @return bool 布尔类型的返回结果
	 */
	public function editMbPayment($data, $condition){
        if(isset($data['payment_config'])) {
            $data['payment_config'] = serialize($data['payment_config']);
        }
		return $this->where($condition)->update($data);
	}
}