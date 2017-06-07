<?php defined('InIMall') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="store_sales_record" />
    <input type="hidden" name="op" value="index" />
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td><th>销售日期</th>
      <td class="w240"><input type="text" class="text w70" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>&nbsp;&#8211;&nbsp;<input id="add_time_to" type="text" class="text w70"  name="add_time_to" value="<?php echo $_GET['add_time_to']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label></td>

      <th>销售店铺</th>
      <td class="w160">
        <select name="sales_id" class="w150">
          <option value="0"><?php echo $lang['im_please_choose'];?></option>
          <option value="<?php echo $_SESSION['store_id']; ?>" <?php if ($_GET['sales_id'] == $_SESSION['store_id']){ echo 'selected=selected';}?>>总店</option>
          <?php if(is_array($output['branch_list']) && !empty($output['branch_list'])){?>
          <?php foreach ($output['branch_list'] as $val) {?>
          <option value="<?php echo $val['store_id']; ?>" <?php if ($_GET['sales_id'] == $val['store_id']){ echo 'selected=selected';}?>><?php echo $val['store_name']; ?></option>
          <?php }?>
          <?php }?>
        </select>
      </td>
      <th>
        <select name="search_type">
          <option value="0" <?php if ($_GET['search_type'] == 0) {?>selected="selected"<?php }?>>商品名称</option>
          <option value="1" <?php if ($_GET['search_type'] == 1) {?>selected="selected"<?php }?>>订单编号</option>
          <option value="2" <?php if ($_GET['search_type'] == 2) {?>selected="selected"<?php }?>>平台货号</option>
        </select>
      </th>
      <td class="w160"><input type="text" class="text w150" name="keyword" value="<?php echo $_GET['keyword']; ?>"/></td>
      <td class="tc w70"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['im_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w10"></th>
      <th colspan="2">商品/订单号/时间</th>
      <th class="w100">单价</th>
      <th class="w100">售价</th>
      <th class="w100">数量</th>
      <th class="w200">销售店铺</th>
    </tr>
  </thead>
  <?php if (is_array($output['sales_list']) && !empty($output['sales_list'])) { ?>
  <tbody>
    <?php foreach ($output['sales_list'] as $key => $val) { ?>
    <tr class="bd-line" >
      <td></td>
      <td class="w50"><div class="pic-thumb"><a href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id']));?>" target="_blank"><img src="<?php echo thumb($val,60);?>"/></a></div></td>
      <td class="tl" title="<?php echo $val['store_name']; ?>">
		<dl class="goods-name"><dt><a href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id']));?>" target="_blank"><?php echo $val['goods_name']; ?></a></dt>
        <dd><?php echo '订单号'.$lang['im_colon'];?><a href="index.php?act=store_order&op=show_order&order_id=<?php echo $val['order_id']; ?>" target="_blank"><?php echo $val['order_sn'];?></a></dd>
        <dd><?php echo '日期'.$lang['im_colon'];?><?php echo @date('Y-m-d H:i:s',$val['add_time']);?></dd>
        </dl>
      </td>
      <td><?php echo $lang['currency'];?><?php echo $val['goods_price'];?></td>
      <td><?php echo $lang['currency'];?><?php echo $val['goods_pay_price'];?></td>
      <td><?php echo $val['goods_num']; ?></td>
      <td><a href="<?php echo urlShop('show_store','index',array('store_id'=> $val['store_id']));?>" target="_blank"><?php echo $val['store_name']; ?></a></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle">&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (is_array($output['sales_list']) && !empty($output['sales_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script>
	$(function(){
	    $('#add_time_from').datepicker({dateFormat: 'yy-mm-dd'});
	    $('#add_time_to').datepicker({dateFormat: 'yy-mm-dd'});
	});
</script>
