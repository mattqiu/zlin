$(document).ready(function(){
	//列表下拉
	$('img[im_type="flex"]').click(function(){
		var status = $(this).attr('status');
		if(status == 'open'){
			var pr = $(this).parent('td').parent('tr');
			var id = $(this).attr('fieldid');
			var deep = $(this).attr('fielddeep');
			var obj = $(this);		
			$(this).attr('status','none');
			//ajax
			$.ajax({
				url: 'index.php?act=member_consumercard&op=my_subccard&ajax=1&parent_id='+id+'&deep='+deep,
				dataType: 'json',
				success: function(data){
					var src='';
					var recommendstr='';
					var curr_state;
					for(var i = 0; i < data.length; i++){
						if (data[i].cc_state == 0){
							curr_state = '未发放';
						}else if(data[i].cc_state == 1){
							curr_state = '平台已发放';
						}else if(data[i].cc_state == 2){
							curr_state = '运营中心已发放';
						}else if(data[i].cc_state == 3){
							curr_state = '推广员已发放';
						}else if(data[i].cc_state == 10){
							curr_state = '已激活';
						}else if(data[i].cc_state == 20){
							curr_state = '已冻结';
						}else{
							curr_state = '卡异常';
						}
						var tmp_vertline = "<img class='preimg' src='"+SHOP_SKINS_URL+"/images/treetable/vertline.gif'/>";
						src += "<tr class='"+pr.attr('class')+" row"+id+"'>";
						src += "<td class='subordinate_name'>";
						for(var tmp_i=0; tmp_i < (data[i].deep-1); tmp_i++){
							src += tmp_vertline;
						}
						//图片
						if(data[i].have_child > 0){
							src += " <img fieldid='"+data[i].cc_sn+"' fielddeep='"+data[i].deep+"' status='open' im_type='flex' src='"+SHOP_SKINS_URL+"/images/treetable/tv-expandable.gif' />";
						}else{
							src += " <img fieldid='"+data[i].cc_sn+"' fielddeep='"+data[i].deep+"' status='none' im_type='flex' src='"+SHOP_SKINS_URL+"/images/treetable/tv-item.gif' />";
						}
						src += data[i].cc_sn;						
						src += "</td>";
						//真实姓名
						src += "<td>"+data[i].member_name+"</td>";
						//门派
						src += "<td>"+data[i].class_name+"</td>";
						//状态						
						src += "<td>"+curr_state+"</td>";
						//保费收益
						src += "<td>"+data[i].safe_totals+"</td>";
						//返利收益
						src += "<td>"+data[i].rebate_totals+"</td>";
						src += "</tr>";
					}
					//插入
					pr.after(src);
					obj.attr('status','close');
					obj.attr('src',obj.attr('src').replace("tv-expandable","tv-collapsable"));
					$('img[im_type="flex"]').unbind('click');
					//重现初始化页面
					$.getScript(RESOURCE_SITE_URL+"/js/jquery.subccard_tree.js");
					$.getScript(RESOURCE_SITE_URL+"/js/member.js");
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
	})
});