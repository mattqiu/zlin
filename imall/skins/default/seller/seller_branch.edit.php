<div class="eject_con">
  <form id="branch_form" method="post" target="_parent" action="<?php echo urlShop('seller_branch', 'branch_save');?>">
    <?php if (isset($output['branch_info']['store_id'])) { ?>
    <input type="hidden" name="branch_id" value="<?php echo $output['branch_info']['store_id'];?>" />
    <input type="hidden" name="member_id" value="<?php echo $output['branch_info']['member_id'];?>" />
    <?php } ?>
    <dl>
      <dt>店铺帐号：</dt>
      <dd>
        <?php if (isset($output['branch_info']['store_id'])) { ?>
        <?php echo $output['branch_info']['seller_name']; ?>
        <?php }else{?>
        <input class="text w200" type="text" name="seller_name" id="seller_name" value="" />
        <?php }?>
      </dd>
    </dl>
    <dl>
      <dt>店铺名称：</dt>
      <dd>
        <input class="text w200" type="text" name="store_name" id="store_name" value="<?php echo $output['branch_info']['store_name']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>设置密码：</dt>
      <dd>
        <input class="text w200" type="password" name="password" id="password" value="" />
      </dd>
    </dl>
    <?php if (!isset($output['branch_info']['store_id'])) { ?>
    <dl>
      <dt>确认密码：</dt>
      <dd>       
        <input class="text w200" type="password" name="password_confirm" id="password_confirm" value="" />        
      </dd>
    </dl> 
    <?php }?>  
    <dl>
      <dt>店铺等级：</dt>
      <dd> 
        <select name="grade_id" id="grade_id">
          <option value="">请选择</option>
          <?php if(!empty($output['grade_list']) && is_array($output['grade_list'])){ ?>
          <?php foreach($output['grade_list'] as $k => $v){ ?>
          <?php $goods_limit = empty($v['sg_goods_limit'])?'不限':$v['sg_goods_limit'];?>
          <?php $explain = '商品数：'.$goods_limit.' 模板数：'.$v['sg_template_number'].' 收费标准：'.$v['sg_price'].'元/年'.' 附加功能：'.$v['function_str'];?>
          <option value="<?php echo $v['sg_id'];?>" data-explain="<?php echo $explain;?>" <?php if ($output['branch_info']['grade_id']==$v['sg_id']){?>selected="selected"<?php }?>><?php echo $v['sg_name'];?></option>
          <?php } ?>
          <?php } ?>
        </select>
      </dd>
    </dl>
    <dl>
      <dt>支付方式：</dt>
      <dd>
        <label for="payment_method0"><input type="radio" <?php if($output['branch_info']['payment_method'] == 0){ ?>checked="checked"<?php } ?> value="0" name="payment_method" id="payment_method0">支付到卖家</label>
        <label for="payment_method1"><input type="radio" <?php if($output['branch_info']['payment_method'] == 1){ ?>checked="checked"<?php } ?> value="1" name="payment_method" id="payment_method1">支付到平台</label>
        <label for="payment_method2"><input type="radio" <?php if($output['branch_info']['payment_method'] == 2){ ?>checked="checked"<?php } ?> value="2" name="payment_method" id="payment_method2">支付到总店</label>
      </dd>
    </dl>    
    <dl>
      <dt>公司名称：</dt>
      <dd>
        <input class="text w200" type="text" name="store_company_name" id="store_company_name" value="<?php echo $output['branch_info']['store_company_name']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>公司所在地：</dt>
      <dd>
        <input id="area_info" name="area_info" type="hidden" value="<?php echo $output['branch_info']['area_info']; ?>"/>
      </dd>
    </dl>
    <dl>
      <dt>公司详细地址：</dt>
      <dd>
        <input id="store_address" name="store_address" type="text" class="w200" value="<?php echo $output['branch_info']['store_address']; ?>">
      </dd>
    </dl>
    <dl>
      <dt>店主姓名：</dt>
      <dd>
        <input class="text w100" type="text" name="member_truename" id="member_truename" value="<?php echo $output['branch_info']['member_truename']; ?>" />性别：        
        <label for="member_sex0"><input type="radio" <?php if($output['branch_info']['member_sex'] == 0){ ?>checked="checked"<?php } ?> value="0" name="member_sex" id="member_sex0">保密</label>            
        <label for="member_sex1"><input type="radio" <?php if($output['branch_info']['member_sex'] == 1){ ?>checked="checked"<?php } ?> value="1" name="member_sex" id="member_sex1">男</label>            
        <label for="member_sex2"><input type="radio" <?php if($output['branch_info']['member_sex'] == 2){ ?>checked="checked"<?php } ?> value="2" name="member_sex" id="member_sex2">女</label>
      </dd>
    </dl>
    <dl>
      <dt>店主手机：</dt>
      <dd>
        <input class="text w200" type="text" name="member_mobile" id="member_mobile" value="<?php echo $output['branch_info']['member_mobile']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>店主QQ：</dt>
      <dd>
        <input class="text w200" type="text" name="member_qq" id="member_qq" value="<?php echo $output['branch_info']['member_qq']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>店主邮箱：</dt>
      <dd>
        <input class="text w200" type="text" name="member_email" id="member_email" value="<?php echo $output['branch_info']['member_email']; ?>" />
      </dd>
    </dl>    
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="保存" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('#area_info').im_region();
	
    $('#branch_form').validate({
    	submitHandler:function(form){
    		ajaxpost('branch_form', '', '', 'onerror') 
    	},		
        rules : {
			<?php if (!isset($output['branch_info']['store_id'])) { ?>
		    password : {
                required : true
            },
			password_confirm : {
                required : true
            },
		    <?php } ?>
            seller_name : {
                required : true
            },
			store_name : {
                required : true
            },
			grade_id : {
                required : true
            },
			store_company_name : {
                required : true
            },
			area_info : {
                required : true
            },
			store_address : {
                required : true
            },
			member_truename : {
                required : true
            },
			member_mobile : {
                required : true
            },
			member_email : {
                required : true
            }
        },
        messages : {
			<?php if (!isset($output['branch_info']['store_id'])) { ?>
		    password : {
                required : '密码不能为空'
            },
			password_confirm : {
                required : '确认密码不能为空'
            },
		    <?php } ?>
            seller_name : {
                required : '店铺帐号不能为空'
            },
			store_name : {
                required : '店铺名称不能为空'
            },
			grade_id : {
                required : '店铺等级必须选择'
            },
			store_company_name : {
                required : '公司名称不能为空'
            },
			area_info : {
                required : '公司所在地不能为空'
            },
			store_address : {
                required : '公司详细地址不能为空'
            },
			member_truename : {
                required : '店主姓名不能为空'
            },
			member_mobile : {
                required : '手机不能为空'
            },
			member_email : {
                required : '电子邮箱不能为空'
            }
        }
    });
});
</script> 
