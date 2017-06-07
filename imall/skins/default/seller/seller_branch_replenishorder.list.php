<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<form method="get" action="index.php" target="_self">
  <table class="search-form">
    <input type="hidden" name="act" value="seller_branch_stubbs" />
    <input type="hidden" name="op" value="replenish_order" />
    <?php if ($_GET['state_type']) { ?>
    <input type="hidden" name="state_type" value="<?php echo $_GET['state_type']; ?>" />
    <?php } ?>
    <tr>
      <td>&nbsp;</td>
      <?php if ($_GET['state_type'] == 'store_order') { ?>
      <td><input type="checkbox" id="skip_off" value="1" <?php echo $_GET['skip_off'] == 1 ? 'checked="checked"' : null;?>  name="skip_off"> <label for="skip_off">不显示已关闭的订单</label></td>
      <?php } ?>
      <th>下单时间</th>
      <td class="w240">
        <input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>&nbsp;&#8211;&nbsp;
        <input id="query_end_date" class="text w70" type="text" name="query_end_date" value="<?php echo $_GET['query_end_date']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>
      </td>
      <th>分店</th>
      <td class="w100"><input type="text" class="text w80" name="buyer_name" value="<?php echo $_GET['buyer_name']; ?>" /></td>
      <th>订单编号</th>
      <td class="w160"><input type="text" class="text w150" name="order_sn" value="<?php echo $_GET['order_sn']; ?>" /></td>
      <td class="w70 tc"><label class="submit-border">
          <input type="submit" class="submit" value="查找" />
        </label></td>
    </tr>
  </table>
</form>
<table class="imsc-default-table order">
  <thead>
    <tr>
      <th class="w10"></th>
      <th colspan="2">商品</th>
      <th class="w100">调拔价(元)</th>
      <th class="w40">数量</th>
      <th class="w110">分店</th>
      <th class="w120">订单金额</th>
      <th class="w100">交易状态</th>
      <th class="w150">交易操作</th>
    </tr>
  </thead>
  <?php if (is_array($output['order_list']) and !empty($output['order_list'])) { ?>
  <?php foreach($output['order_list'] as $order_id => $order) { ?>
  <tbody>
    <tr>
      <td colspan="20" class="sep-row"></td>
    </tr>
    <tr>
      <th colspan="20">
        <span class="ml10">订单编号<?php echo $lang['im_colon'];?><em><?php echo $order['order_sn']; ?></em></span> 
        <span>下单时间<?php echo $lang['im_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$order['add_time']); ?></em></span> 
      </th>
    </tr>
    <?php $i = 0;?>
    <?php foreach($order['goods_list'] as $k => $goods) { ?>
    <?php $i++;?>
    <tr>
      <td class="bdl"></td>
      <td class="w70">
        <div class="imsc-goods-thumb">
          <a href="<?php echo $goods['goods_url'];?>" target="_blank">
          <img src="<?php echo $goods['image_60_url'];?>" onMouseOver="toolTip('<img src=<?php echo $goods['image_240_url'];?>>')" onMouseOut="toolTip()"/>
          </a>
        </div>
      </td>
      <td class="tl">
        <dl class="goods-name">
          <dt><a target="_blank" href="<?php echo $goods['goods_url'];?>"><?php echo $goods['goods_name']; ?></a></dt>
        </dl>
      </td>
      <td><?php echo $goods['goods_tradeprice']; ?></td>
      <td><?php echo $goods['goods_num']; ?></td>

      <!-- S 合并TD -->
      <?php if (($order['goods_count'] > 1 && $k ==0) || ($order['goods_count']) == 1){ ?>
      <td class="bdl" rowspan="<?php echo $order['goods_count'];?>">
        <div class="buyer"><?php echo $order['branch_name'];?>
          <p member_id="<?php echo $order['branch_id'];?>">
            <?php if(!empty($order['extend_branch']['store_qq'])){?>
            <a target="_blank" href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $order['extend_branch']['store_qq'];?>&site=qq&menu=yes" title="QQ: <?php echo $order['extend_branch']['store_qq'];?>"><img border="0" src="http://wpa.qq.com/pa?p=2:<?php echo $order['extend_branch']['store_qq'];?>:52" style=" vertical-align: middle;"/></a>
            <?php }?>
            <?php if(!empty($order['extend_branch']['store_ww'])){?>
            <a target="_blank" href="http://amos.im.alisoft.com/msg.aw?v=2&uid=<?php echo $order['extend_branch']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" ><img border="0" src="http://amos.im.alisoft.com/online.aw?v=2&uid=<?php echo $order['extend_branch']['store_ww'];?>&site=cntaobao&s=2&charset=<?php echo CHARSET;?>" alt="Wang Wang" style=" vertical-align: middle;" /></a>
            <?php }?>
          </p>
          <div class="buyer-info"> <em></em>
            <div class="con">
              <h3><i></i><span>联系信息</span></h3>
              <dl>
                <dt>分店<?php echo $lang['im_colon'];?></dt>
                <dd><?php echo $order['extend_branch']['store_name'];?></dd>
              </dl>
              <dl>
                <dt>电话<?php echo $lang['im_colon'];?></dt>
                <dd><?php echo $order['extend_branch']['store_phone'];?></dd>
              </dl>
              <dl>
                <dt>地址<?php echo $lang['im_colon'];?></dt>
                <dd><?php echo $order['extend_branch']['store_address'];?></dd>
              </dl>
            </div>
          </div>
        </div>
      </td>
      <td class="bdl" rowspan="<?php echo $order['goods_count'];?>"><p class="imsc-order-amount"><?php echo $order['order_amount']; ?></p>
        <p class="goods-freight">
          <?php if ($order['shipping_fee'] > 0){?>
          (含运费<?php echo $order['shipping_fee'];?>)
          <?php }else{?>
          <?php echo $lang['im_common_shipping_free'];?>
          <?php }?>
        </p>
        <p class="goods-pay" title="<?php echo $lang['store_order_pay_method'].$lang['im_colon'];?><?php echo $order['payment_name']; ?>"><?php echo $order['payment_name']; ?></p>
      </td>
      <td class="bdl bdr" rowspan="<?php echo $order['goods_count'];?>">
        <p><?php echo $order['state_desc']; ?></p>
        <!-- 物流跟踪 -->
        <p>
          <?php if ($order['if_deliver']) { ?>
          <a href='index.php?act=store_deliver&op=search_deliver&order_sn=<?php echo $order['order_sn']; ?>'><?php echo $lang['store_order_show_deliver'];?></a>
          <?php } ?>
        </p>
      </td>
      
      <td class="bdl bdr" rowspan="<?php echo $order['goods_count'];?>">
        <!-- 取消订单 -->
        <?php if($order['if_cancel']) { ?>
        <p><a href="javascript:void(0)" class="imsc-btn imsc-btn-red mt5" im_type="dialog" uri="index.php?act=store_order&op=change_state&state_type=order_cancel&order_sn=<?php echo $order['order_sn']; ?>&order_id=<?php echo $order['order_id']; ?>" dialog_title="<?php echo $lang['store_order_cancel_order'];?>" dialog_id="seller_order_cancel_order" dialog_width="400" id="order<?php echo $order['order_id']; ?>_action_cancel" /><i class="fa fa-times-circle-o"></i>取消订单</a></p>
        <?php } ?>
        
        <!-- 调整费用 -->
		<?php if ($order['order_state']==20) {?>
		<p>
		  <a href="javascript:void(0)" class="imsc-btn-mini imsc-btn-green mt10" uri="index.php?act=store_order&op=change_state&state_type=edit_amount_price&order_sn=<?php echo $order['order_sn']; ?>&order_id=<?php echo $order['order_id']; ?>" dialog_width="480" dialog_title="调整费用" im_type="dialog"  dialog_id="seller_order_adjust_fee" id="order<?php echo $order['order_id']; ?>_action_adjust_fee" /><i class="fa fa-pencil"></i>调整费用</a>
		</p>
		<?php } ?>
        
        <!-- 发货 -->
        <?php if ($order['if_send']) { ?>
        <p><a class="imsc-btn imsc-btn-green mt10" href="index.php?act=store_deliver&op=send&order_id=<?php echo $order['order_id']; ?>"/><i class="fa fa-truck"></i>发货设置</a></p>
        <?php } ?>
      </td>
      
      <?php } ?>
      <!-- E 合并TD -->
    </tr>

    <?php }?>
    <?php } } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (is_array($output['order_list']) and !empty($output['order_list'])) { ?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('.checkall_s').click(function(){
        var if_check = $(this).attr('checked');
        $('.checkitem').each(function(){
            if(!this.disabled)
            {
                $(this).attr('checked', if_check);
            }
        });
        $('.checkall_s').attr('checked', if_check);
    });
    $('#skip_off').click(function(){
        url = location.href.replace(/&skip_off=\d*/g,'');
        window.location.href = url + '&skip_off=' + ($('#skip_off').attr('checked') ? '1' : '0');
    });
});
</script> 