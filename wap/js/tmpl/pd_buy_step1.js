var key = getCookie("key");
var card_id = getQueryString("card_id");

$(function() {
    var e = {};
    e.key = key;
    if(card_id){
    	e.card_id = card_id;
    }
    var lock = true;
    if(!lock){
    	alert("您已经提交订单，请直接付款");
    	return false;
    }
    lock = false;
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?act=member_pd_buy&op=buy_step1",
        data: e,
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
            $("#totalPrice").html(result.datas.pay_info.pdr_amount);
            $("#onlineTotal").html(result.datas.pay_info.pdr_amount);
            
            if (result.datas.pay_info.pdr_sn) {
            	$("#deposit").html(result.datas.pay_info.pdr_sn);
            }
            payment_code = "";
            if (!$.isEmptyObject(result.datas.pay_info.payment_list)) {
                var canweixin = true;
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
                goToPayment(result.datas.pay_info.pdr_sn, "pd_pay_new");
            })
            return false
        }
    })
});

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
            
            payment_code = "";
            if (!$.isEmptyObject(result.datas.pay_info.payment_list)) {
                var canweixin = true;
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
                goToPayment(pay_sn, "pd_pay_new");
            })
        }
    })
}

function goToPayment(pdr_sn, opeartion) {
    location.href = ApiUrl + "/index.php?act=member_payment&op=" + opeartion + "&key=" + key + "&pdr_sn=" + pdr_sn + "&payment_code=" + payment_code
}