$(function() {
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
    $("#find_password_code").click(function() {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        var mobilecode = $("#mobilecode").val();
        if (mobilecode.length == 0) {
            errorTipsShow("<p>请填写验证码<p>")
        }
        check_sms_captcha(mobile, mobilecode);
        return false
    });
    loadSeccode();
    $("#refreshcode").bind("click", 
    function() {
        loadSeccode()
    })
});

function send_sms(phone, sec_val, sec_key) {
    $.getJSON(ApiUrl + "/index.php?act=connect_sms&op=get_sms_captcha", {
        type: 3,
        phone: phone,
        sec_val: sec_val,
        sec_key: sec_key
    },
    function(result) {
        if (!result.error) {
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
            1e3)
        } else {
            loadSeccode();
            errorTipsShow("<p>" + result.error + "<p>")
        }
    })
}
function check_sms_captcha(phone, captcha) {
    $.getJSON(ApiUrl + "/index.php?act=connect_sms&op=check_sms_captcha", {
        type: 3,
        phone: phone,
        captcha: captcha
    },
    function(result) {
        if (!result.error) {
            window.location.href = "find_password_password.html?mobile=" + phone + "&captcha=" + captcha
        } else {
            loadSeccode();
            errorTipsShow("<p>" + result.error + "<p>")
        }
    })
}