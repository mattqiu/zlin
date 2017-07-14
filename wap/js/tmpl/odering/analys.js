$(function(){
	var chart = null;
			$(function() {
				$('#container').highcharts({
					chart: {
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false,// 绘图投影
						spacing: [0, 0, 0, 0]
					},
					title: {
						floating: true,
						text: '圆心显示的标题'
					},
					tooltip: {
						pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
					},
					plotOptions: {
						pie: {
							allowPointSelect: true,
							cursor: 'pointer',
							dataLabels: {
								enabled: true,
								format: '<b>{point.name}</b>: {point.percentage:.1f} %',
								style: {
									color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
								}
							},
							point: {
								events: {
									mouseOver: function(e) { // 鼠标滑过时动态更新标题
										// 标题更新函数，API 地址：https://api.hcharts.cn/highcharts#Chart.setTitle
										chart.setTitle({
											text: e.target.name + '\t' + e.target.y + ' %'
										});
									}
									//, 
									// click: function(e) { // 同样的可以在点击事件里处理
									//     chart.setTitle({
									//         text: e.point.name+ '\t'+ e.point.y + ' %'
									//     });
									// }
								}
							},
						}
					},
					series: [{
						type: 'pie',
						innerSize: '40%',//里面的圆的大小
						name: '市场份额',
						data: [{
								name: 'Firefox',
								y: 45.0,
								url: 'http://bbs.hcharts.cn'
							},
							['IE', 26.8],
							{
								name: 'Chrome',
								y: 12.8,
								sliced: true,
								selected: true,
								url: 'http://www.hcharts.cn'
							},
							['Safari', 8.5],
							['Opera', 6.2],
							['其他', 0.7]
						]
					}]
				}, function(c) {
					// 环形图圆心
					var centerY = c.series[0].center[1],
						titleHeight = parseInt(c.title.styles.fontSize);
					c.setTitle({
						y: centerY + titleHeight / 2
					});
					chart = c;
				});
			});
	var keyword = '';
	$(function(){
		var analysajaxCommit = function(keyword) {

			$.ajax({
				type: "get",
				url: ApiUrl + "/index.php?act=store_ordering&op=analys&keyword="+keyword,
				dataType: "json",
				success: function(data) {
					var data = data.datas;
					var goods_spec_name   = data.goods_spec_name;//商品属性名称
					var spec_num_list     = data.spec_num_list;//商品属性订单列表
					var completion_target = data.completion_target;//指标完成

					//指标完成
					if(completion_target){
						$("#completion_target").empty();
						var completion_target_data = "";
						completion_target_data+= '<div class="text">指标完成(元)</div>'+
						'<div class="text"><span>'+completion_target['total_price']+'</span></div>'+
						'<div class="text"><span>'+completion_target['total_size_num']+'</span>款<span>'+completion_target['total_num']+'</span>件</div>';
						$("#completion_target").append(completion_target_data);
					}

					//商品属性名称
					if(goods_spec_name){
						$("#goods_spec").empty();
						var goods_spec = "";
						for (var i = 0; i < goods_spec_name.length; i++) {
							goods_spec +='<div class="button">'
							+goods_spec_name[i]+
						'</div>';
						}
						$("#goods_spec").append(goods_spec);
					}
					
					//商品属性订单列表
					if(spec_num_list){
						var goods_spec_list = "";
						$("#goods_spec_list").empty();
						for (var i = 0; i < spec_num_list.length; i++) {
						goods_spec_list +='<div class="num_list01 display-flex">'+
						'<div class="color" style="background-color:'+spec_num_list[i]['color']+'">'+
							
						'</div>'+
					'</div>'+
					'<div class="num_list02 text-center">'
						+spec_num_list[i]['spec_name']+
					'</div>'+

					'<div class="num_list03 text-center">'
						+spec_num_list[i]['num']+
					'</div>'+
					'<div class="num_list04 text-center">'
						+spec_num_list[i]['proportion']+
					'</div>';
						}

						$("#goods_spec_list").append(goods_spec_list);
					}
					
						$(".button").click(function(){
							keyword = $(this).text();					
							//$(".button").removeClass("button_active");
							
							analysajaxCommit(keyword);
							$(this).addClass("button_active");
						});
					
				},

				error: function(xhr, type, errorThrown) {
					//异常处理；
					console.log(type);
				}
			});
		}
		analysajaxCommit(keyword);
	});



})
