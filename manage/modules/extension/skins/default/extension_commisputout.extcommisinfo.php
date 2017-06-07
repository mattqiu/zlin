<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>推广管理</h3>
        <h5>查看和管理平台推广佣金结算明细。</h5>
      </div>
      <?php echo $output['top_link'];?></div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>查看推广员佣金明细。</li>
    </ul>
  </div>
  
  <div class="fixed-empty"></div>
  <table class="table tb-type2">
    <thead>
      <tr>
        <th class="w12"></th>
        <th class="w150 tl">订单编号</th>
        <th class="w80 tc">订单金额</th>      
        <th class="w80 tc">总佣金</th>
        <th class="w80 tc">分成比率(%)</th>
        <th class="w80 tc">结算佣金</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['detail_list']) && is_array($output['detail_list'])) { ?>
      <?php foreach($output['detail_list'] as $v) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="tl"><?php echo $v['order_sn'];?><a href="<?php echo urlAdminshop('order', 'show_order',array('order_id'=>$v['order_id']));?>" target="_blank">(查看)</a></td>
        <td class="tc"><?php echo $v['goods_amount'];?></td>
        <td class="tc"><?php echo $v['commis_amount'];?></td>
        <td class="tc"><?php echo $v['commis_rate'];?></td>
        <td class="tc"><?php echo $v['mb_commis_totals'];?></td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr class="no_data">
        <td colspan="15">暂无抽佣明细</td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15">
          <div class="pagination"><?php echo $output['show_page'];?></div>
        </td>
      </tr>
    </tfoot>
  </table>
</div>