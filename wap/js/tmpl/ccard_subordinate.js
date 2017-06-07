$(function() {
    var key = getCookie('key');
    if(!key){
        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
        return;
    }

    var page = pagesize;
    var curpage = 1;
    var hasMore = true; 

    function initPage(page,curpage) {
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?act=member_consumercard&op=my_subccard&page="+page+"&curpage="+curpage,
            data:{key:key},
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

                var html = template.render('subordinate-list-tmpl', data);
                $("#ccard-list").html(html);
				
                //下一页
                $(".next-page").click(nextPage);

                //上一页
                $(".pre-page").click(prePage);
				//列表下拉
			    $('img[im_type="flex"]').click(LoadChilds);

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
	
	//列表下拉
	function LoadChilds() {
		var status = $(this).attr('status');
		if(status == 'open'){
			var pr = $(this).parent('td').parent('tr');
			var id = $(this).attr('fieldid');
			var deep = $(this).attr('fielddeep');
			var obj = $(this);
			$(this).attr('status','none');

			$.ajax({	
			    type:'post',	
                url: ApiUrl+'/index.php?act=member_consumercard&op=my_subccard&ajax=1&parent_id='+id+'&deep='+deep,
                data:{key:key},
                dataType:'json',
				success: function(data){
					var src='';
					var recommendstr='';
					var mc_name = '';
					for(var i = 0; i < data.length; i++){
						var tmp_vertline = "<img class='preimg' src='"+WapSiteUrl+"/images/treetable/vertline.gif'/>";
						src += "<tr class='"+pr.attr('class')+" row"+id+"'>";
						src += "<td class='subordinate_name'>";
						for(var tmp_i=0; tmp_i < (data[i].deep-1); tmp_i++){
							src += tmp_vertline;
						}
						//图片
						if(data[i].have_child > 0){
							src += " <img fieldid='"+data[i].cc_sn+"' fielddeep='"+data[i].deep+"' status='open' im_type='flex' src='"+WapSiteUrl+"/images/treetable/tv-expandable.gif' />";
						}else{
							src += " <img fieldid='"+data[i].cc_sn+"' fielddeep='"+data[i].deep+"' status='none' im_type='flex' src='"+WapSiteUrl+"/images/treetable/tv-item.gif' />";
						}
						src += data[i].cc_sn;						
						src += "</td>";
						src += "<td>"+data[i].member_name+"</td>";
						//累计业绩
						src += "<td>"+data[i].safe_totals+"</td>";
						//本期业绩
						src += "<td>"+data[i].rebate_totals+"</td>";
						src += "</tr>";
					}
					//插入
					pr.after(src);
					obj.attr('status','close');
					obj.attr('src',obj.attr('src').replace("tv-expandable","tv-collapsable"));
					$('img[im_type="flex"]').unbind('click');
					//重现初始化页面
					$('img[im_type="flex"]').click(LoadChilds);
				},
				error: function(){
					alert('获取信息失败');
				}
			});
		}
		if(status == 'close'){
			$(".row"+$(this).attr('fieldid')).remove();
			$(this).attr('src',$(this).attr('src').replace("tv-collapsable","tv-expandable"));
			$(this).attr('status','open');
		}
	}
});
