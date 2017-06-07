<?php defined('InIMall') or exit('Access Invalid!');?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/template.min.js" charset="utf-8"></script>
<script type="text/javascript">
    $(document).ready(function(){

        // 当前编辑对象，默认为空
        $edit_item = {};

        //现实商品搜索
        $('#btn_show_goods_select').on('click', function() {
            $('#div_goods_select').show();
        });

        //隐藏商品搜索
        $('#btn_hide_goods_select').on('click', function() {
            $('#div_goods_select').hide();
        });

        //搜索商品
        $('#btn_search_goods').on('click', function() {
            var url = "<?php echo urlShop('store_promotion_funding', 'goods_select');?>";
            url += '&' + $.param({goods_name: $('#search_goods_name').val()});
            $('#div_goods_search_result').load(url);
        });
        $('#div_goods_search_result').on('click', 'a.demo', function() {
            $('#div_goods_search_result').load($(this).attr('href'));
            return false;
        });

        //添加限时折扣商品弹出窗口 
        $('#div_goods_search_result').on('click', '[imtype="btn_add_funding_goods"]', function() {
            $('#dialog_goods_id').val($(this).attr('data-goods-id'));
            $('#dialog_goods_name').text($(this).attr('data-goods-name'));
            $('#dialog_goods_price').text($(this).attr('data-goods-price'));
            $('#dialog_input_goods_price').val($(this).attr('data-goods-price'));
            $('#dialog_goods_img').attr('src', $(this).attr('data-goods-img'));
            $('#dialog_add_funding_goods').im_show_dialog({width: 450, title: '添加商品'});
            $('#dialog_funding_price').val('');
            $('#dialog_add_funding_goods_error').hide();
        });

        //添加限时折扣商品
        $('#div_goods_search_result').on('click', '#btn_submit', function() {
            var goods_id = $('#dialog_goods_id').val();
            var funding_id = <?php echo $_GET['funding_id'];?>;
            var goods_price = Number($('#dialog_input_goods_price').val());
            var funding_price = Number($('#dialog_funding_price').val());
            if(!isNaN(funding_price) && funding_price > 0 && funding_price < goods_price) {
                $.post('<?php echo urlShop('store_promotion_funding', 'funding_goods_add');?>', 
                    {goods_id: goods_id, funding_id: funding_id, funding_price: funding_price},
                    function(data) {
                        if(data.result) {
                            $('#dialog_add_funding_goods').hide();
                            $('#funding_goods_list').prepend(template.render('funding_goods_list_template', data.funding_goods)).hide().fadeIn('slow');
                            $('#funding_goods_list_norecord').hide();
                            showSucc(data.message);
                        } else {
                            showError(data.message);
                        }
                    }, 
                'json');
            } else {
                $('#dialog_add_funding_goods_error').show();
            }
        });

        //编辑限时活动商品
        $('#funding_goods_list').on('click', '[imtype="btn_edit_funding_goods"]', function() {
            $edit_item = $(this).parents('tr.bd-line');
            var funding_goods_id = $(this).attr('data-funding-goods-id');
            var funding_price = $edit_item.find('[imtype="funding_price"]').text();
            var goods_price = $(this).attr('data-goods-price');
            $('#dialog_funding_goods_id').val(funding_goods_id);
            $('#dialog_edit_goods_price').text(goods_price);
            $('#dialog_edit_funding_price').val(funding_price);
            $('#dialog_edit_funding_goods').im_show_dialog({width: 450, title: '修改价格'});
        });

        $('#btn_edit_funding_goods_submit').on('click', function() {
            var funding_goods_id = $('#dialog_funding_goods_id').val();
            var funding_price = Number($('#dialog_edit_funding_price').val());
            var goods_price = Number($('#dialog_edit_goods_price').text());
            if(!isNaN(funding_price) && funding_price > 0 && funding_price < goods_price) {
                $.post('<?php echo urlShop('store_promotion_funding', 'funding_goods_price_edit');?>',
                    {funding_goods_id: funding_goods_id, funding_price: funding_price},
                    function(data) {
                        if(data.result) {
                            $edit_item.find('[imtype="funding_price"]').text(data.funding_price);
                            $edit_item.find('[imtype="funding_discount"]').text(data.funding_discount);
                            $('#dialog_edit_funding_goods').hide();
                        } else {
                            showError(data.message);
                        }
                    }, 'json'
                ); 
            } else {
                $('#dialog_edit_funding_goods_error').show();
            }
        });

        //删除限时活动商品
        $('#funding_goods_list').on('click', '[imtype="btn_del_funding_goods"]', function() {
            var $this = $(this);
            if(confirm('确认删除？')) {
                var funding_goods_id = $(this).attr('data-funding-goods-id');
                $.post('<?php echo urlShop('store_promotion_funding', 'funding_goods_delete');?>',
                    {funding_goods_id: funding_goods_id},
                    function(data) {
                        if(data.result) {
                            $this.parents('tr').hide('slow', function() {
                                var funding_goods_count = $('#funding_goods_list').find('.bd-line:visible').length;
                                if(funding_goods_count <= 0) {
                                    $('#funding_goods_list_norecord').show();
                                }
                            });
                        } else {
                            showError(data.message);
                        }
                    }, 'json'
                );
            }
        });

        //渲染限时折扣商品列表
        funding_goods_array = $.parseJSON('<?php echo json_encode($output['funding_goods_list']);?>');
        if(funding_goods_array.length > 0) {
            var funding_goods_list = '';
            $.each(funding_goods_array, function(index, funding_goods) {
                funding_goods_list += template.render('funding_goods_list_template', funding_goods);
            });
            $('#funding_goods_list').prepend(funding_goods_list);
        } else {
            $('#funding_goods_list_norecord').show();
        }
    });
</script>
<div class="tabmenu">
    <?php include template('layout/submenu');?>
    <?php if($output['funding_info']['editable']) { ?>
    <a id="btn_show_goods_select" class="imsc-btn imsc-btn-green" href="javascript:;"><i></i><?php echo $lang['goods_add'];?></a> </div>
    <?php } ?>
<table class="imsc-default-table">
  <tbody>
    <tr>
      <td class="w90 tr"><strong><?php echo $lang['funding_name'].$lang['im_colon'];?></strong></td>
      <td class="w120 tl"><?php echo $output['funding_info']['funding_name'];?></td>
      <td class="w90 tr"><strong><?php echo $lang['start_time'].$lang['im_colon'];?></strong></td>
      <td class="w120 tl"><?php echo date('Y-m-d H:i',$output['funding_info']['start_time']);?></td>
      <td class="w90 tr"><strong><?php echo $lang['end_time'].$lang['im_colon'];?></strong></td>
      <td class="w120 tl"><?php echo date('Y-m-d H:i',$output['funding_info']['end_time']);?></td>
      <td class="w90 tr"><strong><?php echo '购买下限'.$lang['im_colon'];?></strong></td>
      <td class="w120 tl"><?php echo $output['funding_info']['lower_limit'];?></td>
      <td class="w90 tr"><strong><?php echo '状态'.$lang['im_colon'];?></strong></td>
      <td class="w120 tl"><?php echo $output['funding_info']['funding_state_text'];?></td>
    </tr>
</table>
<div class="alert">
  <strong><?php echo $lang['im_explain'];?><?php echo $lang['im_colon'];?></strong>
  <ul>
    <li><?php echo $lang['funding_manage_goods_explain1'];?></li>
    <li><?php echo $lang['funding_manage_goods_explain2'];?></li>
  </ul>
</div>
<!-- 商品搜索 -->
<div id="div_goods_select" class="div-goods-select" style="display: none;">
    <table class="search-form">
      <tr><th class="w150"><strong>第一步：搜索店内商品</strong></th><td class="w160"><input id="search_goods_name" type="text w150" class="text" name="goods_name" value=""/></td>
        <td class="w70 tc"><a href="javascript:void(0);" id="btn_search_goods" class="imsc-btn"/><i class="fa fa-search"></i><?php echo $lang['im_search'];?></a></td><td class="w10"></td><td><p class="hint">不输入名称直接搜索将显示店内所有普通商品，特殊商品不能参加。</p></td>
      </tr>
    </table>
  <div id="div_goods_search_result" class="search-result"></div>
  <a id="btn_hide_goods_select" class="close" href="javascript:void(0);">X</a> </div>
<table class="imsc-default-table">
  <thead>
    <tr>
      <th class="w10"></th>
      <th class="w50"></th>
      <th class="tl"><?php echo $lang['goods_name'];?></th>
      <th class="w90"><?php echo $lang['goods_store_price'];?></th>
      <th class="w120">折扣价格</th>
      <th class="w120">折扣率</th>
      <th class="w120"><?php echo $lang['im_handle'];?></th>
    </tr>
  </thead>
  <tbody id="funding_goods_list">
    <tr id="funding_goods_list_norecord" style="display:none">
      <td class="norecord" colspan="20"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
  </tbody>
</table>
<div class="bottom">
  <label class="submit-border"><input type="submit" class="submit" id="submit_back" value="<?php echo $lang['im_back'].$lang['funding_index'];?>" onclick="window.location='index.php?act=store_promotion_funding&op=funding_list'"></label>
</div>
<div id="dialog_edit_funding_goods" class="eject_con" style="display:none;">
    <input id="dialog_funding_goods_id" type="hidden">
    <dl><dt>商品价格：</dt><dd><span id="dialog_edit_goods_price"></dd>
    </dl>
    <dl><dt>折扣价格：</dt><dd><input id="dialog_edit_funding_price" type="text" class="text w70"><em class="add-on">元</em>
    <p id="dialog_edit_funding_goods_error" style="display:none;"><label for="dialog_edit_funding_goods_error" class="error"><i class='fa fa-exclamation-circle'></i>折扣价格不能为空，且必须小于商品价格</label></p>
    </dl>    
    <div class="eject_con">
        <div class="bottom pt10 pb10"><a id="btn_edit_funding_goods_submit" class="submit" href="javascript:void(0);">提交</a></div>
    </div>
</div>
<script id="funding_goods_list_template" type="text/html">
<tr class="bd-line">
    <td></td>
    <td><div class="pic-thumb"><a href="<%=goods_url%>" target="_blank"><img src="<%=image_url%>" alt=""></a></div></td>
    <td class="tl"><dl class="goods-name"><dt><a href="<%=goods_url%>" target="_blank"><%=goods_name%></a></dt></dl></td>
    <td><?php echo $lang['currency']; ?><%=goods_price%></td>
    <td><?php echo $lang['currency']; ?><span imtype="funding_price"><%=funding_price%></span></td>
    <td><span imtype="funding_discount"><%=funding_discount%></span></td>
    <td class="nscs-table-handle">
    <?php if($output['funding_info']['editable']) { ?>
    <span><a imtype="btn_edit_funding_goods" class="btn-blue" data-funding-goods-id="<%=funding_goods_id%>" data-goods-price="<%=goods_price%>" href="javascript:void(0);"><i class="fa fa-pencil-square-o"></i><p><?php echo $lang['im_edit'];?></p></a></span>
        <span><a imtype="btn_del_funding_goods" class="btn-red" data-funding-goods-id="<%=funding_goods_id%>" href="javascript:void(0);"><i class="fa fa-trash-o"></i><p><?php echo $lang['im_del'];?></p></a></span>
    <?php } ?>
    </td>
</tr>
</script> 