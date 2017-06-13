<?php defined('InIMall') or exit('Access Invalid!');?>

  <form id="cityunion_config_form" name="cityunion_config_form" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('cityunion_config', 'manage_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <?php if ($output['manage_info']['em_id']!=0) { ?>
    <input type="hidden" name="em_id" value="<?php echo $output['manage_info']['em_id']; ?>" />
    <?php } ?>
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="award_name">补贴名称：</td>
          <td>
            <input class="text w200" type="text" name="award_name" id="award_name" value="<?php echo $output['manage_info']['award_name']; ?>" />
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="mc_id">高管类型：</td>
          <td>
            <label><input type="radio" name="mc_id" value="2" <?php if (empty($output['manage_info']['mc_id']) || $output['manage_info']['mc_id']==2) echo 'checked="checked"'; ?> />合伙人</label>
            <label><input type="radio" name="mc_id" value="3" <?php if ($output['manage_info']['mc_id']==3) echo 'checked="checked"'; ?> />区代</label>
            <label><input type="radio" name="mc_id" value="4" <?php if ($output['manage_info']['mc_id']==4) echo 'checked="checked"'; ?> />省代</label>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="sub_nums">合伙人数：</td>
          <td>
            <input class="text w100" type="text" name="sub_nums" id="sub_nums" value="<?php echo $output['manage_info']['sub_nums']; ?>" />人(不限制填0)
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="child_nums">联盟人数：</td>
          <td>
            <input class="text w100" type="text" name="child_nums" id="child_nums" value="<?php echo $output['manage_info']['child_nums']; ?>" />人(不限制填0)
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="order_nums">完成订单量：</td>
          <td>
            <input class="text w100" type="text" name="order_nums" id="order_nums" value="<?php echo $output['manage_info']['order_nums']; ?>" />个(不限制填0)
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="achieve_val">团队销售额：</td>
          <td>
            <input class="text w100" type="text" name="achieve_val" id="achieve_val" value="<?php echo $output['manage_info']['achieve_val']; ?>" />元(不限制填0)
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="award_rate">补贴比率：</td>
          <td>
            <input class="text w100" type="text" name="award_rate" id="award_rate" value="<?php echo $output['manage_info']['award_rate']; ?>" />%
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="award_level">补贴分享层数：</td>
          <td>
            <input class="text w100" type="text" name="award_level" id="award_level" value="<?php echo $output['manage_info']['award_level']; ?>" />层
          </td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td></td>
          <td colspan="15" ><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><span>保存</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
<script type="text/javascript">
$(function(){
	//按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#cityunion_config_form").valid()){
            ajaxpost('cityunion_config_form', '', '', 'onerror') 
        }
    });
	
    $('#cityunion_config_form').validate({
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
            award_rate : {
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
				required : '合伙人数不能为空',
                number   : '合伙人数必须是数字',
				min      : '合伙人数不能小于0',
            },
			child_nums : {
				required : '联盟人数不能为空',
                number   : '联盟人数必须是数字',
				min      : '联盟人数不能小于0',
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
            award_rate  : {
				required : '补贴比率不能为空',
                number   : '补贴比率必须是数字',
				max      : '补贴比率不能大于100',
				min      : '补贴比率不能小于0',
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
