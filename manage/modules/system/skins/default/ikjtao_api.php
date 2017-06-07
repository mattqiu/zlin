<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
            <h3>跨境淘接口</h3>
            <h5>跨境淘开放平台账号设置</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>成功设置后商城可以通过接口将数据推送给跨境淘，比如跨境淘订单等</li>
    </ul>
  </div>
  <form id="add_form" method="post" action="index.php?act=ikjtao_api&op=ikjtao_api_save">
    <div class="imap-form-default">
      <!-- 跨境淘接口开关 -->
      <dl class="row">
        <dt class="tit">
          <label>跨境淘接口开关</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="ikjtao_isuse_1" class="cb-enable <?php if($output['setting']['ikjtao_api_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['im_open'];?>"><?php echo $lang['im_open'];?></label>
            <label for="ikjtao_isuse_0" class="cb-disable <?php if($output['setting']['ikjtao_api_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['im_close'];?>"><?php echo $lang['im_close'];?></label>
            <input type="radio" id="ikjtao_isuse_1" name="ikjtao_api_isuse" value="1" <?php echo $output['setting']['ikjtao_api_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="ikjtao_isuse_0" name="ikjtao_api_isuse" value="0" <?php echo $output['setting']['ikjtao_api_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ikjtao_app_key">跨境淘应用标识(APP KEY)</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['ikjtao_app_key'];?>" name="ikjtao_app_key" class="input-txt">
          <span class="err"></span>
          <p class="notic"><a class="imap-btn" target="_blank" href="http://erp.ikjtao.com">立即在线申请</a></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ikjtao_secret_key">跨境淘应用密钥(APP SECRET)</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['ikjtao_secret_key'];?>" name="ikjtao_secret_key" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ikjtao_secret_key">跨境淘平台店铺ID</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['ikjtao_store_id'];?>" name="ikjtao_store_id" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ikjtao_customs">海关</label>
        </dt>
        <dd class="opt">
        	<select name="ikjtao_customs">
        		<option value="0" <?php if($output['setting']['ikjtao_customs']==0 || empty($output['setting']['ikjtao_customs'])){echo "selected";}?> >无上报海关</option>
        		<option value="1" <?php if($output['setting']['ikjtao_customs']==1){echo "selected";}?>>广州</option>
        		<option value="2" <?php if($output['setting']['ikjtao_customs']==2){echo "selected";}?>>杭州</option>
        		<option value="3" <?php if($output['setting']['ikjtao_customs']==3){echo "selected";}?>>宁波</option>
        		<option value="4" <?php if($output['setting']['ikjtao_customs']==4){echo "selected";}?>>深圳</option>
        		<option value="5" <?php if($output['setting']['ikjtao_customs']==5){echo "selected";}?>>郑州保税物流中心</option>
        		<option value="6" <?php if($output['setting']['ikjtao_customs']==6){echo "selected";}?>>重庆</option>
        		<option value="7" <?php if($output['setting']['ikjtao_customs']==7){echo "selected";}?>>西安</option>
        		<option value="8" <?php if($output['setting']['ikjtao_customs']==8){echo "selected";}?>>上海</option>
        		<option value="9" <?php if($output['setting']['ikjtao_customs']==9){echo "selected";}?>>郑州综保区</option>
        		<option value="10" <?php if($output['setting']['ikjtao_customs']==10){echo "selected";}?>>郑州综保区</option>
        		<option value="11" <?php if($output['setting']['ikjtao_customs']==11){echo "selected";}?>>广州电子口岸（总署版）</option>
        		<option value="12" <?php if($output['setting']['ikjtao_customs']==12){echo "selected";}?>>郑州综保区（总署版）</option>
        		<option value="13" <?php if($output['setting']['ikjtao_customs']==13){echo "selected";}?>>天津</option>
        	</select>
        	<span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ikjtao_customs_no">备案号</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['ikjtao_customs_no'];?>" name="ikjtao_customs_no" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="ikjtao_customs_name">备案名称</label>
        </dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['setting']['ikjtao_customs_name'];?>" name="ikjtao_customs_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a id="submit" href="javascript:void(0)" class="imap-btn-big imap-btn-green"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>

<script type="text/javascript">
$(document).ready(function(){
    $("#submit").click(function(){
        $("#add_form").submit();
    });
});
</script> 
