<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>二维码制作</h3>
        <h5>二维码在线生成工具</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>二维码在线生成工具，输入网址和logo生成一个二维码。</li>
      <li>二维码制作成功后，请及时用右键"另存为"将生成的二维码存储到其它目录。</li>
    </ul>
  </div>
  <form method="post" enctype="multipart/form-data" id="qrcode_form" name="qrcode_form" action="<?php echo ADMIN_SITE_URL;?>/modules/system/index.php?act=qrcode_build&op=qrcode_build_save">
    <div class="imap-form-default" style="width:600px; float:left;">
      <dl class="row">
        <dt class="tit">
          <label for="taobao_secret_key">网址或文件URL</label>
        </dt>
        <dd class="opt">
          <input type="text" value="http://" name="qrcode_url" class="input-txt">
          <span class="err"></span>
          <p class="notic">请输入以http://开头的完整网址。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="web_files">logo图片</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show">
            <span class="type-file-box">
            <input class="type-file-file" id="qrcode_logo" name="qrcode_logo" type="file" size="30" hidefocus="true">
            </span>
          </div>
          <span class="err"></span>
          <p class="notic">logo图片请使用60*60像素jpg/png格式的图片。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>二维码大小</label>
        </dt>
        <dd class="opt">
          <label><input type="radio" value="1" name="qrcode_size">25X25</label>
          <label><input type="radio" value="2" name="qrcode_size">66X66</label>
          <label><input type="radio" value="3" name="qrcode_size">132X132</label>
          <label><input type="radio" value="4" name="qrcode_size">165X165</label>
          <label><input type="radio" value="6" name="qrcode_size" checked="checked">198X198</label>
          <label><input type="radio" value="7" name="qrcode_size">231X231</label>
          <label><input type="radio" value="8" name="qrcode_size">264X264</label>
          <label><input type="radio" value="9" name="qrcode_size">297X297</label>
          <label><input type="radio" value="10" name="qrcode_size">330X330</label>
          <label><input type="radio" value="20" name="qrcode_size">500X500</label>
          <label><input type="radio" value="30" name="qrcode_size">750X750</label>
          <label><input type="radio" value="40" name="qrcode_size">1000X1000</label>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a id="submit" href="javascript:void(0)" class="imap-btn-big imap-btn-green"><?php echo $lang['im_submit'];?></a></div>
    </div>
    <div style="float:left; width:500px; height:500px; border:solid #CCC 1px; text-align:center;">
      <?php if (!empty($output['qrcode_url'])){?>
      <a>
      <img id="qrcode_src" style="max-height:500px; max-width:500px;" src="<?php echo $output['qrcode_url'];?>"/>
      </a>
      <?php }?>
    </div>
  </form>
</div>

<script type="text/javascript">
$(function(){
    // 模拟默认用户图片上传input type='file'样式
	var textButton="<input type='text' name='textfield' id='textfield4' class='type-file-text' /><input type='button' name='button' id='button4' value='选择上传...' class='type-file-button' />"
    $(textButton).insertBefore("#qrcode_logo");
    $("#qrcode_logo").change(function(){
	    $("#textfield4").val($("#qrcode_logo").val());
    });
	
    $("#submit").click(function(){
		ajaxpost('qrcode_form', '', '', 'onerror');
    });
});
</script> 
