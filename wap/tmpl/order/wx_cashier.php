<?php
require '../../../mobile/config/config.ini.php';
require_once "../../jssdk.php";
$jssdk = new JSSDK(WX_APIID, WX_APPSECRET);
$signPackage = $jssdk->GetSignPackage();
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-touch-fullscreen" content="yes" />
<meta name="format-detection" content="telephone=no"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<meta name="msapplication-tap-highlight" content="no" />
<meta name="viewport" content="initial-scale=1,maximum-scale=1,minimum-scale=1" />
<title>店铺快捷收银台</title>
<link rel="stylesheet" type="text/css" href="../../css/base.css">
<link rel="stylesheet" type="text/css" href="../../css/member.css">
<link rel="stylesheet" type="text/css" href="../../css/common.css">
<link rel="stylesheet" type="text/css" href="../../css/cart.css">
<style>
.wx-bottom-mask{
    z-index: 8;
    margin-top: 10px;
    left: 0;
    right: 0;
    display: block;
}
.wx-bottom-mask-paystep{
    display: block;
    top: 2rem;
    z-index: 8;
    bottom: 0;
    left: 0;
    right: 0;
    min-height: 12rem;
    background: #FFF;
}
</style>
</head>
<body>
<header id="header" class="fixed">
  <div class="header-wrap">
    <div class="header-l"><a href="javascript:history.go(-1);"> <i class="back"></i> </a> </div>
    <div class="header-title">
      <h1>确定收银</h1>
    </div>
    <div class="header-r">
    <a href="javascript:void(0);" id="header-nav"><i class="more"></i><sup></sup></a>
    </div>
  </div>
  <div class="qm-nav-layout">
    <div class="qm-nav-menu"> <span class="arrow"></span>
      <ul>
        <li><a href=""><i class="home"></i>首页</a></li>
        <li><a href="../seller/wx_barcode.php" id="add_barcode"><i class="cart"></i>条码录入<sup></sup></a></li>
      </ul>
    </div>
  </div>
</header>
<div class="qm-main-layout"> 
  <!--底部总金额固定层End-->
  <div id="list-payment-wrapper" class="wx-bottom-mask">
    
    <div class="wx-bottom-mask-paystep">
      <div class="qm-bottom-mask-top" id="wrapperUseBalance" style="display: none;">
        <p class="qm-cart-num">会员共购买了<em id="goodsTotal">0</em>件商品，消费总额<em id="onlineTotal">0.00</em>元</p>
        <p id="isGoodsed" class="qm-cart-payed-tips" style="display:none;">其中有<em id="gcodeTotal">0</em>件商品<em id="goodscodeGroup"></em>未结算，请重新扫码</p>
        <p id="isPayed" class="qm-cart-payed-tips" style="display:none;"></p>
      </div>
      <div class="qm-inp-con qm-inp-cart">
        <ul class="form-box" id="internalPay">
          <li class="form-item">
            <div class="input-box"> 
              <span class="txt">商&nbsp;品&nbsp;条&nbsp;码&nbsp;</span>
              <input type="text" class="inp" id="g_barcode" name="g_barcode" value="" autocomplete="off" placeholder="扫一扫商品条形码">
              <i class="scan-icon" id="scanQRCode1"></i>
              <span class="input-del"></span>
            </div>
          </li>
          <li class="form-item">
            <div class="input-box"> 
              <span class="txt">会&nbsp;员&nbsp;信&nbsp;息&nbsp;</span>
              <input type="text" class="inp" id="member_name" name="member_name" value="" autocomplete="off" placeholder="扫一扫会员推广码">
              <i class="scan-icon" id="scanQRCode2"></i>
	      <span class="input-del"></span>
            </div>
          </li>
          <li class="form-item" id="otherMoney" style="display: none;">
            <div class="input-box"> 
              <span class="txt">其&nbsp;他&nbsp;金&nbsp;额&nbsp;</span>
              <input type="text" class="inp" id="other_price" name="other_price" value="" autocomplete="off" placeholder="请输入未计算的金额">
              <span class="input-del"></span>
            </div>
          </li>
          
          <li class="form-item" id="wrapperUseRCBpay" style="display: none;">
            <div class="input-box pl5">
              <label>
              <input type="checkbox" class="checkbox" id="useRCBpay" autocomplete="off">使用充值卡 <span class="power"><i></i></span></label>
              <p>剩余充值卡 ￥<em id="availableRcBalance">0</em></p>
            </div>
          </li>
          <li class="form-item" id="wrapperuseJFpay" style="display: none;">
            <div class="input-box pl5">
              <label><input type="checkbox" class="checkbox" id="useJFpay" autocomplete="off">使用云币支付    <span class="power"><i></i></span> </label>
              <p id="isPayedPoints"></p>
              <p>剩余云币 ￥<em id="availableJFPoints"></em></p>
            </div>
          </li>
          <li class="form-item" id="wrapperUsePDpay" style="display: none;">
            <div class="input-box pl5">
              <label><input type="checkbox" class="checkbox" id="usePDpay" autocomplete="off">使用积分 <span class="power"><i></i></span> </label>
              <p>剩余积分 ￥<em id="availablePredeposit">0</em></p>
            </div>
          </li>
        </ul>
        <div style="display: block;margin-top: 0.5rem; background-color: #F5F5F5;"></div>
		    <ul>
		     <li class="form-item" id="actualMoney" >
	            <div class="input-box"> 
	              <span class="txt">实&nbsp;收&nbsp;金&nbsp;额&nbsp;</span>
	              <input type="text" class="inp" id="actual_price" name="actual_price" value="" autocomplete="off" placeholder="请输入实收的金额">
	              <span class="input-del"></span>
	            </div>
	          </li>
		     <li class="form-item" id="wrapperuseJFpay">
	            <div class="input-box">
	              <span class="txt">应付云币</span>
	              <input type="text" class="inpt" id="pay_points" name="pay_points" value="" autocomplete="on" disabled="disabled" placeholder="自动计算不需输入">
	              <p>剩余云币 ￥<em id="availablePoints">0</em></p>
	            </div>
	          </li>
	          <li class="form-item" id="wrapperTotelPrice">
	            <div class="input-box">
	            	<span class="txt">应付金额</span>
	              	<p style="font-size:1.2rem;color:#DB4453;">
	              	￥<em id="availableTotal" style="font-size:1.2rem;color:#DB4453;">0.00</em>
	              	</p>
	            </div>
	          </li>
	          
	        </ul>
        
        <div class="qm-pay" id="payment_list">
          <div class="spacing-div"><span>在线支付方式</span></div>
          <div class="pay-sel">
          	<label ><input type="radio" name="payment_code" class="checkbox" id="xjpay" autocomplete="off"><span class="xjpay">现金</span></label>
            <label ><input type="radio" name="payment_code" class="checkbox" id="skpay" autocomplete="off"><span class="skpay">刷卡</span></label>
          	  	<label ><input type="radio" name="payment_code" class="checkbox" id="wxpay" autocomplete="off"  checked="true"><span class="wxpay">微信</span></label>
            <label ><input type="radio" name="payment_code" class="checkbox" id="alipay" autocomplete="off"><span class="alipay">支付宝</span></label>
            <label ><input type="radio" name="payment_code" class="checkbox" id="llpay" autocomplete="off"><span class="llpay">银行卡</span></label>
          </div>
        </div>
        <div class="pay-btn" id="btnToCreateOrder">
        	<input type="hidden" id="member_id" name="member_id" value="">
        	<input type="hidden" id="store_id" name="store_id" value="">
        	<input type="hidden" id="g_list" name="g_list" value="">
        	<input type="hidden" id="g_price" name="g_price" value="">
        	<input type="hidden" id="rebate_price" name="rebate_price" value="">
		<input type="hidden" id="pay_code" name="pay_code" value="">
			<input type="hidden" id="pd_amount" name="pd_amount" value="0">
			<input type="hidden" id="rcb_amount" name="rcb_amount" value="0">
        	<a href="javascript:void(0);"  class="btn-l">确认生成订单</a>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script>
  // 注意：所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。 
  // 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
  // 完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
  wx.config({
	  debug: false,
    appId: '<?php echo $signPackage["appId"];?>',
    timestamp: <?php echo $signPackage["timestamp"];?>,
    nonceStr: '<?php echo $signPackage["nonceStr"];?>',
    signature: '<?php echo $signPackage["signature"];?>',
    jsApiList: [
                'checkJsApi',
                'scanQRCode'
              ]
  });
</script>
<script type="text/javascript" src="../../js/core/zepto.min.js"></script>
<script type="text/javascript" src="../../js/core/template.js"></script> 
<script type="text/javascript" src="../../js/core/simple-plugin.js"></script> 
<script type="text/javascript" src="../../js/config.js"></script> 
<script type="text/javascript" src="../../js/common.js"></script>
<script type="text/javascript" src="../../js/tmpl/seller/add_cashier.js"></script>
<script type="text/javascript" src="../../js/footer.js"></script>
<script>

	var store_id = getQueryString("store_id");
	if(store_id!=''){
	$('#store_id').val(store_id); 
	$('#add_barcode').attr('href',WapSiteUrl+"/tmpl/seller/wx_barcode.php?store_id="+store_id); 
	}
var by_val = getQueryString("by_val");
var _barcode = '';
if(by_val!=''){
	var byvalArr = by_val.split('|');
	_barcode = byvalArr[0];
	$('#g_barcode').val(_barcode);
	$('#goodsTotal').html(byvalArr[1]);
	$('#onlineTotal').html(byvalArr[2]);
	$('#availableTotal').html(byvalArr[2]);
}
var g_barcode = getQueryString("g_barcode");
if(g_barcode!=''){
	if(_barcode!=''){
		$('#g_barcode').val(_barcode);
	}else{
	$('#g_barcode').val(g_barcode); 
	}
}
var g_marketprice = getQueryString("g_marketprice");
if (g_marketprice != ""){
	var goodsTotal = $('#goodsTotal').html();
	_goodsTotal = parseFloat(goodsTotal) + 1;
    $('#goodsTotal').html(_goodsTotal);
	var market_price = $('#onlineTotal').html();
    if(market_price == ''){
    	market_price = 0.00;
    }
    _marketprice = parseFloat(parseFloat(market_price) + parseFloat(g_marketprice)).toFixed(2);
	$('#onlineTotal').html(_marketprice);
	$('#availableTotal').html(_marketprice);
    $('#wrapperUseBalance').show();
}
wx.ready(function () {
	document.querySelector('#scanQRCode1').onclick = function () {
	    wx.scanQRCode({
	      needResult: 1,
	      desc: 'scanQRCode desc',
	      success: function (res) {
	      scancode = res.resultStr;
	      //扫码后获取结果参数赋值给Input
              	if(scancode.indexOf(",")>=0){
                 	var tempArray = scancode.split(',');
                 	var g_barcode = tempArray[1];
                 	var in_barcode = $('#g_barcode').val();
                 	if(in_barcode){
                 		$('#g_barcode').val(in_barcode + ',' + g_barcode);
                    }else{
                 	$('#g_barcode').val(g_barcode);
                    }                 	
	              	$('#add_barcode').attr('href',WapSiteUrl+"/tmpl/seller/wx_barcode.php?store_id="+store_id+"&g_barcode="+g_barcode);
              	}else{
              		var g_barcode = scancode;
              	}
	          	
	    	  	$.getJSON(ApiUrl + "/index.php?act=store_order&op=ajax_get_goodsID", {scancode: g_barcode,store_id:store_id}, function(result) {
	                
	    	  		var goodsTotal = $('#goodsTotal').html();
					if (!result.error) {
                    _goodsTotal = parseFloat(goodsTotal) + 1;
                    
                    $('#goodsTotal').html(_goodsTotal);
					    			
	                    var data = result.datas;
	                    goods_id = data.goods_info.goods_id;
	                    g_marketprice = data.goods_info.goods_marketprice;
	                    var market_price = $('#onlineTotal').html();
	                    if(market_price == ''){
	                    	market_price = 0.00;
			            }
			            _marketprice = parseFloat(parseFloat(market_price) + parseFloat(g_marketprice)).toFixed(2);
	                    $('#onlineTotal').html(_marketprice);
			    $('#availableTotal').html(_marketprice);
	                    $('#wrapperUseBalance').show();
	                }else {
		                if(confirm(result.error+'\n\r是否新添商品')){
		                	var val_barcode = $('#g_barcode').val();
		                	var goodsTotal = $('#goodsTotal').html();
		                	var onlineTotal = $('#onlineTotal').html();
		                	var availableTotal = $('#availableTotal').html();
		                	var by_val = val_barcode+'|'+goodsTotal+'|'+onlineTotal+'|'+availableTotal;
		                	window.location.href=WapSiteUrl+"/tmpl/seller/wx_barcode.php?store_id="+store_id+"&g_barcode="+g_barcode+'&by_val='+by_val;
				        }else{
				        	var gcodeTotal = $('#gcodeTotal').html();
		                    		_gcodeTotal = parseFloat(gcodeTotal) + 1;
		                    		$('#gcodeTotal').html(_gcodeTotal);
				        	$('#goodscodeGroup').append(g_barcode);
				        	$('#isGoodsed').show();
				        	$('#wrapperUseBalance').show();
				        	$('#otherMoney').show();
				}
	                }
	            })
	      }
	    });
	  };
   });
   $("#scanQRCode2").click(function() {
		var store_id = getQueryString("store_id");
		var g_barcode = $('#g_barcode').val();
		if(g_barcode==''){
			alert('没有扫码商品条码');
			$("#scanQRCode1").click();
			return false;
		}
		wx.scanQRCode({
			// 默认为0，扫描结果由微信处理，1则直接返回扫描结果
	   		needResult : 1,
	       	desc : 'scanQRCode desc',
	      	success : function(res) {
	        	//扫码后获取结果参数赋值给Input
	         	var scancode = res.resultStr;
			if(scancode.indexOf(",")>=0){
                 		var tempArr = scancode.split(',');
                 		scancode = tempArr[1];
			}
			
		    	$.getJSON(ApiUrl + "/index.php?act=store_order&op=ajax_member_scancode", 
				    	{scancode: scancode,store_id:store_id,g_barcode:g_barcode}, function(result) {
		    		if(store_id>0){
		    			$('#store_id').val(store_id);
		    		}
			    if (!result.error) {					
		            	var data = result.datas;
						
			          	//读取用户余额信息,并显示到页面
						_memberAvailable(data);
		            }else {
			       		alert(result.error);
			       		$('#member_id').val('');
			       		$('#availablePredeposit').html(0);	
			            $('#availableRcBalance').html(0);			        	
			            $('#availablePoints').html(0);
						$('#availableTotal').html($('#onlineTotal').html());
					    	$('#pay_points').val(0);
						$('#rebate_price').val(0);
						$('#wrapperUsePDpay').hide();
						$('#wrapperUseRCBpay').hide();
		            }
		    	})
	          }
	      });
	  });
	//为了走逆向的流程 所以必须记录每种支付方式 所扣除的金额
   czk_pledge = 0;//充值卡
   jf_pledge = 0; //抵扣云币的金额
   yue_pledge = 0;//余额
   function _memberAvailable(data){
   	//console.log(data);
   	goods_sum = data.goodsInfo.goods_sum; //商品总数
      	goods_price = data.goodsInfo.goods_price; //商品价
  		goods_marketprice = data.goodsInfo.goods_marketprice; //吊牌价
       vip_price = data.goodsInfo.vip_price; //会员价
       vip_points = data.goodsInfo.vip_points; //会员云币
       rebate_price = data.goodsInfo.rebate_price;//返利云币
       member_id = data.memberInfo.member_id; //会员ID
       member_name = data.memberInfo.member_name;//会员登录名
       member_mobile = data.memberInfo.member_mobile;//会员手机号
       member_points = data.memberInfo.member_points;//会员云币
       available_predeposit = data.memberInfo.available_predeposit;//会员积分余额
       available_rc_balance = data.memberInfo.available_rc_balance;//会员充值卡余额
       
       if(member_id>0){
	       	$('#member_id').val(member_id);			        	
		}
   	if(member_name == ''){
       	$('#member_name').val(member_mobile);
       }else{
	    	$('#member_name').val(member_name);
	    }
       $('#availablePredeposit').html(available_predeposit);	
       $('#availableRcBalance').html(available_rc_balance);			        	
       $('#availablePoints').html(member_points-vip_points);			        	
	    if(available_predeposit>0){
	       	$('#wrapperUsePDpay').show();
	   	}else{
	   		$('#wrapperUsePDpay').hide();
	   	}
	    if(available_rc_balance>0){
	       	$('#wrapperUseRCBpay').show();
		}else{
			$('#wrapperUseRCBpay').hide();
		}
	    if(goods_marketprice>0){
	    	$('#onlineTotal').html(goods_marketprice);
		}
	    if(vip_price>0){
	    	$('#availableTotal').html(vip_price);
		}else{
		    $('#availableTotal').html(goods_price);
		}
		if(vip_points>0){
	   		$('#pay_points').val(vip_points);			        	
		}else{
			$('#pay_points').val(0);
		}
		if(rebate_price>0){
	   		$('#rebate_price').val(rebate_price);			        	
		}else{
			$('#rebate_price').val(0);
		}
		$('#g_list').val(data.goodsInfo.goods_list);
   	
	    //初始化
	    pd_pay = 0;
	    rcb_pay = 0;
	    jf_pay = 0;
	    //
	    payable_amount = 0.00; //应付金额
	    pay_amount_online = $("#availableTotal").html(); //需要支付金额
	    //用户账号的余额 初始定义
	    rcb_price = available_rc_balance;
	    points_price = 0;
	    pd_price = available_predeposit;
	    
		//触发充值卡
	    $("#useRCBpay").click(function() {
	    	
	    	//选择使用充值卡
	        if ($(this).prop("checked")) {
	        	if(pay_amount_online<=0){
	        		if(confirm('订单实际支付的金额已经为零，无须再使用充值卡支付\n\r是否坚持使用该支付方式')){
	        			$("#usePDpay").parents("label").attr("class",'');
	        			$('#usePDpay').trigger("click");
	        		}else{
	        			$("#useRCBpay").parents("label").attr("class",'');
	        			return false;
	        		}
	        	}
	            rcb_pay = 1
	            if(jf_pay==1){
	            	$("#isPayed").html("充值卡和云币，不可同时使用！");
					$("#isPayed").show();
	            }
	            //充值卡和云币不可叠加使用
	            $("#useJFpay").parents("label").attr("class",'');
	            jf_pay == 0
	            //因为互斥的缘故 所以还需加回云币的抵扣部分金额
	            pay_amount_online = parseFloat(pay_amount_online) + parseFloat(jf_pledge);
	            
	            //需要初始化
	            jf_pledge = 0;
	            if(pay_amount_online >= rcb_price && pay_amount_online > 0){
	            	czk_pledge =  parseFloat(rcb_price);
	            	pay_amount_online = parseFloat(pay_amount_online) -  parseFloat(rcb_price);
	            }else{
	            	czk_pledge = parseFloat(pay_amount_online);
	            	pay_amount_online = 0.00;
	            }	            
	            $('#availableRcBalance').html(parseFloat(rcb_price) -  parseFloat(czk_pledge));
	        } else {
	        	rcb_pay = 0;
	        	jf_pay=0; //因为和云币时互斥 取消充值卡的时候证明在此之前 云币方式一定是无法使用的
	            //不管又没选择其他方式,总金额里 都应该扣回 之前使用过的充值卡
	            //虽然充值卡和云币互斥，此处没有必要在加回或多扣除云币
	            pay_amount_online = parseFloat(pay_amount_online) + parseFloat(czk_pledge);
	            $('#availableRcBalance').html(parseFloat(rcb_price));
	            //加回去后 需要初始化
	            czk_pledge = 0;
	        }
	        //更新订单总金额
	        $("#availableTotal").html(parseFloat(pay_amount_online).toFixed(2));
	        $("#rcb_amount").val(czk_pledge);
	        //如果订单金额为0，则隐藏支付方式列表
	        if(pay_amount_online==0.00){
	        	//在线支付的金额为0时 支付方式可直接改为 余额支付
	    		payment_code="predeposit";
	    		$('#pay_code').val("predeposit");
	        	$("#payment_list").hide();
	        }else{
	        	$("#payment_list").show();
	        }
	        
	    });
	    
	    //触发云币
	    $("#useJFpay").click(function() {
	    	//选择使用云币
	        if ($(this).prop("checked")) {
	            jf_pay = 1;
	            if(rcb_pay==1){
	            	$("#isPayed").html("云币和充值卡，不可同时使用！");
					$("#isPayed").show();
	            }
	            //充值卡和云币不可叠加使用
	            $("#useRCBpay").parents("label").attr("class",'');
	            rcb_pay = 0;
	            //因为互斥的缘故 所以还需加回充值卡的抵扣部分金额
	            pay_amount_online = parseFloat(pay_amount_online) + parseFloat(czk_pledge);
	            //需要初始化
	            czk_pledge = 0;
	        	if(pay_amount_online >= points_price && pay_amount_online > 0){
	        		jf_pledge =  parseFloat(points_price);
	            	pay_amount_online =  parseFloat(pay_amount_online) -  parseFloat(points_price);
	            }else{
	            	jf_pledge =  parseFloat(pay_amount_online);
	            	pay_amount_online = 0.00;
	            }	        	
	        	$('#availableJFPoints').html(parseFloat(points_price) -  parseFloat(jf_pledge));
	        } else {
	        	jf_pay  =0; 
	        	rcb_pay = 0;//因为和充值卡时互斥 取消 云币的时候证明在此之前 充值卡方式一定是无法使用的
	            //不管又没选择其他方式,总金额里 都应该扣回 之前使用过的云币
	            //虽然充值卡和云币互斥，此处没有必要在加回或多扣除充值卡
	            pay_amount_online = parseFloat(pay_amount_online) + parseFloat(jf_pledge);
	            $('#availableJFPoints').html(parseFloat(points_price));
	            //加回去后 需要初始化
	            jf_pledge = 0;
	        }
	        $("#availableTotal").html(parseFloat(pay_amount_online).toFixed(2));
	        $("#jf_amount").val(jf_pledge);
	        //如果订单金额为0，则隐藏支付方式列表
	        if(parseFloat(pay_amount_online)==0){
	        	//在线支付的金额为0时 支付方式可直接改为 余额支付
	    		payment_code="predeposit";
	    		$('#pay_code').val("predeposit");
	        	$("#payment_list").hide();
	        }else{
	        	$("#payment_list").show();
	        }
	    });
	    //触发积分余额
	    $("#usePDpay").click(function() {
	    	//选择使用积分余额
	        if ($(this).prop("checked")) {
	        	if(pay_amount_online<=0){
	        		if(confirm('订单实际支付的金额已经为零，无须再使用积分支付\n\r是否坚持使用该支付方式')){
	        			$("#useRCBpay").parents("label").attr("class",'');
	        			$('#useRCBpay').trigger("click");
	        		}else{
	        			$("#usePDpay").parents("label").attr("class",'');
	        			return false;
	        		}
	        	}
	            pd_pay = 1
	            //选择使用余额支付
	            if(parseFloat(pay_amount_online) >= parseFloat(pd_price) && parseFloat(pay_amount_online)!=0){
	            	yue_pledge = pd_price;
	            	pay_amount_online = pay_amount_online - pd_price;
	            }else{
	            	yue_pledge = pay_amount_online;
	            	pay_amount_online = 0.00;
	            }
	            $('#availablePredeposit').html(pd_price-yue_pledge);
	        } else {
	        	pd_pay = 0
	            //不管又没选择其他方式,总金额里 都应该扣回 之前使用过的余额
	        	pay_amount_online = parseFloat(pay_amount_online) + parseFloat(yue_pledge);
	        	$('#availablePredeposit').html(parseFloat(pd_price));
	        	//加回去后 需要初始化
	        	yue_pledge = 0;
	        }
	        $("#availableTotal").html(parseFloat(pay_amount_online).toFixed(2));
	        $("#pd_amount").val(yue_pledge);
	        //如果订单金额为0，则隐藏支付方式列表
	        if(parseFloat(pay_amount_online)==0){
	        	//在线支付的金额为0时 支付方式可直接改为 余额支付
	    		payment_code="predeposit";
	    		$('#pay_code').val("predeposit");
	        	$("#payment_list").hide();
	        }else{
	        	$("#payment_list").show();
	        }
	    });
	}
</script>
</html>
