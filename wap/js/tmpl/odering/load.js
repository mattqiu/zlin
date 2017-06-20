$(function() {
	var goods_total = 0;
	var is_ordering = 0;
	var minPrice = 0;
	var maxPrice = 0;
	$('.containers').dropload({
		scrollArea: window,
		domUp: {
			domClass: 'dropload-up',
			domRefresh: '<div class="dropload-refresh">↓下拉刷新-自定义内容</div>',
			domUpdate: '<div class="dropload-update">↑释放更新-自定义内容</div>',
			domLoad: '<div class="dropload-load"><span class="loading"></span>加载中-自定义内容...</div>'
		},
		domDown: {
			domClass: 'dropload-down',
			domRefresh: '<div class="dropload-refresh">↑上拉加载更多-自定义内容</div>',
			domLoad: '<div class="dropload-load"><span class="loading"></span>加载中-自定义内容...</div>',
			domNoData: '<div class="dropload-noData">暂无数据-自定义内容</div>'
		},
		//下拉刷新
		loadUpFn: function(me) {
			$.ajax({
				type: 'GET',
				url: WxappSiteUrl + "/index.php?act=search&op=index&goods_total=" + goods_total + "&is_ordering=" + is_ordering + "&minPrice=" + minPrice + "&maxPrice=" + maxPrice,
				dataType: 'json',
				success: function(data) {
					console.log(data);
					var datas = data.datas;
					var item = datas;
					var list = [];
					var list2 = [];
					for(var i = 0; i < item.length; i++) {
						list += '<div class="show1 display-flex">' +
							'<div class="show-list display-flex">' +
							'<div class="show-list-1 display-flex">' +
							'<div class="image">' +
							'<img src="' +
							item[i].goods_image +
							'" />' +
							'</div>' +
							'</div>' +
							'<div class="show-list-2">' +
							'<div class="goods-name">' +
							item[i].goods_name +
							'</div>' +
							'<div class="goods-num">' +
							item[i].goods_commonid +
							'</div>' +
							'<div class="goods-price "><span class="iconfont">&#xe600;</span><span>' +
							item[i].goods_price +
							'</span></div>' +
							'</div>' +
							'<div class="show-list-3 display-flex">' +
							'<div class="circle">' +
							item[i].goods_total + '</div>' +
							'</div>' +
							'</div>' +
							'</div>'
						list2 += '<div class="show_list">' +
							'<div class="img">' +
							'<img src="' +
							item[i].goods_image +
							'" />' +
							'</div>' +
							'<div class="name">' +
							item[i].goods_name +
							'</div>' +
							'<div class="text display-flex">' +
							'<div class="show-list-2">' +
							'<div class="goods-num">' +
							item[i].goods_commonid +
							'</div>' +
							'<div class="goods-price ">' +
							'<span class="iconfont">&#xe600;</span>' +
							item[i].goods_price +
							'</div>' +
							'</div>' +
							'<div class="show-list-3 display-flex">' +
							'<div class="circle">' +
							item[i].goods_total +
							'</div>' +
							'</div>' +
							'</div>' +
							'</div>'
					}
											$('#show1').append(list);
											$('#two').append(list2);
//					 为了测试，延迟1秒加载
					setTimeout(function() {
						$('#show1').html(list);
						$('#two').html(list2);
						// 每次数据加载完，必须重置
						me.resetload();
						// 重置页数，重新获取loadDownFn的数据
						page = 0;
						// 解锁loadDownFn里锁定的情况
						me.unlock();
						me.noData(false);
					}, 1000);
				},
				error: function(xhr, type) {
					console.log('Ajax error!');
					me.resetload();
				}
			});
		},

		//			上拉加载
		loadDownFn: function(me) {
			//				page++;
			// 拼接HTML
			var list = [];
			var list2 = [];
			$.ajax({
				type: 'GET',
				url: WxappSiteUrl + "/index.php?act=search&op=index&goods_total=" + goods_total + "&is_ordering=" + is_ordering + "&minPrice=" + minPrice + "&maxPrice=" + maxPrice,
				dataType: 'json',
				success: function(data) {
					var datas = data.datas;
					var item = datas;
					var list = [];
					var list2 = [];
					if(item.length > 0) {
						for(var i = 0; i < item.length; i++) {
							list += '<div class="show1 display-flex">' +
								'<div class="show-list display-flex">' +
								'<div class="show-list-1 display-flex">' +
								'<div class="image">' +
								'<img src="' +
								item[i].goods_image +
								'" />' +
								'</div>' +
								'</div>' +
								'<div class="show-list-2">' +
								'<div class="goods-name">' +
								item[i].goods_name +
								'</div>' +
								'<div class="goods-num">' +
								item[i].goods_commonid +
								'</div>' +
								'<div class="goods-price "><span class="iconfont">&#xe600;</span><span>' +
								item[i].goods_price +
								'</span></div>' +
								'</div>' +
								'<div class="show-list-3 display-flex">' +
								'<div class="circle">' +
								item[i].goods_total + '</div>' +
								'</div>' +
								'</div>' +
								'</div>'
							list2 += '<div class="show_list">' +
								'<div class="img">' +
								'<img src="' +
								item[i].goods_image +
								'" />' +
								'</div>' +
								'<div class="name">' +
								item[i].goods_name +
								'</div>' +
								'<div class="text display-flex">' +
								'<div class="show-list-2">' +
								'<div class="goods-num">' +
								item[i].goods_commonid +
								'</div>' +
								'<div class="goods-price ">' +
								'<span class="iconfont">&#xe600;</span>' +
								item[i].goods_price +
								'</div>' +
								'</div>' +
								'<div class="show-list-3 display-flex">' +
								'<div class="circle">' +
								item[i].goods_total +
								'</div>' +
								'</div>' +
								'</div>' +
								'</div>'
						}
						// 如果没有数据
					} else {
						// 锁定
						me.lock();
						// 无数据
						me.noData();
					}
					// 为了测试，延迟1秒加载
					setTimeout(function() {
						// 插入数据到页面，放到最后面
						$('#show1').append(list);
						$('#two').append(list2);
						// 每次数据插入，必须重置
						me.resetload();
					}, 1000);
				},
				error: function(xhr, type) {
					console.log('Ajax error!');
					// 即使加载出错，也得重置
					me.resetload();
				}
			});
		},
		threshold: 50
	});
});