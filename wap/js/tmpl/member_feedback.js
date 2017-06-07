$(function() {
    var key = getCookie("key");
    if (key === null) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    $("#feedbackbtn").click(function() {
        var a = $("#feedback").val();
        if (a == "") {
            $.sDialog({
                skin: "red",
                content: "请填写反馈内容",
                okBtn: false,
                cancelBtn: false
            });
            return false
        }
        $.ajax({
            url: ApiUrl + "/index.php?act=member_feedback&op=feedback_add",
            type: "post",
            dataType: "json",
            data: {
                key: key,
                feedback: a
            },
            success: function(result) {
                if (checkLogin(result.login)) {
                    if (!result.error) {
                        errorTipsShow("<p>提交成功</p>");
                        setTimeout(function() {
                            window.location.href = WapSiteUrl + "/tmpl/member/member.html"
                        },
                        3e3)
                    } else {
                        errorTipsShow("<p>" + result.error + "</p>")
                    }
                }
            }
        })
    })
});