<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=web_special&op=special_class" title="返回专题分类列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['im_cms_special_manage'];?> -  编辑专题分类</h3>
        <h5>商城专题页面设置与模板编辑</h5>
      </div>
    </div>
  </div>
  <form id="add_form" method="post" action="index.php?act=web_special&op=web_special_class_save">
    <input name="class_id" type="hidden" value="<?php if(!empty($output['class_info']['class_id'])) echo $output['class_info']['class_id'];?>" />
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit"><label class="validation" for="class_name"><?php echo $lang['class_name'];?></label></dt>
        <dd class="opt">
          <input type="text" value="<?php echo $output['class_info']['class_name'];?>" name="class_name" id="class_name" class="txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['class_name_error'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label for="class_sort" class="validation"><?php echo $lang['im_sort'];?></label></dt>
        <dd class="opt">
          <input id="class_sort" name="class_sort" type="text" class="txt" value="<?php echo $output['class_info']['class_sort']?$output['class_info']['class_sort']:255;?>" />
          <span class="err"></span>
          <p class="notic"><?php echo $lang['class_sort_explain'];?></p>
        </dd>
      </dl>
      <div class="bot">
        <a id="submit" href="javascript:void(0)" class="imap-btn-big imap-btn-blue"><?php echo $lang['im_submit'];?></a>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $("#submit").click(function(){
        $("#add_form").submit();
    });

    $('#add_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },
        rules : {
            class_name: {
                required : true,
                maxlength : 10
            },
            class_sort: {
                required : true,
                digits: true,
                max: 255,
                min: 0
            }
        },
        messages : {
            class_name: {
                required : "<?php echo $lang['class_name_required'];?>",
                maxlength : jQuery.validator.format("<?php echo $lang['class_name_maxlength'];?>")
            },
            class_sort: {
                required : "<?php echo $lang['class_sort_required'];?>",
                digits: "<?php echo $lang['class_sort_digits'];?>",
                max : jQuery.validator.format("<?php echo $lang['class_sort_max'];?>"),
                min : jQuery.validator.format("<?php echo $lang['class_sort_min'];?>")
            }
        }
    });
});
</script>
