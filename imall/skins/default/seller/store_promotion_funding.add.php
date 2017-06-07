<?php defined('InIMall') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>

<div class="imsc-form-default">
  <form id="add_form" action="index.php?act=store_promotion_funding&op=funding_save" method="post" enctype="multipart/form-data">
    <dl>
      <dt><i class="required">*</i><?php echo $lang['funding_name'].$lang['im_colon'];?></dt>
      <dd>
        <input class="w400 text" name="funding_name" type="text" id="funding_name" value="" maxlength="30"  />
        <span></span>
        <p class="hint"><?php echo $lang['funding_name_tip'];?></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?php echo $lang['start_time'];?><?php echo $lang['im_colon'];?></dt>
      <dd>
          <input id="start_time" name="start_time" type="text" class="text w130" /><em class="add-on"><i class="fa fa-calendar"></i></em><span></span>
          <p class="hint"><?php echo '众筹开始时间不能小于'.date('Y-m-d H:i', $output['start_time']);?></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i><?php echo $lang['end_time'];?><?php echo $lang['im_colon'];?></dt>
      <dd>
          <input id="end_time" name="end_time" type="text" class="text w130"/><em class="add-on"><i class="fa fa-calendar"></i></em><span></span>
		<?php if (!$output['isOwnShop']) { ?>
          <p class="hint">
          <?php echo '众筹开始时间不能大于'.date('Y-m-d H:i', $output['current_funding_quota']['end_time']);?>
          </p>
		<?php } ?>

      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>众筹商品：</dt>
      <dd>
      <div imtype="funding_goods_info" class="selected-group-goods " style="display:none;">
      <div class="goods-thumb"><img id="funding_goods_image" src=""/></div>
          <div class="goods-name">
          <a imtype="funding_goods_href" id="funding_goods_name" href="" target="_blank"></a>
          </div>
          <div class="goods-price">会员价：￥<span imtype="funding_goods_price"></span></div>
      </div>
      <a href="javascript:void(0);" id="btn_show_search_goods" class="imsc-btn imsc-btn-acidblue">选择商品</a>
      <input id="funding_goods_id" name="funding_goods_id" type="hidden" value=""/>
      <span></span>
      <div id="div_search_goods" class="div-goods-select mt10" style="display: none;">
          <table class="search-form">
              <tr>
                  <th class="w150">
                      <strong>第一步：搜索店内商品</strong>
                  </th>
                  <td class="w160">
                      <input id="search_goods_name" type="text w150" class="text" name="goods_name" value=""/>
                  </td>
                  <td class="w70 tc">
                      <a href="javascript:void(0);" id="btn_search_goods" class="imsc-btn"/><i class="fa fa-search"></i><?php echo $lang['im_search'];?></a></td>
                    <td class="w10"></td>
                    <td>
                        <p class="hint">不输入名称直接搜索将显示店内所有普通商品，特殊商品不能参加。</p>
                    </td>
                </tr>
            </table>
            <div id="div_goods_search_result" class="search-result" style="width:739px;"></div>
            <a id="btn_hide_search_goods" class="close" href="javascript:void(0);">X</a>
        </div>
        <p class="hint"><?php echo $lang['funding_goods_explain'];?></br><span class="red">众筹生效后该商品的所有规格SKU都将执行统一的众筹价格</span></p>
        </dd>
    </dl>
    
    <dl imtype="funding_goods_info" style="display:none;">
      <dt>店铺价格：</dt>
      <dd> <?php echo $lang['currency'];?><span imtype="funding_goods_price"></span><input id="input_funding_goods_price" type="hidden"></dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>特卖价格：</dt>
      <dd>
        <input class="w70 text" id="funding_price" name="funding_price" type="text" value=""/><em class="add-on">元</em> <span></span>
        <p class="hint"><?php echo $lang['funding_price_tip'];?></p>
      </dd>
    </dl>
    
    <dl>
      <dt><i class="required">*</i>众筹活动图片<?php echo $lang['im_colon'];?></dt>
      <dd>
      <div class="imsc-upload-thumb goods-pic">
          <p><i class="fa fa-picture-o"></i>
          <img imtype="img_goods_image" style="display:none;" src=""/></p>
      </div>
        <input imtype="goods_image" name="goods_image" type="hidden" value="">
        <div class="imsc-upload-btn">
            <a href="javascript:void(0);">
                <span>
                    <input type="file" hidefocus="true" size="1" class="input-file" name="goods_image" imtype="btn_upload_image"/>
                </span>
                <p><i class="fa fa-upload"></i>图片上传</p>
            </a>
        </div>
        <span></span>
        <p class="hint">用于众筹活动页面的图片,请使用宽度440像素、高度293像素、大小1M内的图片，
		支持jpg、jpeg、gif、png格式上传。</p>
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
    
    <dl style="display: none;">
      <dt>第二阶段 - 尾款金额：</dt>
      <dd>
        <input name="final_payment" type="text" readonly class="text w70">
        <em class="add-on">元</em>
        <p class="hint">系统将根据预定总价和定金自动计算第二阶段应支付的尾款金额。</p>
      </dd>
    </dl>
    
    <dl>
      <dt>活动规则：</dt>
      <dd>
      <div id="cou-level-container">
		<?php foreach ((array) $output['data']['levels'] as $k => $v) { ?>
        <div data-cou-level-item="<?php echo (string) $k; ?>" class="imsc-cou-rule">
          <div class="rule-note">
            <h5>规则<?php echo $k; ?>：<a href="javascript:;" class="imbtn-mini imbtn-grapefruit" data-cou-level-remove="<?php echo (string) $k; ?>"><i class="icon-trash"></i>删除</a></h5>
            <span>
            	购买同一众筹活动商品满
            <input type="text" class="w30" name="sales_rule[<?php echo (string) $k; ?>][maxcou]" value="<?php echo $v['maxcou']; ?>" />
            	件（0为不限），该商品价将为
            <input type="text" class="w50" name="sales_rule[<?php echo (string) $k; ?>][mincost]" value="<?php echo $v['mincost']; ?>" />
            	元。</span>
          </div>
          
        </div>
		<?php } ?>
      </div>

        <a href="javascript:;" id="cou-level-add-button" class="imbtn imbtn-aqua mt10"> <i class="icon-plus-sign"></i> 添加规则 </a>
        <p class="hint">级别会依据购满金额由小到大自动排序；</p>
        <p class="hint">众筹购买的数量为满足众筹条件时，会员即可用同级别的价格购买</p>
      </dd>
    </dl>
    <dl>
      <dt>虚拟数量：</dt>
      <dd>
        <input class="w70 text" id="virtual_quantity" name="virtual_quantity" type="text" value="0"/>
        <span></span>
        <p class="hint"><?php echo $lang['virtual_quantity_explain'];?></p>
      </dd>
    </dl>
    <dl>
      <dt>限购数量：</dt>
      <dd>
        <input class="w70 text" id="lower_limit" name="lower_limit" type="text" value="0"/>
        <span></span>
        <p class="hint"><?php echo $lang['sale_quantity_explain'];?></p>
      </dd>
    </dl>
    
    <div class="bottom"><label class="submit-border">
      <input type="submit" class="submit" value="<?php echo $lang['im_submit'];?>"></label>
    </div>
  </form>
</div>


<div id="cou-level-newly" style="display:none;">
        <div data-cou-level-item="__level" class="imsc-cou-rule">
          <div class="rule-note">
            <h5>新增规则：<a href="javascript:;" class="imbtn-mini imbtn-grapefruit" data-cou-level-remove="__level"><i class="icon-trash"></i>删除</a></h5>
            <span>购买同一众筹活动商品满
            <input type="text" class="w30" name="sales_rule[__level][maxcou]" value="0" />
            	件（0为不限），该商品价将为
            <input type="text" class="w50" name="sales_rule[__level][mincost]" value="" />
            	元。
            </span>
        </div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.css"  />
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script type="text/javascript">
$(document).ready(function(){
	
	var nextId = (function() {
        var i = 100;
        return function() {
            return ++i;
        };
    })();
    
	// ajax添加规则
    $('#cou-level-add-button').click(function() {
        var id = nextId();
        var h = $('#cou-level-newly').html();
        h = h.replace(/__level/g, id);
        $('#cou-level-container').append(h);
    });

    // 规则移除按钮
    $('[data-cou-level-remove]').live('click', function() {
        var id = $(this).attr('data-cou-level-remove');
        $("[data-cou-level-item='"+id+"']").remove();
    });
	
    $('#start_time').datetimepicker({
        controlType: 'select'
    });

    $('#end_time').datetimepicker({
        controlType: 'select'
    });

    $('#btn_show_search_goods').on('click', function() {
        $('#div_search_goods').show();
    });

    $('#btn_hide_search_goods').on('click', function() {
        $('#div_search_goods').hide();
    });

    //搜索商品
    $('#btn_search_goods').on('click', function() {
        var url = "<?php echo urlShop('store_promotion_funding', 'search_goods');?>";
        url += '&' + $.param({goods_name: $('#search_goods_name').val()});
        $('#div_goods_search_result').load(url);
    });

    $('#div_goods_search_result').on('click', 'a.demo', function() {
        $('#div_goods_search_result').load($(this).attr('href'));
        return false;
    });

    //选择商品
    $('#div_goods_search_result').on('click', '[imtype="btn_add_funding_goods"]', function() {
        var goods_commonid = $(this).attr('data-goods-commonid');
        $.get('<?php echo urlShop('store_promotion_funding', 'funding_goods_info');?>', {goods_commonid: goods_commonid}, function(data) {
            if(data.result) {
                $('#funding_goods_id').val(data.goods_id);
                $('#funding_goods_image').attr('src', data.goods_image);
                $('#funding_goods_name').text(data.goods_name);
                $('[imtype="funding_goods_price"]').text(data.goods_price);
                $('#input_funding_goods_price').val(data.goods_price);
                $('[imtype="funding_goods_href"]').attr('href', data.goods_href);
                $('[imtype="funding_goods_info"]').show();
                $('#div_search_goods').hide();
            } else {
                showError(data.message);
            }
        }, 'json');
    });

    //图片上传
    $('[imtype="btn_upload_image"]').fileupload({
        dataType: 'json',
            url: "<?php echo urlShop('store_promotion_funding', 'image_upload');?>",
            add: function(e, data) {
                $parent = $(this).parents('dd');
                $input = $parent.find('[imtype="goods_image"]');
                $img = $parent.find('[imtype="img_goods_image"]');
                data.formData = {old_goods_image:$input.val()};
                $img.attr('src', "<?php echo SHOP_SKINS_URL.'/images/loading.gif';?>");
                data.submit();
            },
            done: function (e,data) {
                var result = data.result;
                $parent = $(this).parents('dd');
                $input = $parent.find('[imtype="goods_image"]');
                $img = $parent.find('[imtype="img_goods_image"]');
                if(result.result) {
                    $img.prev('i').hide();
                    $img.attr('src', result.file_url);
                    $img.show();
                    $input.val(result.file_name);
                } else {
                    showError(data.message);
                }
            }
    });

    jQuery.validator.methods.greaterThanDate = function(value, element, param) {
        var date1 = new Date(Date.parse(param.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 < date2;
    };

    jQuery.validator.methods.lessThanDate = function(value, element, param) {
        var date1 = new Date(Date.parse(param.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 > date2;
    };

    jQuery.validator.methods.greaterThanStartDate = function(value, element) {
        var start_date = $("#start_time").val();
        var date1 = new Date(Date.parse(start_date.replace(/-/g, "/")));
        var date2 = new Date(Date.parse(value.replace(/-/g, "/")));
        return date1 < date2;
    };

    jQuery.validator.methods.lessThanGoodsPrice= function(value, element) {
        var goods_price = $("#input_funding_goods_price").val();
        return Number(value) < Number(goods_price);
    };

    jQuery.validator.methods.checkGroupbuyGoods = function(value, element) {
        var start_time = $("#start_time").val();
        var result = true;
        $.ajax({
            type:"GET",
            url:'<?php echo urlShop('store_groupbuy', 'check_funding_goods');?>',
            async:false,
            data:{start_time: start_time, goods_id: value},
            dataType: 'json',
            success: function(data){
                if(!data.result) {
                    result = false;
                }
            }
        });
        return result;
    };

    //页面输入内容验证
    $("#add_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span');
            error_td.append(error);
        },
        onfocusout: false,
    	submitHandler:function(form){
    		ajaxpost('add_form', '', '', 'onerror');
    	},
        rules : {
            funding_name: {
                required : true
            },
            start_time : {
                required : true,
                greaterThanDate : '<?php echo date('Y-m-d H:i',$output['start_time']);?>'
            },
            end_time : {
                required : true,
				<?php if (!$output['isOwnShop']) { ?>
                lessThanDate : '<?php echo date('Y-m-d H:i',$output['current_groupbuy_quota']['end_time']);?>',
				<?php } ?>
                greaterThanStartDate : true
            },
            funding_goods_id: {
                required : true,
                checkGroupbuyGoods: true
            },
            groupbuy_price: {
                required : true,
                number : true,
                lessThanGoodsPrice: true,
                min : 0.01,
                max : 1000000
            },
            virtual_quantity: {
                required : true,
                digits : true
            },
            lower_limit: {
                required : true,
                digits : true
            },
            goods_image: {
                required : true
            }
        },
        messages : {
            funding_name: {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['funding_name_error'];?>'
            },
            start_time : {
                required : '<i class="fa fa-exclamation-circle"></i>众筹开始时间不能为空',
                greaterThanDate : '<i class="fa fa-exclamation-circle"></i><?php echo sprintf('众筹开始时间必须大于{0}',date('Y-m-d H:i',$output['current_groupbuy_quota']['start_time']));?>'
            },
            end_time : {
                required : '<i class="fa fa-exclamation-circle"></i>众筹结束时间不能为空',
				<?php if (!$output['isOwnShop']) { ?>
                lessThanDate : '<i class="fa fa-exclamation-circle"></i><?php echo sprintf('众筹结束时间必须小于{0}',date('Y-m-d H:i',$output['current_groupbuy_quota']['end_time']));?>',
				<?php } ?>
                greaterThanStartDate : '<i class="fa fa-exclamation-circle"></i>结束时间必须大于开始时间'
            },
            funding_goods_id: {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['group_goods_error'];?>',
                checkGroupbuyGoods: '该商品已经参加了同时段的活动'
            },
            groupbuy_price: {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['groupbuy_price_error'];?>',
                number : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['groupbuy_price_error'];?>',
                lessThanGoodsPrice: '<i class="fa fa-exclamation-circle"></i>众筹价格必须小于商品价格',
                min : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['groupbuy_price_error'];?>',
                max : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['groupbuy_price_error'];?>'
            },
            virtual_quantity: {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['virtual_quantity_error'];?>',
                digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['virtual_quantity_error'];?>'
            },
            lower_limit: {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['sale_quantity_error'];?>',
                digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['sale_quantity_error'];?>'
            },
            goods_image: {
                required : '<i class="fa fa-exclamation-circle"></i>众筹图片不能为空'
            }
        }
    });

	$('#li_1').click(function(){
		$('#li_1').attr('class','active');
		$('#li_2').attr('class','');
		$('#demo').hide();
	});

	$('#goods_demo').click(function(){
		$('#li_1').attr('class','');
		$('#li_2').attr('class','active');
		$('#demo').show();
	});

	$('.des_demo').click(function(){
		if($('#des_demo').css('display') == 'none'){
            $('#des_demo').show();
        }else{
            $('#des_demo').hide();
        }
	});

    $('.des_demo').ajaxContent({
        event:'click', //mouseover
            loaderType:"img",
            loadingMsg:"<?php echo SHOP_SKINS_URL;?>/images/loading.gif",
            target:'#des_demo'
    });
});

function insert_editor(file_path){
	KE.appendHtml('goods_body', '<img src="'+ file_path + '">');
}

(function(data) {
    var s = '<option value="0"><?php echo $lang['text_no_limit']; ?></option>';
    if (typeof data.children != 'undefined') {
        if (data.children[0]) {
            $.each(data.children[0], function(k, v) {
                s += '<option value="'+v+'">'+data['name'][v]+'</option>';
            });
        }
    }
    $('#class_id').html(s).change(function() {
        var ss = '<option value="0"><?php echo $lang['text_no_limit']; ?></option>';
        var v = this.value;
        if (parseInt(v) && data.children[v]) {
            $.each(data.children[v], function(kk, vv) {
                ss += '<option value="'+vv+'">'+data['name'][vv]+'</option>';
            });
        }
        $('#s_class_id').html(ss);
    });
})($.parseJSON('<?php echo json_encode($output['groupbuy_classes']); ?>'));
</script>