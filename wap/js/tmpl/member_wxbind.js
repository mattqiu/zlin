$(function() {
    var key = getCookie("key");
    if (key === null) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
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
					$("#bind_icon").attr('src',result.datas.head_img);
                    $("#wx_nickname").html("微信绑定请到PC端或微信APP登录绑定");
					$("#unbind_btn").html("返　　回");
					$("#unbind_btn").click(function() {
                        location.href = WapSiteUrl+'/tmpl/member/member_account.html';
                    })
                }else{
					$("#bind_icon").attr('src',result.datas.head_img);
					$("#wx_nickname").html(result.datas.nickname);
					$("#unbind_btn").click(function() {
                        $.ajax({
                            url: ApiUrl + "/index.php?act=member_account&op=wx_unbind",
                            type: "post",
                            dataType: "json",
                            data: {
                                key: key
                            },
                            success: function(result) {
                                if (result.code == 200) {
									$.sDialog({
                                        skin:"red",
                                        content:result.datas,
                                        okBtn:true,
                                        cancelBtn:false,
					                    okFn: function() {location.href = WapSiteUrl+'/tmpl/member/member_account.html';}
				                    });
								}else{
									$.sDialog({
                                        skin:"red",
                                        content: result.error,
                                        okBtn: false,
                                        cancelBtn: false
                                    });									
								}
                            }
                        })
                    })
				}
            } else {}
        }
    })
});