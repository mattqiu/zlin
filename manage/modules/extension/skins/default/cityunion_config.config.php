<?php defined('InIMall') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>城市联盟管理</h3>
        <h5>设置城市联盟体系的相关数据</h5>
      </div>
      <?php echo $output['top_link'];?></div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>配置城市联盟参数。</li>
    </ul>
  </div>
  <div class="fixed-empty"></div>
  <form id="form_config" name="form_config" enctype="multipart/form-data" method="post" action="<?php echo urlAdmin('cityunion_config', 'config_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="partner_reg_online">合伙人在线申请<?php echo $lang['im_colon'];?></label></td>
          <td class="vatop rowform onoff" align="left">
            <label for="gl_partner_reg1" class="cb-enable <?php if($output['gl_partner_reg'] == 1){ ?>selected<?php } ?>" ><span><?php echo $lang['open'];?></span></label>
            <label for="gl_partner_reg0" class="cb-disable <?php if($output['gl_partner_reg'] == 0){ ?>selected<?php } ?>" ><span><?php echo $lang['close'];?></span></label>
            <input id="gl_partner_reg1" name="gl_partner_reg" <?php if($output['gl_partner_reg'] == 1){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="gl_partner_reg0" name="gl_partner_reg" <?php if($output['gl_partner_reg'] == 0){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </td>
          <td class="vatop tips">选择“是”，则商城会员将可以在前台购买邀请码成为合伙人；选择“否”，则合伙人只能由管理员在后台添加;</td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="gl_partner_level">合伙人切割层数<?php echo $lang['im_colon'];?></label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['gl_partner_level']>0?$output['gl_partner_level']:3;?>" name="gl_partner_level" id="gl_partner_level" class="w60">
          </td>
          <td class="vatop tips">城市联盟切割层数，最小：1级，最大：8级</td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="gl_invite_require">成为合伙人条件<?php echo $lang['im_colon'];?></label></td>
          <td class="vatop rowform" align="left">
           	邀请码金额：<input type="text" class="txt" value="<?php echo $output['gl_invite_money']?$output['gl_invite_money']:0;?>" style="width:60px" name="gl_invite_money">
          	&nbsp;&nbsp;&nbsp;&nbsp;
          	最低购买 <input type="text" class="txt" value="<?php echo $output['gl_invite_min']?$output['gl_invite_min']:0;?>" style="width:30px" name="gl_invite_min">个
          </td>
          <td class="vatop tips">成为合伙人条件是指合伙人邀请商家的二维码需要购买，最低需要购买多少个，为0表示不限制，由管理员后台添加的合伙人。</td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td class="required" align="right"></td>
          <td colspan="15" class="vatop rowform" align="left"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><span><?php echo $lang['im_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(document).ready(function(){
	//按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#form_config").valid()){
            $("#form_config").submit();
        }
    });

	$('#form_config').validate({
        rules : {
            gl_partner_reg  : {
                required : true
            },
            gl_partner_level  : {
                required : true,
                number   : true,
				max      : 8,
				min      : 1
            },
            gl_invite_min  : {
                required : true,
                number   : true,
				min      : 0
            }
        },
        messages : {
            gl_partner_reg  : {
                required : '请选择是否开启城市合伙人在线申请'
            },
			gl_partner_level  : {
                required : '请输入城市合伙人级数',
                number   : '城市合伙人级数必须是数字',
				max      : '城市合伙人级数不能超过8级',
				min      : '城市合伙人级数不能少于1级'
            },
            gl_invite_min  : {
                required : '请输入城市合伙人最低人数',
                number   : '申请条件必须是数字',
				min      : '申请条件不能小于0'
            }
        }
    });
});
</script>