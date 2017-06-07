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
      <li>查看和管理平台推广待结算佣金明细。</li>
    </ul>
  </div>  

  <div class="fixed-empty"></div>
  <form method="get" action="index.php">
    <input type="hidden" name="act" value="extension_commisputout" />
    <input type="hidden" name="op" value="balance_list" />
    <table class="tb-type1 noborder search">
      <tbody>    
        <tr>
          <td>
            <a href="javascript:void(0);" class="imap-btn imap-btn-green" id="today_flow"><span>今日结算</span></a>
            <a href="javascript:void(0);" class="imap-btn imap-btn-green" id="week_flow"><span>本周结算</span></a>
            <a href="javascript:void(0);" class="imap-btn imap-btn-green" id="month_flow"><span>本月结算</span></a>
            <a href="javascript:void(0);" class="imap-btn imap-btn-green" id="year_flow"><span>今年结算</span></a>
          </td>
          <th class="w30">时段</th>
          <td class="w240">
            <input type="text" class="text w70" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>&nbsp;&#8211;&nbsp;
            <input type="text" class="text w70" id="add_time_to" name="add_time_to" value="<?php echo $_GET['add_time_to']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>
          </td>
          <th class="w30">名称</th>
          <td class="w80">
            <input type="text" class="text w70" name="saleman_name" id="saleman_name" value="<?php echo $_GET['saleman_name']; ?>" />
          </td>
          <td class="w60 tc"><label class="submit-border"><input type="submit" class="submit" value="查询" /></label></td>
        </tr>
      </tbody>
    </table>
  </form>
  <div class="title" style="text-align:center; margin:10px;">
    <p style="font-size:16px; color:#3e576f; font-weight:bold;"><?php echo $output['main_title'];?></p>
    <p style="font-size:12px; color:#6da9d0; "><?php echo $output['sub_title'];?></p>
  </div>
  <table class="table tb-type2">
    <thead>
      <tr>
        <th class="w100 tl">交易号</th>
        <th class="w100 tl">推广帐号</th>
        <th class="w80 tc">类型</th>
        <th class="w80 tc">推广佣金</th>
        <th class="w80 tc">高管奖励</th>
        <th class="w80 tc">门店补贴</th>
        <th class="w80 tc">结算金额</th>
        <th class="w60 tc">审核</th>
        <th class="w100 tc">审核日期</th>      
        <th class="w100 tc">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['extcommis_list']) && is_array($output['extcommis_list'])) {?>
      <?php foreach($output['extcommis_list'] as $v) { ++$i;?>
      <tr>
        <td class="tl"><?php echo $v['pde_sn'];?></td>
        <td class="tl"><?php echo $v['pde_member_name'];?></td>
        <td class="tc">
		  <?php 
		    switch($v['pde_mc_id']) {
			  case 10:
				  $mc_name = '导购';
                  break;
			  case 5:
				  $mc_name = '股东';
                  break;
			  case 4:
				  $mc_name = '首席';
                  break;
			  case 3:
				  $mc_name = '协理';
                  break;
              case 2:
				  $mc_name = '经理';
                  break;
		      case 1:
				  $mc_name = '代理';
				  break;
			  default:
				  $mc_name = '导购';
                  break;			        
		    }
		    echo $mc_name;
		  ?>
        </td>
        <td class="tc"><?php echo $v['pde_commis']>0?$v['pde_commis']:'';?></td>
        <td class="tc"><?php echo $v['pde_manageaward']>0?$v['pde_manageaward']:'';?></td>
        <td class="tc"><?php echo $v['pde_perforaward']>0?$v['pde_perforaward']:'';?></td>
        <td class="tc"><?php echo $v['pde_amount']>0?$v['pde_amount']:'';?></td>
        <td class="tc"><?php echo $v['pde_admin'];?></td>
        <td class="tc"><?php echo date("Y-m-d",$v['pde_add_time']);?></td>
        <td class="tc">          
          <a href="javascript:void(0)" onclick="ajax_form('my_promotion_info','查看推广员信息','<?php echo urlAdminExtension('extension_commisputout','promotion_info',array('promotion_id'=>$v['pde_member_id']));?>',480);">查看</a>|
          <a href="<?php echo urlAdminExtension('extension_commisputout','extcommis_info',array('sn'=>$v['pde_sn'],'id'=>$v['pde_member_id']));?>" title="查看佣金明细">明细</a>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr class="no_data">
        <td colspan="15">暂无佣金结算信息</td>
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
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 
<script type="text/javascript">
	$(function(){
	    $('#add_time_from').datepicker({dateFormat: 'yymmdd'});
	    $('#add_time_to').datepicker({dateFormat: 'yymmdd'});
		
		$('#today_flow').click(function(){
	    	window.location.href = 'index.php?act=extension_commisputout&op=balance_list&type=today';
		})
	    $('#week_flow').click(function(){
	    	window.location.href = 'index.php?act=extension_commisputout&op=balance_list&type=week';
		})
		$('#month_flow').click(function(){
	    	window.location.href = 'index.php?act=extension_commisputout&op=balance_list&type=month';
		})
		$('#year_flow').click(function(){
	    	window.location.href = 'index.php?act=extension_commisputout&op=balance_list&type=year';
		})
	});
</script>