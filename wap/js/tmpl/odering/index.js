$(function() {

	let state_type = 'state_new';
	let size = '';
	let sc = 'asc';
	let order = 'goods_commonid';
	let page = '10';
	var ajaxCommit = function(type) {

		//我的订单
		if(type == 'order') {
			$('#good-shows').empty();
			//总排行榜	
		} else if(type == 'total') {
			$("#total").empty();
		}

		$.ajax({
			type: "get",
			url: ApiUrl + "/index.php?act=store_ordering&op=ordering_list&state_type=" + state_type + "&size=" + size + "&order=" + order + "&sc=" + sc + "&page=" + page + "&curpage=1",
			dataType: "json",
			success: function(data) {
				var datas = data.datas;
				var item = datas.ordering_list;
				var list = [];
				//我的订单 数据模板
				if(type == 'order') {
					for(var i = 0; i < item.length; i++) {
						var li = '<div class="good-show">' +
							'<div class="container-bar center">' +
							'<div class="display-flex border-bottoms">' +
							'<div class="modellist-1 border-rights list-height flex-start">' +
							'<img src="../image/solution1.jpg" />' +
							'<div class="text">' +
							'<div class="gray">' + item[i].goods_common_name + '</div>' +
							'<div>' + item[i].goods_commonid + '</div>' +
							'</div>' +
							'</div>' +
							'<div class="modellist-2 border-rights list-height display-flex text-center">' +
							item[i].num +
							'</div>' +
							'<div class="modellist-3 border-rights list-height display-flex text-center">' +
							item[i].goods_price +
							'</div>' +
							'<div class="modellist-4 list-height display-flex text-center">' +
							item[i].total_price +
							'</div>' +
							'</div>' +
							'</div>' +
							'</div>'

						list.push(li);
					}
					$('#good-shows').append(list);
					//总排行榜 数据模板	
				} else if(type == 'total') {
					for(var i = 0; i < item.length; i++) {
						var li =
							'<div class="flex-nowrap border-bottoms">' +
							'<div class="list-1 border-rights list-height display-flex">' +
							'<img src="../image/solution1.jpg" />' +
							'<div class="text">' +
							'<div class="gray">' +
							item[i].goods_common_name +
							'</div>' +
							'<div>' +
							item[i].goods_commonid +
							'</div>' +
							'</div>' +
							'</div>' +
							'<div class="list-2 border-rights list-height display-flex text-center">' +
							+item[i].num +
							'</div>' +
							'<div class="list-3 border-rights list-height display-flex text-center">' +
							+item[i].goods_price +
							'</div>' +
							'<div class="list-4 border-rights list-height display-flex text-center">' +
							+item[i].total_price +
							'</div>' +
							'<div class="list-5 list-height display-flex text-center">' +
							+item[i].sort +
							'</div>' +
							'</div>' +
							'</div>'

						list.push(li);
					}
					$('#total').append(list);
				}

			},

			error: function(xhr, type, errorThrown) {
				//异常处理；
				console.log(type);
			}
		});
	}

	/*我的订单*/
	$('#up-order').click(function() {
		sc = 'asc';
		ajaxCommit('order');
	})
	$('#down-order').click(function() {
		sc = 'desc';
		ajaxCommit('order');
	})
	$('#Productnumber').click(function() {
		order = 'goods_commonid';
		ajaxCommit('order');
	})
	$('#Number').click(function() {
		order = 'num';
		ajaxCommit('order');
	})
	$('#valuesales').click(function() {
		order = 'total_price';
		ajaxCommit('order');
	})
	/*总排行榜*/
	$("#total_data").click(function() {
		state_type = '';
		size = 'total';
		sc = 'desc';
		order = 'num';
		page = '0';
		ajaxCommit('total');
	});

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
		$('.bar-position2').css('display', 'none')
		$('.bar-position1').animate({
			height: 'toggle'
		})
	})

	$('#all').click(function() {
		$('.bar-position1').css('display', 'none')
		$('.bar-position2').animate({
			height: 'toggle'
		})
	})
	$('.bar-position2 .bar-pisition-list').click(function(){
		var a  = $(this).html();
		$('.bar-list-2 .text').html(a)
		$('.bar-position2').animate({
			height: 'toggle'
		})
	})
	$('#Samplesoftables').click(function() {
		if($('.iconfont01').css('display') == 'block') {
			$('.iconfont01').css('display', 'none').siblings().css('display', 'block');
		} else {
			$('.iconfont01').css('display', 'block').siblings().css('display', 'none');
		}
		if($('#show1').css('display') == 'block') {
			$('#show1').css('display', 'none').siblings().css('display', 'block')
		} else {
			$('#show1').css('display', 'block').siblings().css('display', 'none')
		}
	})

	$('#display').click(function(){
		$("#show3").css('display','block').siblings().css('display','none');
		$('.bar-position2').css('display','none')
		$('.bar-position1').css('display','none')
	})
	$('#alls').click(function(){
		$("#show2").css('display','block').siblings().css('display','none')
	})
	//筛选
	$('#filters').click(function(){
		$('.body').animate({left:'0px'})
	})
	$('.body .container-left').click(function(){
		$('.body').animate({left:'100%'})
	})
	$('#submit').click(function(){
		var min = $('.filter-input #input1').val()
		var max = $('.filter-input #input2').val()
	})
	/*页面加载完成执行ajax 加载我的订单默认数据*/
	//	ajaxCommit('order');
})