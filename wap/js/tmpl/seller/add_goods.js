$(function(){
	var key = getCookie('key');
	$("#gc_key").attr('value',key);
	var seller_name = getCookie('seller_name');
	if(key==''||seller_name==null){
		location.href = 'login.html';
	}
	$('input[name="goods_image"]').ajaxUploadImage({
        url: ApiUrl + "/index.php?act=seller_goods&op=image_upload&key="+key,
        data: {
            key: key,
            name:seller_name
        },
        start: function(e) {
            e.parent().after('<div class="upload-loading"><i></i></div>');
        },
        success: function(e, result) {
        	//console.log(result);
            if (result.error) {
                e.parent().siblings(".upload-loading").remove();
                $.sDialog({
                    skin: "red",
                    content: "图片尺寸过大！",
                    okBtn: false,
                    cancelBtn: false
                });
                return false
            }
            var gi_num = $("#gi_num").val()*1+1;
            e.parents("#gi_table").before('<div class="qm_upload qm1"><a href="javascript:void(0);"><div class="pic-thumb"><input type="hidden" id="goods_image_'+(gi_num-1)+'" name="goods_image['+(gi_num-1)+']" value="'+result.datas.file_name+'" /><img src="' + result.datas.pic + '"/></div></a></div>');
            e.parent().siblings(".upload-loading").remove();
            //e.parents("a").next().val(result.datas.file_name);
	        if(gi_num>5){
            	$("#gi_table").addClass("hide");
            	$.sDialog({
                    skin: "red",
                    content: "商品图片的数量不要超过5张！",
                    okBtn: false,
                    cancelBtn: false
                });
            }
			$("#gi_num").attr('value',gi_num);
        },
        error:function(XMLHttpRequest, textStatus, errorThrown){
            alert('上传失败！');  
        }  
    });
	
	$('input[name="body_pic"]').ajaxUploadImage({
        url: ApiUrl + "/index.php?act=seller_goods&op=upload_pic",
        data: {
            key: key,
            name:seller_name
        },
        start: function(e) {
            e.parent().after('<div class="upload-loading"><i></i></div>');
            //e.parent().siblings(".pic-thumb").remove()
        },
        success: function(e, result) {
        	//console.log(result);
            if (result.error) {
                e.parent().siblings(".upload-loading").remove();
                $.sDialog({
                    skin: "red",
                    content: "图片尺寸过大！",
                    okBtn: false,
                    cancelBtn: false
                });
                return false
            }
            var bi_num = $("#bi_num").val()*1+1;
            e.parents("#gbi_upload").before('<div class="qm_upload qm1"><a href="javascript:void(0);"><div class="pic-thumb"><input type="hidden" name="body_img['+(bi_num-1)+']" value="'+result.datas.file_name+'" /><img src="' + result.datas.pic + '"/></div></a></div>');
            e.parent().siblings(".upload-loading").remove();
            
            if(bi_num>9){
            	$("#gi_table").addClass("hide");
            	$.sDialog({
                    skin: "red",
                    content: "商品详情图的数量不要超过10张！",
                    okBtn: false,
                    cancelBtn: false
                });
            }
    		$("#bi_num").attr('value',bi_num);
        },
        error:function(XMLHttpRequest, textStatus, errorThrown){  
            alert('上传失败！');  
        }  
    });
	
	$.sValid.init({
        rules: {
        	g_name: "required"
        },
        messages: {
        	g_name: "商品名称必填！"
        },
        callback: function(a, e, r) {
            if (a.length > 0) {
                var i = "";
                $.map(e, 
                function(a, e) {
                    i += "<p>" + a + "</p>";
                });
                errorTipsShow(i);
            } else {
                errorTipsHide();
            }
        }
    });
    $("#feedbackbtn,#header-nav").click(function() {
        if ($.sValid()) {
            var g_image = $("#goods_image_0").val();
            var is_commend = $("#is_commend").attr("checked") ? 1: 0;
            var gc_id = $("#gcategory").attr("data-gcid");
            var gc_id_1 = $("#gcategory").attr("data-gcid1");
            var gc_id_2 = $("#gcategory").attr("data-gcid2");
            var gc_name = $("#gcategory").attr("data-gcname");
            $("#gc_id").attr("value",gc_id);
            $("#gc_name").attr("value",gc_name);
            $("#g_commend").attr("value",is_commend);
            
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=seller_goods&op=goods_add",
                data: $('#agoodsFrom').serialize(),
                dataType: "json",
                success: function(result) {
                	console.log(result);
                	if (!CheckDataSucceed(result)){return false;}
                	var data = result.datas;
                	if(data.state){
                		alert(data.msg);
                		location.href = WapSiteUrl+'/tmpl/seller/seller.html';
                		return true;
                	}else{
                		alert(data.msg);
                		location.href = WapSiteUrl+'/tmpl/seller/add_goods.html';
                		return true;
                	}
                },
				error: function(){
			        ShowGetDataError();
		        }
            })
        }
    });
    $("#gcategory").on("click", function() {
        $.cateSelected({
            success: function(a) {
            	//console.log(a);
                $("#gcategory").val(a.gc_info).attr({
                    "data-gcid": a.gc_id,
                    "data-gcid1": a.gc_id_1 == 0 ? a.gc_id: a.gc_id_1,
                    "data-gcid2": a.gc_id_2 == 0 ? a.gc_id_1: a.gc_id_2,
                    "data-gcname": a.gc_name
                })
            }
        })
    });
    
    // 默认是隐藏
    $("#spec_table").hide();
    $("#spec_add").click(function() {
    	$("#def_table").hide();
    	$("#spec_table").show();
    	var s_num = $("#s_num").val();
    	if(s_num > 0){
    		var html = '<ul class="spec-box mt5" id="spec_box_'+s_num+'"><li class="spec-item">';
        	html += '<h4>规&nbsp;&nbsp;格</h4><div class="input-box">';
        	html += '  <input type="text" id="spec_name['+s_num+']" name="spec['+s_num+'][spec_value]" maxlength="10" size="10" class="inp" autocomplete="off" placeholder="输入商品规格，如颜色、尺码" oninput="writeClear($(this));"/>';
        	html += '  <span class="input-del code"></span></div></li>';
        	html += '<li class="spec-item"><h4>价&nbsp;&nbsp;格</h4><div class="input-box">';
        	html += '  <input type="text" id="g_price['+s_num+']" name="spec['+s_num+'][price]" maxlength="10" size="10" class="inp" autocomplete="off" placeholder="" oninput="writeClear($(this));"/>';
        	html += '  <span class="input-del code"></span></div>';
        	html += '<div class="spec-img"><i class="sepc_del" id="sepc_del'+s_num+'"></i></div>';
        	html += '</li>';
        	html += '<li class="spec-item"><h4>库&nbsp;&nbsp;存</h4><div class="input-box">';
        	html += '  <input type="text" id="g_storage['+s_num+']" name="spec['+s_num+'][stock]" maxlength="8" size="10" class="inp" autocomplete="off" placeholder="" oninput="writeClear($(this));"/>';
        	html += '  <span class="input-del code"></span></div></li></ul>';
        	html += '  <script type="text/javascript">$(function(){';
        	html += '  	$("#sepc_del'+s_num+'").click(function() {';
        	html += '	var len = $("#s_num").val();';
        	html += '	$(this).closest(".spec-box").remove();';
        	html += '	var $sepc_len = $("#spec_table ul").size();	if($sepc_len == 0){$("#def_table").show();$("#spec_table").hide();}';
        	html += '});});</script>';
    	}
    	$("#s_num").attr('value',$("#s_num").val()*1+1);
    	var len = $("#spec_table ul").size();//获取span标签的个数
    	$("#spec_table").append(html);
        
    });
    
    //默认删除商品的属性，其他均写入对于的层级中
    $("#sepc_del0").click(function() {
    	$(this).closest(".spec-box").remove();
    	var $sepc_len = $("#spec_table ul").size();//获取商品属性还剩余多少
    	if($sepc_len == 0){
    		$("#def_table").show();
        	$("#spec_table").hide();
    	}
    });
    
});
