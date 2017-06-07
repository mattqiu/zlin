if(WeiXinOauth){
	var key = getCookie('key');	
	if(key==null){			
		var ua = window.navigator.userAgent.toLowerCase();
		if(ua.match(/MicroMessenger/i) == 'micromessenger'){
			window.location.href=ApiUrl+"/index.php?act=connect_wx&op=login&ref="+encodeURIComponent(window.location.href);
		}else{
			location.href = WapSiteUrl + "/tmpl/member/login.html";
		}
	}
}

$(function() {
    if (getQueryString("key") != "") {
        var key = getQueryString("key");
        var username = getQueryString("username");
        addCookie("key", key);
        addCookie("username", username);
    } else {
        var key = getCookie("key");
    }
    var s = getQueryString("store_id");
    if(s){
    	//绑定店铺 zhangchao
        $("#mbr_home").attr("href", "../../store.html?store_id=" + s);
    }
    if (key) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=member",
            data: {
                key: key
            },
            dataType: "json",
            success: function(result) {
            	//证明已经登录但是key丢失，需重新登陆
            	if(result.error){
            		delCookie("key");
            		delCookie("iMall_extension");
					//通知App退出登录
					SendLogOutMsgToApp();
                    location.href = WapSiteUrl +'/tmpl/member/login.html';
            	}
                if (!CheckDataSucceed(result,'/tmpl/member/login.html')){return false;}
				var data = result.datas;
                var html = '<div class="member-info">';
				   html += '<div class="user-avatar" id="user-avatar"> <img id="user_avatar_img" src="' + data.member_info.avator + '"/> </div>';
				   html += '<div class="user-name"> <span>' + data.member_info.user_name + "<sup>" + data.member_info.rank_name + "</sup></span> </div>";
				   html += "</div>";
                   html += '<div class="member-collect"><span><a href="favorites.html"><em>' + data.member_info.favorites_goods + '</em><p>商品收藏</p></a> </span>';
				   html += '<span><a href="favorites_store.html"><em>' + data.member_info.favorites_store + '</em><p>店铺收藏</p></a> </span>';
				   html += '<span><a href="views_list.html"><i class="goods-browse"></i><p>我的足迹</p></a> </span>';
				   html += '</div>';
                $(".member-top").html(html);

				if (result.OPEN_STORE_EXTENSION_STATE == 10 || data.member_info.extension_info.mc_id >= 1 || data.member_info.member_grade > 1) {
					if(result.extension_id){
                    	var extension = "&extension="+result.extension_id;
	                	var im_extension = "?extension="+result.extension_id;
                    }else{
                    	var extension = '';
                    }
					$("#service_center").removeClass("hide").addClass("show");
					//if (data.member_info.extension_info.mc_id >= 1 || data.member_info.member_grade > 1) {
						html  = '<li><a href="extension_info.html"><i class="cc-13"></i><p>推广中心</p></a></li>';
					//}else{
					//	html  = '<li><a href="extension_applyplat.html"><i class="cc-12"></i><p>申请加入推广计划</p></a></li>';			
					//}
					html += '<li><a href="microshop.html?id='+data.member_info.member_id+extension+'"><i class="cc-14"></i><p>个人微店</p></a></li>';
					html += '<li><a href="extension_qrcode.html"><i class="cc-15"></i><p>推广二维码</p></a></li>';
					$("#service_ul").html(html);
				}	

                html  = '<li><a href="order_list.html?data-state=state_new">' + (result.datas.member_info.order_await_pay > 0 ? "<em></em>": "") + '<i class="cc-01"></i><p>待付款</p></a></li>';
				html += '<li><a href="order_list.html?data-state=state_send">' + (result.datas.member_info.order_await_ship > 0 ? "<em></em>": "") + '<i class="cc-02"></i><p>待收货</p></a></li>';
				html += '<li><a href="order_list.html?data-state=state_notakes">' + (result.datas.member_info.order_shipped > 0 ? "<em></em>": "") + '<i class="cc-03"></i><p>待自提</p></a></li>';
				html += '<li><a href="order_list.html?data-state=state_noeval">' + (result.datas.member_info.order_finished > 0 ? "<em></em>": "") + '<i class="cc-04"></i><p>待评价</p></a></li>';
				html += '<li><a href="member_refund.html">('+(result.datas.member_info.order_return > 0 ? "<em></em>": "") + ')<i class="cc-05"></i><p>退款/退货</p></a></li>';
                $("#order_ul").html(html);
				
                html  = '<li><a href="predepositlog_list.html"><i class="cc-06"></i><p>积分</p></a></li>';
				html += '<li><a href="rechargecardlog_list.html"><i class="cc-07"></i><p>充值卡</p></a></li>';
				html += '<li><a href="voucher_list.html"><i class="cc-08"></i><p>代金券</p></a></li>';
				html += '<li><a href="redpacket_list.html"><i class="cc-09"></i><p>红包</p></a></li>';
				html += '<li><a href="pointslog_list.html"><i class="cc-10"></i><p>云币</p></a></li>';
                $("#asset_ul").html(html);
				
				$("#user-avatar").click(function() {
                    $.animationUp({
                        valve: "",
				        wrapper: "#list-avatar-wrapper",
                        scroll: ""
                    });
				});	
				
				$("#get_avatar_qq").click(function() {
					$(".operation-loading").removeClass("hide");
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?act=member&op=get_avatar_qq",
                        data: {
                            key: key
                        },
                        dataType: "json",
                        async: false,
                        success: function(result) {
							$(".operation-loading").addClass("hide");
                            if (result.code == 200) {
                                $("#user_avatar_img").attr("src",result.datas);
                                $(".operation-loading").addClass("hide");
								$.sDialog({
                                    skin: "red",
                                    content: "头像更换成功！",
                                    okBtn: false,
                                    cancelBtn: false
                                })
                            } else {
                                $.sDialog({
                                    skin: "red",
                                    content: result.error,
                                    okBtn: false,
                                    cancelBtn: false
                                })
                            }
                        }
                    })
				});
				
				$("#get_avatar_wx").click(function() {
					$(".operation-loading").removeClass("hide");
                    $.ajax({
                        type: "post",
                        url: ApiUrl + "/index.php?act=member&op=get_avatar_wx",
                        data: {
                            key: key
                        },
                        dataType: "json",
                        async: false,
                        success: function(result) {							
                            if (result.code == 200) {
								$("#user_avatar_img").attr("src",result.datas);
                                $(".operation-loading").addClass("hide");
								$.sDialog({
                                    skin: "red",
                                    content: "头像更换成功！",
                                    okBtn: false,
                                    cancelBtn: false
                                })
                            } else {
                                $.sDialog({
                                    skin: "red",
                                    content: result.error,
                                    okBtn: false,
                                    cancelBtn: false
                                })
                            }
                        }
                    })
				});	
				
				$('input[name="file"]').ajaxUploadImage({
                    url: ApiUrl + "/index.php?act=member&op=avatar_upload",
                    data: {
                        key: key
                    },
                    start: function(e) {
						$(".operation-loading").removeClass("hide");
                    },
                    success: function(e, result) {
						if (result.code == 200) {
							$("#user_avatar_img").attr("src",result.datas.file_url);
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
				
                return false;
            }
        })

    } else {
		$("#extension-barbox").removeClass("show").addClass("hide");
        var html = '<div class="member-info">';
		   html += '<a href="login.html" class="default-avatar" style="display:block;"></a>';
		   html += '<a href="login.html" class="to-login">点击登录</a>'
		   html += '</div>';
		   html += '<div class="member-collect">';
		   html += '<span><a href="login.html"><i class="favorite-goods"></i><p>商品收藏</p></a></span>';
		   html += '<span><a href="login.html"><i class="favorite-store"></i><p>店铺收藏</p></a></span>';
		   html += '<span><a href="login.html"><i class="goods-browse"></i><p>我的足迹</p></a></span>';
		   html += '</div>';
        $(".member-top").html(html);
		
        html  = '<li><a href="login.html"><i class="cc-01"></i><p>待付款</p></a></li>';
		html += '<li><a href="login.html"><i class="cc-02"></i><p>待收货</p></a></li>';
		html += '<li><a href="login.html"><i class="cc-03"></i><p>待自提</p></a></li>';
		html += '<li><a href="login.html"><i class="cc-04"></i><p>待评价</p></a></li>';
		html += '<li><a href="login.html"><i class="cc-05"></i><p>退款/退货</p></a></li>';
        $("#order_ul").html(html);
        return false

    }
    $.scrollTransparent();
    /*
	if (navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)) {
        var loadDateTime = new Date();
        window.setTimeout(function() {
            var timeOutDateTime = new Date();
            if (timeOutDateTime - loadDateTime < 5000) {
                //未安装
            } else {
                //已安装
            }
        }, 25);
        window.location = "tencent100575450://";
	} else if (navigator.userAgent.match(/android/i)) {
        var state = null;
        try {
            state = window.open("tencent100575450://", '_blank');
        } catch(e) {}
        
		if (state) {
            //已安装
			alert('已安装');
        } else {
            //未安装
			alert('未安装');
		}
	}*/
});