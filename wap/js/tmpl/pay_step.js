var key = getCookie("key");
var store_id = getQueryString("store_id");
var invoice_id = 0;
var pd_pay, rcb_pay, qcb_pay, jf_pay;

$.ajax({
    type: "post",
    url: ApiUrl + "/index.php?act=member_vr_buy&op=pay_step",
    data: {
        key: key,
        store_id:store_id
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
        //本次交易需在线支付
        $("#onlineTotal").html('');
        
        pd_pay = 0;
        qcb_pay = 0;
        rcb_pay = 0;
        jf_pay = 0;
        total_price = 0.00; //应付金额
        pay_price = 0.00;	//消费金额
        //用户账号的余额 初始定义
        qcb_price = 0;
        rcb_price = 0;
        points_price = 0;
        pd_price = 0;
        //为了走逆向的流程 所以必须记录每种支付方式 所扣除的金额
        qcb_pledge = 0;//亲诚币
        czk_pledge = 0;//充值卡
        jf_pledge = 0; //抵扣云币的金额
        yue_pledge = 0;//余额
        
        var needpassword = false;
        $("#internalPay").show();
        //商家设置的可用云币数
        jf_ratio = result.datas.jf_ratio; //使用云币比例
        jf_limit = result.datas.jf_limit; //最高可用云币数
        points_trade = result.datas.points_trade; //云币比例
        //获取账号几种支付方式的余额
        qcb_price = parseFloat(result.datas.pay_info.member_available_qcb).toFixed(2);
        rcb_price = parseFloat(result.datas.pay_info.member_available_rcb).toFixed(2);
        points_price = parseFloat(result.datas.pay_info.member_available_points).toFixed(2);
        pd_price = parseFloat(result.datas.pay_info.member_available_pd).toFixed(2);
        //显示隐藏 亲诚币 DIV
		if (parseFloat(qcb_price) > 0 && result.datas.pay_info.pay_qcbamount>0) {
			$("#wrapperUseQCBpay").show();
            $("#availableQcbBalance").html(qcb_price);
        } else {
            $("#wrapperUseQCBpay").hide();
        }
		//显示隐藏 充值卡 DIV
        if (parseFloat(rcb_price) != 0) {
            $("#wrapperUseRCBpay").show();
            $("#availableRcBalance").html(rcb_price);
        } else {
            $("#wrapperUseRCBpay").hide();
        }
        //显示隐藏 云币 DIV
        if (parseFloat(points_price) != 0) {
            $("#wrapperUseJFpy").show();
            $("#availablePoints").html(points_price);
        } else {
            $("#wrapperUseJFpy").hide();
        }
        //显示隐藏 余额 DIV
        if (parseFloat(pd_price) != 0) {
            $("#wrapperUsePDpy").show();
            $("#availablePredeposit").html(pd_price);
        } else {
            $("#wrapperUsePDpy").hide()
            $("#wrapperPaymentPassword").hide();
        }
        //获取消费金额 
	$("#pay_price").keyup(function () { //限制只能输入金额
                var reg = $(this).val().match(/\d+\.?\d{0,2}/);
                var txt = '';
                if (reg != null) {
                    txt = reg[0];
                }
                $(this).val(txt);
            }).change(function () {
                $(this).keyup();
            });
        $('#pay_price').bind({
		
        	blur:function(){
        		pay_price = parseFloat($(this).val()).toFixed(2); //
        		total_price = pay_price; // 初始化时 实际支付的金额等于消费金额
        		jf_price = pay_price*parseFloat(jf_ratio*0.01).toFixed(2)*points_trade;
				if(parseFloat(points_price) < parseFloat(jf_price)){
					jf_price = points_price;
                }
				$("#pay_points").val(jf_price);
				total_price = total_price - jf_price;
        		//只要有一个 支付方式被选中都要扣除
        		if(pd_pay == 1 || rcb_pay == 1 || jf_pay == 1 || qcb_pay == 1){
        			if(qcb_pay==1){
        				if(parseFloat(total_price) >= parseFloat(qcb_price)){
                        	total_price = total_price - qcb_price;
                        }else{
                        	total_price = 0.00;
                        }
        			}
        			//充值卡和云币互斥 只能如此
        			if(rcb_pay==1){
        				if(parseFloat(total_price) >= parseFloat(rcb_price)){
        					total_price = total_price - rcb_price;
                        }else{
                        	total_price = 0.00;
                        }
        			}
        			if(jf_pay==1){
        				points_price = total_price*qcb_price*0.01;
        				if(parseFloat(total_price) >= parseFloat(points_price)){
                        	total_price = total_price - points_price;
                        }else{
                        	total_price = 0.00;
                        }
        			}
        			if(pd_pay==1){
        				if(parseFloat(total_price) >= parseFloat(pd_price)){
                        	total_price = total_price - pd_price;
                        }else{
                        	total_price = 0.00;
                        }
        			}
        		}
        		$("#availableTotal").html(total_price);
        	}
        });
        if (!result.datas.pay_info.member_paypwd) {
            $("#wrapperPaymentPassword").find(".input-box-help").show()
        }
        //先隐藏支付方式列表，及由可能用户的账户余额足矣支付
        $("#vr_payment_list").show();
        //触发亲诚币
        $("#useQCBpay").click(function() {
            if ($(this).prop("checked")) { //选择使用亲诚币
            	qcb_pay = 1
                needpassword = true;
                $("#wrapperPaymentPassword").show();
                //订单金额大于亲诚币 则 所有的亲诚币 都要被使用，否则亲诚币 为订单的金额
                if(parseFloat(total_price) >= parseFloat(qcb_price) && parseFloat(total_price)!=0){ 
                	qcb_pledge = qcb_price;
                	total_price = total_price - qcb_price;
                }else{
                	qcb_pledge = total_price;
                	total_price = 0.00;
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
            	total_price = parseFloat(total_price) + parseFloat(qcb_pledge);
            	//加回去后 需要初始化
            	qcb_pledge = 0;
            }
            $("#availableTotal").html(parseFloat(total_price).toFixed(2));
            //如果订单金额为0，则隐藏支付方式列表
            if(parseFloat(total_price)==0){
            	$("#vr_payment_list").hide();
            }else{
            	$("#vr_payment_list").show();
            }
        });
        //触发充值卡
        $("#useRCBpay").click(function() {
        	//选择使用充值卡
            if ($(this).prop("checked")) {
                needpassword = true;
                $("#wrapperPaymentPassword").show();
                rcb_pay = 1
                //充值卡和云币不可叠加使用
                $("#useJFpy").parents("label").attr("class",'');
                jf_pay == 0
                //因为互斥的缘故 所以还需加回云币的抵扣部分金额
                total_price = parseFloat(total_price) + parseFloat(jf_pledge);
                //需要初始化
                jf_pledge = 0;
                if(parseFloat(total_price) >= parseFloat(rcb_price) && parseFloat(total_price)!=0){
                	czk_pledge = rcb_price;
                	total_price = total_price - rcb_price;
                }else{
                	czk_pledge = total_price;
                	total_price = 0.00;
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
                total_price = parseFloat(total_price) + parseFloat(czk_pledge);
                //加回去后 需要初始化
                czk_pledge = 0;
            }
            //更新订单总金额
            $("#availableTotal").html(parseFloat(total_price).toFixed(2));
            //如果订单金额为0，则隐藏支付方式列表
            if(parseFloat(total_price)==0){
            	$("#vr_payment_list").hide();
            }else{
            	$("#vr_payment_list").show();
            }
        });
        //触发云币
        $("#useJFpy").click(function() {
        	//选择使用云币
            if ($(this).prop("checked")) {
                needpassword = true;
                $("#wrapperPaymentPassword").show();
                jf_pay = 1;
                //充值卡和云币不可叠加使用
                $("#useRCBpay").parents("label").attr("class",'');
                rcb_pay = 0;
                //因为互斥的缘故 所以还需加回充值卡的抵扣部分金额
                total_price = parseFloat(total_price) + parseFloat(czk_pledge);
                //需要初始化
                czk_pledge = 0;
            	if(parseFloat(total_price) >= parseFloat(js_price) && parseFloat(total_price)!=0){
            		jf_pledge = js_price;
                	total_price = total_price - js_price;
                }else{
                	jf_pledge = total_price;
                	total_price = 0.00;
                }
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
                total_price = parseFloat(total_price) + parseFloat(jf_pledge);
                //加回去后 需要初始化
                jf_pledge = 0;
            }
            $("#availableTotal").html(parseFloat(total_price).toFixed(2));
            //如果订单金额为0，则隐藏支付方式列表
            if(parseFloat(total_price)==0){
            	$("#vr_payment_list").hide();
            }else{
            	$("#vr_payment_list").show();
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
                if(parseFloat(total_price) >= parseFloat(pd_price) && parseFloat(total_price)!=0){
                	yue_pledge = pd_price;
                	total_price = total_price - pd_price;
                }else{
                	yue_pledge = total_price;
                	total_price = 0.00;
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
            	total_price = parseFloat(total_price) + parseFloat(yue_pledge);
            	//加回去后 需要初始化
            	yue_pledge = 0;
            }
            $("#availableTotal").html(parseFloat(total_price).toFixed(2));
            //如果订单金额为0，则隐藏支付方式列表
            if(parseFloat(total_price)==0){
            	$("#vr_payment_list").hide();
            }else{
            	$("#vr_payment_list").show();
            }
        });
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
                    url: ApiUrl + "/index.php?act=member_vr_buy&op=check_password",
                    dataType: "json",
                    data: {
                    	key: key,
                        store_id:store_id,
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
        
        payment_code = "";
        if (!$.isEmptyObject(result.datas.pay_info.payment_list)) {
            var canweixin = false;
            var canalipay = false;
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
                }else{
                	$("#" + pay_code).parents("label").hide();
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
        if(parseFloat(total_price)==0){
    		payment_code="predeposit";
    	}
        $("#alipay").click(function() {
            payment_code = "alipay";
        });
        $("#wxpay").click(function() {
            payment_code = "wxpay";
        });
        $("#llpay").click(function() {
            payment_code = "llpay";
        });
        $("#toPay").click(function() {
            if (payment_code == "") {
                 payment_code="wxpay";
            }else{
            	if(parseFloat(total_price)>0&&payment_code=="predeposit"){
            		payment_code="wxpay"
		    	}
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
            }
            $.ajax({
                    type: "post",
                    url: ApiUrl + "/index.php?act=member_vr_buy&op=vrpay_step",
                    dataType: "json",
                    data: {
                        key: key,
                        store_id : store_id,
                        password: password,
                        needpassword: needpassword,
                        total_price:total_price,
                        jf_price:jf_price,
                        pay_price : $("#pay_price").val(),
                        pd_pay : pd_pay,
                        rcb_pay: rcb_pay,
                        qcb_pay : qcb_pay,
                        jf_pay: jf_pay,
                        payment_code:payment_code,
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
                        goToPayment(result.datas.pay_sn, "vr_pay_new")
                    }
            })
            
        })
    }
})

function goToPayment(pay_sn, opeartion) {
    location.href = ApiUrl + "/index.php?act=member_payment&op=" + opeartion + "&key=" + key + "&pay_sn=" + pay_sn + "&rcb_pay=" + rcb_pay + "&pd_pay=" + pd_pay + "&qcb_pay=" + qcb_pay + "&jf_pay=" + jf_pay + "&payment_code=" + payment_code
}