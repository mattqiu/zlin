$(function(){
    // ajax获取商品列表
    $('i[imtype="ajaxGoodsList"]').toggle(
        function(){
            $(this).removeClass('fa fa-plus-circle').addClass('fa fa-minus-circle');
            var _parenttr = $(this).parents('tr');
            var _commonid = $(this).attr('data-comminid');
            var _div = _parenttr.next().find('.imsc-goods-sku');
            if (_div.html() == '') {
                $.getJSON('index.php?act=supplier_goods_online&op=get_goods_list_ajax' , {commonid : _commonid}, function(date){
                    if (date != 'false') {
                        var _ul = $('<ul class="imsc-goods-sku-list"></ul>');
                        $.each(date, function(i, o){
                            $('<li><div class="goods-thumb" title="商家货号：' + o.goods_serial + '"><a href="' + o.url + '" target="_blank"><image src="' + o.goods_image + '" ></a></div>' + o.goods_spec + '<div class="goods-price">价格：<em title="￥' + o.goods_price + '">￥' + o.goods_price + '</em></div><div class="goods-storage" ' + o.alarm + '>库存：<em title="' + o.goods_storage + '" ' + o.alarm + '>' + o.goods_storage + '</em></div><a href="' + o.url + '" target="_blank" class="imsc-btn-mini">查看商品详情</a></li>').appendTo(_ul);
                        });
                        _ul.appendTo(_div);
                        _parenttr.next().show();
                        _div.perfectScrollbar();
                    }
                });
            } else {
            	_parenttr.next().show()
            }
        },
        function(){
            $(this).removeClass('fa fa-minus-circle').addClass('fa fa-plus-circle');
            $(this).parents('tr').next().hide();
        }
    );
});