<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="seller_commisputout" />
    <input type="hidden" name="op" value="balance_list" />
    <tr>
      <td>
        <a href="javascript:void(0);" class="imsc-btn-mini" id="today_flow">今日结算</a>
        <a href="javascript:void(0);" class="imsc-btn-mini" id="week_flow">本周结算</a>
        <a href="javascript:void(0);" class="imsc-btn-mini" id="month_flow">本月结算</a>
        <a href="javascript:void(0);" class="imsc-btn-mini" id="year_flow">今年结算</a>
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
      <th class="w30">名称</th>
      <td class="w80">
        <input type="text" class="text w70" name="saleman_name" id="saleman_name" value="<?php echo $_GET['saleman_name']; ?>" />
      </td>
      <td class="w60 tc"><label class="submit-border"><input type="submit" class="submit" value="查询" /></label></td>
    </tr>
  </table>
</form>
<div class="title" style="text-align:center; margin:10px;">
  <p style="font-size:16px; color:#3e576f; font-weight:bold;"><?php echo $output['main_title'];?></p>
  <p style="font-size:12px; color:#6da9d0; "><?php echo $output['sub_title'];?></p>
</div>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w100">交易号</th>
      <th class="w100">推广帐号</th>
      <th class="w80">类型</th>
      <th class="w80">金额</th>
      <th class="w80">支付方式</th>
      <th class="w60">审核</th>
      <th class="w100">审核日期</th>      
      <th class="w100">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['extcommis_list']) && is_array($output['extcommis_list'])) {?>
    <?php foreach($output['extcommis_list'] as $v) { ++$i;?>
    <tr>
      <td><?php echo $v['pde_sn'];?></td>
      <td><?php echo $v['pde_member_name'];?></td>
      <td><?php if($v['pde_mc_id']==1){echo '导购员';}elseif($v['pde_mc_id']==2){echo '推广员';}else{echo '';}?></td>
      <td><?php echo $v['pde_amount'];?></td>
      <td><?php echo $v['pde_payment_name'];?></td>
      <td><?php echo $v['pde_admin'];?></td>
      <td><?php echo date("Y-m-d",$v['pde_add_time']);?></td>
      <td>
        <a href="javascript:void(0)" im_type="dialog" dialog_title="查看推广员信息" dialog_id="my_apply_info" dialog_width="480" uri="<?php echo urlShop('seller_commisputout', 'promotion_info',array('promotion_id'=>$v['pde_member_id']));?>" title="查看推广员信息">查看</a>|
        <a href="javascript:void(0)" im_type="dialog" dialog_title="查看佣金明细" dialog_id="my_apply_edit" dialog_width="900" uri="<?php echo urlShop('seller_commisputout', 'extcommis_info',array('sn'=>$v['pde_sn'],'id'=>$v['pde_member_id']));?>" title="查看佣金明细">明细</a>
      </td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无佣金结算信息</span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr class="tfoot">
      <td colspan="10">
        <div class="pagination"> <?php echo $output['page'];?> </div>
      </td>
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
	    	window.location.href = 'index.php?act=seller_commisputout&op=balance_list&type=today';
		})
	    $('#week_flow').click(function(){
	    	window.location.href = 'index.php?act=seller_commisputout&op=balance_list&type=week';
		})
		$('#month_flow').click(function(){
	    	window.location.href = 'index.php?act=seller_commisputout&op=balance_list&type=month';
		})
		$('#year_flow').click(function(){
	    	window.location.href = 'index.php?act=seller_commisputout&op=balance_list&type=year';
		})
	});
</script>