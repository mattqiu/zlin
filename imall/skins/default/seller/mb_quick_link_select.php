<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <dl>
    <dt>链接类型：</dt>
    <dd>
      <label for="btn_quick_link_good">
        <input id="btn_quick_link_good" type="radio" class="radio" value="good" name="btn_quick_link_type" checked="checked" imtype="btn_quick_link_type">&nbsp&nbsp商品&nbsp&nbsp&nbsp&nbsp
      </label>
      <label for="btn_quick_link_class">
        <input id="btn_quick_link_class" type="radio" class="radio" value="class" name="btn_quick_link_type" imtype="btn_quick_link_type">&nbsp&nbsp分类&nbsp&nbsp&nbsp&nbsp
      </label>
      <label for="btn_quick_link_special">
        <input id="btn_quick_link_special" type="radio" class="radio" value="special" name="btn_quick_link_type" imtype="btn_quick_link_type">&nbsp&nbsp专辑&nbsp&nbsp&nbsp&nbsp
      </label>
    </dd>
  </dl>
  
  <div id="div_quick_link_good">
    <dl>
      <dt>商品编号：</dt>
      <dd>
        <input id="quick_link_good_text" class="text w200" type="text">
        <p class="hint">请输入商品编号</p>
      </dd>
    </dl>
  </div>
  
  <div id="div_quick_link_class" style="display:none;">
    <dl>
      <dt>商品分类：</dt>
      <dd>
        <select name="quick_link_class_text" id="quick_link_class_text" class="w150">
          <option value="0" selected="selected">请选择分类</option>
          <?php if(is_array($output['store_goods_class']) && !empty($output['store_goods_class'])){?>
          <?php foreach ($output['store_goods_class'] as $val) {?>
          <option value="<?php echo $val['stc_id']; ?>"><?php echo $val['stc_name']; ?></option>
          <?php if (is_array($val['child']) && count($val['child'])>0){?>
          <?php foreach ($val['child'] as $child_val){?>
          <option value="<?php echo $child_val['stc_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child_val['stc_name']; ?></option>
          <?php }?>
          <?php }?>
          <?php }?>
          <?php }?>
        </select>
      </dd>
    </dl>
  </div>
  
  <div id="div_quick_link_special" style="display:none;">
    <dl>
      <dt>专辑名称：</dt>
      <dd>
        <select name="quick_link_special_text" id="quick_link_special_text" class="w150">
          <option value="0" selected="selected">请选择专辑</option>
          <?php if(!empty($output['special_list']) && is_array($output['special_list'])){ ?>
          <?php   foreach($output['special_list'] as $k => $val){ ?>
          <option value="<?php echo $val['special_id']?>"><?php echo $val['special_desc'];?></option>
          <?php }?>
          <?php }?>
        </select>
      </dd>
    </dl>
  </div>
        
  <div class="bottom">
    <label class="submit-border"><a id="btn_quick_link_save" class="submit" href="javascript:void(0);">确定</a></label>
    <label class="submit-border"><a id="btn_quick_link_cancle" class="submit" href="javascript:void(0);">取消</a></label>
  </div>
</div>
<script type="text/javascript">
var WAP_SITE_URL = '<?php echo WAP_SITE_URL;?>';
var inputControl = '<?php echo $_GET['inputctrl'];?>';
$(function(){
	$('[imtype="btn_quick_link_type"]').change(function() { 
        var selectedvalue = $('[imtype="btn_quick_link_type"]:checked').val();
		if (selectedvalue=='good'){
			$('#div_quick_link_class').hide();
			$('#div_quick_link_special').hide();
			
			$('#div_quick_link_good').show();
		}else if(selectedvalue=='class'){
			$('#div_quick_link_good').hide();
			$('#div_quick_link_special').hide();
			
			$('#div_quick_link_class').show();
		}else if(selectedvalue=='special'){
			$('#div_quick_link_good').hide();
			$('#div_quick_link_class').hide();
			
			$('#div_quick_link_special').show();
		}
    });
	
	//保存
    $('#btn_quick_link_save').on('click', function() {
        var selectedvalue = $('[imtype="btn_quick_link_type"]:checked').val();
		var url_value = '';
		var url_str = '';
		if (selectedvalue=='good'){
			url_value = $('#quick_link_good_text').val();
			if(url_value != '') {
                url_str = WAP_SITE_URL+'/tmpl/product_detail.html?goods_id='+url_value;				
            } else {
                showError('请输入商品编号');
				return false;
            }
		}else if(selectedvalue=='class'){
			url_value = $('#quick_link_class_text').val();
			if(url_value >0 ) {
                url_str = WAP_SITE_URL+'/tmpl/product_list.html?store_id=<?php echo $_SESSION['store_id'];?>&stc_id='+url_value;				            
            } else {
                showError('请选择商品类别');
				return false;
            }
		}else if(selectedvalue=='special'){
			url_value = $('#quick_link_special_text').val();
			if(url_value >0 ) {
                url_str = WAP_SITE_URL+'/special.html?store_id=<?php echo $_SESSION['store_id'];?>&special_id='+url_value;                
            } else {
                showError('请选择专辑');
				return false;
            }
		}
		$('#'+inputControl).val(url_str);
		DialogManager.close('my_quick_link_select');
    });
	
	//取消
    $('#btn_quick_link_cancle').on('click', function() {
        DialogManager.close('my_quick_link_select');
    });	

});
</script> 