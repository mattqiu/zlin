<?php defined('InIMall') or exit('Access Invalid!');?>

<link href="<?php echo SHOP_SKINS_URL;?>/css/home_goods.css" rel="stylesheet" type="text/css">
<style type="text/css">
.imcs-goods-picture .levelB, .imcs-goods-picture .levelC { cursor: url(<?php echo SHOP_SKINS_URL;?>/images/shop/zoom.cur), pointer;}
.imcs-goods-picture .levelD { cursor: url(<?php echo SHOP_SKINS_URL;?>/images/shop/hand.cur), move\9;}
</style>

<div id="content" class="wrapper pr">
  <input type="hidden" id="lockcompare" value="unlock" />
  <div class="imcs-detail<?php if ($output['store_info']['is_own_shop']) echo ' ownshop'; ?>">
    <!-- S 商品图片 -->
    <div id="imcs-goods-picture" class="imcs-goods-picture image_zoom"> </div>
    <!-- S 商品基本信息 -->
    <div class="imcs-goods-summary">      
      <div class="name">
        <h1><?php echo $output['goods']['goods_name']; ?></h1>
        <strong><?php echo str_replace("\n", "<br>", $output['goods']['goods_jingle']);?></strong> 
      </div>
      <div class="imcs-meta">
        <div class="rate"> 
          <!-- S 描述相符评分 --><a href="#imGoodsRate">商品评分</a>
          <div class="raty" data-score="<?php echo $output['goods_evaluate_info']['star_average'];?>"></div>
          <!-- E 描述相符评分 --> 
        </div>
        <!-- S 商品参考价格 -->
        <dl class="cost-price">
          <dt><?php echo $lang['goods_index_goods_cost_price'];?><?php echo $lang['im_colon'];?></dt>
          <dd>
            <strong><?php echo $lang['currency'].$output['goods']['goods_marketprice'];?></strong>
          </dd>
        </dl>
        <!-- E 商品参考价格 -->
        <!-- S 商品发布价格 -->
        <dl>
          <dt><?php echo $lang['goods_index_goods_price'];?><?php echo $lang['im_colon'];?></dt>
          <dd class="price">
            <?php if (isset($output['goods']['title']) && $output['goods']['title'] != '') {?>
            <span class="tag"><?php echo $output['goods']['title'];?></span>
            <?php }?>
            <?php if (isset($output['goods']['promotion_price']) && !empty($output['goods']['promotion_price'])) {?>
            <strong><?php echo $lang['currency'].$output['goods']['promotion_price'];?></strong>
            <em>(原售价<?php echo $lang['im_colon'];?><?php echo $lang['currency'].$output['goods']['goods_price'];?>)</em>
            <?php } else {?>
            <strong><?php echo $lang['currency'].$output['goods']['goods_price'];?></strong>
            <?php }?>
          </dd>
        </dl>
        <!-- E 商品发布价格 -->
        
        <!-- 分销选入店铺 start zhang -->
		<?php if(!empty($_SESSION['store_id'])&&$output['goods']['is_market']==1){ ?>
		<dl>
			<!--市场价格-->
          	<dt>提成利润<?php echo $lang['im_colon'];?></dt>
          	<dd class="price">
          	<strong>
          	<?php 
	          	echo $lang['currency'];
	          	if($output['goods']['goods_tradeprice']==0){
	          		echo floatval($output['goods']['goods_price']*$output['goods']['baifen']);
	          	}elseif (!empty($output['goods']['promotion_price'])){
	          		echo floatval($output['goods']['promotion_price']-$output['goods']['goods_tradeprice']-($output['goods']['promotion_price']*$output['goods']['commis_rate']*0.01));
          		}else {
	          		echo floatval($output['goods']['goods_promotion_price']-$output['goods']['goods_tradeprice']-($output['goods']['goods_promotion_price']*$output['goods']['commis_rate']*0.01));
	          	}
          	?>
          	</strong>
          	</dd>
        </dl>
		<?php }?>
		
        <!-- S 促销 -->
        <?php if (isset($output['goods']['promotion_type']) || $output['goods']['have_gift'] == 'gift') {?>
        <dl>
          <dt>促销信息：</dt>
          <dd class="promotion-info">
            <!-- S 限时折扣 -->
            <?php if ($output['goods']['promotion_type'] == 'xianshi') {?>
            <?php echo '直降：'.$lang['currency'].$output['goods']['down_price'];?>
            <?php if($output['goods']['lower_limit']) {?>
            <em><?php echo sprintf('最低%s件起',$output['goods']['lower_limit']);?></em>
            <?php } ?>
            <span><?php echo $output['goods']['explain'];?></span><br>
            <?php }?>
            <!-- E 限时折扣  -->
            <!-- S 抢购-->
            <?php if ($output['goods']['promotion_type'] == 'groupbuy') {?>
            <?php if ($output['goods']['upper_limit']) {?>
            <em><?php echo sprintf('最多限购%s件',$output['goods']['upper_limit']);?></em>
            <?php } ?>
            <span><?php echo $output['goods']['remark'];?></span><br>
            <?php }?>
            <!-- E 抢购 -->
            <!-- S 赠品 -->
            <?php if ($output['goods']['have_gift'] == 'gift') {?>
            <?php echo '赠品'?> <span>赠下方的热销商品，赠完即止</span>
            <?php }?>
            <!-- E 赠品 -->
          </dd>
        </dl>
        <?php }?>
        <!-- E 促销 -->
      </div>            
      <ul class="im-ind-panel">
        <li class="im-ind-item im-ind-sellCount ">
          <span class="im-label">月销量</span>
		  <span class="im-count"><?php echo $output['goods']['goods_salenum']; ?></span>
        </li>
        <li class="im-ind-item im-ind-reviewCount">
          <span class="im-label">累计评价</span>
		  <span class="im-count"><?php echo $output['goods_evaluate_info']['all'];?></span>
        </li>        
	  </ul>      

      <div class="imcs-plus">
        <!-- S 物流运费  预售商品不显示物流 -->
        <?php if ($output['goods']['is_virtual'] == 0) {?>
        <dl class="imcs-freight">
          <dt>
            <?php if ($output['goods']['goods_transfee_charge'] == 1){?>
            <?php echo $lang['goods_index_freight'].$lang['im_colon'];?>
            <?php }else{?>
            <!-- 如果买家承担运费 -->
            <!-- 如果使用了运费模板 -->
            <?php if ($output['goods']['transport_id'] != '0'){?>
            <?php echo $lang['goods_index_trans_to'];?><a href="javascript:void(0)" id="imrecive"><?php echo $lang['goods_index_trans_country'];?></a><?php echo $lang['im_colon'];?>
            <div class="imcs-freight-box" id="transport_pannel">
              <?php if (is_array($output['area_list'])){?>
              <?php foreach($output['area_list'] as $k=>$v){?>
              <a href="javascript:void(0)" imtype="<?php echo $k;?>"><?php echo $v;?></a>
              <?php }?>
              <?php }?>
            </div>
            <?php }else{?>
            <?php echo $lang['goods_index_trans_zcountry'];?><?php echo $lang['im_colon'];?>
            <?php }?>
            <?php }?>
          </dt>
          <dd id="transport_price">
            <?php if($output['goods']['promotion_type'] == 'groupbuy') { ?>
            <span><?php echo $lang['goods_index_groupbuy_no_shipping_fee'];?></span>
            <?php } else { ?>
            <?php if ($output['goods']['goods_freight'] == 0){?>
            <span id="im_kd"><?php echo $lang['goods_index_trans_for_seller'];?></span>
            <?php }else{?>
            <!-- 如果买家承担运费 -->
            <span id="im_kd">运费<?php echo $lang['im_colon'];?><em><?php echo $output['goods']['goods_freight'];?></em><?php echo $lang['goods_index_yuan'];?></span>
            <?php }?>
            <?php }?>
          </dd>
          <dd style="color:red;display:none" id="loading_price">loading.....</dd>
        </dl>
        <?php }?>
        <!-- E 物流运费 --->
        <!-- S 赠品 -->
        <?php if ($output['goods']['have_gift'] == 'gift') {?>
        <dl>
          <dt>赠&#12288;&#12288;品：</dt>
          <dd class="goods-gift" id="imsGoodsGift">
            <?php if (!empty($output['gift_array'])) {?>
            <ul>
              <?php foreach ($output['gift_array'] as $val){?>
              <li>
                <div class="goods-gift-thumb"><span><img src="<?php echo cthumb($val['gift_goodsimage'], '60', $output['goods']['store_id']);?>"></span></div>
                <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['gift_goodsid']));?>" class="goods-gift-name" target="_blank"><?php echo $val['gift_goodsname']?></a><em>x<?php echo $val['gift_amount'];?></em> </li>
              <?php }?>
            </ul>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <!-- S 赠品 -->
      </div>
      <?php if($output['goods']['goods_state'] != 10 && $output['goods']['goods_verify'] == 1){?>
      <div class="imcs-key">
        <!-- S 商品规格值-->
        <?php if (is_array($output['goods']['spec_name'])) { ?>
        <?php foreach ($output['goods']['spec_name'] as $key => $val) {?>
        <dl imtype="imcss-spec">
          <dt><?php echo $val;?><?php echo $lang['im_colon'];?></dt>
          <dd>
            <?php if (is_array($output['goods']['spec_value'][$key]) and !empty($output['goods']['spec_value'][$key])) {?>
            <ul nctyle="ul_sign">
              <?php foreach($output['goods']['spec_value'][$key] as $k => $v) {?>
              <?php if( $key == 1 ){?>
              <!-- 图片类型规格-->
              <li class="sp-img"><a href="javascript:void(0);" class="<?php if (isset($output['goods']['goods_spec'][$k])) {echo 'hovered';}?>" data-param="{valid:<?php echo $k;?>}" title="<?php echo $v;?>"><img src="<?php echo $output['spec_image'][$k];?>"/><?php echo $v;?><i></i></a></li>
              <?php }else{?>
              <!-- 文字类型规格-->
              <li class="sp-txt"><a href="javascript:void(0)" class="<?php if (isset($output['goods']['goods_spec'][$k])) { echo 'hovered';} ?>" data-param="{valid:<?php echo $k;?>}"><?php echo $v;?><i></i></a></li>
              <?php }?>
              <?php }?>
            </ul>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <?php }?>
        <!-- E 商品规格值-->
        <?php if ($output['goods']['is_virtual'] == 1) {?>
        <dl>
          <dt>提货方式：</dt>
          <dd>
            <ul>
              <li class="sp-txt"><a href="javascript:void(0)" class="hovered">电子兑换券<i></i></a></li>
            </ul>
          </dd>
        </dl>
        <?php }?>
        <?php if ($output['goods']['is_virtual'] == 1) {?>
        <!-- 虚拟商品有效期 -->
        <dl>
          <dt>有&nbsp;效&nbsp;期：</dt>
          <dd>即日起 到 <?php echo date('Y-m-d H:i:s', $output['goods']['virtual_indate']);?></dd>
        </dl>
        <?php }else if ($output['goods']['is_presell'] == 1) {?>
        <!-- 预售商品发货时间 -->
        <dl>
          <dt>预&#12288;&#12288;售：</dt>
          <dd><ul><li class="sp-txt"><a href="javascript:void(0)" class="hovered"><?php echo date('Y-m-d', $output['goods']['presell_deliverdate']);?>&nbsp;日发货<i></i></a></li></ul></dd>
        </dl>
        <?php }?>
        <?php if ($output['goods']['is_fcode']) {?>
        <!-- 预售商品发货时间 -->
        <dl>
          <dt>购买类型：</dt>
          <dd><ul><li class="sp-txt"><a href="javascript:void(0)" class="hovered">F码优先购买<i></i></a></li></ul></dd>
        </dl>
        <?php }?>
        <!-- S 购买数量及库存 -->
        <?php if ($output['goods']['goods_state'] != 0 && $output['goods']['goods_storage'] >= 0) {?>
        <dl>
          <dt><?php echo $lang['goods_index_buy_amount'];?><?php echo $lang['im_colon'];?></dt>
          <dd class="imcs-figure-input">
            <input type="text" name="" id="quantity" value="1" size="3" maxlength="6" class="text w30" <?php if ($output['goods']['is_fcode'] == 1) {?>readonly<?php }?>>
            <?php if ($output['goods']['is_fcode'] == 1) {?>
            <span style="margin-left: 5px;">（每个F码优先购买一件商品）</span>(<?php echo $lang['goods_index_stock'];?><em imtype="goods_stock"><?php echo $output['goods']['goods_storage']; ?></em><?php echo $lang['im_jian'];?>)
            <?php } else {?>
            <a href="javascript:void(0)" class="increase">+</a><a href="javascript:void(0)" class="decrease">-</a> <span>(<?php echo $lang['goods_index_stock'];?><em imtype="goods_stock"><?php echo $output['goods']['goods_storage']; ?></em><?php echo $lang['im_jian'];?>
            <!-- 虚拟商品限购数 -->
            <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) { ?>，每人次限购<strong>
              <!-- 虚拟抢购 设置了虚拟抢购限购数 该数小于原商品限购数 -->
              <?php echo ($output['goods']['promotion_type'] == 'groupbuy' && $output['goods']['upper_limit'] > 0 && $output['goods']['upper_limit'] < $output['goods']['virtual_limit']) ? $output['goods']['upper_limit'] : $output['goods']['virtual_limit'];?>
              </strong>件<?php } ?>
            )</span><?php } ?>
          </dd>
        </dl>
        <?php }?>
        <!-- E 购买数量及库存 -->
      </div>

      <!-- S 购买按钮 -->
      <div class="imcs-btn"><!-- S 提示已选规格及库存不足无法购买 -->
        <div imtype="goods_prompt" class="imcs-point">
          <?php if (!empty($output['goods']['goods_spec'])) {?>
          <span class="yes"><?php echo $lang['goods_index_you_choose'];?> <strong><?php echo implode($lang['im_comma'], $output['goods']['goods_spec']);?></strong></span>
          <?php }?>
          <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>
          <span class="no"><i class="fa fa-exclamation-circle"></i>&nbsp;<?php echo $lang['goods_index_understock_prompt'];?></span>
          <?php }?>
        </div>
        <!-- E 提示已选规格及库存不足无法购买 -->
        <!-- S到货通知 -->
        <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>
        <a href="javascript:void(0);" imtype="arrival_notice" class="arrival" title="到货通知">（<i class="fa fa-bullhorn"></i>到货通知）</a>
        <?php }?>
        <!-- E到货通知 -->
        <div class="clear"></div>
          
        <!-- 预约 -->
        <?php if (($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) && $output['goods']['is_appoint'] == 1) {?>
        <div>销售时间：<?php echo date('Y-m-d H:i:s', $output['goods']['appoint_satedate']);?></div>
        <a href="javascript:void(0);" imtype="appoint_submit" class="addcart" title="立即预约">立即预约</a>
        <?php }?>
        <!-- 分销选入店铺 start zhang -->
	  	<?php if(!empty($_SESSION['store_id'])&&($_SESSION['store_id']!=$output['goods']['store_id'])&&$output['goods']['is_market']==1){ ?>
	  	<a href="index.php?act=goods&op=goodsadd&goods_id=<?php echo $_GET['goods_id'];?>" class="addstore">选入店铺</a>
	  	<?php } ?>
        <!-- 立即购买-->
        <a href="javascript:void(0);" imtype="buynow_submit" class="buynow <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0 || ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>no-buynow<?php }?>" title="<?php echo $output['goods']['buynow_text'];?>"></a>
        <?php if ($output['goods']['cart'] == true) {?>
        <!-- 加入购物车-->
        <a href="javascript:void(0);" imtype="addcart_submit" class="addcart <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>no-addcart<?php }?>" title="<?php echo $lang['goods_index_add_to_cart'];?>"></a>
        <?php } ?>
        <!-- S 加入购物车弹出提示框 -->
        <div class="imcs-cart-popup">
          <dl>
            <dt><?php echo $lang['goods_index_cart_success'];?><a title="<?php echo $lang['goods_index_close'];?>" onClick="$('.imcs-cart-popup').css({'display':'none'});">X</a></dt>
            <dd><?php echo $lang['goods_index_cart_have'];?> <strong id="bold_num"></strong> <?php echo $lang['goods_index_number_of_goods'];?> <?php echo $lang['goods_index_total_price'];?><?php echo $lang['im_colon'];?><em id="bold_mly" class="saleP"></em></dd>
            <dd class="btns"><a href="javascript:void(0);" class="imcs-btn-mini imcs-btn-green" onClick="location.href='<?php echo SHOP_SITE_URL.DS?>index.php?act=cart'"><?php echo $lang['goods_index_view_cart'];?></a> <a href="javascript:void(0);" class="imcs-btn-mini" value="" onClick="$('.imcs-cart-popup').css({'display':'none'});"><?php echo $lang['goods_index_continue_shopping'];?></a></dd>
          </dl>
        </div>
        <!-- E 加入购物车弹出提示框 -->
      </div>
      <!-- E 购买按钮 -->
      <?php }else{?>
      <div class="imcs-saleout">
        <dl>
          <dt><i class="fa fa-info-circle"></i><?php echo $lang['goods_index_is_no_show'];?></dt>
          <dd><?php echo $lang['goods_index_is_no_show_message_one'];?></dd>
          <dd><?php echo $lang['goods_index_is_no_show_message_two_1'];?>&nbsp;<a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['goods']['store_id']), $output['store_info']['store_domain']);?>" class="imcs-btn-mini"><?php echo $lang['goods_index_is_no_show_message_two_2'];?></a>&nbsp;<?php echo $lang['goods_index_is_no_show_message_two_3'];?> </dd>
        </dl>
      </div>
      <?php }?>
      <!--E 商品信息 -->      
    </div>
    <!-- E 商品图片及收藏分享 -->
    <div class="imcs-handle">
      <!-- S 分享-->
      <a href="javascript:void(0);" class="share" im_type="sharegoods" data-param='{"gid":"<?php echo $output['goods']['goods_id'];?>"}'><i></i><?php echo $lang['goods_index_snsshare_goods'];?><span>(<em im_type="sharecount_<?php echo $output['goods']['goods_id'];?>"><?php echo intval($output['goods']['sharenum'])>0?intval($output['goods']['sharenum']):0;?>)</em></span></a>      
      <!-- S 收藏 -->
      <a href="javascript:collect_goods('<?php echo $output['goods']['goods_id']; ?>','count','goods_collect');" class="favorite"><i></i><?php echo $lang['goods_index_favorite_goods'];?><span>(<em imtype="goods_collect"><?php echo $output['goods']['goods_collect']?></em>)</span></a>
      <!-- S 喜欢 -->
      <a href="javascript:void(0);" class="like" im_type="likebtn" data-param='{"gid":"<?php echo $output['goods']['goods_id'];?>"}'><i></i><?php echo $lang['goods_index_snslike_goods'];?><span>(<em im_type="likestat_<?php echo $output['goods']['goods_id'];?>"><?php echo intval($output['goods']['likenum'])>0?intval($output['goods']['likenum']):0;?>)</em></span></a>      
      <!-- S 举报 -->
      <?php if($output['inform_switch']) { ?>
      <a href="<?php if ($_SESSION['is_login']) {?>index.php?act=member_inform&op=inform_submit&goods_id=<?php echo $output['goods']['goods_id'];?><?php } else {?>javascript:login_dialog();<?php }?>" title="<?php echo $lang['goods_index_goods_inform'];?>" class="inform"><i></i><?php echo $lang['goods_index_goods_inform'];?></a>
      <?php } ?>
      <!-- S 对比 -->
      <a href="javascript:void(0);" class="compare" im_type="compare_<?php echo $output['goods']['goods_id'];?>" data-param='{"gid":"<?php echo $output['goods']['goods_id'];?>"}'><i></i><?php echo $lang['goods_index_compare_goods'];?></a>
      <!-- End -->  
    </div>
    <!--S 店铺信息-->
    <div style=" position: absolute; z-index: 1; top: -1px; right: -1px;">
      <?php include template('/store/'.$output['store_theme'].'/info');?>
    </div>
    <!--E 店铺信息 -->
    <div class="clear"></div>
  </div>
  <div class="imcs-goods-layout expanded" >
    <!--商品详情左侧 -->
    <div class="imcs-sidebar">
      <div class="imcs-sidebar-container">
        <div class="title">
          <h4>商品二维码</h4>
        </div>
        <div class="content">
          <div class="imcs-goods-code">
            <p><img src="<?php echo GetGoodsQRCode($output['goods']['store_id'],$output['goods']['goods_id']);?>"  title="商品原始地址：<?php echo urlShop('goods', 'index', array('goods_id'=>$output['goods']['goods_id']));?>"></p>
            <span class="imcs-goods-code-note"><i></i>扫描二维码，手机查看分享</span> 
          </div>
        </div>
      </div>
      <?php include template('/store/'.$output['store_theme'].'/callcenter');?>
      <?php include template('/store/'.$output['store_theme'].'/left');?>
    </div>
    <!--商品详情右侧 -->
    <div class="imcs-goods-main" id="main-nav-holder">
      <!-- S 优惠套装 -->
      <div class="imcs-promotion" id="imcss-bundling" style="display:none;"></div>
      <!-- E 优惠套装 -->
      <div class="tabbar pngFix" id="main-nav">
        <div class="imcs-goods-title-nav">
          <ul id="categorymenu">
            <li class="current"><a id="tabGoodsIntro" href="#content"><?php echo $lang['goods_index_goods_info'];?></a></li>
            <li><a id="tabGoodsRate" href="#content"><?php echo $lang['goods_index_evaluation'];?><em>(<?php echo $output['goods_evaluate_info']['all'];?>)</em></a></li>
            <li><a id="tabGoodsTraded" href="#content"><?php echo $lang['goods_index_sold_record'];?><em>(<?php echo $output['goods']['goods_salenum']; ?>)</em></a></li>
            <li><a id="tabGuestbook" href="#content"><?php echo $lang['goods_index_goods_consult'];?></a></li>
          </ul>
          <div class="switch-bar"><a href="javascript:void(0)" id="fold">&nbsp;</a></div>
        </div>
      </div>
      <div class="imcs-intro">
        <div class="content bd" id="imGoodsIntro">

          <!--S 满就送 -->
          <?php if($output['mansong_info']) { ?>
          <div class="imcss-mansong">
            <div class="imcss-mansong-ico"></div>
            <dl class="imcss-mansong-content">
              <dt><?php echo $output['mansong_info']['mansong_name'];?>
                <time>( <?php echo $lang['im_promotion_time'];?><?php echo $lang['im_colon'];?><?php echo date('Y-m-d',$output['mansong_info']['start_time']).'--'.date('Y-m-d',$output['mansong_info']['end_time']);?> )</time>
              </dt>
              <dd>
                <?php foreach($output['mansong_info']['rules'] as $rule) { ?>
                <span><?php echo $lang['im_man'];?><em><?php echo imPriceFormat($rule['price']);?></em><?php echo $lang['im_yuan'];?>
                <?php if(!empty($rule['discount'])) { ?>
                ， <?php echo $lang['im_reduce'];?><i><?php echo imPriceFormat($rule['discount']);?></i><?php echo $lang['im_yuan'];?>
                <?php } ?>
                <?php if(!empty($rule['goods_id'])) { ?>
                ， <?php echo $lang['im_gift'];?> <a href="<?php echo $rule['goods_url'];?>" title="<?php echo $rule['mansong_goods_name'];?>" target="_blank"> <img src="<?php echo cthumb($rule['goods_image'], 60);?>" alt="<?php echo $rule['mansong_goods_name'];?>"> </a>&nbsp;。
                <?php } ?>
                </span>
                <?php } ?>
              </dd>
              <dd class="imcss-mansong-remark"><?php echo $output['mansong_info']['remark'];?></dd>
            </dl>
          </div>
          <?php } ?>
          <!--E 满就送 -->
          <?php if(is_array($output['goods']['goods_attr']) || isset($output['goods']['brand_name'])){?>
          <ul class="imcss-goods-sort">
            <?php if(!empty($output['goods']['goods_serial'])){?>
            <li>商家货号：<?php echo $output['goods']['goods_serial'];?></li>
            <?php }?>
            <?php if(!empty($output['goods']['brand_name'])){echo '<li>'.$lang['goods_index_brand'].$lang['im_colon'].$output['goods']['brand_name'].'</li>';}?>
            <?php if(is_array($output['goods']['goods_attr']) && !empty($output['goods']['goods_attr'])){?>
            <?php 
			      foreach ($output['goods']['goods_attr'] as $val){ 
			        $val= array_values($val);
					if (!empty($val[1]) && $val[1]!='不限'){
					  echo '<li>'.$val[0].$lang['im_colon'].$val[1].'</li>';
					}
				  }
		    ?>
            <?php }?>
          </ul>
          <?php }?>
          <div class="imcs-goods-info-content">
            <?php if (isset($output['plate_top'])) {?>
            <div class="top-template"><?php echo $output['plate_top']['plate_content']?></div>
            <?php }?>
            <div class="default"><?php echo $output['goods']['goods_body']; ?></div>
            <?php if (isset($output['plate_bottom'])) {?>
            <div class="bottom-template"><?php echo $output['plate_bottom']['plate_content']?></div>
            <?php }?>
          </div>
        </div>
      </div>
      <div class="imcs-comment">
        <div class="imcs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_evaluation'];?></a></h4>
        </div>
        <div class="imcs-goods-info-content bd" id="imGoodsRate">
          <div class="top">
            <div class="rate">
              <p><strong><?php echo $output['goods_evaluate_info']['good_percent'];?></strong><sub>%</sub>好评</p>
              <span>共有<?php echo $output['goods_evaluate_info']['all'];?>人参与评分</span></div>
            <div class="percent">
              <dl>
                <dt>好评<em>(<?php echo $output['goods_evaluate_info']['good_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['goods_evaluate_info']['good_percent'];?>%"></i></dd>
              </dl>
              <dl>
                <dt>中评<em>(<?php echo $output['goods_evaluate_info']['normal_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['goods_evaluate_info']['normal_percent'];?>%"></i></dd>
              </dl>
              <dl>
                <dt>差评<em>(<?php echo $output['goods_evaluate_info']['bad_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['goods_evaluate_info']['bad_percent'];?>%"></i></dd>
              </dl>
            </div>
            <div class="btns"><span>您可对已购商品进行评价</span>
              <p><a href="<?php if ($output['goods']['is_virtual']) { echo urlShop('member_vr_order', 'index');} else { echo urlShop('member_order', 'index');}?>" class="imcs-btn imcs-btn-red" target="_blank"><i class="fa fa-comment-o"></i>评价商品</a></p>
            </div>
          </div>
          <div class="imcs-goods-title-nav">
            <ul id="comment_tab">
              <li data-type="all" class="current"><a href="javascript:void(0);"><?php echo $lang['goods_index_evaluation'];?>(<?php echo $output['goods_evaluate_info']['all'];?>)</a></li>
              <li data-type="1"><a href="javascript:void(0);">好评(<?php echo $output['goods_evaluate_info']['good'];?>)</a></li>
              <li data-type="2"><a href="javascript:void(0);">中评(<?php echo $output['goods_evaluate_info']['normal'];?>)</a></li>
              <li data-type="3"><a href="javascript:void(0);">差评(<?php echo $output['goods_evaluate_info']['bad'];?>)</a></li>
            </ul>
          </div>
          <!-- 商品评价内容部分 -->
          <div id="goodseval" class="imcs-commend-main"></div>
        </div>
      </div>
      <div class="imcg-salelog">
        <div class="imcs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_sold_record'];?></a></h4>
        </div>
        <div class="imcs-goods-info-content bd" id="imGoodsTraded">
          <div class="top">
            <div class="price"><?php echo $lang['goods_index_goods_price'];?><strong><?php echo $output['goods']['goods_price'];?></strong><?php echo $lang['goods_index_yuan'];?><span><?php echo $lang['goods_index_price_note'];?></span></div>
          </div>
          <!-- 成交记录内容部分 -->
          <div id="salelog_demo" class="imcs-loading"> </div>
        </div>
      </div>
      <div class="imcs-consult">
        <div class="imcs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_goods_consult'];?></a></h4>
        </div>
        <div class="imcs-goods-info-content bd" id="imGuestbook">
          <!-- 咨询留言内容部分 -->
          <div id="consulting_demo" class="imcs-loading"> </div>
        </div>
      </div>
      <?php if(!empty($output['goods_commend']) && is_array($output['goods_commend']) && count($output['goods_commend'])>1){?>
      <div class="imcs-recommend">
        <div class="title">
          <h4><?php echo $lang['goods_index_goods_commend'];?></h4>
        </div>
        <div class="content">
          <ul>
            <?php foreach($output['goods_commend'] as $goods_commend){?>
            <?php if($output['goods']['goods_id'] != $goods_commend['goods_id']){?>
            <li>
              <dl>
                <dt class="goods-name"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_commend['goods_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><?php echo $goods_commend['goods_name'];?><em><?php echo $goods_commend['goods_jingle'];?></em></a></dt>
                <dd class="goods-pic"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_commend['goods_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><img src="<?php echo thumb($goods_commend, 240);?>" alt="<?php echo $goods_commend['goods_name'];?>"/></a></dd>
                <dd class="goods-price"><?php echo $lang['currency'];?><?php echo $goods_commend['goods_price'];?></dd>
              </dl>
            </li>
            <?php }?>
            <?php }?>
          </ul>
          <div class="clear"></div>
        </div>
      </div>
      <?php }?>
    </div>    
  </div>
</div>
<form id="buynow_form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php">
  <input id="act" name="act" type="hidden" value="buy" />
  <input id="op" name="op" type="hidden" value="buy_step1" />
  <input id="cart_id" name="cart_id[]" type="hidden"/>
</form>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.F_slider.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />

<script type="text/javascript">
/** 辅助浏览 **/
jQuery(function($){	
	//产品图片
	$.getScript('<?php echo SHOP_RESOURCE_SITE_URL?>/js/ImageZoom.js', function(){
		var
		zoomController,
		zoomControllerUl,
		zoomControllerUlLeft = 0,
		shell = $('#imcs-goods-picture'),
		shellPanel = shell.parent(),
		heightNcsDetail = $('div[class="imcs-detail"]').height();
		heightOffset = 60,
		minGallerySize = [360, 360],
		imageZoom = new ImageZoom({
			shell: shell,
			basePath: '',
			levelASize: [60, 60],
			levelBSize: [320, 320],
			gallerySize: minGallerySize,
			onBeforeZoom: function(index, level){
				if(!zoomController){
					zoomController = shell.find('div.controller');
				}

				var
				self = this,
				duration = 320,
				width = minGallerySize[0],
				height = minGallerySize[1],
				zoomFx = function(){
					self.ops.gallerySize = [width, height];
					self.galleryPanel.stop().animate({width:width, height:height}, duration);
					shellPanel.stop().animate({height:height + heightOffset}, duration).css('overflow', 'visible');
					zoomController.animate({width:width-22}, duration);
					shell.stop().animate({width:width}, duration);
				};
				if(level !== this.level && this.level !== 0){
					if(this.level === 1 && level > 1){
						height = Math.max(480, shellPanel.height());
						width = shellPanel.width();
						zoomFx();
					}
					else if(level === 1){
						zoomFx();
						shellPanel.stop().animate({height:heightNcsDetail}, duration);
					}
				}
			},
			onZoom: function(index, level, prevIndex){
				shell.find('a.prev,a.next')[level<3 ? 'removeClass' : 'addClass']('hide');
				shell.find('a.close').css('display', [level>1 ? 'block' : 'none']);
			},
			items: [
	                <?php if (!empty($output['goods_image'])) {?>
	                <?php echo implode(',', $output['goods_image']);?>
	                <?php }?>
					]
		});
		shell.data('imageZoom', imageZoom);
	});

});

    //收藏分享处下拉操作
    jQuery.divselect = function(divselectid,inputselectid) {
      var inputselect = $(inputselectid);
      $(divselectid).mouseover(function(){
          var ul = $(divselectid+" ul");
          ul.slideDown("fast");
          if(ul.css("display")=="none"){
              ul.slideDown("fast");
          }
      });
      $(divselectid).live('mouseleave',function(){
          $(divselectid+" ul").hide();
      });
    };
$(function(){
	//赠品处滚条
	$('#imsGoodsGift').perfectScrollbar();
    <?php if ($output['goods']['goods_state'] == 1 && $output['goods']['goods_storage'] > 0 ) {?>
    // 加入购物车
    $('a[imtype="addcart_submit"]').click(function(){
        addcart(<?php echo $output['goods']['goods_id'];?>, checkQuantity(),'addcart_callback');
    });
        <?php if (!($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>
        // 立即购买
        $('a[imtype="buynow_submit"]').click(function(){
            buynow(<?php echo $output['goods']['goods_id']?>,checkQuantity());
        });
        <?php }?>
    <?php }?>
    // 到货通知
    <?php if ($output['goods']['goods_storage'] == 0 || $output['goods']['goods_state'] == 0) {?>
    $('a[imtype="arrival_notice"]').click(function(){
        <?php if ($_SESSION['is_login'] !== '1'){?>
        login_dialog();
        <?php }else{?>
        ajax_form('arrival_notice', '到货通知','<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $output['goods']['goods_id']));?>', 350);
        <?php }?>
    });
    <?php }?>
    <?php if (($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) && $output['goods']['is_appoint'] == 1) {?>
    $('a[imtype="appoint_submit"]').click(function(){
        <?php if ($_SESSION['is_login'] !== '1'){?>
        login_dialog();
        <?php }else{?>
        ajax_form('arrival_notice', '立即预约', '<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $output['goods']['goods_id'], 'type' => 2));?>', 350);
        <?php }?>
    });
    <?php }?>
    //浮动导航  waypoints.js
    $('#main-nav').waypoint(function(event, direction) {
        $(this).parent().parent().parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    });

    // 分享收藏下拉操作
    $.divselect("#handle-l");
    $.divselect("#handle-r");

    // 规格选择
    $('dl[imtype="imcss-spec"]').find('a').each(function(){
        $(this).click(function(){
            if ($(this).hasClass('hovered')) {
                return false;
            }
            $(this).parents('ul:first').find('a').removeClass('hovered');
            $(this).addClass('hovered');
            checkSpec();
        });
    });

});

function checkSpec() {
    var spec_param = <?php echo $output['spec_list'];?>;
    var spec = new Array();
    $('ul[nctyle="ul_sign"]').find('.hovered').each(function(){
        var data_str = ''; eval('data_str =' + $(this).attr('data-param'));
        spec.push(data_str.valid);
    });
    spec1 = spec.sort(function(a,b){
        return a-b;
    });
    var spec_sign = spec1.join('|');
    $.each(spec_param, function(i, n){
        if (n.sign == spec_sign) {
            window.location.href = n.url;
        }
    });
}

// 验证购买数量
function checkQuantity(){
    var quantity = parseInt($("#quantity").val());
    if (quantity < 1) {
        alert("<?php echo $lang['goods_index_pleaseaddnum'];?>");
        $("#quantity").val('1');
        return false;
    }
    max = parseInt($('[imtype="goods_stock"]').text());
    <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) {?>
    max = <?php echo $output['goods']['virtual_limit'];?>;
    if(quantity > max){
        alert('最多限购'+max+'件');
        return false;
    }
    <?php } ?>
    <?php if (!empty($output['goods']['upper_limit'])) {?>
    max = <?php echo $output['goods']['upper_limit'];?>;
    if(quantity > max){
        alert('最多限购'+max+'件');
        return false;
    }
    <?php } ?>
    if(quantity > max){
        alert("<?php echo $lang['goods_index_add_too_much'];?>");
        return false;
    }
    return quantity;
}

// 立即购买js
function buynow(goods_id,quantity){
<?php if ($_SESSION['is_login'] !== '1'){?>
	login_dialog();
<?php }else{?>
    if (!quantity) {
        return;
    }
    <?php if ($_SESSION['store_id'] == $output['goods']['store_id']) { ?>
    alert('不能购买自己店铺的商品');return;
    <?php } ?>
    $("#cart_id").val(goods_id+'|'+quantity);
    $("#buynow_form").submit();
<?php }?>
}

$(function(){
    //选择地区查看运费
    $('#transport_pannel>a').click(function(){
    	var id = $(this).attr('imtype');
    	if (id=='undefined') return false;
    	var _self = this,tpl_id = '<?php echo $output['goods']['transport_id'];?>';
	    var url = 'index.php?act=goods&op=calc&rand='+Math.random();
	    $('#transport_price').css('display','none');
	    $('#loading_price').css('display','');
	    $.getJSON(url, {'id':id,'tid':tpl_id}, function(data){
	    	if (data == null) return false;
	        if(data != 'undefined') {$('#im_kd').html('运费<?php echo $lang['im_colon'];?><em>' + data + '</em><?php echo $lang['goods_index_yuan'];?>');}else{'<?php echo $lang['goods_index_trans_for_seller'];?>';}
	        $('#transport_price').css('display','');
	    	$('#loading_price').css('display','none');
	        $('#imrecive').html($(_self).html());
	    });
    });
    $("#imcss-bundling").load('index.php?act=goods&op=get_bundling&goods_id=<?php echo $output['goods']['goods_id'];?>', function(){
        if($(this).html() != '') {
            $(this).show();
        }
    });
    $("#salelog_demo").load('index.php?act=goods&op=salelog&goods_id=<?php echo $output['goods']['goods_id'];?>&store_id=<?php echo $output['goods']['store_id'];?>&vr=<?php echo $output['goods']['is_virtual'];?>', function(){
        // Membership card
        $(this).find('[imtype="mcard"]').membershipCard({type:'shop'});
    });
	$("#consulting_demo").load('index.php?act=goods&op=consulting&goods_id=<?php echo $output['goods']['goods_id'];?>&store_id=<?php echo $output['goods']['store_id'];?>', function(){
		// Membership card
		$(this).find('[imtype="mcard"]').membershipCard({type:'shop'});
	});

/** goods.php **/
	// 商品内容部分折叠收起侧边栏控制
	$('#fold').click(function(){
  		$('.imcs-goods-layout').toggleClass('expanded');
	});
	// 商品内容介绍Tab样式切换控制
	$('#categorymenu').find("li").click(function(){
		$('#categorymenu').find("li").removeClass('current');
		$(this).addClass('current');
	});
	// 商品详情默认情况下显示全部
	$('#tabGoodsIntro').click(function(){
		$('.bd').css('display','');
		$('.hd').css('display','');
	});
	// 点击评价隐藏其他以及其标题栏
	$('#tabGoodsRate').click(function(){
		$('.bd').css('display','none');
		$('#imGoodsRate').css('display','');
		$('.hd').css('display','none');
	});
	// 点击成交隐藏其他以及其标题
	$('#tabGoodsTraded').click(function(){
		$('.bd').css('display','none');
		$('#imGoodsTraded').css('display','');
		$('.hd').css('display','none');
	});
	// 点击咨询隐藏其他以及其标题
	$('#tabGuestbook').click(function(){
		$('.bd').css('display','none');
		$('#imGuestbook').css('display','');
		$('.hd').css('display','none');
	});
	//商品排行Tab切换
	$(".imcs-top-tab > li > a").mouseover(function(e) {
		if (e.target == this) {
			var tabs = $(this).parent().parent().children("li");
			var panels = $(this).parent().parent().parent().children(".imcs-top-panel");
			var index = $.inArray(this, $(this).parent().parent().find("a"));
			if (panels.eq(index)[0]) {
				tabs.removeClass("current ").eq(index).addClass("current ");
				panels.addClass("hide").eq(index).removeClass("hide");
			}
		}
	});
	//信用评价动态评分打分人次Tab切换
	$(".imcs-rate-tab > li > a").mouseover(function(e) {
		if (e.target == this) {
			var tabs = $(this).parent().parent().children("li");
			var panels = $(this).parent().parent().parent().children(".imcs-rate-panel");
			var index = $.inArray(this, $(this).parent().parent().find("a"));
			if (panels.eq(index)[0]) {
				tabs.removeClass("current ").eq(index).addClass("current ");
				panels.addClass("hide").eq(index).removeClass("hide");
			}
		}
	});

//触及显示缩略图
	$('.goods-pic > .thumb').hover(
		function(){
			$(this).next().css('display','block');
		},
		function(){
			$(this).next().css('display','none');
		}
	);

	/* 商品购买数量增减js */
	// 增加
	$('.increase').click(function(){
		num = parseInt($('#quantity').val());
	    <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) {?>
	    max = <?php echo $output['goods']['virtual_limit'];?>;
	    if(num >= max){
	        alert('最多限购'+max+'件');
	        return false;
	    }
	    <?php } ?>
	    <?php if (!empty($output['goods']['upper_limit'])) {?>
	    max = <?php echo $output['goods']['upper_limit'];?>;
	    if(num >= max){
	        alert('最多限购'+max+'件');
	        return false;
	    }
	    <?php } ?>
		max = parseInt($('[imtype="goods_stock"]').text());
		if(num < max){
			$('#quantity').val(num+1);
		}
	});
	//减少
	$('.decrease').click(function(){
		num = parseInt($('#quantity').val());
		if(num > 1){
			$('#quantity').val(num-1);
		}
	});

    //评价列表
    $('#comment_tab').on('click', 'li', function() {
        $('#comment_tab li').removeClass('current');
        $(this).addClass('current');
        load_goodseval($(this).attr('data-type'));
    });
    load_goodseval('all');
    function load_goodseval(type) {
        var url = '<?php echo urlShop('goods', 'comments', array('goods_id' => $output['goods']['goods_id']));?>';
        url += '&type=' + type;
        $("#goodseval").load(url, function(){
            $(this).find('[imtype="mcard"]').membershipCard({type:'shop'});
        });
    }

    //记录浏览历史
	$.get("index.php?act=goods&op=addbrowse",{gid:<?php echo $output['goods']['goods_id'];?>});
	//初始化对比按钮
	initCompare();
});
/* 加入购物车后的效果函数 */
function addcart_callback(data){
	$('#bold_num').html(data.num);
    $('#bold_mly').html(price_format(data.amount));
    $('.imcs-cart-popup').fadeIn('fast');
}
</script>
