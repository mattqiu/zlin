$(function() {
    var key = getCookie('key');
    if(!key){
        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
        return;
    }

    var page = pagesize;
    var curpage = 1;
    var hasMore = true;

    var ccard_state = getQueryString('state');
    if (!ccard_state) ccard_state = "distri";    

    function initPage(page,curpage) {
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?act=member_consumercard&op=my_"+ccard_state+"&page="+page+"&curpage="+curpage,
            data:{key:key,ccard_state:ccard_state},
            dataType:'json',
            success:function(result){
                if (!CheckDataSucceed(result)){return false;}
				
                var data = result.datas;
                data.hasmore = result.hasmore; //是不是可以用下一页的功能，传到页面里去判断下一页是否可以用
                data.WapSiteUrl = WapSiteUrl; //页面地址
                data.curpage = curpage; //当前页，判断是否上一页的disabled是否显示
                data.ApiUrl = ApiUrl;
                data.key = getCookie('key');

                template.helper('tsToDateString', function (t) {
                    var d = new Date(parseInt(t) * 1000);
                    var s = '';
                    s += d.getFullYear() + '年';
                    s += (d.getMonth() + 1) + '月';
                    s += d.getDate() + '日';
                    return s;
                });
				
				data.WapSiteUrl = WapSiteUrl;				
			    var html = template.render('ccard-info-tmpl', data);
                $("#ccard-info").html(html);

				var html = template.render('ccard-tab-tmpl', data);
                $("#ccard-tab").html(html);

                var html = template.render(ccard_state+'-list-tmpl', data);
                $("#ccard-list").html(html);

				$("[data-state='"+ccard_state+"']").addClass('selected');
				
                //下一页
                $(".next-page").click(nextPage);

                //上一页
                $(".pre-page").click(prePage);				

                $(window).scrollTop(0);
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
		var key = getCookie('key');
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
						if (!CheckDataSucceed(result)){return false;}						
						
						$.sDialog({
                            skin:"red",
                            content:result.datas.info,
                            okBtn:true,
                            cancelBtn:false
	                    });
				    },
				    error: function(){
			            ShowGetDataError();
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