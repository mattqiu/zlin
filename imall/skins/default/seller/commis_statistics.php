<?php defined('InIMall') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="statistics_commis" />
    <input type="hidden" name="op" value="<?php echo $output['op_key'];?>" />
    <tr>
      <td>
        <a href="javascript:void(0);" class="imsc-btn-mini" id="today_flow">今日统计</a>
        <a href="javascript:void(0);" class="imsc-btn-mini" id="week_flow">本周统计</a>
        <a href="javascript:void(0);" class="imsc-btn-mini" id="month_flow">本月统计</a>
        <a href="javascript:void(0);" class="imsc-btn-mini" id="year_flow">今年统计</a>
      </td>
      <th><?php echo $lang['stat_time_search'];?></th>
      <td class="w240">
        <input type="text" class="text w70" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>&nbsp;&#8211;&nbsp;
        <input type="text" class="text w70" id="add_time_to" name="add_time_to" value="<?php echo $_GET['add_time_to']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>
      </td>
      <td class="w70">
        <select name="give_status">
          <option value="0" <?php if ($_GET['give_status'] == 0) {?>selected="selected"<?php }?>>未结算</option>
          <option value="1" <?php if ($_GET['give_status'] == 1) {?>selected="selected"<?php }?>>已结算</option>          
        </select>
      </td>
      <th>名称查询</th>
      <td class="w100">
        <input type="text" class="text w70" name="saleman_name" id="saleman_name" value="<?php echo $_GET['saleman_name']; ?>" />
      </td>
      <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['im_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<!-- JS统计表 -->
<div class="title" style="text-align:center; margin:10px;">
  <p style="font-size:16px; color:#3e576f; font-weight:bold;"><?php echo $output['main_title'];?></p>
  <p style="font-size:12px; color:#6da9d0; "><?php echo $output['sub_title'];?></p>
</div>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w10"></th>
      <th>序号</th>
      <th>名称</th>
      <th>真实姓名</th>      
      <th>佣金</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['flow_list']) && is_array($output['flow_list'])) { ?>
    <?php foreach($output['flow_list'] as $vk=>$v) { ?>
    <tr class="bd-line">
      <td></td>
      <td><?php echo $vk+1;?></td>
      <td><a href="<?php echo urlShop('member_snshome', 'index',array('mid'=>$v['saleman_id']));?>" target="_blank"><?php echo $v['member_name'];?></a></td>      
      <td><a href="<?php echo urlShop('member_snshome', 'index',array('mid'=>$v['saleman_id']));?>" target="_blank"><?php echo $v['member_truename'];?></a></td>
      <td><?php echo $v['sum'];?></td>
    </tr>    
    <?php }?>
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
    </tr>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无抽佣明细</span></div></td>
    </tr>    
    <?php } ?>
  </tbody>
  <tfoot>
    <tr class="tfoot">
      <td colspan="15"></td>
    </tr>
  </tfoot>
</table>


<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 
<script type="text/javascript">
	$(function(){
	    $('#add_time_from').datepicker({dateFormat: 'yymmdd'});
	    $('#add_time_to').datepicker({dateFormat: 'yymmdd'});
		
		$('#today_flow').click(function(){
	    	window.location.href = 'index.php?act=statistics_commis&op=<?php echo $output['op_key'];?>&type=today';
		})
	    $('#week_flow').click(function(){
	    	window.location.href = 'index.php?act=statistics_commis&op=<?php echo $output['op_key'];?>&type=week';
		})
		$('#month_flow').click(function(){
	    	window.location.href = 'index.php?act=statistics_commis&op=<?php echo $output['op_key'];?>&type=month';
		})
		$('#year_flow').click(function(){
	    	window.location.href = 'index.php?act=statistics_commis&op=<?php echo $output['op_key'];?>&type=year';
		})
	});
</script>
