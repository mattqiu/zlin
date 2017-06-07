<?php defined('InIMall') or exit('Access Invalid!');?>

<script type="text/javascript">
$(document).ready(function(){
    $('#add_time_from').datepicker();
    $('#add_time_to').datepicker();
});
</script>
<div class="tabmenu">
    <ul class="tab pngFix">
    <li class="normal"><a href="index.php?act=supplier_predeposit&amp;op=pd_log">账户日志</a></li>
  	<li class="normal"><a href="index.php?act=supplier_predeposit&amp;op=index">申请提现</a></li>
  	<li class="active"><a href="javascript:void(0);">提现列表</a></li>
  	<li class="normal"><a href="index.php?act=supplier_predeposit&amp;op=pd_psw">提现密码设置</a></li>
  	</ul>
</div>

 <div class="alert"><span class="mr30">可用金额：<strong class="mr5 red" style="font-size: 18px;">0.00</strong>元</span><span>冻结金额：<strong class="mr5 blue" style="font-size: 18px;">0.00</strong>元</span></div>
  <form method="get" action="index.php">
    <table class="ncm-search-table">
      <input type="hidden" name="act" value="seller_predeposit">
      <input type="hidden" name="op" value="pd_cash_list">
      <tbody><tr>
      <th></th><td></td>
        <th>状态：</th>
        <td class="w90"><select id="paystate_search" name="paystate_search">
            <option value="0">请选择...</option>
            <option value="0">未支付</option>
            <option value="1">已支付</option>
          </select>
       </td>
        <th>申请单号</th>
        <td class="w160 tc"><input type="text" class="text w150" name="sn_search" value=""></td>
        <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="搜索"></label></td>
      </tr>
    </tbody></table>
  </form>
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th>申请单号</th>
        <th>申请时间</th>
        <th>提现金额(元)</th>
        <th class="w150">状态</th>
        <th class="w100">操作</th>
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
