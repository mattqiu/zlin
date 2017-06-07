<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <form method="post" action="<?php echo urlShop('store_goods_online', 'edit_fxprice');?>" id="fxprice_form">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="commonid" value="<?php echo $_GET['commonid']; ?>" />
    <dl>
        <dt><i class="required">*</i>供应商回款<?php echo $lang['im_colon'];?></dt>
        <dd>
          <input name="g_take" value="<?php echo $output['goods']['goods_take']; ?>" disabled="disabled" type="text" class="text w60" /><em class="add-on">元</em> <span></span>
          <p class="hint">
          	此价格为售出该商品后给供应商回款多少钱
          	<!-- ，但此价格必须高于会员价的<?php echo (100-$output['goods_class']['commis_rate'])."%"; ?>。 -->
          </p>
        </dd>
	</dl>
      
      <dl edit_model="full_model" im_type="spec_dl" class="spec-bg">
        <dt><?php echo "价格配置".$lang['im_colon'];?></dt>
        <dd class="spec-dd">
          <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
            <thead>
              <?php if(is_array($output['spec_list']) && !empty($output['spec_list'])){?>
              <?php foreach ($output['spec_list'] as $k=>$val){?>
            	<th imtype="spec_name_<?php echo $k;?>" class="w50"><?php if (isset($output['goods']['spec_name'][$k])) { echo $output['goods']['spec_name'][$k];} else {echo $val['sp_name'];}?></th>
              <?php }?>
              <?php }?>
              <th class="w90"><span class="red">*</span>吊牌价
                <div class="batch"><i class="fa fa-pencil-square-o" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置价格：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text price" />
                    <a href="javascript:void(0)" class="imsc-btn-mini" data-type="marketprice">设置</a><span class="arrow"></span></div>
                </div>
              </th>
              <th class="w90"><span class="red">*</span><?php echo $lang['store_goods_index_price'];?>
                <div class="batch"><i class="fa fa-pencil-square-o" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置价格：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text price" />
                    <a href="javascript:void(0)" class="imsc-btn-mini" data-type="price">设置</a><span class="arrow"></span></div>
                </div>
              </th>
              <th class="w90"><span class="red">*</span>批发价
                <div class="batch"><i class="fa fa-pencil-square-o" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置价格：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text price" />
                    <a href="javascript:void(0)" class="imsc-btn-mini" data-type="tradeprice">设置</a><span class="arrow"></span></div>
                </div>
              </th>
              <th class="w60"><span class="red">*</span><?php echo $lang['store_goods_index_stock'];?>
                <div class="batch"><i class="fa fa-pencil-square-o" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置库存：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text stock" />
                    <a href="javascript:void(0)" class="imsc-btn-mini" data-type="stock">设置</a><span class="arrow"></span></div>
                </div>
                </th>
              
              <th class="w90"><?php echo $lang['store_goods_index_goods_no'];?>
              	<div class="batch"><i class="fa fa-pencil-square-o" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置商品货号：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text stock" />
                    <a href="javascript:void(0)" class="imsc-btn-mini" data-type="serial">设置</a><span class="arrow"></span></div>
                </div>
              </th>
              <th class="w90">商品条形码
              <div class="batch"><i class="fa fa-pencil-square-o" title="批量操作"></i>
                  <div class="batch-input" style="display:none;">
                    <h6>批量设置条形码：</h6>
                    <a href="javascript:void(0)" class="close">X</a>
                    <input name="" type="text" class="text stock" />
                    <a href="javascript:void(0)" class="imsc-btn-mini" data-type="barcode">设置</a><span class="arrow"></span></div>
                </div>
              </th>
            </thead>
            <tbody im_type="spec_table">
            <?php if(is_array($output['goods_array']) && !empty($output['goods_array'])){?>
              <?php foreach ($output['goods_array'] as $k=>$spec){?>
            	<tr>
            	<input type="hidden" name="spec[<?php echo $spec['goods_id']; ?>][goods_id]" value="<?php echo $spec['goods_id']; ?>">
            	<?php foreach ($spec['spec_arr'] as $k=>$svar){?>
            	<td>
            		<?php echo $svar; ?>
            	</td>
            	<?php }?>
            	<td><input class="text price" type="text" name="spec[<?php echo $spec['goods_id']; ?>][marketprice]" data_type="marketprice" im_type="<?php echo $spec['goods_id']; ?>|marketprice" value="<?php echo $spec['goods_marketprice']; ?>"><em class="add-on">元</em></td>
            	<td><input class="text price" type="text" name="spec[<?php echo $spec['goods_id']; ?>][price]" data_type="price" im_type="<?php echo $spec['goods_id']; ?>|price" value="<?php echo $spec['goods_price']; ?>"><em class="add-on">元</em></td>
            	<td><input class="text price" type="text" name="spec[<?php echo $spec['goods_id']; ?>][tradeprice]" data_type="tradeprice" im_type="<?php echo $spec['goods_id']; ?>|tradeprice" value="<?php echo $spec['goods_tradeprice']; ?>"><em class="add-on">元</em></td>
            	<td><input class="text stock" type="text" name="spec[<?php echo $spec['goods_id']; ?>][stock]" data_type="stock" im_type="<?php echo $spec['goods_id']; ?>|stock" value="<?php echo $spec['goods_storage']; ?>"></td>
            	<td><input class="text sku" type="text" name="spec[<?php echo $spec['goods_id']; ?>][serial]" data_type="serial" im_type="<?php echo $spec['goods_id']; ?>|serial" value="<?php echo $spec['goods_serial']; ?>"></td>
            	<td><input class="text sku" type="text" name="spec[<?php echo $spec['goods_id']; ?>][barcode]" data_type="barcode"  im_type="<?php echo $spec['goods_id']; ?>|barcode" value="<?php echo $spec['goods_barcode']; ?>"></td>
            	</tr>
            	<?php }?>
            <?php }?>
            </tbody>
          </table>
          <p class="hint">点击<i class="fa fa-pencil-square-o"></i>可批量修改所在列的值。</p>
        </dd>
      </dl>
      
    <div class="bottom">
      <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['im_submit'];?>"/></label>
    </div>
  </form>
</div>

<script>
$(function(){
	// 批量设置价格、库存、预警值
    $('.batch > .fa-pencil-square-o').click(function(){
        $('.batch > .batch-input').hide();
        $(this).next().show();
    });
    $('.batch-input > .close').click(function(){
        $(this).parent().hide();
    });
    $('.batch-input > .imsc-btn-mini').click(function(){
        var _value = $(this).prev().val();
        var _type = $(this).attr('data-type');
        if (_type == 'price' || _type == 'marketprice' || _type == 'tradeprice') {
            _value = number_format(_value, 2);
        } else if (_type == 'serial' || _type == 'barcode') {
            _value = _value;
        } else {
            _value = parseInt(_value);
        }
        if (_type == 'alarm' && _value > 255) {
            _value = 255;
        }
        if (isNaN(_value) && _value =='') {
            _value = 0;
        }
        $('input[data_type="' + _type + '" ]').val(_value);
        $(this).parent().hide();
        $(this).prev().val('');
    });
    $('#fxprice_form').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
        submitHandler:function(form){
            ajaxpost('jingle_form', '', '', 'onerror');
        },
        rules : {
            g_jingle : {
                maxlength: 50
            }
        },
        messages : {
            g_jingle : {
                maxlength: '<i class="fa fa-exclamation-circle"></i>不能超过50个字符'
            }
        }
    });
});
</script> 
<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
var DEFAULT_GOODS_IMAGE = "<?php echo thumb(array(), 60);?>";
var SHOP_RESOURCE_SITE_URL = "<?php echo SHOP_RESOURCE_SITE_URL;?>";

//编辑商品时处理JS
$(function(){
	//电脑端手机端tab切换
	$(".tabs").tabs();
    $.validator.addMethod('checkPrice', function(value,element){
    	_g_price = parseFloat($('input[name="g_price"]').val());
        _g_marketprice = parseFloat($('input[name="g_marketprice"]').val());
        if (_g_price > _g_marketprice) {
            return false;
        }else {
            return true;
        }
    }, '<i class="fa fa-exclamation-circle"></i>会员价不能高于吊牌价格');

    $.validator.addMethod('checkCollect', function(value,element){
    	_g_price = parseFloat($('input[name="g_price"]').val());
        _g_collect = parseFloat($('input[name="g_collect"]').val());
        _commis_rate = parseFloat($('input[name="commis_rate"]').val());
        _koud =  Math.ceil(_g_price*(1-(_commis_rate*0.01)));
        if (_koud < _g_collect) {
            return false;
        }else {
            return true;
        }
    }, '<i class="fa fa-exclamation-circle"></i>商家回款价不能高于(会员价-会员价*平台扣点)');
    $('#goods_form').validate({
        errorPlacement: function(error, element){
            $(element).nextAll('span').append(error);
        },
        <?php if ($output['edit_goods_sign']) {?>
        submitHandler:function(form){
            ajaxpost('goods_form', '', '', 'onerror');
        },
        <?php }?>
        rules : {
            g_name : {
                required    : true,
                minlength   : 3,
                maxlength   : 50
            },
            g_jingle : {
                maxlength   : 140
            },
            g_price : {
                required    : true,
                number      : true,
                min         : 0.01,
                max         : 9999999,
                checkPrice  : true
            },
            g_collect : {
                required    : true,
                number      : true,
                min         : 0.00,
                max         : 9999999,
                checkCollect  : true
            },
            g_marketprice : {
                required    : true,
                number      : true,
                min         : 0.01,
                max         : 9999999
            },
            g_costprice : {
                number      : true,
                min         : 0.00,
                max         : 9999999
            },
            g_storage  : {
                required    : true,
                digits      : true,
                min         : 0,
                max         : 999999999
			}
        },
        messages : {
            g_name  : {
                required    : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_goods_index_goods_name_null'];?>',
                minlength   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_goods_index_goods_name_help'];?>',
                maxlength   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_goods_index_goods_name_help'];?>'
            },
            g_jingle : {
                maxlength   : '<i class="fa fa-exclamation-circle"></i>商品卖点不能超过140个字符'
            },
            g_price : {
                required    : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_goods_index_store_price_null'];?>',
                number      : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_goods_index_store_price_error'];?>',
                min         : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_goods_index_store_price_interval'];?>',
                max         : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_goods_index_store_price_interval'];?>'
            },
            g_marketprice : {
                required    : '<i class="fa fa-exclamation-circle"></i>请填写吊牌价',
                number      : '<i class="fa fa-exclamation-circle"></i>请填写正确的价格',
                min         : '<i class="fa fa-exclamation-circle"></i>请填写0.01~9999999之间的数字',
                max         : '<i class="fa fa-exclamation-circle"></i>请填写0.01~9999999之间的数字'
            },
            g_costprice : {
                number      : '<i class="fa fa-exclamation-circle"></i>请填写正确的价格',
                min         : '<i class="fa fa-exclamation-circle"></i>请填写0.00~9999999之间的数字',
                max         : '<i class="fa fa-exclamation-circle"></i>请填写0.00~9999999之间的数字'
            },
            g_storage : {
                required    : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_goods_index_goods_stock_null'];?>',
                digits      : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_goods_index_goods_stock_error'];?>',
                min         : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_goods_index_goods_stock_checking'];?>',
                max         : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_goods_index_goods_stock_checking'];?>'
			}
        }
    });

});

</script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.js" language="javascript" ></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-zh-CN.js" language="javascript" ></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.css" rel="stylesheet" />
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/scrolld.js"></script>
<script type="text/javascript">$("[id*='Btn']").stop(true).on('click', function (e) {e.preventDefault();$(this).scrolld();})</script>

