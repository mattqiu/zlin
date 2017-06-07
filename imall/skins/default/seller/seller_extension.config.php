<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="alert alert-block mt10">
  <ul class="mt5">
    <li>1、导购员与推广员的区别是：导购员没有下线，推广员可以拥有下线并分享下线的佣金。</li>
    <li>2、导购员推销成功，将获得商品的全部推广佣金，推广员根据所在级别获得相应比率的佣金提成。</li>
    <li>3、导购员可以同时为多家店铺进行推广，推广员只能为一家店铺进行推广活动。</li>
    <li>4、上级推广员按比率分享下线的佣金，下级推广员不能分享上级的佣金。</li>
  </ul>
</div>

<div class="imsc-form-default">
  <form id="form_config" method="post" action="<?php echo urlShop('seller_extension', 'config_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <dl>
      <dt>推广员在线申请<?php echo $lang['im_colon'];?></dt>
      <dd>
        <label for="promotion_open_on" class="mr30">
          <input id="promotion_open_on" type="radio" class="radio vm mr5" name="promotion_open" value="1" <?php echo $output['promotion_open'] > 0?'checked':'';?>>开启
        </label>
        <label for="promotion_open_off">
          <input id="promotion_open_off" type="radio" class="radio vm mr5" name="promotion_open" value="0" <?php echo $output['promotion_open'] == 0?'checked':'';?>>关闭
        </label>
        <p class="hint">选择是否开启推广员在线申请；<br/>
          如选择“是”，则商城会员将可以在店铺首页提交申请成为店铺推广员；<br/>
          如选择“否”，则店铺推广员只能由管理员在店铺后台添加;
        </p>
      </dd>
    </dl>
    <?php if ($output['promotion_level_op']==1){?>
    <dl>
      <dt>推广员最大级数<?php echo $lang['im_colon'];?></dt>
      <dd>
        <div class="imcs-figure-input">
        <input type="text" name="promotion_level" id="promotion_level" value="<?php echo $output['promotion_level'];?>" size="3" maxlength="6" class="text w30" >
        <a href="javascript:void(0)" class="spininput increase">+</a><a href="javascript:void(0)" class="spininput decrease">-</a>
        </div>
        <p class="hint">
          设置推广员最大级数，最小：1级，最大：8级
        </p>
      </dd>
    </dl>
    <?php }?>
    <dl>
      <dt>推广员申请条件<?php echo $lang['im_colon'];?></dt>
      <dd>
        <input name="promotion_require" id="promotion_require" value="<?php echo $output['promotion_require'];?>" class="txt" type="text">
        <p class="hint">推广员申请条件是指顾客必须达到此最低消费额才能申请本店推广员，为0表示不限制。</p>
      </dd>
    </dl>
    <dl>
      <dt>导购员在线申请<?php echo $lang['im_colon'];?></dt>
      <dd>
        <label for="saleman_open_on" class="mr30">
          <input id="saleman_open_on" type="radio" class="radio vm mr5" name="saleman_open" value="1" <?php echo $output['saleman_open'] > 0?'checked':'';?>>开启
        </label>
        <label for="saleman_open_off">
          <input id="saleman_open_off" type="radio" class="radio vm mr5" name="saleman_open" value="0" <?php echo $output['saleman_open'] == 0?'checked':'';?>>关闭
        </label>
        <p class="hint">选择是否开启导购员在线申请；<br/>
          如选择“是”，则商城会员将可以在店铺首页提交申请成为店铺导购员；<br/>
          如选择“否”，则店铺导购员只能由管理员在店铺后台添加;
        </p>
      </dd>
    </dl>
    <dl>
      <dt>导购员申请条件<?php echo $lang['im_colon'];?></dt>
      <dd>
        <input name="saleman_require" id="saleman_require" value="<?php echo $output['saleman_require'];?>" class="txt" type="text">
        <p class="hint">导购员申请条件是指顾客必须达到此最低消费额才能申请本店导购员，为0表示不限制。</p>
      </dd>
    </dl>
    <dl>
      <dt>店铺推广弹窗广告<?php echo $lang['im_colon'];?></dt>
      <dd>
        <?php showEditor('extension_adv',$output['extension_adv'],'100%','150px','visibility:hidden;',"true",false,'simple');?>
        <p class="hint">店铺推广弹窗广告是顾客进入本店时向顾客推送的推广信息弹窗广告，限制在100字以内。</p>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border">
        <input id="btn_submit" type="submit" class="submit" value="提交" />
      </label>
    </div>
  </form>
</div>

<script>
$(document).ready(function(){
	// 增加
	$('.increase').click(function(){
		num = parseInt($('#promotion_level').val());
	    max = 8;
		if(num < max){
			$('#promotion_level').val(num+1);
		}
	});
	//减少
	$('.decrease').click(function(){
		num = parseInt($('#promotion_level').val());
		if(num > 1){
			$('#promotion_level').val(num-1);
		}
	});

	$('#form_config').validate({
		submitHandler:function(form){
			var level=Number($('#promotion_level').val());
			if (level>8) {
				showError('推广员级数不能超过8级');
				return false;
			}	
			if (level<1) {
				showError('推广员级数不能少于1级');
				return false;
			}		
    		ajaxpost('form_config', '', '', 'onerror')
    	},
        rules : {
            promotion_open  : {
                required : true
            },
            promotion_level  : {
                required : true,
                number   : true
            },
			promotion_require  : {
                required : true,
                number   : true
            },
            saleman_open  : {
                required : true
            },
			saleman_require  : {
                required : true,
                number   : true
            }
        },
        messages : {
            promotion_open  : {
                required : '请选择是否开启推广员在线申请'
            },
			promotion_level  : {
                required : '请输入推广员级数',
                number   : '推广员级数必须是数字'
            },
			promotion_require  : {
                required : '请输入推广员申请条件',
                number   : '申请条件必须是数字'
            },			
			saleman_open  : {
                required : '请选择是否开启导购员在线申请'
            },
			saleman_require  : {
                required : '请输入导购员申请条件',
                number   : '申请条件必须是数字'
            }
        }
    });
});
</script>