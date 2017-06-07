<?php defined('InIMall') or exit('Access Invalid!');?>

<script type="text/javascript">
$(document).ready(function(){
    $('#add_time_from').datepicker();
    $('#add_time_to').datepicker();
});
</script>
<div class="tabmenu">
    <ul class="tab pngFix">
  	<li class="active"><a href="javascript:void(0);">账户日志</a></li>
  	<li class="normal"><a href="index.php?act=supplier_predeposit&amp;op=index">申请提现</a></li>
  	<li class="normal"><a href="index.php?act=supplier_predeposit&amp;op=pd_cash_list">提现列表</a></li>
  	<li class="normal"><a href="index.php?act=supplier_predeposit&amp;op=pd_psw">提现密码设置</a></li>
  	</ul>
</div>
<form method="get">
  <input type="hidden" name="act" value="supplier_predeposit">
  <input type="hidden" name="op" value="pd_log">
  <table class="search-form">
    <tbody><tr>
      <td>&nbsp;</td>
      <th>日志内容</th>
      <td class="w160"><input type="text" class="text w150" name="log_content" value=""></td>
      <th>时间</th>
      <td class="w240"><input name="add_time_from" id="add_time_from" type="text" class="text w70 hasDatepicker" value="" readonly="readonly"><label class="add-on"><i class="icon-calendar"></i></label>&nbsp;–&nbsp;<input name="add_time_to" id="add_time_to" type="text" class="text w70 hasDatepicker" value="" readonly="readonly"><label class="add-on"><i class="icon-calendar"></i></label></td>     
      <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="搜索"></label></td>
    </tr>
  </tbody></table>
</form>
<table class="ncsc-default-table">
  <thead>
    <tr>
      <th class="w100">类型</th>
      <th class="tl">日志内容</th>
      <th class="w80">积分变更</th>
      <th class="w110">积分冻结变更</th>
      <th class="w130">时间</th>
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
