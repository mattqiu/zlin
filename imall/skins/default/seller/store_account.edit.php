<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="imsc-form-default">
  <form id="add_form" action="<?php echo urlShop('store_account', 'account_edit_save');?>" method="post">
    <input name="seller_id" value="<?php echo $output['seller_info']['seller_id'];?>" type="hidden" />
    <input name="member_id" value="<?php echo $output['seller_info']['member_id'];?>" type="hidden" />
    <dl>
      <dt><i class="required">*</i>卖家账号名<?php echo $lang['im_colon'];?></dt>
      <dd> <?php echo $output['seller_info']['seller_name'];?> <span></span>
        <p class="hint"></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>导购昵称<?php echo $lang['im_colon'];?></dt>
      <dd><input class="w120 text" name="nick_name" type="text" id="nick_name" value="<?php echo $output['seller_info']['nick_name'];?>" /><span></span>
        <p class="hint"></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>员工身份<?php echo $lang['im_colon'];?></dt>
      <dd>
      	<ul class="imsc-form-radio-list">
            <li>
              <input type="radio" name="is_owner" value="0" id="is_owner_0" <?php if(empty($output['seller_info'])||$output['seller_info']['is_owner'] == 0) {?>checked<?php }?>>
              <label for="is_owner_0">员工</label>
            </li>
            <li>
              <input type="radio" name="is_owner" value="1" id="is_owner_1" <?php if($output['seller_info']['is_owner'] == 1) {?>checked<?php }?>>
              <label for="is_owner_1">店长</label>
            </li>
            <li>
              <input type="radio" name="is_owner" value="2" id="is_owner_2" <?php if($output['seller_info']['is_owner'] == 2) {?>checked<?php }?>>
              <label for="is_owner_2">导购员</label>
            </li>
          </ul>
        <p class="hint">如果是店长则需要设置其管辖的导购</p>
      </dd>
    </dl>
    <dl imtype="im_is_owner" <?php if(empty($output['seller_info'])||$output['seller_info']['is_owner'] == 0) {?> style="display:none;"<?php }?>>
      <dt>管辖导购</dt>
    	<dd>
        <div>
          <?php if(!empty($output['salemen_list']) && is_array($output['salemen_list'])) {?>
          <ul class="imsc-account-container-list">
            <?php foreach($output['salemen_list'] as $seller) {?>
            <li>
              <input id="<?php echo $seller['seller_id'];?>" type="checkbox" class="checkbox" name="saleman_id[]" value="<?php echo $seller['seller_id'];?>" <?php if(!empty($output['saleman'])) {if(in_array($seller['seller_id'], $output['saleman'])) { echo 'checked'; }}?>/>
              <label for="<?php echo $seller['seller_id'];?>"><?php echo $seller['seller_name'];?></label>
            </li>
            <?php } ?>
          </ul>
          <?php } else {?>
          	<p class="hint">暂无其他导购员</p>
          <?php } ?>
        </div>        
    	</dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>账号组<?php echo $lang['im_colon'];?></dt>
      <dd><select name="group_id">
          <?php foreach($output['seller_group_list'] as $value) { ?>
          <option value="<?php echo $value['group_id'];?>" <?php echo $output['seller_info']['seller_group_id'] == $value['group_id']?'selected':'';?>><?php echo $value['group_name'];?></option>
          <?php } ?>
        </select>
        <span></span>
        <p class="hint"></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>是否默认登录此店铺<?php echo $lang['im_colon'];?></dt>
      <dd>
      	<ul class="imsc-form-radio-list">
            <li>
              <input type="radio" name="is_default" value="0" id="is_default_0" <?php if(empty($output['seller_info'])||$output['seller_info']['is_default'] == 0) {?>checked<?php }?>>
              <label for="is_owner_0">否</label>
            </li>
            <li>
              <input type="radio" name="is_default" value="1" id="is_default_1" <?php if($output['seller_info']['is_default'] == 1) {?>checked<?php }?>>
              <label for="is_owner_1">是</label>
            </li>
          </ul>
        <p class="hint">一个员工可能管理多家店铺，此处为了让员工登录后台的时候默认登录的店铺</p>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" value="<?php echo $lang['im_submit'];?>">
      </label>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#btn_select_all').on('click', function() {
        if($(this).prop('checked')) {
            $(this).parents('dd').find('input:checkbox').prop('checked', true);
        } else {
            $(this).parents('dd').find('input:checkbox').prop('checked', false);
        }
    });
	$('[name="is_owner"]').change(function() {
		if ($('[name="is_owner"]:checked').val() == '0'){
			$('[imtype="im_is_owner"]').hide();
		}else{
			$('[imtype="im_is_owner"]').show();
		}
	});
    $('#add_form').validate({
        onkeyup: false,
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
        rules: {
            group_id: {
                required: true
            }
        },
        messages: {
            group_id: {
                required: '<i class="fa fa-exclamation-circle"></i>请选择账号组'
            }
        }
    });
});
</script> 
