<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <?php if ($output['error'] == '') {?>
  <form <?php if ($output['type'] == 'book') {?>id="choosed_goods_form"<?php }else{?>id="choosed_presell_form"<?php }?> action="<?php echo urlShop('store_promotion_book', 'choosed_goods');?>" method="post" >
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="gid" value="<?php echo $output['goods_info']['goods_id'];?>" />
    <input type="hidden" name="type" value="<?php echo $output['type'];?>" />
    <div class="selected-goods-info">
      <div class="goods-thumb"><img src="<?php echo thumb($output['goods_info'], 240);?>" alt=""></div>
      <dl class="goods-info">
        <dt><?php echo $output['goods_info']['goods_name']?> (SKU：<?php echo $output['goods_info']['goods_id'];?>)</dt>
        <dd>销售价格：<strong class="red"><?php echo $lang['currency']; ?><?php echo imPriceFormat($output['goods_info']['goods_price']);?></strong></dd>
        <dd>库存：<span><?php echo $output['goods_info']['goods_storage']?></span> 件</dd>
        <?php if (!empty($output['goods_spec'])) {?>
        <dd>
          <?php foreach ($output['goods_spec'] as $key => $val) {?>
          <?php echo $output['spec_name'][$key];?>：<span class="mr20"><?php echo $val?></span>
          <?php }?>
        </dd>
        <?php }?>
      </dl>
    </div>
    <?php if ($output['type'] == 'book') {?>
    <dl>
      <dt>预定期间：</dt>
      <dd>
        <input name="down_time" type="text" class="text w70">
        <em class="add-on"><i class="fa fa-calendar"></i></em>
        <p class="hint">请选择预定活动截止时间（当日24:00时），即预定活动开始至结束时间段。同时该时间点也作为订单第二阶段尾款支付时间起始点。（如买家一次性支付定金与尾款系统将不会提醒买家进行尾款支付，商家直接进入备货阶段）。</p>
      </dd>
    </dl>
    <dl>
      <dt>预定活动售价：</dt>
      <dd>
        <input name="total_payment" type="text" class="text w70">
        <em class="add-on">元</em>
        <p class="hint">预定活动期间商品优惠价格，预定期满后将恢复商品原价。</p>
      </dd>
    </dl>
    <dl>
      <dt>第一阶段 - 定金额：</dt>
      <dd>
        <input name="down_payment" type="text" class="text w70">
        <em class="add-on">元</em>
        <p class="hint">定金即预定商品第一阶段应付款，注意：定金设置不应超过预定总价的20%。</p>
      </dd>
    </dl>
    <dl>
      <dt>第二阶段 - 尾款金额：</dt>
      <dd>
        <input name="final_payment" type="text" readonly class="text w70">
        <em class="add-on">元</em>
        <p class="hint">系统将根据预定总价和定金自动计算第二阶段应支付的尾款金额。</p>
      </dd>
    </dl>
    <?php } else if($output['type'] == 'presell') {?>
      <dl>
	      <dt>预售活动售价：</dt>
	      <dd>
	        <input name="promotion_price" type="text" class="text w70">
	        <em class="add-on">元</em>
	        <p class="hint">预售活动期间商品优惠价格，预售期满后将恢复商品原价。</p>
	      </dd>
      </dl>
      <dl imtype="virtual_null" >
        <dt>发货时间<?php echo $lang['im_colon'];?></dt>
        <dd>
          <ul class="imsc-form-radio-list">
            <li>
              <input type="radio" name="is_deliverdate" id="is_deliverdate_0" value="0" <?php if($output['goods_info']['presell_days'] != 0) {?>checked<?php }?>>
              <label for="is_deliverdate_0">下单后几天内发货</label>
            </li>
          	<li>
              <input type="radio" name="is_deliverdate" id="is_deliverdate_1" value="1" <?php if($output['goods_info']['presell_deliverdate'] != 0) {?>checked<?php }?>>
              <label for="is_deliverdate_1">同一时间发货</label>
            </li>
          </ul>
          <p class="hint vital">*全款预售商品可以支持发货时间两种情况：<br/>1）默认每个用户下单付款后几天内发货；2）统一某个时间发货。</p>
        </dd>
      </dl>
      <dl imtype="is_deliverdays" <?php if ($output['goods_info']['presell_deliverdate'] != 0) {?>style="display:none;"<?php }?>>
        <dt><i class="required">*</i>几天后发货<?php echo $lang['im_colon'];?></dt>
        <dd>
          <input name="presell_days" value="" type="text" class="text w70" /><em class="add-on">天</em>
          <p class="hint">请填写预售活动的商家发货时间。</p>
        </dd>
      </dl>
      <dl imtype="is_deliverdate" <?php if ($output['goods_info']['presell_days'] != 0) {?>style="display:none;"<?php }?>>
      <dt>商家发货时间：</dt>
        <dd>
          <input name="presell_deliverdate" type="text" class="text w70">
          <em class="add-on"><i class="fa fa-calendar"></i></em>
          <p class="hint">请选择预售活动的商家发货时间。</p>
        </dd>
      </dl>
    
    <?php }?>
    <div class="eject_con">
      <div class="bottom">
        <label class="submit-border"><a id="btn_submit" class="submit" href="javascript:void(0);">提交</a></label>
      </div>
    </div>
  </form>
  <?php } else {?>
  <table class="imsc-default-table imsc-promotion-buy">
    <tbody>
      <tr>
        <td colspan="20" class="norecord"><div class="no-promotion"><span><?php echo $output['error'];?></span></div></td>
      </tr>
    </tbody>
  </table>
  <?php }?>
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css" />
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script> 
<script>
$(function(){
    // 时间控件
    $('input[name="down_time"]').datepicker({minDate: 0<?php if (!checkPlatformStore()) { echo ", maxDate: '" . date('Y-m-d', $output['book_info']['bkq_endtime']) . "'";}?>});
    $('input[name="presell_deliverdate"]').datepicker({minDate: 0<?php if (!checkPlatformStore()) { echo ", maxDate: '" . date('Y-m-d', $output['book_info']['bkq_endtime']) . "'";}?>});
    /* 全款预售 时间控制 */
	var is_deliverdays = <?php echo $output['goods_info']['presell_days'];?>;
	var is_deliverdate = <?php echo $output['goods_info']['presell_deliverdate'];?>;
	if(is_deliverdays =='0'&&is_deliverdate=='0'){
		$("#is_deliverdate_0").attr("checked","checked");
	    $("#is_deliverdate_1").removeAttr("checked");
	    $('[imtype="is_deliverdate"]').hide();
        $('[imtype="is_deliverdays"]').show();
	}
    /* 预售控制 */
    $('#is_deliverdate_0').change(function(){
            $('[imtype="is_deliverdate"]').hide();
            $('[imtype="is_deliverdays"]').show();
    });
    $('#is_deliverdate_1').change(function(){
            $('[imtype="is_deliverdate"]').show();
            $('[imtype="is_deliverdays"]').hide();
    });
    
    // 提交表单
    $("#btn_submit").click(function(){
    	<?php if ($output['type'] == 'book') {?>
    		$("#choosed_goods_form").submit();
    	<?php }else{?>
    		$("#choosed_presell_form").submit();
    	<?php }?>
    });
    // 计算合计总价
    $('input[name="down_payment"],input[name="total_payment"]').change(function(){
        totalPayment();
    });

    jQuery.validator.addMethod("checkDownPayment", function(value, element) {
        if (parseFloat($('input[name="total_payment"]').val()) * 0.2 >= parseFloat($('input[name="down_payment"]').val())) {
            return true;
        } else {
            return false;
        }
    },'<i class="icon-exclamation-sign"></i>定金价格不能超过预定价格的20%');

    // 页面输入内容验证
    $("#choosed_goods_form").validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
        submitHandler:function(form){
            ajaxpost('choosed_goods_form', '', '', 'onerror');
        },
        rules : {
            total_payment: {
                required : true,
                max : <?php echo $output['goods_info']['goods_price'];?>,
                min : 0.01
            },
            down_payment: {
                required : true,
                number : true,
                min : 0.01,
                checkDownPayment : true
            },
            down_time: {
                required : true
            }
        },
        messages : {
            total_payment: {
                required : "<i class='icon-exclamation-sign'></i>合计总价不能为空，不能超过商品销售价格",
                max : "<i class='icon-exclamation-sign'></i>合计总价不能为空，不能超过商品销售价格",
                min : "<i class='icon-exclamation-sign'></i>合计总价不能为空，不能超过商品销售价格"
            },
            down_payment: {
                required : "<i class='icon-exclamation-sign'></i>定金价格不能为空，且必须小于商品价格",
                number : "<i class='icon-exclamation-sign'></i>定金价格不能为空，且必须小于商品价格",
                min : "<i class='icon-exclamation-sign'></i>定金价格不能为空，且必须小于商品价格"
            },
            down_time: {
                required : "<i class='icon-exclamation-sign'></i>请选择尾款支付时间"
            }
        }
    });

    jQuery.validator.addMethod("checkDeliverdate", function(value, element) {
        if (($('#is_deliverdate_1').prop("checked") && $('input[name="presell_deliverdate"]').val()!='')||($('#is_deliverdate_0').prop("checked"))) {
            return true;
        } else {
            return false;
        }
    },'<i class="icon-exclamation-sign"></i>请选择预售活动的商家发货时间');

    jQuery.validator.addMethod("checkDeliverday", function(value, element) {
    	if (($('#is_deliverdate_0').prop("checked") && $('input[name="presell_days"]').val()!='')||($('#is_deliverdate_1').prop("checked"))) {
            return true;
        } else {
            return false;
        }
    },'<i class="icon-exclamation-sign"></i>请填写预售活动的几天内发货');
    // 页面输入内容验证
    $("#choosed_presell_form").validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
        submitHandler:function(form){
            ajaxpost('choosed_presell_form', '', '', 'onerror');
        },
        rules : {
        	presell_deliverdate: {
        		checkDeliverdate : true
            },
            presell_days: {
            	checkDeliverday : true
            }
        },
        messages : {
        	presell_deliverdate: {
                required : "<i class='icon-exclamation-sign'></i>请选择预售活动的商家发货时间"
            },
            presell_days: {
                required : "<i class='icon-exclamation-sign'></i>请填写预售活动的几天内发货"
            }
        }
    });
});

// 计算合计总价
function totalPayment() {
    _down = parseFloat($('input[name="down_payment"]').val());
    _total = parseFloat($('input[name="total_payment"]').val());

    _down = isNaN(_down) ? 0 : _down;
    _total = isNaN(_total) ? 0 : _total;
    _final = _total - _down;
    $('input[name="final_payment"]').val(_final.toFixed(2));
}
</script>