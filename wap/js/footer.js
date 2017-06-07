if(WeiXinOauth){
	var key = getCookie('key');	
	if(key==null){			
		var ua = window.navigator.userAgent.toLowerCase();
		if(ua.match(/MicroMessenger/i) == 'micromessenger'){
			window.location.href=ApiUrl+"/index.php?act=connect_wx&op=login&ref="+encodeURIComponent(window.location.href);
		}
	}
}
$(function() {
    var a = getCookie("key");
    var s = getQueryString("store_id");
    var ge = getQueryString("extension");
    if(ge){
    	get_extension = "?extension="+ge;
    	ge = "&extension="+ge;
    }else{
    	get_extension ='';
    }
    var e = '<div class="qm-footer-wrap posr">' + '<div class="nav-text">';
    if (a) {
        e += '<a href="' + WapSiteUrl + '/tmpl/member/member.html">我的商城</a>' + '<a id="logoutbtn" href="javascript:void(0);">注销</a>' + '<a href="' + WapSiteUrl + '/tmpl/seller/seller.html">商家中心</a>'
    } else {
    	if(s){
    		e += '<a href="' + WapSiteUrl + '/tmpl/member/login.html' + get_extension + '">登录</a>' + '<a href="' + WapSiteUrl + '/tmpl/member/register.html?store_id=' + s + ge + '">注册</a>' + '<a href="' + WapSiteUrl + '/tmpl/seller/login.html">商家中心</a>'
    	}else{    		
    		e += '<a href="' + WapSiteUrl + '/tmpl/member/login.html' + get_extension + '">登录</a>' + '<a href="' + WapSiteUrl + '/tmpl/member/register.html' + get_extension + '">注册</a>' + '<a href="' + WapSiteUrl + '/tmpl/seller/login.html">商家中心</a>'
    	}
    }
    e += '<a href="javascript:void(0);" class="gotop">返回顶部</a>' + "</div>" + '<div class="nav-pic">' + '<a href="' + SiteUrl + '/index.php?act=mb_app" class="app"><span><i></i></span><p>客户端</p></a>' + '<a href="javascript:void(0);" class="touch"><span><i></i></span><p>触屏版</p></a>' + '<a href="' + SiteUrl + '" class="pc"><span><i></i></span><p>电脑版</p></a>' + "</div>" + '<div class="copyright">' + 'Copyright&nbsp;&copy;&nbsp;2014-2016领沃中国<a href="javascript:void(0);">hzlwo.com</a>版权所有' + "</div>";
    $("#footer").html(e);
    var a = getCookie("key");
    $("#logoutbtn").click(function() {
        var a = getCookie("username");
        var s = getCookie("seller_name");
        var e = getCookie("key");
        var i = "wap";
        $.ajax({
            type: "get",
            url: ApiUrl + "/index.php?act=logout",
            data: {
                username: a,
                seller_name: s,
                key: e,
                client: i
            },
            success: function(a) {
                if (a) {
                    delCookie("username");
                    delCookie("seller_name");
                    delCookie("key");
                    delCookie("iMall_extension");
					//通知App退出登录
					SendLogOutMsgToApp();
					
                    location.href = WapSiteUrl
                }
            }
        })
    })
});