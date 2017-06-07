<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <form method="post" action="<?php echo urlShop('store_goods_online', 'edit_goodsClass');?>" id="goodsclass_form">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="commonid" value="<?php echo $_GET['commonid']; ?>" />
    <input type="hidden" name="gtype" value="<?php echo $_GET['gtype']; ?>" />
    <dl>
      <dt>商品分类：</dt>
      <dd>
        <p>
      	<span class="new_add"><a href="javascript:void(0)" id="add_sgcategory" class="imsc-btn"><?php echo $lang['store_goods_index_new_class'];?></a> </span>
          <select name="sgcate_id[]" class="sgcategory">
            <option value="0"><?php echo $lang['im_please_choose'];?></option>
            <?php if (!empty($output['store_goods_class'])){?>
            <?php foreach ($output['store_goods_class'] as $val) { ?>
            <option value="<?php echo $val['stc_id']; ?>"><?php echo $val['stc_name']; ?></option>
            <?php if (is_array($val['child']) && count($val['child'])>0){?>
            <?php foreach ($val['child'] as $child_val){?>
            <option value="<?php echo $child_val['stc_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
            <?php }?>
            <?php }?>
            <?php } ?>
            <?php } ?>
          </select>
        </p>
        
        <p class="hint">如不填，所有已选分类将制空，请谨慎操作</p>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['im_submit'];?>"/></label>
    </div>
  </form>
</div>
<script>
$(function(){
    $('#goodsclass_form').submit(function(){
        ajaxpost('goodsclass_form', '', '', 'onerror');
        return false;
    });
 	// 添加店铺分类
    $("#add_sgcategory").unbind().click(function(){
        $(".sgcategory:last").after($(".sgcategory:last").clone(true).val(0));
    });
    // 选择店铺分类
    $('.sgcategory').unbind().change( function(){
        var _val = $(this).val();       // 记录选择的值
        $(this).val('0');               // 已选择值清零
        // 验证是否已经选择
        if (!checkSGC(_val)) {
            alert('该分类已经选择,请选择其他分类');
            return false;
        }
        $(this).val(_val);              // 重新赋值
    });
 	// 验证店铺分类是否重复
    function checkSGC($val) {
        var _return = true;
        $('.sgcategory').each(function(){
            if ($val !=0 && $val == $(this).val()) {
                _return = false;
            }
        });
        return _return;
    } 
});
</script>