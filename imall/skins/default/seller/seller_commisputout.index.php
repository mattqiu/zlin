<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>  
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="seller_commisputout" />
    <input type="hidden" name="op" value="index" />
    <tr>      
      <td></td>
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
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w30"><input id="all" type="checkbox" class="checkall" /></th>
      <th class="w150">推广帐号</th>
      <th class="w80">推广身份</th>
      <th class="w80">本期业绩</th>
      <th class="w80">推广佣金</th>
      <th class="w80">高管补贴</th>
      <th class="w80">门店补贴</th>
      <th class="w80">结算佣金</th>
      <th class="w80">升级计划</th>
      <th class="w150">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['flow_list']) && is_array($output['flow_list'])) { $i=0;?>
    <?php foreach($output['flow_list'] as $v) { ++$i;?>
    <tr class="bd-line">
      <td class="tc"><input type="checkbox" class="checkitem" value="<?php echo $v['saleman_id']; ?>" /></td>
      <td class="tl"><?php echo $v['member_name'];?></td>
      <td><?php echo $v['mc_name'];?></td>
      <td><?php echo $v['curr_sales'];?></td>     
      <td><?php echo $v['curr_commis'];?></td>
      <td><?php echo $v['award_manage_totals'];?></td>
      <td><?php echo $v['award_perfor_totals'];?></td>
      <td><?php echo $v['commis_totals'];?></td>
      <td>
        <?php if ($v['extension_upgrade_op']==1){;?>
        <i class="fa fa-arrow-up"></i>
		<?php echo $v['extension_upgrade_name'];?>
        <?php }?>
	  </td>           
      <td>
        <a href="javascript:void(0)" im_type="dialog" dialog_title="查看推广员信息" dialog_id="my_promotion_info" dialog_width="480" uri="<?php echo urlShop('seller_commisputout', 'promotion_info',array('promotion_id'=>$v['saleman_id']));?>" title="查看推广员信息">查看</a>|
        <a href="<?php echo urlShop('seller_commisputout', 'commisputout_detail',array('promotion_id'=>$v['saleman_id']));?>" title="查看业绩明细">明细</a>|
        <a href="javascript:void(0)" im_type="dialog" dialog_title="佣金结算" dialog_id="balance_dialog" dialog_width="480" uri="<?php echo urlShop('seller_commisputout', 'balance_edit',array('id'=>$v['saleman_id']));?>" title="佣金结算">结算</a>
      </td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无待结算佣金</span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr class="tfoot">
      <td colspan="10" style="text-align:left;">
        <a href="javascript:void(0)" onclick="balance_edit(1)" class="imsc-btn imsc-btn-acidblue m10"><i class="fa fa-th-large"></i>结算所选</a>
        <a href="javascript:void(0)" onclick="balance_edit(2)" class="imsc-btn imsc-btn-red m10"><i class="fa fa-th"></i>结算全部</a>
      </td>
    </tr>
    <tr class="tfoot">
      <td colspan="10">
        <div class="pagination"> <?php echo $output['show_page'];?> </div>
      </td>
    </tr>
  </tfoot>
</table>
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
        var url = 'index.php?act=seller_commisputout&op=balance_edit'+$id;		
        CUR_DIALOG = ajax_form('balance_dialog', '佣金结算', url, 480,0);
        return false;		
    }
</script>