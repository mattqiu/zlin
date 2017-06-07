<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <form method="get" action="index.php">
    <input type="hidden" name="act" value="member_extension" />
    <input type="hidden" name="op" value="promotion_detail" />
    <input type="hidden" name="promotion_id" value="<?php echo $output['promotion_id'];?>" />
    <table class="imm-search-table">
      <tr>
        <td class="w10">&nbsp;</td>
        <td>
        　　<a href="javascript:void(0);" class="imm-btn-mini" id="today_flow">今日</a>
          <a href="javascript:void(0);" class="imm-btn-mini" id="week_flow">本周</a>
          <a href="javascript:void(0);" class="imm-btn-mini" id="month_flow">本月</a>
          <a href="javascript:void(0);" class="imm-btn-mini" id="year_flow">今年</a>
        </td>        
        <th>时段</th>
        <td class="w240">
          <input type="text" id="add_time_from" name="add_time_from" class="text w70" value="<?php echo $_GET['add_time_from'];?>"><label class="add-on"><i class="fa fa-calendar"></i></label>&nbsp;&#8211;&nbsp;
          <input type="text" id="add_time_to" name="add_time_to" class="text w70" value="<?php echo $_GET['add_time_to'];?>"><label class="add-on"><i class="fa fa-calendar"></i></label>
        </td>
        <th>结算</th>
        <td class="w70">
          <select name="give_status">
            <option value="" <?php if (!isset($_GET['give_status'])) {?>selected="selected"<?php }?>>全部</option>
            <option value="<?php echo ORDER_STATE_NEW;?>" <?php if ($_GET['give_status'] == ORDER_STATE_NEW) {?>selected="selected"<?php }?>>未付款</option>
            <option value="<?php echo ORDER_STATE_PAY;?>" <?php if ($_GET['give_status'] == ORDER_STATE_PAY) {?>selected="selected"<?php }?>>已付款</option>
            <option value="<?php echo ORDER_STATE_SEND;?>" <?php if ($_GET['give_status'] == ORDER_STATE_SEND) {?>selected="selected"<?php }?>>已发货</option>
            <option value="<?php echo ORDER_STATE_SUCCESS;?>" <?php if ($_GET['give_status'] == ORDER_STATE_SUCCESS) {?>selected="selected"<?php }?>>已完成</option>
          </select>
        </td>
        <th>顾客</th>
        <td class="w160"><input type="text" class="text w150" name="saleman_name" id="saleman_name" value="<?php echo $_GET['saleman_name']; ?>" /></td>
        <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="查询" /></label></td>
      </tr>
    </table>
  </form>
  <table class="imm-default-table">
    <thead>
      <tr>
        <th class="w100">日期</th>
        <th class="w200">订单</th>
        <th class="w200">店铺</th>
        <th class="w100">顾客</th>
        <th class="w100">金额</th>
        <th class="w100">状态</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['detail_list']) && is_array($output['detail_list'])) { ?>
      <?php foreach($output['detail_list'] as $v) { ?>
      <tr class="bd-line">
        <td class="goods-time"><?php echo date('Y-m-d',$v['add_time']);?></td>
        <td><?php echo $v['order_sn']; ?></td>
        <td><?php echo $v['store_name']; ?></td>
        <td><?php echo $v['buyer_name']; ?></td>
        <td><?php echo $v['goods_amount'];?></td>
        <td><?php echo $v['state_desc'];?></td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无业绩明细</span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
      </tr>
    </tfoot>
  </table>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 
<script type="text/javascript">
$(function(){
	$('#add_time_from').datepicker({dateFormat: 'yymmdd'});
	$('#add_time_to').datepicker({dateFormat: 'yymmdd'});		

	$('#today_flow').click(function(){
	    window.location.href = 'index.php?act=member_extension&op=promotion_detail&type=today&promotion_id=<?php echo $output['promotion_id'];?>';
	})
	$('#week_flow').click(function(){
	    window.location.href = 'index.php?act=member_extension&op=promotion_detail&type=week&promotion_id=<?php echo $output['promotion_id'];?>';
	})
	$('#month_flow').click(function(){
	    window.location.href = 'index.php?act=member_extension&op=promotion_detail&type=month&promotion_id=<?php echo $output['promotion_id'];?>';
	})
	$('#year_flow').click(function(){
	    window.location.href = 'index.php?act=member_extension&op=promotion_detail&type=year&promotion_id=<?php echo $output['promotion_id'];?>';
	})
});
</script>