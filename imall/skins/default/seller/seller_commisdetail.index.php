<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="imsc-btn imsc-btn-green" im_type="dialog" dialog_title="添加抽佣明细" dialog_id="my_category_add" dialog_width="480" uri="<?php echo urlShop('seller_commisdetail', 'commisdetail_edit');?>" title="添加抽佣明细">添加抽佣明细</a>  
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="seller_commisdetail" />
    <input type="hidden" name="op" value="index" />
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
        <select name="saleman_type">
          <option value="0" <?php if ($_GET['saleman_type'] == 0) {?>selected="selected"<?php }?>>全部</option>
          <option value="1" <?php if ($_GET['saleman_type'] == 1) {?>selected="selected"<?php }?>>导购员</option>
          <option value="2" <?php if ($_GET['saleman_type'] == 2) {?>selected="selected"<?php }?>>推广员</option>
        </select>
      </td>
      <td class="w70">
        <select name="give_status">
          <option value="3" <?php if ($_GET['give_status'] == 3) {?>selected="selected"<?php }?>>全部</option>
          <option value="1" <?php if ($_GET['give_status'] == 1) {?>selected="selected"<?php }?>>已结算</option>
          <option value="0" <?php if ($_GET['give_status'] == 0) {?>selected="selected"<?php }?>>未结算</option>
        </select>
      </td>
      <th class="w30">名称</th>
      <td class="w80">
        <input type="text" class="text w70" name="saleman_name" id="saleman_name" value="<?php echo $_GET['saleman_name']; ?>" />
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
      <th class="w100">推广/导购</th>           
      <th class="w150">订单编号</th>
      <th class="w100">抽佣者</th>      
      <th class="w80">总佣金</th>
      <th class="w80">抽佣率(%)</th>
      <th class="w80">抽佣金额</th>
      <th class="w100">日期</th>
      <th class="w60">状态</th>
      <th class="w100">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['detail_list']) && is_array($output['detail_list'])) { ?>
    <?php foreach($output['detail_list'] as $v) { ?>
    <tr class="bd-line">
      <td></td>
      <td><?php echo $v['mcd_id'];?></td>
      <td><?php echo $v['extension_name'];?><a href="<?php echo urlShop('member_snshome', 'index',array('mid'=>$v['extension_id']));?>" target="_blank">(查看)</a></td>      
      <td><?php echo $v['order_sn'];?><a href="<?php echo urlShop('store_order', 'show_order',array('order_id'=>$v['order_id']));?>" target="_blank">(查看)</a></td>
      <td><?php echo $v['saleman_name'];?><a href="<?php echo urlShop('member_snshome', 'index',array('mid'=>$v['saleman_id']));?>" target="_blank">(查看)</a></td>
      <td><?php echo $v['commis_amount'];?></td>
      <td><?php echo $v['commis_rate'];?></td>
      <td><?php echo $v['mb_commis_totals'];?></td>      
      <td><?php echo date('Y-m-d',$v['add_time']);?></td>
      <td><?php echo ($v['give_status']==1)?'已结算':'未结算';?></td>
      <td>
        <a href="javascript:void(0)" im_type="dialog" dialog_title="修改抽佣明细" dialog_id="my_category_add" dialog_width="480" uri="<?php echo urlShop('seller_commisdetail', 'commisdetail_edit',array('mcd_id'=>$v['mcd_id']));?>" title="修改抽佣明细">修改</a>|
        <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlShop('seller_commisdetail', 'commisdetail_del',array('mcd_id'=>$v['mcd_id']));?>')">删除</a>
      </td>
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
	    	window.location.href = 'index.php?act=seller_commisdetail&op=index&type=today';
		})
	    $('#week_flow').click(function(){
	    	window.location.href = 'index.php?act=seller_commisdetail&op=index&type=week';
		})
		$('#month_flow').click(function(){
	    	window.location.href = 'index.php?act=seller_commisdetail&op=index&type=month';
		})
		$('#year_flow').click(function(){
	    	window.location.href = 'index.php?act=seller_commisdetail&op=index&type=year';
		})
	});
</script>