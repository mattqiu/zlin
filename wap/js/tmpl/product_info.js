$(function() {
    var o = getQueryString("goods_id");
    var iMall_extension = getCookie("iMall_extension");
    var ge = getQueryString("extension");
    if(iMall_extension){
    	iMall_extension = "&extension="+iMall_extension;
    }
    if(!iMall_extension&&ge){
    	iMall_extension = "&extension="+ge;
    }
    $.ajax({
        url: ApiUrl + "/index.php?act=goods&op=goods_body",
        data: {
            goods_id: o
        },
        type: "get",
        success: function(o) {
            $(".fixed-tab-pannel").html(o);
            var store_id = $("#store_id").val();
            if(store_id){            	
            	$('#backto_home').attr('href','store.html?store_id='+store_id+iMall_extension);
            	$('#backto_member').attr('href','member/member.html?store_id='+store_id+iMall_extension);
            	
            }
        }
    });
    $("#goodsDetail").click(function() {
        window.location.href = WapSiteUrl + "/tmpl/product_detail.html?goods_id=" + o+iMall_extension;
    });
    $("#goodsBody").click(function() {
    	var store_id = $("#store_id").val();
    	if(store_id){
    		window.location.href = WapSiteUrl + "/tmpl/product_info.html?goods_id=" + o +"&store_id="+store_id+iMall_extension;
    	}else{
    		window.location.href = WapSiteUrl + "/tmpl/product_info.html?goods_id=" + o+iMall_extension;
    	}
    });
    $("#goodsEvaluation").click(function() {
    	var store_id = $("#store_id").val();
    	if(store_id){
    		window.location.href = WapSiteUrl + "/tmpl/product_eval_list.html?goods_id=" +o+"&store_id="+store_id+iMall_extension;
    	}else{
    		window.location.href = WapSiteUrl + "/tmpl/product_eval_list.html?goods_id=" +o+iMall_extension;
    	}
    });
   
    $(".fixed-tab-pannel").swipeLeft(function(){
    	//alert('swipeLeft');//ok
    	var store_id = $("#store_id").val();
    	if(store_id){
    		window.location.href = WapSiteUrl + "/tmpl/product_eval_list.html?goods_id=" +o+"&store_id="+store_id+iMall_extension;
    	}else{
    		window.location.href = WapSiteUrl + "/tmpl/product_eval_list.html?goods_id=" +o+iMall_extension;
    	}
    });
    $(".fixed-tab-pannel").swipeRight(function(){
    	//alert('swipeRight');//ok
    	window.location.href = WapSiteUrl + "/tmpl/product_detail.html?goods_id=" + o+iMall_extension;
    });
    
//    $(window).scroll(function() {
//        if ($(window).scrollTop() + $(window).height() > $(document).height() - 1) {
//        	setTimeout(jumpurl(),8000);
//        }
//    });
    
});
function jumpurl(){
	var o = getQueryString("goods_id");
	var store_id = getQueryString("store_id");
	if(store_id){
		window.location.href = WapSiteUrl + "/tmpl/product_eval_list.html?goods_id=" +o+"&store_id="+store_id+iMall_extension;
	}else{
		window.location.href = WapSiteUrl + "/tmpl/product_eval_list.html?goods_id=" +o+iMall_extension;
	}
}  