<?php defined('InIMall') or exit('Access Invalid!');?>

<!-- 公司信息 -->
<div id="apply_company_info" class="apply-company-info">
  <div class="alert">
    <h4>注意事项：</h4>
    以下所需要上传的电子版资质文件仅支持JPG\GIF\PNG格式图片，大小请控制在1M之内。
  </div>
  <form id="form_company_info" action="index.php?act=store_joinin&op=step2_p" method="post" enctype="multipart/form-data" >
    <table border="0" cellpadding="0" cellspacing="0" class="all">
      <tbody>
        <tr>
          <th><i>*</i>真实姓名：</th>
          <td colspan="2"><input name="contacts_name" type="text" class="w200" /><span></span></td>
        </tr>
        <tr>
          <th><i>*</i>所在地址：</th>
          <td colspan="2"><input id="company_address" name="company_address" type="hidden" value=""/><span></span></td>
        </tr>
        <tr>
          <th><i>*</i>详细地址：</th>
          <td colspan="2"><input name="company_address_detail" type="text" class="w200"><span></span></td>
        </tr>
        <tr>
          <th><i>*</i>身份证号码：</th>
          <td colspan="2"><input name="store_owner_card" type="text" class="w200" /><span></span></td>
        </tr>
        <tr>
          <th><i>*</i>手持身份证照片：</th>
          <td>
            <input name="owner_card_electronic" type="file" class="w200" />
            <span class="block"></span>
            <IMG class="J_UploaderImg default" src="<?php echo SHOP_SKINS_URL;?>/images/store_joinin/id-hand.png">
         </td>
          <td>
            <img src="<?php echo SHOP_SKINS_URL;?>/images/store_joinin/id-hand-s.jpg">
            <br />
			<span class="ex-detail" data-title="手持身份证照片-照片要求" data-imgRight="<?php echo SHOP_SKINS_URL;?>/images/store_joinin/id-hand-right.jpg" data-imgError="<?php echo SHOP_SKINS_URL;?>/images/store_joinin/id-hand-error.jpg" data-tips=" <li>1.免冠、建议未化妆；五官可见；</li>  <li>2.身份证全部信息需清晰无遮挡，否则认证将无法通过；</li>  <li>3.完整露出手臂；</li>  <li>4.请勿进行任何软件处理；</li>  <li>5.支持jpg/jpeg/bmp格式，最大不超过1M。</li>">查看详细要求</span>
          </td>
        </tr>
        <tr>
          <th><i>*</i>身份证正面：</th>
          <td>
            <input name="owner_card_front_pic" type="file" class="w200" />
            <span class="block"></span>
            <IMG class="J_UploaderImg default" src="<?php echo SHOP_SKINS_URL;?>/images/store_joinin/id-front.png">
          </td>
          <td>
            <img src="<?php echo SHOP_SKINS_URL;?>/images/store_joinin/id-front-s.jpg">
            <br />
			<span class="ex-detail" data-title="身份证正面-照片要求" data-imgRight="<?php echo SHOP_SKINS_URL;?>/images/store_joinin/id-front-l.jpg" data-imgError="" data-tips=" <li>1.身份证上的信息不能被遮挡，且清晰可见；</li>  <li>2.照片请勿进行任何软件处理；</li>  <li>3.照片支持jpg/jpeg/bmp格式，最大不超过1M。</li>">查看详细要求</span>
          </td>
        </tr>
        <tr>
          <th><i>*</i>身份证背面：</th>
          <td>
            <input name="owner_card_back_pic" type="file" class="w200" />
            <span class="block"></span>
            <IMG class="J_UploaderImg default" src="<?php echo SHOP_SKINS_URL;?>/images/store_joinin/id-back.png">
          </td>
          <td>
            <img src="<?php echo SHOP_SKINS_URL;?>/images/store_joinin/id-back-s.jpg">
            <br />
			<span class="ex-detail" data-title="身份证背面-照片要求" data-imgRight="<?php echo SHOP_SKINS_URL;?>/images/store_joinin/id-back-l.jpg" data-imgError="" data-tips=" <li>1.身份证有效期需要在1个月以上；</li>  <li>2.身份证上的信息不能被遮挡，且清晰可见；</li>  <li>3.照片请勿进行任何软件处理；</li>  <li>4.照片支持jpg/jpeg/bmp格式，最大不超过1M。</li>">查看详细要求</span>
          </td>
        </tr>
        <tr>
          <th><i>*</i>联系电话：</th>
          <td colspan="2"><input name="contacts_phone" type="text" class="w200" /><span></span></td>
        </tr>
        <tr>
          <th><i>*</i>电子邮箱：</th>
          <td colspan="2"><input name="contacts_email" type="text" class="w200" /><span></span></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20">&nbsp;</td>
        </tr>
      </tfoot>
    </table>    
  </form>
  <div class="bottom"><a id="btn_apply_company_next" href="javascript:;" class="btn">下一步，提交财务资质信息</a></div>
</div>
<script type="text/javascript">
$(document).ready(function(){

    $('#company_address').im_region();

    $('#form_company_info').validate({
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
        rules : {
			contacts_name: {
                required: true,
                maxlength: 20 
            },
			company_address: {
                required: true,
                maxlength: 50 
            },
			company_address_detail: {
                required: true,
                maxlength: 50 
            },
			store_owner_card: {
                required: true,
                maxlength: 18
            },
			owner_card_electronic: {
                required: true
            },
			owner_card_front_pic: {
                required: true
            },
			owner_card_back_pic: {
                required: true
            },
            contacts_phone: {
                required: true,
                maxlength: 20 
            },
            contacts_email: {
                required: true,
                email: true 
            }
        },
        messages : {
			contacts_name: {
                required: '请输入真实姓名',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
			company_address: {
                required: '请选择所在区域',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
            company_address_detail: {
                required: '请输入详细地址',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
			store_owner_card: {
                required: '请输入身份证号码',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
			owner_card_electronic: {
                required: '请选择上传手持身份证照片'
            },
			owner_card_front_pic: {
                required: '请选择上传身份证正面照片'
            },
			owner_card_back_pic: {
                required: '请选择上传身份证背面照片'
            },
			contacts_phone: {
                required: '请输入联系电话',
                maxlength: jQuery.validator.format("最多{0}个字")
            },
			contacts_email: {
                required: '请输入常用邮箱地址',
                email: '请填写正确的邮箱地址'
            }
        }
    });

    $('#btn_apply_company_next').on('click', function() {
        if($('#form_company_info').valid()) {
            $('#form_company_info').submit();
        }
    });	
});

$(function() {
	$('.ex-detail').bind("click",
	function() {
		//if(DialogManager.show('ShowContentBox_dialog')) return;
		
		var d = DialogManager.create('ShowContentBox_dialog');
	    var Width = 720;
		
		var b=$(this);
		var title=b.attr("data-title");
		var dr=b.attr("data-imgRight");
		var de=b.attr("data-imgError")||"";
		var dt=b.attr("data-tips")||"";
		
		var h=de?'<div class="ex-detail"><div class="img-area"><img src='+de+' /><img class="img-last" src='+dr+' /></div><ul class="tips-content">'+dt+"</ul></div>":'<div class="ex-detail"><div class="img-area"><img src='+dr+' /></div><ul class="tips-content">'+dt+"</ul></div>";
		
        d.setTitle(title);
	    d.setWidth(Width);
		
        d.setContents('<div class="ks-contentbox">'+h+'</div>');	
	    d.show('center',1);
	});
});		
</script>