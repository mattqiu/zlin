$(function(){
	var key = getCookie('key');
	if(!key){
		location.href = 'login.html';
	}
	$.ajax({
		type:'get',
		url:ApiUrl+"/index.php?act=member_extension&op=my_extensionqrcode",	
		data:{key:key},
		dataType:'json',
		//jsonp:'callback',
		success:function(result){
			if (!CheckDataSucceed(result,'/tmpl/member/member.html')){return false;}

			var data = result.datas;			
			
			data.WapSiteUrl = WapSiteUrl;
			var html = template.render('extension-info-tmpl', data);
            $("#extension-info").html(html);
					
			var html = template.render('extension-profile-tmpl', data);
            $("#extension-profile").html(html);			

			return false;
		},
		error: function(){
			ShowGetDataError();
		}	  
	});
	
	function getcashier(){
		//alert("hello");
		$.ajax({
			type:'get',
			url:ApiUrl+"/index.php?act=member_order&op=getCashierOrder",	
			data:{key:key},
			dataType:'json',
			success:function(result){
				var data = result.datas;
				//console.log(data);
				if (result.error) {
	        //$.sDialog({
	        //           skin: "red",
	        //           content: result.error,
	         //          okBtn: false,
	          //         cancelBtn: false
	         //      });
	                return false
	            }
	            $.animationUp({
	                valve: "",
					wrapper: "#list-payment-wrapper",
	                scroll: ""
	            });
	            order_id = data.pay_info.order_id;
	            pay_amount_online = data.pay_info.pay_amount_online;
	            rcb_amount = data.pay_info.rcb_amount;
	            pd_amount = data.pay_info.pd_amount;
	            $("#onlineNum").html(data.pay_info.pay_num_online);
	            
				if (data.pay_info.payed_tips != ""){
					$("#isPayed").html(data.pay_info.payed_tips);
					$("#isPayed").show();
				}else{
					$("#isPayed").hide();
				}
				//显示用户的余额
				usable_points_amount = data.pay_info.usable_points_amount; //剩余云币
				member_available_rcb = data.pay_info.member_available_rcb; //剩余充值卡
				member_available_pd = data.pay_info.member_available_pd; //剩余积分
				$("#availableRcBalance").html(member_available_rcb);
				$("#availablePredeposit").html(member_available_pd);
				$("#availablePoints").html(usable_points_amount);
				if(member_available_rcb>0){
					$("#wrapperUseRCBpay").show();
				}else{
					$("#wrapperUseRCBpay").hide();
				}
				if(member_available_pd>0){
					$("#wrapperUsePDpay").show();
				}else{
					$("#wrapperUsePDpay").hide();
				}
				if(usable_points_amount>0){
					$("#wrapperUseJFpay").show();
				}else{
					$("#wrapperUseJFpay").hide();
				}
				if(member_available_rcb>0||member_available_pd>0||usable_points_amount>0){
					$("#internalPay").show();
				}else{
					$("#internalPay").hide();
				}
				//支付方式
				pay_code = data.pay_info.pay_code;
				//支付单号
				pay_sn = data.pay_info.pay_sn;
				if((pay_code=='predeposit'&& pay_amount_online == 0)||pay_code=='xjpay'||pay_code=='skpay'){
					$("#toConfirmPay").html("确定收货");
					if(pay_code=='predeposit' ){
						if(parseFloat(rcb_amount)>0 || parseFloat(pd_amount)>0){
							$("#onlineCode").html("积分支付");
							if (!data.pay_info.member_paypwd) {
								$('#paymentPassword').attr('disabled','disabled').css('background','#E7E7E7 none');
				                $("#wrapperPaymentPassword").find(".input-box-help").show()
				            }
							$("#internalPay").show();
							$("#wrapperPaymentPassword").show();
							$("#onlineTotal").html(parseFloat(rcb_amount)+parseFloat(pd_amount));
							$("#isPayed").hide();
						}else{
							$("#onlineCode").html("云币支付");
							$("#onlineTotal").html(parseFloat(data.pay_info.usable_points));
						}
						$("#payment_list").hide();
					}
				}else{
	            	$("#onlineTotal").html(pay_amount_online);
				}	
				pay_text = $("#pay_text").val();
				if(pay_text !=''){
					pay_code = pay_text;
				}
				if(pay_code.indexOf(',')>=0){
					len = pay_code.indexOf(',');
					paycodeArr = pay_code.split(",");
					for (var i=0;i<len;i++)
					{
						$("#label_"+paycodeArr[i]).addClass("checked");
					}
				}else{
					$("#label_"+pay_code).addClass("checked");
				}				
				
	            var needpassword = false;
	            password = "";
	            $('#paymentPassword').change(function(){ //丢失时 触发获取
	            		password = $(this).val();
	            		//应该再加一个去验证密码的，注意防刷
	            		$.ajax({
	                        type: "post",
	                        url: ApiUrl + "/index.php?act=member_buy&op=check_password",
	                        dataType: "json",
	                        data: {
	                        	key: key,
	                            password: password
	                        },
	                        success: function(result) {
	                            if (result.error) {
	                                $.sDialog({
	                                    skin: "red",
	                                    content: result.error,
	                                    okBtn: false,
	                                    cancelBtn: false
	                                });
	                                return false
	                            }
	                        }
	            		})
	            	
	            });
	            $("#xjpay").click(function() {
					pay_code = "xjpay";
					$("#pay_text").val(pay_code);
					$("#toConfirmPay").html("确定收货");
	            });
	            $("#skpay").click(function() {
	            	pay_code = "skpay";
	            	$("#pay_text").val(pay_code);
	            	$("#toConfirmPay").html("确定收货");
	            });
				$("#alipay").click(function() {
					pay_code = "alipay";
					$("#pay_text").val(pay_code);
					$("#toConfirmPay").html("确定支付");
	            });
	            $("#wxpay").click(function() {
	            	pay_code = "wxpay";
	            	$("#pay_text").val(pay_code);
	            	$("#toConfirmPay").html("确定支付");
	            });
				$("#llpay").click(function() {
					pay_code = "llpay";
					$("#pay_text").val(pay_code);
					$("#toConfirmPay").html("确定支付");
	            });
	            $("#toConfirmPay").click(function() {
	                if (pay_code == "") {
	                    $.sDialog({
	                        skin: "red",
	                        content: "请选择支付方式",
	                        okBtn: false,
	                        cancelBtn: false
	                    });
	                    return false
	                }
	                if (needpassword) {
	                    if (password == "") {
	                        $.sDialog({
	                            skin: "red",
	                            content: "请填写支付密码",
	                            okBtn: false,
	                            cancelBtn: false
	                        });
	                        return false
	                    }
	                    $.ajax({
	                        type: "post",
	                        url: ApiUrl + "/index.php?act=member_buy&op=check_pd_pwd",
	                        dataType: "json",
	                        data: {
	                            key: key,
	                            password: password
	                        },
	                        success: function(result) {
	                            if (result.error) {
	                                $.sDialog({
	                                    skin: "red",
	                                    content: result.error,
	                                    okBtn: false,
	                                    cancelBtn: false
	                                });
	                                return false
	                            }
	                            goToPayment(pay_sn, "pay_new")
	                        }
	                    })
	                } else if((pay_code=='predeposit'&& pay_amount_online == 0)||pay_code=='xjpay'||pay_code=='skpay'){
	                	//现金和刷卡支付，去确定收货
	                	 $.ajax({
		                        type: "post",
		                        url: ApiUrl + "/index.php?act=member_order&op=order_receive",
		                        dataType: "json",
		                        data: {
		                            key: key,
		                            order_id: order_id
		                        },
		                        success: function(result) {
		                            if (result.error) {
		                                $.sDialog({
		                                    skin: "red",
		                                    content: result.error,
		                                    okBtn: false,
		                                    cancelBtn: false
		                                });
		                                return false
		                            }
		                            $("#list-payment-wrapper").addClass("down").removeClass("up");
		                            $.ajax({
				                        type: "post",
				                        url: ApiUrl + "/index.php?act=member_order&op=unCashierOrder",
				                        dataType: "json",
				                        data: {
				                            key: key
				                        },
				                        success: function(result) {
				                            if (result.error) {
				                                $.sDialog({
				                                    skin: "red",
				                                    content: result.error,
				                                    okBtn: false,
				                                    cancelBtn: false
				                                });
				                                return false
				                            }
				                        }
				                    })
		                        }
		                    })
	                }else{
	                    goToPayment(pay_sn, "pay_new");
	                }
	            })
			},
			error: function(){
				ShowGetDataError();
			}	  
		});
	}
	setInterval(getcashier,10000);//10秒触发一次
    //mqq.invoke("ui", "openUrl",{url: "http://news.qq.com", target: 1, style: 1});
	function goToPayment(pay_sn, opeartion) {
	    location.href = ApiUrl + "/index.php?act=member_payment&op=" + opeartion + "&key=" + key + "&pay_sn=" + pay_sn + "&password=" + password  + "&payment_code=" + pay_code
	}
});
