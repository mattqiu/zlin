<?php defined('InIMall') or exit('Access Invalid!');?>
<link href="<?php echo MOBILE_SKINS_URL;?>/css/promotion.css" rel="stylesheet" type="text/css">

<style>
    .header_title{width:90%;}
    .tabmenu ul li{width:50%;text-align:center;}
    .ncsc-goodspic-list .goods-pic-list li .upload-thumb{
        position: relative;
    }
    .ncsc-goodspic-list ul li .show-default span{
        display: none;;

    }
    .ncsc-goodspic-list ul li .show-default.selected span{
        display: block;
        color:#28B779;
        padding:5px;
        text-align: center;
    }
    label{margin:10px auto;}
    .ncsc-upload-btn{top:55px!important;text-align: center;right:0px!important;left: auto!important;width:40px!important;height:40px!important;border-radius: 100%;box-sizing: border-box;background: rgba(39,169,227,0.6);z-index: 25;text-align: center;}
    .ncsc-upload-btn img{width:20px;;height:20px;}
</style>
<div class="warp">
    <div class="header navbar-fixed-top">
        <div class="return fl">
            <a href="javascript:window.history.go(-1);"><img width="15" height="25" src="<?php echo MOBILE_SKINS_URL;?>/css/images/return_img.jpg"/></a>
        </div>
        <div class="header_title hh">编辑图片</div>
        <div class="clear"></div>
    </div>
    <?php if ($output['edit_goods_sign']) {?>
        <div class="tabmenu">
            <?php include template('layout/submenu');?>
        </div>
    <?php }?>
<form method="post" style="background: #FFF;" id="goods_image" action="<?php if ($output['edit_goods_sign']) { echo urlMobile('store_goods_online', 'edit_save_image'); } else { echo urlMobile('store_goods_add', 'save_image');}?>">
  <input type="hidden" name="form_submit" value="ok">
  <input type="hidden" name="commonid" value="<?php echo $output['commonid'];?>">
  <input type="hidden" name="ref_url" value="<?php echo $_GET['ref_url'];?>" />
  <?php if (!empty($output['value_array'])) {?>
  <div class="ncsc-form-goods-pic">
    <div class="container">
      <?php foreach ($output['value_array'] as $value) {?>
      <div class="ncsc-goodspic-list">
        <ul class="goods-pic-list" nctype="ul<?php echo $value['sp_value_id'];?>">
          <?php for ($i = 0; $i < 5; $i++) {?>
          <li class="ncsc-goodspic-upload">
            <div class="upload-thumb"><img src="<?php echo cthumb($output['img'][$value['sp_value_id']][$i]['goods_image'], 240);?>" nctype="file_<?php echo $value['sp_value_id'] . $i;?>">
              <input type="hidden" name="img[<?php echo $value['sp_value_id'];?>][<?php echo $i;?>][name]" value="<?php echo $output['img'][$value['sp_value_id']][$i]['goods_image'];?>" nctype="file_<?php echo $value['sp_value_id'] . $i;?>">
            </div>
            <div class="show-default<?php if ($output['img'][$value['sp_value_id']][$i]['is_default'] == 1) {echo ' selected';}?>" nctype="file_<?php echo $value['sp_value_id'] . $i;?>">
              <span><i class="icon-ok-circle"></i>默认主图
                <input type="hidden" name="img[<?php echo $value['sp_value_id'];?>][<?php echo $i;?>][default]" value="<?php if ( $output['img'][$value['sp_value_id']][$i]['is_default'] == 1) {echo '1';}else{echo '0';}?>">
              </span><a href="javascript:void(0)" nctype="del" class="del" title="移除">X</a>
            </div>
            <div class="show-sort" style="display: none;">排序：<input name="img[<?php echo $value['sp_value_id'];?>][<?php echo $i;?>][sort]" type="text" class="text" value="<?php echo intval($output['img'][$value['sp_value_id']][$i]['goods_image_sort']);?>" size="1" maxlength="1">
            </div>
            <div class="ncsc-upload-btn">
                <a href="javascript:void(0);">
                    <span>
                        <input type="file" hidefocus="true" size="1" class="input-file" name="file_<?php echo $value['sp_value_id'] . $i;?>" id="file_<?php echo $value['sp_value_id'] . $i;?>">
                    </span>
                    <label><img src="<?php echo MOBILE_SKINS_URL; ?>/images/paizhao.png"/></label>
              </a>
            </div>
            
          </li>

          <?php }?>
            <b class="clear"></b>
        </ul>
        <div class="ncsc-select-album" style="display: none;">
          <a class="ncbtn" href="index.php?act=store_album&op=pic_list&item=goods_image&color_id=<?php echo $value['sp_value_id'];?>" nctype="select-<?php echo $value['sp_value_id'];?>"><i class="icon-picture"></i>从图片空间选择</a>
          <a href="javascript:void(0);" nctype="close_album" class="ncbtn ml5" style="display: none;"><i class=" icon-circle-arrow-up"></i>关闭相册</a>
        </div>
        <div nctype="album-<?php echo $value['sp_value_id'];?>"></div>
      </div>
      <?php }?>
    </div>
    <div class="sidebar"><div class="alert-notic alert-info alert-block" id="uploadHelp">
    <div class="faq-img"></div>
    <h4>上传要求：</h4><ul>
    <li>1. 单张大小不超过<?php echo intval(C('image_max_filesize'))/1024;?>M的正方形图片</li>
    <li>2. 图片最大尺寸将被保留为1280像素</li>
    <li>3. 图片要清晰,不能虚化,保证亮度充足</li>
    <li>4. 操作后请点提交,否则无法在网站生效</li>
    <li>5. 一共五张图可左右滚动查看</li>
    </ul><h4>建议:</h4><ul><li>1. 主图为白色背景正面图。</li><li>2. 依次为正面图>背面图>侧面图>细节图</li></ul></div></div>
  </div>
  <?php }?>
  <div class="bottom tc hr32" style="text-align: center;"><label class="submit-border"><input type="submit" class="submit" value="<?php if ($output['edit_goods_sign']) { echo '提交'; } else { ?><?php echo $lang['store_goods_add_next'];?>，确认商品发布<?php }?>" /></label></div>
</form>
    </div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ajaxfileupload/ajaxfileupload.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo WADMIN_RESOURCE_SITE_URL;?>/js/store_goods_add.step3.js" charset="utf-8"></script>
<script>
var SITEURL = "<?php echo WADMIN_SITE_URL; ?>";
var DEFAULT_GOODS_IMAGE = "<?php echo UPLOAD_SITE_URL.DS.defaultGoodsImage(240);?>";
var SHOP_RESOURCE_SITE_URL = "<?php echo SHOP_RESOURCE_SITE_URL;?>";
$(function(){
    <?php if ($output['edit_goods_sign']) {?>
    $('input[type="submit"]').click(function(){
        ajaxpost('goods_image', '', '', 'onerror');
    });
    <?php }?>
    /* ajax打开图片空间 */
    <?php foreach ($output['value_array'] as $value) {?>
    $('a[nctype="select-<?php echo $value['sp_value_id'];?>"]').ajaxContent({
        event:'click', //mouseover
        loaderType:"img",
        loadingMsg:SHOP_TEMPLATES_URL+"/images/loading.gif",
        target:'div[nctype="album-<?php echo $value['sp_value_id'];?>"]'
    }).click(function(){
        $(this).hide();
        $(this).next().show();
    });
    <?php }?>
});
</script> 
