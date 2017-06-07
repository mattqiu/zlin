$(function() {
    var e = getCookie("key");
    if (!e) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    loadSeccode();
    $("#refreshcode").bind("click", 
    function() {
        loadSeccode()
    });
    $.sValid.init({
        rules: {
            pwd_code: "required",
            captcha: "required"
        },
        messages: {
            pwd_code: "请填写红包卡密",
            captcha: "请填写验证码"
        },
        callback: function(e, a, r) {
            if (e.length > 0) {
                var c = "";
                $.map(a, 
                function(e, a) {
                    c += "<p>" + e + "</p>"
                });
                errorTipsShow(c)
            } else {
                errorTipsHide()
            }
        }
    });
    $("#saveform").click(function() {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        if ($.sValid()) {
            var a = $.trim($("#pwd_code").val());
            var r = $.trim($("#captcha").val());
            var c = $.trim($("#codekey").val());
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=member_redpacket&op=rp_pwex",
                data: {
                    key: e,
                    pwd_code: a,
                    captcha: r,
                    codekey: c
                },
                dataType: "json",
                success: function(e) {
                    if (e.code == 200) {
                        location.href = WapSiteUrl + "/tmpl/member/redpacket_list.html"
                    } else {
                        loadSeccode();
                        errorTipsShow("<p>" + e.error + "</p>")
                    }
                }
            })
        }
    })
});