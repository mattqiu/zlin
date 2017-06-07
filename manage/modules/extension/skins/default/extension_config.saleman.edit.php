<?php defined('InIMall') or exit('Access Invalid!');?>

  <form id="extension_config_form" name="extension_config_form" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('extension_config', 'saleman_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <?php if ($output['saleman_info']['sm_id']!=0) { ?>
    <input type="hidden" name="sm_id" value="<?php echo $output['saleman_info']['sm_id']; ?>" />
    <?php } ?>
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="award_name">补贴名称：</td>
          <td>
            <input class="text w200" type="text" name="award_name" id="award_name" value="<?php echo $output['saleman_info']['award_name']; ?>" />
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="mc_id">导购类型：</td>
          <td>
            <label><input type="radio" name="mc_id" value="12" <?php if (empty($output['saleman_info']['mc_id']) || $output['saleman_info']['mc_id']==12) echo 'checked="checked"'; ?> />导购</label>
            <label><input type="radio" name="mc_id" value="11" <?php if ($output['saleman_info']['mc_id']==11) echo 'checked="checked"'; ?> />经理</label>
            <label><input type="radio" name="mc_id" value="10" <?php if ($output['saleman_info']['mc_id']==10) echo 'checked="checked"'; ?> />店长</label>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="serve_nums">保底薪资：</td>
          <td>
            <input class="text w100" type="text" name="base_salary" id="base_salary" value="<?php echo $output['saleman_info']['base_salary']; ?>" />元(0表示没有基本薪资)
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="serve_nums">服务人数：</td>
          <td>
            <input class="text w100" type="text" name="serve_nums" id="serve_nums" value="<?php echo $output['saleman_info']['serve_nums']; ?>" />人(不限制填0)
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="order_nums">服务单量：</td>
          <td>
            <input class="text w100" type="text" name="order_nums" id="order_nums" value="<?php echo $output['saleman_info']['order_nums']; ?>" />个(不限制填0)
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="achieve_val">服务业绩：</td>
          <td>
            <input class="text w100" type="text" name="achieve_val" id="achieve_val" value="<?php echo $output['saleman_info']['achieve_val']; ?>" />元(不限制填0)
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="award_rate">补贴比率：</td>
          <td>
            <input class="text w100" type="text" name="award_rate" id="award_rate" value="<?php echo $output['saleman_info']['award_rate']; ?>" />%
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
        if($("#extension_config_form").valid()){
            ajaxpost('extension_config_form', '', '', 'onerror') 
        }
    });
	
    $('#extension_config_form').validate({
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
			serve_nums : {
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
                required : '请选择适用导购类型'

            },
			serve_nums : {
				required : '服务人数不能为空',
                number   : '服务人数必须是数字',
				min      : '服务人数不能小于0',
            },
			order_nums : {
				required : '服务订单数不能为空',
                number   : '服务订单数必须是数字',
				min      : '服务订单数不能小于0',
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
            }
        }
    });
});
</script> 
