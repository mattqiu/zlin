<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="seller_commisputout" />
    <input type="hidden" name="op" value="commisputout_detail" />
    <input type="hidden" name="promotion_id" value="<?php echo $output['promotion_id'];?>" />
    <tr>
      <td>
        <a href="javascript:void(0);" class="imsc-btn-mini" id="today_flow">今日明细</a>
        <a href="javascript:void(0);" class="imsc-btn-mini" id="week_flow">本周明细</a>
        <a href="javascript:void(0);" class="imsc-btn-mini" id="month_flow">本月明细</a>
        <a href="javascript:void(0);" class="imsc-btn-mini" id="year_flow">今年明细</a>
      </td>
      <th class="w30">时段</th>
      <td class="w240">
        <input type="text" class="text w70" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>&nbsp;&#8211;&nbsp;
        <input type="text" class="text w70" id="add_time_to" name="add_time_to" value="<?php echo $_GET['add_time_to']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>
      </td>
      <td class="w70">
        <select name="give_status">
          <option value="3" <?php if ($_GET['give_status'] == 3) {?>selected="selected"<?php }?>>全部</option>
          <option value="1" <?php if ($_GET['give_status'] == 1) {?>selected="selected"<?php }?>>已结算</option>
          <option value="0" <?php if ($_GET['give_status'] == 0) {?>selected="selected"<?php }?>>未结算</option>
        </select>
      </td>
      <td class="w60 tc"><label class="submit-border"><input type="submit" class="submit" value="查询" /></label></td>
    </tr>
  </table>
</form>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w10"></th>
      <th class="w30">编号</th>
      <?php if ($output['mc_id'] ==2){?>
      <th class="w100">推广员</th> 
      <?php }?>
      <th class="w150">订单编号</th>      
      <th class="w80">总佣金</th>
      <th class="w80">抽佣率(%)</th>
      <th class="w80">抽佣金额</th>
      <th class="w120">完成时间</th>
      <th class="w60">状态</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['detail_list']) && is_array($output['detail_list'])) { ?>
    <?php foreach($output['detail_list'] as $v) { ?>
    <tr class="bd-line">
      <td></td>
      <td><?php echo $v['mcd_id'];?></td>
      <?php if ($output['mc_id'] ==2){?>
      <td><?php echo $v['extension_name'];?><a href="<?php echo urlShop('member_snshome', 'index',array('mid'=>$v['extension_id']));?>" target="_blank">(查看)</a></td> 
      <?php }?>
      <td><?php echo $v['order_sn'];?><a href="<?php echo urlShop('store_order', 'show_order',array('order_id'=>$v['order_id']));?>" target="_blank">(查看订单)</a></td>
      <td><?php echo $v['commis_amount'];?></td>
      <td><?php echo $v['commis_rate'];?></td>
      <td><?php echo $v['mb_commis_totals'];?></td>
      <td><?php echo date('Y-m-d',$v['add_time']);?></td>
      <td><?php echo ($v['give_status']==1)?'已结算':'未结算';?></td>
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

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 
<script type="text/javascript">
$(function(){
	$('#add_time_from').datepicker({dateFormat: 'yymmdd'});
	$('#add_time_to').datepicker({dateFormat: 'yymmdd'});		

	$('#today_flow').click(function(){
	    window.location.href = 'index.php?act=seller_commisputout&op=commisputout_detail&type=today&promotion_id=<?php echo $output['promotion_id'];?>';
	})
	$('#week_flow').click(function(){
	    window.location.href = 'index.php?act=seller_commisputout&op=commisputout_detail&type=week&promotion_id=<?php echo $output['promotion_id'];?>';
	})
	$('#month_flow').click(function(){
	    window.location.href = 'index.php?act=seller_commisputout&op=commisputout_detail&type=month&promotion_id=<?php echo $output['promotion_id'];?>';
	})
	$('#year_flow').click(function(){
	    window.location.href = 'index.php?act=seller_commisputout&op=commisputout_detail&type=year&promotion_id=<?php echo $output['promotion_id'];?>';
	})
});
</script>