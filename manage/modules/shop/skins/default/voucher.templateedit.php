<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <a class="back" href="<?php echo urlAdminShop('voucher', 'templatelist'); ?>" title="返回列表">
        <i class="fa fa-arrow-circle-o-left"></i>
      </a>
      <div class="subject">
        <h3>店铺代金券 - 编辑代金券</h3>
        <h5>查看/编辑商家发布的代金券</h5>
      </div>
    </div>
  </div>

  <form id="add_form" method="post" action="index.php?act=voucher&op=templateedit">
    <input type="hidden" id="form_submit" name="form_submit" value="ok"/>
    <input type="hidden" id="tid" name="tid" value="<?php echo $output['t_info']['voucher_t_id'];?>"/>
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_voucher_storename'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="readonly txt" value="<?php echo $output['t_info']['voucher_t_storename'];?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>所属店铺分类</label>
        </dt>
        <dd class="opt">
          <input type="text" class="readonly txt" value="<?php echo $output['store_class'][$output['t_info']['voucher_t_sc_id']]['sc_name'];?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_voucher_template_title'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="readonly txt" value="<?php echo $output['t_info']['voucher_t_title'];?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_voucher_template_enddate'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="readonly txt" value="<?php echo @date('Y-m-d',$output['t_info']['voucher_t_end_date']);?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_voucher_template_price'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="readonly txt" value="<?php echo $output['t_info']['voucher_t_price'];?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_voucher_template_total'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="readonly txt" value="<?php echo $output['t_info']['voucher_t_total'];?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_voucher_template_eachlimit'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="readonly txt" value="<?php echo $output['t_info']['voucher_t_eachlimit'];?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_voucher_template_orderpricelimit'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="readonly txt" value="<?php echo $output['t_info']['voucher_t_limit'];?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_voucher_template_describe'];?></label>
        </dt>
        <dd class="opt">
          <textarea rows="6" readonly="readonly" class="readonly tarea"><?php echo $output['t_info']['voucher_t_desc'];?></textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_voucher_template_image'];?></label>
        </dt>
        <dd class="opt">
          <?php if ($output['t_info']['voucher_t_customimg']){?>
          <img onload="javascript:DrawImage(this,160,160);" src="<?php echo $output['t_info']['voucher_t_customimg'];?>"/>
          <?php }?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_voucher_template_giveoutnum'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="readonly txt" value="<?php echo $output['t_info']['voucher_t_giveout'];?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_voucher_template_usednum'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="readonly txt" value="<?php echo $output['t_info']['voucher_t_used'];?>" readonly>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['admin_voucher_template_points'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" class="input-txt" id="points" name="points" value="<?php echo $output['t_info']['voucher_t_points'];?>">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['im_status'];?></label>
        </dt>
        <dd class="opt">
          <?php foreach ($output['templatestate_arr'] as $k=>$v){?>
          <input type="radio" value="<?php echo $v[0];?>" id="tstate_<?php echo $v[0];?>" name="tstate" <?php echo $v[0] == $output['t_info']['voucher_t_state']?'checked="checked"':'';?>>
          <?php echo $v[1];?>
          <?php }?>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label>是否推荐</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label title="<?php echo $lang['im_yes'];?>" class="cb-enable <?php if($output['t_info']['voucher_t_recommend'] == '1'){ ?>selected<?php } ?>" for="recommend1"><?php echo $lang['im_yes'];?></label>
            <label title="<?php echo $lang['im_no'];?>" class="cb-disable <?php if($output['t_info']['voucher_t_recommend'] == '0'){ ?>selected<?php } ?>" for="recommend0"><?php echo $lang['im_no'];?></label>
            <input type="radio" value="1" <?php if($output['t_info']['voucher_t_recommend'] == '1'){ ?>checked="checked"<?php } ?> name="recommend" id="recommend1">
            <input type="radio" value="0" <?php if($output['t_info']['voucher_t_recommend'] == '0'){ ?>checked="checked"<?php } ?> name="recommend" id="recommend0">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
		$("#add_form").submit();
	});
	//页面输入内容验证
	$("#add_form").validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
	    },
	    rules : {
	    	points: {
	    		required : true,
	            digits : true
	        }
	    },
	    messages : {
	    	points: {
	    		required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_voucher_template_points_error'];?>',
		    	digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['admin_voucher_template_points_error'];?>'
	        }
	    }
	});

});
</script>