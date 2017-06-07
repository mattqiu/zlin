<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="eject_con">
  <form id="apply_form" method="post" target="_parent" action="<?php echo urlShop('seller_commisputout', 'balance_save');?>">
    <input type="hidden" name="id" value="<?php echo $output['promotion_id'];?>" />
    <dl>
      <dt>结算帐户：</dt>
      <dd><?php echo $output['promotion_name'];?></dd>
    </dl>
    <dl>
      <dt>结算金额：</dt>
      <dd><?php echo $output['pay_commis'];?>元</dd>
    </dl>
    <dl>
      <dt>帐户余额：</dt>
      <dd><?php echo $output['my_predeposit'];?>元</dd>
    </dl> 
    <div class="bottom">
      <?php if ($output['my_predeposit']>=$output['pay_commis']){?>
      <a href="javascript:void(0)" onclick="balance_save()" class="imsc-btn imsc-btn-acidblue m10"><i class="fa fa-thumbs-up"></i>确定结算</a>
      <?php }else {?>
      <a href="index.php?act=predeposit&op=recharge_add" class="imsc-btn imsc-btn-red m10"><i class="fa fa-cc-visa"></i>积分充值</a>
      <?php }?>
      <a href="javascript:void(0)" onclick="balance_cancel()" class="imsc-btn imsc-btn-orange m10"><i class="fa fa-thumbs-down"></i>取消结算</a>
    </div>
  </form>
</div>
<script language="javascript">
    function balance_save(){
		ajaxpost('apply_form', '', '', 'onerror');
    }
	function balance_cancel(){
		DialogManager.close('balance_dialog');
    }	
</script>