<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="eject_con">
  <form id="commisclass_form" method="post" target="_parent" action="<?php echo urlShop('seller_extension', 'commisclass_save');?>">
    <?php if ($output['commis_info']['commis_id']!=0) { ?>
    <input type="hidden" name="commis_id" value="<?php echo $output['commis_info']['commis_id']; ?>" />
    <?php } ?>
    <dl>
      <dt><i class="required">*</i>名称：</dt>
      <dd>
        <input class="text w200" type="text" name="commis_name" id="commis_name" value="<?php echo $output['commis_info']['commis_name']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>佣金类型：</dt>
      <dd>
        <label><input type="radio" name="commis_class" imtype="commis_class" value="0" <?php if ($_GET['commis_id']=='' or $output['commis_info']['commis_class']==0) echo 'checked="checked"'; ?> />无佣金</label>
        <label><input type="radio" name="commis_class" imtype="commis_class" value="1" <?php if ($output['commis_info']['commis_class']==1) echo 'checked="checked"'; ?> />固定佣金</label>
        <label><input type="radio" name="commis_class" imtype="commis_class" value="2" <?php if ($output['commis_info']['commis_class']==2) echo 'checked="checked"'; ?> />售价的百分比</label>
        <label><input type="radio" name="commis_class" imtype="commis_class" value="3" <?php if ($output['commis_info']['commis_class']==3) echo 'checked="checked"'; ?> />利润的百分比</label>
      </dd>
    </dl>
    <dl>
      <dt>佣金比率：</dt>
      <dd>
        <input class="text w60" type="text" name="commis_rate" id="commis_rate" value="<?php echo intval($output['commis_info']['commis_rate']); ?>"  />
        <label id="commis_rate_title">
        <?php 
		  switch ($output['commis_info']['commis_class']){
			  case 0:
			    echo "";
				break;
			  case 1:
			    echo " 元";
				break;
			  case 2:
			    echo " %";
				break;
			  case 3:
			    echo " %";
				break;
			  default:
			    echo "";
				break;
		  }
		?>
        </label>
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="保存" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#commisclass_form').validate({
    	submitHandler:function(form){
    		ajaxpost('commisclass_form', '', '', 'onerror') 
    	},
        rules : {
            commis_name : {
                required : true
            },
            commis_rate : {
				required : true,
                number   : true
            }
        },
        messages : {
            commis_name : {
                required : '名称不能为空'

            },
            commis_rate  : {
				required : '比率不能为空',
                number   : '比率必须是数字'
            }
        }
    });
});
$('[imtype="commis_class"]').change(function() { 
    var selectedvalue = $('[imtype="commis_class"]:checked').val();
    if (selectedvalue=='0'){
	    $('#commis_rate').val(0);
		$('#commis_rate_title').hide();
	}else if (selectedvalue=='1'){
		$('#commis_rate_title').show();
		$('#commis_rate_title').html(" 元");
	}else if (selectedvalue=='2'){
		$('#commis_rate_title').show();
		$('#commis_rate_title').html(" %");
	}else if (selectedvalue=='3'){
		$('#commis_rate_title').show();
		$('#commis_rate_title').html(" %");
	}
});
</script> 
