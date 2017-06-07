$(function(){
	var key = getCookie('key');
	if(!key){
		location.href = 'login.html';
	}
	$.ajax({
		type:'get',
		url:ApiUrl+"/index.php?act=member_consumercard&op=my_ccardqrcode",	
		data:{key:key},
		dataType:'json',
		//jsonp:'callback',
		success:function(result){
			if (!CheckDataSucceed(result,'/tmpl/member/member.html')){return false;}

			var data = result.datas;
			//分享数据
			if (result.OPEN_CONSUMER_CARD_STATE==1){
			    mShareName = "轻轻一扫　幸福养老！";
                mShareURL = WapSiteUrl + '/tmpl/member/ccard_join.html?extension='+result.extension_id;
                mSharePic = data.ccard_info.ccard_qrcode;
                mShareContent = "亲诚MMT购物养老,以爱传爱，汇爱成海!";
			}else{
				mShareName = "推广二维码";
                mShareURL = WapSiteUrl + '/tmpl/member/extension_join.html?extension='+result.extension_id;
                mSharePic = data.ccard_info.ccard_qrcode;
                mShareContent = "扫一扫二维码，开创财富之旅！";
			}
			
			data.WapSiteUrl = WapSiteUrl;
			var html = template.render('ccard-info-tmpl', data);
            $("#ccard-info").html(html);
					
		    var html = template.render('ccard-profile-tmpl', data);
            $("#ccard-profile").html(html);
		
			return false;
		},
		error: function(){
			ShowGetDataError();
		}	  
	});
});