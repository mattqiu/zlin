
$(function(){

    /* 广告图片ajax上传 */
    var url = SITEURL + '/index.php?act=store_setting&op=silde_image_upload';
	var saveurl = SITEURL + '/index.php?act=store_setting&op=store_explain_save';
    $('.imsc-upload-btn').find('input[type="file"]').unbind().change(	    
        function() {
            var id = $(this).attr('id');
            var file_id = $(this).attr('file_id');
            ajaxFileUpload(url, id, file_id);
        });

    /* 删除图片 */
    $('a[imtype="del"]').unbind().click(	
        function() {
			var file_id = $('#file_pic').attr('file_id');				
			var id = $('#file_pic').attr('id');	
				
            $.getJSON('index.php?act=store_setting&op=dorp_img', {file_id : file_id}, function(data) {
                $('#explain_pic').attr('src', '');
            });
			$.getScript(SHOP_RESOURCE_SITE_URL + "/js/store_explain.js");           
        });
		
	//广告保存
    $('#btn_save_adv').on('click', function() {		
        var explain_info = {};
		
		explain_info.pic = $('#explain_pic').attr("src");
		explain_info.url1 = $("#exp_url_1").val();
		explain_info.url2 = $("#exp_url_2").val();
		explain_info.url3 = $("#exp_url_3").val();
		explain_info.url4 = $("#exp_url_4").val();

        save_adv(
            saveurl,
            explain_info,
            function(data) {
                showSucc(data.message);
            }
        );
    });
});

function save_adv(url, exp_info, done, always) {
	var post = {data: exp_info};
	
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
	$('#explain_pic').attr('src',SHOP_SKINS_URL+"/images/loading.gif");

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
					$('#explain_pic').attr('src','');
				}else
				{
					$('#explain_pic').attr('src',UPLOAD_SITE_URL+'/'+ATTACH_STORE+'/slide/'+data.file_name);
					$('#file_pic').attr('file_id',data.file_id);
				}
				$.getScript(SHOP_RESOURCE_SITE_URL + "/js/store_explain.js");   
			},
			error: function (data, status, e)
			{
				alert(e);
				$.getScript(SHOP_RESOURCE_SITE_URL + "/js/store_explain.js");   
			}
		}
	)
	return false;
}
