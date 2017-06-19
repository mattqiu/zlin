//底部的导航栏
$(function() {
	$("#order").click(function() {
		window.location.href = WapSiteUrl + "/tmpl/ordering/";
	});
	$("#goods").click(function() {
		window.location.href = WapSiteUrl + "/tmpl/ordering/dinghuohui02_3.html";
	});
	$("#display").click(function(){
		window.location.href = WapSiteUrl + "/tmpl/ordering/analys.html";
	})
	$("#goback").click(function() {
		console.log("返回上一层")
		window.history.back(-1);
	})
	$("#ordermanager").click(function() {
		window.location.href = WapSiteUrl + "/tmpl/ordering/orderforgoods.html";
	});
})