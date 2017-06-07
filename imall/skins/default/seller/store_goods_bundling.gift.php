<div class="div-goods-select">
  <table class="search-form">
    <tbody>
      <tr>
        <td>&nbsp;</td>
        <th>商品分类</th>
        <td class="w160">
          <select name="stc_id" class="w150">
            <option value="0"><?php echo $lang['im_please_choose'];?></option>
            <?php if (!empty($output['store_goods_class'])){?>
            <?php foreach ($output['store_goods_class'] as $val) { ?>
            <option value="<?php echo $val['stc_id']; ?>" <?php if($val['stc_id'] == $_GET['stc_id']) echo 'selected="selected"';?>><?php echo $val['stc_name']; ?></option>
            <?php if (is_array($val['child']) && count($val['child'])>0){?>
            <?php foreach ($val['child'] as $child_val){?>
            <option value="<?php echo $child_val['stc_id']; ?>" <?php if($child_val['stc_id'] == $_GET['stc_id']) echo 'selected="selected"';?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
            <?php }}}}?>
          </select>
        </td>
        <th>商品名称</th>
        <td class="w160"><input type="text" name="b_search_keyword" class="text" value="<?php echo $_GET['keyword'];?>" /></td>
        <td class="tc w70"><a href="index.php?act=store_goods_gift&op=bundling_gift_goods" imtype="search_a" class="imcs-btn"><i class="fa fa-search"></i><?php echo $lang['im_search'];?></a></td>
        <td class="w10"></td>
      </tr>
    </tbody>
  </table>
  <div class="search-result" style="width:739px;">
    <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){ ?>
    <ul class="goods-list" imtype="bundling_goods_add_tbody" style=" width:760px;">
      <?php foreach ($output['goods_list'] as $val){?>
      <li imtype="<?php echo $val['goods_id'];?>">
        <div class="goods-thumb"><img src="<?php echo cthumb($val['goods_image'], 240, $_SESSION['store_id']);?>" imtype="<?php echo $val['goods_image'];?>" /></div>
        <dl class="goods-info">
          <dt><a href="#" target="_blank" title="<?php echo $lang['bundling_goods_name'].'/'.$lang['bundling_goods_code'];?><?php echo $val['goods_name'];?><?php  if($val['goods_serial'] != ''){ echo $val['goods_serial'];}?>"><?php echo $val['goods_name'];?></a></dt>
          <dd>价格：¥<?php echo $val['goods_price'];?></dd>
          <dd>库存：<?php echo $val['goods_storage'].$lang['piece'];?></dd>
        </dl>
        <div data-param="{gid:<?php echo $val['goods_id'];?>,image:'<?php echo $val['goods_image'];?>',src:'<?php echo cthumb($val['goods_image'], 60, $_SESSION['store_id']);?>',gname:'<?php echo $val['goods_name'];?>',gprice:'<?php echo $val['goods_price'];?>',gstorang:'<?php echo $val['goods_storage'];?>'}">
          <a href="JavaScript:void(0);" class="imsc-btn-mini imsc-btn-green" onclick="bundling_goods_add($(this))"><i class="fa fa-plus"></i>添加到赠品列表</a>
        </div>
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
		target:'#bundling_gift_goods_ajaxContent'
	});

	$('a[imtype="search_a"]').click(function(){		
		$(this).attr('href', $(this).attr('href')+'&stc_id='+$('select[name="stc_id"]').val()+ '&' +$.param({'keyword':$('input[name="b_search_keyword"]').val()}));
		$('a[imtype="search_a"]').ajaxContent({
			event:'dblclick', //mouseover
			loaderType:'img',
			loadingMsg:'<?php echo SHOP_SKINS_URL;?>/images/loading.gif',
			target:'#bundling_gift_goods_ajaxContent'
		});
		$(this).dblclick();
		return false;
	});


	// 验证商品是否已经被选择。
	O = $('input[imtype="goods_id"]');
	A = new Array();
	if(typeof(O) != 'undefined'){
		O.each(function(){
			A[$(this).val()] = $(this).val();
		});
	}
	T = $('ul[imtype="bundling_goods_add_tbody"] li');
	if(typeof(T) != 'undefined'){
		T.each(function(){
			if(typeof(A[$(this).attr('imtype')]) != 'undefined'){
				$(this).children(':last').html('<a href="JavaScript:void(0);" onclick="bundling_operate_delete($(\'#bundling_tr_'+$(this).attr('imtype')+'\'), '+$(this).attr('imtype')+')" class="imsc-btn-mini imsc-btn-orange"><i class="fa fa-ban"></i>从赠品列表中移除</a>');
			}
		});
	}
});

/* 添加商品 */
function bundling_goods_add(o){
	// 验证商品是否已经添加。
	var _bundlingtr = $('tbody[imtype="bundling_data"] tr:not(:first)');
	if(typeof(_bundlingtr) != 'undefined'){
		if(_bundlingtr.length == <?php echo C('promotion_bundling_goods_sum');?>){
			alert('<?php printf($lang['bundling_goods_add_enough_prompt'], C('promotion_bundling_goods_sum'));?>');
			return false;
		}
	}

    eval('var _data = ' + o.parent().attr('data-param'));
    if (_data.gstrong == 0) {
        alert('<?php echo $lang['bundling_goods_storage_not_enough'];?>');
        return false;
    }
    // 隐藏第一个tr
    $('tbody[imtype="bundling_data"]').children(':first').hide();
    // 插入数据
    $('<tr id="bundling_tr_' + _data.gid + '"></tr>')
        .append('<input type="hidden" imtype="goods_id" name="gifts[g_' + _data.gid + '][gg_id]" value="' + _data.gid + '">')
        .append('<td class="w50 "><div class="pic-thumb"><img imtype="bundling_data_img" ncname="' + _data.image + '" src="' + _data.src + '" onload="javascript:DrawImage(this,60,60)"></span></div></td>')
        .append('<td class="tl"><dl class="goods-name"><dt style="width: 300px;">' + _data.gname + '</dt></dl></td>')
        .append('<td class="w90 goods-price" imtype="bundling_data_price">' + _data.gprice + '</td>')
        .append('<td class="w90"><input type="text" imtype="price" name="gifts[g_' + _data.gid + '][gg_num]" value="1" class="text w70"></td>')
        .append('<td class="nscs-table-handle w90"><span><a href="javascript:void(0);" onclick="bundling_operate_delete($(\'#bundling_tr_' + _data.gid + '\'), ' + _data.gid + ')" class="btn-orange"><i class="fa fa-ban"></i><p>移除</p></a></span></td>')
        .fadeIn().appendTo('tbody[imtype="bundling_data"]');

    $('li[imtype="' + _data.gid + '"]').children(':last').html('<a href="JavaScript:void(0);" class="imsc-btn-mini imsc-btn-orange" onclick="bundling_operate_delete($(\'#bundling_tr_' + _data.gid + '\'), ' + _data.gid + ')"><i class="fa fa-ban"></i>从赠品列表中移除</a>');
    //count_cost_price_sum();
    //count_price_sum();
}

</script> 