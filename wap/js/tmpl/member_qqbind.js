$(function() {
    var key = getCookie("key");
    if (key === null) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
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
					$("#bind_icon").attr('src',result.datas.head_img);
                    $("#qq_nickname").html('未绑定');
					$("#unbind_btn").html("立即绑定");
					$("#unbind_btn").click(function() {
                        location.href = ApiUrl + "/index.php?act=connect&op=get_qq_oauth2"
                    })
                }else{
					$("#bind_icon").attr('src',result.datas.head_img);
					$("#qq_nickname").html(result.datas.nickname);
					$("#unbind_btn").click(function() {
                        $.ajax({
                            url: ApiUrl + "/index.php?act=member_account&op=qq_unbind",
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