<?php defined('InIMall') or exit('Access Invalid!');?>
<script type="text/javascript">
var SHOP_SITE_URL = "<?php echo SHOP_SITE_URL; ?>";
var UPLOAD_SITE_URL = "<?php echo UPLOAD_SITE_URL; ?>";
var ATTACH_ADV = "<?php echo ATTACH_ADV; ?>";
var screen_adv_list = new Array();//焦点大图广告数据
var screen_adv_append = '';
var focus_adv_append = '';
<?php if(!empty($output['screen_adv_list']) && is_array($output['screen_adv_list'])){ ?>
<?php foreach ($output['screen_adv_list'] as $key => $val) { ?>
adv_info = new Array();
ap_id = "<?php echo $val['ap_id'];?>";
adv_info['ap_id'] = ap_id;
adv_info['ap_name'] = "<?php echo $val['ap_name'];?>";
adv_info['ap_img'] = "<?php echo $val['default_content'];?>";
screen_adv_list[ap_id] = adv_info;
screen_adv_append += '<option value="'+ap_id+'">'+adv_info['ap_name']+'</option>';
<?php } ?>
<?php } ?>
</script>
<style type="text/css">
.color {
	position: relative!important;
	z-index: 1!important;
	padding: 0!important;
}
.color .evo-pop {
	bottom: 26px!important;
}
.evo-colorind-ie {
	position: relative;
*top:0/*IE6,7*/ !important;
}
</style>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['im_cms_special_manage'];?></h3>
        <h5>商城专辑首页焦点图管理</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> 
    </div>
    <ul>
      <li>焦点大图区可设置背景颜色。</li>
      <li>所有相关设置完成，使用底部的“更新板块内容”前台展示页面才会变化。</li>
    </ul>
  </div>   
  <div class="homepage-focus" id="homepageFocusTab">
    <form id="upload_screen_form" class="tab-content" name="upload_screen_form" enctype="multipart/form-data" method="post" action="index.php?act=web_special&op=screen_pic" target="upload_pic">
      <input type="hidden" name="form_submit" value="ok" />
      <input type="hidden" name="web_id" value="<?php echo $output['code_screen_list']['web_id'];?>">
      <input type="hidden" name="code_id" value="<?php echo $output['code_screen_list']['code_id'];?>">
      <input type="hidden" name="key" value="">
      <div class="imap-form-default">
        <dl class="row">
          <dt class="tit"></dt>
          <dd class="opt">
            <div class="full-screen-slides">
              <ul>
                <?php if (is_array($output['code_screen_list']['code_info']) && !empty($output['code_screen_list']['code_info'])) { ?>
                <?php foreach ($output['code_screen_list']['code_info'] as $key => $val) { ?>
                <?php if (is_array($val) && $val['ap_id'] > 0) { ?>
                <li ap="1" screen_id="<?php echo $val['pic_id'];?>" title="可上下拖拽更改显示顺序">
                <div class="title"><h4>广告调用</h4><a class="imap-btn-mini del" href="JavaScript:del_screen(<?php echo $val['pic_id'];?>);" title="<?php echo $lang['im_del'];?>"><i class="fa fa-trash"></i>删除</a></div>
                  <div class="focus-thumb" onclick="select_screen(<?php echo $val['pic_id'];?>);" style="background-color:<?php echo $val['color'];?>;" title="点击编辑选中区域内容"> <img title="<?php echo $val['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>"/></div>
                  <input name="screen_list[<?php echo $val['pic_id'];?>][pic_id]" value="<?php echo $val['pic_id'];?>" type="hidden">
                  <input name="screen_list[<?php echo $val['pic_id'];?>][ap_id]" value="<?php echo $val['ap_id'];?>" type="hidden">
                  <input name="screen_list[<?php echo $val['pic_id'];?>][pic_name]" value="<?php echo $val['pic_name'];?>" type="hidden">
                  <input name="screen_list[<?php echo $val['pic_id'];?>][color]" value="<?php echo $val['color'];?>" type="hidden">
                  <input name="screen_list[<?php echo $val['pic_id'];?>][pic_img]" value="<?php echo $val['pic_img'];?>" type="hidden">
                </li>
                <?php }else { ?>
                <li ap="0" screen_id="<?php echo $val['pic_id'];?>" title="可上下拖拽更改显示顺序"><div class="title"><h4>图片调用</h4><a class="imap-btn-mini del" href="JavaScript:del_screen(<?php echo $val['pic_id'];?>);"><i class="fa fa-trash"></i>删除</a></div>
                  <div class="focus-thumb" onclick="select_screen(<?php echo $val['pic_id'];?>);" style="background-color:<?php echo $val['color'];?>;" title="点击编辑选中区域内容"> <img title="<?php echo $val['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>"/></div>
                  <input name="screen_list[<?php echo $val['pic_id'];?>][pic_id]" value="<?php echo $val['pic_id'];?>" type="hidden">
                  <input name="screen_list[<?php echo $val['pic_id'];?>][pic_name]" value="<?php echo $val['pic_name'];?>" type="hidden">
                  <input name="screen_list[<?php echo $val['pic_id'];?>][pic_url]" value="<?php echo $val['pic_url'];?>" type="hidden">
                  <input name="screen_list[<?php echo $val['pic_id'];?>][color]" value="<?php echo $val['color'];?>" type="hidden">
                  <input name="screen_list[<?php echo $val['pic_id'];?>][pic_img]" value="<?php echo $val['pic_img'];?>" type="hidden">
                </li>
                <?php } ?>
                <?php } ?>
                <?php } ?>
              </ul>
            </div>
            <p class="notic"><?php echo '小提示：单击图片选中修改，拖动可以排序，添加最多不超过5个，保存后生效。';?></p>
            <div class="mt20"><a class="imap-btn" href="JavaScript:add_screen('pic');"><?php echo '图片调用';?></a>
              <?php if(!empty($output['screen_adv_list']) && is_array($output['screen_adv_list'])){ ?>
              <a class="imap-btn" href="JavaScript:add_screen('adv');"><?php echo '广告调用';?></a>
              <?php } ?>
            </div>
          </dd>
        </dl>
      </div>
      <div id="ap_screen" class="imap-form-default" style="display:none; overflow: visible;">
        <div class="title">
          <h3>新增/选中区域内容设置详情</h3>
        </div>
        <dl class="row">
          <dt class="tit"><?php echo '广告位';?></dt>
          <dd class="opt">
            <input type="hidden" name="ap_pic_id" value="">
            <select id="ap_id_screen" name="ap_id_screen" class=" w200" onchange="select_ap_screen();">
            </select>
            <span class="err"></span>
            <p class="notic">调用的数据是宽度为1920像素，高度为481像素的图片类广告位。</p>
          </dd>
        </dl>
        <dl class="row" style="z-index: 3;">
          <dt class="tit">
            <label><?php echo '背景颜色';?></label>
          </dt>
          <dd class="opt">
            <input id="ap_color" name="ap_color" value="" class="" type="text">
            <span class="err"></span>
            <p class="notic">为确保显示效果美观，可设置首页全屏焦点图区域整体背景填充色用于弥补图片在不同分辨率下显示区域超出图片时的问题，可根据您焦点图片的基础底色作为参照进行颜色设置。</p>
          </dd>
        </dl>
      </div>
      <div id="upload_screen" class="imap-form-default" style="display:none; overflow: visible;">
        <div class="title">
          <h3>新增/选中区域内容设置详情</h3>
        </div>
        <dl class="row">
          <dt class="tit"><?php echo '文字标题';?></dt>
          <dd class="opt">
            <input type="hidden" name="screen_id" value="">
            <input class="txt" type="text" name="screen_pic[pic_name]" value="">
            <span class="err"></span>
            <p class="notic">图片标题文字将作为图片Alt形式显示。</p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit">
            <label><?php echo $lang['web_config_upload_url'];?></label>
          </dt>
          <dd class="opt">
            <input name="screen_pic[pic_url]" value="" class="input-txt" type="text">
            <span class="err"></span>
            <p class="vatop tips">输入图片要跳转的URL地址，正确格式应以"http://"开头，点击后将以"_blank"形式另打开页面。</p>
          </dd>
        </dl>
        <dl class="row">
          <dt class="tit"><?php echo $lang['web_config_upload_adv_pic'];?></dt>
          <dd class="opt">
            <div class="input-file-show"><span class="type-file-box">
              <input type='text' name='textfield' id='textfield1' class='type-file-text' />
              <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
              <input name="pic" id="pic" type="file" class="type-file-file" size="30">
              </span></div>
            <p class="notic">为确保显示效果正确，请选择最小不低于W:776px H:300px、最大不超过W:1920px H:481px的清晰图片作为全屏焦点图。</p>
          </dd>
        </dl>
        <dl class="row" style="z-index: 3;">
          <dt class="tit">
            <label><?php echo '背景颜色';?></label>
          </dt>
          <dd class="opt">
            <input id="screen_color" name="screen_pic[color]" value="" class="" type="text">
            <span class="err"></span>
            <p class="notic">为确保显示效果美观，可设置首页全屏焦点图区域整体背景填充色用于弥补图片在不同分辨率下显示区域超出图片时的问题，可根据您焦点图片的基础底色作为参照进行颜色设置。</p>
          </dd>
        </dl>
      </div>
      <div class="imap-form-default">
        <div class="bot">
          <a href="JavaScript:void(0);" onclick="$('#upload_screen_form').submit();" class="imap-btn-big imap-btn-green"><?php echo $lang['web_config_save'];?></a> 
          <a href="index.php?act=web_special&op=html_update&web_id=<?php echo $output['code_screen_list']['web_id'];?>" class="imap-btn-big imap-btn-green"><?php echo $lang['web_config_web_html'];?></a> 
          <span class="web-save-succ" style="display:none;"><?php echo $lang['im_common_save_succ'];?></span> 
        </div>
      </div>
    </form>
  </div>
</div>
<iframe style="display:none;" src="" name="upload_pic"></iframe>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/colorpicker/evol.colorpicker.css" rel="stylesheet" type="text/css">
<script src="<?php echo RESOURCE_SITE_URL;?>/js/colorpicker/evol.colorpicker.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script src="<?php echo ADMIN_RESOURCE_URL?>/js/web_focus.js"></script>