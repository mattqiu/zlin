$(function() {
    var key = getCookie("key");
    if (!key) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    $.ajax({
        type: "get",
        url: ApiUrl + "/index.php?act=member_account&op=get_mobile_info",
        data: {
            key: key
        },
        dataType: "json",
        success: function(result) {
            if (result.code == 200) {
                if (result.datas.state) {
                    $("#mobile_link").attr("href", "member_mobile_modify.html");
                    $("#mobile_value").html(result.datas.mobile)
                }
            } else {}
        }
    });
    $.ajax({
        type: "get",
        url: ApiUrl + "/index.php?act=member_account&op=get_paypwd_info",
        data: {
            key: key
        },
        dataType: "json",
        success: function(result) {
            if (result.code == 200) {
                if (!result.datas.state) {
                    $("#paypwd_tips").html("未设置")
                }else{
					$("#paypwd_tips").html("已设置")
				}
            } else {}
        }
    })
	$.ajax({
        type: "get",
        url: ApiUrl + "/index.php?act=member_account&op=get_qqbind_info",
        data: {
            key: key
        },
        dataType: "json",
        success: function(result) {
            if (result.code == 200) {
                if (!result.datas.state) {
                    $("#qqbind_tips").html("未绑定")
					$("#qqbinding_btn").click(function() {
                        location.href = ApiUrl + "/index.php?act=connect&op=get_qq_oauth2"
                    })
                }else{
					$("#qqbind_tips").html("已绑定");
					$("#qqbinding_btn").click(function() {
                        location.href = WapSiteUrl + "/tmpl/member/member_qqbind.html"
                    })
				}
            } else {}
        }
    })
	$.ajax({
        type: "get",
        url: ApiUrl + "/index.php?act=member_account&op=get_wxbind_info",
        data: {
            key: key
        },
        dataType: "json",
        success: function(result) {
            if (result.code == 200) {
                if (!result.datas.state) {
                    $("#wxbind_tips").html("未绑定");
					$("#wxbinding_btn").click(function() {
                        location.href = WapSiteUrl + "/tmpl/member/member_wxbind.html"
                    })
                }else{
					$("#wxbind_tips").html("已绑定");
					$("#wxbinding_btn").click(function() {
                        location.href = WapSiteUrl + "/tmpl/member/member_wxbind.html"
                    })
				}
            } else {}
        }
    })	
});