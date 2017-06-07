$(function() {
    var key = getCookie("key");
    var seller_name = getCookie("seller_name");
    var s = getQueryString("store_id");
    if(s){
    	$("#backto_home").attr('href','store.html?store_id='+s);
    	$("#header-nav").attr('href','register.html?store_id='+s);
    }
    if (key&&seller_name) {
        window.location.href = WapSiteUrl + "/tmpl/seller/seller.html";
        return
    }
    var referurl = document.referrer;
    $.sValid.init({
        rules: {
            sellername: "required",
            sellerpwd: "required"
        },
        messages: {
            sellername: "用户名必须填写！",
            sellerpwd: "密码必填!"
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
    $("#loginbtn").click(function() { //商家登陆
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        if (a) {
            a = false
        } else {
            return false
        }
        var sellername = $("#sellername").val();
        var password = $("#sellerpwd").val();
        var client = "wap";
        if ($.sValid()) {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=seller_login&op=index",
                data: {
                    seller_name: sellername,
                    password: password,
                    client: client
                },
                dataType: "json",
                success: function(result) {					
                    a = true;	
					if (!CheckDataSucceed(result)){return false;}
					
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
					if (referurl==''){referurl = "seller.html";}
					location.href = referurl;
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
	    		location.href = ApiUrl + "/index.php?act=connect_wx&op=login";
	    	}
        })
	}else{
		$(".weixin").hide();
	}
});