$(function() {
	$('.tabbar .bar-list').click(function() {
		$(this).addClass('active').siblings().removeClass('active');
	})
	$('.tabbar .bar-list').eq(0).click(function(){
		$('.model-1').css('display','block');
		$('.model-2').css('display','none');
	})
	$('.tabbar .bar-list').eq(1).click(function(){
		$('.model-1').css('display','none');
		$('.model-2').css('display','block');
	})
	$('.tabbar .bar-list').eq(2).click(function(){
		 $(".model-3").animate({left:"0px"});
	})
	$('.footer-right').click(function(){
		$('.model-3').animate({left:"100%"});
	})
	$('.filter-center .small-circle').click(function(){
		$(this).toggleClass('filter-active');
	})
	//订货会03
	$('.tabbar .bar-list').eq(0).click(function(){
		$('.attribute').css('display','block').siblings().css('display','none');
	})
	$('.tabbar .bar-list').eq(1).click(function(){
		$('.selling').css('display','block').siblings().css('display','none');
	})
	$('.tabbar .bar-list').eq(2).click(function(){
		$('.related-to ').css('display','block').siblings().css('display','none');
	})
})