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
	//单选


		var row = null;
		var col = null;
		$('.color_center button').click(function() {
			col = $(this).index()
			console.log(col);
		})
		$('.sizes_center button').click(function() {
			var col = 2;
			row = $(this).index();
			console.log(row);
			console.log(typeof(row))
			//多选操作
			$('.size_center .center_list').eq(row+1).find('input').addClass('active').parent().parent().siblings().find('input').removeClass('active');
		})
	//全选操作
	var num = null;
	$('.right .all').click(function() {
		if($('.color .right .all').hasClass('all_active') && $('.sizes .right .all').hasClass('all_active')) {
			$('.size_center input').addClass('input_active')
			num = $('.num .num_center .right input').val();
			$('.size_center input').val(num)

		} else {
			$('.size_center input').removeClass('input_active')
		}

	})
	//数量增加减少操作
	$('.num .num_center .right .down').click(function() {
		//判断是否选择的长度是否为0；如果是0;弹出提示框
		if($('.sizes_center button.button_active').length == 0 || $('.color_center button.button_active').length == 0) {
			$('.warning1').show();
			$('.warning1').animate({
				opacity: '1'
			});
			setTimeout(function() {
				$('.warning1').animate({
					opacity: '0'
				});
			}, 3000);
		} else {
			//判断如果长度不是0的操作;
			if($('.num .num_center .right input').val() > 0) {
				num--;
				$('.num .num_center .right input').val(num);
				if($('.color .right .all').hasClass('all_active') && $('.sizes .right .all').hasClass('all_active')) {
					$('.size_center input').val(num)
				}
			} else {
				$('.warning').show();
				$('.warning').animate({
					opacity: '1'
				});
				setTimeout(function() {
					$('.warning').animate({
						opacity: '0'
					});
				}, 3000);
			}
		}

	})
	$('.num .num_center .right .up').click(function() {
		if($('.sizes_center button.button_active').length == 0 || $('.color_center button.button_active').length == 0) {
			$('.warning1').show();
			$('.warning1').animate({
				opacity: '1'
			});
			setTimeout(function() {
				$('.warning1').animate({
					opacity: '0'
				});
			}, 3000);
		} else {
			num++;
			$('.num .num_center .right input').val(num);
			if($('.color .right .all').hasClass('all_active') && $('.sizes .right .all').hasClass('all_active')) {
				$('.size_center input').val(num)
			}
		}

	})
})