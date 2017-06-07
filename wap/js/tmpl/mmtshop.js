ShowMainMenu = true;

$(function() {

	$.ajax({
        url: ApiUrl + "/index.php?act=index&op=publicTop",
        type: 'get',
        dataType: 'json',
        success: function(result) {
			if (!CheckDataSucceed(result)){return false;}
			
			var data = result.datas;
			var html = template.render('pub_top', data);
            $("#main_Top").html(html);	
            if(result.extension_id){
            	var extension = "&extension="+result.extension_id;
            }else{
            	var extension = '';
            }
			$('.ico_03').click(function(){
                var keyword = encodeURIComponent($('#keywordtop').val());
                location.href = WapSiteUrl+'/tmpl/product_list.html?keyword='+keyword+extension;
            });		
			
			if (data.adv_list && data.adv_list.length >0){
			    TouchSlide({ 
	                slideCell:"#focus",
	                titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
	                mainCell:".bd ul", 
	                effect:"leftLoop", 
	                autoPlay:true,//自动播放
	                autoPage:true //自动分页
	            });	
			}			
		},
		error: function(){
			ShowGetDataError();
		}
    });

    $.ajax({
        url: ApiUrl + "/index.php?act=index",
        type: 'get',
        dataType: 'json',
        success: function(result) {
			if (!CheckDataSucceed(result)){return false;}
			
            var data = result.datas;
            var html = '';

            $.each(data, function(k, v) {
                $.each(v, function(kk, vv) {
                    switch (kk) {
                        case 'adv_list':
                        case 'home3':
                            $.each(vv.item, function(k3, v3) {
                                vv.item[k3].url = buildUrl(v3.type, v3.data, result.extension);
                            });
                            break;

                        case 'home1':
                            vv.url = buildUrl(vv.type, vv.data, result.extension);
                            break;

                        case 'home2':
                        case 'home4':
                            vv.square_url = buildUrl(vv.square_type, vv.square_data, result.extension);
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data, result.extension);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data, result.extension);
                            break;
						case 'goods':
						    vv.extension = result.extension;
						    break;
                    }
                    html += template.render(kk, vv);
                    return false;
                });
            });

            $("#main-container").html(html);

            $('.adv_list').each(function() {
                if ($(this).find('.item').length < 2) {
                    return;
                }

                Swipe(this, {
                    startSlide: 2,
                    speed: 400,
                    auto: 3000,
                    continuous: true,
                    disableScroll: false,
                    stopPropagation: false,
                    callback: function(index, elem) {},
                    transitionEnd: function(index, elem) {}
                });
            });
        },
		error: function(){
			ShowGetDataError();
		}
    });
});
