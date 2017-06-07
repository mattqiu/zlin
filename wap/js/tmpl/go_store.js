ShowMainMenu = true;

$(function (){
	var store_id = getQueryString('store_id');	

	$("input[name=keyword]").val(escape(getQueryString('keyword')));
    	$("input[name=store_id]").val(getQueryString('store_id'));  
	
	$.ajax({
        url: ApiUrl + "/index.php?act=store&op=publicTop&store_id="+store_id,
        type: 'get',
        dataType: 'json',
        success: function(result) {
			if (!CheckDataSucceed(result)){return false;}
						
            var data = result.datas;
			var html = template.render('pub_top', data);
            $("#main_Top").html(html);
				
		    $('.ico_03').click(function(){
                var keyword = encodeURIComponent($('#keywordtop').val());
                location.href = WapSiteUrl+'/tmpl/product_list.html?keyword='+keyword;
            });
			
			TouchSlide({ 
	            slideCell:"#focus",
	            titCell:".hd ul", //开启自动分页 autoPage:true ，此时设置 titCell 为导航元素包裹层
	            mainCell:".bd ul", 
	            effect:"leftLoop", 
	            autoPlay:true,//自动播放
	            autoPage:true //自动分页
	        });	
        },
		error: function(){
			ShowGetDataError();
		}
    });
	
	$.ajax({
        url: ApiUrl + "/index.php?act=store&op=decoration&store_id="+store_id,
        type: 'get',
        dataType: 'json',
        success: function(result) {
			if (!CheckDataSucceed(result)){return false;}
			
            var data = result.datas;
            var html = '';			

            $.each(data, function(k, v) {
                $.each(v, function(kk, vv) {
                    switch (kk) {
                        case 'adv_list':
                        case 'home3':
                            $.each(vv.item, function(k3, v3) {
                                vv.item[k3].url = buildUrl(v3.type, v3.data, result.extension);
                            });
                            break;

                        case 'home1':
                            vv.url = buildUrl(vv.type, vv.data, result.extension);
                            break;

                        case 'home2':
                        case 'home4':
                            vv.square_url = buildUrl(vv.square_type, vv.square_data, result.extension);
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data, result.extension);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data, result.extension);
                            break;
						case 'goods':
						    vv.extension = result.extension;
						    break;
                    }					
                    html += template.render(kk, vv);
                    return false;
                });
            });

            $("#main-container").html(html);

            $('.adv_list').each(function() {
                if ($(this).find('.item').length < 2) {
                    return;
                }

                Swipe(this, {
                    startSlide: 2,
                    speed: 400,
                    auto: 3000,
                    continuous: true,
                    disableScroll: false,
                    stopPropagation: false,
                    callback: function(index, elem) {},
                    transitionEnd: function(index, elem) {}
                });
            });
        },
		error: function(){
			ShowGetDataError();
		}
    });    
  
    $(".page-warp").click(function (){
        $(this).find(".pagew-size").toggle();
    });
  
    if($("input[name=store_id]").val()!=''){
        $.ajax({
            url:ApiUrl+"/index.php?act=store&op=goods_list&key=4&page="+pagesize+"&curpage=1"+'&store_id='+$("input[name=store_id]").val(),
            type:'get',
            dataType:'json',
            success:function(result){
			    if (!CheckDataSucceed(result)){return false;}
			
                $("input[name=hasmore]").val(result.hasmore);
                if(!result.hasmore){
                    $('.next-page').addClass('disabled');
                }
          
                var curpage = $("input[name=curpage]").val();//分页
                var page_total = result.page_total;
                var page_html = '';
                for(var i=1;i<=result.page_total;i++){
                    if(i==curpage){
                        page_html+='<option value="'+i+'" selected>'+i+'</option>';
                    }else{
                        page_html+='<option value="'+i+'">'+i+'</option>';
                    } 
                }     
          
                $('select[name=page_list]').empty();
                $('select[name=page_list]').append(page_html);
          
		        result.datas.extension = result.extension;
                var html = template.render('home_body', result.datas);
                $("#product_list").append(html);  
            },
		    error: function(){
			    ShowGetDataError();
		    }
        });
    }else{
        $.ajax({
            url:ApiUrl+"/index.php?act=store&op=goods_list&key=4&page="+pagesize+"&curpage=1"+'&keyword='+$("input[name=keyword]").val(),
            type:'get',
            dataType:'json',
            success:function(result){
				if (!CheckDataSucceed(result)){return false;}
				
                $("input[name=hasmore]").val(result.hasmore);
                if(!result.hasmore){
                    $('.next-page').addClass('disabled');
                }
          
                var curpage = $("input[name=curpage]").val();//分页
                var page_total = result.page_total;
                var page_html = '';
                for(var i=1;i<=result.page_total;i++){
                    if(i==curpage){
                        page_html+='<option value="'+i+'" selected>'+i+'</option>';
                    }else{
                        page_html+='<option value="'+i+'">'+i+'</option>';
                    } 
                }     
          
                $('select[name=page_list]').empty();
                $('select[name=page_list]').append(page_html);
          
                result.datas.extension = result.extension;
                var html = template.render('home_body', result.datas);
                $("#product_list").append(html);  
            },
		    error: function(){
			    ShowGetDataError();
		    }
        });
    }    
    
    $("select[name=page_list]").change(function(){
        var key = parseInt($("input[name=key]").val());
        var order = parseInt($("input[name=order]").val());
        var page = parseInt($("input[name=page]").val());     
        var store_id = parseInt($("input[name=store_id]").val());
        var keyword = $("input[name=keyword]").val();
        var hasmore = $("input[name=hasmore]").val();
      
        var curpage = $('select[name=page_list]').val();
      
        if(store_id>0){
            var url = ApiUrl+"/index.php?act=store&op=goods_list&key="+key+"&order="+order+"&page="+page+"&curpage="+curpage+"&store_id="+store_id;
        }else{
            var url = ApiUrl+"/index.php?act=store&op=goods_list&key="+key+"&order="+order+"&page="+page+"&curpage="+curpage+"&keyword="+keyword;
        }
      
        $.ajax({
            url:url,
            type:'get',
            dataType:'json',
            success:function(result){
				if (!CheckDataSucceed(result)){return false;}
				
			    result.datas.extension = result.extension;
                var html = template.render('home_body', result.datas);
                $("#product_list").empty();
                $("#product_list").append(html);
        
                if(curpage>1){
                    $('.pre-page').removeClass('disabled');
                }else{
                    $('.pre-page').addClass('disabled');
                }
        
                if(curpage<result.page_total){
                    $('.next-page').removeClass('disabled');  
                }else{
                    $('.next-page').addClass('disabled');
                }
        
                $("input[name=curpage]").val(curpage);
            },
		    error: function(){
			    ShowGetDataError();
		    }
        });      
    });
    
    
    $('.keyorder').click(function(){
        var key = parseInt($("input[name=key]").val());
        var order = parseInt($("input[name=order]").val());
        var page = parseInt($("input[name=page]").val());     
        var curpage = eval(parseInt($("input[name=curpage]").val())-1);
        var store_id = parseInt($("input[name=store_id]").val());
        var keyword = $("input[name=keyword]").val();
        var hasmore = $("input[name=hasmore]").val();

        var curkey = $(this).attr('key');//1.销量 2.浏览量 3.价格 4.最新排序
        if(curkey == key){
            if(order == 1){
                var curorder = 2;
            }else{
                var curorder = 1;
            }
        }else{
            var curorder = 1;
        } 
    
        $(this).addClass("current").siblings().removeClass("current");
    
        if(store_id>0){
            var url = ApiUrl+"/index.php?act=store&op=goods_list&key="+curkey+"&order="+curorder+"&page="+page+"&curpage=1&store_id="+store_id;
        }else{
            var url = ApiUrl+"/index.php?act=store&op=goods_list&key="+curkey+"&order="+curorder+"&page="+page+"&curpage=1&keyword="+keyword;
        }
    
        $.ajax({
            url:url,
            type:'get',
            dataType:'json',
            success:function(result){
				if (!CheckDataSucceed(result)){return false;}
								
                $("input[name=hasmore]").val(result.hasmore);
		        result.datas.extension = result.extension;
                var html = template.render('home_body', result.datas);
                $("#product_list").empty();
                $("#product_list").append(html);  
                $("input[name=key]").val(curkey);
                $("input[name=order]").val(curorder);     
            },
		    error: function(){
			    ShowGetDataError();
		    }
        });
    });
  
    $('.pre-page').click(function(){//上一页
        var key = parseInt($("input[name=key]").val());
        var order = parseInt($("input[name=order]").val());
        var page = parseInt($("input[name=page]").val());     
        var curpage = eval(parseInt($("input[name=curpage]").val())-1);
        var store_id = parseInt($("input[name=store_id]").val());
        var keyword = $("input[name=keyword]").val();

        if(curpage<1){
            return false;
        }
    
        if(store_id>=0){
            var url = ApiUrl+"/index.php?act=store&op=goods_list&key="+key+"&order="+order+"&page="+page+"&curpage="+curpage+"&store_id="+store_id;
        }else{
            var url = ApiUrl+"/index.php?act=store&op=goods_list&key="+key+"&order="+order+"&page="+page+"&curpage="+curpage+"&keyword="+keyword;
        }
        $.ajax({
            url:url,
            type:'get',
            dataType:'json',
            success:function(result){
				if (!CheckDataSucceed(result)){return false;}
				
                $("input[name=hasmore]").val(result.hasmore);
                if(curpage == 1){
                    $('.next-page').removeClass('disabled');
                    $('.pre-page').addClass('disabled');
                }else{
                    $('.next-page').removeClass('disabled');
                }
		        result.datas.extension = result.extension;
                var html = template.render('home_body', result.datas);
                $("#product_list").empty();
                $("#product_list").append(html);    
                $("input[name=curpage]").val(curpage);
        
                var page_total = result.page_total;
                var page_html = '';
                for(var i=1;i<=result.page_total;i++){
                    if(i==curpage){
                        page_html+='<option value="'+i+'" selected>'+i+'</option>';
                    }else{
                        page_html+='<option value="'+i+'">'+i+'</option>';
                    } 
                }
          
                $('select[name=page_list]').empty();
                $('select[name=page_list]').append(page_html);
            },
		    error: function(){
			    ShowGetDataError();
		    }   
        });
    });
  
    $('.next-page').click(function(){//下一页
        var hasmore = $('input[name=hasmore]').val();
        if(hasmore == 'false'){
            return false;
        }
    
        var key = parseInt($("input[name=key]").val());
        var order = parseInt($("input[name=order]").val());
        var page = parseInt($("input[name=page]").val());
        var curpage = eval(parseInt($("input[name=curpage]").val())+1);
        var store_id = parseInt($("input[name=store_id]").val());
        var keyword = $("input[name=keyword]").val();

        if(store_id>=0){
            var url = ApiUrl+"/index.php?act=store&op=goods_list&key="+key+"&order="+order+"&page="+page+"&curpage="+curpage+"&store_id="+store_id;
        }else{
            var url = ApiUrl+"/index.php?act=store&op=goods_list&key="+key+"&order="+order+"&page="+page+"&curpage="+curpage+"&keyword="+keyword;
        }   
        $.ajax({
            url:url,
            type:'get',
            dataType:'json',
            success:function(result){
			    if (!CheckDataSucceed(result)){return false;}
			
                $("input[name=hasmore]").val(result.hasmore);
                if(!result.hasmore){
                    $('.pre-page').removeClass('disabled');
                    $('.next-page').addClass('disabled');
                }else{
                    $('.pre-page').removeClass('disabled');
                }
		        result.datas.extension = result.extension;
                var html = template.render('home_body', result.datas);
                $("#product_list").empty();
                $("#product_list").append(html);
                $("input[name=curpage]").val(curpage);
        
                var page_total = result.page_total;
                var page_html = '';
                for(var i=1;i<=result.page_total;i++){
                    if(i==curpage){
                        page_html+='<option value="'+i+'" selected>'+i+'</option>';
                    }else{
                        page_html+='<option value="'+i+'">'+i+'</option>';
                    } 
                }     
                $('select[name=page_list]').empty();
                $('select[name=page_list]').append(page_html);
            },
		    error: function(){
			    ShowGetDataError();
		    }
        });
    });   

    //渲染店铺信息页面
    $.ajax({
        url:ApiUrl+"/index.php?act=store&op=store_detail",
        type:"get",
        data:{store_id:store_id},
        dataType:"json",
        success:function(result){
		    if (!CheckDataSucceed(result)){return false;}
		  
            var data = result.datas;
            //渲染模板
            var html = template.render('go_store', data);
            $("#product_detail_wp").html(html);
			
	        //分享			
	        window._bd_share_config.common.bdText = data.store_info.store_name;
		    window._bd_share_config.common.bdDesc = data.store_info.store_name;
		    window._bd_share_config.common.bdUrl = data.store_info.store_url;
		    if (data.store_info.avatar && data.store_info.avatar!=''){
			    window._bd_share_config.common.bdPic = data.store_info.avatar;
		    }else{
			    window._bd_share_config.common.bdPic ='';
		    }			
            $(".pd-storeshare").click(function (){
		        $("#bdshare").click();
		    });
		    with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src="http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion="+~(-new Date()/36e5)];
        },
		error: function(){
			ShowGetDataError();
		}
    });
  
    function AddView(){//增加浏览记录
        var store_info = getCookie('store');
        var store_id = getQueryString('store_id');
        if(store_id<1){
            return false;
        }

        if(store_info==''){
            store_info+=store_id; 
        }else{
            var storearr = store_info.split('@');
            if(contains(storearr,store_id)){
                return false;
            }
            if(storearr.length<5){
                store_info+='@'+store_id;
            }else{
                storearr.splice(0,1);
                storearr.push(store_id);
                store_info = storearr.join('@');
            }
        }

        addcookie('store',store_info);
        return false;
    }
  
    function contains(arr, str) {//检测store_id是否存入
        var i = arr.length;
        while (i--) {
            if (arr[i] === str) {
                return true;
            }   
        }   
        return false;
    }
    //$.sValid.init({
    //    rules:{
    //        buynum:"digits"
    //    },
    //    messages:{
    //        buynum:"请输入正确的数字"
    //    },
    //    callback:function (eId,eMsg,eRules){
    //        if(eId.length >0){
    //            var errorHtml = "";
    //            $.map(eMsg,function (idx,item){
    //                errorHtml += "<p>"+idx+"</p>";
    //            });
    //            $.sDialog({
    //                skin:"red",
    //                content:errorHtml,
    //                okBtn:false,
    //                cancelBtn:false
    //            });
    //        }
    //    }  
    //});
});