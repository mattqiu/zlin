$(function() {
    var key = getCookie('key');
    if(!key){
        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
        return;
    }

    var page = pagesize;
    var curpage = 1;
    var hasMore = true;

    var extension_state = getQueryString('extension_state');
    if (!extension_state) extension_state = "achievement";    

    function initPage(page,curpage) {
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?act=member_extension&op=my_"+extension_state+"&page="+page+"&curpage="+curpage,
            data:{key:key,extension_state:extension_state},
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
			    var html = template.render('extension-info-tmpl', data);
                $("#extension-info").html(html);

				var html = template.render('extension-tab-tmpl', data);
                $("#extension-tab").html(html);

                var html = template.render(extension_state+'-list-tmpl', data);
                $("#extension-list").html(html);

				$("[data-state='"+extension_state+"']").addClass('selected');
				
				//初始化统计数据
				//initStatisticsData(extension_state,data);

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
	
	//初始化统计数据
	function initStatisticsData(extension_state,data) {		
		if (extension_state=="achievement"){
			if (data.extension_list.statistics_all) {
				$("#Statistics_all").html("<span>累积业绩<br>"+data.extension_list.statistics_all.amounts+"</span>")				
			}
			if (data.extension_list.statistics_curr) {
				$("#Statistics_curr").html("<span>当前业绩<br>"+data.extension_list.statistics_curr.amounts+"</span>")				
			}
		}else if (extension_state=="income"){
			if (data.extension_list.statistics_curr) {
				$("#Statistics_all").html("<span>待收益<br>"+data.extension_list.statistics_curr.commis+"</span>");
				if (data.extension_list.statistics_curr.commis>0){
					$("#Statistics_curr").html("<span><a href='javascript:void(0)' onclick='apply_commission("+data.extension_list.statistics_curr.commis+");'>申请提佣</a></span>")
				}else{
					$("#Statistics_curr").hide();
				}
			}
						
		}else if (extension_state=="balance"){
			$("#Statistics_all").hide();
			if (data.extension_list.statistics_all) {
				$("#Statistics_curr").html("<span>已结算<br>"+data.extension_list.statistics_all.shares+"</span>")				
			}			
		}
	}

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
