<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="imcr-main">
  <div class="imcr-title">
    <h3>订单支付</h3>
    <h5>订单已生成，请扫码支付，祝您购物愉快。</h5>
  </div>
  <div class="imcr-receipt-info mb30">
    <div class="imcr-finish-a">
      这是您的支付二维码。请用手机扫描二维码完成支付。
    </div>
    <div align="center" id="qrcode"></div>

    <div class="imcr-finish-b">可通过用户中心<a href="<?php echo SHOP_SITE_URL?>/index.php?act=member_order">已买到的商品</a>查看订单状态。</div>
    <div class="imcr-finish-c mb30">
      <a href="<?php echo SHOP_SITE_URL?>" class="imcr-btn-mini imcr-btn-green mr15"><i class="icon-shopping-cart"></i>继续购物</a>
      <a href="<?php echo SHOP_SITE_URL?>/index.php?act=member_order" class="imcr-btn-mini imcr-btn-acidblue"><i class="icon-file-text-alt"></i>查看订单</a>
    </div>
  </div>
</div>

<script src="<?php echo RESOURCE_SITE_URL;?>/js/qrcode.js"></script>
<script>
	if(<?php echo $output['code_url'] != NULL; ?>)
	{
		var url = "<?php echo $output['code_url'];?>";
		//参数1表示图像大小，取值范围1-10；参数2表示质量，取值范围'L','M','Q','H'
		var qr = qrcode(10, 'M');
		qr.addData(url);
		qr.make();
		var wording=document.createElement('p');
		wording.innerHTML = "扫我，扫我";
		var code=document.createElement('DIV');
		code.innerHTML = qr.createImgTag();
		var element=document.getElementById("qrcode");
		element.appendChild(wording);
		element.appendChild(code);
	}
</script>