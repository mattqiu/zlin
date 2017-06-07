<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>推广管理</h3>
        <h5>管理平台推广员及申请</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>推广员业绩明细。</li>
    </ul>
  </div>  
  
  <div class="fixed-empty"></div>
  <form method="get" action="index.php">
    <input type="hidden" name="act" value="extension_promotion" />
    <input type="hidden" name="op" value="promotion_detail" />
    <input type="hidden" name="promotion_id" value="<?php echo $output['promotion_id'];?>" />
    <table class="tb-type1 noborder search">
      <tbody>    
        <tr>
          <td>
            <a href="javascript:void(0);" class="imap-btn imap-btn-green" id="today_flow"><span>今日明细</span></a>
            <a href="javascript:void(0);" class="imap-btn imap-btn-green" id="week_flow"><span>本周明细</span></a>
            <a href="javascript:void(0);" class="imap-btn imap-btn-green" id="month_flow"><span>本月明细</span></a>
            <a href="javascript:void(0);" class="imap-btn imap-btn-green" id="year_flow"><span>今年明细</span></a>
          </td>
          <th class="w36">时段</th>
          <td class="w350">
            <input type="text" class="text w72" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>&nbsp;&#8211;&nbsp;
            <input type="text" class="text w72" id="add_time_to" name="add_time_to" value="<?php echo $_GET['add_time_to']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>
          </td>
          <td class="w80">
            <select name="give_status">
              <option value="3" <?php if (!isset($_GET['give_status'])) {?>selected="selected"<?php }?>>全部</option>
              <option value="1" <?php if (isset($_GET['give_status']) && $_GET['give_status'] == 1) {?>selected="selected"<?php }?>>已结算</option>
              <option value="0" <?php if (isset($_GET['give_status']) && $_GET['give_status'] == 0) {?>selected="selected"<?php }?>>未结算</option>
            </select>
          </td>
          <td class="w60 tc"><label class="submit-border"><input type="submit" class="submit" value="查询" /></label></td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w40">编号</th> 
        <th class="w100">推广员</th> 
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
        <td><?php echo $v['extension_name'];?><a href="<?php echo urlShop('member_snshome', 'index',array('mid'=>$v['extension_id']));?>" target="_blank">(查看)</a></td> 
        <td><?php echo $v['order_sn'];?><a href="<?php echo urlAdminshop('order', 'show_order',array('order_id'=>$v['order_id']));?>" target="_blank">(查看订单)</a></td>
        <td><?php echo $v['commis_amount'];?></td>
        <td><?php echo $v['commis_rate'];?></td>
        <td><?php echo $v['mb_commis_totals'];?></td>
        <td><?php echo date('Y-m-d',$v['add_time']);?></td>
        <td><?php echo ($v['give_status']==1)?'已结算':'未结算';?></td>
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
        <td colspan="10">
          <div class="pagination"><?php echo $output['show_page'];?></div>
        </td>
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
	    window.location.href = 'index.php?act=extension_promotion&op=promotion_detail&type=today&promotion_id=<?php echo $output['promotion_id'];?>';
	})
	$('#week_flow').click(function(){
	    window.location.href = 'index.php?act=extension_promotion&op=promotion_detail&type=week&promotion_id=<?php echo $output['promotion_id'];?>';
	})
	$('#month_flow').click(function(){
	    window.location.href = 'index.php?act=extension_promotion&op=promotion_detail&type=month&promotion_id=<?php echo $output['promotion_id'];?>';
	})
	$('#year_flow').click(function(){
	    window.location.href = 'index.php?act=extension_promotion&op=promotion_detail&type=year&promotion_id=<?php echo $output['promotion_id'];?>';
	})
});
</script>