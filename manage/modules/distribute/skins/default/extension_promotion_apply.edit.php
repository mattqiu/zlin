<?php defined('InIMall') or exit('Access Invalid!');?>

  <form id="apply_form" name="apply_form" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('extension_promotion', 'apply_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="id" value="<?php echo $output['apply_info']['ai_id'];?>" />
    <input type="hidden" id="verify" name="verify" value="" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="required tr">申 请 人：</td>
          <td class="vatop rowform"><?php echo $output['apply_info']['truename'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="mc_id">代理类型：</label></td>
          <td class="vatop rowform">
            <select name="mc_id" class="w80">
              <?php if (!empty($output['mc_list']) && is_array($output['mc_list'])) {?>
              <?php foreach($output['mc_list'] as $v) {?>
              <option value="<?php echo $v[0];?>"><?php echo $v[1];?></option>
              <?php }?>
              <?php }?>            
            </select>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="ai_replyinfo">审核意见：</td>
          <td>
            <textarea class="textarea" cols="50" name="ai_replyinfo" rows="20">这老板很懒，什么都不肯留下!</textarea>
          </td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td></td>
          <td colspan="15" >
            <a href="javascript:void(0)" onclick="verify(2)" class="btn m10"><span><i class="fa fa-thumbs-up"></i>审核通过</span></a>
            <a href="javascript:void(0)" onclick="verify(1)" class="btn m10"><span><i class="fa fa-thumbs-down"></i>拒绝申请</span></a>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
<script language="javascript">
    function verify(price){
		$("#verify").val(price);
		ajaxpost('apply_form', '', '', 'onerror');
    }
</script>