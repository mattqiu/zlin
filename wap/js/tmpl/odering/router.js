//底部的导航栏
$("#order").click(function() {
	window.location.href = WapSiteUrl + "/tmpl/ordering/";
});
$("#goods").click(function() {
	window.location.href = WapSiteUrl + "/tmpl/ordering/dinghuohui02_3.html";
});

//各个页面之间的跳转
$("#goback").click(function(){
	console.log("返回上一层")
	window.history.back(-1); 
})
$("#ordermanager").click(function() {
	window.location.href = WapSiteUrl + "/tmpl/ordering/orderforgoods.html";
});