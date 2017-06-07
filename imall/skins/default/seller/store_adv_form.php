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
    <?php for($i=0;$i<4;$i++){?>
    <div class="adv_pic">
      <div class="big_logo" id="banner_01"> 
        <a href="<?php echo $output['store_adv'][$i]['url'];?>" target="_blank">
          <img src="<?php echo $output['store_adv'][$i]['pic'];?>" border="0" id="adv_pic_<?php echo $i;?>" imtype="file_pic_<?php echo $i;?>">
        </a>
        <div class="big_txt"> 
          <a href="<?php echo $output['store_adv'][$i]['url'];?>" target="_blank">
            <img src="<?php echo $output['store_adv'][$i]['text'];?>" border="0" id="adv_text_<?php echo $i;?>" imtype="file_text_<?php echo $i;?>">
          </a> 
        </div>
      </div>
    </div>
    <?php } ?>    
  </div>
  <!-- 图片上传部分 -->
  <ul class="right_adv" id="store_adv_list">
    <?php for($i=0;$i<4;$i++){?>
    <li class="adv_pic">   
      <div class="url">
        <label><?php echo $lang['store_slide_image_url'];?></label>
        <input type="text" class="text w150" name="adv_url_<?php echo $i;?>" id="adv_url_<?php echo $i;?>" value="<?php echo ($output['store_adv'][$i]['url'] == '')?'http://':$output['store_adv'][$i]['url'];?>"/>
      </div>
      <div class="imsc-upload-btn"> 
        <a href="javascript:void(0);">
        <span>
        <input type="file" hidefocus="true" size="1" class="input-file" name="file_pic_<?php echo $i;?>" id="file_pic_<?php echo $i;?>"/>
        </span>
        <p><i class="fa fa-upload"></i>广告图片</p>
        </a>
      </div>
      
      <div class="imsc-upload-btn"> 
        <a href="javascript:void(0);">
        <span>
        <input type="file" hidefocus="true" size="1" class="input-file" name="file_text_<?php echo $i;?>" id="file_text_<?php echo $i;?>"/>
        </span>
        <p><i class="fa fa-upload"></i>说明图片</p>
        </a>
      </div>
      
      <div class="imsc-upload-btn"> 
        <a class="submit" imtype="del" href="javascript:void(0);">清除</a>
      </div>
    </li>
    <?php } ?>
  </ul>
  <div class="bottom"><label class="submit-border"><a id="btn_save_adv" class="submit" href="javascript:void(0);">保   存</a></label></div>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js" charset="utf-8"></script> 
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/store_adv.js" charset="utf-8"></script>
<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL;?>";
var SHOP_SKINS_URL = '<?php echo SHOP_SKINS_URL;?>';
var UPLOAD_SITE_URL = '<?php echo UPLOAD_SITE_URL;?>';
var ATTACH_COMMON = '<?php echo ATTACH_COMMON;?>';
var ATTACH_STORE = '<?php echo ATTACH_STORE;?>';
var SHOP_RESOURCE_SITE_URL = '<?php echo SHOP_RESOURCE_SITE_URL;?>';
</script> 
