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

		if(0 < $('.color_center button.button_active').length && $('.color_center button.button_active').length < $('.color_center button').length) {

			$('.color_center button').addClass('button_active')
		} else {

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
		} else {
			$(this).toggleClass('button_active')
		}
		if(0 <= $('.color_center button.button_active').length && $('.color_center button.button_active').length < $('.color_center button').length) {
			$('.color_center .right .all').removeClass('all_active')
		} else {
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
		if(0 < $('.sizes_center button.button_active').length && $('.sizes_center button.button_active').length < $('.sizes_center button').length) {

			$('.sizes_center button').addClass('button_active')
		} else {

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
		} else {
			$(this).toggleClass('button_active')
		}
		if(0 <= $('.sizes_center button.button_active').length && $('.sizes_center button.button_active').length < $('.sizes_center button').length) {
			$('.sizes_center .right .all').removeClass('all_active')
		} else {
			$('.sizes_center .right .all').addClass('all_active')
		}
	})
	//模拟数据的双向绑定
	//第一步点击button的时侯找到相应的input输入框
	$('.color_center button').click(function() {
		console.log($(this).index())

	})

	$('.sizes_center button').click(function() {
		console.log($(this).index())
		bind_fortwo();
	})

	function bind_fortwo() {
		for(var i = 0; i < $('.sizes_center button').length; i++) {
			console.log($('.sizes_center').find('.button_active')[i])
		}
	};
	bind_fortwo();
	//第二部将数据绑定到相应的输入框里面

	//第三步 获取相应的数据 并提交
	//全选
	var num = null;
	$('.right .all').click(function() {
		console.log("全选的操作")
		if($('.color .right .all').hasClass('all_active') && $('.sizes .right .all').hasClass('all_active')) {
			$('.size_center input').addClass('input_active')
			var num = $('.num .num_center .right input').val();
			console.log(num);
			$('.size_center input').val(num);
		} else {
			$('.size_center input').removeClass('input_active')
		}

	})

})