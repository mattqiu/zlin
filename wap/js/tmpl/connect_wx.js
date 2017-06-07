$(function() {
    var key = getCookie("key");
    var store_id = getQueryString("store_id");
    if(store_id){
    	$("#store_id").val(store_id);
    }
    if (key) {
		ShowErrorMessages("登录成功", "/tmpl/member/member.html");
    }else{
    	
		var ref = getQueryString("ref");
	    	$.getJSON(ApiUrl + "/index.php?act=connect_wx&op=autologin", {client: "wap"}, function(result) {
            if (result.code == 200) {
				if (result.datas.userid){
					updateCookieCart(result.datas.key);
                    addCookie("username", result.datas.username);
                    addCookie("key", result.datas.key);
					//向APP发送登录通知
					SendLoginInfoToApp(result.datas.key);
                    location.href = WapSiteUrl + "/tmpl/member/member.html?extension="+result.extension_id;
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
				if(store_id==''){
					store_id = result.datas.store_id;
				}
				$("#store_id").val(store_id);
				var lock = true; //防止重复提交
				//注册
				$("#register_btn").click(function() {					
					if(!lock){
			        	window.location.href = WapSiteUrl + "/tmpl/member/member.html"
			        	return false;
			        }
			        lock = false;
			        var reg_user_name = $("#reg_user_name").val();
			        var reg_password = $("#reg_password").val();
			        var member_mobile = $("#member_mobile").val();
                    if (reg_user_name=="" || reg_password=="") {
						errorTipsShow("<p>用户名和密码不能为空</p>")				
                        return false
                    }
		    
                    extension = result.extension_id;
		    if(extension==""){
		    	extension = getCookie("extension");
		    }
                    if(extension==""){
                    	errorTipsShow("<p>您没有邀请人无法注册</p>")				
                        return false
                    }
                    var client = "wap";
                    
					$(".pre-loading").show();
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?act=connect_wx&op=register",
                        data: {
                            reg_user_name: reg_user_name,
                            reg_password: reg_password,
                            member_mobile: member_mobile,
                            store_id: store_id,
                            extension:extension,
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
                                    addCookie("iMall_extension", result.extension_id)
									//向APP发送登录通知
					                SendLoginInfoToApp(result.datas.key);
									
                                    if(result.extension_id){
                                    	if(ref.indexOf("?") > 0 ){
                                    		var extension = "&extension="+result.extension_id;
    				                	}else{
    				                		var extension = "?extension="+result.extension_id;
    				                	}
                                    }else{
                                    	var extension = '';
                                    }
					                if (ref){
					                	location.href = ref + extension;
									}else{
	                                    location.href = WapSiteUrl + "/tmpl/member/member.html"+extension;
									}
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
			        var store_id = $("#store_id").val();
			        var extension = getQueryString("extension");
			        if(store_id==''){
			        	store_id = result.datas.store_id;
			        }
                    if (user_name=="" || password=="") {
						errorTipsShow("<p>用户名和密码不能为空</p>")				
                        return false
                    }
                    var client = "wap";
					$(".pre-loading").show();
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?act=connect_wx&op=binding",
                        data: {
                            user_name: user_name,
                            password: password,
                            store_id: store_id,
                            extension:extension,
                            client: client
                        },
                        dataType: "json",
                        success: function(result) {
							$(".pre-loading").hide();
                            if (result.code == 200) {
                                if (typeof result.datas.key == "undefined") {
                                    return false
                                } else {
                                	delCookie("username");
                                	delCookie("key");
                                	delCookie("iMall_extension");
                                    updateCookieCart(result.datas.key);
                                    addCookie("username", result.datas.username);
                                    addCookie("key", result.datas.key);
                                    addCookie("iMall_extension", result.extension_id)
									//向APP发送登录通知
					                SendLoginInfoToApp(result.datas.key);
                                    
                                    if(result.extension_id){
                                    	if(ref.indexOf("?") > 0 ){
                                    		var extension = "&extension="+result.extension_id;
    				                	}else{
    				                		var extension = "?extension="+result.extension_id;
    				                	}
                                    }
                                    if (ref){
                                    	if(ref.indexOf("?") > 0 ){
					                		location.href = ref + extension;
					                	}else{
					                		location.href = ref + extension;
					                	}
									}else{
                                        location.href = WapSiteUrl + "/tmpl/member/member.html"+"?extension="+result.extension_id;
									}
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