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
			url: WxappSiteUrl + "/index.php?act=ordering_goods&op=goods_detail&goods_commonid="+goods_commonid,
			dataType: "json",
			success: function(data) {
				var goods_detail = data.datas.goods_detail;
				var spec_name = data.datas.goods_detail.spec_name;
				var store_info   = data.datas.store_info;
				var goos_imgs    = data.datas.goods_detail.goos_imgs;
				//商品轮播图
				var img = "";
				for (var i = 0; i < goos_imgs.length; i++) {
					img += '<li><img class="img2" src="'+goos_imgs[i].goods_image+'" alt=""></li>'
				}
				/*console.log(img);
				return;*/
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
				'<div class="show-list-3 display-flex">';
				if(goods_detail.goods_sum!=0){
					li2+= '<div class="circle">'+
						goods_detail.goods_sum
					+'</div>'+
				'</div>';
			}else{
				li2+= '<img src="../../image/goods-nodh.jpg"/>'+
				'</div>';
			}
			
				
				//商品图片
				var li3 = 
				'<img src="'+goods_detail.goods_image+'" />';
				$('.attribute').append(li);
				$('.show-list').append(li2);
				$('.goodsshow').append(li3);
				$('.zhangshiul').append(img);
				//相关推荐
				var list = [];
				for(var i = 0; i < store_info.length; i++) {
					var li4 = 
					'<div class="related-show">'+
						'<a href="'+WapSiteUrl+'/tmpl/ordering/dinghuohui3.html?goods_commonid='+store_info[i].goods_commonid+'"><img src="'+store_info[i].goods_image+'" /></a>'+
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