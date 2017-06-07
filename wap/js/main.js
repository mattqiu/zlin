$(function() {
	$.ajax({
        url: ApiUrl + "/index.php?act=index&op=publicTop",
        type: 'get',
        dataType: 'json',
        success: function(result) {
			if (!CheckDataSucceed(result)){return false;}
			
			var data = result.datas;			
			var html = template.render('adv_list', data);
            $("#main-container1").html(html);	
			
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
