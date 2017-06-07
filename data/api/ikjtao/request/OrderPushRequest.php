<?php
/**
 * TOP API: ikjtao订单推送APIrequest
 * 
 * @author auto create
 * @since 1.0, 2013-01-16 16:30:54
 */
class OrderPushRequest
{
	/** 
	 * 是否含税
	 * 类型 ：boolean
	 * 说明：为true goods=>price 含税，需保证运费为0 ； 为false 销售单价不含税 
	 **/
	private $dutyPaid;
	/** 
	 * 消费税 = 商品累计消费税之和
	 **/
	private $consumptionDutyAmount;
	/** 
	 * 增值税 = 商品累计增值税之和
	 **/
	private $addedValueTaxAmount;
	/**
	 * 毛重 
	 **/
	private $grossWeight;
	/**
	 * 保价费（无保价费时自动设置为0）
	 **/
	private $insuranceFee;
	/** 
	 * 关税额（免税请设置0）
	 **/
	private $tariffAmount;
	
	/** 
	 * 订单号
	 **/
	private $orderNo;
	/** 
	 * 订单总金额=运费+关额+消费税+增值税+保费+商品金额总和
	 **/
	private $orderAmount;
	/** 
	 * 运费
	 **/
	private $postage;
	/** 
	 * 税费合计
	 **/
	private $taxAmount;
	/** 
	 * 优惠金额
	 **/
	private $disAmount;
	/** 
	 * 支付金额=订单总金额-优惠金额
	 **/
	private $payAmount;
	/** 
	 * 支付方式
	 **/
	private $paymentMode;
	/** 
	 * 交易流水号
	 **/
	private $paymentNo;
	/** 
	 * 支付订单号(没有则同orderNo)
	 **/
	private $orderSeqNo;
	/** 
	 * 买家真实姓名
	 **/
	private $name;
	/** 
	 * 买家真实身份证号，末位为X则大写
	 **/
	private $idNum;
	/** 
	 * 下单时间 yyyy-MM-dd HH:mm:ss
	 **/
	private $addTime;
	/** 
	 * 支付时间 yyyy-MM-dd HH:mm:ss
	 **/
	private $payTime;
	
	/** 
	 * 买家账号
	 **/
	private $buyerAccount;
	/**
	 * 收件人
	 **/
	private $consignee;
	/**
	 * 收件人手机
	 **/
	private $consigneeMobile;
	/**
	 * 省份(北京、天津、上海、重庆直接填城市名)
	 **/
	private $province;
	/**
	 * 城市
	 **/
	private $city;
	/**
	 * 区县
	 **/
	private $district;
	/**
	 * 街道地址(不包含省市区)
	 **/
	private $consigneeAddr;
	/**
	 * 物流名称
	 **/
	private $logisticsName;
	
	/**
	 * 商品列表
	 * 
	 */
	private $goods = array();
	/**
	 * 产品名称
	 **/
	private $productName;
	/**
	 * 产品编码
	 **/
	private $productNo;
	/**
	 * 价格
	 **/
	private $price;
	/**
	 * 数量
	 **/
	private $qty;
	
	/**
	 * 业务参数
	 * 
	 * @param unknown $dutyPaid
	 */
	private $apiParas = array();
	
	public function setDutyPaid($dutyPaid)
	{
		$this->dutyPaid = $dutyPaid;
		$this->apiParas["dutyPaid"] = $dutyPaid;
	}

	public function getDutyPaid()
	{
		return $this->dutyPaid;
	}

	public function setConsumptionDutyAmount($consumptionDutyAmount)
	{
		$this->consumptionDutyAmount = $consumptionDutyAmount;
		$this->apiParas["consumptionDutyAmount"] = $consumptionDutyAmount;
	}

	public function getConsumptionDutyAmount()
	{
		return $this->consumptionDutyAmount;
	}
	
	public function setAddedValueTaxAmount($addedValueTaxAmount)
	{
		$this->addedValueTaxAmount = $addedValueTaxAmount;
		$this->apiParas["addedValueTaxAmount"] = $addedValueTaxAmount;
	}
	
	public function getAddedValueTaxAmount()
	{
		return $this->addedValueTaxAmount;
	}
	
	public function setGrossWeight($grossWeight)
	{
		$this->grossWeight = $grossWeight;
		$this->apiParas["grossWeight"] = $grossWeight;
	}
	
	public function getGrossWeight()
	{
		return $this->grossWeight;
	}
	
	public function setInsuranceFee($insuranceFee)
	{		$this->insuranceFee = $insuranceFee;
		$this->apiParas["insuranceFee"] = $insuranceFee;
	}
	
	public function getInsuranceFee()
	{
		return $this->insuranceFee;
	}
	
	public function setTariffAmount($tariffAmount)
	{
		$this->tariffAmount = $tariffAmount;
		$this->apiParas["tariffAmount"] = $tariffAmount;
	}
	
	public function getTariffAmount()
	{
		return $this->tariffAmount;
	}
	public function setOrderNo($orderNo)
	{
		$this->orderNo = $orderNo;
		$this->apiParas["orderNo"] = $orderNo;
	}
	
	public function getOrderNo()
	{
		return $this->orderNo;
	}
	public function setOrderAmount($orderAmount)
	{
		$this->orderAmount = $orderAmount;
		$this->apiParas["orderAmount"] = $orderAmount;
	}
	public function getOrderAmount()
	{
		return $this->orderAmount;
	}
	public function setPostage($postage)
	{
		$this->postage = $postage;
		$this->apiParas["postage"] = $postage;
	}
	
	public function getPostage()
	{
		return $this->postage;
	}
	public function setTaxAmount($taxAmount)
	{
		$this->taxAmount = $taxAmount;
		$this->apiParas["taxAmount"] = $taxAmount;
	}
	
	public function getTaxAmount()
	{
		return $this->taxAmount;
	}
	
	public function setDisAmount($disAmount)
	{
		$this->disAmount = $disAmount;
		$this->apiParas["disAmount"] = $disAmount;
	}
	
	public function getDisAmount()
	{
		return $this->disAmount;
	}
	
	public function setPayAmount($payAmount)
	{
		$this->payAmount = $payAmount;
		$this->apiParas["payAmount"] = $payAmount;
	}
	public function getPayAmount()
	{
		return $this->payAmount;
	}
	
	public function setPaymentMode($paymentMode)
	{
		$this->paymentMode = $paymentMode;
		$this->apiParas["paymentMode"] = $paymentMode;
	}
	
	public function getPaymentMode()
	{
		return $this->paymentMode;
	}
	
	public function setPaymentNo($paymentNo)
	{
		$this->paymentNo = $paymentNo;
		$this->apiParas["paymentNo"] = $paymentNo;
	}
	
	public function getPaymentNo()
	{
		return $this->paymentNo;
	}
	
	public function setOrderSeqNo($orderSeqNo)
	{
		$this->orderSeqNo = $orderSeqNo;
		$this->apiParas["orderSeqNo"] = $orderSeqNo;
	}
	
	public function getOrderSeqNo()
	{
		return $this->orderSeqNo;
	}
	
	public function setName($name)
	{
		$this->name = $name;
		$this->apiParas["name"] = $name;
	}
	
	public function getName(){
		return $this->name;
	}
	
	public function setIdNum($idNum)
	{
		$this->idNum = $idNum;
		$this->apiParas["idNum"] = $idNum;
	}
	
	public function getIdNum()
	{
		return $this->idNum;
	}
	
	public function setAddTime($addTime)
	{
		$this->addTime = $addTime;
		$this->apiParas["addTime"] = $addTime;
	}
	
	public function getAddTime()
	{
		return $this->addTime;
	}
	
	public function setPayTime($payTime)
	{
		$this->payTime = $payTime;
		$this->apiParas["payTime"] = $payTime;
	}
	
	public function getPayTime()
	{
		return $this->payTime;
	}
	
	public function setBuyerAccount($buyerAccount)
	{
		$this->buyerAccount = $buyerAccount;
		$this->apiParas["buyerAccount"] = $buyerAccount;
	}
	
	public function getBuyerAccount()
	{
		return $this->buyerAccount;
	}
	
	public function setConsignee($consignee)
	{
		$this->consignee = $consignee;
		$this->apiParas["consignee"] = $consignee;
	}
	
	public function getConsignee()
	{
		return $this->consignee;
	}
	
	public function setConsigneeMobile($consigneeMobile)
	{
		$this->consigneeMobile = $consigneeMobile;
		$this->apiParas["consigneeMobile"] = $consigneeMobile;
	}
	
	public function getConsigneeMobile()
	{
		return $this->consigneeMobile;
	}
	
	public function setProvince($province)
	{
		$this->province = $province;
		$this->apiParas["province"] = $province;
	}
	
	public function getProvince()
	{
		return $this->province;
	}
	
	public function setCity($city)
	{
		$this->city = $city;
		$this->apiParas["city"] = $city;
	}
	
	public function getCity()
	{
		return $this->city;
	}
	
	public function setDistrict($district)
	{
		$this->district = $district;
		$this->apiParas["district"] = $district;
	}
	
	public function getDistrict()
	{
		return $this->district;
	}
	
	public function setConsigneeAddr($consigneeAddr)
	{
		$this->consigneeAddr = $consigneeAddr;
		$this->apiParas["consigneeAddr"] = $consigneeAddr;
	}
	
	public function getConsigneeAddr()
	{
		return $this->consigneeAddr;
	}
	
	public function setLogisticsName($logisticsName)
	{
		$this->logisticsName = $logisticsName;
		$this->apiParas["logisticsName"] = $logisticsName;
	}
	
	public function getLogisticsName()
	{
		return $this->logisticsName;
	}
	/**
	 * 商品列表
	 */
	public function setGoods($goods)
	{
		$this->goods = $goods;
		$this->apiParas["goods"] = $goods;
	}
	
	public function getGoods()
	{
		return $this->goods;
	}
	
	public function setProductName($productName)
	{
		$this->productName = $productName;
		$this->goods["productName"] = $productName;
	}
	
	public function getProductName()
	{
		return $this->productName;
	}
	
	public function setProductNo($productNo)
	{
		$this->productNo = $productNo;
		$this->goods["productNo"] = $productNo;
	}
	
	public function getProductNo()
	{
		return $this->productNo;
	}
	public function setPrice($price)
	{
		$this->price = $price;
		$this->goods["price"] = $price;
	}
	
	public function getPrice()
	{
		return $this->price;
	}
	
	public function setQty($qty)
	{
		$this->qty = $qty;
		$this->goods["qty"] = $qty;
	}
	
	public function getQty()
	{
		return $this->qty;
	}
	
	public function getApiParas()
	{
		return $this->apiParas;
	}
	
	public function getApiMethodName()
	{
		return "ikjtao.item.order";
	}
	
	public function check()
	{
		RequestCheckUtil::checkNotNull($this->dutyPaid,"dutyPaid");
		RequestCheckUtil::checkNotNull($this->consumptionDutyAmount,"consumptionDutyAmount");
		RequestCheckUtil::checkNotNull($this->addedValueTaxAmount,"addedValueTaxAmount");
		RequestCheckUtil::checkNotNull($this->grossWeight,"grossWeight");
		RequestCheckUtil::checkNotNull($this->insuranceFee,"insuranceFee");
		RequestCheckUtil::checkNotNull($this->tariffAmount,"tariffAmount");
		RequestCheckUtil::checkNotNull($this->orderNo,"orderNo");
		RequestCheckUtil::checkNotNull($this->orderAmount,"orderAmount");
		RequestCheckUtil::checkNotNull($this->postage,"postage");
		RequestCheckUtil::checkNotNull($this->taxAmount,"taxAmount");
		RequestCheckUtil::checkNotNull($this->disAmount,"disAmount");
		RequestCheckUtil::checkNotNull($this->payAmount,"payAmount");
		RequestCheckUtil::checkNotNull($this->paymentMode,"paymentMode");
		RequestCheckUtil::checkNotNull($this->paymentNo,"paymentNo");
		RequestCheckUtil::checkNotNull($this->orderSeqNo,"orderSeqNo");
		RequestCheckUtil::checkNotNull($this->addTime,"addTime");
		RequestCheckUtil::checkNotNull($this->payTime,"payTime");
		RequestCheckUtil::checkNotNull($this->consignee,"consignee");
		RequestCheckUtil::checkNotNull($this->consigneeMobile,"consigneeMobile");
		RequestCheckUtil::checkNotNull($this->province,"province");
		RequestCheckUtil::checkNotNull($this->city,"city");
		RequestCheckUtil::checkNotNull($this->district,"district");
		RequestCheckUtil::checkNotNull($this->consigneeAddr,"consigneeAddr");
		RequestCheckUtil::checkNotNull($this->logisticsName,"logisticsName");
		
	}
	
	public function putOtherTextParam($key, $value) {
		$this->apiParas[$key] = $value;
		$this->$key = $value;
	}
}
