$(function() {
    var key = getcookie('key');
    if (key == '') {
        window.location.href = WapSiteUrl+'/tmpl/member/login.html';
        return;
    }

    var page = pagesize;
    var curpage = 1;
    var hasMore = true;

    var extension_state = GetQueryString('extension_state');
    if (!extension_state) extension_state = "subordinate";    

    function initPage(page,curpage) {
        $.ajax({
            type:'post',
            url:ApiUrl+"/index.php?act=member_extension&op=my_"+extension_state+"&page="+page+"&curpage="+curpage,
            data:{key:key,extension_state:extension_state},
            dataType:'json',
            success:function(result){
                checklogin(result.login); //检测是否登录了
                var data = result.datas;
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
				
				data.WapSiteUrl = WapSiteUrl;
			    var html = template.render('extension-info-tmpl', data);
                $("#extension-info").html(html);

				var html = template.render('extension-tab-tmpl', data);
                $("#extension-tab").html(html);

                var html = template.render(extension_state+'-list-tmpl', data);
                $("#extension-list").html(html);

				$("[data-state='"+extension_state+"']").addClass('current');
				
				//初始化统计数据
				//initStatisticsData(extension_state,data);

                //下一页
                $(".next-page").click(nextPage);

                //上一页
                $(".pre-page").click(prePage);
				//列表下拉
				if (extension_state=="subordinate"){
				    $('img[im_type="flex"]').click(LoadChilds);
				}
				
				//操作
				if (extension_state=="apply"){
				    $('a[im_type="verify"]').click(Verify_op);
					$('a[im_type="apply_del"]').click(Apply_Del);
				}

                $(window).scrollTop(0);
            }
        });
    }

    // 初始化页面
    initPage(page, curpage);
	
	//初始化统计数据
	function initStatisticsData(extension_state,data) {		
		if (extension_state=="subordinate"){
			if (data.extension_list.my_subordinate) {
				$("#Statistics_all").html("<span>下级<br>"+data.extension_list.my_subordinate+"人</span>")				
			}
			if (data.extension_list.my_subordinate_all) {
				$("#Statistics_curr").html("<span>下线<br>"+data.extension_list.my_subordinate_all+"人</span>")				
			}
			
		}else if (extension_state=="apply"){			
			if (data.extension_list.apply_all) {
				$("#Statistics_all").html("<span>总申请<br>"+data.extension_list.apply_all+"人</span>")				
			}
			if (data.extension_list.apply_curr) {
				$("#Statistics_curr").html("<span>待审核<br>"+data.extension_list.apply_curr+"人</span>")				
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
	
	//列表下拉
	function LoadChilds() {
		var status = $(this).attr('status');
		if(status == 'open'){
			var pr = $(this).parent('td').parent('tr');
			var id = $(this).attr('fieldid');
			var deep = $(this).attr('fielddeep');
			var obj = $(this);
			$(this).attr('status','none');
			//ajax
			$.ajax({		
				type:'post',
                url: ApiUrl+'/index.php?act=member_extension&op=my_subordinate&ajax=1&parent_id='+id+'&deep='+deep,
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
							src += " <img fieldid='"+data[i].member_id+"' fielddeep='"+data[i].deep+"' status='open' im_type='flex' src='"+WapSiteUrl+"/images/treetable/tv-expandable.gif' />";
						}else{
							src += " <img fieldid='"+data[i].member_id+"' fielddeep='"+data[i].deep+"' status='none' im_type='flex' src='"+WapSiteUrl+"/images/treetable/tv-item.gif' />";
						}
						src += data[i].member_name;						
						src += "</td>";
						//身份类型
						if (data[i].mc_id == 5){
							mc_name = '股东';
						}else if(data[i].mc_id == 4){
							mc_name = '首席';
						}else if(data[i].mc_id == 3){
							mc_name = '协理';
						}else if(data[i].mc_id == 2){
							mc_name = '经理';
						}else{
							mc_name = '推广员';
						}							
						src += "<td>"+mc_name+"</td>";
						//累计业绩
						src += "<td>"+data[i].total_sales+"</td>";
						//本期业绩
						src += "<td>"+data[i].curr_sales+"</td>";
						src += "</tr>";
					}
					//插入
					pr.after(src);
					obj.attr('status','close');
					obj.attr('src',obj.attr('src').replace("tv-expandable","tv-collapsable"));
					$('img[im_type="flex"]').unbind('click');
					//重现初始化页面
					$('img[im_type="flex"]').click(LoadChilds);
					//$.getScript(RESOURCE_SITE_URL+"/js/jquery.subordinate_tree.js");
					//$.getScript(RESOURCE_SITE_URL+"/js/member.js");
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
	
	//审核
	function Verify_op() {

		var id = $(this).attr('fieldid');
		var verify = $(this).attr('verify');
		//ajax
		$.ajax({		
			type:'post',
            url: ApiUrl+'/index.php?act=member_extension&op=verify_save',
            data:{key:key,id:id,verify:verify},
            dataType:'json',
		    success:function(result){
				if(!result.datas.error){
					$.sDialog({
                        skin:"red",
                        content:result.datas.verify_info,
                        okBtn:true,
                        cancelBtn:false,
					    okFn: function() {location.reload();}
				    });
				}else{
					$.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:true,
                        cancelBtn:false,
				    });
				}
			},
			error: function(){
				alert('审核操作失败');
			}
		});
	}	

    //删除申请
	function Apply_Del() {

		var id = $(this).attr('fieldid');
		//ajax
		$.ajax({		
			type:'get',
            url: ApiUrl+'/index.php?act=member_extension&op=apply_del',
            data:{key:key,id:id},
            dataType:'json',
		    success:function(result){
				if(!result.datas.error){
					$.sDialog({
                        skin:"red",
                        content:result.datas.del_info,
                        okBtn:true,
                        cancelBtn:false,
					    okFn: function() {location.reload();}
				    });
				}else{
					$.sDialog({
                        skin:"red",
                        content:result.datas.error,
                        okBtn:true,
                        cancelBtn:false,
				    });
				}
			},
			error: function(){
				alert('删除操作失败');
			}
		});
	}	
});
