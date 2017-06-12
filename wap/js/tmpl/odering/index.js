$(function() {
	let sc = 'asc';
	let order = 'goods_commonid'
	$('#up-order').click(function(){
		sc = 'asc'
		console.log(sc)
	})
	$('#down-order').click(function(){
		sc = 'esc'
		console.log(sc)
		
	})
	$('#Productnumber').click(function(){
		order = 'goods_commonid'
		console.log(order);
	})
	$('#Number').click(function(){
		order = 'num'
		console.log(order);
	})
	$('#valuesales').click(function(){
		order = 'total_price'
		console.log(order);
	})
	$('.tabbar .bar-list').click(function() {
		$(this).addClass('active').siblings().removeClass('active');
	})
	$('.tabbar .bar-list').eq(0).click(function() {
		$('.model-1').css('display', 'block');
		$('.model-2').css('display', 'none');
	})
	$('.tabbar .bar-list').eq(1).click(function() {
		$('.model-1').css('display', 'none');
		$('.model-2').css('display', 'block');
	})
	$('.tabbar .bar-list').eq(2).click(function() {
		$(".model-3").animate({
			left: "0px"
		});
	})
	$('.footer-right').click(function() {
		$('.model-3').animate({
			left: "100%"
		});
	})
	//filter01点击切换样式
	$('.filter-center .filter01 .small-circle').click(function() {
		//		$(this).toggleClass('filter-active');
		$(this).addClass('filter-active').parents(".filter-list").siblings().find('.small-circle').removeClass('filter-active');
	})
	//filter01
	//filter02点击切换样式
	$('.filter-center .filter02 .small-circle').click(function() {

		//		$(this).toggleClass('filter-active');
		$(this).addClass('filter-active').parents(".filter-list").siblings().find('.small-circle').removeClass('filter-active');
	})
	//filter02点击
	//订货会03
	$('.tabbar .bar-list').eq(0).click(function() {
		$('.attribute').css('display', 'block').siblings().css('display', 'none');
	})
	$('.tabbar .bar-list').eq(1).click(function() {
		$('.selling').css('display', 'block').siblings().css('display', 'none');
	})
	$('.tabbar .bar-list').eq(2).click(function() {
		$('.related-to ').css('display', 'block').siblings().css('display', 'none');
	})
	$('#default').click(function() {

		$('.bar-position').animate({
			height: 'toggle'
		})
	})
})