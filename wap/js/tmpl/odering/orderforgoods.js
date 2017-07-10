$(function(){
	let	state_type = 'state_commit';
	let	size = 'order';
	let	sc = 'desc';
	let	order = 'num';
	let	page = '0';
	function ajaxCommit(type) {

		$('#order_completed').empty();
		$('#bottom').empty();
		$('#not_ready').empty();
		$.ajax({
			type: "get",
			url: ApiUrl + "/index.php?act=store_ordering&op=ordering_list&state_type=" + state_type + "&size=" + size + "&order=" + order + "&sc=" + sc + "&page=" + page + "&curpage=1",
			dataType: "json",
			success: function(data) {
				var datas = data.datas;
				var item  = datas.ordering_list;
				var item2 = datas.bottom;
				var list  = [];
				var list2 = [];
				var list3 = [];
				//订单管理数据模板
 				if(type == 'ordermanager') {
					for(var i = 0; i < item.length; i++) {
						var li =
							'<div class="order_center ">'+
						'<div class="row01 display-flex">'+
							'<div class="row_list1">'+
								'款号ZFQ1234555'
							+'</div>'+
							'<div class="row_list2">'+
								item[i].addtime
							+'</div>'+
						'</div>'+
						'<div class="row02 display-flex">'+
							'<div class="row_list1">'+

							'</div>'+
							'<div class="row_list2">'+
								'张小明'
							+'</div>'+
						'</div>'+
						'<div class="row03 display-flex">'+
							'<div class="row_list1">'+
								item[i].style_num+'款'+item[i].piece_num+'件'
							+'</div>'+
							'<div class="row_list2">'+
								item[i].total_price+'元'
							+'</div>'+
						'</div>'+
					'</div>'

						list.push(li);
					}
					$('#order_completed').append(list);
					if(item2){
						var li2 = 
						'<div class="footer_center display-flex">'+
							'<div class="list">'+
								'合计:<span>'+item2.total_style_num+'款'+item2.total_piece_num+'件</span>'+
							'</div>'+
							'<div class="list text-right">'+
								'总金额:<span>'+item2.total_price+'元</span>'+
							'</div>'+
						'</div>'
						list2.push(li2);
						$('#bottom').append(list2);
					}
										

				}else if(type == 'ordermanager_unfinished'){
					for(var i = 0; i < item.length; i++) {
						var li3 =
							'<div class="order_list display-flex">'+
						'<div class="order_center display-flex">'+
							'<div class="center_left display-flex">'+
								'<div class="checkbox">'+

								'</div>'+
							'</div>'+
							'<div class="center_right">'+
								'<div class="row01 display-flex">'+
									'<div class="row_list1">'+
										'款号ZFQ1234555'+
									'</div>'+
									'<div class="row_list2">'+
										item[i].addtime
									+'</div>'+
								'</div>'+
								'<div class="row02 display-flex">'+
									'<div class="row_list1">'+

									'</div>'+
									'<div class="row_list2">'+
										'张小明'+
									'</div>'+
								'</div>'+
								'<div class="row03 display-flex">'+
									'<div class="row_list1">'+
										item[i].style_num+'款'+item[i].piece_num+'件'+
									'</div>'+
									'<div class="row_list2">'+
										item[i].total_price+'元'+
									'</div>'+
								'</div>'+
							'</div>'+
					'</div>'

						list3.push(li3);
					}
					$('#not_ready').append(list3);
				}

			},

			error: function(xhr, type, errorThrown) {
				//异常处理；
				console.log(type);
			}
		});
	}
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
		console.log("被点击");
		$(this).toggleClass('checkbox_sure');
	})
	/*订单管理 已完成订单*/
	$("#already_order").click(function(){
		state_type = 'state_commit';
		ajaxCommit('ordermanager');
	});
	/*订单管理 未完成订单*/
	$("#unfinished_order").click(function(){
		state_type = 'state_new';
		ajaxCommit('ordermanager_unfinished');
	});
	ajaxCommit('ordermanager');
})
