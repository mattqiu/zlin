<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>

<div class="item-publish">
  <form id="post_form" method="post" name="post_form" action="index.php?act=seller_payment&op=edit">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="payment_id" value="<?php echo $output['payment']['payment_id'];?>" />
    <div class="main-content" id="mainContent">
      <table border="0" cellpadding="0" cellspacing="0" class="store-joinin">
        <thead>
          <tr>
            <th colspan="20"><?php echo $output['payment']['payment_name'];?></th>
          </tr>
        </thead>
        <tbody>
          <?php if ($output['payment']['payment_code'] == 'chinabank') { ?>
          <tr>
            <th>网银在线商户号: </th>
            <td>
              <input type="hidden" name="config_name" value="chinabank_account,chinabank_key" />
              <input name="chinabank_account" id="chinabank_account" value="<?php echo $output['config_array']['chinabank_account'];?>" class="txt" type="text">
            </td>
          </tr>
          <tr>
            <th>网银在线密钥: </th>
            <td><input name="chinabank_key" id="chinabank_key" value="<?php echo $output['config_array']['chinabank_key'];?>" class="txt" type="text"></td>
          </tr>
          <?php } elseif ($output['payment']['payment_code'] == 'tenpay') { ?>
          <tr>
            <th>财付通商户号: </th>
            <td>
              <input type="hidden" name="config_name" value="tenpay_account,tenpay_key" />
              <input name="tenpay_account" id="tenpay_account" value="<?php echo $output['config_array']['tenpay_account'];?>" class="txt" type="text">  
            </td>
          </tr>
          <tr>
            <th>财付通密钥:  </th>
            <td><input name="tenpay_key" id="tenpay_key" value="<?php echo $output['config_array']['tenpay_key'];?>" class="txt" type="text"></td>
          </tr>
          <?php } elseif ($output['payment']['payment_code'] == 'alipay') { ?>
          <tr>
            <th>支付宝账号:  </th>
            <td>
              <input type="hidden" name="config_name" value="alipay_service,alipay_account,alipay_key,alipay_partner,alipay_server" />
              <input type="hidden" name="alipay_service" value="create_direct_pay_by_user" />
              <input name="alipay_account" id="alipay_account" value="<?php echo $output['config_array']['alipay_account'];?>" class="txt w300" type="text">  
            </td>
          </tr>
          <tr>
            <th>交易安全校验码（key）: </th>
            <td><input name="alipay_key" id="alipay_key" value="<?php echo $output['config_array']['alipay_key'];?>" class="txt w300" type="text"></td>
          </tr>
          <tr>
            <th>合作者身份（partner ID）:  </th>
            <td>
              <input name="alipay_partner" id="alipay_partner" value="<?php echo $output['config_array']['alipay_partner'];?>" class="txt" type="text">
              <span></span>
              <p class="hint"><a href="https://b.alipay.com/order/pidKey.htm?pid=2088001525694587&product=fastpay" target="_blank">get my key and partner ID</a></p>
            </td>
          </tr>
          <tr>
            <th>选择接口类型 : </th>
            <td>
              <select class="class-select" name="alipay_server">
                <option value="0" <?php if ($output['config_array']['alipay_server']==0){?>selected<?php }?>>使用标准双接口</option>
                <option value="1" <?php if ($output['config_array']['alipay_server']==1){?>selected<?php }?>>使用担保交易接口</option>
                <option value="2" <?php if ($output['config_array']['alipay_server']==2){?>selected<?php }?>>使用即时到帐交易接口</option>
              </select>
              <p class="hint">请选择您最后一次跟支付宝签订的协议里面说明的接口类型</p>   
            </td>            
          </tr>
          <?php } elseif ($output['payment']['payment_code'] == 'wxpay') { ?>
          <tr>
            <th>APP唯一凭证(appid): </th>
            <td>
              <input type="hidden" name="config_name" value="wxpay_appid,wxpay_appsecret,wxpay_mchid,wxpay_mchkey" />
              <input name="wxpay_appid" id="wxpay_appid" value="<?php echo $output['config_array']['wxpay_appid'];?>" class="txt w300" type="text">  
            </td>
          </tr>
          <tr>
            <th>应用密钥(appsecret): </th>
            <td>
              <input name="wxpay_appsecret" id="wxpay_appsecret" value="<?php echo $output['config_array']['wxpay_appsecret'];?>" class="txt w300" type="text">  
            </td>
          </tr>
          <tr>
            <th>商户号(mchid): </th>
            <td>
              <input name="wxpay_mchid" id="wxpay_mchid" value="<?php echo $output['config_array']['wxpay_mchid'];?>" class="txt w300" type="text">  
            </td>
          </tr>
          <tr>
            <th>商户密钥(mchkey): </th>
            <td>
              <input name="wxpay_mchkey" id="wxpay_mchkey" value="<?php echo $output['config_array']['wxpay_mchkey'];?>" class="txt w300" type="text">  
            </td>
          </tr>
          <?php } elseif ($output['payment']['payment_code'] == 'llpay') { ?>
          <tr>
            <th>商户号: </th>
            <td>
              <input type="hidden" name="config_name" value="llpay_partner,llpay_encrypt,llpay_rsa_key,llpay_md5_key" />
              <input name="llpay_partner" id="llpay_partner" value="<?php echo $output['config_array']['llpay_partner'];?>" class="txt w300" type="text">  
            </td>
          </tr>
          <tr>
            <th>签名方式 : </th>
            <td>
              <select class="class-select" name="llpay_encrypt">
                <option value="RSA" <?php if ($output['config_array']['llpay_encrypt']=='RSA'){?>selected<?php }?>>RSA加密</option>
                <option value="MD5" <?php if ($output['config_array']['llpay_encrypt']=='MD5'){?>selected<?php }?>>MD5加密</option>
              </select>
              <p class="hint"></p>   
            </td>            
          </tr>
          <tr>
            <th>RSA密钥: </th>
            <td>
              <textarea name="llpay_rsa_key" id="llpay_rsa_key" rows="6" class="tarea" ><?php echo $output['config_array']['llpay_rsa_key'];?></textarea>
            </td>
          </tr>
          <tr>
            <th>MD5密钥: </th>
            <td>
              <input name="llpay_md5_key" id="llpay_md5_key" value="<?php echo $output['config_array']['llpay_md5_key'];?>" class="txt w300" type="text">  
            </td>
          </tr>
          <?php }?>
          <tr>
            <th>启用: </th>
            <td>
              <input type="radio" <?php if($output['payment']['payment_state'] == '1'){ ?>checked="checked"<?php }?> value="1" name="payment_state" id="payment_state1">是
              <input type="radio" <?php if($output['payment']['payment_state'] == '0'){ ?>checked="checked"<?php }?> value="0" name="payment_state" id="payment_state2">否  
            </td>
          </tr>
        </tbody>
      </table>
      <label class="submit-border">
        <input type="submit" class="submit" value="提交" />
      </label>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#post_form').validate({
		submitHandler:function(form){
    		ajaxpost('post_form', '', '', 'onerror')
    	},
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
                required  : '网银在线商户号不能为空'
            },
            chinabank_key  : {
                required   : '网银在线密钥不能为空'
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
                required  : '财付通商户号不能为空'
            },
            tenpay_key  : {
                required   : '财付通密钥不能为空'
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
                required  : '支付宝账号不能为空'
            },
            alipay_key  : {
                required   : '交易安全校验码（key）不能为空'
            },
            alipay_partner  : {
                required   : '合作者身份（partner ID）不能为空'
            }
        }
		<?php } ?>
    });
});
</script>