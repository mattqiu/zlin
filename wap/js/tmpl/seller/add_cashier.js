$(function(){
	var rcb_pay = 0;
	var pd_pay = 0;
	var key = getCookie('key');
	if (!key) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    }
	
    payment_code = "";
    $("#alipay").click(function() {
        payment_code = "alipay";
        $('#pay_code').val("alipay");
    });
    $("#wxpay").click(function() {
        payment_code = "wxpay";
        $('#pay_code').val("wxpay");
    });
	$("#llpay").click(function() {
        payment_code = "llpay";
        $('#pay_code').val("llpay");
    });
	$("#xjpay").click(function() {
        payment_code = "xjpay";
        $('#pay_code').val("xjpay");
    });
	$("#skpay").click(function() {
        payment_code = "skpay";
        $('#pay_code').val("skpay");
    });
	var lock = true; //防止重复提交
    $("#btnToCreateOrder").click(function() {
    	
    	var key = getCookie('key');
    	var store_id = getQueryString("store_id");
    	if(!lock){
        	alert("订单已经生成成功，请客户确定去支付或收货！");
        	location.href = WapSiteUrl+'/tmpl/order/wx_cashier.php?store_id='+store_id;
        	return false;
        }
    	var member_name	= $("#member_name").val();
    	var member_id 	= $("#member_id").val();
        var g_barcode 	= $("#g_barcode").val();
        var goods_amount = $('#onlineTotal').html();
        var order_amount = $("#availableTotal").html();
        var g_list 	= $("#g_list").val();
        var vip_points = $('#pay_points').val();
	    var payment_code = $('#pay_code').val();
	    var g_price = $('#g_price').val();
	    var rebate_price = $('#rebate_price').val();
	    var pd_amount = $('#pd_amount').val();
	    var rcb_amount = $('#rcb_amount').val();
	    var actual_price = $("#actual_price").val();//实收金额
        if(store_id == ''){
        	alert("该店铺不存在，是非法经营！");
        	return false;
        }
        if(g_barcode == '' || g_barcode == 'undefined'){
        	alert("商品条形码必填！");
        	return false;
        }
        if(member_id == '' || member_id == 'undefined'){
        	alert("用户不存在，请提供正确的会员信息");
        	return false;
        }
        if(payment_code == '' || payment_code == 'undefined'){
        	alert("确定支付方式！");
        	return false;
        }
        $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=store_order&op=createOrder",
                data: {
                	key: key,store_id:store_id,member_id:member_id,g_barcode:g_barcode, 
                	g_price:g_price,rebate_price:rebate_price,g_list:g_list,
                	goods_amount:goods_amount,order_amount:order_amount,vip_points:vip_points,
                	pd_amount: pd_amount,rcb_amount:rcb_amount,payment_code:payment_code
                },
                dataType: "json",
                success: function(result) {
                	lock = false;
                	if (!CheckDataSucceed(result)){return false;}
                	var data = result.datas;
                	//console.log(data);return false;
                	if(data.error){
                		alert('订单创建失败！'+data.msg);
                		//location.href = WapSiteUrl+'/tmpl/order/wx_cashier.php?store_id='+store_id+'&g_barcode='+data.g_barcode+'&g_marketprice='+g_marketprice;
                		return true;
                	}else{
                		alert('订单创建成功！订单号是：'+data.pay_sn);
                		location.href = WapSiteUrl+'/tmpl/order/wx_cashier.php?store_id='+store_id;
                		return true;
                	}
                },
				error: function(){
			        ShowGetDataError();
		        }
            })
        
    });
    // 失去条码input并发生变化时触发的 
    //根据输入的条码查询商品是否存在
    $('#g_barcode').change(function (){ 
	    var g_barcode 	= $("#g_barcode").val();
	    //条码为空时，清空所有的值
	    if(g_barcode==''){
	    	$('#member_name').val('');
			$('#goodsTotal').html(0);
			$('#onlineTotal').html(0.00);
    		$('#availableTotal').html(0.00);
    		$('#gcodeTotal').html(0);
    		$('#goodscodeGroup').html('');
            $('#wrapperUseBalance').hide();
        	$('#isGoodsed').hide();
        	$('#wrapperUseBalance').hide();
        	$('#otherMoney').hide();
			$("#scanQRCode1").click();
			return false;
		}
	    var bcode_len = 1;
	    var store_id = getQueryString("store_id");
	    if(g_barcode.indexOf(",")>=0){
         	var tempArray = g_barcode.split(',');
         	bcode_len = tempArray.length;
         	g_barcode = tempArray[bcode_len-1];
      	}
	    _goodsNum = $('#goodsTotal').html();
	    _nogNum = $('#gcodeTotal').html();
	    //如果条码的数量大于等于已查的商品数量 就不在做查询了
	    if(bcode_len<=parseFloat(_goodsNum)+parseFloat(_nogNum)){
	    	if(confirm('已扫的商品可能出现丢失，建议重新扫码！')){
	    		$("#g_barcode").val(g_barcode);
	    		$('#goodsTotal').html(0);
				$('#onlineTotal').html(0.00);
	    		$('#availableTotal').html(0.00);
	    		$('#gcodeTotal').html(0);
	    		$('#goodscodeGroup').html('');
	            $('#wrapperUseBalance').hide();
	        	$('#isGoodsed').hide();
	        	$('#wrapperUseBalance').hide();
	        	$('#otherMoney').hide();
				$("#scanQRCode1").click();
	    	}else{
	    		return false;
	    	}	    	
	    }
	    $.ajax({
            type: "get",
            url: ApiUrl + "/index.php?act=store_order&op=ajax_get_goodsID",
            data: {
            	scancode:g_barcode,store_id:store_id
            },
            dataType: "json",
            success: function(result) {
            	var _barcode = $("#g_barcode").val();
				if (!result.error) {
                    var data = result.datas;
                    goods_id = data.goods_info.goods_id;
                    g_marketprice = data.goods_info.goods_marketprice;
                    if(_barcode.indexOf(",")>=0){
                    	var goodsTotal = $('#goodsTotal').html();
                        _goodsTotal = parseFloat(goodsTotal) + 1;
                        var market_price = $('#onlineTotal').html();
                        if(market_price == ''){
                        	market_price = 0.00;
    		            }
                  	}else{
                  		_goodsTotal = 1;
                  		market_price = 0.00;
                  	}                    
		            _marketprice = parseFloat(parseFloat(market_price) + parseFloat(g_marketprice)).toFixed(2);
		            $('#goodsTotal').html(_goodsTotal);
                    $('#onlineTotal').html(_marketprice);
		    		$('#availableTotal').html(_marketprice);
                    $('#wrapperUseBalance').show();
                }else {
	                if(confirm(result.error+'\n\r是否新添商品')){
	                	_goodsNum = $('#goodsTotal').html();
	                	_onlineTotal = $('#onlineTotal').html();
	                	by_val = _barcode+'|'+_goodsNum+'|'+_onlineTotal;
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
				return false;
            },
			error: function(){
		        ShowGetDataError();
	        }
        });
	});    
    //其他金额
    $("#other_price").change(function(){
		var other_price = $('#other_price').val();
		orderTotal = $('#availableTotal').html();
		onlineTotal = $('#onlineTotal').html();
		_onlineTotal = parseFloat(parseFloat(onlineTotal) + parseFloat(other_price)).toFixed(2);
		_orderTotal = parseFloat(parseFloat(orderTotal) + parseFloat(other_price)).toFixed(2);
        $('#onlineTotal').html(_onlineTotal);
		$('#availableTotal').html(_orderTotal);
		if(other_price!=''){
			$('#isGoodsed').hide();
			if(other_price>0){
				var goodsTotal = $('#goodsTotal').html();
				gcodeTotal = $('#gcodeTotal').html();
				_goodsTotal = parseFloat(_goodsTotal) + parseFloat(gcodeTotal);
				g_barcode = $('#g_barcode').val();
				var tempArr = g_barcode.split(',');
	         	bcode_len = tempArr.length;
	         	if(_goodsTotal<=bcode_len){
	         		$('#goodsTotal').html(_goodsTotal);
	         	}				
			}			
		}
    });
    //实收金额
    $("#actual_price").change(function(){
		var actual_price = $('#actual_price').val();
		var member_name = $('#member_name').val();
		if(member_name==''){
			alert('需要先扫一扫会员二维码或提供会员手机号');
			$('#actual_price').val('0.00');
			$("#scanQRCode1").click();
			return false;
		}
		if(actual_price!='' && actual_price>0){
			$('#availableTotal').html(actual_price);
		}
    });
    //根据登录名获取会员昵称
    $("#member_name").change(function(){
    	var store_id = getQueryString("store_id");
		var g_barcode = $('#g_barcode').val();
		var member_name = $('#member_name').val();
		if(member_name==''){
			//alert('会员名不可为空');
			$('#member_id').val('');
			$('#availablePredeposit').html(0);	
            $('#availableRcBalance').html(0);			        	
            $('#availablePoints').html(0);
			$('#availableTotal').html($('#onlineTotal').html());
			$('#pay_points').val(0);
			$('#rebate_price').val(0);
			$('#wrapperUsePDpay').hide();
			$('#wrapperUseRCBpay').hide();
			return false;
		}
		if(g_barcode==''){
			alert('没有扫码商品条码');
			$("#scanQRCode1").click();
			return false;
		}
    	$.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=store_order&op=getMemberInfo",
            data: {
            	member_name:member_name,store_id:store_id,g_barcode:g_barcode
            },
            dataType: "json",
            success: function(result) {
            	//console.log(result);
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
					return false;
		      	}
            },
			error: function(){
		        ShowGetDataError();
	        }
        });
	});
  	
});
