// 选择商品分类

//第一条分类
var gcategory_text=null;
fistlist();
function fistlist() {
    $('#first_gcategory').empty();
    $.getJSON('index.php?act=store_goods_add&op=ajax_goods_class', {gc_id : 0, deep: 1}, function(data) {
        $.map(data,function (h) {
            var list='<li class="" nctype="selClass" data-param="{gcid:'+h.gc_id+',deep:1,tid:'+h.type_id+'}"> <a class="" href="javascript:void(0)"><i class="icon-double-angle-right"></i>'+h.gc_name+'</a></li>'
            $('#first_gcategory').append(list);
        })
        $('#first_gcategory').find('li').each(function (index) {
            $(this).click(function () {
                sect(index);
            })
        })
    })
}
function sect(index) {

	var selbox=$('#first_gcategory').find('li').eq(index);
    gcategory_text=selbox.find('a').text();
    eval('var data_str = ' +selbox.data('param'));
    $('#class_div_2').find('ul').empty();

    $.getJSON('index.php?act=store_goods_add&op=ajax_goods_class', {gc_id : data_str.gcid, deep: 2}, function(data) {
        $.map(data,function (n) {
            var libox2='<li data-param="{gcid:'+ n.gc_id +',deep:'+ 2 +',tid:'+ n.type_id +'}"><a class="" href="javascript:void(0)"><i class="icon-double-angle-right"></i>'
                + n.gc_name + '</a></li>';
            $('#class_div_2').children('ul').append(libox2);
        })
        $('#class_div_2').children('ul').append('<b class="clear"></b>');
        $('.sort_list1').hide();
        $('.sort_list2').show();
        $('.sort_list3').hide();
        $('#class_div_2').find('li').each(function (index) {
            $(this).click(function () {
                thirdlist(index);
            })
        })
	})
}

function thirdlist(index) {
    var selbox= $('#class_div_2').find('li').eq(index);
    eval('var data_str = ' +selbox.data('param'));
    gcategory_text+='>'+$('#class_div_2').find('li').eq(index).find('a').text();
    $('#class_div_3').find('ul').empty();
    $.getJSON('index.php?act=store_goods_add&op=ajax_goods_class', {gc_id : data_str.gcid, deep:3}, function(data) {
        $.map(data,function (n) {
            var libox2='<li data-param="{gcid:'+ n.gc_id +',deep:'+ 3 +',tid:'+ n.type_id +'}"><a class="" href="javascript:void(0)"><i class="icon-double-angle-right"></i>'
                + n.gc_name + '</a></li>';
            $('#class_div_3').children('ul').append(libox2);
        })
        $('#class_div_3').children('ul').append('<b class="clear"></b>');
        $('.sort_list1').hide();
        $('.sort_list3').show();
        $('.sort_list2').hide();
        $('#class_div_3').find('li').each(function (index) {
            $(this).click(function () {
                var sel_state=  $(this).data('param');
                eval('var data_json = ' +sel_state);
                var gcid=data_json.gcid;
                
                if(gcid) {
                    $('#gcategory_hidden').val(gcid);
                    gcategory_text+= '>' + $(this).text();
                    $('.gcategory_input').html(gcategory_text);
                    gcategory_hide();
                    get_goods_speclist(gcid);//这里触发
                }
            });
        })
    })
}


// function usersort(index) {
//     $('#class_div_3').find('li').eq(index).find('a').addClass('classDivClick').parent().siblings().find('a').removeClass('classDivClick');
//     $('input[nctype="buttonNextStep"]').css({'background':'#ff0000','color':'#fff'});
// }
function gcategory_hide() {
    $('.sort_list1').show();
    $('.sort_list2').hide();
    $('.sort_list3').hide();
    $('.gcategory_box').animate({'right':'-150%'},500);
}

function get_goods_speclist(gcid) {
	$.getJSON('index.php?act=store_goods_add&op=ajax_goods_spec', {gc_id : gcid}, function(data) {
		//console.log(data);
		$('#goods_spec_group').html('<h3>商品规格</h3>');
		$('#goods_spec_group').show();
		var lisign='<input name="sign_i" type="hidden" nctype="sign_i" value="'+data.sign_i+'" />';
		var j=0;
		var ajax_spec_list = '';
        $.map(data.spec_list,function (n,i) {
        	//为了避免再次选择分类的时候，还会出现一次 如颜色和尺码问题
        	var th_spec_name = $('th[nctype="spec_name_'+i+'"]').html();
        	if(typeof(th_spec_name) == 'undefined'){
        		ajax_spec_list += '<th nctype="spec_name_'+i+'">'+n.sp_name+'</th>';
        	}
        	var libox='<dl nc_type="spec_group_dl_'+j+'" nctype="spec_group_dl" class="spec-bg"';
            if (i == '1'){libox += 'spec_img="t"';}
            libox += '>';
            libox += '<dd nctype="sp_group_val">';
            libox += '<input name="sp_name['+i+']" type="hidden" class="form-control" style="width: 70px;" title="自定义规格类型名称，规格值名称最多不超过4个字" value="'+n.sp_name+'" maxlength="4" imtype="spec_name" data-param="{id:'+i+',name:\''+n.sp_name+'\'}" />'; 
            libox += n.sp_name+'：';
            libox += '<ul class="spec">'; 
	            for(var sp in n.value){
	            	libox += '<li style="width:25%;"  data-param="{gc_id:'+gcid+',sp_value_id:'+n.value[sp].sp_value_id+',url:\''+SITEURL + '/index.php?act=store_goods_add&op=ajax_edit_spec\'}">';
	            	libox += '<span nctype="input_checkbox">';
	            	libox += '<input type="checkbox" value="'+n.value[sp].sp_value_name+'"';
	            	libox += '	nc_type="'+n.value[sp].sp_value_id+'"'; 
	            	if (i == '1'){ libox += 'class="sp_val"';}
					libox += '	name="sp_val['+i+']['+n.value[sp].sp_value_id+']">';
					libox += '</span>';
					libox += '<span nctype="pv_name">'+n.value[sp].sp_value_name+'</span>';
					libox += '</li>';
	            }
	        libox += '	<li data-param="{gc_id:'+gcid+',sp_id:'+i+',url:\''+SITEURL + '/index.php?act=store_goods_add&op=ajax_add_spec\'}" style="width: 100%">';
	        libox += '		<div nctype="specAdd1">';
	        libox += '        	<a href="javascript:void(0);" class="ncbtn" imtype="specAdd"><i class="icon-plus"></i>添加规格值</a>';
	        libox += '		</div>';
	        libox += '		<div nctype="specAdd2" style="display:none;">';
	        libox += '    		<input class="text w60" type="text" placeholder="规格值名称" maxlength="40">';
	        libox += '      	<a href="javascript:void(0);" imtype="specAddSubmit" class="ncbtn ncbtn-aqua ml5 mr5">确认</a>';
	        libox += '         	<a href="javascript:void(0);" imtype="specAddCancel" class="ncbtn ncbtn-bittersweet">取消</a>';
	        libox += '		</div>';
	        libox += '	</li>';
	        libox += '</ul>';
	        libox += '</dd>';
            libox += '</dl>';
            $('#goods_spec_group').append(lisign+libox);
            
            j++;
        })
        $('#ajax_goods_spec_list').show();
        $('.spec_table').find('tr').prepend(ajax_spec_list);
        var spec_checked = "<script>var spec_group_checked = [";
        var jsbox = "function ajax_into_array() {";
        for(var i = 0 ; i < data.sign_i; i++) {
        	if (i + 1 == data.sign_i) {
        		spec_checked +=  "''";
        	} else {
        		spec_checked += "'',";
        	}
        	jsbox += 'spec_group_checked_'+i+' = new Array();';
        	jsbox += '$(\'dl[nc_type="spec_group_dl_'+i+'"]\').find(\'input[type="checkbox"]:checked\').each(function () {';
        	jsbox += '	t = $(this).attr(\'nc_type\');';
        	jsbox += '	v = $(this).val();';
        	jsbox += '	c = null;';
        	jsbox += '	if ($(this).parents(\'dl:first\').attr(\'spec_img\') == \'t\') {';
        	jsbox += '	c = 1;';
        	jsbox += '	}';
        	jsbox += '	spec_group_checked_'+i+'[spec_group_checked_'+i+'.length] = [v, t, c];';
        	jsbox += '});';
        	jsbox += '	spec_group_checked['+i+'] = spec_group_checked_'+i+';';
    	}
     		jsbox += '}';
       	spec_checked += "];";
        var ajaxstockbox = "function ajax_goods_stock_set() {";
        	ajaxstockbox += '	$(\'input[name="g_price"]\').attr(\'readonly\', \'readonly\').css(\'background\', \'#E7E7E7 none\');';
        	ajaxstockbox += '   $(\'input[name="g_storage"]\').attr(\'readonly\', \'readonly\').css(\'background\', \'#E7E7E7 none\');';
        	
        	ajaxstockbox += '   $(\'dl[nc_type="spec_dl"]\').show();';
        	ajaxstockbox += '   str = \'<tr>\';';
        	ajaxstockbox += 	data.ajax_recursion_spec;
        	ajaxstockbox += '   if (str == \'<tr>\') {';
        	ajaxstockbox += '   	$(\'input[name="g_price"]\').removeAttr(\'readonly\').css(\'background\', \'\');';
        	ajaxstockbox += '    	$(\'input[name="g_storage"]\').removeAttr(\'readonly\').css(\'background\', \'\');';
        	ajaxstockbox += '       $(\'dl[nc_type="spec_dl"]\').hide();';
        	ajaxstockbox += '   } else {';
        	ajaxstockbox += '       $(\'tbody[nc_type="spec_table"]\').empty().html(str)';
        	ajaxstockbox += '    		.find(\'input[nc_type]\').each(function () {';
        	ajaxstockbox += '      		s = $(this).attr(\'nc_type\');';
        	ajaxstockbox += '       	try {';
        	ajaxstockbox += '             	$(this).val(V[s]);';
        	ajaxstockbox += '         	} catch (ex) {';
        	ajaxstockbox += '             	$(this).val(\'\');';
        	ajaxstockbox += '          	};';
                                
        	ajaxstockbox += '         	if ($(this).attr(\'data_type\') == \'marketprice\' && $(this).val() == \'\') {';
        	ajaxstockbox += '            	$(this).val($(\'input[name="g_marketprice"]\').val());';
        	ajaxstockbox += '          	}';
        	ajaxstockbox += '        	if ($(this).attr(\'data_type\') == \'price\' && $(this).val() == \'\') {';
        	ajaxstockbox += '            	$(this).val($(\'input[name="g_price"]\').val());';
        	ajaxstockbox += '         	}';
        	ajaxstockbox += '          	if ($(this).attr(\'data_type\') == \'stock\' && $(this).val() == \'\') {';
        	ajaxstockbox += '           	$(this).val(\'0\');';
        	ajaxstockbox += '        	}';
        	ajaxstockbox += '         	if ($(this).attr(\'data_type\') == \'alarm\' && $(this).val() == \'\') {';
        	ajaxstockbox += '          		$(this).val(\'0\');';
        	ajaxstockbox += '          	}';
        	ajaxstockbox += '         	if ($(this).attr(\'data_type\') == \'sku\' && $(this).val() == \'\') {';
        	ajaxstockbox += '            	$(this).val($(\'input[name="g_serial"]\').val());';
        	ajaxstockbox += '          	}';
        	ajaxstockbox += '    		}).end()';
        	ajaxstockbox += '          	.find(\'input[data_type="stock"]\').change(function () {';
        	ajaxstockbox += '          		computeStock();    ';// 库存计算
        	ajaxstockbox += '          	}).end()';
        	ajaxstockbox += '       	.find(\'input[data_type="price"]\').change(function () {';
        	ajaxstockbox += '          		computePrice();     ';// 价格计算
        	ajaxstockbox += '         	}).end()';
        	ajaxstockbox += '          	.find(\'input[nc_type]\').change(function () {';
        	ajaxstockbox += '          		s = $(this).attr(\'nc_type\');';
        	ajaxstockbox += '          		V[s] = $(this).val();';
        	ajaxstockbox += '     		});';
        	ajaxstockbox += '   	}';
        	ajaxstockbox += ' 	$(\'div[nctype="spec_div"]\').perfectScrollbar(\'update\');';
        	ajaxstockbox += '}</script>';
        $('#goods_spec_group').append(spec_checked+jsbox+ajaxstockbox);
        
    })
}
