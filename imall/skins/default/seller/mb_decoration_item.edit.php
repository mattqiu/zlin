<?php defined('InIMall') or exit('Access Invalid!');?>
<style type="text/css">
.dialog_content {
	overflow: hidden;
	padding: 0 15px 15px !important;
}
h4.dialog-handle-title { font-size: 12px !important; font-weight: 700; line-height:26px; color: #555; display: block; height: 26px; padding: 6px 0; margin:0 !important;}
.dialog-handle-box { border-bottom: dotted 1px #CBE9F3; padding-bottom:8px;}
.dialog-handle-box .left {width: 45%; float:left; }
.dialog-handle-box .right {width: 45%; float:left; color: #999;}

.s-tips { color: #333; background: #FEFAE7; background: none repeat scroll 0 0 #FEFEDA; border: 1px solid #FFE8C2 !important; padding:4px; margin-bottom: 6px;}
.s-tips i { background: url(../images/sky/bg_position.gif) no-repeat scroll -270px -630px; vertical-align: middle; display:inline-block; width: 16px; height: 16px;}
.margintop{ margin-top:10px; }
.dialog-image-desc { color: #999;}
</style>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="imsc-form-default">
  <form id="form_item" action="<?php echo urlShop('mb_decoration', 'special_item_save');?>" method="post">
    <input type="hidden" name="special_id" value="<?php echo $output['item_info']['special_id'];?>">
    <input type="hidden" name="item_id" value="<?php echo $output['item_info']['item_id'];?>">
    <table class="table tb-type2 nohover">
      <tbody>
        <?php $item_data = $output['item_info']['item_data'];?>
        <?php $item_edit_flag = true;?>
        <tr class="noborder">
          <td style="height: auto; padding: 0;">
            <div id="item_edit_content" class="mb-item-edit-content">
              <?php require('mb_special_item.module_' . $output['item_info']['item_type'] . '.php');?>
            </div></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2"><a id="btn_save" class="btn" href="javascript:;"><span>保存编辑</span></a>
            <?php if($output['item_info']['special_id'] > 0) { ?>
            <a id="btn_back" href="<?php echo urlShop('mb_decoration', 'index_edit', array('special_id' => $output['item_info']['special_id']));?>" class="btn"><span>返回上一级</span></a>
            <?php } else { ?>
            <a id="btn_back" href="<?php echo urlShop('mb_decoration', 'index_edit');?>" class="btn"><span>返回上一级</span></a>
            <?php } ?></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<div id="dialog_item_edit_image" style="display:none;">
  <div class="s-tips margintop"><i></i>请按提示尺寸制作上传图片，已达到手机客户端及Wap手机商城最佳显示效果。</div>
  <div class="upload-thumb"> <img id="dialog_item_image" src="" alt=""></div>
  <input id="dialog_item_image_name" type="hidden">
  <input id="dialog_type" type="hidden">
  <form id="form_image" action="">
    <div class="dialog-handle-box clearfix">
      <h4 class="dialog-handle-title">选择要上传的图片：</h4>
      <span>
      <input id="btn_upload_image" type="file" name="special_image">
      </span> <span id="dialog_image_desc" class="dialog-image-desc"></span>
      <h4 class="dialog-handle-title">操作类型：</h4>
      <div>
        <select id="dialog_item_image_type" name="" class="vatop">
        <option value="">-请选择-</option>
          <option value="keyword">关键字</option>
          <option value="special">专题编号</option>
          <option value="goods">商品编号</option>
          <option value="class">分类编号</option>
          <option value="promotion">促销活动</option>
          <option value="url">链接</option>
        </select>
        <input id="dialog_item_image_data" type="text" class="txt w200 marginright marginbot vatop"><br />
        <span id="dialog_item_image_desc" class="dialog-image-desc"></span>
      </div>
    </div>
    <div>
      <a id="btn_save_item" class="btn" href="javascript:;"><span>保存</span></a>
    </div>
  </form>
</div>
<script id="item_image_template" type="text/html">
    <div imtype="item_image" class="item">
        <img imtype="image" src="<%=image%>" alt="">
        <input imtype="image_name" name="item_data[item][<%=image_name%>][image]" type="hidden" value="<%=image_name%>">
        <input imtype="image_type" name="item_data[item][<%=image_name%>][type]" type="hidden" value="<%=image_type%>">
        <input imtype="image_data" name="item_data[item][<%=image_name%>][data]" type="hidden" value="<%=image_data%>">
        <a imtype="btn_del_item_image" href="javascript:;">删除</a>
    </div>
</script> 

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/template.min.js" charset="utf-8"></script> 
<script type="text/javascript">
    var url_upload_image = '<?php echo urlShop('mb_decoration', 'special_image_upload');?>';

    $(document).ready(function(){
        var $current_content = null;
        var $current_image = null;
        var $current_image_name = null;
        var $current_image_type = null;
        var $current_image_data = null;
        var old_image = '';
        var $dialog_item_image = $('#dialog_item_image');
        var $dialog_item_image_name = $('#dialog_item_image_name');
        var special_id = <?php echo $output['item_info']['special_id'];?>;

        //保存
        $('#btn_save').on('click', function() {
        	var html = $('#content').val();
        	if(html!=''){
        		$('#custom_content').val(html);
            }
			ajaxpost('form_item', '', '', 'onerror')
        });

        //编辑图片
        $('[imtype="btn_edit_item_image"]').on('click', function() {
            //初始化当前图片对象
            $item_image = $(this).parents('[imtype="item_image"]');
            $current_image = $item_image.find('[imtype="image"]');
            $current_image_name = $item_image.find('[imtype="image_name"]');
            $current_image_type = $item_image.find('[imtype="image_type"]');
            $current_image_data = $item_image.find('[imtype="image_data"]');

            $('#dialog_item_image').attr('src', $current_image.attr('src'));
            $('#dialog_item_image_name').val($current_image_name.val());
            $('#dialog_item_image_type').val($current_image_type.val());
            $('#dialog_item_image_data').val($current_image_data.val());
            $('#dialog_image_desc').text('推荐图片尺寸' + $(this).attr('data-desc'));
            $('#dialog_type').val('edit');
            change_image_type_desc($('#dialog_item_image_type').val());
            $('#dialog_item_edit_image').im_show_dialog({
                width: 600,
                title: '编辑'
            });
        });

        //添加图片
        $('[imtype="btn_add_item_image"]').on('click', function() {
            $dialog_item_image.hide();
            $dialog_item_image_name.val('');
            $current_content = $(this).parent().find('[imtype="item_content"]');
            $('#dialog_image_desc').text('推荐图片尺寸' + $(this).attr('data-desc'));
            $('#dialog_type').val('add');
            change_image_type_desc($('#dialog_item_image_type').val());
            $('#dialog_item_edit_image').im_show_dialog({
                width: 600,
                title: '添加'
            });
        });

        //删除图片
        $('#item_edit_content').on('click', '[imtype="btn_del_item_image"]', function() {
            $(this).parents('[imtype="item_image"]').remove();
        });

        //图片上传
        $("#btn_upload_image").fileupload({
            dataType: 'json',
            url: url_upload_image,
            formData: {special_id: special_id},
            add: function(e, data) {
                old_image = $dialog_item_image.attr('src');
                $dialog_item_image.attr('src', LOADING_IMAGE);
                data.submit();
            },
            done: function (e, data) {
                var result = data.result;
                if(typeof result.error === 'undefined') {
                    $dialog_item_image.attr('src', result.image_url);
                    $dialog_item_image.show();
                    $dialog_item_image_name.val(result.image_name);
                } else {
                    $dialog_item_image.attr('src') = old_image;
                    showError(result.error);
                }
            }
        });

        $('#btn_save_item').on('click', function() {
            var type = $('#dialog_type').val();
            if(type == 'edit') {
                edit_item_image_save();
            } else {
                if($dialog_item_image_name.val() == '') {
                    showError('请上传图片');
                    return false;
                }
                add_item_image_save();
            }
            $('#dialog_item_edit_image').hide();
        });

        function edit_item_image_save() {
            $current_image.attr('src', $('#dialog_item_image').attr('src'));
            $current_image_name.val($('#dialog_item_image_name').val());
            $current_image_type.val($('#dialog_item_image_type').val());
			if ($('#dialog_item_image_type').val() == 'promotion'){			
                $current_image_data.val(<?php echo $_SESSION['store_id'];?>);
			}else{
				$current_image_data.val($('#dialog_item_image_data').val());
			}
        }

        function add_item_image_save() {
            var $html_item_image = $('#html_item_image');
            var item = {};
            item.image = $('#dialog_item_image').attr('src');
            item.image_name = $('#dialog_item_image_name').val();
            item.image_type = $('#dialog_item_image_type').val();
			if ($('#dialog_item_image_type').val() == 'promotion'){			
                item.image_data = <?php echo $_SESSION['store_id'];?>;
			}else{
				item.image_data = $('#dialog_item_image_data').val();
			}
            $current_content.append(template.render('item_image_template', item));
        }


        $('#dialog_item_image_type').on('change', function() {
            change_image_type_desc($(this).val());
        });

        function change_image_type_desc(type) {
            var desc_array = {};
            var desc = '操作类型一共五种，对应点击以后的操作。';
            if(type != '') {
                desc_array['keyword'] = '关键字类型会根据搜索关键字跳转到商品搜索页面，输入框填写搜索关键字。';
                desc_array['special'] = '专题编号会跳转到指定的专题，输入框填写专题编号。';
                desc_array['goods'] = '商品编号会跳转到指定的商品详细页面，输入框填写商品编号。';
				desc_array['class'] = '分类编号会跳转到指定的分类页面，输入框填写商品分类编号。';
				desc_array['promotion'] = '活动编号会跳转到指定的活动页面，输入框填写活动编号。不填活动编号则会跳转到代金券及活动列表页';
                desc_array['url'] = '链接会跳转到指定链接，输入框填写完整的URL。';
                desc = desc_array[type];
            }
            $('#dialog_item_image_desc').text(desc);
        }
    });
    </script> 
