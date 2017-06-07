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
