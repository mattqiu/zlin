<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="eject_con">
  <form id="apply_form" method="post" target="_parent" action="<?php echo urlShop('seller_saleman', 'apply_save');?>">
    <input type="hidden" name="id" value="<?php echo $output['apply_info']['ai_id'];?>" />
    <input type="hidden" id="verify" name="verify" value="" />
    <dl>
      <dt>申 请 人：</dt>
      <dd><?php echo $output['apply_info']['truename'];?></dd>
    </dl>
    <dl>
      <dt>审核意见：</dt>
      <dd>
        <textarea class="textarea" cols="50" name="ai_replyinfo" rows="10">这老板很懒，什么都不肯留下!</textarea>
      </dd>
    </dl>    
    <div class="bottom">
      <a href="javascript:void(0)" onclick="verify(2)" class="imsc-btn imsc-btn-acidblue m10"><i class="fa fa-thumbs-up"></i>审核通过</a>
      <a href="javascript:void(0)" onclick="verify(1)" class="imsc-btn imsc-btn-acidblue m10"><i class="fa fa-thumbs-down"></i>拒绝申请</a>
    </div>
  </form>
</div>
<script language="javascript">
    function verify(price){
		$("#verify").val(price);
		ajaxpost('apply_form', '', '', 'onerror');
    }
</script>