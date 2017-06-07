$(function() {
    var id  = getQueryString("id");
    var key = getCookie("key");
	
    if (key || id) {
		var extension =  getQueryString("extension");
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=microshop",
            data: {
                key: key,
				id : id,
				extension : extension
            },
            dataType: "json",
            success: function(result) {
                if (!CheckDataSucceed(result)){return false;}			
			    var data = result.datas;
			    if(result.extension_id){
                	if(ref.indexOf("?") > 0 ){
                		var extension = "&extension="+result.extension_id;
                	}else{
                		var extension = "?extension="+result.extension_id;
                	}
                }else{
                	var extension = '';
                }
				//分享数据
			    mShareName = data.member_info.truename+'的微店';
                mShareURL = WapSiteUrl + "/tmpl/member/microshop.html?id=" + data.member_info.shop_id + extension;
                mShareContent = data.member_info.truename+'的微店';
				mSharePic = data.member_info.avator;
				
				var html = template.render('header-bar-tmpl', data);
                $("#header").html(html);				
			
			    $(".member-top").css("background-image","url("+data.member_info.shop_bg+")");
				
                html  = '<div class="member-collect"><span><a href="javascript:void(0);"><em>' + data.member_info.distribute_goods + '</em><p>宝贝</p></a> </span>';
			    html += '<span><a href="javascript:void(0);"><i class="groups"></i><p>微店联盟</p></a> </span>';
			    html += '<span><a href="javascript:void(0);"><em>' + data.member_info.fans_all + '</em><p>粉丝</p></a> </span>';				   
			    html += '</div>';
                $(".member-top").html(html);
				
				html = template.render('bottom-menu-tmpl', data);
                $("#bottom_menu").html(html);
				
				html = template.render('list-qrcode-tmpl', data);
                $("#list-qrcode-wrapper").html(html);				
				
				$("#microshop_fans").click(function() {
                    location.href = WapSiteUrl + "/tmpl/member/microshop.html?id=" + data.member_info.shop_id + extension;		
		        });
				
				$("#user_avatar_img").click(function() {
                    location.href = WapSiteUrl + "/tmpl/member/microshop.html?id=" + data.member_info.shop_id;
		        });
				
				$("#user-qrcode").click(function() {
                    $.animationUp({
                        valve: "",
				        wrapper: "#list-qrcode-wrapper",
                        scroll: ""
                    });
		        });
				
				return false
            },
		    error: function(){
			    ShowGetDataError();
		    }
        })
		
		var i = new qmscrollLoad;
        i.loadInit({
            url: ApiUrl + "/index.php?act=microshop&op=fans_list",
            getparam: {
                key: key,
				id : id
            },
            tmplid: "distribute_fans_list",
            containerobj: $("#fans_list"),
            iIntervalId: true,
            data: {
                WapSiteUrl: WapSiteUrl
            }
        });
    } else {
        return false
    }
});