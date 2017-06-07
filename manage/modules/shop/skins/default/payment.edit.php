<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=payment" title="返回支付方式列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['im_pay_method'];?> - <?php echo $lang['im_set'];?>“<?php echo $output['payment']['payment_name'];?>”</h3>
        <h5><?php echo $lang['im_pay_method_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" name="form1">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="payment_id" value="<?php echo $output['payment']['payment_id'];?>" />
    <div class="imap-form-default">
      <?php if ($output['payment']['payment_code'] == 'chinabank') { ?>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_chinabank_account'];?></dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="chinabank_account,chinabank_key" />
          <input name="chinabank_account" id="chinabank_account" value="<?php echo $output['config_array']['chinabank_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_chinabank_key'];?></dt>
        <dd class="opt">
          <input name="chinabank_key" id="chinabank_key" value="<?php echo $output['config_array']['chinabank_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } elseif ($output['payment']['payment_code'] == 'tenpay') { ?>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_tenpay_account'];?></dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="tenpay_account,tenpay_key" />
          <input name="tenpay_account" id="tenpay_account" value="<?php echo $output['config_array']['tenpay_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_tenpay_key'];?></dt>
        <dd class="opt">
          <input name="tenpay_key" id="tenpay_key" value="<?php echo $output['config_array']['tenpay_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } elseif ($output['payment']['payment_code'] == 'alipay') { ?>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_alipay_account'];?></dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="alipay_service,alipay_account,alipay_key,alipay_partner,alipay_server" />
          <input type="hidden" name="alipay_service" value="create_direct_pay_by_user" />
          <input name="alipay_account" id="alipay_account" value="<?php echo $output['config_array']['alipay_account'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_alipay_key'];?></dt>
        <dd class="opt">
          <input name="alipay_key" id="alipay_key" value="<?php echo $output['config_array']['alipay_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_alipay_partner'];?></dt>
        <dd class="opt">
          <input name="alipay_partner" id="alipay_partner" value="<?php echo $output['config_array']['alipay_partner'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"><a href="https://b.alipay.com/order/pidKey.htm?pid=2088001525694587&product=fastpay" target="_blank">获取PID和Key</a></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">选择接口类型</dt>
        <dd class="opt">
          <select class="class-select" name="alipay_server">
            <option value="0" <?php if ($output['config_array']['alipay_server']==0){?>selected<?php }?>>使用标准双接口</option>
            <option value="1" <?php if ($output['config_array']['alipay_server']==1){?>selected<?php }?>>使用担保交易接口</option>
            <option value="2" <?php if ($output['config_array']['alipay_server']==2){?>selected<?php }?>>使用即时到帐交易接口</option>
          </select>
          <span class="err"></span>
          <p class="notic">请选择您最后一次跟支付宝签订的协议里面说明的接口类型</p>
        </dd>
      </dl>
      <?php } elseif ($output['payment']['payment_code'] == 'wxpay') { ?>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>APP唯一凭证(appid)</label>
        </dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="wxpay_appid,wxpay_appsecret,wxpay_mchid,wxpay_mchkey" />
          <input name="wxpay_appid" id="wxpay_appid" value="<?php echo $output['config_array']['wxpay_appid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>应用密钥(appsecret)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_appsecret" id="wxpay_appsecret" value="<?php echo $output['config_array']['wxpay_appsecret'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>商户号(mchid)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_mchid" id="wxpay_mchid" value="<?php echo $output['config_array']['wxpay_mchid'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>商户密钥(mchkey)</label>
        </dt>
        <dd class="opt">
          <input name="wxpay_mchkey" id="wxpay_mchkey" value="<?php echo $output['config_array']['wxpay_mchkey'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } elseif ($output['payment']['payment_code'] == 'llpay') { ?>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>商户号</label>
        </dt>
        <dd class="opt">
          <input type="hidden" name="config_name" value="llpay_partner,llpay_encrypt,llpay_rsa_key,llpay_md5_key" />
          <input name="llpay_partner" id="llpay_partner" value="<?php echo $output['config_array']['llpay_partner'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">签名方式</dt>
        <dd class="opt">
          <select class="class-select" name="llpay_encrypt">
            <option value="RSA" <?php if ($output['config_array']['llpay_encrypt']=='RSA'){?>selected<?php }?>>RSA加密</option>
            <option value="MD5" <?php if ($output['config_array']['llpay_encrypt']=='MD5'){?>selected<?php }?>>MD5加密</option>
          </select>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>RSA密钥</label>
        </dt>
        <dd class="opt">
          <textarea name="llpay_rsa_key" id="llpay_rsa_key" rows="6" class="tarea" ><?php echo $output['config_array']['llpay_rsa_key'];?></textarea>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>MD5密钥</label>
        </dt>
        <dd class="opt">
          <input name="llpay_md5_key" id="llpay_md5_key" value="<?php echo $output['config_array']['llpay_md5_key'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <?php } ?>
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_index_enable'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="payment_state1" class="cb-enable <?php if($output['payment']['payment_state'] == '1'){ ?>selected<?php } ?>" ><?php echo $lang['im_yes'];?></label>
            <label for="payment_state2" class="cb-disable <?php if($output['payment']['payment_state'] == '0'){ ?>selected<?php } ?>" ><?php echo $lang['im_no'];?></label>
            <input type="radio" <?php if($output['payment']['payment_state'] == '1'){ ?>checked="checked"<?php }?> value="1" name="payment_state" id="payment_state1">
            <input type="radio" <?php if($output['payment']['payment_state'] == '0'){ ?>checked="checked"<?php }?> value="0" name="payment_state" id="payment_state2">
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn" onclick="document.form1.submit()"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#post_form').validate({
		<?php if($output['payment']['payment_code'] == 'chinabank') { ?>
        rules : {
            chinabank_account : {
                required   : true
            },
            chinabank_key : {
                required   : true
            }
        },
        messages : {
            chinabank_account  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_chinabank_account'];?><?php echo $lang['payment_edit_not_null']; ?>'
            },
            chinabank_key  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_chinabank_key'];?><?php echo $lang['payment_edit_not_null']; ?>'
            }
        }
		<?php } elseif ($output['payment']['payment_code'] == 'tenpay') { ?>
        rules : {
            tenpay_account : {
                required   : true
            },
            tenpay_key : {
                required   : true
            }
        },
        messages : {
            tenpay_account  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_tenpay_account'];?><?php echo $lang['payment_edit_not_null']; ?>'
            },
            tenpay_key  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_tenpay_key'];?><?php echo $lang['payment_edit_not_null']; ?>'
            }
        }
			
		<?php } elseif ($output['payment']['payment_code'] == 'alipay') { ?>
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
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_alipay_account'];?><?php echo $lang['payment_edit_not_null']; ?>'
            },
            alipay_key  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_alipay_key'];?><?php echo $lang['payment_edit_not_null']; ?>'
            },
            alipay_partner  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['payment_alipay_partner'];?><?php echo $lang['payment_edit_not_null']; ?>'
            }
        }
		<?php } ?>
    });
});
</script>