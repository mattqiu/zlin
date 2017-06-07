var goods_id = getQueryString("goods_id");
$(function() {
	var store_id = getQueryString("store_id");
    var o = new qmscrollLoad;
    var iMall_extension = getCookie("iMall_extension");
    var ge = getQueryString("extension");
    if(iMall_extension){
    	iMall_extension = "&extension="+iMall_extension;
    }
    if(!iMall_extension&&ge){
    	iMall_extension = "&extension="+ge;
    }
    o.loadInit({
        url: ApiUrl + "/index.php?act=goods&op=goods_evaluate",
        getparam: {
            goods_id: goods_id
        },
        tmplid: "product_ecaluation_script",
        containerobj: $("#product_evaluation_html"),
        iIntervalId: true,
        callback: function() {
            callback()
        }
    });
    if(store_id){
    	//修改进入会员中心 zhangchao
		$("#backto_home").attr('href','store.html?store_id='+ store_id);
		$("#backto_member").attr('href','member/member.html?store_id='+store_id);
    }
    $("#goodsDetail").click(function() {
        window.location.href = WapSiteUrl + "/tmpl/product_detail.html?goods_id=" + goods_id + iMall_extension;
    });
    $("#goodsBody").click(function() {
    	if(store_id){
    		window.location.href = WapSiteUrl + "/tmpl/product_info.html?goods_id=" + goods_id+"&store_id="+store_id + iMall_extension;
    	}else{
    		window.location.href = WapSiteUrl + "/tmpl/product_info.html?goods_id=" + goods_id + iMall_extension;
    	}
    });
    $("#goodsEvaluation").click(function() {
    	if(store_id){
    		window.location.href = WapSiteUrl + "/tmpl/product_eval_list.html?goods_id=" + goods_id+"&store_id="+store_id + iMall_extension;
    	}else{
    		window.location.href = WapSiteUrl + "/tmpl/product_eval_list.html?goods_id=" + goods_id + iMall_extension;
    	}
    });
    $(".qm-tag-nav").find("a").click(function() {
        var i = $(this).attr("data-state");
        o.loadInit({
            url: ApiUrl + "/index.php?act=goods&op=goods_evaluate",
            getparam: {
                goods_id: goods_id,
                type: i
            },
            tmplid: "product_ecaluation_script",
            containerobj: $("#product_evaluation_html"),
            iIntervalId: true,
            callback: function() {
                callback()
            }
        });
        $(this).parent().addClass("selected").siblings().removeClass("selected")
    })
});
function callback() {
    $(".goods_geval").on("click", "a", function() {
        var o = $(this).parents(".goods_geval");
        o.find(".qm-bigimg-layout").removeClass("hide");
        var i = o.find(".pic-box");
        o.find(".close").click(function() {
            o.find(".qm-bigimg-layout").addClass("hide")
        });
        if (i.find("li").length < 2) {
            return
        }
        Swipe(i[0], {
            speed: 400,
            auto: 3e3,
            continuous: false,
            disableScroll: false,
            stopPropagation: false,
            callback: function(o, i) {
                $(i).parents(".qm-bigimg-layout").find("div").last().find("li").eq(o).addClass("cur").siblings().removeClass("cur")
            },
            transitionEnd: function(o, i) {}
        })
    })
}