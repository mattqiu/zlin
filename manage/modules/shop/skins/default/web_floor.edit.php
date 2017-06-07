<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=web_channel&op=floor_list" title="返回模块列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>频道管理 - 设置“<?php echo $output['web']['web_name'];?>”模块</h3>
        <h5>商城的频道及模块内容管理</h5>
      </div>
    </div>
  </div>
  <form id="web_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="channel_id" value="<?php echo $output['web']['web_id'];?>" />
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>模块名称</label>
        </dt>
        <dd class="opt">
          <input id="web_name" name="web_name" value="<?php echo $output['web']['web_name'];?>" class="input-txt" type="text" maxlength="20">
          <span class="err"></span>
          <p class="notic">只在后台模块列表中作为模块标识出现，在频道页不显示，最多20个字。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>模块类型</label>
        </dt>
        <dd class="opt"><?php echo $output['style_array'][$output['web']['web_page']];?><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['web_config_style_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="hidden" value="<?php echo $output['web']['style_name']?>" name="style_name" id="style_name">
          <ul class="home-templates-board-style">
            <li class="red"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_red'];?></li>
            <li class="pink"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_pink'];?></li>
            <li class="orange"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_orange'];?></li>
            <li class="green"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_green'];?></li>
            <li class="blue"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_blue'];?></li>
            <li class="purple"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_purple'];?></li>
            <li class="brown"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_brown'];?></li>
            <li class="azure"><em></em><i class="fa fa-check-circle"></i>墨绿</li>
            <li class="default"><em></em><i class="fa fa-check-circle"></i><?php echo $lang['web_config_style_default'];?></li>
          </ul>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['web_config_style_name_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="web_layout">版块布局:</label>
        </dt>
        <dd class="opt">
          <select id="web_layout" name="web_layout">
            <option <?php if(empty($output['web']['web_layout']) || $output['web']['web_layout'] == 'floor'){ ?>selected="selected"<?php } ?> value="floor">默认布局</option>
            <option <?php if($output['web']['web_layout'] == 'tmall'){ ?>selected="selected"<?php } ?> value="tmall">天猫布局(分类+广告)</option>
            <option <?php if($output['web']['web_layout'] == 'cat'){ ?>selected="selected"<?php } ?> value="cat">天猫布局(分类)</option>
            <option <?php if($output['web']['web_layout'] == 'adv'){ ?>selected="selected"<?php } ?> value="adv">天猫布局(广告)</option>
            <option <?php if($output['web']['web_layout'] == 'meituan'){ ?>selected="selected"<?php } ?> value="meituan">美团布局</option>
            <option <?php if($output['web']['web_layout'] == 'vip'){ ?>selected="selected"<?php } ?> value="vip">唯品会布局</option>
            <option <?php if($output['web']['web_layout'] == 'mogu'){ ?>selected="selected"<?php } ?> value="mogu">蘑菇街布局</option>
          </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>     
      <dl class="row">
        <dt class="tit">显示状态</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="show1" class="cb-enable <?php if($output['web']['web_show'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['im_yes'];?>"><?php echo $lang['im_yes'];?></label>
            <label for="show0" class="cb-disable <?php if($output['web']['web_show'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['im_no'];?>"><?php echo $lang['im_no'];?></label>
            <input id="show1" name="web_show" <?php if($output['web']['web_show'] == '1'){ ?>checked="checked"<?php } ?>  value="1" type="radio">
            <input id="show0" name="web_show" <?php if($output['web']['web_show'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
	$(".home-templates-board-style .<?php echo $output['web']['style_name']?>").addClass("selected");
	$("#submitBtn").click(function(){
        if($("#web_form").valid()){
            $("#web_form").submit();
		}
	});
	$(".home-templates-board-style li").click(function(){
        $(".home-templates-board-style li").removeClass("selected");
        $("#style_name").val($(this).attr("class"));
        $(this).addClass("selected");
	});
	$("#web_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            web_name : {
                required : true
            }
        },
        messages : {
            web_name : {
                required : "<i class='fa fa-exclamation-circle'></i>模块名称不能为空"
            }
        }
	});
});

</script>
