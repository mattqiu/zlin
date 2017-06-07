<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>平台客服</h3>
        <h5>商城对用户咨询类型设定与处理</h5>
      </div>
      <ul class="tab-base im-row">
        <li><a href="<?php echo urlAdminShop('mall_consult', 'index');?>">平台客服咨询列表</a></li>
        <li><a href="<?php echo urlAdminShop('mall_consult', 'type_list');?>">平台咨询类型</a></li>
        <li><a href="JavaScript:void(0);" class="current">在线客服</a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>在线客服将显示在右侧工具条的下方平台客服中。</li>
    </ul>
  </div>
  <form method="post" name="form_online" id="form_online">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit"><label class="validation">QQ客服</label></dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="mall_qq" value="<?php echo $output['consult_setting']['qq'];?>" />
          <span class="err"></span>
          <p class="notic">请输入qq号码</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label class="validation">淘宝旺旺客服</label></dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="mall_ww" value="<?php echo $output['consult_setting']['ww'];?>" />
          <span class="err"></span>
          <p class="notic">请输入淘宝旺旺帐号</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label class="validation">站内IM客服</label></dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="mall_im" value="<?php echo $output['consult_setting']['im'];?>" />
          <span class="err"></span>
          <p class="notic">请输入站内会员帐号</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $("#submitBtn").click(function(){
        $("#form_online").submit();
    });
});
</script>
