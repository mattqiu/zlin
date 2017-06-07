<?php defined('InIMall') or exit('Access Invalid!');?>
<div class="eject_con">
  <table class="imsc-default-table">
    <thead>
      <tr im_type="table_header">
        <th class="w10">&nbsp;</th>
        <th class="w50">&nbsp;</th>
        <th coltype="editable" column="goods_name" checker="check_required" inputwidth="230px">商品名称</th>
        <th class="w80">单价</th>
        <th class="w80">进价</th>
        <th class="w80">库存</th>
        <th class="w100">补货数量</th>
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
        <td><span><?php echo $lang['currency'].$val['goods_price'];?></span></td>
        <td><span><?php echo $lang['currency'].$val['goods_costprice'];?></span></td>
        <td>
          <span <?php if ($val['goods_storage']<$val['goods_storage_alarm']){echo 'style="color:red;"';}?>><?php echo $val['goods_storage'].$lang['piece']; ?></span>
        </td>
        <td class="goods-time">
          <div class="goods-storage">
            <input type="text" class="text w40" id="nums_item_<?php echo $val['goods_id'];?>" b-good-id="<?php echo $val['goods_id'];?>" p-good-id="<?php echo $val['src_id'];?>" name="replenish_num" value="" />
          </div>		
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
    <a href="javascript:void(0);" class="imsc-btn imsc-btn-red mt10 mb10" name="replenishment" imtype="goods_replenish"><i class="fa fa-plus-square"></i>申请补货</a>
  </div>
</div>
<script>
$(function(){
    $('a[imtype="goods_replenish"]').click(function(){
		var _items = "";
		$('input[name="replenish_num"]').each(function(){
			if ($(this).val()!='' && parseInt($(this).val())>0){
				_items += $(this).attr("p-good-id") + '|' + $(this).val() +'|' + $(this).attr("b-good-id") + ',';
			}
		});
		_items = _items.substr(0, (_items.length - 1));
		if (_items ==""){
			showError('请输入商品补货数量！呵呵');
            return false;
		}
		ajax_get_confirm('确定要申请补货吗?','<?php echo urlShop('store_goods_online', 'replenish_save',array('pst_id'=>$_SESSION['S_parent_id']));?>&data=' + _items);
    });
});
</script>