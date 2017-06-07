<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w10"></th>
      <th>支付方式</th>
      <th>启用</th>
      <th class="w120">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['payment_list']) && is_array($output['payment_list'])) { ?>
    <?php foreach($output['payment_list'] as $v) { ?>
    <tr class="bd-line">
      <td></td>
      <td><?php echo $v['payment_name'];?></td>
      <td>
	    <?php echo $v['payment_state'] == '1' ? '开启中' : '关闭中';?>
      </td>
      <td><a href="index.php?act=seller_payment&op=edit&payment_id=<?php echo $v['payment_id']; ?>"><?php echo $lang['im_edit']?></a></td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr class="tfoot">
      <td colspan="15"></td>
    </tr>
  </tfoot>
</table>