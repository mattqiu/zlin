$(function() {
//获取url中的goods_commonid参数的值
      (function ($) {
        $.getUrlParam = function (name) {
          var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
          var r = window.location.search.substr(1).match(reg);
          if (r != null) return unescape(r[2]); return null;
        }
      })(jQuery);
      var goods_commonid = $.getUrlParam('goods_commonid');
      var goods_sum = $.getUrlParam('goods_sum');
			$.ajax({
			type: "get",
			url: WxappSiteUrl + "/index.php?act=goods&op=goods_detail&goods_commonid="+goods_commonid,
			dataType: "json",
			success: function(data) {
				var goods_detail = data.datas.goods_detail;
				var spec_name = data.datas.goods_detail.spec_name;
				//var spec_value = data.datas.goods_detail.spec_value;
				var store_info   = data.datas.store_info;
			  /*console.log(spec_name);
				console.log(spec_value);
				console.log(spec_name.length);
				console.log(spec_value.length);*/
				//商品属性
				var li = '<div class="attribute-list">'+
                	'<div class="public">品牌名称</div><div class="publics">'+goods_detail.brand_name+'</div>'+
                '</div>'+
                '<div class="attribute-list">'+
                	'<div class="public">货号</div><div class="publics">'+goods_detail.goods_serial+'</div>'+
                '</div>';
                for (var i = 0; i < spec_name.length; i++) {
                	li +='<div class="attribute-list">'+
                	'<div class="public">'+spec_name[i]+'</div><div class="publics">'+goods_detail.spec_value[i]+'</div>'+
                '</div>';
                }
                //商品详情
                var li2 = 
                '<div class="show-list-2">'+
					'<div class="goods-name">'+goods_detail.goods_name+'</div>'+
					'<div class="goods-num">'+goods_detail.goods_serial+'</div>'+
					'<div class="goods-price "><span class="iconfont">&#xe600;</span><span>'+goods_detail.goods_price+'</span></div>'+
				'</div>'+
				'<div class="show-list-3 display-flex">'+
					'<div class="circle">'+
						goods_sum
					+'</div>'+
				'</div>';
				//商品图片
				var li3 = 
				'<img src="'+goods_detail.goods_image+'" />';
				$('.attribute').append(li);
				$('.show-list').append(li2);
				$('.goodsshow').append(li3);
				//相关推荐
				var list = [];
				for(var i = 0; i < store_info.length; i++) {
					var li4 = 
					'<div class="related-show">'+
						'<img src="'+store_info[i].goods_image+'" />'+
						'<div class="show-list-2">'+
							'<div class="goods-num">'+store_info[i].goods_serial+'</div>'+
							'<div class="goods-price "><span class="iconfont">&#xe600;</span><span>'+store_info[i].goods_price+'</span></div>'+
						'</div>'+
					'</div>';

					list.push(li4);
				}
				$('#about_goods').append(list);
				//商品卖点
				li5 = goods_detail.goods_jingle;
				$('.selling').empty();
				$('.selling').append(li5);
			},
					


			error: function(xhr, type, errorThrown) {
				//异常处理；
				console.log(type);
			}
		});
});