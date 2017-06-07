<?php defined('InIMall') or exit('Access Invalid!');?>
<div class="eject_con">
  <table class="imsc-default-table">
    <thead>
      <tr im_type="table_header">
        <th class="w10">&nbsp;</th>
        <th class="w50">&nbsp;</th>
        <th coltype="editable" column="goods_name" checker="check_required" inputwidth="230px">商品名称</th>
        <th class="w60">单价</th>
        <th class="w60">批发价</th>
        <th class="w60">库存</th>
        <th class="w80">申请数量</th>
        <th class="w80">审核数量</th>
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
        <td><span><?php echo $lang['currency'].$val['goods_tradeprice'];?></span></td>
        <td><span><?php echo $val['goods_storage'].$lang['piece']; ?></span></td>
        <td>
          <span><?php echo $val['nums'].$lang['piece']; ?></span>
        </td>
        <td class="goods-time">
          <div class="goods-storage">
            <input type="text" class="text w40" id="nums_item_<?php echo $val['goods_id'];?>" p-good-id="<?php echo $val['goods_id'];?>" b-good-id="<?php echo $val['b_id'];?>" data-nums="<?php echo $val['goods_storage']; ?>" name="replenish_num" value="<?php echo $val['nums']; ?>" onkeyup="change_nums(<?php echo $val['goods_id'];?>, this);"/>
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
    <a href="javascript:void(0);" class="imsc-btn imsc-btn-green mt10 mb10" name="replenish_verify" imtype="verify_replenish" op_id="50"><i class="fa fa-home"></i>同意(已发货)</a>
    <a href="javascript:void(0);" class="imsc-btn imsc-btn-acidblue mt10 mb10" name="replenish_verify" imtype="verify_replenish" op_id="20"><i class="fa fa-truck"></i>同意(待发货)</a>
    <a href="javascript:void(0);" class="imsc-btn imsc-btn-black mt10 mb10" name="replenish_verify" imtype="verify_replenish" op_id="10"><i class="fa fa-thumbs-down"></i>拒绝</a>
    <a href="javascript:void(0);" class="imsc-btn imsc-btn-orange mt10 mb10" name="replenish_verify" imtype="verify_replenish" op_id="0"><i class="fa fa-reply-all"></i>关闭</a>
  </div>
</div>
<script>
$(function(){
    $('a[imtype="verify_replenish"]').click(function(){
		var _op_id = parseInt($(this).attr("op_id"));
		if (_op_id == 0){
		    DialogManager.close('my_apply_edit');
			return false;
		}
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
		var msg = '';
		if (_op_id ==10){
			msg = '拒绝';
		}else{
			msg = '同意';
		}
		ajax_get_confirm('确定'+msg+'分店补货吗?','<?php echo urlShop('seller_branch_stubbs', 'replenish_save',array('bp_id'=>$output['apply_info']['bp_id']));?>&data=' + _items +'&op_id=' + _op_id);
    });
});

function change_nums(id, input){
    var goods_id = id;
	var goods_storae = $('#nums_item_' + id).attr("data-nums");
	var goods_num = input.value;
	if (goods_num>goods_storae){
		$('#nums_item_' + id).val(goods_storae);
		showError('补货数量不能超过库存数量！呵呵');
        return false;
	}
}
</script>