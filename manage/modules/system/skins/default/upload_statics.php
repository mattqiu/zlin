<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>静态网页上传</h3>
        <h5>管理员上传静态网页</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>出于安全考虑，系统默认不允许上传html和zip文件，如果确认要上传，请到上传参数那里添加html和zip类型。</li>
      <li>上传的文件统一存储到【statics】目录，文件名不变，如果遇到有相同的文件名，则会直接覆盖。</li>
      <li>如果要上传文件夹，请先将文件夹用winrar压缩成zip格式再上传，系统会自动解压到【statics】目录中，原有的文件夹结构及文件名不变。</li>
      <li>文件成功上传后的URL为：<?php echo UPLOAD_SITE_URL.'/statics/你的文件名';?>。</li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" name="statics_form" id="statics_form">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="web_files">html/zip文件</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show">
            <span class="type-file-box">
            <input class="type-file-file" id="web_files" name="web_files" type="file" size="30" hidefocus="true" im_type="change_web_files">
            </span></div>
          <p class="notic">单网页请上传html文件，多文件请上传zip文件。</p>
        </dd>
      </dl>
      <div class="bot"><a id="submit" href="JavaScript:void(0);" class="imap-btn-big imap-btn-green"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
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
});
</script> 