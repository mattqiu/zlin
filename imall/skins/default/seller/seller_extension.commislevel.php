<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="alert alert-block mt10">
  <ul class="mt5">
    <li>佣金分成，是指当推广员推广成功后，各级别分享所得佣金的分成比率，总额是100</li>
    <li>上级可以分享下级的推广佣金，但下级不能分享上级的推广佣金</li>
  </ul>
</div>
<div class="item-publish">
  <form id="post_form" method="post" name="form1" action="index.php?act=seller_extension&op=commislevel_save">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="mcr_id" value="<?php echo $output['level_rate']['mcr_id'];?>" />
    <div class="main-content" id="mainContent">
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <tbody>
          <tr>
            <th>管理奖励抽佣比例: </th>
            <td><input name="rate_manage" id="rate_manage" value="<?php echo $output['level_rate']['rate_manage']?$output['level_rate']['rate_manage']:0;?>" class="txt" type="text">%</td>
          </tr>
          <tr>
            <th>业绩奖励抽佣比例: </th>
            <td><input name="rate_perfor" id="rate_perfor" value="<?php echo $output['level_rate']['rate_perfor']?$output['level_rate']['rate_perfor']:0;?>" class="txt" type="text">%</td>
          </tr>
          <tr>
            <th>一级推广员分佣比例: </th>
            <td><input name="rate_level1" id="rate_level1" value="<?php echo $output['level_rate']['rate_level1']?$output['level_rate']['rate_level1']:0;?>" class="txt" type="text">%</td>
          </tr>
          <?php if ($output['promotion_level']>1){?>
          <tr>
            <th>二级推广员分佣比例: </th>
            <td><input name="rate_level2" id="rate_level2" value="<?php echo $output['level_rate']['rate_level2']?$output['level_rate']['rate_level2']:0;?>" class="txt" type="text">%</td>
          </tr>
          <?php }?>
          <?php if ($output['promotion_level']>2){?>
          <tr>
            <th>三级推广员分佣比例: </th>
            <td><input name="rate_level3" id="rate_level3" value="<?php echo $output['level_rate']['rate_level3']?$output['level_rate']['rate_level3']:0;?>" class="txt" type="text">%</td>
          </tr>
          <?php }?>
          <?php if ($output['promotion_level']>3){?>
          <tr>
            <th>四级推广员分佣比例: </th>
            <td><input name="rate_level4" id="rate_level4" value="<?php echo $output['level_rate']['rate_level4']?$output['level_rate']['rate_level4']:0;?>" class="txt" type="text">%</td>
          </tr>
          <?php }?>
          <?php if ($output['promotion_level']>4){?>
          <tr>
            <th>五级推广员分佣比例: </th>
            <td><input name="rate_level5" id="rate_level5" value="<?php echo $output['level_rate']['rate_level5']?$output['level_rate']['rate_level5']:0;?>" class="txt" type="text">%</td>
          </tr>
          <?php }?>
          <?php if ($output['promotion_level']>5){?>
          <tr>
            <th>六级推广员分佣比例: </th>
            <td><input name="rate_level6" id="rate_level6" value="<?php echo $output['level_rate']['rate_level6']?$output['level_rate']['rate_level6']:0;?>" class="txt" type="text">%</td>
          </tr>
          <?php }?>
          <?php if ($output['promotion_level']>6){?>
          <tr>
            <th>七级推广员分佣比例: </th>
            <td><input name="rate_level7" id="rate_level7" value="<?php echo $output['level_rate']['rate_level7']?$output['level_rate']['rate_level7']:0;?>" class="txt" type="text">%</td>
          </tr>
          <?php }?>
          <?php if ($output['promotion_level']>7){?>
          <tr>
            <th>八级推广员分佣比例: </th>
            <td><input name="rate_level8" id="rate_level8" value="<?php echo $output['level_rate']['rate_level8']?$output['level_rate']['rate_level8']:0;?>" class="txt" type="text">%</td>
          </tr>  
          <?php }?>    
        </tbody>
      </table>
      <label class="submit-border">
        <input type="submit" class="submit" value="提交" />
      </label>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#post_form').validate({
		submitHandler:function(form){
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
    		ajaxpost('post_form', '', '', 'onerror')
    	},
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
                required : '比率不能为空',
                number   : '比率必须是数字'
            },
			rate_perfor  : {
                required : '比率不能为空',
                number   : '比率必须是数字'
            },
            rate_level1  : {
                required : '比率不能为空',
                number   : '比率必须是数字'
            }
			<?php if ($output['promotion_level']>1){?>
			,
			rate_level2  : {
                required : '比率不能为空',
                number   : '比率必须是数字'
            }
			<?php }?>
            <?php if ($output['promotion_level']>2){?>
			,
			rate_level3  : {
                required : '比率不能为空',
                number   : '比率必须是数字'
            }
			<?php }?>
            <?php if ($output['promotion_level']>3){?>
			,
			rate_level4  : {
                required : '比率不能为空',
                number   : '比率必须是数字'
            }
			<?php }?>
            <?php if ($output['promotion_level']>4){?>
			,
			rate_level5  : {
                required : '比率不能为空',
                number   : '比率必须是数字'
            }
			<?php }?>
            <?php if ($output['promotion_level']>5){?>
			,
			rate_level6  : {
                required : '比率不能为空',
                number   : '比率必须是数字'
            }
			<?php }?>
            <?php if ($output['promotion_level']>6){?>
			,
			rate_level7  : {
                required : '比率不能为空',
                number   : '比率必须是数字'
            }
			<?php }?>
            <?php if ($output['promotion_level']>7){?>
			,
			rate_level8  : {
                required : '比率不能为空',
                number   : '比率必须是数字'
            }
			<?php }?>
        }
    });
});
</script>