<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="seller_branch_synchro" />
    <input type="hidden" name="op" value="index" />
    <tr>
      <td>&nbsp;</td>
      <th><?php echo $lang['store_goods_index_store_goods_class'];?></th>
      <td class="w160"><select name="stc_id" class="w150">
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
        </select></td>
      <th>
        <select name="search_type">
          <option value="0" <?php if ($_GET['type'] == 0) {?>selected="selected"<?php }?>><?php echo $lang['store_goods_index_goods_name'];?></option>
          <option value="1" <?php if ($_GET['type'] == 1) {?>selected="selected"<?php }?>><?php echo $lang['store_goods_index_goods_no'];?></option>
          <option value="2" <?php if ($_GET['type'] == 2) {?>selected="selected"<?php }?>>平台货号</option>
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
      <th class="w600">选&nbsp;择&nbsp;商&nbsp;品</th>
      <th class="w10">&nbsp;</th>
      <th class="w250">选&nbsp;择&nbsp;分&nbsp;店</th>
      <th class="w150">操&nbsp;&nbsp;作</th>
    </tr>
  </thead>
  <tr style="float:left; ">
  	<td>
  		<input type="checkbox" id="all" class="checkall">全选
  	</td>
  </tr>
  <tr>
    <td>
      <table class="imsc-table-style" style="width:600px">        
        <tbody>
          <?php if (!empty($output['goods_list'])) { ?>
          <?php foreach ($output['goods_list'] as $val) { ?>
          <tr>
            <th class="tc"><input type="checkbox" class="checkitem tc" <?php if ($val['goods_lock'] == 1) {?>disabled="disabled"<?php }?> value="<?php echo $val['goods_commonid']; ?>"/></th>
            <th colspan="20">平台货号：<?php echo $val['goods_commonid'];?></th>
          </tr>
          <tr>
            <td class="trigger"><i class="tip fa fa-plus-circle" imtype="ajaxGoodsList" data-comminid="<?php echo $val['goods_commonid'];?>" title="点击展开查看此商品全部规格；规格值过多时请横向拖动区域内的滚动条进行浏览。"></i></td>
            <td>
              <div class="pic-thumb">
                <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['storage_array'][$val['goods_commonid']]['goods_id']));?>" target="_blank">
                <img src="<?php echo thumb($val, 60);?>"/>
                </a>
              </div>
            </td>
            <td class="tl">
              <dl class="goods-name">
                <dt>
				  <?php if ($val['goods_commend']) { echo '<span>荐</span>';}?>
                  <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $output['storage_array'][$val['goods_commonid']]['goods_id']));?>" target="_blank"><?php echo $val['goods_name']; ?></a>
                </dt>
                <dd><?php echo $val['gc_name']; ?></dd>
                <dd><?php echo $lang['store_goods_index_goods_no'].$lang['im_colon'];?><?php echo $val['goods_serial'];?></dd>
              </dl>
            </td>
          </tr>
          <tr style="display:none;"><td colspan="20"><div class="imsc-goods-sku ps-container" style="width:600px;"></div></td></tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span><?php echo $lang['no_record'];?></span></div></td>
          </tr>
          <?php } ?>
        </tbody>
        <tfoot>
          <?php  if (!empty($output['goods_list'])) { ?>
          <tr>
            <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
          </tr>
          <?php } ?>
        </tfoot>
      </table>
    </td>
    <td class="goods_stubbs"></td>
    <td class="stubbs_store_td">
      <?php if (!empty($output['branch_list']) && is_array($output['branch_list'])) { $i=0;?>
      <ul class="stubbs_store_list">
      <?php foreach($output['branch_list'] as $v) { ++$i;?>
        <li>
          <div><input type="checkbox" class="storeitem tc" value="<?php echo $v['store_id'];?>" /><?php echo ' '.$v['store_name'];?></div>
        </li>
      <?php }?>
      </ul>
      <?php }?>
    </td>
    <td>
      <a href="javascript:void(0);" imtype="synchro_goods" class="imsc-btn imsc-btn-green"><i class="fa fa-refresh"></i>开始同步</a>
    </td>
  </tr>
</table>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script> 
<script>
$(function(){
	/* 选择调拔商品时 */
    $('.checkitem').click(function(){
        
		$('i[data-comminid='+this.value+']').click();
        if (this.checked){			
        	//alert($(this).val());
        	$(".goodsitem:checkbox").attr("checked", true);
		}else{
			//必须加上 取消商品同步的动作
			$('li[name="goods_'+$(this).val()+'"]').attr('data','');
		}
    });
	
	// ajax获取商品列表
    $('i[imtype="ajaxGoodsList"]').toggle(
        function(){
            $(this).removeClass('fa fa-plus-circle').addClass('fa fa-minus-circle');
            var _parenttr = $(this).parents('tr');
            var _commonid = $(this).attr('data-comminid');
            var _div = _parenttr.next().find('.imsc-goods-sku');
            if (_div.html() == '') {
                $.getJSON('index.php?act=seller_branch_synchro&op=get_goods_list_ajax' , {commonid : _commonid}, function(date){
                    if (date != 'false') {
                        var _ul = $('<ul class="imsc-goods-sku-list"></ul>');
                        $.each(date, function(i, o){
                            $('<li name="goods_' +_commonid+'" id="goods_' + o.goods_id +'" data=""><div class="goods-thumb" title="商家货号：' + o.goods_serial + '"><a href="' + o.url + '" target="_blank"><image src="' + o.goods_image + '" ></a></div>' + o.goods_spec + '<div class="goods-price">价格：<em title="￥' + o.goods_price + '">￥' + o.goods_price + '</em></div><div class="goods-storage" ' + o.alarm + '>库存：<em title="' + o.goods_storage + '" ' + o.alarm + '>' + o.goods_storage + '</em></div><div class="goods-storage"><input type="checkbox" class="goodsitem" onchange="change_states('+o.goods_id+', this);"/></div></li>').appendTo(_ul);
                        });
                        _ul.appendTo(_div);
                        _parenttr.next().show();
                        _div.perfectScrollbar();
                    }
                });
            } else {
            	_parenttr.next().show()
            }
        },
        function(){
            $(this).removeClass('fa fa-minus-circle').addClass('fa fa-plus-circle');
            $(this).parents('tr').next().hide();
        }
    );  
	
    //Ajax提示
    $('.tip').poshytip({
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'center',
        alignY: 'top',
        offsetY: 5,
        allowTipHover: false
    });
    $(".checkall").click(function(){
    	if (this.checked){			
    		//_itema = 'all,';
		}else{
			_itema = '';
		}
        
    });
    $('a[imtype="synchro_goods"]').click(function(){		
        if($('.storeitem:checked').length == 0){    //没有选择分店
		    showError('请选择接收分店！');
            return false;
        }
		//if($('.checkitem:checked').length == 0){    //没有选择商品
		//    showError('请选择同步商品！');
        //    return false;
        //}
        var _items = '';
        $('.checkitem:checked').each(function(){
			$('li[name="goods_'+$(this).val()+'"]').each(function(){
				if ($(this).attr("data")!=""){
				    _items += $(this).attr("data") + ',';
				}
			});
        });
        //alert(_items);
        _items = _items.substr(0, (_items.length - 1));
        //console.log(_items);
		var _stores = '';
        $('.storeitem:checked').each(function(){
			_stores += $(this).val() + ',';
        });		
        _stores = _stores.substr(0, (_stores.length - 1));
		
		ajax_get_confirm('真的要同步商品信息吗?','<?php echo urlShop('seller_branch_synchro', 'goods_save');?>&data=' + _items +'&stores=' + _stores);
    });
});
/**
  * 更改子商品选择状态
  * @param cart_id
  * @param input
*/
function change_states(id, input){
    var goods_id = id;	
	var select_state = input.checked;
	if (select_state == false){
		$('#goods_'+goods_id).attr('data','');
	}else{
	    $('#goods_'+goods_id).attr('data',goods_id);
	}
}
</script>