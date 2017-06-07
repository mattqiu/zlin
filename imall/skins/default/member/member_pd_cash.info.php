<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="imm-default-form">
    <dl>
      <dt><?php echo $lang['predeposit_cashsn'].$lang['im_colon']; ?></dt>
      <dd><?php echo $output['info']['pdc_sn']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['predeposit_cash_price'].$lang['im_colon']; ?></dt>
      <dd><?php echo $output['info']['pdc_amount']; ?> <?php echo $lang['currency_zh']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['predeposit_cash_shoukuanbank'].$lang['im_colon']; ?></dt>
      <dd><?php echo $output['info']['pdc_bank_name']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['predeposit_cash_shoukuanaccount'].$lang['im_colon'];?></dt>
      <dd><?php echo $output['info']['pdc_bank_no']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['predeposit_cash_shoukuanname'].$lang['im_colon'];?></dt>
      <dd><?php echo $output['info']['pdc_bank_user']; ?></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['predeposit_addtime'].$lang['im_colon'];?></dt>
      <dd><?php echo @date('Y-m-d',$output['info']['pdc_add_time']); ?></dd>
    </dl>
    <dl>
      <dt><?php echo $lang['predeposit_paystate'].$lang['im_colon'];?></dt>
      <dd><?php echo str_replace(array('0','1','2'),array('审核中','已审核','已支付'),$output['info']['pdc_payment_state']);?></dd>
    </dl>
   <?php if (intval($output['info']['pdc_check_time'])) {?>
    <dl>
      <dt><?php echo $lang['predeposit_checktime'].$lang['im_colon'];?></dt>
      <dd><?php echo @date('Y-m-d H:i:s',$output['info']['pdc_check_time']); ?></dd>
    </dl>
   <?php } ?>
   <?php if (intval($output['info']['pdc_payment_time'])) {?>
    <dl>
      <dt><?php echo $lang['predeposit_paytime'].$lang['im_colon'];?></dt>
      <dd><?php echo @date('Y-m-d H:i:s',$output['info']['pdc_payment_time']); ?></dd>
    </dl>
   <?php } ?> 
  </div>
</div>
