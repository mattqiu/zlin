<?php defined('InIMall') or exit('Access Invalid!');?>

<table class="imm-default-table">
  <thead>
    <tr>
      <th class="w10"></th>
      <th class="w150">订单编号</th>
      <th class="w80">订单金额</th>      
      <th class="w80">总佣金</th>
      <th class="w80">分成比率(%)</th>
      <th class="w80">推广积分</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['detail_list']) && is_array($output['detail_list'])) { ?>
    <?php foreach($output['detail_list'] as $v) { ?>
    <tr class="bd-line">
      <td></td>
      <td><?php echo $v['order_sn'];?></td>
      <td><?php echo $v['goods_amount'];?></td>
      <td><?php echo $v['commis_amount'];?></td>
      <td><?php echo $v['commis_rate'];?></td>
      <td><?php echo $v['mb_commis_totals'];?></td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无抽佣明细</span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr class="tfoot">
      <td colspan="15"><div class="pagination"><?php echo $output['show_page'];?></div></td>
    </tr>
  </tfoot>
</table>