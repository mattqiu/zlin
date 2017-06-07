$(function() {
    var e = getCookie("key");
    if (!e) {
        location.href = "login.html"
    }
    $("#add_address").on("click",function() {
    	var UA = navigator.appVersion.toLowerCase();
        var isqqBrowser = (UA.split("qq/").length > 1) ? true : false;
        var iswxBrowser = (UA.split("micromessenger/").length > 1) ? true : false;
	    if (iswxBrowser == true){	
	    	location.href = ApiUrl + "/control/member_getaddress.php";
	    }else{
	    	location.href = "address_opera.html";
	    }
    });
    
    function s() {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=member_address&op=address_list",
            data: {
                key: e
            },
            dataType: "json",
            success: function(e) {
                checkLogin(e.login);
                if (e.datas.address_list == null) {
                    return false
                }
                var s = e.datas;
                var t = template.render("saddress_list", s);
                $("#address_list").empty();
                $("#address_list").append(t);
                $(".deladdress").click(function() {
                    var e = $(this).attr("address_id");
                    $.sDialog({
                        skin: "block",
                        content: "确认删除吗？",
                        okBtn: true,
                        cancelBtn: true,
                        okFn: function() {
                            a(e)
                        }
                    })
                })
            }
        })
    }
    s();
    function a(a) {
        $.ajax({
            type: "post",
            url: ApiUrl + "/index.php?act=member_address&op=address_del",
            data: {
                address_id: a,
                key: e
            },
            dataType: "json",
            success: function(e) {
                checkLogin(e.login);
                if (e) {
                    s()
                }
            }
        })
    }
});
function add(){
    	var UA = navigator.appVersion.toLowerCase();
        var isqqBrowser = (UA.split("qq/").length > 1) ? true : false;
        var iswxBrowser = (UA.split("micromessenger/").length > 1) ? true : false;
	    if (iswxBrowser == true){	
	    	location.href = ApiUrl + "/control/member_getaddress.php";
	    }else{
	    	location.href = "address_opera.html";
	    }
    }