<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>

<table class="imsc-table-style">
  <thead>
    <tr im_type="table_header">
      <th class="w600">选&nbsp;择&nbsp;项&nbsp;&nbsp;目</th>
      <th class="w10">&nbsp;</th>
      <th class="w250">选&nbsp;择&nbsp;分&nbsp;店</th>
      <th class="w150">操&nbsp;&nbsp;作</th>
    </tr>
  </thead>
  <tr>
    <td style="text-align:left;">
      <div class="imsc-form-default">
        <dl>
          <dt>店铺信息<?php echo $lang['im_colon'];?></dt>
          <dd>
            <label for="store_info" class="mr30">
              <input id="store_info_on" type="radio" class="radio vm mr5" name="store_info" value="1">选择
            </label>
            <label for="store_info_off">
              <input id="store_info_off" type="radio" class="radio vm mr5" name="store_info" value="0" checked>不选
            </label>
            <p class="hint">包括总店logo、头像、横幅、二维码等信息</p>
          </dd>
        </dl>
        <dl>
          <dt>商品分类<?php echo $lang['im_colon'];?></dt>
          <dd>
            <label for="store_class" class="mr30">
              <input id="store_class_on" type="radio" class="radio vm mr5" name="store_class" value="1">选择
            </label>
            <label for="store_class_off">
              <input id="store_class_off" type="radio" class="radio vm mr5" name="store_class" value="0" checked>不选
            </label>
            <p class="hint">包括总店绑定的分类和自定义分类，此操作将删除分店原有的分类；
              <br />该项有风险，操作需谨慎！
            </p>
          </dd>
        </dl>
        <dl>
          <dt>商品规格<?php echo $lang['im_colon'];?></dt>
          <dd>
            <label for="store_spec" class="mr30">
              <input id="store_spec_on" type="radio" class="radio vm mr5" name="store_spec" value="1">选择
            </label>
            <label for="store_spec_off">
              <input id="store_spec_off" type="radio" class="radio vm mr5" name="store_spec" value="0" checked>不选
            </label>
            <p class="hint">同步总店全部商品规格设定，此操作将删除分店原有的规格设定；
              <br />该项有风险，操作需谨慎！
            </p>
          </dd>
        </dl>
        <dl>
          <dt>推广配置<?php echo $lang['im_colon'];?></dt>
          <dd>
            <label for="store_extension" class="mr30">
              <input id="store_extension_on" type="radio" class="radio vm mr5" name="store_extension" value="1">选择
            </label>
            <label for="store_extension_off">
              <input id="store_extension_off" type="radio" class="radio vm mr5" name="store_extension" value="0" checked>不选
            </label>
            <p class="hint">包括总店推广的级别、佣金分成、佣金模板，此操作将删除分店原有的推广配置；
              <br />该项有风险，操作需谨慎！
            </p>
          </dd>
        </dl>
      </div>
    </td>
    <td class="goods_stubbs"></td>
    <td class="stubbs_store_td">
      <?php if (!empty($output['branch_list']) && is_array($output['branch_list'])) { $i=0;?>
      <ul class="stubbs_store_list">
      <?php foreach($output['branch_list'] as $v) { ++$i;?>
        <li>
          <div><input type="checkbox" class="storeitem tc" value="<?php echo $v['store_id'];?>" /><?php echo ' '.$v['store_name'];?></div>
        </li>
      <?php }?>
      </ul>
      <?php }?>
    </td>
    <td>
      <a href="javascript:void(0);" imtype="synchro_config" class="imsc-btn imsc-btn-green"><i class="fa fa-refresh"></i>开始同步</a>
    </td>
  </tr>
</table>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script> 
<script>
$(function(){
    $('a[imtype="synchro_config"]').click(function(){		
        if($('.storeitem:checked').length == 0){    //没有选择分店
		    showError('请选择接收分店！呵呵');
            return false;
        }
		
        var _items = '';
		if ($("input[name='store_info']:checked").val()==1){
			_items += 'store_info' + ',';
		}
		if ($("input[name='store_class']:checked").val()==1){
			_items += 'store_class' + ',';
		}
		if ($("input[name='store_spec']:checked").val()==1){
			_items += 'store_spec' + ',';
		}
		if ($("input[name='store_extension']:checked").val()==1){
			_items += 'store_extension' + ',';
		}		
		
        _items = _items.substr(0, (_items.length - 1));
		
		if (_items == ''){
			showError('请选择同步项目！呵呵');
            return false;
		}
		
		var _stores = '';
        $('.storeitem:checked').each(function(){
			_stores += $(this).val() + ',';
        });		
        _stores = _stores.substr(0, (_stores.length - 1));
		
		ajax_get_confirm('真的要同步配置信息吗?','<?php echo urlShop('seller_branch_synchro', 'config_save');?>&data=' + _items +'&stores=' + _stores);
    });
});
</script>