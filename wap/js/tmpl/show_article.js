ShowMainMenu = true;

$(function (){
	var store_id = GetQueryString('store_id');
	var mn_id = GetQueryString('mn_id');	

	$.ajax({
        url: ApiUrl + "/index.php?act=store&op=publicTop&store_id="+store_id,
        type: 'get',
        dataType: 'json',
        success: function(result) {	
		    if (!CheckDataSucceed(result)){return false;}
						
            var data = result.datas;
			var html = template.render('pub_top', data);
            $("#main_Top").html(html);
			
			TouchSlide({ 
	            slideCell:"#focus",
	            titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
	            mainCell:".bd ul", 
	            effect:"leftLoop", 
	            autoPlay:true,//自动播放
	            autoPage:true //自动分页
	        });
        },
		error: function(){
			ShowGetDataError();
		}
    });
	
	$.ajax({
        url: ApiUrl + "/index.php?act=store&op=show_article&store_id="+store_id+"&mn_id="+mn_id,
        type: 'get',
        dataType: 'json',
        success: function(result) {
			if (!CheckDataSucceed(result)){return false;}
			
            $("#main-container").html(result.datas.info);
        },
		error: function(){
			ShowGetDataError();
		}
    });
});