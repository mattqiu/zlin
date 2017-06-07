<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="imsc-form-default"> 
  <div class="alert">
    <ul>
      <li>1. 最多可上传4张图片，支持jpg、jpeg、gif、png格式上传，店铺广告仅对部分店铺主题有效。</li>
      <li>2. 广告图片建议大小：235X242象素，说明图片建议大小：215X90象素。</li>
      <li>3. 跳转链接必须带有“http://”</li>
      <li>4. 操作完成以后，按“提交”按钮保存生效。</li>
    </ul>
  </div>
  <div class="right_adv">
    <div class="explain_ico">
      <img src="<?php echo $output['store_exp']['pic'];?>" border="0" id="explain_pic" imtype="explain_pic">
    </div>  
  </div>
  <!-- 图片上传部分 -->
  <ul class="right_adv">
    <li class="explain_ico">   
      <div class="url">
        <label>跳转URL1</label>
        <input type="text" class="text w150" name="exp_url_1" id="exp_url_1" value="<?php echo ($output['store_exp']['url1'] == '')?'http://':$output['store_exp']['url1'];?>"/>
      </div>
      <div class="url">
        <label>跳转URL2</label>
        <input type="text" class="text w150" name="exp_url_2" id="exp_url_2" value="<?php echo ($output['store_exp']['url2'] == '')?'http://':$output['store_exp']['url2'];?>"/>
      </div>
      <div class="url">
        <label>跳转URL3</label>
        <input type="text" class="text w150" name="exp_url_3" id="exp_url_3" value="<?php echo ($output['store_exp']['url3'] == '')?'http://':$output['store_exp']['url3'];?>"/>
      </div>
      <div class="url">
        <label>跳转URL4</label>
        <input type="text" class="text w150" name="exp_url_4" id="exp_url_4" value="<?php echo ($output['store_exp']['url4'] == '')?'http://':$output['store_exp']['url4'];?>"/>
      </div>
      <div class="imsc-upload-btn"> 
        <a href="javascript:void(0);">
        <span>
          <input type="file" hidefocus="true" size="1" class="input-file" name="file_pic" id="file_pic"/>
        </span>
        <p><i class="fa fa-upload"></i>广告图片</p>
        </a>
      </div>     
      <div class="imsc-upload-btn"> 
        <a class="submit" imtype="del" href="javascript:void(0);">清除</a>
      </div>
    </li>
  </ul>
  <div class="bottom"><label class="submit-border"><a id="btn_save_adv" class="submit" href="javascript:void(0);">保   存</a></label></div>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js" charset="utf-8"></script> 
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/store_explain.js" charset="utf-8"></script>
<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL;?>";
var SHOP_SKINS_URL = '<?php echo SHOP_SKINS_URL;?>';
var UPLOAD_SITE_URL = '<?php echo UPLOAD_SITE_URL;?>';
var ATTACH_COMMON = '<?php echo ATTACH_COMMON;?>';
var ATTACH_STORE = '<?php echo ATTACH_STORE;?>';
var SHOP_RESOURCE_SITE_URL = '<?php echo SHOP_RESOURCE_SITE_URL;?>';
</script> 
