<?php defined('InIMall') or exit('Access Invalid!');?>

  <form id="commisdetail_form" name="commisdetail_form" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('extension_commisdetail', 'commisdetail_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <?php if ($output['commis_detail']['mcd_id']!=0) { ?>
    <input type="hidden" name="mcd_id" value="<?php echo $output['commis_detail']['mcd_id']; ?>" />
    <?php } ?>
    <table class="table tb-type3 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="required tr">代理id：</td>        
          <td class="vatop rowform"><input class="text w200" type="text" name="saleman_id" id="saleman_id" value="<?php echo $output['commis_detail']['saleman_id']; ?>" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr">代理名称：</td>        
          <td class="vatop rowform"><input class="text w200" type="text" name="saleman_name" id="saleman_name" value="<?php echo $output['commis_detail']['saleman_name']; ?>" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr">订单ID：</td>        
          <td class="vatop rowform"><input class="text w200" type="text" name="order_id" id="order_id" value="<?php echo $output['commis_detail']['order_id']; ?>" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="order_sn">订单编号：</label></td>        
          <td class="vatop rowform"><input class="text w200" type="text" name="order_sn" id="order_sn" value="<?php echo $output['commis_detail']['order_sn']; ?>" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr">订单总价：</td>        
          <td class="vatop rowform"><input class="text w200" type="text" name="goods_amount" id="goods_amount" value="<?php echo $output['commis_detail']['goods_amount']; ?>" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr">抽佣比例：</td>        
          <td class="vatop rowform"><input class="text w200" type="text" name="commis_rate" id="commis_rate" value="<?php echo $output['commis_detail']['commis_rate']; ?>" />%</td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="mb_commis_totals">抽佣金额：</label></td>        
          <td class="vatop rowform"><input class="text w200" type="text" name="mb_commis_totals" id="mb_commis_totals" value="<?php echo $output['commis_detail']['mb_commis_totals']; ?>" /></td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td class="required tr"></td>
          <td colspan="15"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><span>保存</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>

<script type="text/javascript">
$(function(){
	$("#submitBtn").click(function(){
        if($("#commisdetail_form").valid()){
            ajaxpost('commisdetail_form', '', '', 'onerror') 
	    }
	});
	
    $('#commisdetail_form').validate({
        rules : {
            order_sn : {
                required : true
            },
            mb_commis_totals : {
				required : true,
                number   : true
            }
        },
        messages : {
            order_sn : {
                required : '订单编号不能为空'

            },
            mb_commis_totals  : {
				required : '抽佣金额不能为空',
                number   : '抽佣金额必须是数字'
            }
        }
    });
});
</script> 
