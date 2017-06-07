var key = getCookie("key");
$(function() {
    var e = new qmscrollLoad;
    e.loadInit({
        url: ApiUrl + "/index.php?act=member_goodsbrowse&op=browse_list",
        getparam: {
            key: key
        },
        tmplid: "viewlist_data",
        containerobj: $("#viewlist"),
        iIntervalId: true,
        data: {
            WapSiteUrl: WapSiteUrl
        }
    });
    $("#clearbtn").click(function() {
        $.ajax({
            type: "get",
            url: ApiUrl + "/index.php?act=member_goodsbrowse&op=browse_clearall",
            data: {
                key: key,
				goods_id:'all'
            },
            dataType: "json",
            async: false,
            success: function(e) {
                if (e.done == 'true') {
                    location.href = WapSiteUrl + "/tmpl/member/views_list.html"
                } else {
                    $.sDialog({
                        skin: "red",
                        content: e.msg,
                        okBtn: false,
                        cancelBtn: false
                    })
                }
            }
        })
    })
});