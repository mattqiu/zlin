<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="eject_con">
  <form id="manage_form" method="post" target="_parent" action="<?php echo urlShop('seller_extension', 'manage_save');?>">
    <?php if ($output['manage_info']['em_id']!=0) { ?>
    <input type="hidden" name="em_id" value="<?php echo $output['manage_info']['em_id']; ?>" />
    <?php } ?>
    <dl>
      <dt><i class="required">*</i>奖励名称：</dt>
      <dd>
        <input class="text w200" type="text" name="award_name" id="award_name" value="<?php echo $output['manage_info']['award_name']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>高管类型：</dt>
      <dd>
        <label><input type="radio" name="mc_id" value="2" <?php if (empty($output['manage_info']['mc_id']) || $output['manage_info']['mc_id']==2) echo 'checked="checked"'; ?> />经理</label>
        <label><input type="radio" name="mc_id" value="3" <?php if ($output['manage_info']['mc_id']==3) echo 'checked="checked"'; ?> />协理</label>
        <label><input type="radio" name="mc_id" value="4" <?php if ($output['manage_info']['mc_id']==4) echo 'checked="checked"'; ?> />首席</label>
        <label><input type="radio" name="mc_id" value="5" <?php if ($output['manage_info']['mc_id']==5) echo 'checked="checked"'; ?> />股东</label>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>直推人数：</dt>
      <dd>
        <input class="text w200" type="text" name="sub_nums" id="sub_nums" value="<?php echo $output['manage_info']['sub_nums']; ?>" />人(不限制填0)
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>团队人数：</dt>
      <dd>
        <input class="text w200" type="text" name="child_nums" id="child_nums" value="<?php echo $output['manage_info']['child_nums']; ?>" />人(不限制填0)
      </dd>
    </dl> 
    <dl>
      <dt><i class="required">*</i>完成订单量：</dt>
      <dd>
        <input class="text w200" type="text" name="order_nums" id="order_nums" value="<?php echo $output['manage_info']['order_nums']; ?>" />个(不限制填0)
      </dd>
    </dl>   
    <dl>
      <dt><i class="required">*</i>团队业绩：</dt>
      <dd>
        <input class="text w200" type="text" name="achieve_val" id="achieve_val" value="<?php echo $output['manage_info']['achieve_val']; ?>" />人(不限制填0)
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>奖励比率：</dt>
      <dd>
        <input class="text w200" type="text" name="award_commis" id="award_commis" value="<?php echo $output['manage_info']['award_commis']; ?>" />%
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>奖励分享层数：</dt>
      <dd>
        <input class="text w200" type="text" name="award_level" id="award_level" value="<?php echo $output['manage_info']['award_level']; ?>" />
      </dd>
    </dl>

    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="保存" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#manage_form').validate({
    	submitHandler:function(form){
    		ajaxpost('manage_form', '', '', 'onerror') 
    	},
        rules : {
            award_name : {
                required : true
            },
			mc_id      : {
                required : true
            },
			sub_nums   : {
				required : true,
                number   : true,
				min      : 0
            },
			child_nums : {
				required : true,
                number   : true,
				min      : 0
            },
			order_nums : {
				required : true,
                number   : true,
				min      : 0
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
				min      : 0
            }
        },
        messages : {
            award_name : {
                required : '名称不能为空'

            },
			mc_id     : {
                required : '请选择适用高管类型'

            },
			sub_nums   : {
				required : '直推人数不能为空',
                number   : '直推人数必须是数字',
				min      : '直推人数不能小于0',
            },
			child_nums : {
				required : '团队人数不能为空',
                number   : '团队人数必须是数字',
				min      : '团队人数不能小于0',
            },
			order_nums : {
				required : '订单数不能为空',
                number   : '订单数必须是数字',
				min      : '订单数不能小于0',
            },				
			achieve_val  : {
				required : '销售业绩不能为空',
                number   : '销售业绩必须是数字',
				min      : '销售业绩不能小于0',
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
				min      : '分享层数不能小于0',
            }
        }
    });
});
</script> 
