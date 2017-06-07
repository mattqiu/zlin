<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="imsc-form-default">
  <form method="post" action="index.php?act=mb_navigation&op=navigation_save" target="_parent" name="mb_navigation_form" id="mb_navigation_form" enctype="multipart/form-data">
    <input type="hidden" name="mn_id" value="<?php echo $output['mn_info']['mn_id'];?>"/>
    <dl>
      <dt><i class="required">*</i><?php echo $lang['store_navigation_name'].$lang['im_colon'];?></dt>
      <dd>
        <input type="text" class="w150 text" name="mn_title" value="<?php echo $output['mn_info']['mn_title'];?>" /><span></span>
      </dd>
    </dl>
    
    <dl>
      <dt>导航图标： </dt>
      <dd>
        <div class="imsc-upload-thumb store-avatar">
          <p>
            <?php if(empty($output['mn_info']['mn_thumb'])){?>
            <img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.DS.'category' .DS.'default.png';?>" im_type="mn_thumb" />
            <?php }else{?>
            <img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.DS.'category' .DS.$output['mn_info']['mn_thumb'];?>" im_type="mn_thumb" />
            <?php }?>
          </p>
        </div>
        <div class="imsc-upload-btn"> 
          <a href="javascript:void(0);">
            <span>
              <input type="hidden" value="<?php echo $output['mn_info']['mn_thumb'];?>" name="mn_thumb" id="mn_thumb">
              <input name="_pic" type="file"  hidefocus="true" id="_pic" class="input-file"/>
            </span>
            <p><i class="fa fa-upload"></i>图片上传</p>
          </a> 
        </div>
        <p class="hint">建议使用宽72像素*高72像素内的方型图片；点击下方"提交"按钮后生效。</p>
      </dd>
    </dl>    
    
    <dl>
      <dt><?php echo $lang['store_navigation_display'].$lang['im_colon'];?></dt>
      <dd>
        <ul class="imsc-form-radio-list">
          <li>
            <label for="mn_if_show_0"><input type="radio" class="radio" name="mn_if_show" id="mn_if_show_0" value="1"<?php if($output['mn_info']['mn_if_show'] == '1' || $output['mn_info']['mn_if_show'] == ''){?> checked="checked"<?php }?>/>
            <?php echo $lang['store_payment_yes'];?></label></li>
          <li>
            <label for="mn_if_show_1"><input type="radio" class="radio" name="mn_if_show" id="mn_if_show_1" value="0"<?php if($output['mn_info']['mn_if_show'] == '0'){?> checked="checked"<?php }?>/>
            <?php echo $lang['store_payment_no'];?></label></li>
        </ul>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_goods_class_sort'].$lang['im_colon'];?></dt>
      <dd>
        <input type="text" class="w50 text" name="mn_sort" value="<?php if($output['mn_info']['mn_sort'] != ''){ echo $output['mn_info']['mn_sort'];}else{echo '255';}?>"/>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_navigation_content'].$lang['im_colon'];?></dt>
      <dd>
        <?php showEditor('mn_content',$output['mn_info']['mn_content'],'600px','300px','','true',$output['editor_multimedia']); ?>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_navigation_url'].$lang['im_colon']; ?></dt>
      <dd>
        <p>
          <input type="text" class="w300 text" id="mn_url" name="mn_url" value="<?php echo $output['mn_info']['mn_url'];?>" />
          <a href="javascript:void(0)" class="imsc-btn imsc-btn-acidblue" im_type="dialog" dialog_title="选择快捷链接" dialog_id="my_quick_link_select" dialog_width="480" uri="index.php?act=mb_navigation&op=quick_link&inputctrl=mn_url&mn_id=<?php echo $output['mn_info']['mn_id'];?>"><i class="fa fa-link"></i>快捷链接</a>
        </p>
        <p class="hint"><?php echo $lang['store_navigation_url_tip']; ?></p>
        </td>
    </dl>
    <div class="bottom">
      <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['store_goods_class_submit'];?>" /></label>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.Jcrop/jquery.Jcrop.min.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
function call_back(picname){
	$('#mn_thumb').val(picname);
	$('img[im_type="mn_thumb"]').attr('src','<?php echo UPLOAD_SITE_URL.DS.ATTACH_MOBILE.DS.'category';?>/'+picname); 
	$('#_pic').val('');
}
$(document).ready(function(){
	//裁剪图片后返回接收函    
	$('#_pic').change(uploadChange);
	function uploadChange(){
		var filepatd=$(this).val();
		var extStart=filepatd.lastIndexOf(".");
		var ext=filepatd.substring(extStart,filepatd.lengtd).toUpperCase();
		if(ext!=".PNG"&&ext!=".GIF"&&ext!=".JPG"&&ext!=".JPEG"){
			alert("file type error");
			$(this).attr('value','');
			return false;
		}
		if ($(this).val() == '') return false;
		ajaxFileUpload();
	}
	function ajaxFileUpload()
	{
		$.ajaxFileUpload
		(
			{
				url:'index.php?act=cut&op=pic_upload&form_submit=ok&uploadpath=<?php echo ATTACH_MOBILE.DS.'category';?>',    
				secureuri:false,
				fileElementId:'_pic',
				dataType: 'json',
				success: function (data, status)
				{
					if (data.status == 1){
						ajax_form('cutpic','<?php echo $lang['im_cut'];?>','index.php?act=cut&op=pic_cut&x=100&y=100&resize=0&url='+data.url,680);
					}else{
						alert(data.msg);$('#_pic').bind('change',uploadChange);
					}
				},
				error: function (data, status, e)
				{
					alert('上传失败');$('#_pic').bind('change',uploadChange);
				}
			}
		)
	};
    
	//页面输入内容验证
	$('#mb_navigation_form').validate({
	        errorPlacement: function(error, element){
	            var error_td = element.parent('dd').children('span');
	            error_td.append(error);
	        },
	     	submitHandler:function(form){
	    		ajaxpost('mb_navigation_form', '', '', 'onerror')
	    	},
        rules: {
            mn_title: {
                required: true,
                maxlength: 10
            }
        },
        messages: {
            mn_title: {
                required: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_navigation_name_null'];?>',
                maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_navigation_name_max'];?>'
            }
        }
    });
});
</script> 
