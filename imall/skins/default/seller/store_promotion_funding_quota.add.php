<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="imsc-form-default">
  <form id="add_form" action="index.php?act=store_promotion_funding&op=funding_quota_add_save" method="post">
    <dl>
      <dt><i class="required">*</i><?php echo $lang['funding_quota_add_quantity'].$lang['im_colon'];?></dt>
      <dd>
          <input id="funding_quota_quantity" name="funding_quota_quantity" type="text" class="text w30" /><em class="add-on"><?php echo $lang['text_month'];?></em><span></span>
        <p class="hint"><?php echo $lang['funding_price_explain1'];?></p>
        <p class="hint"><?php echo $lang['funding_price_explain2'].$output['setting_config']['promotion_funding_price'].$lang['im_yuan'];?> ; </p>
        <p class="hint"><strong style="color: red">相关费用会在店铺的账期结算中扣除</strong></p>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border"><input id="submit_button" type="submit" class="submit" value="<?php echo $lang['im_submit'];?>"></label>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){
    //页面输入内容验证
    $("#add_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span');
            error_td.append(error);
        },
    	submitHandler:function(form){
            var unit_price = <?php echo $output['setting_config']['promotion_funding_price'];?>;
            var quantity = $("#funding_quota_quantity").val();
            var price = unit_price * quantity;
            showDialog('<?php echo $lang['funding_quota_add_confirm'];?>'+price+'<?php echo $lang['im_yuan'];?>', 'confirm', '', function(){
            	ajaxpost('add_form', '', '', 'onerror');
            	});
    	},
            rules : {
                funding_quota_quantity : {
                    required : true,
                    digits : true,
                    min : 1,
                    max : 12
                }
            },
                messages : {
                    funding_quota_quantity : {
                        required : "<i class='fa fa-exclamation-circle'></i><?php echo $lang['funding_quota_quantity_error'];?>",
                        digits : "<i class='fa fa-exclamation-circle'></i><?php echo $lang['funding_quota_quantity_error'];?>",
                        min : "<i class='fa fa-exclamation-circle'></i><?php echo $lang['funding_quota_quantity_error'];?>",
                        max : "<i class='fa fa-exclamation-circle'></i><?php echo $lang['funding_quota_quantity_error'];?>"
                    }
                }
    });
});
</script>
