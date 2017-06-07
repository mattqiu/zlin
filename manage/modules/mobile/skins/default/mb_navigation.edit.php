<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <a class="back" href="index.php?act=mb_navigation&op=navigation_list" title="返回导航列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>分类导航 - 添加</h3>
        <h5>手机客户端分类导航图标/图片设置</h5>
      </div>      
    </div>
  </div>
  <form method="post" action="<?php echo urlAdminMobile('mb_navigation','navigation_save');?>" name="mb_navigation_form" id="mb_navigation_form" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="mn_id" value="<?php echo $output['mn_info']['mn_id'];?>"/>
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit">导航名称</dt>
        <dd class="opt">
          <input type="text" class="w150 text" name="mn_title" value="<?php echo $output['mn_info']['mn_title'];?>" /><span></span>
          <span class="err"></span>
          <p class="notic">建议四个字，不超过6个字</p>
        </dd>
      </dl>   
      <dl class="row"> 
        <dt class="tit">导航图标</dt>
        <dd class="opt">
          <div class="input-file-show">
            <span class="show">
              <a class="nyroModal" rel="gal" href="<?php echo empty($output['mn_info']['mn_thumb'])?UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.DS.'category' .DS.'default.png':UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.DS.'category' .DS.$output['mn_info']['mn_thumb'];?>"> 
                <i class="fa fa-picture-o" onMouseOver="toolTip('<img src=<?php echo empty($output['mn_info']['mn_thumb'])?UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.DS.'category' .DS.'default.png':UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.DS.'category' .DS.$output['mn_info']['mn_thumb'];?>>')" onMouseOut="toolTip()"/></i> 
              </a>
            </span>
            <span class="type-file-box">
              <input type="text" name="textfield" id="textfield1" class="type-file-text" />
              <input type="button" name="button" id="button1" value="选择上传..." class="type-file-button" />
              <input class="type-file-file" id="mn_thumb" name="mn_thumb" type="file" size="30" hidefocus="true" im_type="mn_thumb" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            </span>
          </div>
          <span class="err"></span>
          <p class="notic">建议使用宽72像素*高72像素内的方型图片</p>
        </dd>
      </dl>      
      <dl class="row"> 
        <dt class="tit">内容</dt>
        <dd class="opt">
          <?php showEditor('mn_content',$output['mn_info']['mn_content'],'600px','300px','','true',$output['editor_multimedia']); ?>
          <span class="err"></span>
          <p class="notic">如果没有添加链接，则点击此导航将会显示此内容</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">链接URL</dt>
        <dd class="opt">
          <input type="text" class="w300 text" id="mn_url" name="mn_url" value="<?php echo $output['mn_info']['mn_url'];?>" />
          <a href="javascript:void(0)" class="imap-btn imap-btn-green" onclick="ajax_form('my_quick_link_select','选择快捷链接','<?php echo urlAdminMobile('mb_navigation','quick_link',array('inputctrl'=>'mn_url','mn_id'=>$output['mn_info']['mn_id']));?>',480);"><i class="fa fa-link"></i>快捷链接</a>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">是否显示</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="mn_if_show_enable" class="cb-enable <?php if(empty($output['mn_info']['mn_if_show']) || $output['mn_info']['mn_if_show'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['im_yes'];?>"><?php echo $lang['im_yes'];?></label>
            <label for="mn_if_show_disabled" class="cb-disable <?php if($output['mn_info']['mn_if_show'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['im_no'];?>"><?php echo $lang['im_no'];?></label>
            <input id="mn_if_show_enable" name="mn_if_show" <?php if(empty($output['mn_info']['mn_if_show']) || $output['mn_info']['mn_if_show'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="mn_if_show_disabled" name="mn_if_show" <?php if($output['mn_info']['mn_if_show'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row"> 
        <dt class="tit">排序</dt>
        <dd class="opt">
          <input type="text" class="w50 text" name="mn_sort" value="<?php if($output['mn_info']['mn_sort'] != ''){ echo $output['mn_info']['mn_sort'];}else{echo '255';}?>"/>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>        
      </dl>
      <div class="bot">
        <a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a>
      </div>
    </div>
  </form>
</div>

<script type="text/javascript">
function call_back(picname){
	$('#mn_thumb').val(picname);
	$('img[im_type="mn_thumb"]').attr('src','<?php echo UPLOAD_SITE_URL.DS.ATTACH_MOBILE.DS.'category';?>/'+picname); 
	$('#_pic').val('');
}

$(document).ready(function(){
	$("#submitBtn").click(function(){
        if($("#mb_navigation_form").valid()){
            $("#mb_navigation_form").submit();
	    }
	});
	
	//页面输入内容验证
	$('#mb_navigation_form').validate({
	        errorPlacement: function(error, element){
	            var error_td = element.parents('dl').find('span.err');
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
                required: '<i class="fa fa-exclamation-circle"></i>导航名称不能为空',
                maxlength: '<i class="fa fa-exclamation-circle"></i>不能超过10个字'
            }
        }
    });
});
</script> 
