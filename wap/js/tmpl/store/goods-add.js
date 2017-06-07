clicpshow();

$('.save_img-btn').click(function () {
    $('#clipArea').empty();
    $('html,body').css({'height':'100%','overflow':'hidden'});
    paizhao();

});

function paizhao(){
var select='upload_img';
var prew='img-view';
    var fn=function (new_img) {
        $.ajax({
            url: SITEURL + '/index.php?act=store_goods_add&op=image_upload&upload_type=uploadedfile',
            type: 'post',
            data: {name:'goods_image',goods_image:new_img},
            dataType: 'json',
            success: function (rs) {
                $('html,body').css({'height':'auto','overflow':'visible'});
                $('#img-view').attr('src', rs.thumb_name);
                $('#image_path').val( rs.name);
                $('.loading-box').hide();
            },
        })
    }
clicpfn(select,prew,fn,300,300);
}
$('.good-number').html($('.goods-input').val().length);
$('.banner-number').html($('.selle-banner').val().length);
$('.goods-input').keyup(function () {
   var length=$(this).val().length;
    var val=$(this).val();
    if(length>60){
        length=60;
        $('.goods-input').val(val.substr(0,60));
    }
    if(length<0){
        length=0;
    }
    $('.good-number').html(length);
})
$('.selle-banner').keyup(function () {
    var length=$(this).val().length;
    var val=$(this).val();
    if(length>140){
        length=140;
        $('.selle-banner').val(val.substr(0,140));
    }
    if(length<0){
        length=0;
    }
    $('.banner-number').html(length);
})
$('.goods-desc').click(function () {
    $('html,body').css({'height':'100%','overflow':'hidden'});
    $('.editor-box').animate({right:0},500);

});

$('#gcategory').click(function () {
    var href=$('.gcategory_href').val();
    $('.gcategory_box').animate({'right':'0'},500);
  $('html,body').css({'overflow':'scroll','overflow-x':'hidden'});
});


//市场价，重量，价格清空
$('.g_price').keydown(function () {
    inputkey($(this))
});
$('.g_marketprice').keydown(function () {
    inputkey($(this))
});
$('.goods_weight').keydown(function () {
    inputkey($(this))
});
function inputkey(a) {
    if(a.val()=='0.00'||a.val()==''||a.val()=='0.0'||a.val()=='0'){
       a.val('');
    }
}

$('.storeClassification-collect').click(function () {
    $('.store_list2').hide();
    $('.store_list1').show();
    $('.storeClassification').animate({'right':'0'},500);
})































//裁剪查件
function clicpshow(){
    var clicpshow='<img src="../../images/default.png" id="imghiddle" style="position:fixed;top:-150%;height:100px;width:auto;"/><div class="clicp-box"><div>'+
        '<div id="clipArea"></div></div>'+
        '<img id="preview_size_fake"/><span id="swh"></span>'+
        '<div class="clipbtn-box"><button type="button" id="clipBtn">完成</button>'+
        '<b class="clear"></b></div></div>';
    $('body').append(clicpshow);
}

function clicpfn(select,prew,fn,truewidth,trueheight){
    var _width=$(window).width();
    var _height=$(window).height();
    $("#clipArea").width(_width).height(_height);
    $("#clipArea").photoClip({
        width: truewidth, // 截取区域的宽度
        height: trueheight, // 截取区域的高度
        file: "#"+select, // 上传图片的<input type="file">控件的选择器或者DOM对象
        //view: "#preview", // 显示截取后图像的容器的选择器或者DOM对象
        ok: "#clipBtn", // 确认截图按钮的选择器或者DOM对象
        //outputType: "jpg", // 指定输出图片的类型，可选 "jpg" 和 "png" 两种种类型，默认为 "jpg"
        strictSize: true, // 是否严格按照截取区域宽高裁剪。默认为false，表示截取区域宽高仅用于约束宽高比例。如果设置为true，则表示截取出的图像宽高严格按照截取区域宽高输出
        loadStart: function(file) {

        }, // 开始加载的回调函数。this指向 fileReader 对象，并将正在加载的 file 对象作为参数传入
        loadComplete: function(src) {
            $('.loading-box').show();
            $('.clicp-box').show()
        }, // 加载完成的回调函数。this指向图片对象，并将图片地址作为参数传入
        loadError: function(event) {}, // 加载失败的回调函数。this指向 fileReader 对象，并将错误事件的 event 对象作为参数传入
        clipFinish: function(dataURL) {
            new_img=dataURL;
            // new_img=jic.compress(pre,80);
            fn(new_img);
        }, // 裁剪完成的回调函数。this指向图片对象，会将裁剪出的图像数据DataURL作为参数传入
        imgchange:function (dataURL) {
            var pre=document.getElementById(prew);
            pre.src=dataURL.base64;
            new_img=jic.compress(pre,80);
            pre.src=new_img;
            fn(new_img,pre);
            $('html,body').css({'height':'auto','overflow':'visible'});
        }
        //不剪裁压缩
    });


    /**
     * Detecting vertical squash in loaded image.
     * Fixes a bug which squash image vertically while drawing into canvas for some images.
     * This is a bug in iOS6 devices. This function from https://github.com/stomita/ios-imagefile-megapixel
     *
     */
    function detectVerticalSquash(img) {
        var iw = img.naturalWidth, ih = img.naturalHeight;
        var canvas = document.createElement('canvas');
        canvas.width = 1;
        canvas.height = ih;
        var ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);
        var data = ctx.getImageData(0, 0, 1, ih).data;
        // search image edge pixel position in case it is squashed vertically.
        var sy = 0;
        var ey = ih;
        var py = ih;
        while (py > sy) {
            var alpha = data[(py - 1) * 4 + 3];
            if (alpha === 0) {
                ey = py;
            } else {
                sy = py;
            }
            py = (ey + sy) >> 1;
        }
        var ratio = (py / ih);
        return (ratio===0)?1:ratio;
    }

    /**
     * A replacement for context.drawImage
     * (args are for source and destination).
     */
    function drawImageIOSFix(ctx, img, sx, sy, sw, sh, dx, dy, dw, dh) {
        var vertSquashRatio = detectVerticalSquash(img);
        // Works only if whole image is displayed:
        // ctx.drawImage(img, sx, sy, sw, sh, dx, dy, dw, dh / vertSquashRatio);
        // The following works correct also when only a part of the image is displayed:
        ctx.drawImage(img, sx * vertSquashRatio, sy * vertSquashRatio,
            sw * vertSquashRatio, sh * vertSquashRatio,
            dx, dy, dw, dh );
    }


    $("#clipArea").parents('.collex').removeAttr('style');
    $('#clipBtn').click(function () {
        $('html,body').css({'height':'auto','overflow':'visible'});
        $('.clicp-box').hide();
    })
    var jic = {
        /**
         * Receives an Image Object (can be JPG OR PNG) and returns a new Image Object compressed
         * @param {Image} source_img_obj The source Image Object
         * @param {Integer} quality The output quality of Image Object
         * @return {Image} result_image_obj The compressed Image Object
         */

        compress: function(source_img_obj, quality, output_format){
            var result_image_obj='';
            var mime_type = "image/jpeg";
            if(output_format!=undefined && output_format=="png"){
                mime_type = "image/png";
            }
            var cvs = document.createElement('canvas');
            var _width=source_img_obj.naturalWidth;
            var _height=source_img_obj.naturalHeight;
            cvs.width =300;
            cvs.height =300;
            var abs=Math.abs(_width-_height)/2;
            var w=300;
            var x=0;
            var y=0;
            if(abs>1){
                if(_width>_height){
                    w=_height;
                    x=abs;
                    y=0;
                }else if(_width<_height){
                    w=_width;
                    x=0;
                    y=abs;
                }
            }else{
                x=0;
                y=0;
                w=_width;
            }

            drawImageIOSFix(cvs.getContext("2d"),source_img_obj,x, y,w,w,0,0,truewidth,trueheight);
            var newImageData = cvs.toDataURL(mime_type, quality/100);
            return newImageData;
            result_image_obj = new Image();
            result_image_obj.src = newImageData;
            return result_image_obj;
        }

    }

    function onPreviewLoad(sender) {
        autoSizePreview(sender, sender.offsetWidth, sender.offsetHeight);
    }
    function autoSizePreview(objPre, originalWidth, originalHeight) {
        var zoomParam = clacImgZoomParam(200, 200, originalWidth, originalHeight);
        objPre.style.width = zoomParam.width + 'px';
        objPre.style.height = zoomParam.height + 'px';
        objPre.style.marginTop = zoomParam.top + 'px';
        objPre.style.marginLeft = zoomParam.left + 'px';
    }
    function clacImgZoomParam(maxWidth, maxHeight, width, height) {
        var param = {width: width, height: height, top: 0, left: 0};
        if (width > maxWidth || height > maxHeight) {
            rateWidth = width / maxWidth;
            rateHeight = height / maxHeight;
            if (rateWidth > rateHeight) {
                param.width = maxWidth;
                param.height = height / rateWidth;
            } else {
                param.width = width / rateHeight;
                param.height = maxHeight;
            }
        }
        param.left = (maxWidth - param.width) / 2;
        param.top = (maxHeight - param.height) / 2;
        return param;
    }


}
