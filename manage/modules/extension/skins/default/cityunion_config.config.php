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
  <form id="form_config" name="form_config" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('extension_config', 'config_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="promotion_reg_online">推广员在线申请<?php echo $lang['im_colon'];?></label></td>
          <td class="vatop rowform onoff" align="left">
            <label for="gl_promotion_reg1" class="cb-enable <?php if($output['gl_promotion_reg'] == 1){ ?>selected<?php } ?>" ><span><?php echo $lang['open'];?></span></label>
            <label for="gl_promotion_reg0" class="cb-disable <?php if($output['gl_promotion_reg'] == 0){ ?>selected<?php } ?>" ><span><?php echo $lang['close'];?></span></label>
            <input id="gl_promotion_reg1" name="gl_promotion_reg" <?php if($output['gl_promotion_reg'] == 1){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="gl_promotion_reg0" name="gl_promotion_reg" <?php if($output['gl_promotion_reg'] == 0){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </td>
          <td class="vatop tips">选择“是”，则商城会员将可以在前台申请成为推广员；选择“否”，则推广员只能由管理员在后台添加;</td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="gl_promotion_level">推广员切割层数<?php echo $lang['im_colon'];?></label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['gl_promotion_level']>0?$output['gl_promotion_level']:3;?>" name="gl_promotion_level" id="gl_promotion_level" class="w60">
          </td>
          <td class="vatop tips">推广员切割层数，最小：1级，最大：8级</td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="gl_promotion_require">推广员申请条件<?php echo $lang['im_colon'];?></label></td>
          <td class="vatop rowform" align="left">
            <input type="text" value="<?php echo $output['gl_promotion_require']?$output['gl_promotion_require']:0;?>" name="gl_promotion_require" id="gl_promotion_require" class="txt w200">
          </td>
          <td class="vatop tips">推广员申请条件是指会员必须达到此最低消费额才能申请成为推广员，为0表示不限制，管理员后台添加的推广员不受此限制。</td>
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
            gl_promotion_reg  : {
                required : true
            },
            gl_promotion_level  : {
                required : true,
                number   : true,
				max      : 8,
				min      : 1
            },
			gl_promotion_require  : {
                required : true,
                number   : true,
				min      : 0
            }
        },
        messages : {
            gl_promotion_reg  : {
                required : '请选择是否开启推广员在线申请'
            },
			gl_promotion_level  : {
                required : '请输入推广员级数',
                number   : '推广员级数必须是数字',
				max      : '推广员级数不能超过8级',
				min      : '推广员级数不能少于1级'
            },
			gl_promotion_require  : {
                required : '请输入推广员申请条件',
                number   : '申请条件必须是数字',
				min      : '申请条件不能小于0'
            }
        }
    });
});
</script>