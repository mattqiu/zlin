$(function() {
	//控制颜色
	$('.center .bigcircle').click(function() {
		$(this).find('.smallcircle').addClass('active')
		$(this).siblings().find('.smallcircle').removeClass('active')
	})
	$('.color_center .right .all').click(function() {
		$(this).toggleClass('all_active');
		$('.center #more').find('.smallcircle').addClass('active');
		$('.center #only').find('.smallcircle').removeClass('active');


		if(0<$('.color_center button.button_active').length&&$('.color_center button.button_active').length<$('.color_center button').length){

			$('.color_center button').addClass('button_active')
		}else{

			$('.color_center button').toggleClass('button_active')
		}
	})
	$('.center #only').click(function() {
		$('.color_center .right .all').removeClass('all_active')
        $('.color_center button').removeClass('button_active')
	})
	$('.color_center button').click(function() {

		if($('.center #only .smallcircle').hasClass('active')) {
            $(this).toggleClass('button_active').siblings().removeClass('button_active')
		}else{
			$(this).toggleClass('button_active')
		}
		if(0<=$('.color_center button.button_active').length&&$('.color_center button.button_active').length<$('.color_center button').length){
			$('.color_center .right .all').removeClass('all_active')
		}else{
			$('.color_center .right .all').addClass('all_active')
		}
	})
	//控制尺码
	$('.sizes .bigcircle').click(function() {
		$(this).find('.smallcircle').addClass('active')
		$(this).siblings().find('.smallcircle').removeClass('active')
	})
	$('.sizes_center .right .all').click(function() {
		$(this).toggleClass('all_active');
		$('.centers #more').find('.smallcircle').addClass('active');
		$('.centers #only').find('.smallcircle').removeClass('active');
		if(0<$('.sizes_center button.button_active').length&&$('.sizes_center button.button_active').length<$('.sizes_center button').length){

			$('.sizes_center button').addClass('button_active')
		}else{

			$('.sizes_center button').toggleClass('button_active')
		}
	})
	$('.centers #only').click(function() {
		$('.sizes_center .right .all').removeClass('all_active')
        $('.sizes_center button').removeClass('button_active')
	})
	$('.sizes_center button').click(function() {
		if($('.centers #only .smallcircle').hasClass('active')) {
            $(this).toggleClass('button_active').siblings().removeClass('button_active')
		}else{
			 $(this).toggleClass('button_active')
		}
		if(0<=$('.sizes_center button.button_active').length&&$('.sizes_center button.button_active').length<$('.sizes_center button').length){
			$('.sizes_center .right .all').removeClass('all_active')
		}else{
			$('.sizes_center .right .all').addClass('all_active')
		}
	})
	
	
})