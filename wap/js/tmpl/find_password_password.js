$(function() {
    var mobile = getQueryString("mobile");
    var captcha = getQueryString("captcha");
    $("#checkbox").click(function() {
        if ($(this).prop("checked")) {
            $("#password").attr("type", "text")
        } else {
            $("#password").attr("type", "password")
        }
    });
    $.sValid.init({
        rules: {
            password: "required"
        },
        messages: {
            password: "密码必填!"
        },
        callback: function(e, a, r) {
            if (e.length > 0) {
                var s = "";
                $.map(a, 
                function(e, a) {
                    s += "<p>" + e + "</p>"
                });
                errorTipsShow(s)
            } else {
                errorTipsHide()
            }
        }
    });
    $("#completebtn").click(function() {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        var password = $("#password").val();
        if ($.sValid()) {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=connect_sms&op=find_password",
                data: {
                    phone: mobile,
                    captcha: captcha,
                    password: password,
                    client: "wap"
                },
                dataType: "json",
                success: function(result) {
                    if (!result.error) {
                        addCookie("username", result.datas.username);
                        addCookie("key", result.datas.key);
                        addCookie("iMall_extension", result.extension_id);
                        location.href = WapSiteUrl + "/tmpl/member/member.html"
                    } else {
                        errorTipsShow("<p>" + result.error + "</p>")
                    }
                }
            })
        }
    })
});