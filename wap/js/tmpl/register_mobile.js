$(function() {
    loadSeccode();
    $("#refreshcode").bind("click", function() {
        loadSeccode()
    });
    $.sValid.init({
        rules: {
            usermobile: {
                required: true,
                mobile: true
            }
        },
        messages: {
            usermobile: {
                required: "请填写手机号！",
                mobile: "手机号码不正确"
            }
        },
        callback: function(e, i, r) {
            if (e.length > 0) {
                var l = "";
                $.map(i, 
                function(e, i) {
                    l += "<p>" + e + "</p>"
                });
                errorTipsShow(l)
            } else {
                errorTipsHide()
            }
        }
    });
    $("#register_mobile_btn").click(function() {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        if ($.sValid()) {
			var phone = $("#usermobile").val();
			var captcha = $("#captcha").val();
			var codekey = $("#codekey").val();
			//检测验证码是否正确及手机号码是否可以注册
			$.getJSON(ApiUrl + "/index.php?act=connect_sms&op=check_sms_mobile", {
                type: 1,
                phone: phone,
                captcha: captcha,
                codekey: codekey
            },
            function(result) {
                if (result.error) {              
					loadSeccode();
                    errorTipsShow("<p>" + result.error + "<p>");
					return false
				}				
            })
			$(this).attr("href", "register_mobile_code.html?mobile=" + phone + "&captcha=" + captcha + "&codekey=" + codekey);	
            
        } else {
            return false
        }
    })
});