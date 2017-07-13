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

	$(function(){
		var goodsajaxCommit 	= function() {
		$("#show1").empty();
		$("#two").empty();
			$.ajax({
				type: "get",
				url: ApiUrl + "/index.php?act=store_ordering&op=analys&keyword=价格",
				dataType: "json",
				success: function(data) {
					var data = data;
					console.log(data);
					
				},

				error: function(xhr, type, errorThrown) {
					//异常处理；
					console.log(type);
				}
			});
		}
		goodsajaxCommit();
	});
})
