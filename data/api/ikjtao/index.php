<?php
class ikjtao_item {
	public function __construct(){
        require('TopClient.php');
        require('RequestCheckUtil.php');		
	}
    public function fetch($ikj_oInfo) {
		
        $tb_top = new TopClient;
        $tb_top->appkey = C('ikjtao_app_key');
        $tb_top->secretKey = C('ikjtao_secret_key');
        $req = $this->load_api('OrderPushRequest');
        $req->setDutyPaid($ikj_oInfo['dutyPaid']);
        $req->setConsumptionDutyAmount($ikj_oInfo['consumptionDutyAmount']);
        $req->setAddedValueTaxAmount($ikj_oInfo['addedValueTaxAmount']);
        $req->setGrossWeight($ikj_oInfo['grossWeight']);
        $req->setInsuranceFee($ikj_oInfo['insuranceFee']);
        $req->setTariffAmount($ikj_oInfo['tariffAmount']);
        $req->setOrderNo($ikj_oInfo['orderNo']);
        $req->setOrderAmount($ikj_oInfo['orderAmount']);
        $req->setPostage($ikj_oInfo['postage']);
        $req->setTaxAmount($ikj_oInfo['taxAmount']);
        $req->setDisAmount($ikj_oInfo['disAmount']);
        $req->setPayAmount($ikj_oInfo['payAmount']);
        $req->setPaymentMode($ikj_oInfo['paymentMode']);
        $req->setPaymentNo($ikj_oInfo['paymentNo']);
        $req->setOrderSeqNo($ikj_oInfo['orderSeqNo']);
        $req->setName($ikj_oInfo['name']);
        $req->setIdNum($ikj_oInfo['idNum']);
        $req->setAddTime($ikj_oInfo['addTime']);
        $req->setPayTime($ikj_oInfo['payTime']);
        $req->setBuyerAccount($ikj_oInfo['buyerAccount']);
        $req->setConsignee($ikj_oInfo['consignee']);
        $req->setConsigneeMobile($ikj_oInfo['consigneeMobile']);
        $req->setProvince($ikj_oInfo['province']);
        $req->setCity($ikj_oInfo['city']);
        $req->setDistrict($ikj_oInfo['district']);
        $req->setConsigneeAddr($ikj_oInfo['consigneeAddr']);
        $req->setLogisticsName($ikj_oInfo['logisticsName']);
        $req->setGoods($ikj_oInfo['goods']);
        
        $resp = $tb_top->execute($req);
        
        if (!isset($resp)) {
            return false;
        }
        return $resp;
    }

    public function get_id($url) {
        $id = 0;
        $parse = parse_url($url);
        if (isset($parse['query'])) {
            parse_str($parse['query'], $params);
            if (isset($params['id'])) {
                $id = $params['id'];
            } elseif (isset($params['item_id'])) {
                $id = $params['item_id'];
            } elseif (isset($params['default_item_id'])) {
                $id = $params['default_item_id'];
            }
        }
        return $id;
    }

	public function load_api($api_name)	{
		require_once('request/'.$api_name.'.php');
		return new $api_name;
	}
}
