$(function() {
    var key = getCookie("key");
    if (!key) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    var order_id = getQueryString("order_id");
    $.getJSON(ApiUrl + "/index.php?act=member_evaluate&op=index", {
        key: key,
        order_id: order_id
    },
    function(result) {
        if (result.error) {
            $.sDialog({
                skin: "red",
                content: result.error,
                okBtn: false,
                cancelBtn: false
            });
            return false
        }
        var html = template.render("member-evaluation-script", result.datas);
        $("#member-evaluation-div").html(html);
        $('input[name="file"]').ajaxUploadImage({
            url: ApiUrl + "/index.php?act=sns_album&op=file_upload",
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
                e.parent().after('<div class="pic-thumb"><img src="' + result.datas.file_url + '"/></div>');
                e.parent().siblings(".upload-loading").remove();
                e.parents("a").next().val(result.datas.file_name)
            }
        });
        $(".star-level").find("i").click(function() {
            var e = $(this).index();
            for (var a = 0; a < 5; a++) {
                var r = $(this).parent().find("i").eq(a);
                if (a <= e) {
                    r.removeClass("star-level-hollow").addClass("star-level-solid")
                } else {
                    r.removeClass("star-level-solid").addClass("star-level-hollow")
                }
            }
            $(this).parent().next().val(e + 1)
        });
        $(".btn-l").click(function() {
            var formdata = $("form").serializeArray();
            var postdata = {};
            postdata.key = key;
            postdata.order_id = order_id;
            for (var t = 0; t < formdata.length; t++) {
                postdata[formdata[t].name] = formdata[t].value
            }
            $.ajax({
                type: "post",
                url: ApiUrl + "/index.php?act=member_evaluate&op=save",
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
                    window.location.href = WapSiteUrl + "/tmpl/member/order_list.html"
                }
            })
        })
    })
});