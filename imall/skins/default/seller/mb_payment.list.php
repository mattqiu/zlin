<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="imsc-form-default">
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr>
        <td>
        <ul>
            <li>此处列出了手机支持的支付方式，点击编辑可以设置支付参数及开关状态</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <table class="table tb-type2">
    <thead>
      <tr class="thead">
        <th>支付方式</th>
        <th class="align-center">启用</th>
        <th class="align-center"><?php echo $lang['im_handle'];?></th>
      </tr>
    </thead>
    <tbody>
        <?php if(!empty($output['mb_payment_list']) && is_array($output['mb_payment_list'])){ ?>
        <?php foreach($output['mb_payment_list'] as $k => $v) { ?>
      <tr class="hover">
        <td><?php echo $v['payment_name'];?></td>
        <td class="w25pre align-center"><?php echo $v['payment_state_text'];?></td>
        <td class="w156 align-center"><a href="<?php echo urlShop('mb_payment', 'payment_edit', array('payment_id' => $v['payment_id']));?>"><?php echo $lang['im_edit']?></a></td>
      </tr>
      <?php } } ?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15"></td>
      </tr>
    </tfoot>
  </table>
</div>
