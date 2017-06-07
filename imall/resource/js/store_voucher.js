$(document).ready(function(){
	/* ajax添加商品  */
	$('#voucher_add_goods').ajaxContent({
		event:'click', //mouseover
		loaderType:"img",
		loadingMsg:SHOP_SKINS_URL+"/images/loading.gif",
		target:'#voucher_add_goods_ajaxContent'
	}).click(function(){
	    $(this).hide();
	    $('#voucher_add_goods_delete').show();
	});
	
	$('#voucher_add_goods_delete').click(function(){
	    $(this).hide();
	    $('#voucher_add_goods_ajaxContent').html('');
	    $('#voucher_add_goods').show();
	});
	// 退拽效果
    $('tbody[imtype="voucher_data"]').sortable({ items: 'tr' });
    $('#goods_images').sortable({ items: 'li' });
});


/* 计算商品原价 */
function count_cost_price_sum(){
	data_price = $('td[imtype="voucher_data_price"]');
	if(typeof(data_price) != 'undefined'){
		var S_price = 0;
		data_price.each(function(){
			S_price += parseFloat($(this).html());
		});
		$('span[imtype="cost_price"]').html(S_price.toFixed(2));
	}else{
		$('span[imtype="cost_price"]').html('');
	}
}

/* 计算商品售价 */
function count_price_sum(){
    data_price = $('input[imtype="price"]');
    if(typeof(data_price) != 'undefined'){
        var S_price = 0;
        data_price.each(function(){
            S_price += parseFloat($(this).val());
        });
        //$('#discount_price').val(S_price.toFixed(2));
        $('#discount_price').attr("value",S_price.toFixed(2));
    }else{
        $('#discount_price').val('');
    }
}

/* 计算商品分销利润 */
function count_goods_gain_sum(){
	data_price = $('input[imtype="goods_gain"]');
	
	if(typeof(data_price) != 'undefined'){
		var S_price = 0;
		data_price.each(function(){
			S_price += parseFloat($(this).val());
		});
		$('span[imtype="gain_price"]').html(S_price.toFixed(2));
	}else{
		$('span[imtype="gain_price"]').html('');
	}
}