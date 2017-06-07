var order_id, order_goods_id, goods_pay_price, goods_num;
$(function() {
    var key = getCookie("key");
    if (!key) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html"
    }
    $.getJSON(ApiUrl + "/index.php?act=member_refund&op=refund_form", {
        key: key,
        order_id: getQueryString("order_id"),
        order_goods_id: getQueryString("order_goods_id")
    },
    function(result) {
        result.datas.WapSiteUrl = WapSiteUrl;
        $("#order-info-container").html(template.render("order-info-tmpl", result.datas));
        order_id = result.datas.order.order_id;
        order_goods_id = result.datas.goods.order_goods_id;
        var html = "";
        for (var r in result.datas.reason_list) {
            html += '<option value="' + r + '">' + result.datas.reason_list[r].reason_info + "</option>"
        }
        $("#refundReason").append(html);
		
        goods_pay_price = result.datas.goods.goods_pay_price;
        $('input[name="refund_amount"]').val(goods_pay_price);		
        $("#returnAble").html("￥" + goods_pay_price);
		
        goods_num = result.datas.goods.goods_num;
        $('input[name="goods_num"]').val(goods_num);
        $("#goodsNum").html("最多" + goods_num + "件");
		
        $('input[name="refund_pic"]').ajaxUploadImage({
            url: ApiUrl + "/index.php?act=member_refund&op=upload_pic",
            data: {
                key: key
            },
            start: function(e) {
                e.parent().after('<div class="upload-loading"><i></i></div>');
                e.parent().siblings(".pic-thumb").remove()
            },
            success: function(e, result) {
                checkLogin(result.login);
                if (result.error) {
                    e.parent().siblings(".upload-loading").remove();
                    $.sDialog({
                        skin: "red",
                        content: "图片尺寸过大！",
                        okBtn: false,
                        cancelBtn: false
                    });
                    return false
                }
                e.parent().after('<div class="pic-thumb"><img src="' + result.datas.pic + '"/></div>');
                e.parent().siblings(".upload-loading").remove();
                e.parents("a").next().val(result.datas.file_name)
            }
        });
        $(".btn-l").click(function() {
            var formdata = $("form").serializeArray();
            var postdata = {};
            postdata.key = key;
            postdata.order_id = order_id;
            postdata.order_goods_id = order_goods_id;
            postdata.refund_type = 2;
            for (var r = 0; r < formdata.length; r++) {
                postdata[formdata[r].name] = formdata[r].value
            }
            if (isNaN(parseFloat(postdata.refund_amount)) || parseFloat(postdata.refund_amount) > parseFloat(goods_pay_price) || parseFloat(postdata.refund_amount) == 0) {
                $.sDialog({
                    skin: "red",
                    content: "退款金额不能为空，或不能超过可退金额",
                    okBtn: false,
                    cancelBtn: false
                });
                return false
            }
            if (postdata.buyer_message.length == 0) {
                $.sDialog({
                    skin: "red",
                    content: "请填写退款说明",
                    okBtn: false,
                    cancelBtn: false
                });
                return false
            }
            if (isNaN(postdata.goods_num) || parseInt(postdata.goods_num) == 0 || parseInt(postdata.goods_num) > parseInt(goods_num)) {
                $.sDialog({
                    skin: "red",
                    content: "退货数量不能为空，或不能超过可退数量",
                    okBtn: false,
                    cancelBtn: false
                });
                return false
            }
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=member_refund&op=refund_post",
                data: postdata,
                dataType: "json",
                async: false,
                success: function(result) {
                    checkLogin(result.login);
                    if (result.error) {
                        $.sDialog({
                            skin: "red",
                            content: result.error,
                            okBtn: false,
                            cancelBtn: false
                        });
                        return false
                    }
                    window.location.href = WapSiteUrl + "/tmpl/member/member_return.html"
                }
            })
        })
    })
});