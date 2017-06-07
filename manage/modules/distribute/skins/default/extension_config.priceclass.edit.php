<?php defined('InIMall') or exit('Access Invalid!');?>

  <form id="extension_config_form" name="extension_config_form" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('extension_config', 'priceclass_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <?php if ($output['price_info']['pid']!=0) { ?>
    <input type="hidden" name="pid" value="<?php echo $output['price_info']['pid']; ?>" />
    <?php } ?>
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="sg_name">模板名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input class="text w200" type="text" name="pname" id="pname" value="<?php echo $output['price_info']['pname']; ?>" /></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="sg_name">平台商品定价模版:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <label><input type="radio" name="ptype" imtype="ptype" value="0" <?php if ($_GET['pid']=='' || $output['price_info']['ptype']==0) echo 'checked="checked"'; ?> />正常销售</label>
            <label><input type="radio" name="ptype" imtype="ptype" value="1" <?php if ($output['price_info']['ptype']==1) echo 'checked="checked"'; ?> />众筹模式</label>
            <label><input type="radio" name="ptype" imtype="ptype" value="2" <?php if ($output['price_info']['ptype']==2) echo 'checked="checked"'; ?> />预售模式</label>
            <label><input type="radio" name="ptype" imtype="ptype" value="3" <?php if ($output['price_info']['ptype']==3) echo 'checked="checked"'; ?> />旺销模式</label>
            <label><input type="radio" name="ptype" imtype="ptype" value="4" <?php if ($output['price_info']['ptype']==4) echo 'checked="checked"'; ?> />库存尾货</label>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="sg_name">供应商利润比例:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input class="text w60" type="text" name="profit_rate" id="profit_rate" value="<?php echo intval($output['price_info']['profit_rate']); ?>"  />
            <label id="profit_rate_title">
            <?php 
		      switch ($output['price_info']['ptype']){
			      case 0:
			        echo " ";
				    break;
			      case 1:
			        echo " %";
				    break;
			      case 2:
			        echo " %";
				    break;
			      case 3:
			        echo " %";
				    break;
			    case 4:
			    	echo " %";
			    	break;
			      default:
			        echo "";
				    break;
		      }
		    ?>
		   	 供应商利润
            </label>
          </td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label class="validation" for="sg_name">供应商回款比例:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input class="text w60" type="text" name="huik_rate" id="huik_rate" value="<?php echo intval($output['price_info']['huik_rate']); ?>"  />
            %&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <label id="cost_rate_title">供应商回款 = 供应商成本 + 供应商利润</label>
          </td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label class="validation" for="sg_name">平台扣点:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input class="text w60" type="text" readonly name="mall_points" id="mall_points" value="<?php echo intval($output['price_info']['mall_points']); ?>"  />
            <label id="mall_points">%</label>
          </td>
        </tr>
        
        <tr>
          <td colspan="2" class="required"><label class="validation" for="sg_name">门店租金补贴:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
          	<input class="text w60" type="text" readonly name="store_subsidy" id="store_subsidy" value="<?php echo intval($output['price_info']['store_subsidy']); ?>"  />
            %
            <label id="store_subsidy"></label>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="sg_name">3级推广+高管管理补贴:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input class="text w60" type="text" name="tuig_subsidy" id="tuig_subsidy" value="<?php echo intval($output['price_info']['tuig_subsidy']); ?>"  />
            <label id="extension_subsidy">%</label>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><span><?php echo $lang['im_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
<script type="text/javascript">
$(function(){
    $('#extension_config_form').validate({
    	errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            pname : {
                required : true
            },
            profit_rate : {
				required : true,
                number   : true
            }
        },
        messages : {
            pname : {
                required : '名称不能为空'

            },
            profit_rate  : {
				required : '比率不能为空',
                number   : '比率必须是数字'
            }
        }
    });
});
$('#huik_rate').mouseout(function(){
	var profit = $('#profit_rate').val();
	var huik = $('#huik_rate').val();
	var cost = (parseInt(huik) + parseInt(profit))/2;
	//$('#cost_rate_title').html("供应商成本比:"+cost+"%");
	
 });
$('[imtype="ptype"]').change(function() { 
    var selectedvalue = $('[imtype="ptype"]:checked').val();
    if (selectedvalue=='0'){
	    $('#profit_rate').val(0);
		$('#profit_rate_title').hide();
	}else if (selectedvalue=='1'){
		$('#profit_rate_title').show();
		$('#profit_rate_title').html(" %");
	}else if (selectedvalue=='2'){
		$('#profit_rate_title').show();
		$('#profit_rate_title').html(" %");
	}else if (selectedvalue=='3'){
		$('#profit_rate_title').show();
		$('#profit_rate_title').html(" %");
	}else if (selectedvalue=='4'){
		$('#profit_rate_title').show();
		$('#profit_rate_title').html(" %");
	}
});
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
        if($("#extension_config_form").valid()){
            ajaxpost('extension_config_form', '', '', 'onerror') 
	    }
	});
});
</script> 
