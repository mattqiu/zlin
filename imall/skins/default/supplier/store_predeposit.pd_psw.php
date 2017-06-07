<?php defined('InIMall') or exit('Access Invalid!');?>

<script type="text/javascript">
$(document).ready(function(){
    $('#add_time_from').datepicker();
    $('#add_time_to').datepicker();
});
</script>
<div class="tabmenu">
    <ul class="tab pngFix">
    <li class="normal"><a href="index.php?act=supplier_predeposit&amp;op=pd_log">账户日志</a></li>
    <li class="normal"><a href="index.php?act=supplier_predeposit&amp;op=index">申请提现</a></li>
  	<li class="normal"><a href="index.php?act=supplier_predeposit&amp;op=pd_cash_list">提现列表</a></li>
  	<li class="active"><a href="javascript:void(0);">提现密码设置</a></li>
  	</ul>
</div>
<form method="post" id="cash_form" action="index.php?act=supplier_predeposit&amp;op=pd_pwd_edit">
      <input type="hidden" name="form_submit" value="ok">
            <dl>
        <dt><i class="required red">*</i>新密码：</dt>
        <dd>
        	<input name="pdc_pwd" type="password" class="text w200" id="pdc_amount" maxlength="10">
        	<p class="hint mt5"></p>
        </dd>
      </dl>
       <dl>
        <dt><i class="required red">*</i>确认密码：</dt>
        <dd>
        	<input name="pdc_pwd_again" type="password" class="text w200" id="pdc_amount" maxlength="10">
        	<p class="hint mt5"></p>
        </dd>
      </dl>
      <dl class="bottom"><dt>&nbsp;</dt>
          <dd><label class="submit-border"><input type="submit" class="submit" value="设置"></label><a class="ncbtn ml10" href="javascript:history.go(-1);">取消并返回</a></dd>
      </dl>
    </form>
