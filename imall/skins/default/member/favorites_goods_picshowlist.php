<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <table class="imm-default-table">
    <thead>
      <tr im_type="table_header">
        <td><input type="checkbox" id="all" class="checkall"/>
          <label for="all"><?php echo $lang['im_select_all'];?></label>
          <a href="javascript:void(0);" class="imm-btn-mini" uri="index.php?act=member_favorites&op=delfavorites&type=goods" name="fav_id" confirm="<?php echo $lang['im_ensure_del'];?>" im_type="batchbutton"><i class="fa fa-trash-o"></i><?php echo $lang['im_del'];?></a>
          <div class="model-switch-btn"><?php echo $lang['favorite_view_mode'].$lang['im_colon'] ;?><a href="index.php?act=member_favorites&op=fglist&show=list" title="<?php echo $lang['favorite_view_mode_list'];?>"><i class="fa fa-list"></i></a><a href="index.php?act=member_favorites&op=fglist&show=pic" class="current" title="<?php echo $lang['favorite_view_mode_pic'];?>"><i class="fa fa-picture-o"></i></a><a href="index.php?act=member_favorites&op=fglist&show=store" title="<?php echo $lang['favorite_view_mode_shop'];?>"><i class="fa fa-home"></i></a></div></td>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){ ?>
      <tr>
        <td colspan="2" class="pic-model"><ul>
            <?php foreach($output['favorites_list'] as $key=>$favorites){?>
            <li class="favorite-pic-list">
              <div class="favorite-goods-thumb"><a href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" target="_blank"><img src="<?php echo thumb($favorites['goods'], 240);?>" /></a></div>
              <div class="favorite-goods-info">
                <dl>
                  <dt>
                    <input type="checkbox" class="checkitem" value="<?php echo $favorites['goods']['goods_id'];?>"/>
                    <a href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" target="_blank"><?php echo $favorites['goods']['goods_name'];?></a></dt>
                  <dd><span><strong><?php echo imPriceFormat($favorites['goods']['goods_price']);?></strong><?php echo $lang['currency_zh'];?></span><a href="javascript:void(0)"  im_type="sharegoods" data-param='{"gid":"<?php echo $favorites['goods']['goods_id'];?>"}' class="sns-share" title="<?php echo $lang['favorite_snsshare_goods'];?>"><i class="fa fa-share-square-o"></i><?php echo $lang['im_snsshare'];?></a></dd>
                  <dd><span><?php echo $lang['favorite_selled'].$lang['im_colon'] ;?><em><?php echo $favorites['goods']['goods_salenum'];?></em><?php echo $lang['piece'];?></span><span>(<em><?php echo $favorites['goods']['evaluation_count'];?></em><?php echo $lang['favorite_number_of_consult'] ;?>)</span><span><?php echo $lang['favorite_popularity'].$lang['im_colon'];?><?php echo $favorites['goods']['goods_collect'];?></span><a href="javascript:void(0)" onclick="ajax_get_confirm('<?php echo $lang['im_ensure_del'];?>', 'index.php?act=member_favorites&op=delfavorites&type=goods&fav_id=<?php echo $favorites['fav_id'];?>');" class="imm-btn-mini" title="<?php echo $lang['im_del'];?>"><?php echo $lang['im_del'];?></a></dd>
                  </dd>
                </dl>
              </div>
            </li>
            <?php }?>
          </ul></td>
      </tr>
      <?php }else{?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php }?>
    </tbody>
    <tfoot>
      <?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php }?>
    </tfoot>
  </table>
  
  <!-- 猜你喜欢 -->
  <div id="guesslike_div" style="width:980px;"></div>
  
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" charset="utf-8"></script> 
<script>
//鼠标经过弹出图片信息
$(document).ready(function() {
	$(".favorite-pic-list div").hover(function() {
		$(this).animate({
			"top": "-40px"
		},
		400, "swing");
	},
	function() {
		$(this).stop(true, false).animate({
			"top": "0"
		},
		400, "swing");
	});

	//猜你喜欢
	$('#guesslike_div').load('<?php echo urlShop('search', 'get_guesslike', array()); ?>', function(){
        $(this).show();
    });
});
</script> 
