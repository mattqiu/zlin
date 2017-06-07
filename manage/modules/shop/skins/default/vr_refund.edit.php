<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="javascript:history.back(-1)" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>虚拟订单退款 - 处理退款“退单编号：<?php echo $output['refund']['refund_sn']; ?>”</h3>
        <h5>虚拟类商品订单退款申请及审核处理</h5>
      </div>
    </div>
  </div>
  <form id="post_form" method="post" action="index.php?act=vr_refund&op=edit&refund_id=<?php echo $output['refund']['refund_id']; ?>">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default">
      <div class="title">
        <h3>买家退货申请</h3>
      </div>
      <dl class="row">
        <dt class="tit">申请时间</dt>
        <dd class="opt"><?php echo date('Y-m-d H:i:s',$output['refund']['add_time']); ?> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">商品名称</dt>
        <dd class="opt"><a href="<?php echo urlShop('goods','index',array('goods_id'=> $output['refund']['goods_id']));?>" target="_blank"><?php echo $output['refund']['goods_name']; ?></a> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">兑换码</dt>
        <dd class="opt">
          <?php if (is_array($output['code_array']) && !empty($output['code_array'])) { ?>
          <?php foreach ($output['code_array'] as $key => $val) { ?>
          <?php echo $val;?><br />
          <?php } ?>
          <?php } ?>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['refund_order_refund'];?></dt>
        <dd class="opt"><?php echo imPriceFormat($output['refund']['refund_amount']); ?> </dd>
      </dl>
      <dl class="row">
        <dt class="tit">退款说明</dt>
        <dd class="opt"><?php echo $output['refund']['buyer_message']; ?> </dd>
      </dl>
      <div class="title">
        <h3>平台退款审核</h3>
      </div>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em>是否同意</label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="state1" class="cb-enable" title="<?php echo $lang['im_yes'];?>"><?php echo $lang['im_yes'];?></label>
            <label for="state0" class="cb-disable" title="<?php echo $lang['im_no'];?>"><?php echo $lang['im_no'];?></label>
            <input id="state1" name="admin_state"  value="2" type="radio">
            <input id="state0" name="admin_state" value="3" type="radio">
          </div>
          <span class="err"></span>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['refund_message'];?></label>
        </dt>
        <dd class="opt">
          <textarea id="admin_message" name="admin_message" class="tarea"></textarea>
          <span class="err"></span> </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a> </div>
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
			var error_td = element.parentsUntil('dl').children('span.err');
            error_td.append(error);
        },
        rules : {
            admin_state : {
                required   : true
            },
            admin_message : {
                required   : true
            }
        },
        messages : {
            admin_state : {
                required : '<i class="fa fa-exclamation-circle"></i>请选择是否同意退款'
            },
            admin_message  : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['refund_message_null'];?>'
            }
        }
    });
});
</script>