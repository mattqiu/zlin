<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="cms-sns">
  <div class="cms-sns-left">
    <div class="cms-sns-content">       
      <div class="apply_join">
        <div class="ad"></div>
        <div class="content">
		  <div class="content-agent">
            <h2>申请加盟
              <?php if (!empty($output['store_info']['store_phone'])){;?>
              <span class="cb2a">或直接致电：<strong><?php echo $output['store_info']['store_phone'];?></strong></span>
              <?php }?>
            </h2>
          </div>          
		  <hr class="line2" />
		  <form id="applyadd_form" method="post" target="_parent" action="<?php echo urlShop('store_addapply', 'apply_join_save',array('store_id'=>$output['store_info']['store_id']));?>">                                                                         
          <dl>
            <dt>姓名：</dt>
            <dd style="min-height:54px;">
              <input type="text" id="truename" name="truename" class="text tip" value="<?php echo $output['apply_info']['member_truename'];?>" autofocus />
            </dd>
          </dl>
          <dl>
            <dt>Email：</dt>
            <dd style="min-height:54px;">
              <input type="text" id="email" name="email" class="text tip" value="<?php echo $output['apply_info']['member_email'];?>" />
            </dd>
          </dl>
          <dl>
            <dt>手机：</dt>
            <dd style="min-height:54px;">
              <input type="text" id="mobile" name="mobile" class="text tip" value="<?php echo $output['apply_info']['member_mobile'];?>" />
            </dd>
          </dl>
          <dl>
            <dt>QQ：</dt>
            <dd style="min-height:54px;">
              <input type="text" id="qq" name="qq" class="text tip" value="<?php echo $output['apply_info']['member_qq'];?>" />
            </dd>
          </dl>
          <dl>
            <dt>所在地：</dt>
            <dd style="min-height:54px;">
              <input type="text" id="areainfo" name="areainfo" class="text tip" value="<?php echo preg_replace("/\s/","",$output['apply_info']['member_areainfo']);?>" />
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
              <input type="submit" id="Submit" value="提交加盟申请" class="submit" title="提交加盟申请" />
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

<script type="text/javascript">
$(function(){
	jQuery.validator.addMethod("phones", function(value, element) {
		return this.optional(element) || /^[1][3-8]+\d{9}/i.test(value);
	}, "phone number please"); 

    $('#applyadd_form').validate({
    	submitHandler:function(form){
    		ajaxpost('applyadd_form', '', '', 'onerror') 
    	},
        rules : {
            truename : {
                required : true
            },
            email : {
				required : true,
				email    : true
            },
			mobile : {
				required : true,
				phones   : true
            },
			qq : {
				required : true
            },
			areainfo : {
				required : true
            }
        },
        messages : {
			truename : {
                required : '真实姓名不能为空'
            },
            email : {
				required : '电子邮箱不能为空',
				email    : '电子邮箱格式不正确'
            },
			mobile : {
				required : '手机号码不能为空',
				phones   : '手机号码不正确'
            },
			qq : {
				required : 'QQ号码不能为空'
            },
			areainfo : {
				required : '详细地址不能为空'
            }
        }
    });
});
</script>