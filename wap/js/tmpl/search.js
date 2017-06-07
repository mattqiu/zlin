$(function() {
    Array.prototype.unique = function() {
        var e = [];
        for (var t = 0; t < this.length; t++) {
            if (e.indexOf(this[t]) == -1) e.push(this[t])
        }
        return e
    };
    var keyword = decodeURIComponent(getQueryString("keyword"));
    if (keyword) {
        $("#keyword").val(keyword);
        writeClear($("#keyword"))
    }
    $("#keyword").on("input", function() {
        var keyword = $.trim($("#keyword").val());
        var store_id = getQueryString("store_id");
        if (keyword == "") {
            $("#search_tip_list_container").hide()
        } else {
            $.getJSON(ApiUrl + "/index.php?act=goods&op=auto_complete", {term: $("#keyword").val()}, function(result) {
                if (!result.error) {					
                    var data = result.datas;
                    data.WapSiteUrl = WapSiteUrl;
                    if (data.list.length > 0) {
                        $("#search_tip_list_container").html(template.render("search_tip_list_script", data)).show()
                    } else {
                        $("#search_tip_list_container").hide()
                    }
                }else {
                    $("#search_tip_list_container").hide()
                }
            })
        }
    });
    $(".input-del").click(function() {
        $(this).parent().removeClass("write").find("input").val("")
    });
    template.helper("$buildUrl", buildUrl);
	
    $.getJSON(ApiUrl + "/index.php?act=index&op=search_key_list", function(result) {
        var data = result.datas;
        data.WapSiteUrl = WapSiteUrl;
        $("#hot_list_container").html(template.render("hot_list", data));
        $("#search_his_list_container").html(template.render("search_his_list", data));
    });
	
    $("#header-nav").click(function() {
        if ($("#keyword").val() == "") {
            window.location.href = buildUrl("keyword", getCookie("deft_key_value") ? getCookie("deft_key_value") : "", "")
        } else {
            window.location.href = buildUrl("keyword", $("#keyword").val(), "")
        }
    })
});
//清除历史记录
function clear_his_key(){
	$.getJSON(ApiUrl + "/index.php?act=index&op=search_history_clear", {}, function(result) {			
        if (!result.error) {
            $("#search_his_list_container").remove();
        }
    })
}