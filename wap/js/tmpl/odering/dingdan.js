$(function() {
	$('.center .bigcircle').click(function() {
		$(this).find('.smallcircle').addClass('active')
		$(this).siblings().find('.smallcircle').removeClass('active')
	})
	$('.right .all').click(function() {
		$(this).toggleClass('all_active');
		$('#more').find('.smallcircle').addClass('active');
		$('#only').find('.smallcircle').removeClass('active');
		console.log($('.color_centers button').length);
//		if($()){
//			$('.color_centers button').toggleClass('button_active')
//		}else{
//			$('.color_centers button').addClass('button_active')
//		}
	})
	$('#only').click(function() {
		$('.right .all').removeClass('all_active')
	})
	$('.color_center button').click(function() {
		console.log($('#only .smallcircle').hasClass('active'));
		if($('#only .smallcircle').hasClass('active')) {
            $(this).addClass('button_active').siblings().removeClass('button_active')
		}else{
			 $(this).toggleClass('button_active')
		}
	})
	
})