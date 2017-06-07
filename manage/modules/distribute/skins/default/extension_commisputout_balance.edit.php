<?php defined('InIMall') or exit('Access Invalid!');?>

  <form id="commisputout_form" name="commisputout_form" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('extension_commisputout', 'balance_save');?>">
    <input type="hidden" name="id" value="<?php echo $output['promotion_id'];?>" />
    <table class="table tb-type3 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="required tr">结算帐户：</td>        
          <td class="vatop rowform"><?php echo $output['promotion_name'];?></td>
        </tr>
        <tr class="noborder">
          <td class="required tr">结算金额：</td>        
          <td class="vatop rowform"><?php echo $output['pay_commis'];?>元</td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td class="required tr"></td>
          <td colspan="15">
            <a href="javascript:void(0)" onclick="balance_save()" class="imap-btn-big imap-btn-green"><span><i class="fa fa-thumbs-up"></i>确定结算</span></a>
            <a href="javascript:void(0)" onclick="balance_cancel()" class="imap-btn-big imap-btn-green"><span><i class="fa fa-thumbs-down"></i>取消结算</span></a>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
<script language="javascript">
    function balance_save(){
		ajaxpost('commisputout_form', '', '', 'onerror');
    }
	function balance_cancel(){
		DialogManager.close('balance_dialog');
    }	
</script>