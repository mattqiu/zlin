<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="alert" align="center">
    <span class="mr30">累计推广收入：<strong class="mr5 red" style="font-size: 18px;"><?php echo ($output['statistics_all']['commis']>0)?$output['statistics_all']['commis']:0; ?></strong><?php echo $lang['currency_zh'];?></span>   </div>
  <form method="get" action="index.php">
    <input type="hidden" name="act" value="member_extension" />
    <input type="hidden" name="op" value="my_commission" />
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
        <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="查询" /></label></td>
      </tr>
    </table>
  </form>
  <div class="title" style="text-align:center; margin:10px;">
    <p style="font-size:16px; color:#3e576f; font-weight:bold;"><?php echo $output['main_title'];?></p>
    <p style="font-size:12px; color:#6da9d0; "><?php echo $output['sub_title'];?></p>
  </div>
  <table class="imm-default-table">
    <thead>
      <tr>
        <th class="w100">交易号</th>
        <th class="w80 tc">推广佣金</th>
        <th class="w80 tc">高管奖励</th>
        <th class="w80 tc">门店补贴</th>
        <th class="w80 tc">结算金额</th>
        <th class="w100">结算日期</th>
        <th class="w100">操作</th>   
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['balance_list'])>0) { ?>
      <?php foreach($output['balance_list'] as $key=>$v) { ?>
      <tr class="bd-line">
        <td><?php echo $v['pde_sn'];?></td>
        <td class="tc"><?php echo $v['pde_commis']>0?$v['pde_commis']:'';?></td>
        <td class="tc"><?php echo $v['pde_manageaward']>0?$v['pde_manageaward']:'';?></td>
        <td class="tc"><?php echo $v['pde_perforaward']>0?$v['pde_perforaward']:'';?></td>
        <td class="tc"><?php echo $v['pde_amount']>0?$v['pde_amount']:'';?></td>
        <td><?php echo date("Y-m-d",$v['pde_add_time']);?></td>
        <td>
          <a href="javascript:void(0)" im_type="dialog" dialog_title="查看佣金明细" dialog_id="my_balance_detail" dialog_width="900" uri="<?php echo urlShop('member_extension', 'my_balance_detail',array('sn'=>$v['pde_sn'],'id'=>$v['pde_member_id']));?>" title="查看佣金明细">明细</a>
        </td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span>暂无结算记录！</span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (count($output['balance_list'])>0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['page']; ?></div></td>
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
	    	window.location.href = 'index.php?act=member_extension&op=my_commission&type=today';
		})
	    $('#week_flow').click(function(){
	    	window.location.href = 'index.php?act=member_extension&op=my_commission&type=week';
		})
		$('#month_flow').click(function(){
	    	window.location.href = 'index.php?act=member_extension&op=my_commission&type=month';
		})
		$('#year_flow').click(function(){
	    	window.location.href = 'index.php?act=member_extension&op=my_commission&type=year';
		})
	});
</script>