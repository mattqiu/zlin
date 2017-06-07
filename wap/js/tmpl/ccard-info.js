$(function(){
	var key = getcookie('key');
	if(key==''){
		location.href = 'login.html';
	}
	$.ajax({
		type:'get',
		url:ApiUrl+"/index.php?act=member_consumercard&op=my_consumercard",	
		data:{key:key},
		dataType:'json',
		//jsonp:'callback',
		success:function(result){
			checklogin(result.login);
			if(!result.datas.error){
			    var data = result.datas;			
			
			    data.WapSiteUrl = WapSiteUrl;
			    var html = template.render('ccard-info-tmpl', data);
                $("#ccard-info").html(html);
					
			    var html = template.render('ccard-profile-tmpl', data);
                $("#ccard-profile").html(html);
			}else{
				$.sDialog({
                    skin:"red",
                    content:result.datas.error,
                    okBtn:true,
                    cancelBtn:false,
					okFn: function() {location.href = WapSiteUrl+'/tmpl/member/member.html';}
				});
			}			
			return false;
		},
		error: function(){
			alert('获取信息失败');
		}	  
	});
});