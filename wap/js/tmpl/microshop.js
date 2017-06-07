var getData_tag = true;

if(WeiXinOauth){
	var key = getCookie('key');	
	if(key==null){			
		var ua = window.navigator.userAgent.toLowerCase();
		if(ua.match(/MicroMessenger/i) == 'micromessenger'){
			getData_tag = false;
			window.location.href=ApiUrl+"/index.php?act=connect_wx&op=login&extension="+getQueryString("extension")+"&ref="+encodeURIComponent(window.location.href);
		}
	}
}

$(function() {
    var id  = getQueryString("id");
    var key = getCookie("key");
	
    if ((getData_tag==true) && (key || id)) {
		var extension = getQueryString("extension");
        $.ajax({
            type: "get",
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
                	var extension = "&extension="+result.extension_id;
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
				
				html  = '';
				if (data.view_type == 1){
				    html  = '<div class="member-bgedit-tips"><span>轻触更换背景</span></div>';
				}
                html += '<div class="member-collect"><span><a href="javascript:void(0);"><em>' + data.member_info.distribute_goods + '</em><p>宝贝</p></a> </span>';
			    html += '<span><a href="javascript:void(0);"><i class="forward"></i><p>推荐宝贝</p></a> </span>';
			    html += '<span><a href="javascript:void(0);"><em>' + data.member_info.fans_all + '</em><p>粉丝</p></a> </span>';				   
			    html += '</div>';
                $(".member-top").html(html);
				
				html = template.render('bottom-menu-tmpl', data);
                $("#bottom_menu").html(html);
				
				html = template.render('list-qrcode-tmpl', data);
                $("#list-qrcode-wrapper").html(html);				
				
				$("#microshop_fans").click(function() {
                    location.href = WapSiteUrl + "/tmpl/member/microshop_fans.html?id=" + data.member_info.shop_id + extension;	
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
				//编辑模式
				if (data.view_type == 1){
				    html = template.render('list-backgroud-tmpl', data);
                    $("#list-backgroud-wrapper").html(html);
					
					$("#shop_top").click(function() {
                        $.animationUp({
                            valve: "",
				            wrapper: "#list-backgroud-wrapper",
                            scroll: ""
                        });
		            });
					
					$('input[name="file"]').ajaxUploadImage({
                        url: ApiUrl + "/index.php?act=member_distribute&op=backgroud_upload",
                        data: {
                            key: key
                        },
                        start: function(e) {
						    $(".operation-loading").removeClass("hide");
                        },
                        success: function(e, result) {
						    if (result.code == 200) {
							    $(".member-top").css("background-image","url("+result.datas.file_url+")");
							    $(".operation-loading").addClass("hide");
						    }else{
                                $.sDialog({
                                    skin: "red",
                                    content: result.error,
                                    okBtn: false,
                                    cancelBtn: false
                                });
                            }						
                        }
                    });	
				}
				
				return false
            },
		    error: function(){
			    ShowGetDataError();
		    }
        })
		
		var i = new qmscrollLoad;
        i.loadInit({
            url: ApiUrl + "/index.php?act=microshop&op=distribute_list",
            getparam: {
                key: key,
				id : id
            },
            tmplid: "distribute_goods_list",
            containerobj: $("#distribute_list"),
            iIntervalId: true,
            data: {
                WapSiteUrl: WapSiteUrl
            }
        });
		$("#distribute_list").on("click", "[im_type='distribute_del']", function() {
            var goods_id = $(this).attr("data_id");
            if (goods_id <= 0) {
                $.sDialog({
                    skin: "red",
                    content: "删除失败",
                    okBtn: false,
                    cancelBtn: false
                })
            }
            if (dropDistributeGoods(goods_id)) {
                $("#disitem_" + goods_id).remove();
                if (!$.trim($("#distribute_list").html())) {
                    location.href = WapSiteUrl + "/tmpl/member/microshop.html?id=" + id + '&extension=' + extension;	
                }
            }
        })		
    } else {
        return false
    }
});