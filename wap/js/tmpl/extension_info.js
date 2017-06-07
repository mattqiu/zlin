$(function(){
	var key = getCookie('key');
	if(!key){
		location.href = 'login.html';
	}
	$.ajax({
		type:'get',
		url:ApiUrl+"/index.php?act=member_extension&op=my_extensioninfo",	
		data:{key:key},
		dataType:'json',
		//jsonp:'callback',
		success:function(result){
			if (!CheckDataSucceed(result,'/tmpl/member/member.html')){return false;}

			var data = result.datas;			
			
			data.WapSiteUrl = WapSiteUrl;
			var html = template.render('extension-info-tmpl', data);
            $("#extension-info").html(html);
					
			var html = template.render('extension-profile-tmpl', data);
            $("#extension-profile").html(html);
	
			return false;
		},
		error: function(){
			ShowGetDataError();
		}	  
	});
});