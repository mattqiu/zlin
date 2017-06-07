$(function(){
	var key = getcookie('key');
	if(key==''){
		location.href = 'login.html';
	}
	
	var page = pagesize;
    var curpage = 1;
    var hasMore = true;

    var extension_state = GetQueryString('extension_state');
    if (!extension_state) extension_state = "achievement";   
	
	function initPage(page,curpage) {
	    $.ajax({
		    type:'get',
		    url:ApiUrl+"/index.php?act=member_consumercard&op=my_consumercard",	
		    data:{key:key},
		    dataType:'json',
		    //jsonp:'callback',
		    success:function(result){
			    checklogin(result.login);
			    if(!result.datas.error){
			        var data = result.datas;			
			
			        data.WapSiteUrl = WapSiteUrl;
			        var html = template.render('ccard-info-tmpl', data);
                    $("#ccard-info").html(html);
				
				    data.hasmore = result.hasmore; //是不是可以用下一页的功能，传到页面里去判断下一页是否可以用
                    data.WapSiteUrl = WapSiteUrl; //页面地址
                    data.curpage = curpage; //当前页，判断是否上一页的disabled是否显示
                    data.ApiUrl = ApiUrl;
                    data.key = getcookie('key');

                    template.helper('tsToDateString', function (t) {
                        var d = new Date(parseInt(t) * 1000);
                        var s = '';
                        s += d.getFullYear() + '年';
                        s += (d.getMonth() + 1) + '月';
                        s += d.getDate() + '日';
                        return s;
                    });

				    var html = template.render('extension-tab-tmpl', data);
                    $("#extension-tab").html(html);

			    }else{
				    $.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:true,
                        cancelBtn:false,
					    okFn: function() {location.href = WapSiteUrl+'/tmpl/member/member.html';}
				    });
			    }			
			    return false;
		    },
		    error: function(){
			    alert('获取信息失败');
		    }	  
	    });
	}
	// 初始化页面
    initPage(page, curpage);
	
	// 下一页
    function nextPage() {
        var hasMore = $(this).attr("has_more");
        if (hasMore == "true") {
            curpage++;
            initPage(page, curpage);
        }
    }

    // 上一页
    function prePage() {
        if (curpage > 1) {
            $(this).removeClass("disabled");
            curpage--;
            initPage(page, curpage);
        }
    }
});

// 申请提佣
function apply_commission(commis) {
	if (commis>0){
		var key = getcookie('key');
        $.sDialog({
            skin:"red",
            content:'本次申请提佣金额：'+commis+'元，确定吗?',
            okBtn:true,
            cancelBtn:true,
		    okFn: function() {
				$.ajax({
                    type:'post',
                    url:ApiUrl+"/index.php?act=member_extension&op=apply_commission",
                    data:{key:key},
                    dataType:'json',
                    success:function(result){
						if(!result.datas.error){
							$.sDialog({
                                skin:"red",
                                content:result.datas.info,
                                okBtn:true,
                                cancelBtn:false
	                        });
						}else{
							$.sDialog({
                                skin:"red",
                                content:result.datas.error,
                                okBtn:true,
                                cancelBtn:false
	                        });
						}
				    }
				});
			}
	    });
	}else{
		$.sDialog({
            skin:"red",
            content:'无佣金可申请提取！',
            okBtn:true,
            cancelBtn:false
	    });
	}
}