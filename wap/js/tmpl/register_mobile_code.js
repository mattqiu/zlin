$(function() {
    loadSeccode();
    $("#refreshcode").bind("click", function() {
        loadSeccode()
    });
    var mobile = getQueryString("mobile");
    var captcha = getQueryString("captcha");
    var codekey = getQueryString("codekey");
    $("#usermobile").html(mobile);
    send_sms(mobile, captcha, codekey);
	
    $("#again").click(function() {
        captcha = $("#captcha").val();
        codekey = $("#codekey").val();
        send_sms(mobile, captcha, codekey)
    });
    $("#register_mobile_password").click(function() {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        var mobilecode = $("#mobilecode").val();
        if (mobilecode.length == 0) {
            errorTipsShow("<p>请填写验证码<p>")
        }
        check_sms_captcha(mobile, mobilecode);
        return false
    })
});

function send_sms(mobile, captcha, codekey) {
    $.getJSON(ApiUrl + "/index.php?act=connect_sms&op=get_sms_captcha", {
        type: 1,
        phone: mobile,
        sec_val: captcha,
        sec_key: codekey
    },
    function(result) {
        if (result.error) {
			loadSeccode();
            errorTipsShow("<p>" + result.error + "<p>");
        } else {
			$.sDialog({
                skin: "green",
                content: "发送成功",
                okBtn: false,
                cancelBtn: false
            });
            $(".code-again").hide();
            $(".code-countdown").show().find("em").html(result.datas.sms_time);
            var c = setInterval(function() {
                var e = $(".code-countdown").find("em");
                var a = parseInt(e.html() - 1);
                if (a == 0) {
                    $(".code-again").show();
                    $(".code-countdown").hide();
                    clearInterval(c)
                } else {
                    e.html(a)
                }
            },
            1e3);            
        }
    })
}

function check_sms_captcha(mobile, mobilecode) {
    $.getJSON(ApiUrl + "/index.php?act=connect_sms&op=check_sms_captcha", {
        type: 1,
        phone: mobile,
        captcha: mobilecode
    },
    function(result) {
        if (!result.error) {
            window.location.href = "register_mobile_password.html?mobile=" + mobile + "&captcha=" + mobilecode
        } else {
            loadSeccode();
            errorTipsShow("<p>" + result.error + "<p>")
        }
    })
}