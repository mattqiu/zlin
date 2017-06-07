if(WeiXinOauth){
	var key = getCookie('key');	
	if(key==null){			
		var ua = window.navigator.userAgent.toLowerCase();
		if(ua.match(/MicroMessenger/i) == 'micromessenger'){
			window.location.href=ApiUrl+"/index.php?act=connect_wx&op=login&ref="+encodeURIComponent(window.location.href);
		}
	}
}

$(function(){
		var key = getCookie('key');
		var seller_name = getCookie('seller_name');
		if(key==''||seller_name==null){
			location.href = 'login.html';
		}
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=seller_store&op=store_info",
			data:{key:key},
			dataType:'json',
			//jsonp:'callback',
			success:function(result){
				var data = result.datas;
				//console.log(data);
				$('#store_name').html(data.store_info.store_name);
				$('#store_sales').html(data.store_info.store_sales);
				//$('#goods_num').html(data.seller_info.goods_num);
				//$('#send_num').html(data.seller_info.send_num);
				
				return false;
			}
		});
		$("#t_downnav").addClass("hide");
		$("#d_agoodOpen").click(function() {
			$("#t_downnav").removeClass("hide");
		});
		$("#d_agoodClose").click(function() {
			$("#t_downnav").addClass("hide");
        });
		
		$("#fx-market").click(function() {
			window.location.href = WapSiteUrl + "/tmpl/seller/fxmarket.html";
        });
		$("#add-goods").click(function() {
			window.location.href = WapSiteUrl + "/tmpl/seller/add_goods.html";
        });
		$.scrollTransparent();
});

//检查数据获取是否正确
function CheckSDataSucceed(result,url){
	if(result.status.succeed==1){
		return true;
	}else{
		//登录过期，请重新登录
		if (result.status.error_code && result.status.error_code == 100){
			delCookie("seller_name");
            delCookie("key");
            delCookie("iMall_extension");
		}
		ShowErrorMessages(result.status.error_desc,url);
		return false;
	}
}
