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
      <li>查看和管理平台推广已结算佣金明细。</li>
    </ul>
  </div>
  
  <div class="fixed-empty"></div>
  <form method="get" action="index.php">
    <input type="hidden" name="act" value="extension_commisputout" />
    <input type="hidden" name="op" value="index" />
    <table class="tb-type1 noborder search">
      <tbody>    
        <tr>       
          <th class="w30">名称</th>
          <td class="w80">
            <input type="text" class="text w70" name="saleman_name" id="saleman_name" value="<?php echo $_GET['saleman_name']; ?>" />
          </td>
          <td class="w60 tc"><label class="submit-border"><input type="submit" class="submit" value="查询" /></label></td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2">
    <thead>
      <tr>
        <th class="w30 tc"><input id="all" type="checkbox" class="checkall" /></th>
        <th class="w150">推广帐号</th>
        <th class="w80 tc">推广身份</th>
        <th class="w80 tc">本期业绩</th>
        <th class="w80 tc">推广佣金</th>
        <th class="w80 tc">高管补贴</th>
        <th class="w80 tc">门店补贴</th>
        <th class="w80 tc">结算佣金</th>
        <th class="w80 tc">升级计划</th>
        <th class="w150 tc">操作</th>
      </tr>
    </thead>
    <tbody>    
      <?php if (!empty($output['flow_list']) && is_array($output['flow_list'])) { $i=0;?>
      <?php foreach($output['flow_list'] as $v) { ++$i;?>
      <tr class="bd-line">
        <td class="tc"><input type="checkbox" class="checkitem" value="<?php echo $v['saleman_id']; ?>" /></td>
        <td class="tl"><?php echo $v['member_name'];?></td>
        <td class="tc"><?php echo $v['mc_name'];?></td>
        <td class="tc"><?php echo $v['curr_sales'];?></td>
        <td class="tc"><?php echo $v['curr_commis'];?></td>   
        <td class="tc"><?php echo $v['award_manage_totals'];?></td>   
        <td class="tc"><?php echo $v['award_perfor_totals'];?></td>        
        <td class="tc"><?php echo $v['commis_totals'];?></td>
        <td class="tc">
          <?php if ($v['extension_upgrade_op']==1){;?>
          <i class="fa fa-arrow-up"></i>
		  <?php echo $v['extension_upgrade_name'];?>
          <?php }?>
        </td>
        <td class="tc">
          <a href="javascript:void(0)" onclick="ajax_form('my_promotion_info','查看推广员信息','<?php echo urlAdminExtension('extension_commisputout','promotion_info',array('promotion_id'=>$v['saleman_id']));?>',480);">查看</a>|
          <a href="<?php echo urlAdminExtension('extension_commisputout', 'commisputout_detail',array('promotion_id'=>$v['saleman_id']));?>" title="查看业绩明细">明细</a>|
          <a href="javascript:void(0)" onclick="ajax_form('balance_dialog','佣金结算','<?php echo urlAdminExtension('extension_commisputout','balance_edit',array('id'=>$v['saleman_id']));?>',480);">结算</a>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr class="no_data">
        <td colspan="15">暂无待结算佣金</td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="10" style="text-align:left;">
          <a href="javascript:void(0)" onclick="balance_edit(1)" class="btns m10"><span><i class="fa fa-th-large"></i>结算所选</span></a>
          <a href="javascript:void(0)" onclick="balance_edit(2)" class="btns m10"><span><i class="fa fa-th"></i>结算全部</span></a>
        </td>
      </tr>
      <tr class="tfoot">
        <td colspan="10">
          <div class="pagination"> <?php echo $output['show_page'];?> </div>
        </td>
      </tr>
    </tfoot>
  </table>
</div>
<script language="javascript">
    function balance_edit(btype){
		var $id = '';
		if (btype == 1){
			/* 是否有选择 */
            if($('.checkitem:checked').length == 0){    //没有选择
        	    showDialog('请选择需要结算的推广帐号！');
                return false;
            }
			/* 获取选中的项 */
	        var items = '';
	        $('.checkitem:checked').each(function(){
	            items += this.value + ',';
	        });
	        items = items.substr(0, (items.length - 1));
			
			$id = '&id='+items;
		}
        var url = 'index.php?act=extension_commisputout&op=balance_edit'+$id;		
        CUR_DIALOG = ajax_form('balance_dialog', '佣金结算', url, 480,0);
        return false;		
    }
</script>