$(function() {
    var e = getCookie("key");
    if (e) {
        window.location.href = WapSiteUrl + "/tmpl/member/member.html";
        return
    }
    $.getJSON(ApiUrl + "/index.php?act=connect&op=get_state&t=connect_sms_reg", 
    function(e) {
        if (e.datas != "0") {
            $(".register-tab").show()
        }
    });
    var iMall_extension = getCookie("iMall_extension");
    var extension = getQueryString("extension");
    if(!iMall_extension && extension){
    	$("#extension").val(extension);
    }else{
        $("#extension").val(iMall_extension);
    }
    $.sValid.init({
        rules: {
            username: "required",
            userpwd: "required",
            password_confirm: "required",
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            username: "用户名必须填写！",
            userpwd: "密码必填!",
            password_confirm: "确认密码必填!",
            email: {
                required: "邮件必填!",
                email: "邮件格式不正确"
            }
        },
        callback: function(e, r, a) {
            if (e.length > 0) {
                var i = "";
                $.map(r, 
                function(e, r) {
                    i += "<p>" + e + "</p>"
                });
                errorTipsShow(i)
            } else {
                errorTipsHide()
            }
        }
    });
    $("#registerbtn").click(function() {
        if (!$(this).parent().hasClass("ok")) {
            return false
        }
        var e = $("input[name=username]").val();
        var r = $("input[name=pwd]").val();
        var a = $("input[name=password_confirm]").val();
        var i = $("input[name=email]").val();
        var t = "wap";
        if ($.sValid()) {
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=login&op=register",
                data: {
                    username: e,
                    password: r,
                    password_confirm: a,
                    email: i,
                    client: t
                },
                dataType: "json",
                success: function(e) {
                    if (!e.error) {
                        if (typeof e.datas.key == "undefined") {
                            return false
                        } else {
                            updateCookieCart(e.datas.key);
                            addCookie("username", e.datas.username);
                            addCookie("key", e.datas.key);
                            addCookie("iMall_extension", result.extension_id);
                            location.href = WapSiteUrl + "/tmpl/member/member.html"
                        }
                        errorTipsHide()
                    } else {
                        errorTipsShow("<p>" + e.error + "</p>")
                    }
                }
            })
        }
    })
	
	var UA = navigator.appVersion.toLowerCase();
    var isqqBrowser = (UA.split("qq/").length > 1) ? true : false;
    var iswxBrowser = (UA.split("micromessenger/").length > 1) ? true : false;
	
    $(".qq").click(function() {
        location.href = ApiUrl + "/index.php?act=connect&op=get_qq_oauth2"
    })
	if (iswxBrowser == true){		
	    $(".weixin").click(function() {
	    	var s = getQueryString("store_id");
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