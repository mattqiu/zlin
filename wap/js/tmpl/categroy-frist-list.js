$(function() {
	$.ajax({
		url:ApiUrl+"/index.php?act=goods_class",
		type:'get',
		jsonp:'callback',
		dataType:'json',
		success:function(result){
			if (!CheckDataSucceed(result)){return false;}
			
			var data = result.datas;
			data.WapSiteUrl = WapSiteUrl;
			var html = template.render('category-one', data);
			$("#categroy-cnt").html(html);
		},
		error: function(){
			ShowGetDataError();
		}
	});
});