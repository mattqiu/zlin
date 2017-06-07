<?php defined('InIMall') or exit('Access Invalid!');?>

<style type="text/css">
.cms-sns-content a.agreement {
	color: rgb(197, 56, 1); font-weight: 600;
}
.cms-sns-content a.agreement:hover {
	text-decoration: underline;
}
</style>

<div class="cms-sns">
  <div class="cms-sns-left">
    <div class="cms-sns-content">       
      <div class="apply_join">
        <div class="ad"></div>
        <div class="content">
		  <div class="content-agent">
            <h2>注册店铺推广
              <?php if (!empty($output['store_info']['store_phone'])){;?>
              <span class="cb2a">或直接致电：<strong><?php echo $output['store_info']['store_phone'];?></strong></span>
              <?php }?>
            </h2>
          </div>          
		  <hr class="line2" />
		  <form id="extensionreg_form" method="post" target="_parent" action="<?php echo urlShop('store_addapply', 'reg_extension_save',array('store_id'=>$output['store_info']['store_id']));?>">
          <dl style="min-height:30px;">
            <dt></dt>
            <dd>
              <?php if ($output['store_info']['saleman_apply'] == 1 && $output['ai_type']!=3){?>
              <input type="radio" name="ai_type" value="1" checked="checked"/>导购员&nbsp;&nbsp;
              <?php }?>
              <?php if ($output['store_info']['promotion_apply'] == 1){?>
              <input type="radio" name="ai_type" value="2" checked="checked"/>推广员
              <?php }?>
            </dd>
          </dl>
          
          <dl>
            <dt><?php echo $lang['login_register_username'];?>：</dt>
            <dd>
              <input type="text" id="user_name" name="user_name" class="text tip" title="<?php echo $lang['login_register_username_to_login'];?>" autofocus />
            </dd>
          </dl>
          <dl>
            <dt><?php echo $lang['login_register_pwd'];?>：</dt>
            <dd>
              <input type="password" id="password" name="password" class="text tip" title="<?php echo $lang['login_register_password_to_login'];?>" />
            </dd>
          </dl>
          <dl>
            <dt><?php echo $lang['login_register_ensure_password'];?>：</dt>
            <dd>
              <input type="password" id="password_confirm" name="password_confirm" class="text tip" title="<?php echo $lang['login_register_input_password_again'];?>"/>
            </dd>
          </dl>
          <dl>
            <dt>手机：</dt>
            <dd>
              <input type="text" id="mobile" name="mobile" class="text tip" title="请输入联系电话" />
            </dd>
          </dl>
          <dl>
            <dt>姓名：</dt>
            <dd  style="min-height:54px;">
              <input type="text" id="truename" name="truename" class="text tip" title="请输入真实姓名" />
            </dd>
          </dl>
          <dl>
            <dt><?php echo $lang['login_register_email'];?>：</dt>
            <dd>
              <input type="text" id="email" name="email" class="text tip" title="<?php echo $lang['login_register_input_valid_email'];?>" />
            </dd>
          </dl>
          <dl>
            <dt>QQ：</dt>
            <dd>
              <input type="text" id="qq" name="qq" class="text tip" title="请输入常用QQ号" />
            </dd>
          </dl>
          <dl>
            <dt>所在地：</dt>
            <dd>
              <input type="text" id="areainfo" name="areainfo" class="text tip" title="请输入现居住地联系地址" />
            </dd>
          </dl>
          <dl>
            <dt>留言：</dt>
            <dd>
              <textarea class="textarea" cols="50" name="describe" rows="10">这家伙很懒，什么都不肯留下!</textarea>
            </dd>
          </dl>
          <dl>
            <dt>&nbsp;</dt>
            <dd>
              <input type="submit" id="Submit" value="立即注册" class="submit" title="立即注册" />
              <input name="agree" type="checkbox" class="vm ml10" id="clause" value="1" checked="checked" />
              <span for="clause" class="ml5"><?php echo $lang['login_register_agreed'];?><a href="<?php echo urlShop('document', 'index',array('code'=>'agreement'));?>" target="_blank" class="agreement" title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_agreement'];?></a></span>
              <label></label>
            </dd>
          </dl>
		  </form>
	    </div>
      </div>
      
    </div>
  </div>
  <div class="cms-sns-right">
    <div class="cms-sns-right-container">
      <div class="cms-store-pic">
        <a><img src="<?php echo getStoreLogo($output['store_info']['store_avatar']);?>" alt="<?php echo $output['store_info']['store_name'];?>" title="<?php echo $output['store_info']['store_name'];?>" /></a>
      </div>
      <dl class="cms-store-info">
        <dt><?php echo $output['store_info']['store_name']; ?></dt>
        <dd>已收藏：<em imtype="store_collect"><?php echo $output['store_info']['store_collect']?></em></dd>
      </dl>
      <div class="cms-store-favorites"><a href="javascript:collect_store('<?php echo $output['store_info']['store_id'];?>','count','store_collect')" ><i class="fa fa-plus"></i>收藏店铺</a></div>
    </div>
    <div class="cms-sns-right-container">
      <?php if (!empty($output['favorites_list'])) {?>
      <div class="title">最新收藏用户</div>
      <div class="cms-favorites-user">
        <ul>
          <?php foreach ($output['favorites_list'] as $val) {?>
          <li><a target="_blank" href="<?php echo urlShop('member_snshome', 'index', array('mid'=>$val['member_id']));?>"><img alt="<?php echo $val['member_name'];?>" title="<?php echo $val['member_name'];?>" src="<?php echo getMemberAvatarForID($val['member_id']);?>" /></a></li>
          <?php }?>
        </ul>
      </div>
      <?php }?>
    </div>    
  </div>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script> 
<script type="text/javascript">
//注册表单提示
$('.tip').poshytip({
	className: 'tip-yellowsimple',
	showOn: 'focus',
	alignTo: 'target',
	alignX: 'center',
	alignY: 'top',
	offsetX: 0,
	offsetY: 5,
	allowTipHover: false
});

$(function(){
	jQuery.validator.addMethod("lettersonly", function(value, element) {
		return this.optional(element) || /^[^:%,'\*\"\s\<\>\&]+$/i.test(value);
	}, "Letters only please"); 
	jQuery.validator.addMethod("lettersmin", function(value, element) {
		return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length>=3);
	}, "Letters min please"); 
	jQuery.validator.addMethod("lettersmax", function(value, element) {
		return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length<=15);
	}, "Letters max please");		
	jQuery.validator.addMethod("phones", function(value, element) {
		return this.optional(element) || /^[1][3-8]+\d{9}/i.test(value);
	}, "phone number please"); 

    $('#extensionreg_form').validate({
		errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
        onkeyup: false,
    	submitHandler:function(form){
    		ajaxpost('applyadd_form', '', '', 'onerror') 
    	},
        rules : {
			user_name : {
                required : true,
                lettersmin : true,
                lettersmax : true,
                lettersonly : true,
                remote   : {
                    url :'index.php?act=login&op=check_member&column=ok',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#user_name').val();
                        }
                    }
                }
            },
            password : {
                required : true,
                minlength: 6,
				maxlength: 20
            },
            password_confirm : {
                required : true,
                equalTo  : '#password'
            },
            mobile : {
				required : true,
				phones   : true
            },
            truename : {
                required : true
            },
			agree : {
                required : true
            }
        },
        messages : {
			user_name : {
                required : '<?php echo $lang['login_register_input_username'];?>',
                lettersmin : '<?php echo $lang['login_register_username_range'];?>',
                lettersmax : '<?php echo $lang['login_register_username_range'];?>',
				lettersonly: '<?php echo $lang['login_register_username_lettersonly'];?>',
				remote	 : '<?php echo $lang['login_register_username_exists'];?>'
            },
            password  : {
                required : '<?php echo $lang['login_register_input_password'];?>',
                minlength: '<?php echo $lang['login_register_password_range'];?>',
				maxlength: '<?php echo $lang['login_register_password_range'];?>'
            },
            password_confirm : {
                required : '<?php echo $lang['login_register_input_password_again'];?>',
                equalTo  : '<?php echo $lang['login_register_password_not_same'];?>'
            },
            mobile : {
				required : '手机号码不能为空',
				phones   : '手机号码不正确'
            },
			truename : {
                required : '真实姓名不能为空'
            }
			agree : {
                required : '<?php echo $lang['login_register_must_agree'];?>'
            }
        }
    });
});
</script>