$(function(){
	var key = getCookie('key');
	if(key==''){
		window.location.href = WapSiteUrl+'/tmpl/member/login.html';
	}
	var pay_sn = getQueryString('pay_sn');

    $.ajax({			
		url:ApiUrl+"/index.php?act=member_buy&op=pay&pay_sn="+pay_sn,
		type:'get',
		data:{key:key},
		dataType:'json',
		success:function(result){
			if (!CheckDataSucceed(result)){return false;}
			
			var data = result.datas;			
			data.WapSiteUrl = WapSiteUrl;//页面地址
			data.ApiUrl = ApiUrl;
			data.key = getCookie('key');
			template.helper('$getLocalTime', function (nS) {
                var d = new Date(parseInt(nS) * 1000);
                var s = '';
                s += d.getFullYear() + '年';
                s += (d.getMonth() + 1) + '月';
                s += d.getDate() + '日 ';
                s += d.getHours() + ':';
                s += d.getMinutes();
                return s;
		    });
            template.helper('p2f', function(s) {
                return (parseFloat(s) || 0).toFixed(2);
            });
			
			data.extension_id = result.extension_id;
			var html = template.render('order-list-tmpl', data);
			$("#order-list").html(html);
				
            $(window).scrollTop(0);
		}
	});
});
