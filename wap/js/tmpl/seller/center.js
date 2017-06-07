$(function() {
	var key = getCookie("key");
	if (key) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=seller_center",
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
                    location.href = WapSiteUrl +'/tmpl/seller/login.html';
            	}
                if (!CheckDataSucceed(result,'/tmpl/seller/login.html')){return false;}
				var data = result.datas;
                var html = '<div class="member-info">';
				   html += '<div class="user-avatar" id="user-avatar"> <img id="user_avatar_img" src="' + data.store_info.store_avatar + '"/><sup>' + data.store_info.grade_name + '</sup></div>';
				   html += '<div class="user-name"> <span>' + data.store_info.seller_name + "</span> </div>";
				   html += "</div>";
                   html += '<div class="member-collect"><span><a href="goods_list.html?data-state=online"><em class="sale_goods">' + data.store_info.goods_online + '</em><p>出售中</p></a> </span>';
				   html += '<span><a href="goods_list.html?data-state=offline"><em class="ck_goods">' + data.store_info.goods_offline + '</em><p>仓库中</p></a> </span>';
				   html += '<span><a href="goods_list.html?data-state=lockup"><em class="wg_goods">' + data.store_info.goods_lockup + '</em><p>违规下架</p></a> </span>';
				   html += '</div>';
                $(".member-top").html(html);
              
                html  = '<li><a href="order_list.html?data-state=state_new">' + (data.store_info.order_await_pay > 0 ? '<em style="display: inline;" ></em>': "") + '<i class="cc-17"></i><p>待付款</p></a></li>';
				html += '<li><a href="order_list.html?data-state=state_pay">' + (data.store_info.order_await_ship > 0 ? '<em style="display: inline;" ></em>': "") + '<i class="cc-14"></i><p>待发货</p></a></li>';
				html += '<li><a href="order_list.html?data-state=state_send">' + (data.store_info.order_shipped > 0 ? '<em style="display: inline;" ></em>': "") + '<i class="cc-22"></i><p>已发货</p></a></li>';
				html += '<li><a href="order_list.html?data-state=state_success">' + (data.store_info.order_finished > 0 ? '<em style="display: inline;" ></em>': "") + '<i class="cc-15"></i><p>已完成</p></a></li>';
				html += '<li><a href="order_list.html?data-state=state_cancel">'+(data.store_info.order_return > 0 ? '<em style="display: inline;" ></em>': "") + '<i class="cc-16"></i><p>已取消</p></a></li>';
                $("#order_ul").html(html);
				
                html  = '<li><a href="sale_stat.html"><i class="cc-17"></i><p>店铺概况</p></a></li>';
				html += '<li><a href="sale_stat.html?act=goodslist"><i class="cc-18"></i><p>店铺统计</p></a></li>';
				html += '<li><a href="sale_stat.html?act=storeflow"><i class="cc-19"></i><p>店铺流量</p></a></li>';
				html += '<li><a href="sale_stat.html?act=goodsflow"><i class="cc-20"></i><p>商品流量</p></a></li>';
				html += '<li><a href="sale_stat.html?act=hotgoods"><i class="cc-21"></i><p>热销排行</p></a></li>';
                $("#sale_stat").html(html);
                
                html  = '<li><a href="order_refund.html">' + (data.store_info.order_await_pay > 0 ? '<em class="order_refund"></em>': "") + '<i class="cc-23"></i><p>售前退款</p></a></li>';
                html += '<li><a href="order_refund.html?lock=1">' + (data.store_info.order_await_pay > 0 ? '<em class="order_refund"></em>': "") + '<i class="cc-24"></i><p>售后退款</p></a></li>';
                html += '<li><a href="order_return.html">' + (data.store_info.order_await_pay > 0 ? '<em class="order_return"></em>': "") + '<i class="cc-25"></i><p>售前退货</p></a></li>';
                html += '<li><a href="order_return.html?lock=1">' + (data.store_info.order_await_pay > 0 ? '<em class="order_return1"></em>': "") + '<i class="cc-26"></i><p>售后退货</p></a></li>';
                $("#order_refund").html(html);
                
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
        var html = '<div class="member-info">';
		html += '<a href="login.html" class="default-avatar" style="display:block;"></a>';
		html += '<a href="login.html" class="to-login">点击登录</a>'
		html += '</div>';
		html += '<div class="member-collect"><span><a href="goods_list.html?data-state=online"><em class="sale_goods">0</em><p>出售中</p></a> </span>';
		html += '<span><a href="goods_list.html?data-state=offline"><em class="ck_goods">0</em><p>仓库中</p></a> </span>';
		html += '<span><a href="goods_list.html?data-state=lockup"><em class="wg_goods">0</em><p>违规下架</p></a> </span>';
		html += '</div>';
		$(".member-top").html(html);
     
        html  = '<li><a href="login.html"><i class="cc-17"></i><p>待付款</p></a></li>';
		html += '<li><a href="login.html"><i class="cc-14"></i><p>待发货</p></a></li>';
		html += '<li><a href="login.html"><i class="cc-22"></i><p>已发货</p></a></li>';
		html += '<li><a href="login.html"><i class="cc-15"></i><p>已完成</p></a></li>';
		html += '<li><a href="login.html"><i class="cc-16"></i><p>已取消</p></a></li>';
        $("#order_ul").html(html);
        return false

    }
    $.scrollTransparent();
    
});