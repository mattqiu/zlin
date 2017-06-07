var key = getCookie("key");
var password, rcb_pay, pd_pay, qcb_pay, jf_pay, payment_code;
//a :pay_sn e:member_buy p:pay
function toPay(pay_sn, action, opeartion) {
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?act=" + action + "&op=" + opeartion,
        data: {
            key: key,
            pay_sn: pay_sn
        },
        dataType: "json",
        success: function(result) {
            checkLogin(result.login);
            if (result.error) {
                $.sDialog({
                    skin: "red",
                    content: result.error,
                    okBtn: false,
                    cancelBtn: false
                });
                return false
            }
            $.animationUp({
                valve: "",
				wrapper: "#list-payment-wrapper",
                scroll: ""
            });
            $("#onlineTotal").html(result.datas.pay_info.pay_amount_online);
			if (result.datas.pay_info.pay_qcbamount>0){
			    $("#qcbPayTotal").html(result.datas.pay_info.pay_qcbamount);
				$("#qcbPayTotal").parent().show();
			}else{
				$("#qcbPayTotal").html(result.datas.pay_info.pay_qcbamount);
				$("#qcbPayTotal").parent().hide();
			}
			if (result.datas.pay_info.payed_tips != ""){
				$("#isPayed").html(result.datas.pay_info.payed_tips);
				$("#isPayed").show();
			}else{
				$("#isPayed").html(result.datas.pay_info.payed_tips);
				$("#isPayed").hide();
			}
            if (!result.datas.pay_info.member_paypwd) {
                $("#wrapperPaymentPassword").find(".input-box-help").show()
            }
            var needpassword = false;
            //初始化
            pd_pay = 0;
            qcb_pay = 0;
            rcb_pay = 0;
            jf_pay = 0;
            //
            payable_amount = 0.00; //应付金额
            pay_amount_online = parseFloat(result.datas.pay_info.pay_amount_online).toFixed(2);	//在线支付金额，注意快递费的问题
            
            //用户账号的余额 初始定义
            qcb_price = 0;
            rcb_price = 0;
            usable_points = 0; //订单可用云币数
            usable_points_amount = 0; //订单可用云币抵扣金额
            pd_price = 0;
            //为了走逆向的流程 所以必须记录每种支付方式 所扣除的金额
            qcb_pledge = 0;//亲诚币
            czk_pledge = 0;//充值卡
            yue_pledge = 0;//余额
            
            usable_points_total = result.datas.pay_info.usable_points_total; //订单可用云币数
            usable_points_amount = parseFloat(result.datas.pay_info.usable_points_amount).toFixed(2);//可用云币额
            if (parseFloat(result.datas.pay_info.payed_amount) <= 0) {//已支付的
            	
            	//获取账号几种支付方式的余额member_available_points
                qcb_price = parseFloat(result.datas.pay_info.member_available_qcb).toFixed(2); //亲城比
                rcb_price = parseFloat(result.datas.pay_info.member_available_rcb).toFixed(2);//充值卡
                usable_points = parseFloat(result.datas.pay_info.usable_points_total).toFixed(2);//云币
                pd_price = parseFloat(result.datas.pay_info.member_available_pd).toFixed(2);//积分余额
                member_points = result.datas.pay_info.member_points;
                if(usable_points>0){                	
                	jf_pledge =  parseFloat(usable_points);
                	needpassword = true;
                    $("#wrapperPaymentPassword").show();
                	$('#useJFpy').trigger("click");//模拟触发使用积分
                }else{
                	jf_pledge = 0; //抵扣云币的金额
                }
                if (qcb_price == 0.00 && rcb_price == 0.00  && usable_points == 0.00 && pd_price == 0.00) {
                    $("#internalPay").hide()
                } else {
                	$("#internalPay").show();
                	//显示隐藏 亲城比 DIV
			if (qcb_price > 0) {
                        $("#wrapperUseQCBpay").show();
                        $("#availableQcbBalance").html(qcb_price)
                    } else {
                        $("#wrapperUseQCBpay").hide()
                    }
					//显示隐藏 充值卡 DIV
                    if (rcb_price > 0) {
                        $("#wrapperUseRCBpay").show();
                        $("#availableRcBalance").html(rcb_price)
                    } else {
                        $("#wrapperUseRCBpay").hide()
                    }
                    //显示隐藏 云币 DIV
                    if (usable_points > 0) {
                        $("#wrapperUseJFpy").show();
                        $("#availablePoints").html(member_points);
                        $("#isPayedPoints").html(result.datas.pay_info.payed_points_tips);
        				$("#isPayedPoints").show();
        			} else {
                        $("#wrapperUseJFpy").hide();
                        $("#isPayedPoints").hide();
                    }
                  //显示隐藏 积分余额 DIV
                    if (pd_price > 0) {
                        $("#wrapperUsePDpy").show();
                        $("#availablePredeposit").html(pd_price)
                    } else {
                        $("#wrapperUsePDpy").hide()
                    }
                }
            } else {
                $("#internalPay").hide()
            }
            password = "";
            /** 这种写法有问题 系统获取不到值
            $("#paymentPassword").on("change", function() {
                password = $(this).val()
            });
            */
            $('#paymentPassword').bind({
            	blur:function(){ //丢失时 触发获取
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
            	}
            });
            //触发亲诚币
            $("#useQCBpay").click(function() {
                if ($(this).prop("checked")) { //选择使用亲诚币
                	qcb_pay = 1
                    needpassword = true;
                    $("#wrapperPaymentPassword").show();
                    //订单金额大于亲诚币 则 所有的亲诚币 都要被使用，否则亲诚币 为订单的金额
                    if(pay_amount_online >= qcb_price && pay_amount_online > 0){ 
                    	qcb_pledge = parseFloat(qcb_price).toFixed(2);
                    	pay_amount_online = pay_amount_online - qcb_price;
                    }else{
                    	qcb_pledge = parseFloat(pay_amount_online).toFixed(2);
                    	pay_amount_online = 0.00;
                    }
                } else { //取消使用亲诚币
                	qcb_pay = 0
                    if (pd_pay == 1 || rcb_pay == 1 || jf_pay == 1) {
                        needpassword = true;
                        $("#wrapperPaymentPassword").show()
                    } else {
                        needpassword = false;
                        $("#wrapperPaymentPassword").hide()
                    }
                	//不管又没选择其他方式,总金额里 都应该扣回 之前使用过的亲诚币
                	pay_amount_online = parseFloat(pay_amount_online) + parseFloat(qcb_pledge);
                	//加回去后 需要初始化
                	qcb_pledge = 0;
                }
                $("#onlineTotal").html(parseFloat(pay_amount_online).toFixed(2));
                //如果订单金额为0，则隐藏支付方式列表
                if(parseFloat(pay_amount_online)==0){
                	$("#payment_list").hide();
                }else{
                	$("#payment_list").show();
                }
            });
            //触发充值卡
            $("#useRCBpay").click(function() {
            	//选择使用充值卡
                if ($(this).prop("checked")) {
                    needpassword = true;
                    $("#wrapperPaymentPassword").show();
                    rcb_pay = 1
                    if(jf_pay==1){
                    	$("#isPayed").html("充值卡和云币，不可同时使用！");
        				$("#isPayed").show();
                    }
                    //充值卡和云币不可叠加使用
                    $("#useJFpy").parents("label").attr("class",'');
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
                } else {
                	rcb_pay = 0;
                	jf_pay=0; //因为和云币时互斥 取消充值卡的时候证明在此之前 云币方式一定是无法使用的
                    if (pd_pay == 1 || jf_pay == 1 || qcb_pay == 1) {
                        needpassword = true;
                        $("#wrapperPaymentPassword").show();
                    } else {
                        needpassword = false;
                        $("#wrapperPaymentPassword").hide();
                    }
                    //不管又没选择其他方式,总金额里 都应该扣回 之前使用过的充值卡
                    //虽然充值卡和云币互斥，此处没有必要在加回或多扣除云币
                    pay_amount_online = pay_amount_online + czk_pledge;
                    //加回去后 需要初始化
                    czk_pledge = 0;
                }
                //更新订单总金额
                $("#onlineTotal").html(parseFloat(pay_amount_online).toFixed(2));
                //如果订单金额为0，则隐藏支付方式列表
                if(pay_amount_online==0.00){
                	$("#payment_list").hide();
                }else{
                	$("#payment_list").show();
                }
            });
            //触发云币
            $("#useJFpy").click(function() {
	    
            	//选择使用云币
                if ($(this).prop("checked")) {
                    needpassword = true;
                    $("#wrapperPaymentPassword").show();
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
                	if(pay_amount_online >= usable_points_amount && pay_amount_online > 0){
                		jf_pledge =  parseFloat(usable_points);
                    	pay_amount_online =  parseFloat(pay_amount_online) -  parseFloat(usable_points_amount);
                    }else{
                    	jf_pledge =  parseFloat(pay_amount_online);
                    	pay_amount_online = 0.00;
                    }
		    //会员可用云币余额
                    $("#availablePoints").html(parseFloat(member_points));
                } else {
                	jf_pay  =0; 
                	rcb_pay = 0;//因为和充值卡时互斥 取消 云币的时候证明在此之前 充值卡方式一定是无法使用的
                    if (rcb_pay == 1 || qcb_pay == 1 || pd_pay == 1) {
                		needpassword = true;
                		$("#wrapperPaymentPassword").show();
                    } else {
                		needpassword = false;
                		$("#wrapperPaymentPassword").hide();
                    }
                    //不管又没选择其他方式,总金额里 都应该扣回 之前使用过的云币
                    //虽然充值卡和云币互斥，此处没有必要在加回或多扣除充值卡
                    pay_amount_online = parseFloat(pay_amount_online) + parseFloat(usable_points_amount);
                    //会员可用云币余额
                    $("#availablePoints").html(parseFloat(member_points) + parseFloat(jf_pledge));
                    //加回去后 需要初始化
                    jf_pledge = 0;
                }
                $("#onlineTotal").html(parseFloat(pay_amount_online).toFixed(2));
                //如果订单金额为0，则隐藏支付方式列表
                if(parseFloat(pay_amount_online)==0){
                	$("#payment_list").hide();
                }else{
                	$("#payment_list").show();
                }
            });
            //触发积分余额
            $("#usePDpy").click(function() {
            	//选择使用积分余额
                if ($(this).prop("checked")) {
                    needpassword = true;
                    $("#wrapperPaymentPassword").show();
                    pd_pay = 1
                    //选择使用余额支付
                    if(parseFloat(pay_amount_online) >= parseFloat(pd_price) && parseFloat(pay_amount_online)!=0){
                    	yue_pledge = pd_price;
                    	pay_amount_online = pay_amount_online - pd_price;
                    }else{
                    	yue_pledge = pay_amount_online;
                    	pay_amount_online = 0.00;
                    }
                } else {
                	pd_pay = 0
                	if (rcb_pay == 1 || qcb_pay == 1 || jf_pay == 1) {
                		needpassword = true;
                		$("#wrapperPaymentPassword").show();
                    } else {
                		needpassword = false;
                		$("#wrapperPaymentPassword").hide();
                    }
                    //不管又没选择其他方式,总金额里 都应该扣回 之前使用过的余额
                	pay_amount_online = parseFloat(pay_amount_online) + parseFloat(yue_pledge);
                	//加回去后 需要初始化
                	yue_pledge = 0;
                }
                $("#onlineTotal").html(parseFloat(pay_amount_online).toFixed(2));
                //如果订单金额为0，则隐藏支付方式列表
                if(parseFloat(pay_amount_online)==0){
                	$("#payment_list").hide();
                }else{
                	$("#payment_list").show();
                }
            });
            
            payment_code = "";
            if (!$.isEmptyObject(result.datas.pay_info.payment_list)) {
                var canweixin = true;
                var canalipay = true;//false;
		var canllpay = true;
                var n = navigator.userAgent.match(/MicroMessenger\/(\d+)\./);
                if (parseInt(n && n[1] || 0) >= 5) {
                    canweixin = true
                } else {
                    canalipay = true
                }
                for (var o = 0; o < result.datas.pay_info.payment_list.length; o++) {
                    var pay_code = result.datas.pay_info.payment_list[o].payment_code;
                    if (pay_code == "alipay" && canalipay) {
                        $("#" + pay_code).parents("label").show();
                        if (payment_code == "") {
                            payment_code = pay_code;
                            $("#" + payment_code).attr("checked", true).parents("label").addClass("checked")
                        }
                    }
                    if (pay_code == "wxpay" && canweixin) {
                        $("#" + pay_code).parents("label").show();
                        if (payment_code == "") {
                            payment_code = pay_code;
                            $("#" + payment_code).attr("checked", true).parents("label").addClass("checked")
                        }
                    }
		    if (pay_code == "llpay" && canllpay) {
                        $("#" + pay_code).parents("label").show();
                        if (payment_code == "") {
                            payment_code = pay_code;
                            $("#" + payment_code).attr("checked", true).parents("label").addClass("checked")
                        }
                    }
                }
            }
            //在线支付的金额为0时 支付方式可直接改为 余额支付
            if(parseFloat(pay_amount_online)==0){
        		payment_code="predeposit";
        	}
            $("#alipay").click(function() {
                payment_code = "alipay"
            });
            $("#wxpay").click(function() {
                payment_code = "wxpay"
            });
			$("#llpay").click(function() {
                payment_code = "llpay"
            });
            $("#toPay").click(function() {
                if (payment_code == "") {
                    $.sDialog({
                        skin: "red",
                        content: "请选择支付方式",
                        okBtn: false,
                        cancelBtn: false
                    });
                    return false
                }
               	if(parseFloat(pay_amount_online)>0 && payment_code=="predeposit"){
               		payment_code="wxpay"
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
                            goToPayment(pay_sn, action == "member_buy" ? "pay_new": "vr_pay_new")
                        }
                    })
                } else {
                    goToPayment(pay_sn, action == "member_buy" ? "pay_new": "vr_pay_new")
                }
            })
        }
    })
}

function goToPayment(pay_sn, opeartion) {
    location.href = ApiUrl + "/index.php?act=member_payment&op=" + opeartion + "&key=" + key + "&pay_sn=" + pay_sn + "&password=" + password + "&rcb_pay=" + rcb_pay + "&pd_pay=" + pd_pay + "&qcb_pay=" + qcb_pay + "&jf_pay=" + jf_pay + "&payment_code=" + payment_code
}