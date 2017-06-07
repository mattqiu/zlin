<?php defined('InIMall') or exit('Access Invalid!');?>

  <form id="member_form" name="member_form" enctype="multipart/form-data" method="post" action="<?php echo urlAdminShop('member', 'membergrade_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <?php if ($output['membergrade_info']['mg_id']!=0) { ?>
    <input type="hidden" name="mg_id" value="<?php echo $output['membergrade_info']['mg_id']; ?>" />
    <?php } ?>
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="grade_name">补贴名称：</td>
          <td>
            <input class="text w200" type="text" name="grade_name" id="grade_name" value="<?php echo $output['membergrade_info']['grade_name']; ?>" />
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="grade_level">会员级别：</td>
          <td>
        	<select class="class-select valid" name="grade_level">
              <option value="0">-请选择-</option>
              <?php if (!empty($output['grade_level']) && is_array($output['grade_level'])) { ?>
      			<?php foreach($output['grade_level'] as $v) { ?>
              	<option value="<?php echo $v['level'];?>" <?php if($output['membergrade_info']['grade_level']==$v['level']){echo "selected";} ?>><?php echo $v['level_name'];?></option>
              	<?php } ?>
              <?php } ?>
        	</select>
        	</td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="child_nums">团队人数：</td>
          <td>
            <input class="text w100" type="text" name="child_nums" id="child_nums" value="<?php echo $output['membergrade_info']['child_nums']; ?>" />人(不限制填0)
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="order_nums">完成订单量：</td>
          <td>
            <input class="text w100" type="text" name="order_nums" id="order_nums" value="<?php echo $output['membergrade_info']['order_nums']; ?>" />个(不限制填0)
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="team_amount">团队销售额：</td>
          <td>
            <input class="text w100" type="text" name="team_amount" id="team_amount" value="<?php echo $output['membergrade_info']['team_amount']; ?>" />元(不限制填0)
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="level_rate">会员享受折扣：</td>
          <td>
            <input class="text w100" type="text" name="level_rate" id="level_rate" value="<?php echo $output['membergrade_info']['level_rate']; ?>" />%
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
        if($("#member_form").valid()){
            ajaxpost('member_form', '', '', 'onerror') 
        }
    });
	
    $('#member_form').validate({
        rules : {
            grade_name : {
                required : true
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
			team_amount  : {
				required : true,
                number   : true,
				min      : 0
            }
        },
        messages : {
            grade_name : {
                required : '名称不能为空'

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
			team_amount  : {
				required : '销售业绩不能为空',
                number   : '销售业绩必须是数字',
				min      : '销售业绩不能小于0',
            }
        }
    });
});
</script> 
