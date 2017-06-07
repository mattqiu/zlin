$(function(){
	if (!key) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    }
	var by_val = getQueryString("by_val");//传输字段
	$("#by_val").val(by_val);
	$.sValid.init({
		rules: {
			g_barcode: "required", 
			g_marketprice: "required", 
			g_discount: "required",
			g_price: "required",
			g_tradeprice: "required"
		},
        messages: {
        	g_barcode: "商品条形码必填！", 
        	g_marketprice: "吊牌价必填！", 
        	g_discount: "折扣必填！", 
        	g_price: "会员价必填！", 
        	g_tradeprice: "批发价必填！"
        },        
        callback: function(a, e, r) {
            if (a.length > 0) {
                var i = "";
                $.map(e, 
                function(a, e) {
                    i += "<p>" + a + "</p>";
                });
                errorTipsShow(i);
            } else {
                errorTipsHide();
            }
        }
    });
    $("#feedbackbtn,#header-nav").click(function() {
        if ($.sValid()) {
        	var key = getCookie('key');
        	var g_barcode 		= $("#g_barcode").val();
            var g_marketprice 	= $("#g_marketprice").val();
            var g_discount 		= $("#g_discount").val();
            var g_price 		= $("#g_price").val();
            var g_tradeprice 	= $("#g_tradeprice").val();
            var g_gbcode 		= $("#g_gbcode").val();
            var commis_amount 	= $("#commis_amount").val();
            var rebate_amount 	= $("#rebate_amount").val();
        	var store_id = getQueryString("store_id");
        	if(parseFloat(g_tradeprice)>parseFloat(g_price)){
        		alert('批发价不可大于会员价！');
        	}
        	if(commis_amount==''){
        		commis_amount = g_price - g_tradeprice;
        	}
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=store_order&op=add_barcode",
                data: {key: key,store_id:store_id,g_barcode:g_barcode, g_marketprice: g_marketprice,g_discount:g_discount,g_price:g_price,g_tradeprice:g_tradeprice,g_gbcode:g_gbcode,commis_amount:commis_amount,rebate_amount:rebate_amount},
                dataType: "json",
                success: function(result) {
                	if (!CheckDataSucceed(result)){return false;}
                	var data = result.datas;
                	if(data.bid){
                		alert(data.msg);
                		location.href = WapSiteUrl+'/tmpl/order/wx_cashier.php?store_id='+store_id+'&g_barcode='+g_barcode+'&g_marketprice='+g_marketprice+'&by_val='+by_val;
                		return true;
                	}else{
                		alert(data.msg);
                		location.href = WapSiteUrl+'/tmpl/seller/wx_barcode.php?store_id='+store_id+'&g_barcode='+g_barcode+'&by_val='+by_val;
                		return true;
                	}
                },
				error: function(){
			        ShowGetDataError();
		        }
            })
        }
    });
    $('#g_marketprice').change(function(){ // 失去吊牌价时触发的时间 
	    var g_marketprice 	= $("#g_marketprice").val();
	    var g_discount 		= $("#g_discount").val();
	    var g_price 		= $("#g_price").val();
		if (g_price == '') { 
			$('#g_price').val(g_marketprice);
			$("#g_discount").val('100');
			$('#g_tradeprice').val((g_marketprice*0.4).toFixed(2));
			$('#commis_amount').val((g_marketprice*0.6).toFixed(2));
		}else {
			var _discount = ((g_price/g_marketprice)*100).toFixed(0);
			$("#g_discount").val(_discount);
		}
		if (g_discount != '') { 
			var _price = (g_discount*g_marketprice*0.01).toFixed(2);
			$('#g_price').val(_price);
			_tradeprice = $("#g_tradeprice").val();
			if(parseFloat(_tradeprice)>parseFloat(_price)){
				$("#commis_amount").val(0);
				$("#g_tradeprice").val(_price);
			}else{
				$("#commis_amount").val(_price - _tradeprice);				
			}
		}
	});
	$('#g_discount').change(function(){ // 失去吊牌价时触发的时间 
		var g_marketprice 	= $("#g_marketprice").val();
	    var g_discount 		= $("#g_discount").val();
	    var g_price 		= $("#g_price").val();
	    _tradeprice = $("#g_tradeprice").val();
	    
		if (g_marketprice == '') { 
			if (g_price != '') { 
				var _marketprice = (g_price/(g_discount*0.01)).toFixed(2);
				$('#_marketprice').val(_marketprice);				
			}
		}else {
			var _price = (g_discount*g_marketprice*0.01).toFixed(2);
			$('#g_price').val(_price);
		}
		if(parseFloat(_tradeprice)>parseFloat(_price)){
			$("#commis_amount").val(0);
			$("#g_tradeprice").val(_price-g_marketprice*0.1);
		}else{
			$("#commis_amount").val(_price - _tradeprice);				
		}
	});
	$('#g_price').blur(function(){//得到吊牌价时触发的时间 
		var g_marketprice 	= $("#g_marketprice").val();
	    var g_discount 		= $("#g_discount").val();
	    var g_price 		= $("#g_price").val();
	    _tradeprice = $("#g_tradeprice").val();
		if (g_marketprice == '') { 
			if (g_discount != '') { 
				var _marketprice = (g_price/(g_discount*0.01)).toFixed(2);
				$('#_marketprice').val(_marketprice);
			}
		}else {
			var _discount = ((g_price/g_marketprice)*100).toFixed(0);
			$("#g_discount").val(_discount);
		}
		if(parseFloat(_tradeprice)>parseFloat(g_price)){
			$("#commis_amount").val(0);
			$("#g_tradeprice").val(g_price);
		}else{
			$("#commis_amount").val(g_price - _tradeprice);				
		}
	});
	
	$('#g_tradeprice').blur(function(){//得到吊牌价时触发的时间 
	    var g_price 		= $("#g_price").val();
	    var g_tradeprice 	= $("#g_tradeprice").val();
	    if(parseFloat(g_tradeprice)>parseFloat(g_price)){
	    	alert('批发价不可大于会员价');
	    	$("#commis_amount").val(0);
	    	return false;
	    }else{
	    	$("#commis_amount").val(g_price-g_tradeprice);	    	
	    }
	});
});
