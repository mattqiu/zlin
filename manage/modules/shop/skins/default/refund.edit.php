<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['refund_manage'];?> - 处理退款“退单编号：<?php echo $output['refund']['refund_sn']; ?>”</h3>
        <h5><?php echo $lang['refund_manage_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" action="index.php?act=refund&op=edit&refund_id=<?php echo $output['refund']['refund_id']; ?>">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default">
      <div class="title">
        <h3>买家退款申请</h3>
      </div>
      <dl class="row">
        <dt class="tit">申请时间</dt>
        <dd class="opt"><?php echo date('Y-m-d H:i:s',$output['refund']['add_time']); ?> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">商品名称</dt>
        <dd class="opt"><a href="<?php echo urlShop('goods','index',array('goods_id'=> $output['refund']['goods_id']));?>" target="_blank"><?php echo $output['refund']['goods_name']; ?></a></dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['refund_buyer_message'];?></dt>
        <dd class="opt"><?php echo $output['refund']['reason_info']; ?> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">退款说明</dt>
        <dd class="opt"><?php echo $output['refund']['buyer_message']; ?> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">凭证上传</dt>
        <dd class="opt">
          <?php if (is_array($output['pic_list']) && !empty($output['pic_list'])) { ?>
          <?php foreach ($output['pic_list'] as $key => $val) { ?>
          <?php if(!empty($val)){ ?>
          <a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/'.$val;?>" class="nyroModal" rel="gal"> <img height="64" class="show_image" src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/'.$val;?>"></a>
          <?php } ?>
          <?php } ?>
          <?php } ?>
        </dd>
      </dl>
      <div class="title">
        <h3>商家退款处理</h3>
      </div>
      <dl class="row">
        <dt class="tit">审核结果</dt>
        <dd class="opt"><?php echo $output['state_array'][$output['refund']['seller_state']];?> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">处理备注</dt>
        <dd class="opt"><?php echo $output['refund']['seller_message']; ?> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">处理时间</dt>
        <dd class="opt"><?php echo date('Y-m-d H:i:s',$output['refund']['seller_time']); ?> </dd>
      </dl>
      <div class="title">
        <h3>平台退款审核</h3>
      </div>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['refund_message'];?></label>
        </dt>
        <dd class="opt">
          <textarea id="admin_message" name="admin_message" class="tarea"></textarea>
          <span class="err"></span> </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js"></script>

<script type="text/javascript">
$(function(){
    $('.nyroModal').nyroModal();
	$("#submitBtn").click(function(){
        if($("#post_form").valid()){
            $("#post_form").submit();
		}
	});
    $('#post_form').validate({
		errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            admin_message : {
                required   : true
            }
        },
        messages : {
            admin_message  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['refund_message_null'];?>'
            }
        }
    });
});
</script>