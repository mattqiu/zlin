$(function(){
	$(".bar_center .ready").click(function(){
		$(this).addClass('active').siblings().removeClass('active');
	})
	
	$('.bar_center .ready').eq(0).click(function() {
		$('#ready').css('display', 'block');
		$('#not_ready').css('display', 'none');
	})
	$('.bar_center .ready').eq(1).click(function() {
		$('#ready').css('display', 'none');
		$('#not_ready').css('display', 'block');
	})
	$('.order_center .center_left .checkbox').click(function(){
		$(this).toggleClass('checkbox_sure');
	})
})
