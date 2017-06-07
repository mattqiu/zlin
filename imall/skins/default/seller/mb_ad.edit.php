<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <form id="link_form" enctype="multipart/form-data" method="post" action="index.php?act=mb_ad&op=mb_ad_edit">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="link_id" value="<?php echo $output['link_array']['link_id'];?>" />
    <dl>
      <dt><i class="required">*</i>广告标题：</dt>
      <dd>
        <input type="text" value="<?php echo $output['link_array']['link_title'];?>" name="link_title" id="link_title" class="txt">
      </dd>
    </dl>    
    <dl>
      <dt>广告链接：</dt>
      <dd>
        <input type="text" name="link_keyword" id="link_keyword" value="<?php echo $output['link_array']['link_keyword'];?>" class="txt" >
      </dd>
    </dl>
    <dl>
      <dt>广告图片：</dt>
      <dd>
        <span class="type-file-box">
          <input name="link_pic" type="file" class="type-file-file" id="link_pic" size="30">
          <input type='text' name='textfield' id='textfield1' class='type-file-text' value="<?php echo $output['link_array']['link_pic'];?>" />
          <input type='button' name='button' id='button1' value='' class='type-file-button' />
        </span>
        <span class="type-file-show">
          <img class="show_image" src="<?php echo SHOP_SKINS_URL;?>/images/preview.png">
          <div class="type-file-preview"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_MOBILE.'/ad/'.$output['link_array']['link_pic'];?>"></div>
        </span>
      </dd>
    </dl>
    <dl>
      <dt>排序：</dt>
      <dd>
        <input type="text" value="<?php echo $output['link_array']['link_sort'];?>" name="link_sort" id="link_sort" class="txt">
      </dd>
    </dl>   
    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" value="提交" />
      </label>
    </div>
  </form>
</div>
<script>
$(function(){
    $('#link_form').validate({
		errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
    	submitHandler:function(form){
    		ajaxpost('link_form', '', '', 'onerror') 
    	},
        rules : {
            link_title : {
                required : true
            },   
            link_sort : {
                number   : true
            }
        },
        messages : {
            link_title : {
                required : '广告标题不能为空'
            },
            link_sort  : {
                number   : '序号不能为空'
            }
        }
    });
	
	$("#link_pic").change(function(){
	    $("#textfield1").val($("#link_pic").val());
	});
	// 显示隐藏预览图 start
	$('.show_image').hover(
		function(){
			$(this).next().css('display','block');
		},
		function(){
			$(this).next().css('display','none');
		}
	);
});
</script>