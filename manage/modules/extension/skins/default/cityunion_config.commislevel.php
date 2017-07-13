<?php defined('InIMall') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>城市联盟管理</h3>
        <h5>设置平台城市联盟体系的相关数据</h5>
      </div>
      <?php echo $output['top_link'];?></div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>城市联盟佣金分配，是指职业经理人邀请供应商或零售商成功后，团队成员再订货会上下单后所获取到的佣金比率，总额是100</li>
      <li>供应商或零售商均可成为职业经理人</li>      <li>如果邀请的是供应商：一次订货会完成后，职业经理人可获取到如10%的佣金，如果该职业经理人有上级邀请人，那么还需分给上一级30%的佣金，自己最终拿到返佣总额佣金的7%</li>      <li>如果邀请的是零售商：一次订货会完成后，职业经理人可获取到如10%的佣金，如果该职业经理人有上级邀请人，那么平台会再给上一级5%的佣金。零售商会获得20%的返利</li>      <li>总比公式：100 = 平台抽成 + 邀请供应商返佣总比 + 零售商自己返利 + 邀请零售商的层级返佣总和</li>
    </ul>
  </div>
  <div class="fixed-empty" style="margin-top:30px;"></div>
  <form id="cityunion_config_form" name="form_config" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('cityunion_config', 'commislevel_save');?>">
    <input type="hidden" name="form_submit" value="ok" />    <input type="hidden" name="partner_level" id="partner_level" value="<?php echo $output['partner_level'];?>" />	<input type="hidden" name="mcr_id" value="<?php echo $output['level_rate']['mcr_id'];?>" />    <table class="table tb-type2 nobdb">      <tbody>        <tr class="noborder">          <td class="required w150 tr"><label class="validation" for="rate_manage">平台提留比率: </label></td>          <td class="vatop rowform" align="left">            <input type="text" value="<?php echo $output['level_rate']['rate_manage']?$output['level_rate']['rate_manage']:0;?>" name="rate_manage" id="rate_manage" class="w60">%          </td>          <td class="vatop tips"></td>        </tr>        <tr class="noborder">          <td class="required tr"><label class="validation" for="rate_trader">零售商自己返利比率: </label></td>          <td class="vatop rowform" align="left">            <input type="text" value="<?php echo $output['level_rate']['rate_trader']?$output['level_rate']['rate_trader']:0;?>" name="rate_trader" id="rate_trader" class="w60">%          </td>          <td class="vatop tips"></td>        </tr>        <tr class="noborder">          <td class="required tr"><label class="validation" for="rate_supplier">邀请供应商总佣金比率: </label></td>          <td class="vatop rowform" align="left">            <input type="text" value="<?php echo $output['level_rate']['rate_supplier']?$output['level_rate']['rate_supplier']:0;?>" name="rate_supplier" id="rate_supplier" class="w60">%          </td>          <td class="vatop tips"></td>        </tr>      </tbody>    </table>    <div class="fixed-empty" style="margin-top:30px;">邀请供应商返佣比例设置</div>
    <table class="table tb-type2 nobdb" id="supplier_level">
      <tbody>
        <?php foreach ($output['level_rate']['rate_supplier_level'] as $slkey=>$slvar) {?>
        <tr class="noborder">
          <td class="required w150 tr"><label class="validation" for="rate_supplier_level">邀请供应商<?php echo $slkey;?>级佣金比率: </label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $slvar;?>" name="rate_supplier_level[<?php echo $slkey;?>]" id="rate_supplier_level<?php echo $slkey;?>" class="w60">%
          </td>
          <td class="vatop tips"></td>
        </tr>
        <?php }?>
      </tbody>
    </table>    	<div class="fixed-empty" style="margin-top:30px;">	  	邀请零售商返佣比例设置	  	<input type="hidden" id="trader_total" value="" />	</div>    <table class="table tb-type2 nobdb" id="trader_level">      <tbody>        <?php foreach ($output['level_rate']['rate_trader_level'] as $tlkey=>$tlvar) {?>        <tr class="noborder">          <td class="required w150 tr"><label class="validation" for="rate_trader_level">邀请供应商<?php echo $tlkey;?>级佣金比率: </label></td>          <td class="vatop rowform" align="left">            <input type="text" value="<?php echo $tlvar;?>" name="rate_trader_level[<?php echo $tlkey;?>]" id="rate_trader_level<?php echo $tlkey;?>" class="w60">%          </td>          <td class="vatop tips"></td>        </tr>        <?php }?>      </tbody>      <tfoot>        <tr class="tfoot">          <td class="required" align="right"></td>          <td colspan="15" class="vatop rowform" align="left"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><span><?php echo $lang['im_submit'];?></span></a></td>        </tr>      </tfoot>    </table>  </form>
</div>
<script>
$(document).ready(function(){	var rate_total = 100;//总佣金比率	var supplier_total = 0;//邀请供应商返佣总比	var trader_total = 0;//零售商层级返佣比	var rate_manage = 0;//平台抽成比率，自动计算	//循环 input绑定事件	$("#supplier_level").on("change","input",function(){		supplier_rate = Number($(this).val());		supplier_total += supplier_rate;		$(this).css("background-color","#FFFFCC");		$('#rate_supplier').val(supplier_total);//赋值给供应商返佣总比		rate_total -= supplier_rate;		if(rate_total<0){			alert("佣金设置有误，总和不可以大于100%");		}		$('#rate_manage').val(rate_total);//赋值给平台抽成比率	});	$("#trader_level").on("change","input",function(){		trader_rate = Number($(this).val());		trader_total += trader_rate;		$('#trader_total').val(trader_total);//赋值给零售商返佣总比		$(this).css("background-color","#FFFFCC");		rate_total -= trader_rate;		if(rate_total<0){			alert("佣金设置有误，总和不可以大于100%");		}		$('#rate_manage').val(rate_total);//赋值给平台抽成比率			});			$('#rate_trader').change(function(){		rate_trader = Number($(this).val());		$(this).css("background-color","#FFFFCC");		rate_total -= rate_trader;		if(rate_total<0){			alert("佣金设置有误，总和不可以大于100%");		}		$('#rate_manage').val(rate_total);//赋值给平台抽成比率	});	
	//按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#cityunion_config_form").valid()){
            var totals=Number($('#rate_manage').val())+Number($('#rate_trader').val())+Number($('#rate_supplier').val())+Number($('#trader_total').val());     	
			if (totals>100) {
				showError('佣金比率总和不能超过100');
				return false;
			}			
    		ajaxpost('cityunion_config_form', '', '', 'onerror')
        }
    });
	
	$('#cityunion_config_form').validate({
        rules : {
			rate_manage  : {
                required : true,
                number   : true
            },
            rate_supplier  : {
                required : true,
                number   : true
            },
			rate_trader  : {
                required : true,
                number   : true
            },
			
        },
        messages : {
			rate_manage  : {
                required : '平台抽拥比率不能为空',
                number   : '平台抽拥比率必须是数字'
            },
            rate_supplier  : {
                required : '供应商总抽成比率不能为空',
                number   : '供应商总抽成比率必须是数字'
            },
			rate_trader  : {
                required : '零售商自己返利比率不能为空',
                number   : '零售商自己返利比率必须是数字'
            }
        }
    });
});
</script>