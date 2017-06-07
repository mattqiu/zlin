<div class="div-goods-select">
  <table class="search-form">
    <tbody>
      <tr>
        <td>&nbsp;</td>
        <th><?php echo $lang['voucher_goods_store_class'];?></th>
        <td class="w160"><select name="stc_id" class="w150">
            <option value="0"><?php echo $lang['im_please_choose'];?></option>
            <?php if (!empty($output['store_goods_class'])){?>
            <?php foreach ($output['store_goods_class'] as $val) { ?>
            <option value="<?php echo $val['stc_id']; ?>" <?php if($val['stc_id'] == $_GET['stc_id']) echo 'selected="selected"';?>><?php echo $val['stc_name']; ?></option>
            <?php if (is_array($val['child']) && count($val['child'])>0){?>
            <?php foreach ($val['child'] as $child_val){?>
            <option value="<?php echo $child_val['stc_id']; ?>" <?php if($child_val['stc_id'] == $_GET['stc_id']) echo 'selected="selected"';?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
            <?php }}}}?>
          </select></td>
        <th><?php echo $lang['voucher_goods_name'];?></th>
        <td class="w160"><input type="text" name="b_search_keyword" class="text" value="<?php echo $_GET['keyword'];?>" /></td>
        <td class="tc w70"><a href="index.php?act=store_voucher&op=voucher_add_goods" imtype="search_a" class="imcs-btn"><i class="fa fa-search"></i><?php echo $lang['im_search'];?></a></td>
        <td class="w10"></td>
      </tr>
    </tbody>
  </table>
  <div class="search-result" style="width:739px;">
    <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){ ?>
    <ul class="goods-list" imtype="voucher_goods_add_tbody" style=" width:760px;">
      <?php foreach ($output['goods_list'] as $val){?>
      <li imtype="<?php echo $val['goods_commonid'];?>">
        <div class="goods-thumb"><img src="<?php echo cthumb($val['goods_image'], 240, $_SESSION['store_id']);?>" imtype="<?php echo $val['goods_image'];?>" /></div>
        <dl class="goods-info">
          <dt><a href="#" target="_blank" title="<?php echo $lang['voucher_goods_name'].'/'.$lang['voucher_goods_code'];?><?php echo $val['goods_name'];?><?php  if($val['goods_serial'] != ''){ echo $val['goods_serial'];}?>"><?php echo $val['goods_name'];?></a></dt>
          <dd>会员价：¥<?php echo $val['goods_price'];?></dd>
        </dl>
        <div data-param="{gid:<?php echo $val['goods_commonid'];?>,image:'<?php echo $val['goods_image'];?>',src:'<?php echo cthumb($val['goods_image'], 60, $_SESSION['store_id']);?>',gname:'<?php echo $val['goods_name'];?>',gprice:'<?php echo $val['goods_price'];?>',gstorang:'<?php echo $val['goods_storage'];?>'}">
        <a href="JavaScript:void(0);" class="imsc-btn-mini imsc-btn-green" onclick="voucher_goods_add($(this))"><i class="fa fa-plus"></i>添加到代金券商品组</a></div>
      </li>
      <?php }?>
    </ul>
    <?php }else{?>
    <div class="norecord">
      <div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span><?php echo $lang['no_record'];?></span></div>
    </div>
    <?php }?>
    <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
    <div class="pagination"><?php echo $output['show_page']; ?></div>
    <?php }?>
  </div>
</div>
<script>
$(function(){
	/* ajax添加商品  */
	$('.demo').unbind().ajaxContent({
		event:'click', //mouseover
		loaderType:"img",
		loadingMsg:SHOP_SKINS_URL+"/images/loading.gif",
		target:'#voucher_add_goods_ajaxContent'
	});

	$('a[imtype="search_a"]').click(function(){
		$(this).attr('href', $(this).attr('href')+'&stc_id='+$('select[name="stc_id"]').val()+ '&' +$.param({'keyword':$('input[name="b_search_keyword"]').val()}));
		$('a[imtype="search_a"]').ajaxContent({
			event:'dblclick', //mouseover
			loaderType:'img',
			loadingMsg:'<?php echo SHOP_SKINS_URL;?>/images/loading.gif',
			target:'#voucher_add_goods_ajaxContent'
		});
		$(this).dblclick();
		return false;
	});


	// 验证商品是否已经被选择。
	O = $('input[imtype="goods_commonid"]');
	A = new Array();
	if(typeof(O) != 'undefined'){
		O.each(function(){
			A[$(this).val()] = $(this).val();
		});
	}
	T = $('ul[imtype="voucher_goods_add_tbody"] li');
	if(typeof(T) != 'undefined'){
		T.each(function(){
			if(typeof(A[$(this).attr('imtype')]) != 'undefined'){
				$(this).children(':last').html('<a href="JavaScript:void(0);" onclick="voucher_operate_delete($(\'#voucher_tr_'+$(this).attr('imtype')+'\'), '+$(this).attr('imtype')+')" class="imsc-btn-mini imsc-btn-orange"><i class="fa fa-ban"></i><?php echo $lang['voucher_goods_add_voucher_exit'];?></a>');
			}
		});
	}
});

/* 添加商品 */
function voucher_goods_add(o){
	// 验证商品是否已经添加。
	var _vouchertr = $('tbody[imtype="voucher_data"] tr:not(:first)');
	
    eval('var _data = ' + o.parent().attr('data-param'));
    if (_data.gstrong == 0) {
        alert('<?php echo $lang['voucher_goods_storage_not_enough'];?>');
        return false;
    }
    // 隐藏第一个tr
    $('tbody[imtype="voucher_data"]').children(':first').hide();
    // 插入数据
    $('<tr id="voucher_tr_' + _data.gid + '"></tr>')
        .append('<input type="hidden" imtype="goods_commonid" name="goods[g_' + _data.gid + '][gid]" value="' + _data.gid + '">')
        .append('<td class="w70"><input type="checkbox" name="goods[g_' + _data.gid + '][appoint]" value="1" checked="checked"></td>')
        .append('<td class="w50 "><div class="pic-thumb"><img imtype="voucher_data_img" ncname="' + _data.image + '" src="' + _data.src + '" onload="javascript:DrawImage(this,60,60)"></span></div></td>')
        .append('<td class="tl"><dl class="goods-name"><dt style="width: 300px;">' + _data.gname + '</dt></dl></td>')
        .append('<td class="w90 goods-price" imtype="voucher_data_price">' + _data.gprice + '</td>')
        .append('<td class="w90"><input type="text" imtype="price" name="goods[g_' + _data.gid + '][price]" value="' + _data.gprice + '" class="text w70"></td>')
        .append('<td class="nscs-table-handle w90"><span><a href="javascript:void(0);" onclick="voucher_operate_delete($(\'#voucher_tr_' + _data.gid + '\'), ' + _data.gid + ')" class="btn-orange"><i class="fa fa-ban"></i><p><?php echo $lang['voucher_goods_remove'];?></p></a></span></td>')
        .fadeIn().appendTo('tbody[imtype="voucher_data"]');

    $('li[imtype="' + _data.gid + '"]').children(':last').html('<a href="JavaScript:void(0);" class="imsc-btn-mini imsc-btn-orange" onclick="voucher_operate_delete($(\'#voucher_tr_' + _data.gid + '\'), ' + _data.gid + ')"><i class="fa fa-ban"></i><?php echo $lang['voucher_goods_add_voucher_exit'];?></a>');
    count_cost_price_sum();
    count_price_sum();
}

</script> 