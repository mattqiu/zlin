$(function() {
    var key = getCookie('key');
    //if (key == '') {
    //    window.location.href = WapSiteUrl+'/tmpl/member/login.html';
    //    return;
    //}

    var page = 5;
    var curpage = 1;
    var hasMore = true;

    var voucher_state = getQueryString('voucher_state');
    if (!voucher_state) voucher_state = 1;
	var id = getQueryString('id');

    function initPage(page,curpage) {
        $.ajax({
            type:'get',
            url:ApiUrl+"/index.php?act=promotion&op=voucher_list&page="+page+"&curpage="+curpage,
            data:{key:key,voucher_state:voucher_state,id:id},
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
				
				data.extension = result.extension;
                var html = template.render('voucher-list-tmpl', data);
                $("#voucher_list").html(html);
				
				//代金券兑换功能
                $("[im_type='exchangebtn']").live('click',function(){				
					
    	            var data_str = $(this).attr('data-param');
	                eval( "data_str = "+data_str);
					
					var msg = "您确定使用"+data_str.points+"积分兑换1张"+data_str.store+"面值"+data_str.price+"元的代金券？";
					$.sDialog({
                        skin:"red",
                        content:msg,
                        okBtn:true,
                        cancelBtn:true,
					    okFn: function() {VoucherExchange(data_str.vid);}
				    });
	                return false;
                });

                //下一页
                $(".next-page").click(nextPage);

                //上一页
                $(".pre-page").click(prePage);

                $(window).scrollTop(0);
            },
			error: function(){
			    ShowGetDataError();
		    }
        });
    }

    // 初始化页面
    initPage(page, curpage);
	
	// 下一页
    function VoucherExchange(vid) {
		var key = getCookie('key');
        if (key == '') {
            window.location.href = WapSiteUrl+'/tmpl/member/login.html';
            return;
        }
		$.ajax({		
			type:'post',
            url: ApiUrl+'/index.php?act=promotion&op=voucherexchange',
            data:{key:key,vid:vid},
            dataType:'json',
		    success:function(result){
				if (!CheckDataSucceed(result)){return false;}				
				
				$.sDialog({
                    skin:"red",
                    content:result.datas.exchange_info,
                    okBtn:true,
                    cancelBtn:false,
					okFn: function() {location.href = WapSiteUrl+'/tmpl/member/voucher_list.html';}
				});
			},
			error: function(){
			    ShowGetDataError();
		    }
		});
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
