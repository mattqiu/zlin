$(function() {
    var key = getCookie("key");
    if (!key) {
        window.location.href = WapSiteUrl + "/tmpl/seller/login.html";
        return
    }
    $.ajax({
        type: "post",
        url: ApiUrl + "/index.php?act=seller_account&op=get_seller_info",
        data: {
            key: key
        },
        dataType: "json",
        success: function(result) {
        	//console.log(result);
            if (result.datas.state) {
                $("#points").val(result.datas.points);
                $("#rc_balance").val(result.datas.rc_balance);
                $("#predeposit").val(result.datas.predeposit);
            }
        }
    });
    
    $.sValid.init({
        rules: {
        	points: "required",
        	rc_balance: "required",
        	predeposit: "required"
        },
        messages: {
        	points: "最多可使用的云币必填！",
        	rc_balance: "最多可用充值卡必填！",
        	predeposit: "最多可使用的积分必填！"
        },
        callback: function(e, a, t) {
            if (e.length > 0) {
                var o = "";
                $.map(a, 
                function(e, a) {
                    o += "<p>" + e + "</p>"
                });
                errorTipsShow(o)
            } else {
                errorTipsHide()
            }
        }
    });
    
    $(".btn").click(function() {
        if ($.sValid()) {
            var points = $("#points").val();
            var rc_balance = $("#rc_balance").val();
            var predeposit = $("#predeposit").val();
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=seller_account&op=store_discount_edit",
                data: {
                	key: key,
                    points: points,
                    rc_balance: rc_balance,
                    predeposit: predeposit
                },
                dataType: "json",
                success: function(result) {
                    if (result) {
                    	if(result.status.succeed){
                    		alert('编辑成功');
                    	}else{
                    		alert(result.status.error_desc);
                    	}
                        location.href = WapSiteUrl + "/tmpl/seller/seller_account.html"
                    } else {
                        location.href = WapSiteUrl
                    }
                }
            })
        }
    });
    
});