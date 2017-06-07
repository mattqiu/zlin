
$(function(){

    /* 广告图片ajax上传 */
    var url = SITEURL + '/index.php?act=store_setting&op=silde_image_upload';
	var saveurl = SITEURL + '/index.php?act=store_setting&op=store_adv_save';
    $('.imsc-upload-btn').find('input[type="file"]').unbind().change(	    
        function() {
            var id = $(this).attr('id');
            var file_id = $(this).attr('file_id');
            ajaxFileUpload(url, id, file_id);
        });

    /* 删除图片 */
    $('a[imtype="del"]').unbind().click(	
        function() {
            var obj = $(this).parents('li');
			obj.find('input[type="file"]').each(function() {
				var file_id = $(this).attr('file_id');				
				var id = $(this).attr('id');	
				
                $.getJSON('index.php?act=store_setting&op=dorp_img', {file_id : file_id}, function(data) {
                    $('img[imtype="'+id+'"]').attr('src', '');
                });
			}); 
			$.getScript(SHOP_RESOURCE_SITE_URL + "/js/store_adv.js");           
        });
		
	//广告保存
    $('#btn_save_adv').on('click', function() {		
        var advs_list = [];
		
		var i = 0;
		$("#store_adv_list li").each(function() {
			var advs = {};

			advs.pic = $("#adv_pic_"+i).attr("src");
			advs.text = $("#adv_text_"+i).attr("src");
			advs.url = $("#adv_url_"+i).val();
			advs_list[i] = advs;
			i++;
	    });		

        save_adv(
            saveurl,
            advs_list,
            function(data) {
                showSucc(data.message);
            }
        );
    });
});

function save_adv(url, advs_list, done, always) {
	var post = {data: advs_list};
	
    $.ajax({
        type: "POST",
        url: url,
        data: post, 
        dataType: "json"
    })
    .done(function(data) {
        if(typeof data.error == 'undefined') {
            done(data);
        } else {
            showError(data.error);
        }
    })
    .fail(function() {
        showError('操作失败');
    })
    .always(always);
}

/* 图片上传ajax */
function ajaxFileUpload(url, id, file_id)
{
	$('img[imtype="'+id+'"]').attr('src',SHOP_SKINS_URL+"/images/loading.gif");

	$.ajaxFileUpload
	(
		{
			url:url,
			secureuri:false,
			fileElementId:id,
			dataType: 'json',
			data:{name:'logan', id:id, file_id:file_id},
			success: function (data, status)
			{
				if(typeof(data.error) != 'undefined')
				{
					alert(data.error);
					$('img[imtype="'+id+'"]').attr('src','');
				}else
				{
					$('img[imtype="'+id+'"]').attr('src',UPLOAD_SITE_URL+'/'+ATTACH_STORE+'/slide/'+data.file_name);
					$('#'+id).attr('file_id',data.file_id);
				}
				$.getScript(SHOP_RESOURCE_SITE_URL + "/js/store_adv.js");   
			},
			error: function (data, status, e)
			{
				alert(e);
				$.getScript(SHOP_RESOURCE_SITE_URL + "/js/store_adv.js");   
			}
		}
	)
	return false;
}
