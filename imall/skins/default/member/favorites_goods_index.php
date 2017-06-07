<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <table class="imm-default-table shoplist">
    <thead>
      <tr>
        <td colspan="20"><input type="checkbox" id="all" class="checkall"/>
          <label for="all"><?php echo $lang['im_select_all'];?></label>
          <a href="javascript:void(0);" class="imm-btn-mini" uri="index.php?act=member_favorites&op=delfavorites&type=goods" name="fav_id" confirm="<?php echo $lang['im_ensure_del'];?>" im_type="batchbutton"><i class="fa fa-trash-o"></i><?php echo $lang['im_del'];?></a>
          <div class="model-switch-btn"><?php echo $lang['favorite_view_mode'].$lang['im_colon'] ;?><a href="index.php?act=member_favorites&op=fglist&show=list" class="current" title="<?php echo $lang['favorite_view_mode_list'];?>"><i class="fa fa-list"></i></a><a href="index.php?act=member_favorites&op=fglist&show=pic" title="<?php echo $lang['favorite_view_mode_pic'];?>"><i class="fa fa-picture-o"></i></a><a href="index.php?act=member_favorites&op=fglist&show=store" title="<?php echo $lang['favorite_view_mode_shop'];?>"><i class="fa fa-home"></i></a></div></td>
      </tr>
      <tr im_type="table_header">
        <th class="w30"></th>
        <th colspan="2"><?php echo $lang['favorite_product_name'];?></th>
        <th class="w150"><?php echo $lang['favorite_store_info'];?></th>
        <th class="w100"><?php echo $lang['favorite_product_price'];?></th>
        <th class="w150"><?php echo $lang['favorite_date'];?></th>
        <th class="w100"><?php echo $lang['favorite_popularity'];?></th>
        <th class="w110"><?php echo $lang['favorite_handle'];?></th>
      </tr>
    </thead>
    <?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){ ?>
    <tbody>
      <?php foreach($output['favorites_list'] as $key=>$favorites){?>
      <tr class="bd-line">
        <td style="vertical-align: middle;"><input type="checkbox" class="checkitem" value="<?php echo $favorites['goods']['goods_id'];?>"/></td>
        <td class="w50"><div class="pic-thumb"><a href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" target="_blank"><img src="<?php echo thumb($favorites['goods'], 60);?>" onMouseOver="toolTip('<img src=<?php echo thumb($favorites['goods'], 240);?>>')" onMouseOut="toolTip()" /></a></div></td>
        <td class="tl"><dl class="goods-name">
            <dt><a href="index.php?act=goods&goods_id=<?php echo $favorites['goods']['goods_id'];?>" target="_blank"><?php echo $favorites['goods']['goods_name'];?></a></dt>
            <dd><?php echo $lang['favorite_selled'].$lang['im_colon'] ;?><em><?php echo $favorites['goods']['goods_salenum'];?></em><?php echo $lang['piece'];?>(<em><?php echo $favorites['goods']['evaluation_count'];?></em><?php echo $lang['favorite_number_of_consult'] ;?>)</dd>
          </dl></td>
        <td><p> <a href="<?php echo urlShop('show_store','index',array('store_id'=> $favorites['goods']['store_id']), $favorites['goods']['store_domain']);?>" ><?php echo $favorites['goods']['store_name'];?></a>
            <?php if(!empty($output['store_favorites']) && in_array($favorites['goods']['store_id'],$output['store_favorites'])){ ?>
            <i class="fa fa-check-circle green" title="<?php echo $lang['favorite_collected_store'];?>"></i>
            <?php }else{?>
            <a href="javascript:collect_store('<?php echo $favorites['goods']['store_id'];?>','store','')" title="<?php echo $lang['favorite_collect_store'];?>" im_store="<?php echo $favorites['goods']['store_id'];?>"> <i class=" fa fa-plus-circle"></i></a>
            <?php }?>
          </p>
          <p class="mt5"><span member_id="<?php echo $favorites['goods']['member_id'];?>"></span>
            <?php if(!empty($favorites['goods']['store_qq'])){?>
            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $favorites['goods']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $favorites['goods']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $favorites['goods']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
            <?php }?>
            <?php if(!empty($favorites['goods']['store_ww'])){?>
            <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $favorites['goods']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $favorites['goods']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang"  style=" vertical-align: middle;"/></a>
            <?php }?>
          </p></td>
        <td><span class="goods-price"><?php echo imPriceFormat($favorites['goods']['goods_price']);?></span></td>
        <td class="goods-time"><?php echo date("Y-m-d",$favorites['fav_time']);?></td>
        <td><?php echo $favorites['goods']['goods_collect'];?></td>
        <td class="imm-table-handle"><span><a href="javascript:void(0)" class="btn-acidblue" im_type="sharegoods" data-param='{"gid":"<?php echo $favorites['goods']['goods_id'];?>"}' title="<?php echo $lang['favorite_snsshare_goods'];?>"><i class="fa fa-share-square-o"></i>
          <p><?php echo $lang['favorite_snsshare_goods'];?></p>
          </a></span> <span><a href="javascript:void(0)" class="btn-red" onclick="ajax_get_confirm('<?php echo $lang['im_ensure_del'];?>', 'index.php?act=member_favorites&op=delfavorites&type=goods&fav_id=<?php echo $favorites['fav_id'];?>');"><i class="fa fa-trash-o"></i>
          <p><?php echo $lang['im_del_&nbsp'];?></p>
          </a></span></td>
      </tr>
      <?php }?>
    </tbody>
    <?php }else{?>
    <tbody>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i></i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
    </tbody>
    <?php }?>
    <?php if(!empty($output['favorites_list']) && is_array($output['favorites_list'])){?>
    <tfoot>
      <tr>
        <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
      </tr>
    </tfoot>
    <?php }?>
  </table>
  <!-- 猜你喜欢 -->
  <div id="guesslike_div" style="width:980px;"></div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" charset="utf-8"></script>
<script>
//鼠标经过弹出图片信息
$(document).ready(function() {
	//猜你喜欢
	$('#guesslike_div').load('<?php echo urlShop('search', 'get_guesslike', array()); ?>', function(){
        $(this).show();
    });
});
</script>
