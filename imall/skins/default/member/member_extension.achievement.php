<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="alert" align="center">
    <span class="mr30">累计销售：<strong class="mr5 red" style="font-size: 18px;"><?php echo $output['statistics_all']['amounts']; ?></strong><?php echo $lang['currency_zh'];?></span>
    <span>本季销售：<strong class="mr5 blue" style="font-size: 18px;"><?php echo $output['statistics_curr']['amounts']; ?></strong><?php echo $lang['currency_zh'];?></span>
  </div>
  <form method="get" action="index.php">
    <input type="hidden" name="act" value="member_extension" />
    <input type="hidden" name="op" value="my_achievement" />
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
        <th>状态</th>
        <td class="w70">
          <select name="give_status">
            <option value="" <?php if (!isset($_GET['give_status'])) {?>selected="selected"<?php }?>>全部</option>
            <option value="<?php echo ORDER_STATE_NEW;?>" <?php if ($_GET['give_status'] == ORDER_STATE_NEW) {?>selected="selected"<?php }?>>未付款</option>
            <option value="<?php echo ORDER_STATE_PAY;?>" <?php if ($_GET['give_status'] == ORDER_STATE_PAY) {?>selected="selected"<?php }?>>已付款</option>
            <option value="<?php echo ORDER_STATE_SEND;?>" <?php if ($_GET['give_status'] == ORDER_STATE_SEND) {?>selected="selected"<?php }?>>已发货</option>
            <option value="<?php echo ORDER_STATE_SUCCESS;?>" <?php if ($_GET['give_status'] == ORDER_STATE_SUCCESS) {?>selected="selected"<?php }?>>已完成</option>
          </select>
        </td>
        <th>推广</th>
        <td class="w160"><input type="text" class="text w150" name="saleman_name" id="saleman_name" value="<?php echo $_GET['saleman_name']; ?>" /></td>
        <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="查询" /></label></td>
      </tr>
    </table>
  </form>
  <table class="imm-default-table">
    <thead>
      <tr>
        <th class="w100">日期</th>
        <th class="w100">推广</th>
        <th class="w200">订单</th>
        <th class="w200">店铺</th>
        <th class="w100">顾客</th>
        <th class="w100">金额</th>
        <th class="w100">状态</th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['achievement_list'])>0) { ?>
      <?php foreach($output['achievement_list'] as $key=>$val) { ?>
      <tr class="bd-line">
        <td class="goods-time"><?php echo date('Y-m-d',$val['add_time']);?></td>
        <td class="goods-price"><?php echo $val['saleman_name']; ?></td>
        <td><?php echo $val['order_sn']; ?></td>
        <td><?php echo $val['store_name']; ?></td>
        <td><?php echo $val['buyer_name']; ?></td>
        <td><?php echo $val['goods_amount'];?></td>
        <td><?php echo $val['state_desc'];?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span>暂无销售业绩！</span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (count($output['achievement_list'])>0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
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
	    	window.location.href = 'index.php?act=member_extension&op=my_achievement&type=today';
		})
	    $('#week_flow').click(function(){
	    	window.location.href = 'index.php?act=member_extension&op=my_achievement&type=week';
		})
		$('#month_flow').click(function(){
	    	window.location.href = 'index.php?act=member_extension&op=my_achievement&type=month';
		})
		$('#year_flow').click(function(){
	    	window.location.href = 'index.php?act=member_extension&op=my_achievement&type=year';
		})
	});
</script>