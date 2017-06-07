$(function() {
    var key = getCookie("key");
    var username = getCookie("username");
    var s = getQueryString("store_id");
    if(s){
    	$("#backto_home").attr('href','store.html?store_id='+s);
    	$("#header-nav").attr('href','register.html?store_id='+s);
    }
    if (key&&username) {
        window.location.href = WapSiteUrl + "/tmpl/member/member.html";
        return
    }
    var referurl = document.referrer;
    $.sValid.init({
        rules: {
            username: "required",
            userpwd: "required"
        },
        messages: {
            username: "用户名必须填写！",
            userpwd: "密码必填!"
        },
        callback: function(e, r, a) {
            if (e.length > 0) {
                var i = "";
                $.map(r, function(e, r) {
                    i += "<p>" + e + "</p>"
                });
                errorTipsShow(i)
            } else {
                errorTipsHide()
            }
        }
    });
    var a = true;
    $("#loginbtn").click(function() {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        if (a) {
            a = false
        } else {
            return false
        }
        var username = $("#username").val();
        var password = $("#userpwd").val();
        var client = "wap";
        if ($.sValid()) {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=login",
                data: {
                    username: username,
                    password: password,
                    client: client
                },
                dataType: "json",
                success: function(result) {
                	//console.log(result);
                    a = true;	
					if (!CheckDataSucceed(result)){return false;}
					//alert('推广ID：'+result.OPEN_STORE_EXTENSION_STATE+'/n/r店铺ID：'+result.datas.store_id+'/n/r推广类型：'+result.datas.mc_id);
	
					var remhours = 0;
                    if ($("#checkbox").prop("checked")) {
                        remhours = 188
                    }
                    updateCookieCart(result.datas.key);
                    addCookie("username", result.datas.username, remhours);
                    addCookie("seller_name", result.datas.seller_name, remhours);
                    addCookie("key", result.datas.key, remhours);
                    addCookie("iMall_extension", result.extension_id, remhours);
					//向APP发送登录通知
					SendLoginInfoToApp(result.datas.key);
					
					if (result.OPEN_STORE_EXTENSION_STATE == 1 && result.datas.store_id>0 && (result.datas.mc_id==1 || result.datas.mc_id==2)){
						//单店推广
						if(result.extension_id){
							location.href = WapSiteUrl+'/tmpl/store.html?store_id='+result.datas.store_id+"&extension="+result.extension_id;
						}else{
							location.href = WapSiteUrl+'/tmpl/store.html?store_id='+result.datas.store_id;
						}
					}else{
						if (referurl==''){referurl = "member.html";}
						extension = result.extension.replace("&", "?");
						location.href = referurl+extension;
					}
                },
				error: function(){
			        ShowGetDataError();
		        }
            })
        }
    });
    var UA = navigator.appVersion.toLowerCase();
    var isqqBrowser = (UA.split("qq/").length > 1) ? true : false;
    var iswxBrowser = (UA.split("micromessenger/").length > 1) ? true : false;
    $(".weibo").click(function() {
        location.href = ApiUrl + "/index.php?act=connect&op=get_sina_oauth2"
    });
    $(".qq").click(function() {
        location.href = ApiUrl + "/index.php?act=connect&op=get_qq_oauth2"
    })
	if (iswxBrowser == true){		
	    $(".weixin").click(function() {
	    	if(s){
	    		location.href = ApiUrl + "/index.php?act=connect_wx&op=login&store_id=" + s;
	    	}else{	    		
	    		location.href = ApiUrl + "/index.php?act=connect_wx&op=login&ref="+document.referrer;
	    	}
        })
	}else{
		$(".weixin").hide();
	}
});