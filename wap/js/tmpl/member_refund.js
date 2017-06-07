$(function() {
    var key = getCookie("key");
    var t = new qmscrollLoad;
    t.loadInit({
        url: ApiUrl + "/index.php?act=member_refund&op=get_refund_list",
        getparam: {
            key: key
        },
        tmplid: "refund-list-tmpl",
        containerobj: $("#refund-list"),
        iIntervalId: true,
        data: {
            WapSiteUrl: WapSiteUrl
        }
    })
});