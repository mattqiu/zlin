<?php defined('InIMall') or exit('Access Invalid!');?>
<div class="page">
	<div class="fixed-bar">
		<div class="item-title">
			<a class="back" href="index.php?act=goods_class&op=goods_class" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      		<div class="subject">
        		<h3><?php echo $lang['goods_class_index_class'];?> - <?php echo $lang['im_edit'];?>“<?php echo $output['class_info']['gc_name'];?>”的分类导航</h3>
        		<h5><?php echo $lang['goods_class_index_class_subhead'];?></h5>
      		</div>
    	</div>
	</div>
	<div class="explanation" id="explanation">
    	<div class="title" id="checkZoom">
    		<i class="fa fa-lightbulb-o"></i>
      		<h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      		<span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span>
      	</div>
    	<ul>
      		<li>设置前台左上侧商品分类导航的相关信息，可以设置分类前图标、分类别名、推荐分类、推荐品牌以及两张广告图片。</li>
      		<li><?php echo L('goods_class_index_help3');?></li>
		</ul>
	</div>
	<form method="post" enctype="multipart/form-data" name="goodsClassForm" id="goods_class_form">
    	<input type="hidden" name="form_submit" value="ok" />
    	<input type="hidden" name="gc_id" value="<?php echo $output['class_info']['gc_id'];?>" />
    	<div class="imap-form-default">
      		<dl class="row">
        		<dt class="tit">
          			<label for="gc_alias_name">分类别名</label>
				</dt>
        		<dd class="opt">
        			<input type="text" name="gc_alias_name" value="<?php echo $output['class_info']['gc_alias_name'];?>" maxlength="20" id="gc_alias_name" class="input-txt" />
          			<span class="err"></span>
					<p class="notic">可选项。设置别名后，别名将会替代原分类名称展示在分类导航菜单列表中。</p>
				</dd>
      		</dl>      		
			<dl class="row">
        		<dt class="tit">
          			<label><em>*</em>推荐分类</label>
        		</dt>
        		<dd class="opt">
        			<div>分类下的三级分类 <a class="imap-btn" imtype="class_hide" href="javascript:;">隐藏未选项</a></div>
        			<div id="class_div" class="scrollbar-box">
			  			<div class="imap-type-spec-list">
			    			<?php if(is_array($output['class_list']) && !empty($output['class_list'])) {?>
			              		<?php foreach ($output['class_list'] as $key => $value) {?>
			              			<dl>
			                			<dt id="class_dt_<?php echo $value['gc_id'];?>"><?php echo $value['gc_name'];?></dt>
			                			<input type="hidden" name="class_id[]" value="" />
			                			<?php if($value['_child']) {?>
			                				<dd>
			                  					<?php foreach($value['_child'] as $k => $v) {?>
			                  						<label for="class_<?php echo $v['gc_id'];?>">
			                    						<input type="checkbox" name="class_id[]" value="<?php echo $v['gc_id']?>" <?php if(in_array($v['gc_id'], explode(',', $output['class_info']['class_ids']))) {?>checked="checked"<?php }?> /> <?php echo $v['gc_name']?>
			                    					</label>
			                 					<?php }?>
			                				</dd>
			                			<?php }?>
			              			</dl>
			              		<?php }?>
							<?php } else {?>
			  					<div>还没有分类，赶快去<a href="javascript:;" onclick="window.parent.openItem('shop|goods_class')">分类管理</a>添加分类吧！</div>
			              	<?php }?>
			 			</div>
					</div>
					<p class="notic">推荐分类将在展开后的二、三级导航列表上方突出显示，建议根据分类名称长度控制选择数量不超过8个以确保展示效果。</p>
	    		</dd>
			</dl>
      		<dl class="row">
        		<dt class="tit">
          			<label><em>*</em>推荐品牌</label>
        		</dt>
        		<dd class="opt">
          			<div id="brandcategory">快捷定位
            			<select class="class-select">
              				<option value="0"><?php echo $lang['im_please_choose'];?></option>
              				<?php if(!empty($output['gc_list'])) {?>
              					<?php foreach($output['gc_list'] as $k => $v) {?>
              						<?php if($v['gc_parent_id'] == 0) {?>
              							<option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
              						<?php }?>
              					<?php }?>
              				<?php }?>
            			</select>
            			分类下对应的品牌 <a class="imap-btn" imtype="brand_hide" href="javascript:;">隐藏未选项</a>
            		</div>
          			<div id="brand_div" class="scrollbar-box">
            			<div class="imap-type-spec-list">
              				<?php if(is_array($output['brand_list']) && !empty($output['brand_list'])) {?>
              					<?php foreach($output['brand_list'] as $key => $value) {?>
              						<dl>
                						<dt id="brand_dt_<?php echo $key;?>"><?php echo $value['name'];?></dt>
                						<input type="hidden" name="brand_id[]" value="" />
                						<?php if($value['brand']) {?>
                							<dd>
                  								<?php foreach($value['brand'] as $k => $v) {?>
                  									<label for="brand_<?php echo $v['brand_id'];?>">
                    									<input type="checkbox" name="brand_id[]" value="<?php echo $v['brand_id']?>" <?php if(in_array($v['brand_id'], explode(',', $output['class_info']['brand_ids']))) {?>checked="checked"<?php }?> id="brand_<?php echo $v['brand_id'];?>" /> <?php echo $v['brand_name']?>
                    								</label>
                  								<?php }?>
                							</dd>
                						<?php }?>
              						</dl>
              					<?php }?>
              				<?php } else {?>
              					<div>还没有品牌，赶快去<a href="javascript:;" onclick="window.parent.openItem('shop|brand')">品牌管理></a>添加品牌吧！</div>
              				<?php }?>
            			</div>
          			</div>
        		</dd>
      		</dl>

      		<dl class="row">
        	  <dt class="tit">
          		<label for="image">分类背景图片</label>
        	  </dt>
        	  <dd class="opt">
          		<div class="input-file-show">
          		  <span class="show">
          			<a class="nyroModal" rel="gal" href="<?php echo $output['class_info']['image']?$output['class_info']['image']:'javascript:;';?>">
                      <i class="fa fa-picture-o" <?php if ($output['class_info']['image']){?>onMouseOver="toolTip('<img src=<?php echo $output['class_info']['image'];?>>')"<?php }?> onMouseOut="toolTip()"></i>
                    </a>
          		  </span>
          		  <span class="type-file-box">
            		<input class="type-file-file" id="image" name="image" type="file" size="30" im_type="change_pic" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            		<input type="text" name="textfield" id="textfield1" class="type-file-text" />
            		<input type="button" name="button" id="button1" value="选择上传..." class="type-file-button" />
            	  </span>
            	</div>
          		<p class="notic">建议用png带透明效果的图片</p>
        	  </dd>
      		</dl>
      		<dl class="row">
        	  <dt class="tit">
          		<label for="pic">广告1图</label>
        	  </dt>
        	  <dd class="opt">
          		<div class="input-file-show">
          		  <span class="show">
          			<a class="nyroModal" rel="gal" href="<?php if($output['class_info']['adv1']) {echo UPLOAD_SITE_URL.'/'.ATTACH_ADV.'/'.$output['class_info']['adv1'];} else {echo 'javascript:;';}?>">
                      <i class="fa fa-picture-o" <?php if($output['class_info']['adv1']) {?>onmouseover="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_ADV.'/'.$output['class_info']['adv1'];?>>');" onmouseout="toolTip();"<?php }?>></i>
                    </a>
          		  </span>
          		  <span class="type-file-box">
            		<input class="type-file-file" id="adv1" name="adv1" type="file" size="30" im_type="change_pic" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            		<input type="text" name="textfield" id="textfield2" class="type-file-text">
            		<input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
            	  </span>
            	</div>
          		<label title="分类导航广告图1-跳转链接" for="adv1_link" class="ml5">
          		  <i class="fa fa-link"></i>
            	  <input type="text" value="<?php echo $output['class_info']['adv1_link'];?>" name="adv1_link" id="adv1_link" class="input-txt ml5">
          		</label>
          		<p class="notic">
          		  广告图片将展示在推荐品牌下方，请使用宽度190像素，高度150像素的jpg/gif/png格式图片作为分类导航广告上传，<br />
            	  如需跳转请在后方添加以http://开头的链接地址。
            	</p>
        	  </dd>
      		</dl>
      		<dl class="row">
        	  <dt class="tit">
          		<label for="pic">广告2图</label>
        	  </dt>
        	  <dd class="opt">
          		<div class="input-file-show">
          		  <span class="show">
          			<a class="nyroModal" rel="gal" href="<?php if($output['class_info']['adv2']) {echo UPLOAD_SITE_URL.'/'.ATTACH_ADV.'/'.$output['class_info']['adv2'];} else {echo 'javascript:;';}?>">
                      <i class="fa fa-picture-o" <?php if($output['class_info']['adv2']) {?>onmouseover="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_ADV.'/'.$output['class_info']['adv2'];?>>');" onmouseout="toolTip();"<?php }?>></i>
                    </a>
          		  </span>
          		  <span class="type-file-box">
            		<input class="type-file-file" id="adv2" name="adv2" type="file" size="30" im_type="change_pic" hidefocus="true" title="点击前方预览图可查看大图，点击按钮选择文件并提交表单后上传生效">
            		<input type="text" name="textfield" id="textfield3" class="type-file-text">
            		<input type="button" name="button" id="button1" value="选择上传..." class="type-file-button">
            	  </span>
            	</div>
          		<label for="adv2_link" title="分类导航广告图2-跳转链接" class="ml5">
          		  <i class="fa fa-link"></i>
            	  <input type="text" value="<?php echo $output['class_info']['adv2_link'];?>" name="adv2_link" id="adv2_link" class="input-txt ml5">
          		</label>
          		<p class="notic">
          		  广告图片将展示在推荐品牌下方，请使用宽度190像素，高度150像素的jpg/gif/png格式图片作为分类导航广告上传，<br />
            	  如需跳转请在后方添加以http://开头的链接地址。
            	</p>
        	  </dd>
      		</dl>
      		<div class="bot">
      			<a href="javascript:;" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a>
      		</div>
    	</div>
	</form>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js" charset="utf-8"></script>
<script src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.nyroModal.js" charset="utf-8"></script>
<script type="text/javascript">
$(function() {
	$('#pic').change(function() {
		$('#textfield1').val($(this).val());
	});
	$('#class_div').perfectScrollbar();
	$('a[imtype="class_hide"]').live('click', function() {
    	checked_hide('class');
	});
	$('a[imtype="class_show"]').live('click', function() {
    	checked_show('class');
	});
	$('#brand_div').perfectScrollbar();
	$('#brandcategory > select').live('change', function() {
        brand_scroll($(this));
    });
    $('a[imtype="brand_hide"]').live('click', function() {
        checked_hide('brand');
    });
    $('a[imtype="brand_show"]').live('click', function() {
        checked_show('brand');
    });
	
	$("#image").change(function(){
		$("#textfield1").val($(this).val());
	});
	
	$('#adv1').change(function() {
		$('#textfield2').val($(this).val());
	});
	$('#adv2').change(function() {
		$('#textfield3').val($(this).val());
	});
	$('.nyroModal').nyroModal();
	$('#submitBtn').click(function() {
	    if($('#goods_class_form').valid()) {
	     	$('#goods_class_form').submit();
		}
	});
});
var brandScroll = 0;
function brand_scroll(o) {
    var id = o.val();
    if(!$('#brand_dt_'+id).is('dt')) {
        return false;
    }
    $('#brand_div').scrollTop(-brandScroll);
    var sp_top = $('#brand_dt_'+id).offset().top;
    var div_top = $('#brand_div').offset().top;
    $('#brand_div').scrollTop(sp_top-div_top);
    brandScroll = sp_top-div_top;
}
function checked_show(str) {
	$('#'+str+'_div').find('dt').show().end().find('label').show();
	$('#'+str+'_div').find('dl').show();
	$('a[imtype="'+str+'_show"]').attr('imtype', str+'_hide').html('隐藏未选项');
	$('#'+str+'_div').perfectScrollbar('destroy').perfectScrollbar();
}
function checked_hide(str) {
	$('#'+str+'_div').find('dt').hide();
	$('#'+str+'_div').find('input[type="checkbox"]').parents('label').hide();
	$('#'+str+'_div').find('input[type="checkbox"]:checked').parents('label').show();
	$('#'+str+'_div').find('dl').each(function() {
    	if($(this).find('input[type="checkbox"]:checked').length == 0) {
        	$(this).hide();
    	}
	});
	$('a[imtype="'+str+'_hide"]').attr('imtype', str+'_show').html('显示未选项');
	$('#'+str+'_div').perfectScrollbar('destroy').perfectScrollbar();
}
gcategoryInit('brandcategory');
</script> 