$(function(){
	var key = getCookie('key');
	if(!key){
		location.href = 'login.html';
	}
	$.ajax({
		type:'get',
		url:ApiUrl+"/index.php?act=member_consumercard&op=my_ccarddetail",	
		data:{key:key},
		dataType:'json',
		//jsonp:'callback',
		success:function(result){
			if (!CheckDataSucceed(result,'/tmpl/member/member.html')){return false;}

			var data = result.datas;			
			
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