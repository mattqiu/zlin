$(document).ready(function() {
    var decoration = {};
    
    //当前block
    decoration.current_block_id = null;
    //当前对话框
    decoration.current_dialog = null;
    //当前编辑按钮
    decoration.current_block_edit_button = null;
    //编辑器
    decoration.editor = null;
    //幻灯图片数限制
    decoration.slide_image_limit = 5;
    //导航菜单默认样式
    decoration.default_nav_style = '.imcs-nav { background-color: #D93600; height: 38px; overflow: hidden; width: 1198px; }';
    decoration.default_nav_style += '.imcs-nav ul { white-space: nowrap; display: block; width: 1199px; height: 38px; margin-left: -1px; overflow: hidden;}';
    decoration.default_nav_style += '.imcs-nav li a span { font-size: 14px; font-weight: 600; line-height: 20px; text-overflow: ellipsis; white-space: nowrap; max-width:160px; color: #FFF; float: left; height: 20px; padding: 9px 15px; margin-left: 4px; overflow:hidden; cursor:pointer;}';
    //图片热点图片对象
    decoration.$hot_area_image = null;
    //图片热点序号
    decoration.hot_area_index = 1;

    //封装post提交
    decoration.ajax_post = function(url, post, done, always) {
        $.ajax({
            type: "POST",
            url: url,
            data: post, 
            dataType: "json"
        })
        .done(function(data) {
            if(typeof data.error == 'undefined') {
                done(data);
            } else {
                showError(data.error);
            }
        })
        .fail(function() {
            showError('操作失败');
        })
        .always(always);
    }

    //显示模块
    decoration.show_dialog_module = function(module_type, content, full_width) {
		if (module_type =='goods'){
		    decoration['show_goods_dialog_module'](module_type, content, full_width);
		}else{
            if(typeof full_width == 'undefined') {
                full_width = false;
            }
            var $dialog = $('#dialog_module_' + module_type);
            if($dialog.length > 0) {
                decoration.current_dialog = $dialog;
                $('#dialog_select_module').hide();
                var function_name = 'show_dialog_module_' + module_type;
                decoration[function_name]($dialog, content, full_width);
            } else {
                showError('模块不存在');
            }
		}
    }
	
	//显示品牌对话框
    decoration.show_dialog_module_brand = function($dialog, content) {
        var html = '';	
        $(content).find('[class="hd"]').each(function() {
			var i =1;
			$(this).find('li').each(function() {
				var data = {};
		        data.row_caption = $(this).attr("data-row-caption");
                data.row_sec_caption = $(this).attr("data-row-sec-caption");
		        data.row_count = i;
		        data.row_show = i==1?true:false;
		
		        html += template.render('template_module_brand_row_list', data);
				i++;
			});			
		});
		$('#brand_hd ul').html(html);

        html = '';
		$(content).find('[class="bd"]').each(function() {
		  i =1;
		  $(this).find('ul').each(function() {			
			html += '<ul id="brand_group_'+i+'">';
			$(this).find('li').each(function() {
				var data = {};
		        data.brand_name = $(this).attr("data-brand-name");
                data.brand_img = $(this).attr("data-brand-img");
				data.brand_url = $(this).attr("data-brand-url");
		
		        html += template.render('template_module_brand_item_list', data);				
			});	
			html += '</ul>';
			i++;
		  });	
		});
		$('#brand_bd').html(html);

		$("#brand_bd ul").each(function() {
		    $(this).hide();
		});
		$('#brand_group_1').show();

        $dialog.im_show_dialog({width: 1020, title: '品牌模块'});
    };

	
	//显示商品模块 add by yangbaiyan
    decoration.show_goods_dialog_module = function(module_type, content, full_width) {
        if(typeof full_width == 'undefined') {
            full_width = false;
        }
        var $dialog = $('#dialog_module_goods_' + module_type);
        if($dialog.length > 0) {
            decoration.current_dialog = $dialog;
            $('#dialog_select_module').hide();
            var function_name = 'show_dialog_module_goods_' + module_type;
            decoration[function_name]($dialog, content, full_width);
        } else {
            showError('模块不存在');
        }
    }

    //显示自定义模块对话框
    decoration.show_dialog_module_html = function($dialog, content) {
        $dialog.im_show_dialog({width: 1020, title: '自定义模块'});
        if(!decoration.editor) {
            decoration.editor = KindEditor.create('#module_html_editor', {
                items : ['source', '|', 'fullscreen', 'undo', 'redo', 'cut', 'copy', 'paste', '|','fontname', 'fontsize', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline','removeformat', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist','insertunorderedlist', '|', 'image','flash', 'media',  'link', '|', 'about'],
                allowImageUpload: false,
                allowFlashUpload: false,
                allowMediaUpload: false,
                allowFileManager: false,
                filterMode: false,
            });
        }
        decoration.editor.html(content);
    };

    //显示幻灯模块对话框
    decoration.show_dialog_module_slide = function($dialog, content, full_width) {
        $dialog.im_show_dialog({width: 1020, title: '图片和幻灯'});
        var html = '';
        $(content).find('li').each(function() { 
            var data = {};
            data.image_url = $(this).attr('data-image-url');
            data.image_name = $(this).attr('data-image-name');
            data.image_link = $(this).attr('data-image-link');
            html += template.render('template_module_slide_image_list', data);
        });
        $('#txt_slide_full_width').attr('checked', full_width);
        $('#module_slide_html ul').html(html);
        $('#div_module_slide_upload').hide();
        $('#btn_add_slide_image').show();
    }

    //显示图片热点模块对话框
    decoration.show_dialog_module_hot_area = function($dialog, content) {
        decoration.hot_area_index = 1;

        //图片
        $('#div_module_hot_area_image').html($(content).find('img'));
        decoration.$hot_area_image = $('#div_module_hot_area_image').find('img');
        decoration.$hot_area_image.imgAreaSelect({ 
            handles: true,
            zIndex: 1200,
            fadeSpeed: 200 
        });

        $('#module_hot_area_url').val('');

        var html = '';
        $('#module_hot_area_select_list').html('');
        $(content).find('area').each(function() { 
            var position = $(this).attr('coords');
            var link = $(this).attr('href');
            decoration.add_hot_area(position, link);
        });

        $dialog.im_show_dialog({
            width: 1020,
            title: '图片热点模块',
            close_callback: function() {
                decoration.hot_area_cancel_selection();
            }
        });
    }
	
	//显示店铺商品标题模块对话框
    decoration.show_dialog_module_goods_title = function($dialog, content, full_width) {
        $dialog.im_show_dialog({width: 640, title: '商品模块标题'});
		var title_type = $(content).find('[imtype="title_item"]').attr('data-title-type');
		var title_url = $(content).find('[imtype="title_item"]').attr('data-title-url');
		var title_text = $(content).find('[imtype="title_item"]').attr('data-title-text');
		var title_img = $(content).find('[imtype="title_item"]').attr('data-title-img');		
        
		if (title_type=='img'){
			$("#module_goods_title_type_img").attr("checked","checked");
			$("#div_module_goods_title_text").hide();
			$("#div_module_goods_title_img").show();
		}else{
			$("#module_goods_title_type_text").attr("checked","checked");
			$("#div_module_goods_title_img").hide();
			$("#div_module_goods_title_text").show();
		}
		$("#module_goods_title_text").val(title_text);
		if (title_img !=''){
		    $('#div_module_goods_title_img_pre').html('<img src="' + title_url + '" data-image-name="' + title_img + '">');
        } else {
            $('#div_module_goods_title_img_pre').html('');
		}
		
		var html = '';
        $(content).find('[imtype="adv_item"]').each(function() { 
            var data = {};            
            data.adv_name = $(this).attr('data-adv-name');
			data.adv_url = $(this).attr('data-adv-url');			
            html += template.render('template_module_goods_adv_list', data);
        });
		$('#module_goods_title_adv_list ul').html(html);
						
    }

    //显示店铺商品模块对话框
    decoration.show_dialog_module_goods_goods = function($dialog, content) {
        var html = '';	
        $(content).find('[imtype="goods_item"]').each(function() {
            $(this).append('<a class="imsc-btn-mini" imtype="btn_module_goods_operate" href="javascript:;"><i class="fa fa-ban"></i>取消选择</a>');
            html += $('<div />').append($(this)).html();
        });
        $('#div_module_goods_list').html(html);
        $dialog.im_show_dialog({width: 1020, title: '店铺商品模块'});
    };
	//显示店铺商品幻灯模块对话框
    decoration.show_dialog_module_goods_slide = function($dialog, content, full_width) {
        $dialog.im_show_dialog({width: 1020, title: '商品幻灯'});
        var html = '';
        $(content).find('[imtype="slide_item"]').each(function() { 
            var data = {};
            data.image_url = $(this).attr('data-image-url');
            data.image_name = $(this).attr('data-image-name');
            data.image_link = $(this).attr('data-image-link');
            html += template.render('template_module_goods_slide_image_list', data);
        });
        $('#txt_slide_full_width').attr('checked', full_width);
        $('#module_goods_slide_html ul').html(html);
        $('#div_module_goods_slide_upload').hide();
        $('#btn_add_goods_slide_image').show();
    }
	
	//显示店铺商品品牌对话框
    decoration.show_dialog_module_goods_brand = function($dialog, content) {
        var html = '';	
        $(content).find('[imtype="brand_item"]').each(function() {
            $(this).append('<a class="imsc-btn-mini" imtype="btn_module_brand_operate" href="javascript:;"><i class="fa fa-ban"></i>取消选择</a>');
            html += $('<div />').append($(this)).html();
        });
        $('#div_module_brand_list').html(html);
        $dialog.im_show_dialog({width: 1020, title: '店铺品牌模块'});
    };

    //块排序
    decoration.sort_decoration_block = function() {
        var sort_string = '';
        $block_list = $('#store_decoration_area').children();
        $block_list.each(function(index, block) {
            sort_string += $(block).attr('data-block-id') + ',';
        });
        $.post(URL_DECORATION_BLOCK_SORT, {sort_string: sort_string}, function(data) {
            if(typeof data.error != 'undefined') {
                showError(data.error);
            }
        }, 'json');
    };

    //保存块内容
    decoration.save_decoration_block = function(html, module_type, full_width) {
        //是否100%宽度设置
        if(typeof full_width == 'undefined') {
            full_width = 0;
        } else {
            full_width = 1;
        }

        var post = { 
            block_id: decoration.current_block_id,
            module_type: module_type,
            full_width: full_width,
            content: html
        };

        decoration.ajax_post(
            URL_DECORATION_BLOCK_SAVE,
            post,
            function(data) {
                decoration.current_block_edit_button.attr('data-module-type', module_type);
                var $block = $('#block_' + decoration.current_block_id);
                if(full_width) {
                    $block.addClass('store-decoration-block-full-width');
                } else {
                    $block.removeClass('store-decoration-block-full-width');
                }
                if(module_type == 'html') {
                    data.html = data.html.replace(/\\"/g, '"');
                }
                $block.find('[imtype="store_decoration_block_module"]').html(data.html);
                decoration.current_dialog.hide();
            }
        );
    };
	
	//保存商品块内容 add by yangbaiyan
    decoration.save_decoration_block_goods = function(html, module_type, full_width) {
        //是否100%宽度设置
        if(typeof full_width == 'undefined') {
            full_width = 0;
        } else {
            full_width = 1;
        }

        var post = { 
            block_id: decoration.current_block_id,
            module_type: module_type,
            full_width: full_width,
            content: html
        };
        decoration.ajax_post(
            URL_DECORATION_BLOCK_SAVE_GOODS,
            post,
            function(data) {
                decoration.current_block_edit_button.attr('data-module-type', module_type);
                var $block = $('#block_' + decoration.current_block_id);
                if(full_width) {
                    $block.addClass('store-decoration-block-full-width');
                } else {
                    $block.removeClass('store-decoration-block-full-width');
                }
                $block.find('[imtype="store_decoration_block_module"]').html(data.html);
                decoration.current_dialog.hide();
            }
        );
    };

    decoration.apply_nav_style = function(nav_display, nav_style) {
        if(nav_display == 'true') {
            $('#decoration_nav').show();
        } else {
            $('#decoration_nav').hide();
        }

        $('#style_nav').remove();

        if(nav_style == '') {
            nav_style = decoration.default_nav_style;
            $('#decoration_nav_style').val(decoration.default_nav_style);
        }

        $('head').append('<style id="style_nav">' + nav_style + '</style>');
    };

    decoration.apply_banner = function(banner_display, banner_image_url) {
        var $decoration_banner = $('#decoration_banner');
        if(banner_display == 'true' && banner_image_url != '') {
            $decoration_banner.show();
        } else {
            $decoration_banner.hide();
        }
        $decoration_banner.html('<img src="' + banner_image_url + '" alt="">');
    };

    //添加热点块
    decoration.add_hot_area = function(position, link) {
        var data = {};
        data.link = link;
        data.position = position; 
        data.index = decoration.hot_area_index;
        var html = template.render('template_module_hot_area_list', data);
        $('#module_hot_area_select_list').append(html);

        var position_array = position.split(',');
        var display = {};
        display.width = position_array[2] - position_array[0];
        display.height = position_array[3] - position_array[1];
        display.left = position_array[0];
        display.top = position_array[1];
        display.index = decoration.hot_area_index;
        var display_html = template.render('template_module_hot_area_display', display);
        $('#div_module_hot_area_image').append(display_html);

        decoration.hot_area_index = decoration.hot_area_index + 1;
    };

    //取消热点块选区
    decoration.hot_area_cancel_selection = function() {
        var ias = decoration.$hot_area_image.imgAreaSelect({ instance: true });
        if(typeof ias != 'undefined') {
            ias.cancelSelection();
        }
    };

    //初始化banner
    decoration.apply_banner(
        $("input[name='decoration_banner_display']:checked").val(),
        $('#img_banner_image').attr('src')
    );

    //初始化导航样式
    decoration.apply_nav_style(
        $("input[name='decoration_nav_display']:checked").val(),
        $('#decoration_nav_style').val()
    );

    //编辑背景
    $('#btn_edit_background').on('click', function() {
        $('#dialog_edit_background').im_show_dialog({width: 640, title: '编辑背景'});
    });

    //上传背景图
    $('#file_background_image').fileupload({
        dataType: 'json',
        url: URL_DECORATION_ALBUM_UPLOAD, 
        add: function (e, data) {
            $('#img_background_image').attr('src', LOADING_IMAGE);
            $('#img_background_image').addClass('loading');
            $('#div_background_image').show();
            data.submit();
        },
        done: function (e, data) {
            var result = data.result;
            $('#img_background_image').removeClass('loading');
            if(typeof result.error == 'undefined') {
                $('#img_background_image').attr('src', result.image_url);
                $('#txt_background_image').val(result.image_name);
                $('#div_background_image').show();
            } else {
                $('#div_background_image').hide();
                showError(result.error);
            }
        }
    });

    //删除背景图
    $('#btn_del_background_image').on('click', function() {
        $('#img_background_image').attr('src', '');
        $('#txt_background_image').val('');
        $('#div_background_image').hide();
    });

    //保存背景
    $('#btn_save_background').on('click', function() {
        var post = { 
            decoration_id: DECORATION_ID,
            background_color: $('#txt_background_color').val(),
            background_image: $('#txt_background_image').val(),
            background_image_repeat: $("input[name='background_repeat']:checked").val(),
            background_position_x: $('#txt_background_position_x').val(),
            background_position_y: $('#txt_background_position_y').val(),
            background_attachment: $('#txt_background_attachment').val()
        };

        decoration.ajax_post(
            URL_DECORATION_BACKGROUND_SETTING_SAVE,
            post,
            function(data) {
                $('#store_decoration_content').attr('style', data.decoration_background_style);
            },
            function() {
                $('#dialog_edit_background').hide();
            }
        );
    });

    //编辑头部
    $('#btn_edit_head').on('click', function() {
        $('#dialog_edit_head').im_show_dialog({width: 640, title: '编辑头部'});
    });

    //编辑头部弹出窗口tabs
    $('#dialog_edit_head_tabs').tabs();

    //恢复默认导航样式
    $('#btn_default_nav_style').on('click', function() {
        $('#decoration_nav_style').val(decoration.default_nav_style);
    });

    //保存导航样式
    $('#btn_save_decoration_nav').on('click', function() {
        var nav_display = $("input[name='decoration_nav_display']:checked").val();
        var nav_style = $('#decoration_nav_style').val();

        var post = {
            decoration_id: DECORATION_ID,
            nav_display: nav_display,
            content: nav_style
        };
       
        decoration.ajax_post(
            URL_DECORATION_NAV_SAVE,
            post,
            function(data) {
                decoration.apply_nav_style(nav_display, nav_style);
                $('#dialog_edit_head').hide();
            }
        );
    });

    //上传banner图
    $('#file_decoration_banner').fileupload({
        dataType: 'json',
        url: URL_DECORATION_ALBUM_UPLOAD, 
        add: function (e, data) {
            $('#img_banner_image').attr('src', LOADING_IMAGE);
            $('#img_banner_image').addClass('loading');
            $('#div_banner_image').show();
            data.submit();
        },
        done: function (e, data) {
            var result = data.result;
            $('#img_banner_image').removeClass('loading');
            if(typeof result.error == 'undefined') {
                $('#img_banner_image').attr('src', result.image_url);
                $('#txt_banner_image').val(result.image_name);
                $('#div_banner_image').show();
            } else {
                $('#div_banner_image').hide();
                showError(result.error);
            }
        }
    });

    //删除banner图
    $('#btn_del_banner_image').on('click', function() {
        $('#txt_banner_image').val('');
        $('#div_banner_image').hide();
    });

    //保存装修banner设置
    $('#btn_save_decoration_banner').on('click', function() {
        var banner_display = $("input[name='decoration_banner_display']:checked").val();
        var banner_image = $('#txt_banner_image').val();

        var post = {
            decoration_id: DECORATION_ID,
            banner_display: banner_display,
            content: banner_image
        };
       
        decoration.ajax_post(
            URL_DECORATION_BANNER_SAVE,
            post,
            function(data) {
                decoration.apply_banner(banner_display, data.image_url);
                $('#dialog_edit_head').hide();
            }
        );
    });

    //添加块
    $('#btn_add_block').on('click', function() {
        var post = { 
            decoration_id: DECORATION_ID,
            block_layout: 'block_1'
        };

        decoration.ajax_post(
            URL_DECORATION_BLOCK_ADD,
            post,
            function(data) {
                $('#store_decoration_area').append(data.html);

                //title提示
                $('.tip').poshytip(POSHYTIP);

                //滚动到底部
                $("html, body").animate({ scrollTop: $(document).height() }, 1000);

                //块排序
                decoration.sort_decoration_block();
            }
        );
    });

    //删除块
    $('#store_decoration_area').on('click', '[imtype="btn_del_block"]', function() {
        $this = $(this);
        if(confirm('确认删除？')) {
            var post = { 
                block_id: $(this).attr('data-block-id')
            };

            decoration.ajax_post(
                URL_DECORATION_BLOCK_DEL,
                post,
                function(data) {
                    $this.parents('[imtype="store_decoration_block"]').hide();
                }
            );
        }
    });

    //装修块拖拽排序
    $( "#store_decoration_area" ).sortable({
        update: function(event, ui) {
            decoration.sort_decoration_block();
        }
    });

    //添加模块
    $('#store_decoration_area').on('click', '[imtype="btn_edit_module"]', function() {
        var module_type = $(this).attr('data-module-type');
        decoration.current_block_id = $(this).attr('data-block-id');
        decoration.current_block_edit_button = $(this);
        if(module_type == '') {
            //新模块弹出模块选择对话框
            $('#dialog_select_module').im_show_dialog({width: 480, title: '选择模块'});
        } else {
            //已有模块直接编辑
            var $block = $('#block_' + decoration.current_block_id);
            var content = $block.find('[imtype="store_decoration_block_module"]').html();
            var full_width = $block.hasClass('store-decoration-block-full-width');
            decoration.show_dialog_module(module_type, content, full_width);
        }
    });
	//商品模块编辑 add by yangbaiyan
    $('#store_decoration_area').on('click', '[imtype="btn_edit_goods_module"]', function() {
        var module_type = $(this).attr('data-module-type');
        decoration.current_block_id = $(this).attr('data-block-id');
        decoration.current_block_edit_button = $(this);
        if(module_type == '') {
            //新模块弹出模块选择对话框
            $('#dialog_select_module').im_show_dialog({width: 480, title: '选择模块'});
        } else {
            //已有模块直接编辑
            var $block = $('#block_' + decoration.current_block_id);
            var content = $block.find('[imtype="store_decoration_block_module"]').html();
            var full_width = $block.hasClass('store-decoration-block-full-width');
            decoration.show_goods_dialog_module(module_type, content, full_width);
        }
    });

    //模块选择对话框选择模块类型后打开对应的模块编辑对话框
    $('[imtype="btn_show_module_dialog"]').on('click', function() {
        var module_type = $(this).attr('data-module-type');
        decoration.show_dialog_module(module_type);
    });

    //自定义模块保存
    $('#btn_save_module_html').on('click', function() {
        decoration.editor.sync();
        var html = $('#module_html_editor').val();

        decoration.save_decoration_block(html, 'html');
    });

    //添加幻灯图片
    $('#btn_add_slide_image').on('click', function() {
        var image_count = $('#module_slide_html ul').children().length;
        if(image_count >= decoration.slide_image_limit) {
            showError('每个幻灯片最多只能上传' + decoration.slide_image_limit + '张图片');
            return;
        }
        $('#div_module_slide_image').html('');
        $('#module_slide_url').val('');
        $('#div_module_slide_upload').show();
        $('#btn_add_slide_image').hide();
    });

    //幻灯图片上传
    $('[imtype="btn_module_slide_upload"]').fileupload({
        dataType: 'json',
        url: URL_DECORATION_ALBUM_UPLOAD, 
        add: function (e, data) {
            $('#div_module_slide_image').html('<img class="loading" src="' + LOADING_IMAGE + '">');
            data.submit();
        },
        done: function (e, data) {
            var result = data.result;
            if(typeof result.error == 'undefined') {
                $('#div_module_slide_image').html('<img src="' + result.image_url + '" data-image-name="' + result.image_name + '">');
            } else {
                $('#div_module_slide_image').html('');
                showError(result.error);
            }
        }
    });

    //保存添加的幻灯图片
    $('#btn_save_add_slide_image').on('click', function() {
        var data = {};
        $image = $('#div_module_slide_image img');
        if($image.length > 0) {
            data.image_url = $image.attr('src');
            data.image_name = $image.attr('data-image-name');
            data.image_link = $('#module_slide_url').val();

            var html = template.render('template_module_slide_image_list', data);
            $('#module_slide_html ul').append(html);
            $('#div_module_slide_upload').hide();
            $('#btn_add_slide_image').show();
        } else {
            showError('请上传图片');
        }
    });

    //幻灯片模块图片删除
    $('#module_slide_html').on('click', '[imtype="btn_del_slide_image"]', function() {
        $(this).parents('li').remove();
    });

    //取消添加幻灯图片
    $('#btn_cancel_add_slide_image').on('click', function() {
        $('#div_module_slide_upload').hide();
        $('#btn_add_slide_image').show();
    });

    //幻灯模块保存
    $('#btn_save_module_slide').on('click', function() {
        var data = {};
        var i = 0;
        data.height = parseInt($('#txt_slide_height').val(), 10);

        //验证高度
        if(isNaN(data.height)) {
            showError('请输入正确的显示高度');
            return;
        }

        data.images = [];
        $('#module_slide_html li').each(function() { 
            var image = {};
            image.image_name = $(this).attr('data-image-name');
            image.image_link = $(this).attr('data-image-link');
            data.images[i] = image;
            i++;
        });
        decoration.save_decoration_block(data, 'slide', $('#txt_slide_full_width').attr('checked'));
    });

    //热点图片上传
    $('[imtype="btn_module_hot_area_upload"]').fileupload({
        dataType: 'json',
        url: URL_DECORATION_ALBUM_UPLOAD, 
        add: function (e, data) {
            $('#div_module_hot_area_image').html('<img class="loading" src="' + LOADING_IMAGE + '">');
            data.submit();
        },
        done: function (e, data) {
            var result = data.result;
            if(typeof result.error == 'undefined') {
                $('#div_module_hot_area_image').html('<img src="' + result.image_url + '" data-image-name="' + result.image_name + '">');
                decoration.$hot_area_image = $('#div_module_hot_area_image').find('img');
                decoration.$hot_area_image.imgAreaSelect({ 
                    handles: true,
                    zIndex: 1200,
                    fadeSpeed: 200 
                });
            } else {
                $('#div_module_hot_area_image').html('');
                showError(result.error);
            }
        }
    });

    //添加热点区域
    $('#btn_module_hot_area_add').on('click', function() {
        var ias = decoration.$hot_area_image.imgAreaSelect({ instance: true });
        var selection = ias.getSelection();
        if (!selection.width || !selection.height) {
            showError('请选择热点区域');
            return;
        }

        //添加热点块
        var position = selection.x1 + ',' + selection.y1 + ',' + selection.x2 + ',' + selection.y2; 
        var link = $('#module_hot_area_url').val();
        decoration.add_hot_area(position, link);

        decoration.hot_area_cancel_selection();
    });

    //选择图片热点块
    $('#dialog_module_hot_area').on('click', '[imtype="btn_module_hot_area_select"]', function() {
        var position = $(this).attr('data-hot-area-position').split(',');
        var ias = decoration.$hot_area_image.imgAreaSelect({ instance: true });
        ias.setSelection(position[0], position[1], position[2], position[3], true);
        ias.setOptions({ show: true });
        ias.update();
    });

    //删除图片热点块
    $('#dialog_module_hot_area').on('click', '[imtype="btn_module_hot_area_del"]', function() {
        var display_id = $(this).attr('data-index');
        $('#hot_area_display_' + display_id).remove();
        $(this).parents('li').remove();
    });

    //图片热点模块保存
    $('#btn_save_module_hot_area').on('click', function() {
        var data = {};
        var i = 0;
        data.image = decoration.$hot_area_image.attr('data-image-name');
        if(data.image == '') {
            showError('请首先上传图片并添加热点');
            return;
        }

        data.areas = [];
        $('#module_hot_area_select_list li').each(function() { 
            var area = {};
            var position = $(this).attr('data-hot-area-position').split(',');
            area.x1 = position[0];
            area.y1 = position[1];
            area.x2 = position[2];
            area.y2 = position[3];
            area.link= $(this).attr('data-hot-area-link');
            data.areas[i] = area;
            i++;
        });

        decoration.hot_area_cancel_selection();

        decoration.save_decoration_block(data, 'hot_area');
    });
	
	//编辑商品头部弹出窗口tabs
    $('#dialog_module_goods_title_tabs').tabs();
	
	$('[imtype="module_goods_title_type"]').change(function() { 
        var selectedvalue = $('[imtype="module_goods_title_type"]:checked').val();
		if (selectedvalue=='img'){
			$('#div_module_goods_title_text').hide();
			$('#div_module_goods_title_img').show();
		}else{
			$('#div_module_goods_title_img').hide();
			$('#div_module_goods_title_text').show();
		}
    });
	//商品头部图片上传
    $('[imtype="btn_module_goods_title_img_upload"]').fileupload({
        dataType: 'json',
        url: URL_DECORATION_ALBUM_UPLOAD, 
        add: function (e, data) {
            $('#div_module_goods_title_img_pre').html('<img class="loading" src="' + LOADING_IMAGE + '">');
            data.submit();
        },
        done: function (e, data) {
            var result = data.result;
            if(typeof result.error == 'undefined') {
                $('#div_module_goods_title_img_pre').html('<img src="' + result.image_url + '" data-image-name="' + result.image_name + '">');
            } else {
                $('#div_module_goods_title_img_pre').html('');
                showError(result.error);
            }
        }
    });
	
	//添加商品广告
    $('#btn_add_goods_adv_caption').on('click', function() {
        var adv_count = $('#module_goods_title_adv_list ul').children().length;
        if(adv_count >= 5) {
            showError('每个模块只能添加5个广告');
            return;
        }
        $('#module_goods_adv_caption').val('');
		$('#module_goods_adv_url').val('');
        $('#div_module_goods_title_adv').show();
        $('#btn_add_goods_adv_caption').hide();
    });
	
	//保存添加的商品广告
    $('#btn_save_add_goods_adv_caption').on('click', function() {
        var data = {};
		data.adv_name = $('#module_goods_adv_caption').val();
        data.adv_url = $('#module_goods_adv_url').val();        
			
        if(data.adv_name != '') {
            var html = template.render('template_module_goods_adv_list', data);
            $('#module_goods_title_adv_list ul').append(html);
            $('#div_module_goods_title_adv').hide();
            $('#btn_add_goods_adv_caption').show();
        } else {
            showError('请输入广告标题');
        }
    });
	
	//取消添加商品广告
    $('#btn_cancel_add_goods_adv_caption').on('click', function() {
        $('#div_module_goods_title_adv').hide();
        $('#btn_add_goods_adv_caption').show();
    });

    //商品广告删除
    $('#module_goods_title_adv_list').on('click', '[imtype="btn_del_goods_adv_caption"]', function() {
        $(this).parents('li').remove();
    });

    
	//商品模块头部保存
    $('#btn_save_module_goods_title').on('click', function() {
        var data = {};
		
		data.type = $('[imtype="module_goods_title_type"]:checked').val();
		data.text = $("#module_goods_title_text").val();
		data.url = $('#div_module_goods_title_img_pre img').attr('src');
		data.img = $('#div_module_goods_title_img_pre img').attr('data-image-name');
		
		var i = 0;
		var adv_list = [];
        $('#module_goods_title_adv_list li').each(function() { 
            var adv = {};
            adv.adv_name = $(this).attr('data-adv-name');
            adv.adv_url = $(this).attr('data-adv-url');
			adv_list[i] = adv;
            i++;
        });
		data.adv = adv_list;

        decoration.save_decoration_block_goods(data, 'title');
    });


    //商品模块搜索
    $('#btn_module_goods_search').on('click', function() {
        var param = '&' + $.param({keyword: $('#txt_goods_search_keyword').val()});
        $('#div_module_goods_search_list').load(URL_DECORATION_GOODS_SEARCH + param);
    });

    //商品模块搜索结果翻页
    $('#div_module_goods_search_list').on('click', 'a.demo', function() {
        $('#div_module_goods_search_list').load($(this).attr('href'));
        return false;
    });

    //商品添加
    $('#div_module_goods_search_list').on('click', '[imtype="btn_module_goods_operate"]', function() {
        var $goods = $(this).parents('[imtype="goods_item"]').clone();
        $goods.find('[imtype="btn_module_goods_operate"]').html('<i class="fa fa-ban"></i>取消选择');
        $('#div_module_goods_list').append($goods);
    });

    //商品删除
    $('#div_module_goods_list').on('click', '[imtype="btn_module_goods_operate"]', function() {
        $(this).parents('[imtype="goods_item"]').remove();
    });

    //商品模块保存
    $('#btn_save_module_goods').on('click', function() {
        var data = [];
        var i = 0;

        $('#div_module_goods_list').find('[imtype="goods_item"]').each(function() { 
            var goods = {};
            goods.goods_id = $(this).attr('data-goods-id');
            goods.goods_name = $(this).attr('data-goods-name');
            goods.goods_price = $(this).attr('data-goods-price');
			goods.goods_promotion_price = $(this).attr('data-goods-promotion-price');
			goods.goods_marketprice = $(this).attr('data-goods-marketprice');
            goods.goods_image = $(this).attr('data-goods-image');
            data[i] = goods;
            i++;
        });

        decoration.save_decoration_block_goods(data, 'goods');
    });
	
	//添加商品幻灯图片
    $('#btn_add_goods_slide_image').on('click', function() {
        var image_count = $('#module_goods_slide_html ul').children().length;
        if(image_count >= decoration.slide_image_limit) {
            showError('每个幻灯片最多只能上传' + decoration.slide_image_limit + '张图片');
            return;
        }
        $('#div_module_goods_slide_image').html('');
        $('#module_goods_slide_url').val('');
        $('#div_module_goods_slide_upload').show();
        $('#btn_add_goods_slide_image').hide();
    });

    //商品幻灯图片上传
    $('[imtype="btn_module_goods_slide_upload"]').fileupload({
        dataType: 'json',
        url: URL_DECORATION_ALBUM_UPLOAD, 
        add: function (e, data) {
            $('#div_module_goods_slide_image').html('<img class="loading" src="' + LOADING_IMAGE + '">');
            data.submit();
        },
        done: function (e, data) {
            var result = data.result;
            if(typeof result.error == 'undefined') {
                $('#div_module_goods_slide_image').html('<img src="' + result.image_url + '" data-image-name="' + result.image_name + '">');
            } else {
                $('#div_module_goods_slide_image').html('');
                showError(result.error);
            }
        }
    });

    //保存添加的商品幻灯图片
    $('#btn_save_add_goods_slide_image').on('click', function() {
        var data = {};
        $image = $('#div_module_goods_slide_image img');
        if($image.length > 0) {
            data.image_url = $image.attr('src');
            data.image_name = $image.attr('data-image-name');
            data.image_link = $('#module_goods_slide_url').val();

            var html = template.render('template_module_goods_slide_image_list', data);
            $('#module_goods_slide_html ul').append(html);
            $('#div_module_goods_slide_upload').hide();
            $('#btn_add_goods_slide_image').show();
        } else {
            showError('请上传图片');
        }
    });

    //商品幻灯片模块图片删除
    $('#module_goods_slide_html').on('click', '[imtype="btn_del_goods_slide_image"]', function() {
        $(this).parents('li').remove();
    });

    //取消添加商品幻灯图片
    $('#btn_cancel_add_goods_slide_image').on('click', function() {
        $('#div_module_goods_slide_upload').hide();
        $('#btn_add_goods_slide_image').show();
    });

    //商品幻灯模块保存
    $('#btn_save_module_goods_slide').on('click', function() {
        var data = [];
        var i = 0;

        $('#module_goods_slide_html li').each(function() { 
            var image = {};
            image.image_name = $(this).attr('data-image-name');
            image.image_link = $(this).attr('data-image-link');
			data[i] = image;
            i++;
        });
        decoration.save_decoration_block_goods(data, 'slide');
    });
	
	
	//品牌模块搜索
    $('#btn_module_brand_search').on('click', function() {
        var param = '&' + $.param({keyword: $('#txt_brand_search_keyword').val()});
        $('#div_module_brand_search_list').load(URL_DECORATION_BRAND_SEARCH + param);
    });

    //品牌模块搜索结果翻页
    $('#div_module_brand_search_list').on('click', 'a.demo', function() {
        $('#div_module_brand_search_list').load($(this).attr('href'));
        return false;
    });

    //品牌添加
    $('#div_module_brand_search_list').on('click', '[imtype="btn_module_brand_operate"]', function() {
        var $brand = $(this).parents('[imtype="brand_item"]').clone();
        $brand.find('[imtype="btn_module_brand_operate"]').html('<i class="fa fa-ban"></i>取消选择');
        $('#div_module_brand_list').append($brand);
    });

    //品牌删除
    $('#div_module_brand_list').on('click', '[imtype="btn_module_brand_operate"]', function() {
        $(this).parents('[imtype="brand_item"]').remove();
    });

    //品牌模块保存
    $('#btn_save_module_brand').on('click', function() {
        var data = [];
        var i = 0;

        $('#div_module_brand_list').find('[imtype="brand_item"]').each(function() { 
            var brand = {};
            brand.brand_name = $(this).attr('data-brand-name');
            brand.brand_url = $(this).attr('data-brand-url');
            brand.brand_img = $(this).attr('data-brand-img');
            data[i] = brand;
            i++;
        });

        decoration.save_decoration_block_goods(data, 'brand');
    });
	//品牌模块功能操作开始-------------------------------------------------------------------------		
	//添加品牌橱窗
    $('#brand_btn_add_row').on('click', function() {
        var brand_row_count = $('#brand_hd ul').children().length;
        if(brand_row_count >= 3) {			
            showError('每个模块只能添加3个展示橱窗');
            return;
        }
        $('#brand_module_row_caption').val('');
		$('#brand_module_row_sec_caption').val('');
        $('#brand_div_module_row').show();
        $('#brand_btn_add_group').hide();
    });
	
	//保存添加的品牌橱窗
    $('#brand_btn_save_add_row').on('click', function() {
        var data = {};
		var count = brand_row_count = $('#brand_hd ul').children().length+1;
		data.row_caption = $('#brand_module_row_caption').val();
        data.row_sec_caption = $('#brand_module_row_sec_caption').val();        
		data.row_count = count;
		data.row_show = true;
		
		var html = template.render('template_module_brand_row_list', data);
			
        if(data.row_caption != '') {
			$("#brand_bd ul").each(function() {
		        $(this).hide();
	        });
			$("#brand_hd ul li").each(function() {
		        $(this).attr("class","");
	        });
	
            $('#brand_hd ul').append(html);
			$('#brand_bd').append('<ul id="brand_group_'+count+'"></ul>');
			
            $('#brand_div_module_row').hide();
            $('#brand_btn_add_group').show();
        } else {
            showError('请输入品牌橱窗标题');
        }
    });
	
	//取消添加品牌橱窗
    $('#brand_btn_cancel_add_row').on('click', function() {
        $('#brand_div_module_row').hide();
        $('#brand_btn_add_group').show();
    });

    //品牌橱窗删除
    $('#brand_module_brand_list').on('click', '[imtype="brand_btn_del_row"]', function() {		
		var id = $(this).parents('li').attr("data-row-index");
		$("#brand_group_"+id).remove();		
        $(this).parents('li').remove();
		
		$("#brand_hd ul li").first().attr('class','on');
		$("#brand_bd ul").first().show();
    });
	
	//添加品牌
    $('#brand_btn_add_item').on('click', function() {
        var brand_row_count = $('#brand_hd ul').children().length;
        if(brand_row_count <= 0) {			
            showError('请先添加品牌展示橱窗');
            return;
        }
		var id='';
		$("#brand_hd ul li").each(function() {
		    if ($(this).attr('class')=='on'){
				id = $(this).attr("data-row-index");
			}
	    });			
		if (id==''){		
            showError('请先选择品牌展示橱窗');
            return;
		}
		
		$('#brand_div_module_pic_pre').html('');
        $('#brand_module_item_name').val('');
		$('#brand_module_item_url').val('');
		
        $('#brand_div_module_item').show();
        $('#brand_btn_add_group').hide();
    });
	
	//品牌图片上传
    $('[imtype="brand_module_pic_upload"]').fileupload({
        dataType: 'json',
        url: URL_DECORATION_ALBUM_UPLOAD, 
        add: function (e, data) {
            $('#brand_div_module_pic_pre').html('<img class="loading" src="' + LOADING_IMAGE + '">');
            data.submit();
        },
        done: function (e, data) {
            var result = data.result;
            if(typeof result.error == 'undefined') {
                $('#brand_div_module_pic_pre').html('<img src="' + result.image_url + '" data-image-name="' + result.image_name + '">');
            } else {
                $('#brand_div_module_pic_pre').html('');
                showError(result.error);
            }
        }
    });
	
	//保存添加的自定义品牌
    $('#brand_btn_save_add_item').on('click', function() {
        var data = {};
		
		$image = $('#brand_div_module_pic_pre img');
		if($image.length > 0) {
			var id='';
		    $("#brand_hd ul li").each(function() {
		        if ($(this).attr('class')=='on'){
				    id = $(this).attr("data-row-index");
			    }
	        });			
		    if (id!=''){		
			    data.brand_name = $('#brand_module_item_name').val();
                data.brand_img = $image.attr('src');
		        data.brand_url = $('#brand_module_item_url').val();        

                var html = template.render('template_module_brand_item_list', data);
                $("#brand_group_"+id).append(html);
		    }			
            $('#brand_div_module_item').hide();
            $('#brand_btn_add_group').show();
        } else {
            showError('请上传品牌图片');
        }
    });
	
	//取消添加品牌
    $('#brand_btn_cancel_add_item').on('click', function() {
        $('#brand_div_module_item').hide();
        $('#brand_btn_add_group').show();
    });
	
	//品牌模块搜索
    $('#brand_module_btn_search').on('click', function() {
        var param = '&' + $.param({keyword: $('#brand_search_keyword').val()});
        $('#brand_div_module_search_list').load(URL_DECORATION_BRAND_SEARCH + param);
    });

    //品牌模块搜索结果翻页
    $('#brand_div_module_search_list').on('click', 'a.demo', function() {
        $('#brand_div_module_search_list').load($(this).attr('href'));
        return false;
    });
	
	//品牌添加
    $('#brand_div_module_search_list').on('click', '[imtype="btn_module_brand_operate"]', function() {
		var brand_row_count = $('#brand_hd ul').children().length;
        if(brand_row_count <= 0) {			
            showError('请先添加品牌展示橱窗');
            return;
        }
		var id='';
		$("#brand_hd ul li").each(function() {
		    if ($(this).attr('class')=='on'){
				id = $(this).attr("data-row-index");
			}
	    });			
		if (id!=''){		
            var $brand = $(this).parents('[imtype="brand_item"]').clone();
            $brand.find('[imtype="btn_module_brand_operate"]').html('<i class="fa fa-ban"></i>取消选择');
		
            $("#brand_group_"+id).append($brand);
		}
    });

    //品牌删除
    $('#brand_bd').on('click', '[imtype="btn_module_brand_operate"]', function() {
        $(this).parents('[imtype="brand_item"]').remove();
    });
    
	//品牌模块保存
    $('#brand_btn_save_module_list').on('click', function() {
		var brand_row_count = $('#brand_hd ul').children().length;
        if(brand_row_count <= 0) {			
            showError('请先添加品牌展示橱窗');
            return;
        }
		
        var data = [];
		var id='';
		
		var i = 0;
		var j =0;
		$("#brand_hd ul li").each(function() {
			var groups = {};
		    var brand_group = {};
			id = $(this).attr("data-row-index");
			brand_group.row_caption = $(this).attr('data-row-caption');
			brand_group.row_sec_caption = $(this).attr('data-row-sec-caption');
			groups.brand_group = brand_group;
			var items = [];			
			j = 0;
			$("#brand_group_"+id+" li").each(function() {
				var brand_item = {};
				brand_item.brand_name = $(this).attr('data-brand-name');
				brand_item.brand_img = $(this).attr('data-brand-img');
				brand_item.brand_url = $(this).attr('data-brand-url');
				items[j] = brand_item;
				j++;
			});
			groups.brand_items = items;
			
			data[i] = groups;
			i++;
	    });		

        decoration.save_decoration_block(data, 'brand');
    });
	
	//品牌模块功能操作结束-------------------------------------------------------------------------

    //关闭窗口
    $('#btn_close').on('click', function() {
        window.close();
    });	
});

//tttttttttt
function brand_row_item_click(obj){
    if (obj.attr('class')!='on'){
		$("#brand_hd ul li").each(function() {
		    $(this).attr("class","");
	    });			
		obj.attr('class','on');
		
		$("#brand_bd ul").each(function() {
		    $(this).hide();
		});
		var id = obj.attr("data-row-index");
		$("#brand_group_"+id).show();
	}
}

function brand_row_item_over(obj){	
	//$("#brand_bd ul").each(function() {
	//	$(this).hide();
	//});
	//var id = obj.attr("data-row-index");
	//$("#brand_group_"+id).show();
}

function brand_row_item_out(obj){	
	//
}
