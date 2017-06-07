$(function(){
	// 取消回车提交表单
    $('input').keypress(function(e){
        var key = window.event ? e.keyCode : e.which;
        if (key.toString() == "13") {
         return false;
        }
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
    
    /* 商品图片ajax上传 */
    $('#goods_image').fileupload({
        dataType: 'json',
        url: SITEURL + '/index.php?act=store_goods_add&op=image_upload&upload_type=uploadedfile', //SITEURL + '/index.php?act=web_api&op=bgimage_upload',
        formData: {name:'goods_image'},
        add: function (e,data) {
        	$('img[imtype="goods_image"]').attr('src', SHOP_SKINS_URL + '/images/loading.gif');
            data.submit();
        },
        done: function (e,data) {
            var param = data.result;
            if (typeof(param.error) != 'undefined') {
                alert(param.error);
                $('img[imtype="goods_image"]').attr('src',DEFAULT_GOODS_IMAGE);
            } else {
                $('input[imtype="goods_image"]').val(param.name);
                $('img[imtype="goods_image"]').attr('src',param.thumb_name);
            }
        }
    });

    /* ajax打开图片空间 */
    // 商品主图使用
    $('a[imtype="show_image"]').unbind().ajaxContent({
        event:'click', //mouseover
        loaderType:"img",
        loadingMsg:SHOP_SKINS_URL+"/images/loading.gif",
        target:'#demo'
    }).click(function(){
        $(this).hide();
        $('a[imtype="del_goods_demo"]').show();
    });
    $('a[imtype="del_goods_demo"]').unbind().click(function(){
        $('#demo').html('');
        $(this).hide();
        $('a[imtype="show_image"]').show();
    });
	
	/* 商品广告图片ajax上传 */
    $('#goods_advpic').fileupload({
        dataType: 'json',
        url: SITEURL + '/index.php?act=store_goods_add&op=image_upload&upload_type=uploadedfile', //SITEURL + '/index.php?act=web_api&op=bgimage_upload',
        formData: {name:'goods_advpic'},
        add: function (e,data) {
        	$('img[imtype="goods_advpic"]').attr('src', SHOP_SKINS_URL + '/images/loading.gif');
            data.submit();
        },
        done: function (e,data) {
            var param = data.result;
            if (typeof(param.error) != 'undefined') {
                alert(param.error);
                $('img[imtype="goods_advpic"]').attr('src',DEFAULT_GOODS_IMAGE);
            } else {
                $('input[imtype="goods_advpic"]').val(param.name);
                $('img[imtype="goods_advpic"]').attr('src',param.thumb_name);
            }
        }
    });
	/* ajax打开图片空间 */
    // 商品广告图片使用
    $('a[imtype="show_advpic"]').unbind().ajaxContent({
        event:'click', //mouseover
        loaderType:"img",
        loadingMsg:SHOP_SKINS_URL+"/images/loading.gif",
        target:'#demo_advpic'
    }).click(function(){
        $(this).hide();
        $('a[imtype="del_goods_advpic_demo"]').show();
    });
    $('a[imtype="del_goods_advpic_demo"]').unbind().click(function(){
        $('#demo_advpic').html('');
        $(this).hide();
        $('a[imtype="show_advpic"]').show();
    });
    // 商品描述使用
    $('a[imtype="show_desc"]').unbind().ajaxContent({
        event:'click', //mouseover
        loaderType:"img",
        loadingMsg:SHOP_SKINS_URL+"/images/loading.gif",
        target:'#des_demo'
    }).click(function(){
        $(this).hide();
        $('a[imtype="del_desc"]').show();
    });
    $('a[imtype="del_desc"]').click(function(){
        $('#des_demo').html('');
        $(this).hide();
        $('a[imtype="show_desc"]').show();
    });
    $('#add_album').fileupload({
        dataType: 'json',
        url: SITEURL+'/index.php?act=store_goods_add&op=image_upload',
        formData: {name:'add_album'},
        add: function (e,data) {
            $('i[imtype="add_album_i"]').removeClass('fa fa-upload').addClass('fa fa-spinner icon-spin icon-large').attr('data_type', parseInt($('i[imtype="add_album_i"]').attr('data_type'))+1);
            data.submit();
        },
        done: function (e,data) {
            var _counter = parseInt($('i[imtype="add_album_i"]').attr('data_type'));
            _counter -= 1;
            if (_counter == 0) {
                $('i[imtype="add_album_i"]').removeClass('fa fa-spinner icon-spin icon-large').addClass('fa fa-upload');
                $('a[imtype="show_desc"]').click();
            }
            $('i[imtype="add_album_i"]').attr('data_type', _counter);
        }
    });
    /* ajax打开图片空间 end */
    
    // 商品属性
    attr_selected();
    $('select[im_type="attr_select"]').change(function(){
        id = $(this).find('option:selected').attr('im_type');
        name = $(this).attr('attr').replace(/__IM__/g,id);
        $(this).attr('name',name);
    });
    
    // 修改规格名称
    $('dl[imtype="spec_group_dl"]').on('click', 'input[type="checkbox"]', function(){
        pv = $(this).parents('li').find('span[imtype="pv_name"]');
        if(typeof(pv.find('input').val()) == 'undefined'){
            pv.html('<input type="text" maxlength="20" class="text" value="'+pv.html()+'" />');
        }else{
            pv.html(pv.find('input').val());
        }
    });
    
    $('span[imtype="pv_name"] > input').live('change',function(){
        change_img_name($(this));       // 修改相关的颜色名称
        into_array();           // 将选中的规格放入数组
        goods_stock_set();      // 生成库存配置
    });
    
    // 修改品牌名称
    $('select[name="b_id"]').change(function(){
        getBrandName();
    });

    // 运费部分显示隐藏
    $('input[imtype="freight"]').click(function(){
            $('input[imtype="freight"]').nextAll('div[imtype="div_freight"]').hide();
            $(this).nextAll('div[imtype="div_freight"]').show();
    });
    
    // 商品所在地
    var area_select = $("#province_id");
    areaInit(area_select,0);//初始化地区
    $("#province_id").change(function (){
        // 删除后面的select
        $(this).nextAll("select").remove();
        if (this.value > 0){
            var text = $(this).get(0).options[$(this).get(0).selectedIndex].text;
            var area_id = this.value;
            var EP = new Array();
            EP[1]= true;EP[2]= true;EP[9]= true;EP[22]= true;EP[34]= true;EP[35]= true;
            if(typeof(im_a[area_id]) != 'undefined'){//数组存在
                var areas = new Array();
                var option = "";
                areas = im_a[area_id];
            if (typeof(EP[area_id]) == 'undefined'){
                option = "<option value='0'>"+text+"(*)</option>";
            }
            $("<select name='city_id' id='city_id'>"+option+"</select>").insertAfter(this);
                for (var i = 0; i <areas.length; i++){
                    $(this).next("select").append("<option value='" + areas[i][0] + "'>" + areas[i][1] + "</option>");
                }
            }
        }
     });
    
    // 定时发布时间
    $('#starttime').datepicker({dateFormat: 'yy-mm-dd'});
    $('input[name="g_state"]').click(function(){
        if($(this).attr('imtype') == 'auto'){
            $('#starttime').removeAttr('disabled').css('background','');
            $('#starttime_H').removeAttr('disabled').css('background','');
            $('#starttime_i').removeAttr('disabled').css('background','');
        }else{
            $('#starttime').attr('disabled','disabled').css('background','#E7E7E7 none');
            $('#starttime_H').attr('disabled','disabled').css('background','#E7E7E7 none');
            $('#starttime_i').attr('disabled','disabled').css('background','#E7E7E7 none');
        }
    });
    
    // 计算批发价\三级分销利润
    $('input[name="g_price"],input[name="g_collect"]').change(function(){
    	//g_costpriceCalculator();
    	//
    	g_tradeCalculator();
    });
    gainCalculator(); //自动计算利润
    // 计算折扣
    $('input[name="g_price"],input[name="g_marketprice"]').change(function(){
        discountCalculator();
    });
    
    // 计算利润
    $('input[name="g_price"],input[name="g_costprice"]').change(function(){
    	//alert("计算利润");
    	//gainCalculator();
    });
    
    // 批量设置商品货号
    $('input[name="g_serial"]').change(function(){
    	var _serial = parseFloat($('input[name="g_serial"]').val());
    	if(confirm("是否也更新商品属性里的货号！"))
    	{
    		$('input[data_type="serial" ]').attr('value',_serial);
    	}
    });
    
    $('input[name="g_price"]').change(function(){
    	batchpriceCalculator();
    });
    $('input[name="g_marketprice"]').change(function(){
    	batchmpriceCalculator();
    });
    //批发价变动后批量设置属性价
    $('input[name="g_tradeprice"]').change(function(){
    	batchtpriceCalculator();
    	//计算三级分销利润
    	gainCalculator();
    });
    /* AJAX添加规格值 */
    // 添加规格
    $('a[imtype="specAdd"]').click(function(){
        var _parent = $(this).parents('li:first');
        _parent.find('div[imtype="specAdd1"]').hide();
        _parent.find('div[imtype="specAdd2"]').show();
        _parent.find('input').focus();
    });
    // 取消
    $('a[imtype="specAddCancel"]').click(function(){
        var _parent = $(this).parents('li:first');
        _parent.find('div[imtype="specAdd1"]').show();
        _parent.find('div[imtype="specAdd2"]').hide();
        _parent.find('input').val('');
    });
    // 提交
    $('a[imtype="specAddSubmit"]').click(function(){
        var _parent = $(this).parents('li:first');
        eval('var data_str = ' + _parent.attr('data-param'));
        var _input = _parent.find('input');
		_parent.find('div[imtype="specAdd1"]').show();
        _parent.find('div[imtype="specAdd2"]').hide();
        $.getJSON(data_str.url, {gc_id : data_str.gc_id , sp_id : data_str.sp_id , name : _input.val()}, function(data){
            if (data.done) {
                _parent.before('<li><span imtype="input_checkbox"><input type="checkbox" name="sp_val[' + data_str.sp_id + '][' + data.value_id + ']" im_type="' + data.value_id + '" value="' +_input.val()+ '" /></span><span imtype="pv_name">' + _input.val() + '</span></li>');
                _input.val('');
            }            
        });
    });
    // 修改规格名称
    $('input[imtype="spec_name"]').change(function(){
        eval('var data_str = ' + $(this).attr('data-param'));
        if ($(this).val() == '') {
            $(this).val(data_str.name);
        }
        $('th[imtype="spec_name_' + data_str.id + '"]').html($(this).val());
    });
	//赠品 add by yangbaiyan
	/* ajax添加商品  */
	$('#bundling_gift_goods').ajaxContent({
		event:'click', //mouseover
		loaderType:"img",
		loadingMsg:SHOP_SKINS_URL+"/images/loading.gif",
		target:'#bundling_gift_goods_ajaxContent'
	}).click(function(){
	    $(this).hide();
	    $('#bundling_gift_goods_delete').show();
	});
	//  add by yangbaiyan
	$('#bundling_gift_goods_delete').click(function(){
	    $(this).hide();
	    $('#bundling_gift_goods_ajaxContent').html('');
	    $('#bundling_gift_goods').show();
	});
	// 退拽效果  add by yangbaiyan
    $('tbody[imtype="bundling_data"]').sortable({ items: 'tr' });
    $('#goods_images').sortable({ items: 'li' });
	
	// 批量设置价格、库存、预警值
    $('.batch > .fa-pencil-square-o').click(function(){
        $('.batch > .batch-input').hide();
        $(this).next().show();
    });
    $('.batch-input > .close').click(function(){
        $(this).parent().hide();
    });
    $('.batch-input > .imsc-btn-mini').click(function(){
        var _value = $(this).prev().val();
        var _type = $(this).attr('data-type');
        if (_type == 'price' || _type == 'marketprice' || _type == 'tradeprice') {
            _value = number_format(_value, 2);
        }  else if (_type == 'serial' || _type == 'barcode') {
            _value = _value;
        } else {
            _value = parseInt(_value);
        }
        if (_type == 'alarm' && _value > 255) {
            _value = 255;
        }
        if (isNaN(_value) && _value =='') {
            _value = 0;
        }
        $('input[data_type="' + _type + '" ]').val(_value);
        $(this).parent().hide();
        $(this).prev().val('');
        if (_type == 'price') {
            computePrice();
        }
        if (_type == 'stock') {
            computeStock();
        }
    });
    
    /* AJAX选择品牌 */
    // 根据首字母查询
    $('.letter[imtype="letter"]').find('a[data-letter]').click(function(){
        var _url = $(this).parents('.brand-index:first').attr('data-url');
        var _tid = $(this).parents('.brand-index:first').attr('data-tid');
        var _letter = $(this).attr('data-letter');
        var _search = $(this).html();
        $.getJSON(_url, {type : 'letter', tid : _tid, letter : _letter}, function(data){
            insertBrand(data, _search);
        });
    });
    // 根据关键字查询
    $('.search[imtype="search"]').find('a').click(function(){
        var _url = $(this).parents('.brand-index:first').attr('data-url');
        var _tid = $(this).parents('.brand-index:first').attr('data-tid');
        var _keyword = $('#search_brand_keyword').val();
        $.getJSON(_url, {type : 'keyword', tid : _tid, keyword : _keyword}, function(data){
            insertBrand(data, _keyword);
        });
    });
    // 选择品牌
    $('ul[imtype="brand_list"]').on('click', 'li', function(){
        $('#b_id').val($(this).attr('data-id'));
        $('#b_name').val($(this).attr('data-name'));
        $('.imsc-brand-select > .imsc-brand-select-container').hide();
    });
    //搜索品牌列表滚条绑定
    $('div[imtype="brandList"]').perfectScrollbar();
    $('select[name="b_id"]').change(function(){
        getBrandName();
    });
    $('input[name="b_name"]').focus(function(){
        $('.imsc-brand-select > .imsc-brand-select-container').show();
    });
    
    //Ajax提示
    $('.tip').poshytip({
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'left',
        alignY: 'top',
        offsetX: 5,
        offsetY: -78,
        allowTipHover: false
    });
    $('.tip2').poshytip({
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'right',
        alignY: 'center',
        offsetX: 5,
        offsetY: 0,
        allowTipHover: false
    });

    /* 虚拟控制 */
    // 虚拟商品有效期
    $('#g_vindate').datepicker({dateFormat: 'yy-mm-dd', minDate: new Date()});
    $('[name="is_gv"]').change(function(){
        if ($('#is_gv_1').prop("checked")) {
            $('#is_fc_0').click();          // 虚拟商品不能发布F码，取消选择F码
            $('#is_presell_0').click();     // 虚拟商品不能设置预售，取消选择预售
            //zhangchao
            $('#is_crowdfunding_0').click();     // 虚拟商品不能设置众筹，取消选择众筹
            $('#is_market_0').click();     // 虚拟商品不能加入分销市场，取消选择加入市场
            
            $('[imtype="virtual_valid"]').show();
            $('[imtype="virtual_null"]').hide();
        } else {
            $('[imtype="virtual_valid"]').hide();
            $('[imtype="virtual_null"]').show();
            $('#g_vindate').val('');
            $('#g_vlimit').val('');
        }
    });
    
    /* F码控制 */
    $('[name="is_fc"]').change(function(){
        if ($('#is_fc_1').prop("checked")) {
            $('[imtype="fcode_valid"]').show();
        } else {
            $('[imtype="fcode_valid"]').hide();
            $('#g_fccount').val('');
            $('#g_fcprefix').val('');
        }
    });
    /* 众筹控制  zhangchao*/
    // 众筹--发货时间
    $('#g_crowdfundingdate').datepicker({dateFormat: 'yy-mm-dd', minDate: new Date()});
    $('#g_deliverdate').datepicker({dateFormat: 'yy-mm-dd', minDate: new Date()});
    
    $('[name="is_crowdfunding"]').change(function(){
        if ($('#is_crowdfunding_1').prop("checked")) {
            $('[imtype="is_crowdfunding"]').show();
        } else {
            $('[imtype="is_crowdfunding"]').hide();
        }
    });
    /* 选入市场控制  zhangchao*/
    	// 时间设置
        $('#cf_starttime').datetimepicker({
            //timeFormat: "HH:mm",
            //dateFormat: "yy-mm-dd",
            showSecond: true,
            timeFormat: 'HH:mm',
            stepHour: 1,
            stepMinute: 1,
            controlType:"select",
            minDateTime: new Date()
        });
        $('#cf_endtime').datetimepicker({
            //timeFormat: "HH:mm",
            //dateFormat: "yy-mm-dd",
            showSecond: true,
            timeFormat: 'HH:mm',
            stepHour: 1,
            stepMinute: 1,
            controlType:"select",
            minDateTime: new Date()
        });
    $('[name="is_market"]').change(function(){
        if ($('#is_market_1').prop("checked")) {
            $('[imtype="is_market"]').show();
        } else {
            $('[imtype="is_market"]').hide();
        }
    });
    /* 预售控制 */
    // 预售--发货时间
    //$('#g_deliverdate').datepicker({dateFormat: 'yy-mm-dd', minDate: new Date()});
    $('[name="is_presell"]').change(function(){
        if ($('#is_presell_1').prop("checked")) {
            $('[imtype="is_presell"]').show();
        } else {
            $('[imtype="is_presell"]').hide();
        }
    });
    
    /* 预约预售控制 */
    // 预约--出售时间
    $('#g_saledate').datepicker({dateFormat: 'yy-mm-dd', minDate: new Date()});
    $('[name="is_appoint"]').change(function(){
        if ($('#is_appoint_1').prop("checked")) {
            $('[imtype="is_appoint"]').show();
        } else {
            $('[imtype="is_appoint"]').hide();
        }
    });
    
    /* 手机端 商品描述 */
    // 显示隐藏控制面板
    $('div[imtype="mobile_pannel"]').on('click', '.module', function(){
        mbPannelInit();
        $(this).siblings().removeClass('current').end().addClass('current');
    });
    // 上移
    $('div[imtype="mobile_pannel"]').on('click', '[imtype="mp_up"]', function(){
        var _parents = $(this).parents('.module:first');
        _rs = mDataMove(_parents.index(), 0);
        if (!_rs) {
            return false;
        }
        _parents.clone().insertBefore(_parents.prev()).end().remove();
        mbPannelInit();
    });
    // 下移
    $('div[imtype="mobile_pannel"]').on('click', '[imtype="mp_down"]', function(){
        var _parents = $(this).parents('.module:first');
        _rs = mDataMove(_parents.index(), 1);
        if (!_rs) {
            return false;
        }
        _parents.clone().insertAfter(_parents.next()).end().remove();
        mbPannelInit();
    });
    // 删除
    $('div[imtype="mobile_pannel"]').on('click', '[imtype="mp_del"]', function(){
        var _parents = $(this).parents('.module:first');
        mDataRemove(_parents.index());
        _parents.remove();
        mbPannelInit();
    });
    // 编辑
    $('div[imtype="mobile_pannel"]').on('click', '[imtype="mp_edit"]', function(){
        $('a[imtype="meat_cancel"]').click();
        var _parents = $(this).parents('.module:first');
        var _val = _parents.find('.text-div').html();
        $(this).parents('.module:first').html('')
            .append('<div class="content"></div>').find('.content')
            .append('<div class="imsc-mea-text" imtype="mea_txt"></div>')
            .find('div[imtype="mea_txt"]')
            .append('<p id="meat_content_count" class="text-tip">')
            .append('<textarea class="textarea valid" data-old="' + _val + '" imtype="meat_content">' + _val + '</textarea>')
            .append('<div class="button"><a class="imsc-btn imsc-btn-blue" imtype="meat_edit_submit" href="javascript:void(0);">确认</a><a class="imsc-btn ml10" imtype="meat_edit_cancel" href="javascript:void(0);">取消</a></div>')
            .append('<a class="text-close" imtype="meat_edit_cancel" href="javascript:void(0);">X</a>')
            .find('#meat_content_count').html('').end()
            .find('textarea[imtype="meat_content"]').unbind().charCount({
                allowed: 500,
                warning: 50,
                counterContainerID: 'meat_content_count',
                firstCounterText:   '还可以输入',
                endCounterText:     '字',
                errorCounterText:   '已经超出'
            });
    });
    // 编辑提交
    $('div[imtype="mobile_pannel"]').on('click', '[imtype="meat_edit_submit"]', function(){
        var _parents = $(this).parents('.module:first');
        var _c = _parents.find('textarea[imtype="meat_content"]').val();
        var _cl = _c.length;
        if (_cl == 0 || _cl > 500) {
            return false;
        }
        _data = new Object;
        _data.type = 'text';
        _data.value = _c;
        _rs = mDataReplace(_parents.index(), _data);
        if (!_rs) {
            return false;
        }
        _parents.html('').append('<div class="tools"><a imtype="mp_up" href="javascript:void(0);">上移</a><a imtype="mp_down" href="javascript:void(0);">下移</a><a imtype="mp_edit" href="javascript:void(0);">编辑</a><a imtype="mp_del" href="javascript:void(0);">删除</a></div>')
            .append('<div class="content"><div class="text-div">' + _c + '</div></div>')
            .append('<div class="cover"></div>');

    });
    // 编辑关闭
    $('div[imtype="mobile_pannel"]').on('click', '[imtype="meat_edit_cancel"]', function(){
        var _parents = $(this).parents('.module:first');
        var _c = _parents.find('textarea[imtype="meat_content"]').attr('data-old');
        _parents.html('').append('<div class="tools"><a imtype="mp_up" href="javascript:void(0);">上移</a><a imtype="mp_down" href="javascript:void(0);">下移</a><a imtype="mp_edit" href="javascript:void(0);">编辑</a><a imtype="mp_del" href="javascript:void(0);">删除</a></div>')
        .append('<div class="content"><div class="text-div">' + _c + '</div></div>')
        .append('<div class="cover"></div>');
    });
    // 初始化控制面板
    mbPannelInit = function(){
        $('div[imtype="mobile_pannel"]')
            .find('a[imtype^="mp_"]').show().end()
            .find('.module')
            .first().find('a[imtype="mp_up"]').hide().end().end()
            .last().find('a[imtype="mp_down"]').hide();
    }
    // 添加文字按钮，显示文字输入框
    $('a[imtype="mb_add_txt"]').click(function(){
        $('div[imtype="mea_txt"]').show();
        $('a[imtype="meai_cancel"]').click();
    });
    $('div[imtype="mobile_editor_area"]').find('textarea[imtype="meat_content"]').unbind().charCount({
        allowed: 500,
        warning: 50,
        counterContainerID: 'meat_content_count',
        firstCounterText:   '还可以输入',
        endCounterText:     '字',
        errorCounterText:   '已经超出'
    });
    // 关闭 文字输入框按钮
    $('a[imtype="meat_cancel"]').click(function(){
        $(this).parents('div[imtype="mea_txt"]').find('textarea[imtype="meat_content"]').val('').end().hide();
    });
    // 提交 文字输入框按钮
    $('a[imtype="meat_submit"]').click(function(){
        var _c = toTxt($('textarea[imtype="meat_content"]').val());
        var _cl = _c.length;
        if (_cl == 0 || _cl > 500) {
            return false;
        }
        _data = new Object;
        _data.type = 'text';
        _data.value = _c;
        _rs = mDataInsert(_data);
        if (!_rs) {
            return false;
        }
        $('<div class="module m-text"></div>')
            .append('<div class="tools"><a imtype="mp_up" href="javascript:void(0);">上移</a><a imtype="mp_down" href="javascript:void(0);">下移</a><a imtype="mp_edit" href="javascript:void(0);">编辑</a><a imtype="mp_del" href="javascript:void(0);">删除</a></div>')
            .append('<div class="content"><div class="text-div">' + _c + '</div></div>')
            .append('<div class="cover"></div>').appendTo('div[imtype="mobile_pannel"]');
        
        $('a[imtype="meat_cancel"]').click();
    });
    // 添加图片按钮，显示图片空间文字
    $('a[imtype="mb_add_img"]').click(function(){
        $('a[imtype="meat_cancel"]').click();
        $('div[imtype="mea_img"]').show().load('index.php?act=store_album&op=pic_list&item=mobile');
    });
    // 关闭 图片选择
    $('div[imtype="mobile_editor_area"]').on('click', 'a[imtype="meai_cancel"]', function(){
        $('div[imtype="mea_img"]').html('');
    });
    // 插图图片
    insert_mobile_img = function(data){
        _data = new Object;
        _data.type = 'image';
        _data.value = data;
        _rs = mDataInsert(_data);
        if (!_rs) {
            return false;
        }
        $('<div class="module m-image"></div>')
            .append('<div class="tools"><a imtype="mp_up" href="javascript:void(0);">上移</a><a imtype="mp_down" href="javascript:void(0);">下移</a><a imtype="mp_rpl" href="javascript:void(0);">替换</a><a imtype="mp_del" href="javascript:void(0);">删除</a></div>')
            .append('<div class="content"><div class="image-div"><img src="' + data + '"></div></div>')
            .append('<div class="cover"></div>').appendTo('div[imtype="mobile_pannel"]');
        
    }
    // 替换图片
    $('div[imtype="mobile_pannel"]').on('click', 'a[imtype="mp_rpl"]', function(){
        $('a[imtype="meat_cancel"]').click();
        $('div[imtype="mea_img"]').show().load('index.php?act=store_album&op=pic_list&item=mobile&type=replace');
    });
    // 插图图片
    replace_mobile_img = function(data){
        var _parents = $('div.m-image.current');
        _parents.find('img').attr('src', data);
        _data = new Object;
        _data.type = 'image';
        _data.value = data;
        mDataReplace(_parents.index(), _data);
    }
    // 插入数据
    mDataInsert = function(data){
        _m_data = mDataGet();		
        _m_data.push(data);
        return mDataSet(_m_data);
    }
    // 数据移动 
    // type 0上移  1下移
    mDataMove = function(index, type) {
        _m_data = mDataGet();
        _data = _m_data.splice(index, 1);
        if (type) {
            index += 1;
        } else {
            index -= 1;
        }
        _m_data.splice(index, 0, _data[0]);
        return mDataSet(_m_data);
    }
    // 数据移除
    mDataRemove = function(index){
        _m_data = mDataGet();
        _m_data.splice(index, 1);     // 删除数据
        return mDataSet(_m_data);
    }
    // 替换数据
    mDataReplace = function(index, data){
        _m_data = mDataGet();
        _m_data.splice(index, 1, data);
        return mDataSet(_m_data);
    }
    // 获取数据
    mDataGet = function(){
        _m_body = $('input[name="m_body"]').val();
        if (_m_body == '' || _m_body == 'false') {			
            var _m_data = new Array;
        } else {
            eval('var _m_data = ' + _m_body);
        }		
        return _m_data;
    }
    // 设置数据
    mDataSet = function(data){
        var _i_c = 0;
        var _i_c_m = 20;
        var _t_c = 0;
        var _t_c_m = 5000;
        var _sign = true;
        $.each(data, function(i, n){
            if (n.type == 'image') {
                _i_c += 1;
                if (_i_c > _i_c_m) {
                    alert('只能选择'+_i_c_m+'张图片');
                    _sign = false;
                    return false;
                }
            } else if (n.type == 'text') {
                _t_c += n.value.length;
                if (_t_c > _t_c_m) {
                    alert('只能输入'+_t_c_m+'个字符');
                    _sign = false;
                    return false;
                }
            }
        });
        if (!_sign) {
            return false;
        }
        $('span[imtype="img_count_tip"]').html('还可以选择图片<em>' + (_i_c_m - _i_c) + '</em>张');
        $('span[imtype="txt_count_tip"]').html('还可以输入<em>' + (_t_c_m - _t_c) + '</em>字');
        _data = JSON.stringify(data);
        $('input[name="m_body"]').val(_data);
        return true;
    }
    // 转码
    toTxt = function(str) {
        var RexStr = /\<|\>|\"|\'|\&/g
        str = str.replace(RexStr, function(MatchStr) {
            switch (MatchStr) {
            case "<":
                return "&lt;";
                break;
            case ">":
                return "&gt;";
                break;
            case "\"":
                return "&quot;";
                break;
            case "'":
                return "&#39;";
                break;
            case "&":
                return "&amp;";
                break;
            default:
                break;
            }
        })
        return str;
    }
});
/* 删除商品  add by yangbaiyan */
function bundling_operate_delete(o, id){
	o.remove();
	check_bundling_data_length();
	$('li[imtype="'+id+'"]').children(':last').html('<a href="JavaScript:void(0);" onclick="bundling_goods_add($(this))" class="imsc-btn-mini imsc-btn-green"><i class="fa fa-plus"></i>添加到赠品列表</a>');
	//count_cost_price_sum();
}
/*  add by yangbaiyan*/
function check_bundling_data_length(){
	if ($('tbody[imtype="bundling_data"] tr').length == 1) {
	    $('tbody[imtype="bundling_data"]').children(':first').show();
	}
}

// 计算商品库存
function computeStock(){
    // 库存
    var _stock = 0;
    $('input[data_type="stock"]').each(function(){
        if($(this).val() != ''){
            _stock += parseInt($(this).val());
        }
    });
    $('input[name="g_storage"]').val(_stock);
}

// 计算价格
function computePrice(){
    // 计算最低价格
    var _price = 0;var _price_sign = false;
    $('input[data_type="price"]').each(function(){
        if($(this).val() != '' && $(this)){
            if(!_price_sign){
                _price = parseFloat($(this).val());
                _price_sign = true;
            }else{
                _price = (parseFloat($(this).val())  > _price) ? _price : parseFloat($(this).val());
            }
        }
    });
    $('input[name="g_price"]').val(number_format(_price, 2));

    discountCalculator();       // 计算折扣
}

// 计算折扣
function discountCalculator() {
    var _price = parseFloat($('input[name="g_price"]').val());
    var _marketprice = parseFloat($('input[name="g_marketprice"]').val());
    if((!isNaN(_price) && _price != 0) && (!isNaN(_marketprice) && _marketprice != 0)){
        var _discount = parseInt(_price/_marketprice*100);
        $('input[name="g_discount"]').val(_discount);
    }
}

//计算供货商成本
function g_costpriceCalculator() {
	g_tradeCalculator();
}

//计算批发价
function g_tradeCalculator() {
  var _price = parseFloat($('input[name="g_price"]').val());
  var _collect = parseFloat($('input[name="g_collect"]').val());
  var _commisrate = parseFloat($('input[name="commis_rate"]').val()); //该分类扣点
  if(isNaN(_commisrate) && _commisrate == 0){
  	_commisrate = 6; //如果后台没有设置分类扣点，则默认为6%
  }
  if((!isNaN(_price) && _price != 0)){
  	if((!isNaN(_collect) && _collect != 0)){ //商家供货价是否设置或是否等于0
  		//批发价：= 商家供货价  + 会员价*平台扣点
      	var _tradeprice = parseFloat(_collect + _price*(_commisrate*0.01));
      	$('input[name="g_tradeprice"]').attr('value',_tradeprice);
      	$('input[name="g_costprice"]').attr('value',_tradeprice); //商家供货成本价 暂时屏蔽
      	//三级分销利润：= 会员价  - 批发价
      	var _gainprice = parseFloat(_price - _tradeprice);
        $('input[name="g_gain"]').attr('value',_gainprice.toFixed(2));
  	}else{
  		$('input[name="g_collect"]').attr('value',0.00); //商家供货价
  		$('input[name="g_costprice"]').attr('value',parseFloat(_price*(_commisrate*0.01)).toFixed(2)); //商家供货价
  		$('input[name="g_tradeprice"]').attr('value',parseFloat(_price*(_commisrate*0.01)).toFixed(2));  //批发价  		
  		$('input[name="g_gain"]').attr('value',parseFloat(_price-(_price*(_commisrate*0.01))).toFixed(2));
  	}
  }else{
  	$('input[name="g_costprice"]').attr('value',0.00); //商家供货成本价
  	$('input[name="g_tradeprice"]').attr('value',0.00); //批发价
  	$('input[name="g_collect"]').attr('value',0.00); //商家供货价
  	$('input[name="g_gain"]').attr('value',0.00); //三级分销利润
  }
}

//批量设置吊牌价 zhangchao 
function batchmpriceCalculator() {
	
	var _type = "marketprice";
	var _marketprice = parseFloat($('input[name="g_marketprice"]').val());
	//if(confirm("是否也更新商品属性里的吊牌价！"))
	//{
		$('input[data_type="marketprice" ]').attr('value',_marketprice);
	//}
}

//批量设置吊牌价 zhangchao 
function batchpriceCalculator() {
	
	var _type = "price";
	var _price = parseFloat($('input[name="g_price"]').val());
	if(confirm("是否也更新商品属性里的销售价！"))
	{
		$('input[data_type="price" ]').attr('value',_price);
	}
}
//批量设置批发价 zhangchao 和加入市场有关
function batchtpriceCalculator() {
	
	var _type = "tradeprice";
	var _tradeprice = parseFloat($('input[name="g_tradeprice"]').val());
	if(confirm("是否也更新商品属性里的批发价！"))
	{
		$('input[data_type="tradeprice" ]').attr('value',_tradeprice);
	}
}

//计算利润
function gainCalculator() {
	
	var _price = parseFloat($('input[name="g_price"]').val()); //会员价 
    var _tradeprice = parseFloat($('input[name="g_tradeprice"]').val()); //批发价 = 商家供货成本
    
    var _costprice = parseFloat($('input[name="g_costprice"]').val());//商家供货成本 
    if(_tradeprice>_costprice){
    	if((!isNaN(_price) && _price != 0)&&(!isNaN(_tradeprice) && _tradeprice != 0)){
        	//平台利润：= 会员价 - 批发价
        	var _gainprice = parseFloat(_price - _tradeprice).toFixed(2);
            $('input[name="g_gain"]').attr('value',_gainprice);
        }else{
        	g_tradeCalculator();
        }
    }else{
    	$('input[name="g_tradeprice"]').attr('value',_costprice);
    }
}

//获得商品名称
function getBrandName() {
    var brand_name = $('select[name="b_id"] > option:selected').html();
    $('input[name="b_name"]').attr('value',brand_name);
}
//修改相关的颜色名称
function change_img_name(Obj){
     var S = Obj.parents('li').find('input[type="checkbox"]');
     S.attr('value',Obj.val());
     var V = $('tr[imtype="file_tr_'+S.attr('im_type')+'"]');
     V.find('span[imtype="pv_name"]').html(Obj.val());
     V.find('input[type="file"]').attr('name', Obj.val());
}
// 商品属性
function attr_selected(){
    $('select[im_type="attr_select"] option:selected').each(function(){
        id = $(this).attr('im_type');
        name = $(this).parents('select').attr('attr').replace(/__IM__/g,id);
        $(this).parents('select').attr('name',name);
    });
}
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
/* 插入商品图片 */
function insert_img(name, src) {
    $('input[imtype="goods_image"]').attr('value',name);
    $('img[imtype="goods_image"]').attr('src',src);
}
/* 插入商品广告图片 */
function insert_advpic(name, src) {
    $('input[imtype="goods_advpic"]').attr('value',name);
    $('img[imtype="goods_advpic"]').attr('src',src);
}

/* 插入编辑器 */
function insert_editor(file_path) {
    KE.appendHtml('goods_body', '<img src="'+ file_path + '">');
}

function setArea(area1, area2) {
    $('#province_id').val(area1).change();
    $('#city_id').val(area2);
}

// 插入品牌
function insertBrand(param, search) {
    $('div[imtype="brandList"]').show();
    $('div[imtype="noBrandList"]').hide();
    var _ul = $('ul[imtype="brand_list"]');
    _ul.html('');
    if ($.isEmptyObject(param)) {
        $('div[imtype="brandList"]').hide();
        $('div[imtype="noBrandList"]').show().find('strong').html(search);
        return false;
    }
    $.each(param, function(i, n){
        $('<li data-id="' + n.brand_id + '" data-name="' + n.brand_name + '"><em>' + n.brand_initial + '</em>' + n.brand_name + '</li>').appendTo(_ul);
    });

    //搜索品牌列表滚条绑定
    $('div[imtype="brandList"]').perfectScrollbar('update');
}