<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>  
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="seller_branch_balance" />
    <input type="hidden" name="op" value="index" />
    <tr>      
      <td></td>

      <th class="w30">名称</th>
      <td class="w80">
        <input type="text" class="text w70" name="branch_name" id="branch_name" value="<?php echo $_GET['branch_name']; ?>" />
      </td>
      <td class="w60 tc"><label class="submit-border"><input type="submit" class="submit" value="查询" /></label></td>
    </tr>
  </table>
</form>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w30"><input id="all" type="checkbox" class="checkall" /></th>
      <th class="w150">分店代码</th>
      <th class="w100">分店名称</th>
      <th class="w80">补货合计</th>
      <th class="w80">运费</th>
      <th class="w80">退货合计</th>
      <th class="w80">结算金额</th>
      <th class="w150">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['flow_list']) && is_array($output['flow_list'])) { $i=0;?>
    <?php foreach($output['flow_list'] as $v) { ++$i;?>
    <tr class="bd-line">
      <td class="tc"><input type="checkbox" class="checkitem" value="<?php echo $v['branch_id']; ?>" /></td>
      <td><?php echo $v['branch_id'];?></td>
      <td><?php echo $v['branch_name'];?></td>
      <td><?php echo $v['goods_totals'];?></td>
      <td><?php echo $v['shipping_totals'];?></td>
      <td><?php echo $v['order_totals'];?></td>      
      <td><?php echo $v['share_totals'];?></td>
      <td>
        <a href="javascript:void(0)" im_type="dialog" dialog_title="查看订单明细" dialog_id="my_order_info" dialog_width="480" uri="<?php echo urlShop('seller_branch_balance', 'bill_info',array('branch_id'=>$v['branch_id']));?>" title="查看订单明细">查看</a>|
        <a href="javascript:void(0)" im_type="dialog" dialog_title="分销结算" dialog_id="balance_dialog" dialog_width="480" uri="<?php echo urlShop('seller_branch_balance', 'balance_edit',array('id'=>$v['branch_id']));?>" title="分销结算">结算</a>
      </td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无待结算分店</span></div></td>
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
        	    showDialog('请选择需要结算的分店！');
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
        var url = 'index.php?act=seller_branch_balance&op=balance_edit'+$id;		
        CUR_DIALOG = ajax_form('balance_dialog', '分销结算', url, 480,0);
        return false;		
    }
</script>