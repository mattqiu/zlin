$(function() {
    var key = getCookie("key");
    if (!key) {
        window.location.href = WapSiteUrl + "/tmpl/member/login.html";
        return
    }
    $.getJSON(ApiUrl + "/index.php?act=member_index&op=my_asset", {
        key: key
    },
    function(result) {
        checkLogin(result.login);
    })
});