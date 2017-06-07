<?php defined('InIMall') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<style>
.pic_list .small_pic ul li {
	height: 100px;
}
.ui-sortable-helper {
	border: dashed 1px #F93;
	box-shadow: 2px 2px 2px rgba(153,153,153, 0.25);
	filter: alpha(opacity=75);
	-moz-opacity: 0.75;
	opacity: .75;
	cursor: ns-resize;
}
.ui-sortable-helper td {
	background-color: #FFC !important;
}
.ajaxload {
	display: block;
	width: 16px;
	height: 16px;
	margin: 100px 300px;
}
</style>
<input id="level2_flag" type="hidden" value="false" />
<input id="level3_flag" type="hidden" value="false" />
<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="alert"> <strong><?php echo $lang['im_explain'].$lang['im_colon'];?></strong>
    <ul>
      <?php if(intval(C('promotion_bundling_sum')) != 0){?>
      <li><?php printf($lang['bundling_add_explain1'], C('promotion_bundling_sum'), C('promotion_bundling_goods_sum'));?></li>
      <?php }else{?>
      <li><?php printf($lang['bundling_add_explain2'], C('promotion_bundling_goods_sum'));?></li>
      <?php }?>
      <li>凡选择指定优惠的商品，在这个商品的详细页将出现发布的优惠套装。</li>
      <li>特殊商品不能参加该活动。</li>
    </ul>
  </div>
  <div class="imsc-form-default"> 
    <!-- 说明 -->
    
    <form id="add_form" method="post" action="index.php?act=store_promotion_bundling&op=bundling_add">
      <input type="hidden" name="form_submit" value="ok" />
      <?php if (!empty($output['bundling_info'])){?>
      <input type="hidden" name="bundling_id" value="<?php echo $output['bundling_info']['bl_id'];?>" />
      <?php }?>
      <dl>
        <dt><i class="required">*</i><?php echo $lang['bundling_name'].$lang['im_colon'];?></dt>
        <dd>
          <p>
            <input id="bundling_name" name="bundling_name" type="text" maxlength="25" class="w400 text" value="<?php echo $output['bundling_info']['bl_name'];?>" />
            <span></span> </p>
          <p class="hint"><?php echo $lang['bundling_name_explain'];?></p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i><?php echo $lang['bundling_add_price'].$lang['im_colon'];?></dt>
        <dd>
          <input id="discount_price" name="discount_price" type="text" readonly style="background:#E7E7E7 none;" class="text w60 mr5" value="<?php echo $output['bundling_info']['bl_discount_price'];?>"/>
          <?php echo $lang['currency_zh'];?> <span></span>
          <p class="hint mt10"><?php echo $lang['bundling_cost_price'];?><span imtype="cost_price" class="price mr5 ml5"><?php echo $output['bundling_info']['bl_cost_price'];?></span><?php echo $lang['currency_zh'].$lang['bundling_cost_price_note'];?></p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i><?php echo $lang['bundling_goods'].$lang['im_colon'];?></dt>
        <dd>
          <p>
            <input id="bundling_goods" type="hidden" value="" name="bundling_goods">
            <span></span></p>
          <table class="imsc-default-table mb15">
            <thead>
              <tr>
                <th class="w70">指定优惠</th>
                <th class="tl" colspan="2">商品名称</th>
                <th class="w90"><?php echo $lang['bundling_cost_price'];?></th>
                <th class="w90">优惠价格</th>
                <th class="w90"><?php echo $lang['im_common_button_operate'];?></th>
              </tr>
            </thead>
            <tbody imtype="bundling_data"  class="bd-line tip" title="<?php echo $lang['bundling_add_goods_explain'];?>">
              <tr style="display:none;">
                <td colspan="20" class="norecord"><div class="no-promotion"><i class="zh"></i><span>优惠套装还未选择添加商品。</span></div></td>
              </tr>
              <?php if(!empty($output['b_goods_list'])){?>
              <?php foreach($output['b_goods_list'] as $val){?>
              <?php if (isset($output['goods_list'][$val['goods_id']])) {?>
              <tr id="bundling_tr_<?php echo $val['goods_id']?>" class="off-shelf">
              	<input type="hidden" value="<?php echo $output['goods_list'][$val['goods_id']]['goods_gain'];?>" imtype="goods_gain" name="goods[<?php echo $val['goods_id'];?>][goods_gain]" />
                <input type="hidden" value="<?php echo $val['bl_goods_id'];?>" name="goods[<?php echo $val['goods_id'];?>][bundling_goods_id]" />
                <input type="hidden" value="<?php echo $val['goods_id'];?>" name="goods[<?php echo $val['goods_id'];?>][gid]" imtype="goods_id">
                <td class="w70"><input type="checkbox" name="goods[<?php echo $val['goods_id'];?>][appoint]" value="1" <?php if ($val['bl_appoint'] == 1) {?>checked="checked"<?php }?>></td>
                <td class="w50"><div class="shelf-state"><div class="pic-thumb"><img src="<?php echo cthumb($output['goods_list'][$val['goods_id']]['goods_image'], 60, $_SESSION['store_id']);?>" ncname="<?php echo $output['goods_list'][$val['goods_id']]['goods_image'];?>" imtype="bundling_data_img">
                    </div></div>
                </td>
                <td class="tl"><dl class="goods-name">
                    <dt style="width: 300px;"><?php echo $output['goods_list'][$val['goods_id']]['goods_name'];?></dt>
                  </dl></td>
                <td class="goods-price w90" imtype="bundling_data_price"><?php echo $output['goods_list'][$val['goods_id']]['goods_price'];?></td>
                <td class="w90"><?php echo $val['goods_store_price'];?>
                  <input imtype="price" type="text" value="<?php echo $val['bl_goods_price'];?>" name="goods[<?php echo $val['goods_id'];?>][price]" class="text w70"></td>
                <td class="nscs-table-handle w90"><span><a onclick="bundling_operate_delete($('#bundling_tr_<?php echo $val['goods_id']?>'), <?php echo $val['goods_id']?>)" href="JavaScript:void(0);" class="btn-orange"><i class="fa fa-ban"></i>
                  <p><?php echo $lang['bundling_goods_remove'];?></p>
                  </a></span></td>
              </tr>
              <?php }?>
              <?php }?>
              <?php }?>
            </tbody>
          </table>
          <a id="bundling_add_goods" href="index.php?act=store_promotion_bundling&op=bundling_add_goods" class="imsc-btn imsc-btn-acidblue"><?php echo $lang['bundling_goods_add'];?></a>
          <div class="div-goods-select-box">
            <div id="bundling_add_goods_ajaxContent"></div>
            <a id="bundling_add_goods_delete" class="close" href="javascript:void(0);" style="display: none; right: -10px;">X</a></div>
        </dd>
      </dl>
      <dl>
        <dt><?php echo $lang['bundling_add_freight_method'].$lang['im_colon'];?></dt>
        <dd>
        <ul class="imsc-form-radio-list">
          <li><label for="whops_seller"><input id="whops_seller" type="radio" name="bundling_freight_choose" <?php if(!isset($output['bundling_info']) || $output['bundling_info']['bl_freight_choose'] == '1'){ ?>checked="checked"<?php }?> value="1" /><?php echo $lang['bundling_add_freight_method_seller'];?></label></li>
          <li><label for="whops_buyer"><input id="whops_buyer" type="radio" name="bundling_freight_choose" <?php if(isset($output['bundling_info']) && $output['bundling_info']['bl_freight_choose'] == '0'){ ?>checked="checked"<?php }?> value="0" /><?php echo $lang['bundling_add_freight_method_buyer'];?></label>
          <div id="whops_buyer_box" class="transport_tpl" style="<?php if(!isset($output['bundling_info']) || $output['bundling_info']['bl_freight_choose'] == '1'){ ?>display:none;<?php }?>"><input class="w50 text" type="text" name="bundling_freight" value="<?php echo $output['bundling_info']['bl_freight'];?>" /><em class="add-on">元</em>
          </div>
          </li>
          </ul>
        </dd>
      </dl>
      
      <dl>
        <dt>推广员套餐<?php echo $lang['im_colon'];?></dt>
        <dd>
        <ul class="imsc-form-radio-list">
          <li><label for="extension_seller"><input id="extension_seller" type="radio" name="bl_is_extension" <?php if(!isset($output['bundling_info']) || $output['bundling_info']['bl_is_extension'] == '0'){ ?>checked="checked"<?php }?> value="0" />常用优惠套餐</label></li>
          <li><label for="extension_buyer"><input id="extension_buyer" type="radio" name="bl_is_extension" <?php if(isset($output['bundling_info']) && $output['bundling_info']['bl_is_extension'] == '1'){ ?>checked="checked"<?php }?> value="1" />购买套餐后可升级为推广员</label>
          <div id="whops_extension_day" class="transport_tpl" style="<?php if(!isset($output['bundling_info']) || $output['bundling_info']['bl_is_extension'] == '0'){ ?>display:none;<?php }?>">推广员有效期为<input class="w50 text" type="text" name="extension_day" value="<?php echo $output['bundling_info']['extension_day'];?>" /><em class="add-on">天</em>
          </div>
          </li>
          </ul>
        </dd>
      </dl>
      
      <dl id="whops_extension_gain"  style="<?php if(!isset($output['bundling_info']) || $output['bundling_info']['bl_is_extension'] == '0'){ ?>display:none;<?php }?>">
        <dt>分销利润<?php echo $lang['im_colon'];?></dt>
        <dd>
          <p class="hint mt10"><span imtype="gain_price" class="price mr5 ml5"><?php echo $output['bundling_info']['bl_cost_price'];?></span><?php echo $lang['currency_zh'].$lang['bundling_cost_price_note'];?></p>
        </dd>
      </dl>
      <dl id="whops_extension_allot">
        <dt>利润分配<?php echo $lang['im_colon'];?></dt>
        <dd>
          <ul class="imsc-form-radio-list">
          <li><input type="checkbox" name="bl_is_gain" value="1" <?php if ($val['bl_is_gain'] == 1) {?>checked="checked"<?php }?> style="vertical-align: middle; margin-right: 5px;">管理者</li>
          <li><input type="checkbox" name="bl_is_gain" value="2" <?php if ($val['bl_is_gain'] == 2) {?>checked="checked"<?php }?> style="vertical-align: middle; margin-right: 5px;">门店补贴</li>
          <li><input type="checkbox" name="bl_is_gain" value="3" <?php if ($val['bl_is_gain'] == 3) {?>checked="checked"<?php }?> style="vertical-align: middle; margin-right: 5px;">三级分销</li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt><?php echo $lang['bundling_status'].$lang['im_colon'];?></dt>
        <dd>
          <ul class="imsc-form-radio-list">
            <li><label for="bundling_status_1">
              <input type="radio" name="state" value="1" id="bundling_status_1" <?php if(!isset($output['bundling_info']) || $output['bundling_info']['bl_state'] == 1) echo 'checked="checked"'; ?> />
              <?php echo $lang['bundling_status_1'];?></label></li>
            <li><label for="bundling_status_0">
              <input type="radio" name="state" value="0" id="bundling_status_0" <?php if(isset($output['bundling_info']) && $output['bundling_info']['bl_state'] == 0) echo 'checked="checked"'; ?> />
              <?php echo $lang['bundling_status_0'];?></label></li>
          </ul>
        </dd>
      </dl>
      <div class="bottom">
          <label class="submit-border"><input id="submit_button" type="submit" value="<?php echo $lang['im_submit'];?>"  class="submit"></label>        
      </div>
    </form>
  </div>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script> 
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/store_bundling.js"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script> 
<script type="text/javascript">
var DEFAULT_GOODS_IMAGE = '<?php echo defaultGoodsImage(60);?>';
$(function(){
    jQuery.validator.addMethod('bundling_goods', function(value, element){
    	return $('tbody[imtype="bundling_data"] > tr').length >2?true:false;
    });
	//Ajax提示
    $('.tip').poshytip({
    	className: 'tip-yellowsimple',
    	showTimeout: 1,
    	alignTo: 'target',
    	alignX: 'left',
    	alignY: 'top',
    	offsetX: 5,
    	offsetY: -78,
    	allowTipHover: false
    });
    $('.tip2').poshytip({
    	className: 'tip-yellowsimple',
    	showTimeout: 1,
    	alignTo: 'target',
    	alignX: 'right',
    	alignY: 'center',
    	offsetX: 5,
    	offsetY: 0,
    	allowTipHover: false
    });
    //页面输入内容验证
    $("#add_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.nextAll('span:first');
            error_td.append(error);
        },
     	submitHandler:function(form){
    		ajaxpost('add_form', '', '', 'onerror')
    	},
        rules : {
            bundling_name : {
                required : true
            },
            bundling_goods : {
				bundling_goods : true
	        },
            discount_price : {
				required : true,
				number : true
            }
        },
        messages : {
            bundling_name : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_add_name_error'];?>'
            },
            bundling_goods : {
            	bundling_goods : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_add_goods_error'];?>'
            },
            discount_price : {
				required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_add_price_error_null'];?>',
				number : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['bundling_add_price_error_not_num'];?>'
            }
        
        }
    });
	$('input[name="bundling_freight_choose"]').click(function(){
		if($(this).val() == '0'){
			$('#whops_buyer_box').show();
		}else{
			$('#whops_buyer_box').hide();
		}
	});
	
	$('input[name="bl_is_extension"]').click(function(){
		if($(this).val() == '1'){
			$('#whops_extension_day').show();
			$('#whops_extension_gain').show();
			$('#whops_extension_allot').show();
			
		}else{
			$('#whops_extension_day').hide();
			$('#whops_extension_gain').hide();
			$('#whops_extension_allot').hide();
		}
	});

    check_bundling_data_length();
    <?php if(!empty($output['bundling_info'])){?>
    count_cost_price_sum(); // 计算商品原价
    count_goods_gain_sum();
    count_price_sum();
    <?php }?>

    $('tbody[imtype="bundling_data"]').on('change', 'input[imtype="price"]', function(){
        count_price_sum();
    });
});


/* 删除商品 */
function bundling_operate_delete(o, id){
	o.remove();
	check_bundling_data_length();
	$('li[imtype="'+id+'"]').children(':last').html('<a href="JavaScript:void(0);" onclick="bundling_goods_add($(this))" class="imsc-btn-mini imsc-btn-green"><i class="fa fa-plus"></i><?php echo $lang['bundling_goods_add_bundling'];?></a>');
	count_cost_price_sum();
	count_goods_gain_sum();
}

function check_bundling_data_length(){
	if ($('tbody[imtype="bundling_data"] tr').length == 1) {
	    $('tbody[imtype="bundling_data"]').children(':first').show();
	}
}
</script>