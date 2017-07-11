$(function() {
	$.ajax({
		type:"get",
		url:"../../../../zlin/wap/js/tmpl/odering/form.json",
		async:true,
	    success:function(data){
	    	console.log(data);
	    	var item = data.datas;
	    	var list = [];
	    		var header = '<div class="header_center display-flex">'+
					'<div class="img display-flex">'+
						'<img src="../../image/solution1.jpg" alt="" />'+
					'</div>'+
					'<div class="text">'+
						'<div class="list">'+
							'<div class="name">货号</div>'+
							'<div class="num">'+item.goods_serial+'</div>'+
						'</div>'+
						'<div class="list">'+
							'<div class="name">名称</div>'+
							'<div class="num">'+item.goods_name+'</div>'+
						'</div>'+
						'<div class="list">'+
							'<div class="name">零售价</div>'+
							'<div class="num">'+item.goods_price+'</div>'+
						'</div>'+
					'</div>'+
				'</div>'
				list.push(header);
	    	$('.containers_header').append(list);
	    }
	});
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
	//单选多选的数据绑定
	var row = null;
	var col = null;
	var num = null;
	$('.sizes_center button').click(function() {
		$('.num .num_center .right input').val()
		var num2 = null;
		row = $(this).index();
		$('.color_center button').click(function() {
			col = $(this).index()
			$('.num .num_center .right input').val()
			$('.size_center .center_list').eq(row + 1).find('.list').eq(col + 1).find('input').addClass('input_active').parent().parent().siblings().find('input').removeClass('input_active');
			$('.size_center .center_list').eq(row + 1).find('.list').eq(col + 1).find('input').parent().siblings().find('input').removeClass('input_active');
			num2 = $('.size_center .center_list').eq(row + 1).find('.list').eq(col + 1).find('input').val();
			$('.num .num_center .right input').val(num2)
		})
	})
	$('.color_center button').click(function() {
		
		$('.num .num_center .right input').val()
		var num1 = null;
		col = $(this).index()
		$('.sizes_center button').click(function() {
			row = $(this).index();
			$('.num .num_center .right input').val()
			$('.size_center .center_list').eq(row + 1).find('.list').eq(col + 1).find('input').addClass('input_active').parent().parent().siblings().find('input').removeClass('input_active')
			$('.size_center .center_list').eq(row + 1).find('.list').eq(col + 1).find('input').parent().siblings().find('input').removeClass('input_active');
			num1 = $('.size_center .center_list').eq(row + 1).find('.list').eq(col + 1).find('input').val();
			$('.num .num_center .right input').val(num1)
		})
	})
	//全选操作，点击修改全部的数据
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
		} else if($('.sizes_center button.button_active').length == 1 || $('.color_center button.button_active').length == 1) {
			if($('.num .num_center .right input').val() > 0) {
				num--;
				$('.num .num_center .right input').val(num);
				$('.size_center input.input_active').val(num)
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
	//控制选择的弹出框的
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
		} else if($('.sizes_center button.button_active').length == 1 || $('.color_center button.button_active').length == 1) {
			num++;
			$('.num .num_center .right input').val(num);
			$('.size_center input.input_active').val(num)
		} else {
			num++;
			$('.num .num_center .right input').val(num);
			if($('.color .right .all').hasClass('all_active') && $('.sizes .right .all').hasClass('all_active')) {
				$('.size_center input').val(num)
			}
		}

	})
})