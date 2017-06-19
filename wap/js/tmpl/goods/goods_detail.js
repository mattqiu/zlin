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

      alert(goods_commonid);

			$.ajax({
			type: "get",
			url: WxappSiteUrl + "/index.php?act=goods&op=goods_detail&goods_commonid="+goods_commonid,
			dataType: "json",
			success: function(data) {
				var goods_detail = data.datas.goods_detail;
				var store_info   = data.datas.store_info;
				console.log(store_info);

				var li = '<div class="attribute-list">'+
                	'<div class="public">品牌名称</div><div class="publics">'+goods_detail.goods_commonid+'</div>'+
                '</div>'+
                '<div class="attribute-list">'+
                	'<div class="public">货号</div><div class="publics">'+goods_detail.goods_commonid+'</div>'+
                '</div>'+
                '<div class="attribute-list">'+
                	'<div class="public">颜色</div><div class="publics">'+goods_detail.goods_commonid+'</div>'+
                '</div>'+
                '<div class="attribute-list">'+
                	'<div class="public">尺码</div><div class="publics">'+goods_detail.goods_commonid+'</div>'+
                '</div>'+
                '<div class="attribute-list">'+
                	'<div class="public">类型</div><div class="publics">'+goods_detail.gc_name+'</div>'+
                '</div>'+
                '<div class="attribute-list">'+
                	'<div class="public">季节</div><div class="publics">'+goods_detail.goods_commonid+'</div>'+
                '</div>'+
                '<div class="attribute-list">'+
                	'<div class="public">上市日期</div><div class="publics">'+goods_detail.goods_selltime+'</div>'+
                '</div>'+
                '<div class="attribute-list">'+
                	'<div class="public">波段</div><div class="publics">'+goods_detail.goods_commonid+'</div>'+
                '</div>'+
                '<div class="attribute-list">'+
                	'<div class="public">面料</div><div class="publics">'+goods_detail.goods_commonid+'</div>'+
                '</div>'+
                '<div class="attribute-list">'+
                	'<div class="public">里料</div><div class="publics">'+goods_detail.goods_commonid+'</div>'+
                '</div>';
                var li2 = 
                '<div class="show-list-2">'+
					'<div class="goods-name">'+goods_detail.goods_name+'</div>'+
					'<div class="goods-num">'+goods_detail.goods_commonid+'</div>'+
					'<div class="goods-price "><span class="iconfont">&#xe600;</span><span>'+goods_detail.goods_commonid+'</span></div>'+
				'</div>'+
				'<div class="show-list-3 display-flex">'+
					'<div class="circle">'+
						goods_detail.goods_total
					+'</div>'+
				'</div>';
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
							'<div class="goods-num">'+store_info[i].goods_commonid+'</div>'+
							'<div class="goods-price "><span class="iconfont">&#xe600;</span><span>'+store_info[i].goods_price+'</span></div>'+
						'</div>'+
					'</div>';

					list.push(li4);
				}
				$('#about_goods').append(list);
			},
					


			error: function(xhr, type, errorThrown) {
				//异常处理；
				console.log(type);
			}
		});
});