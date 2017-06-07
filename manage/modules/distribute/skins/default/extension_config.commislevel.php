<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>推广管理</h3>
        <h5>设置平台推广体系的相关数据</h5>
      </div>
      <?php echo $output['top_link'];?></div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>推广佣金分配，是指推广员自已购买或推广成功后，团队成员分享推广佣金的比率，总额是100</li>
      <li>上级可以分享下级的推广佣金，但下级不能分享上级的推广佣金</li>
    </ul>
  </div>
  
  <div class="fixed-empty"></div>
  <form id="extension_config_form" name="form_config" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('extension_config', 'commislevel_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="mcr_id" value="<?php echo $output['level_rate']['mcr_id'];?>" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="required w150 tr"><label class="validation" for="rate_manage">高管奖励提留比率: </label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['level_rate']['rate_manage']?$output['level_rate']['rate_manage']:0;?>" name="rate_manage" id="rate_manage" class="w60">%
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="rate_perfor">门店补贴提留比率: </label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['level_rate']['rate_perfor']?$output['level_rate']['rate_perfor']:0;?>" name="rate_perfor" id="rate_perfor" class="w60">%
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="rate_level1">一级代理佣金比率: </label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['level_rate']['rate_level1']?$output['level_rate']['rate_level1']:0;?>" name="rate_level1" id="rate_level1" class="w60">%
          </td>
          <td class="vatop tips"></td>
        </tr>
        <?php if ($output['promotion_level']>1){?>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="rate_level2">二级代理佣金比率: </label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['level_rate']['rate_level2']?$output['level_rate']['rate_level2']:0;?>" name="rate_level2" id="rate_level2" class="w60">%
          </td>
          <td class="vatop tips"></td>
        </tr>
        <?php }?>
        <?php if ($output['promotion_level']>2){?>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="rate_level3">三级代理佣金比率: </label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['level_rate']['rate_level3']?$output['level_rate']['rate_level3']:0;?>" name="rate_level3" id="rate_level3" class="w60">%
          </td>
          <td class="vatop tips"></td>
        </tr>
        <?php }?>
        <?php if ($output['promotion_level']>3){?>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="rate_level4">四级代理佣金比率: </label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['level_rate']['rate_level4']?$output['level_rate']['rate_level4']:0;?>" name="rate_level4" id="rate_level4" class="w60">%
          </td>
          <td class="vatop tips"></td>
        </tr>
        <?php }?>
        <?php if ($output['promotion_level']>4){?>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="rate_level5">五级代理佣金比率: </label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['level_rate']['rate_level5']?$output['level_rate']['rate_level5']:0;?>" name="rate_level5" id="rate_level5" class="w60">%
          </td>
          <td class="vatop tips"></td>
        </tr>
        <?php }?>
        <?php if ($output['promotion_level']>5){?>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="rate_level6">六级代理佣金比率: </label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['level_rate']['rate_level6']?$output['level_rate']['rate_level6']:0;?>" name="rate_level6" id="rate_level6" class="w60">%
          </td>
          <td class="vatop tips"></td>
        </tr>
        <?php }?>
        <?php if ($output['promotion_level']>6){?>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="rate_level7">七级代理佣金比率: </label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['level_rate']['rate_level7']?$output['level_rate']['rate_level7']:0;?>" name="rate_level7" id="rate_level7" class="w60">%
          </td>
          <td class="vatop tips"></td>
        </tr>
        <?php }?>
        <?php if ($output['promotion_level']>7){?>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="rate_level8">八级代理佣金比率: </label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['level_rate']['rate_level8']?$output['level_rate']['rate_level8']:0;?>" name="rate_level8" id="rate_level8" class="w60">%
          </td>
          <td class="vatop tips"></td>
        </tr>
        <?php }?>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td class="required" align="right"></td>
          <td colspan="15" class="vatop rowform" align="left"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><span><?php echo $lang['im_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(document).ready(function(){
	//按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#extension_config_form").valid()){
            var totals=Number($('#rate_manage').val())+Number($('#rate_perfor').val())+Number($('#rate_level1').val());
			<?php if ($output['promotion_level']>1){?>
			totals+=Number($('#rate_level2').val());
			<?php }?>
			<?php if ($output['promotion_level']>2){?>
			totals+=Number($('#rate_level3').val());
			<?php }?>
			<?php if ($output['promotion_level']>3){?>
			totals+=Number($('#rate_level4').val());
			<?php }?>
			<?php if ($output['promotion_level']>4){?>
			totals+=Number($('#rate_level5').val());
			<?php }?>
			<?php if ($output['promotion_level']>5){?>
			totals+=Number($('#rate_level6').val());
			<?php }?>
			<?php if ($output['promotion_level']>6){?>
			totals+=Number($('#rate_level7').val());
			<?php }?>
			<?php if ($output['promotion_level']>7){?>
			totals+=Number($('#rate_level8').val());
			<?php }?>
			if (totals>100) {
				showError('佣金比率总和不能超过100');
				return false;
			}			
    		ajaxpost('extension_config_form', '', '', 'onerror')
        }
    });
	
	$('#extension_config_form').validate({
        rules : {
			rate_manage  : {
                required : true,
                number   : true
            },
			rate_perfor  : {
                required : true,
                number   : true
            },
            rate_level1  : {
                required : true,
                number   : true
            }
			<?php if ($output['promotion_level']>1){?>
			,
            rate_level2  : {
                required : true,
                number   : true
            }
			<?php }?>
            <?php if ($output['promotion_level']>2){?>
		    ,
            rate_level3  : {
                required : true,
                number   : true
            }
			<?php }?>
            <?php if ($output['promotion_level']>3){?>
			,
            rate_level4  : {
                required : true,
                number   : true
            }
			<?php }?>
            <?php if ($output['promotion_level']>4){?>
			,
            rate_level5  : {
                required : true,
                number   : true
            }
			<?php }?>
            <?php if ($output['promotion_level']>5){?>
			,
            rate_level6  : {
                required : true,
                number   : true
            }
			<?php }?>
            <?php if ($output['promotion_level']>6){?>
			,
            rate_level7  : {
                required : true,
                number   : true
            }
			<?php }?>
            <?php if ($output['promotion_level']>7){?>
			,
            rate_level8  : {
                required : true,
                number   : true
            }
			<?php }?>
        },
        messages : {
			rate_manage  : {
                required : '分享比率不能为空',
                number   : '分享比率必须是数字'
            },
			rate_perfor  : {
                required : '分享比率不能为空',
                number   : '分享比率必须是数字'
            },
            rate_level1  : {
                required : '分享比率不能为空',
                number   : '分享比率必须是数字'
            }
			<?php if ($output['promotion_level']>1){?>
			,
			rate_level2  : {
                required : '分享比率不能为空',
                number   : '分享比率必须是数字'
            }
			<?php }?>
            <?php if ($output['promotion_level']>2){?>
			,
			rate_level3  : {
                required : '分享比率不能为空',
                number   : '分享比率必须是数字'
            }
			<?php }?>
            <?php if ($output['promotion_level']>3){?>
			,
			rate_level4  : {
                required : '分享比率不能为空',
                number   : '分享比率必须是数字'
            }
			<?php }?>
            <?php if ($output['promotion_level']>4){?>
			,
			rate_level5  : {
                required : '分享比率不能为空',
                number   : '分享比率必须是数字'
            }
			<?php }?>
            <?php if ($output['promotion_level']>5){?>
			,
			rate_level6  : {
                required : '分享比率不能为空',
                number   : '分享比率必须是数字'
            }
			<?php }?>
            <?php if ($output['promotion_level']>6){?>
			,
			rate_level7  : {
                required : '分享比率不能为空',
                number   : '分享比率必须是数字'
            }
			<?php }?>
            <?php if ($output['promotion_level']>7){?>
			,
			rate_level8  : {
                required : '分享比率不能为空',
                number   : '分享比率必须是数字'
            }
			<?php }?>
        }
    });
});
</script>