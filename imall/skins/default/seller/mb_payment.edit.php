<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="imsc-form-default">
  <form id="post_form" method="post" name="form1" action="<?php echo urlShop('mb_payment', 'payment_save');?>">
    <input type="hidden" name="payment_id" value="<?php echo $output['payment']['payment_id'];?>" />
    <input type="hidden" name="payment_code" value="<?php echo $output['payment']['payment_code'];?>" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['payment']['payment_name'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php if ($output['payment']['payment_code'] == 'alipay') { ?>
        <tr>
          <td colspan="2" class="required"><label class="validation">支付宝账号:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
              <input name="alipay_account" id="alipay_account" value="<?php echo $output['payment']['payment_config']['alipay_account'];?>" class="txt w300" type="text">
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label class="validation">交易安全校验码（key）:</label> </td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <input name="alipay_key" id="alipay_key" value="<?php echo $output['payment']['payment_config']['alipay_key'];?>" class="txt w300" type="text">
            </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
            <td colspan="2" class="required"><label class="validation">合作者身份（partner ID）:</label> </td>
        </tr>
        <tr class="noborder">
            <td class="vatop rowform">
                <input name="alipay_partner" id="alipay_partner" value="<?php echo $output['payment']['payment_config']['alipay_partner'];?>" class="txt w300" type="text">
            </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required">选择接口类型 : </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <select class="class-select" name="alipay_server">
              <option value="0" <?php if ($output['payment']['payment_config']['alipay_server']==0){?>selected<?php }?>>使用标准双接口</option>
              <option value="1" <?php if ($output['payment']['payment_config']['alipay_server']==1){?>selected<?php }?>>使用担保交易接口</option>
              <option value="2" <?php if ($output['payment']['payment_config']['alipay_server']==2){?>selected<?php }?>>使用即时到帐交易接口</option>
            </select>         
          </td>
          <td class="vatop tips">请选择您最后一次跟支付宝签订的协议里面说明的接口类型</td>
        </tr>
        <?php } ?>
        <?php if ($output['payment']['payment_code'] == 'wxpay') { ?>
        <tr>
          <td colspan="2" class="required"><label class="validation">APP唯一凭证(appid):</label> </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input name="wxpay_appid" id="wxpay_appid" value="<?php echo $output['payment']['payment_config']['wxpay_appid'];?>" class="txt w300" type="text"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">应用密钥(appsecret): </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input name="wxpay_appsecret" id="wxpay_appsecret" value="<?php echo $output['payment']['payment_config']['wxpay_appsecret'];?>" class="txt w300" type="text"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">商户号(mchid): </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input name="wxpay_mchid" id="wxpay_mchid" value="<?php echo $output['payment']['payment_config']['wxpay_mchid'];?>" class="txt w300" type="text"></td>
          <td class="vatop tips"></td>
        </tr>  
        <tr>
          <td colspan="2" class="required"><label class="validation">商户密钥(mchkey): </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input name="wxpay_mchkey" id="wxpay_mchkey" value="<?php echo $output['payment']['payment_config']['wxpay_mchkey'];?>" class="txt w300" type="text"></td>
          <td class="vatop tips"></td>
        </tr>        
        <?php } ?>
        <?php if ($output['payment']['payment_code'] == 'llpay') { ?>
        <tr>
          <td colspan="2" class="required"><label class="validation">商户号:</label> </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input name="llpay_partner" id="llpay_partner" value="<?php echo $output['payment']['payment_config']['llpay_partner'];?>" class="txt w300" type="text"></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required">签名方式: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <select class="class-select" name="llpay_encrypt">
              <option value="RSA" <?php if ($output['payment']['payment_config']['llpay_encrypt']=='RSA'){?>selected<?php }?>>RSA加密</option>
              <option value="MD5" <?php if ($output['payment']['payment_config']['llpay_encrypt']=='MD5'){?>selected<?php }?>>MD5加密</option>
            </select>         
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation">RSA密钥: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <textarea name="llpay_rsa_key" id="llpay_rsa_key" rows="6" class="tarea w300" ><?php echo $output['payment']['payment_config']['llpay_rsa_key'];?></textarea>
          </td>
          <td class="vatop tips"></td>
        </tr>  
        <tr>
          <td colspan="2" class="required"><label class="validation">MD5密钥: </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input name="llpay_md5_key" id="llpay_md5_key" value="<?php echo $output['payment']['payment_config']['llpay_md5_key'];?>" class="txt w300" type="text"></td>
          <td class="vatop tips"></td>
        </tr>        
        <?php } ?>

        <tr>
          <td colspan="2" class="required">启用: </td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform onoff">
            <input type="radio" <?php if($output['payment']['payment_state'] == '1'){ ?>checked="checked"<?php }?> value="1" name="payment_state" id="payment_state1">启用
            <input type="radio" <?php if($output['payment']['payment_state'] == '0'){ ?>checked="checked"<?php }?> value="0" name="payment_state" id="payment_state2">关闭
          </td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="btn" id="btn_submit" ><span><?php echo $lang['im_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#post_form').validate({
        errorPlacement: function(error, element){
			error.appendTo(element.parentsUntil('tr').parent().prev().find('td:first'));
        },
		<?php if ($output['payment']['payment_code'] == 'alipay') { ?>
        rules : {
            alipay_account : {
                required   : true
            },
            alipay_key : {
                required   : true
            },
            alipay_partner : {
                required   : true
            }
        },
        messages : {
            alipay_account  : {
                required  : '支付宝账号不能为空'
            },
            alipay_key  : {
                required  : '交易安全校验码不能为空'
            },
            alipay_partner  : {
                required  : '合作者身份不能为空'
            }
        }
		<?php } ?>
		<?php if ($output['payment']['payment_code'] == 'wxpay') { ?>
        rules : {
            wxpay_key : {
                required   : true
            },
            wxpay_partner : {
                required   : true
            }
        },
        messages : {
            wxpay_key  : {
                required  : '交易安全校验码不能为空'
            },
            wxpay_partner  : {
                required  : '合作者身份不能为空'
            }
        }
		<?php } ?>
    });

    $('#btn_submit').on('click', function() {
		ajaxpost('post_form', '', '', 'onerror')
    });
});
</script>
