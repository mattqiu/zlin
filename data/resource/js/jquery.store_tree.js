$(document).ready(function(){
	//列表下拉
	$('img[im_type="flex"]').click(function(){
		var status = $(this).attr('status');
		if(status == 'open'){
			var pr = $(this).parent('td').parent('tr');
			var id = $(this).attr('fieldid');
			var obj = $(this);
			$(this).attr('status','none');
			//ajax
			$.ajax({
				url: 'index.php?act=store&op=store_child&ajax=1&parent_id='+id,
				dataType: 'json',
				success: function(data){
					var src='';
					var recommendstr='';
					for(var i = 0; i < data.length; i++){
						var tmp_vertline = "<img class='preimg' src='"+ADMIN_SKINS_URL+"/images/vertline.gif'/>";
						src += "<tr class='"+pr.attr('class')+" row"+id+"'>";
						src += "<td class='w36'>";
						//图片
						if(data[i].branch_op == 1){
							src += " <img fieldid='"+data[i].gc_id+"' status='open' im_type='flex' src='"+ADMIN_SKINS_URL+"/images/tv-expandable.gif' />";
						}else{
							src += " <img fieldid='"+data[i].gc_id+"' status='none' im_type='flex' src='"+ADMIN_SKINS_URL+"/images/tv-item.gif' />";
						}				
						src += "</td><td>";						
						for(var tmp_i=1; tmp_i < (2); tmp_i++){
							src += tmp_vertline;
						}
						if(data[i].branch_op == 1){
							src += " <img fieldid='"+data[i].gc_id+"' status='open' im_type='flex' src='"+ADMIN_SKINS_URL+"/images/tv-item1.gif' />";
						}else{
							src += " <img fieldid='"+data[i].gc_id+"' status='none' im_type='flex' src='"+ADMIN_SKINS_URL+"/images/tv-expandable1.gif' />";
						}
						src += "<a href='index.php?act=show_store&op=index&store_id="+data[i].store_id+"'>"+data[i].store_name+"</a>";
						src += "</td>";
						src += "<td>"+data[i].member_name+"</td>";
						src += "<td>"+data[i].seller_name+"</td>";						
						src += "<td class='align-center'>"+data[i].branch_op+"</td>";
						src += "<td class='align-center'>"+data[i].branch_limit+"</td>";
						src += "<td class='align-center'>"+data[i].extension_op+"</td>";
						src += "<td class='align-center'>"+data[i].promotion_limit+"</td>";
						src += "<td class='align-center'>"+data[i].saleman_limit+"</td>";
						
						if (data[i].is_own_shop == 1){//自营店铺
							src += "<td class='align-center'>"+data[i].bind_all_gc_name+"</td>";
						}else{//普通店铺
						    src += "<td class='align-center'>"+data[i].grade_name+"</td>";						
						    src += "<td class='align-center'>"+data[i].payment_method_name+"</td>";
						    src += "<td class='nowarp align-center'>"+data[i].store_end_time+"</td>";						    
						}
						src += "<td class='align-center w72'>"+data[i].store_state+"</td>";
						//操作
						src += "<td class='align-center w200'>";
						if (data[i].is_own_shop == 1){//自营店铺
						    src += "<a href='index.php?act=ownshop&op=edit&id="+data[i].store_id+"'>编辑</a>&nbsp;&nbsp;";
							if (data[i].bind_all_gc!=1){
								src += "<a href='index.php?act=ownshop&op=bind_class&id="+data[i].store_id+"'>经营类目</a>&nbsp;&nbsp;";
							}
							if (data[i].can_del==1){
							    src += "<a href='index.php?act=ownshop&op=del&id="+data[i].store_id+"' onclick=\"return confirm('此操作不可逆转！确定删除？');\">删除</a>";
						    }
						}else{
						    src += "<a href='index.php?act=store&op=store_joinin_detail&member_id="+data[i].member_id+"'>查看</a>&nbsp;&nbsp;";
						    src += "<a href='index.php?act=store&op=store_edit&store_id="+data[i].store_id+"'>编辑</a>&nbsp;&nbsp;";
						    src += "<a href='index.php?act=store&op=store_bind_class&store_id="+data[i].store_id+"'>经营类目</a>&nbsp;&nbsp;";
						    if (data[i].remindRenewal==1){
							    src += "<a href='index.php?act=store&op=remind_renewal&store_id="+data[i].store_id+"'>提醒续费</a>&nbsp;&nbsp;";
						    }
							if (data[i].can_del==1){
							    src += "<a href='javascript:void(0)' onclick=\"if(confirm('你确定要删除吧?')){location.href='index.php?act=store&op=store_del&store_id="+data[i].store_id+"';}\">删除</a>";
						    }
						}						
						src += "</td>";
						src += "</tr>";
					}
					//插入
					pr.after(src);
					obj.attr('status','close');
					obj.attr('src',obj.attr('src').replace("tv-expandable","tv-collapsable"));
					$('img[im_type="flex"]').unbind('click');
					//重现初始化页面
                    $.getScript(RESOURCE_SITE_URL+"/js/jquery.edit.js");
					$.getScript(RESOURCE_SITE_URL+"/js/jquery.store_tree.js");
					$.getScript(RESOURCE_SITE_URL+"/js/admincp.js");
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