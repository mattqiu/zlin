<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="imsc-form-default">
  <form method="post" action="index.php?act=store_navigation&op=navigation_save" target="_parent" name="store_navigation_form" id="store_navigation_form" enctype="multipart/form-data">
    <input type="hidden" name="sn_id" value="<?php echo $output['sn_info']['sn_id'];?>"/>
    <dl>
      <dt><i class="required">*</i><?php echo $lang['store_navigation_name'].$lang['im_colon'];?></dt>
      <dd>
        <input type="text" class="w150 text" name="sn_title" value="<?php echo $output['sn_info']['sn_title'];?>" /><span></span>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_navigation_display'].$lang['im_colon'];?></dt>
      <dd>
        <ul class="imsc-form-radio-list">
          <li>
            <label for="sn_if_show_0"><input type="radio" class="radio" name="sn_if_show" id="sn_if_show_0" value="1"<?php if($output['sn_info']['sn_if_show'] == '1' || $output['sn_info']['sn_if_show'] == ''){?> checked="checked"<?php }?>/>
            <?php echo $lang['store_payment_yes'];?></label></li>
          <li>
            <label for="sn_if_show_1"><input type="radio" class="radio" name="sn_if_show" id="sn_if_show_1" value="0"<?php if($output['sn_info']['sn_if_show'] == '0'){?> checked="checked"<?php }?>/>
            <?php echo $lang['store_payment_no'];?></label></li>
        </ul>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_goods_class_sort'].$lang['im_colon'];?></dt>
      <dd>
        <input type="text" class="w50 text" name="sn_sort" value="<?php if($output['sn_info']['sn_sort'] != ''){ echo $output['sn_info']['sn_sort'];}else{echo '255';}?>"/>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_navigation_content'].$lang['im_colon'];?></dt>
      <dd>
        <?php showEditor('sn_content',$output['sn_info']['sn_content'],'600px','300px','','true',$output['editor_multimedia']); ?>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['store_navigation_url'].$lang['im_colon']; ?></dt>
      <dd>
        <p>
          <input type="text" class="w300 text" id="sn_url" name="sn_url" value="<?php echo $output['sn_info']['sn_url'];?>" />
          <a href="javascript:void(0)" class="imsc-btn imsc-btn-acidblue" im_type="dialog" dialog_title="选择快捷链接" dialog_id="my_quick_link_select" dialog_width="480" uri="index.php?act=store_navigation&op=quick_link&inputctrl=sn_url&sn_id=<?php echo $output['sn_info']['sn_id'];?>"><i class="fa fa-link"></i>快捷链接</a>
        </p>
        <p class="hint"><?php echo $lang['store_navigation_url_tip']; ?></p>
        </td>
    </dl>
    <dl>
      <dt><?php echo $lang['store_navigation_new_open'].$lang['im_colon']; ?></dt>
      <dd>
        <ul class="imsc-form-radio-list">
          <li>
            <label for="sn_new_open_1"><input type="radio" class="radio" name="sn_new_open" id="sn_new_open_1" value="1" <?php if($output['sn_info']['sn_new_open'] == '1' || $output['sn_info']['sn_new_open'] == ''){?> checked="checked"<?php }?>>
            <?php echo $lang['store_navigation_new_open_yes']; ?></label></li>
          <li>
            <label for="sn_new_open_0"><input type="radio" class="radio" name="sn_new_open" id="sn_new_open_0" value="0" <?php if($output['sn_info']['sn_new_open'] == '0'){?> checked="checked"<?php }?>>
            <?php echo $lang['store_navigation_new_open_no']; ?></label></li>
        </ul>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['store_goods_class_submit'];?>" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
	//页面输入内容验证
	$('#store_navigation_form').validate({
	        errorPlacement: function(error, element){
	            var error_td = element.parent('dd').children('span');
	            error_td.append(error);
	        },
	     	submitHandler:function(form){
	    		ajaxpost('store_navigation_form', '', '', 'onerror')
	    	},
        rules: {
            sn_title: {
                required: true,
                maxlength: 10
            }
        },
        messages: {
            sn_title: {
                required: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_navigation_name_null'];?>',
                maxlength: '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_navigation_name_max'];?>'
            }
        }
    });
});
</script> 
