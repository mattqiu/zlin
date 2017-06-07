<?php defined('InIMall') or exit('Access Invalid!');?>
<div class="eject_con">
  <table class="imsc-default-table">
    <thead>
      <tr im_type="table_header">
        <th class="w10">&nbsp;</th>
        <th class="w50">&nbsp;</th>
        <th coltype="editable" column="goods_name" checker="check_required" inputwidth="230px">商品名称</th>
        <th class="w80">批发价</th>
        <th class="w100">申请分店</th>
        <th class="w80">申请退货数量</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['goods_list'])) { ?>
      <?php foreach ($output['goods_list'] as $val) { ?>
      <tr>
        <td>&nbsp;</td>
        <td>
          <div class="pic-thumb">
            <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>" target="_blank">
            <img src="<?php echo thumb($val, 60);?>"/>
            </a>
          </div>
        </td>
        <td class="tl">
          <ul class="goods-name">
            <li style="max-width: 450px !important;">
              <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>" target="_blank"><?php echo $val['goods_name'];?></a>
            </li>          
          </ul>
        </td>
        <td><span><?php echo $lang['currency'].$val['goods_tradeprice'];?></span></td>
        <td><span><?php echo $output['apply_info']['bp_branch_name'];?></span></td>
        <td>
          <span><?php echo $val['nums'].$lang['piece']; ?></span>
        </td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <div class="bottom">
    <a href="javascript:void(0);" class="imsc-btn imsc-btn-green mt10 mb10" name="returned" imtype="goods_returned"><i class="fa fa-reply-all"></i>关闭</a>
  </div>
</div>
<script>
$(function(){
    $('a[imtype="goods_returned"]').click(function(){
		DialogManager.close('my_apply_info');
    });
});
</script>