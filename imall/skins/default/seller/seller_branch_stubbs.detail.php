<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="seller_branch_stubbs" />
    <input type="hidden" name="op" value="goodsdetail" />
    <tr>
      <td>&nbsp;</td>
      <th><?php echo $lang['store_goods_index_store_goods_class'];?></th>
      <td class="w160">
        <select name="stc_id" class="w150">
          <option value="0"><?php echo $lang['im_please_choose'];?></option>
          <?php if(is_array($output['store_goods_class']) && !empty($output['store_goods_class'])){?>
          <?php foreach ($output['store_goods_class'] as $val) {?>
          <option value="<?php echo $val['stc_id']; ?>" <?php if ($_GET['stc_id'] == $val['stc_id']){ echo 'selected=selected';}?>><?php echo $val['stc_name']; ?></option>
          <?php if (is_array($val['child']) && count($val['child'])>0){?>
          <?php foreach ($val['child'] as $child_val){?>
          <option value="<?php echo $child_val['stc_id']; ?>" <?php if ($_GET['stc_id'] == $child_val['stc_id']){ echo 'selected=selected';}?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
          <?php }?>
          <?php }?>
          <?php }?>
          <?php }?>
        </select>
      </td>
      <th>调拔分店</th>
      <td class="w160">
        <select name="buyer_id" class="w150">
          <option value="0"><?php echo $lang['im_please_choose'];?></option>
          <?php if(is_array($output['branch_list']) && !empty($output['branch_list'])){?>
          <?php foreach ($output['branch_list'] as $val) {?>
          <option value="<?php echo $val['store_id']; ?>" <?php if ($_GET['buyer_id'] == $val['store_id']){ echo 'selected=selected';}?>><?php echo $val['store_name']; ?></option>
          <?php }?>
          <?php }?>
        </select>
      </td>
      <th>
        <select name="search_type">
          <option value="0" <?php if ($_GET['search_type'] == 0) {?>selected="selected"<?php }?>><?php echo $lang['store_goods_index_goods_name'];?></option>
          <option value="1" <?php if ($_GET['search_type'] == 1) {?>selected="selected"<?php }?>><?php echo $lang['store_goods_index_goods_no'];?></option>
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
    <tr im_type="table_header">
      <th class="w30">&nbsp;</th>
      <th class="w50">&nbsp;</th>
      <th coltype="editable" column="goods_name" checker="check_required" inputwidth="230px"><?php echo $lang['store_goods_index_goods_name'];?></th>
      <th class="w100">单价</th>
      <th class="w100">调拔价</th>
      <th class="w100">数量</th>
      <th class="w100">调拔时间</th>
      <th class="w150">收货分店</th>
      <th class="w50"><?php echo $lang['im_handle'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['stubbs_list'])) { ?>
    <?php foreach ($output['stubbs_list'] as $val) { ?>
    <tr>
      <td class="trigger"></td>
      <td>
        <div class="pic-thumb">
          <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>" target="_blank"><img src="<?php echo thumb($val, 60);?>"/></a>
        </div>
      </td>
      <td class="tl">
        <dl class="goods-name">
          <dt><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>" target="_blank"><?php echo $val['goods_name']; ?></a></dt>
        </dl>
      </td>
      <td><span><?php echo $lang['currency'].$val['goods_price']; ?></span></td>
      <td><span><?php echo $lang['currency'].$val['goods_tradeprice']; ?></span></td>
      <td><?php echo $val['goods_num'].$lang['piece']; ?></span></td>
      <td class="goods-time"><?php echo @date('Y-m-d',$val['stubbs_time']);?></td>
      <td><span><?php echo $val['branch_name']; ?></span></td>
      <td class="nscs-table-handle">
        <span>
          <a href="javascript:void(0);" onclick="ajax_get_confirm('<?php echo $lang['im_ensure_del'];?>', '<?php echo urlShop('seller_branch_stubbs', 'drop_goods', array('bs_id' => $val['bs_id']));?>');" class="btn-red"><i class="fa fa-trash-o"></i><p><?php echo $lang['im_del'];?></p></a>
        </span>
      </td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php  if (!empty($output['stubbs_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>