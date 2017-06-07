<div class="eject_con">
  <form id="commisdetail_form" method="post" target="_parent" action="<?php echo urlShop('seller_commisdetail', 'commisdetail_save');?>">
    <?php if ($output['commis_detail']['mcd_id']!=0) { ?>
    <input type="hidden" name="mcd_id" value="<?php echo $output['commis_detail']['mcd_id']; ?>" />
    <?php } ?>
    <dl>
      <dt>店铺ID：</dt>
      <dd>
        <input class="text w200" type="text" name="store_id" id="store_id" value="<?php echo $output['commis_detail']['store_id']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>店铺名称：</dt>
      <dd>
        <input class="text w200" type="text" name="store_name" id="store_name" value="<?php echo $output['commis_detail']['store_name']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>推广导/购员id：</dt>
      <dd>
        <input class="text w200" type="text" name="saleman_id" id="saleman_id" value="<?php echo $output['commis_detail']['saleman_id']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>推广/导购员名称：</dt>
      <dd>
        <input class="text w200" type="text" name="saleman_name" id="saleman_name" value="<?php echo $output['commis_detail']['saleman_name']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>订单索引id：</dt>
      <dd>
        <input class="text w200" type="text" name="order_id" id="order_id" value="<?php echo $output['commis_detail']['order_id']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>订单编号：</dt>
      <dd>
        <input class="text w200" type="text" name="order_sn" id="order_sn" value="<?php echo $output['commis_detail']['order_sn']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>订单总价格：</dt>
      <dd>
        <input class="text w200" type="text" name="goods_amount" id="goods_amount" value="<?php echo $output['commis_detail']['goods_amount']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>抽佣比例：</dt>
      <dd>
        <input class="text w200" type="text" name="commis_rate" id="commis_rate" value="<?php echo $output['commis_detail']['commis_rate']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>抽佣金额：</dt>
      <dd>
        <input class="text w200" type="text" name="mb_commis_totals" id="mb_commis_totals" value="<?php echo $output['commis_detail']['mb_commis_totals']; ?>" />
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="保存" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#commisdetail_form').validate({
    	submitHandler:function(form){
    		ajaxpost('commisdetail_form', '', '', 'onerror') 
    	},
        rules : {
            order_sn : {
                required : true
            },
            mb_commis_totals : {
				required : true,
                number   : true
            }
        },
        messages : {
            order_sn : {
                required : '订单编号不能为空'

            },
            mb_commis_totals  : {
				required : '抽佣金额不能为空',
                number   : '抽佣金额必须是数字'
            }
        }
    });
});
</script> 
