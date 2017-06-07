<?php defined('InIMall') or exit('Access Invalid!');?>

<script type="text/javascript">
$(document).ready(function(){
    $('#add_time_from').datepicker();
    $('#add_time_to').datepicker();
});
</script>
<div class="tabmenu">
    <ul class="tab pngFix">
    <li class="active"><a href="index.php?act=supplier_distribution&amp;lock=1">员工列表</a></li>
  	<li class="normal"><a href="index.php?act=supplier_distribution&amp;lock=2">员工审核</a></li>
  	</ul>
</div>
<!--<form method="get" action="index.php">
    <input type="hidden" name="act" value="supplier_refund" />
    <input type="hidden" name="lock" value="" />
    <table class="search-form">
        <tr>
            <td>&nbsp;</td>
            <th></th>
            <td class="w240"><input name="add_time_from" id="add_time_from" type="text" class="text w70" value="" /><label class="add-on"><i class="icon-calendar"></i></label> &#8211; <input name="add_time_to" id="add_time_to" type="text" class="text w70" value="" /><label class="add-on"><i class="icon-calendar"></i></label></td>
            <th class="w60">处理状态</th>
            <td class="w80"><select name="state">
                    <option value="" selected>全部</option>
                    <option value="1" selected></option>
                    <option value="2" selected></option>
                    <option value="3" selected></option>
                </select></td>
            <th class="w120"><select name="type">
                    <option value="order_sn" selected></option>
                    <option value="refund_sn" selected></option>
                    <option value="buyer_name" selected></option>
                </select></th>
            <td class="w160"><input type="text" class="text" name="key" value="" /></td>

            <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="" /></label></td>
        </tr>
    </table>
</form>-->
<table class="ncsc-default-table">
    <thead>
    <tr>
        <th class="w120">店铺名</th>
        <th class="w70">真实姓名</th>
        <th class="w80">申请时间</th>
        <th class="w80">审核时间</th>
        <th class="w40">是否停用</th>
        <th class="w40">操作</th>
    </tr>
    </thead>
    <tbody>
      <tr>
      <td colspan="20" class="norecord">
      	<div class="warning-option"><i class="icon-warning-sign"></i><span>暂无符合条件的数据记录</span></div>
      </td>
    </tr>
      </tbody>
  <tfoot>
    <?php if (!empty($output['pdlog_list'])) { ?>
    <tr>
      <th class="tc"><input type="checkbox" id="all" class="checkall"/></th>
      <th colspan="10"><label for="all" ><?php echo $lang['im_select_all'];?></label>
        <a href="javascript:void(0);" im_type="batchbutton" uri="<?php echo urlShop('supplier_predeposit', 'drop_plate');?>" name="p_id" confirm="<?php echo $lang['im_ensure_del'];?>" class="ncbtn-mini"><i class="icon-trash"></i><?php echo $lang['im_del'];?></a>
       </th>
    </tr>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
