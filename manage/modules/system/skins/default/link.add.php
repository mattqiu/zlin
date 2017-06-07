<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=link&op=link" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>新增合作伙伴</h3>
        <h5>添加新的合作伙伴或友情链接</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>标识“*”的选项为必填项，其余为选填项。</li>
    </ul>
  </div>
  <form id="link_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit"><label class="validation" for="link_title">连接名称:</label></dt>
        <dd class="opt">
          <input type="text" value="" name="link_title" id="link_title" class="txt">
          <span class="err"></span>
          <p class="notic">合作伙伴的名称。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label class="validation" for="link_url">连接地址:</label></dt>
        <dd class="opt">
          <input type="text" value="http://" name="link_url" id="link_url" class="txt">
          <span class="err"></span>
          <p class="notic">合作伙伴的链接地址</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label for="link_pic">连接图片:</label></dt>
        <dd class="opt">
          <div class="input-file-show">
            <span class="type-file-box">
              <input class="type-file-file" id="link_pic" name="link_pic" type="file" size="30" hidefocus="true" im_type="change_web_files">
            </span>
          </div>
          <span class="err"></span>
          <p class="notic">合作伙伴的标志图片</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label for="link_sort">排序:</label></dt>
        <dd class="opt">
          <input type="text" value="255" name="link_sort" id="link_sort" class="txt">
          <span class="err"></span>
          <p class="notic">数字越小越靠前</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
	// 模拟默认用户图片上传input type='file'样式
	var textButton="<input type='text' name='textfield' id='textfield4' class='type-file-text' /><input type='button' name='button' id='button4' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#web_files");
    $("#web_files").change(function(){
	    $("#textfield4").val($("#web_files").val());
    });
	
	$("#submit").click(function(){
        $("#statics_form").submit();
    });
	
	$("#submitBtn").click(function(){
        if($("#link_form").valid()){
            $("#link_form").submit();
	    }
	});
});
//
$(document).ready(function(){
	$('#link_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        success: function(label){
            label.addClass('valid');
        },
        rules : {
            link_title : {
                required : true
            },
            link_url  : {
                required : true,
                url      : true
            },
            link_sort : {
                number   : true
            }
        },
        messages : {
            link_title : {
                required : '<?php echo $lang['link_add_title_null'];?>'
            },
            link_url  : {
                required : '<?php echo $lang['link_add_url_null'];?>',
                url      : '<?php echo $lang['link_add_url_wrong'];?>'
            },
            link_sort  : {
                number   : '<?php echo $lang['link_add_sort_int'];?>'
            }
        }
    });
});
</script> 
<script type="text/javascript">
$(function(){
    var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='' class='type-file-button' />"
	$(textButton).insertBefore("#link_pic");
	$("#link_pic").change(function(){
	$("#textfield1").val($("#link_pic").val());
});
});
</script>