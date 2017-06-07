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
  	<li class="active"><a href="javascript:void(0);">申请提现</a></li>
  	<li class="normal"><a href="index.php?act=supplier_predeposit&amp;op=pd_cash_list">提现列表</a></li>
  	<li class="normal"><a href="index.php?act=supplier_predeposit&amp;op=pd_psw">提现密码设置</a></li>
  	</ul>
</div>
<form method="post" id="cash_form" action="index.php?act=supplier_predeposit&amp;op=pd_cash_add">
      <input type="hidden" name="form_submit" value="ok">
      <dl>
        <dt><i class="required red">*</i>提现金额：</dt>
        <dd><input name="pdc_amount" type="text" class="text w100" id="pdc_amount" maxlength="10" value="0"><em class="add-on">
<i class="icon-renminbi"></i></em> （当前可用金额：<strong class="orange">0</strong>&nbsp;&nbsp;元）<span></span>
          <p class="hint mt5"></p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required red">*</i>收款银行：</dt>
        <dd><input name="pdc_bank_name" type="text" class="text w200" id="pdc_bank_name" maxlength="40" value="支行名称"><span></span>
          <p class="hint">强烈建议优先填写国有4大银行(中国银行、中国建设银行、中国工商银行和中国农业银行)
请填写详细的开户银行分行名称，虚拟账户如支付宝、财付通填写“支付宝”、“财付通”即可。</p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required red">*</i>收款账号：</dt>
        <dd><input name="pdc_bank_no" type="text" class="text w200" id="pdc_bank_no" maxlength="30" value="22222222"><span></span>
          <p class="hint">银行账号或虚拟账号(支付宝、财付通等账号)</p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required red">*</i>开户人姓名：</dt>
        <dd><input name="pdc_bank_user" type="text" class="text w100" id="pdc_bank_user" maxlength="10" value="开户名"><span></span>
        <p class="hint">收款账号的开户人姓名</p>
          </dd>
      </dl>
      <dl>
        <dt><i class="required red">*</i>支付密码：</dt>
        <dd><input name="password" type="password" class="text w100" id="password" maxlength="20"><span></span>
        <p class="hint">
                            <strong class="red">还未设置提现密码</strong><a href="index.php?act=supplier_predeposit&amp;op=pd_psw" class="ncbtn-mini ncbtn-aqua vm ml10" target="_blank">马上设置</a>
                      </p>
          </dd>
      </dl>
      <dl class="bottom"><dt>&nbsp;</dt>
          <dd><label class="submit-border"><input type="submit" class="submit" value="确认提现"></label><a class="ncbtn ml10" href="javascript:history.go(-1);">取消并返回</a></dd>
      </dl>
    </form>
