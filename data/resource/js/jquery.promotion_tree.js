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
				url: 'index.php?act=seller_promotion&op=promotion_list&ajax=1&parent_id='+id+'&deep='+deep,
				dataType: 'json',
				success: function(data){
					var src='';
					var recommendstr='';
					var mc_name = '';
					var child_name = '';
					for(var i = 0; i < data.length; i++){
						var tmp_vertline = "<img class='preimg' src='"+SHOP_SKINS_URL+"/images/treetable/vertline.gif'/>";
						src += "<tr class='"+pr.attr('class')+" row"+id+"'>";
						src += "<td class='subordinate_name'>";
						for(var tmp_i=0; tmp_i < (data[i].deep-1); tmp_i++){
							src += tmp_vertline;
						}
						//图片
						if(data[i].have_child > 0){
							src += " <img fieldid='"+data[i].member_id+"' fielddeep='"+data[i].deep+"' status='open' im_type='flex' src='"+SHOP_SKINS_URL+"/images/treetable/tv-expandable.gif' />";
						}else{
							src += " <img fieldid='"+data[i].member_id+"' fielddeep='"+data[i].deep+"' status='none' im_type='flex' src='"+SHOP_SKINS_URL+"/images/treetable/tv-item.gif' />";
						}
						src += data[i].member_name;						
						src += "</td>";
						//身份类型
						if (data[i].mc_id == 5){
							mc_name = '股东';
							child_name = '首席';
						}else if(data[i].mc_id == 4){
							mc_name = '首席';
							child_name = '协理';
						}else if(data[i].mc_id == 3){
							mc_name = '协理';
							child_name = '经理';
						}else if(data[i].mc_id == 2){
							mc_name = '经理';
							child_name = '下级推广员';
						}else{
							mc_name = '推广员';
							child_name = '下级推广员';
						}							
						src += "<td>"+mc_name+"</td>";
						//累计业绩
						src += "<td>"+data[i].total_sales+"</td>";
						//累计佣金
						src += "<td>"+data[i].total_commis+"</td>";
						//本期业绩
						src += "<td>"+data[i].curr_sales+"</td>";
						//本期佣金
						src += "<td>"+data[i].curr_commis+"</td>";
						//操作
						src += "<td>";
						src += "<a href='javascript:void(0)' im_type='dialog' dialog_title='查看"+mc_name+"信息' dialog_id='my_promotion_info' dialog_width='480' uri='index.php?act=seller_promotion&op=promotion_info&promotion_id="+data[i].member_id+"' title='查看"+mc_name+"信息'>查看</a>|";						
						src += "<a href='javascript:void(0)' im_type='dialog' dialog_title='修改"+mc_name+"信息' dialog_id='my_category_add' dialog_width='480' uri='index.php?act=seller_promotion&op=promotion_edit&promotion_id="+data[i].member_id+"' title='修改"+mc_name+"信息'>修改</a>|";                        
						src += "<a href='javascript:void(0)' im_type='dialog' dialog_title='添加"+child_name+"' dialog_id='my_promotion_add' dialog_width='480' uri='index.php?act=seller_promotion&op=promotion_add&parent_id="+data[i].member_id+"' title='添加"+child_name+"'>添加</a>|";				  
						src += "<a href='index.php?act=seller_promotion&op=promotion_detail&promotion_id="+data[i].member_id+"' title='查看业绩明细'>明细</a>|"; 
						src += "<a href='javascript:void(0)' onclick=\"javascript:ajax_get_confirm('真的要删除吗?','index.php?act=seller_promotion&op=promotion_del&promotion_id="+data[i].member_id+"')\">删除</a>";
						src += "</td>";
						src += "</tr>";
					}
					//插入
					pr.after(src);
					obj.attr('status','close');
					obj.attr('src',obj.attr('src').replace("tv-expandable","tv-collapsable"));
					$('img[im_type="flex"]').unbind('click');
					//重现初始化页面
					$.getScript(RESOURCE_SITE_URL+"/js/jquery.promotion_tree.js");
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