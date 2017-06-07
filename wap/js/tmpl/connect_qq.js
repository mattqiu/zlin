$(function() {
    var key = getCookie("key");
    if (key) {
		$(".pre-loading").show();
        $.getJSON(ApiUrl + "/index.php?act=connect_qq&op=bindqq", function(result) {
			$(".pre-loading").hide();
            if (result.code == 200) {
			    ShowErrorMessages("QQ绑定成功", "/tmpl/member/member.html");
            }else{
			    ShowErrorMessages(result.error, "/tmpl/member/member_account.html");
		    }
        });
    }else{
		$(".pre-loading").show();
	    $.getJSON(ApiUrl + "/index.php?act=connect_qq&op=autologin", {client: "wap"}, function(result) {
			$(".pre-loading").hide();
            if (result.code == 200) {
				if (result.datas.userid){
					updateCookieCart(result.datas.key);
                    addCookie("username", result.datas.username);
                    addCookie("key", result.datas.key);
                    addCookie("iMall_extension", result.extension_id);
					//向APP发送登录通知
					SendLoginInfoToApp(result.datas.key);
					
                    location.href = WapSiteUrl + "/tmpl/member/member.html";
					return true;
				}				
				
				$("#tab_register").click(function() {
					$("#binding").addClass("hide");
					$("#tab_binding").parent().removeClass("selected");					
					
					$("#register").removeClass("hide");
					$("#tab_register").parent().addClass("selected");
				})
				
				$("#tab_binding").click(function() {
					$("#register").addClass("hide");
					$("#tab_register").parent().removeClass("selected");	
					
					$("#binding").removeClass("hide");
					$("#tab_binding").parent().addClass("selected");
				})
				
				$("#reg_user_name").val(result.datas.user_name);
				$("#reg_password").val(result.datas.user_passwd);
				//注册
				$("#register_btn").click(function() {
			        var reg_user_name = $("#reg_user_name").val();
			        var reg_password = $("#reg_password").val();
                    if (reg_user_name=="" || reg_password=="") {
						errorTipsShow("<p>用户名和密码不能为空</p>")				
                        return false
                    }
                    var client = "wap";
					$(".pre-loading").show();
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?act=connect_qq&op=register",
                        data: {
                            reg_user_name: reg_user_name,
                            reg_password: reg_password,
                            client: client
                        },
                        dataType: "json",
                        success: function(result) {
							$(".pre-loading").hide();
                            if (result.code == 200) {
                                if (typeof result.datas.key == "undefined") {
                                    return false
                                } else {
                                    updateCookieCart(result.datas.key);
                                    addCookie("username", result.datas.username);
                                    addCookie("key", result.datas.key);
                                    addCookie("iMall_extension", result.extension_id);
									//向APP发送登录通知
					                SendLoginInfoToApp(result.datas.key);
									
                                    location.href = WapSiteUrl + "/tmpl/member/member.html"
                                }
                                errorTipsHide()
                            } else {
                                errorTipsShow("<p>" + result.error + "</p>")
                            }
                        }
                    })
				})
				
				//绑定已有帐号
				$("#binding_btn").click(function() {
			        var user_name = $("#user_name").val();
			        var password = $("#password").val();
                    if (user_name=="" || password=="") {
						errorTipsShow("<p>用户名和密码不能为空</p>")				
                        return false
                    }
                    var client = "wap";
					$(".pre-loading").show();
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?act=connect_qq&op=binding",
                        data: {
                            user_name: user_name,
                            password: password,
                            client: client
                        },
                        dataType: "json",
                        success: function(result) {
							$(".pre-loading").hide();
                            if (result.code == 200) {
                                if (typeof result.datas.key == "undefined") {
                                    return false
                                } else {
                                    updateCookieCart(result.datas.key);
                                    addCookie("username", result.datas.username);
                                    addCookie("key", result.datas.key);
                                    addCookie("iMall_extension", result.extension_id);
									//向APP发送登录通知
					                SendLoginInfoToApp(result.datas.key);
									
                                    location.href = WapSiteUrl + "/tmpl/member/member.html"
                                }
                                errorTipsHide()
                            } else {
                                errorTipsShow("<p>" + result.error + "</p>")
                            }
                        }
                    })
				})				    
            }else{
			    ShowErrorMessages(result.error, "/tmpl/member/login.html");
		    }
        });        
	}
});