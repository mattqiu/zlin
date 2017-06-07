<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="eject_con">
  <form id="perfor_form" method="post" target="_parent" action="<?php echo urlShop('seller_extension', 'perfor_save');?>">
    <?php if ($output['perfor_info']['ep_id']!=0) { ?>
    <input type="hidden" name="ep_id" value="<?php echo $output['perfor_info']['ep_id']; ?>" />
    <?php } ?>
    <dl>
      <dt><i class="required">*</i>奖励名称：</dt>
      <dd>
        <input class="text w200" type="text" name="award_name" id="award_name" value="<?php echo $output['perfor_info']['award_name']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>门店类型：</dt>
      <dd>
        <label><input type="radio" name="mc_id" value="2" <?php if (empty($output['perfor_info']['mc_id']) || $output['perfor_info']['mc_id']==2) echo 'checked="checked"'; ?> />经理</label>
        <label><input type="radio" name="mc_id" value="3" <?php if ($output['perfor_info']['mc_id']==3) echo 'checked="checked"'; ?> />协理</label>
        <label><input type="radio" name="mc_id" value="4" <?php if ($output['perfor_info']['mc_id']==4) echo 'checked="checked"'; ?> />首席</label>
        <label><input type="radio" name="mc_id" value="5" <?php if ($output['perfor_info']['mc_id']==5) echo 'checked="checked"'; ?> />股东</label>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>销售业绩：</dt>
      <dd>
        <input class="text w200" type="text" name="achieve_val" id="achieve_val" value="<?php echo $output['perfor_info']['achieve_val']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>奖励比率：</dt>
      <dd>
        <input class="text w200" type="text" name="award_commis" id="award_commis" value="<?php echo $output['perfor_info']['award_commis']; ?>" />%
      </dd>
    </dl>
    <dl style="display:none;">
      <dt><i class="required">*</i>奖励分享层数：</dt>
      <dd>
        <input class="text w200" type="text" name="award_level" id="award_level" value="<?php echo $output['perfor_info']['award_level']?$output['perfor_info']['award_level']:$output['promotion_level']; ?>" />
      </dd>
    </dl>

    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="保存" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#perfor_form').validate({
    	submitHandler:function(form){
    		ajaxpost('perfor_form', '', '', 'onerror') 
    	},
        rules : {
            award_name : {
                required : true
            },
			achieve_val  : {
				required : true,
                number   : true,
				min      : 0
            },
            award_commis : {
				required : true,
                number   : true,
				max      : 100,
				min      : 0
            },
			award_level  : {
				required : true,
                number   : true,
				max      : <?php echo $output['promotion_level'];?>,
				min      : 0
            }
        },
        messages : {
            award_name : {
                required : '名称不能为空'

            },
			achieve_val  : {
				required : '奖励条件不能为空',
                number   : '奖励条件必须是数字',
				min      : '奖励条件不能小于0',
            },
            award_commis  : {
				required : '比率不能为空',
                number   : '比率必须是数字',
				max      : '奖励比率不能大于100',
				min      : '奖励比率不能小于0',
            },
			award_level  : {
				required : '分享层数不能为空',
                number   : '分享层数必须是数字',
				max      : '分享层数不能大于<?php echo $output['promotion_level'];?>',
				min      : '分享层数不能小于0',
            }
        }
    });
});
</script> 
