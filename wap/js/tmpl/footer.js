$(function (){
	var extension = getQueryString('extension');
	var store_id = getQueryString('store_id');
	var footerURL = ApiUrl + "/index.php?act=index&op=publicFooter";
	if (store_id && store_id>0){
	    footerURL = ApiUrl + "/index.php?act=store&op=publicFooter&store_id="+store_id;
	}
			
	$.ajax({
        url: footerURL,
        type: 'get',
        dataType: 'json',
        success: function(result) {
			if (!CheckDataSucceed(result)){return false;}
							
            var data = result.datas;
			var tmpl = get_main_footer_tmpl(data);//template.render('pub_footer', data);	
			var render = template.compile(tmpl);
	        var html = render();
            $("#main_footer").html(html);			
			
			$('.ico_07').click(function(){
                var keyword = encodeURIComponent($('#keywordfoot').val());
                location.href = WapSiteUrl+'/tmpl/product_list.html?keyword='+keyword;
            });
        }
    });
	
	if (ShowMainGlobalNav==true){
	    $.ajax({
            url: ApiUrl + "/index.php?act=index&op=publicGlobalNav",
            type: 'get',
            dataType: 'json',
		    data:{key:getCookie('key'),client:'wap'},
            success: function(result) {
			    if (!CheckDataSucceed(result)){return false;}
					
                var data = result.datas;
			    var tmpl = get_main_globalnav_tmpl(data);//template.render('pub_globalnav', data);
			    var render = template.compile(tmpl);
	            var html = render();
                $("#main-globalnav").html(html);
			
			    Zepto(function($){
                    var $nav = $('.global-nav'), $btnLogo = $('.global-nav__operate-wrap');
                    //点击箭头，显示隐藏导航
                    $btnLogo.on('click',function(){
                        if($btnLogo.parent().hasClass('global-nav--current')){
                            navHide();
                        }else{
                            navShow();
                        }
                    });

                    var navShow = function(){
                        $nav.addClass('global-nav--current');
                    }

                    var navHide = function(){
                        $nav.removeClass('global-nav--current');
                    }
   
                    $(window).on("scroll", function() {
                        if($nav.hasClass('global-nav--current')){
                            navHide();
                        }
                    });
                })
            }
        });
	}	
	
	if (ShowMainMenu==true){
	    var store_id = getQueryString('store_id');
	    $.ajax({
            url: ApiUrl + "/index.php?act=index&op=publicMainMenu&store_id="+store_id,
            type: 'get',
            dataType: 'json',
            success: function(result) {
				if (!CheckDataSucceed(result)){return false;}
							
                var data = result.datas;
			    data.WapSiteUrl = WapSiteUrl;
			    var tmpl = get_main_menu_tmpl(data);//template.render('pub_menu', data);			
			    var render = template.compile(tmpl);
	            var html = render();
                $("#main-menu").html(html);	
			
			    $('#main-menu').css('display','');
			    $('nav#main-menu').mmenu();		
            },
			error: function(){
			    ShowGetDataError();
		    }
        });
	}
});

function get_search_box(){
    document.getElementById('keywordfoot').focus();
}

function get_main_footer_tmpl(data){	
   var tmpl ='<div class="in">'
            +'  <div class="search_box">'
            +'    <input name="keywords" type="text" id="keywordfoot" />'
            +'    <button class="ico_07" type="submit" value="搜索" > </button>'
            +'  </div>'
            +'  <a href="'+data.wap_site_url+'/tmpl/member/member.html?act=member" class="homeBtn"> <span class="ico_05"> </span> </a>'
            +'  <a href="#top" class="gotop"> <span class="ico_06"> </span> <p> TOP </p></a>'
            +'</div>'
            +'<p class="link region">'
            +'  <a href="'+data.site_url+'"> 电脑版 </a>'
            +'  <a href="'+data.wap_site_url+'"> 触屏版 </a>' 
            +'  <a href="'+data.ios_app_url+'"> 苹果客户端 </a>' 
            +'  <a href="'+data.android_app_url+'"> Android客户端 </a>'
            +'</p>'
            +'<p class="region">';		   
    if (data.icp_number) {
        tmpl +='  ICP备案证书号: <a href="http://www.miibeian.gov.cn/" target="_blank"> '+data.icp_number+' </a>'; 
    }   
    tmpl +='</p>';	 
    tmpl +='<p class="region">';
    tmpl +='  '+data.copyright+'';
    tmpl +='</p>';
    tmpl +='<div class="favLink region"> <a href="'+data.site_url+'"> Powered by '+data.site_name+' </a> </div>';
    return tmpl;
}

function get_main_globalnav_tmpl(data){
    var tmpl ='<div class="global-nav__nav-wrap">'
             +'  <div class="global-nav__nav-item">'
             +'    <a href="'+data.wap_site_url+'/index.html" class="global-nav__nav-link">'
             +'      <i class="fa fa-home"></i>'
             +'      <span class="global-nav__nav-tit">首页</span>'
             +'    </a>'
             +'  </div>'
             +'  <div class="global-nav__nav-item">'
             +'    <a href="'+data.wap_site_url+'/tmpl/product_first_categroy.html" class="global-nav__nav-link">'
             +'      <i class="fa fa-th-list"></i>'
             +'      <span class="global-nav__nav-tit">分类</span>'
             +'    </a>'
             +'  </div>'
             +'  <div class="global-nav__nav-item">'
             +'    <a href="javascript:get_search_box();" class="global-nav__nav-link">'
             +'      <i class="fa fa-search"></i>'
             +'      <span class="global-nav__nav-tit">搜索</span>'
             +'    </a>'
             +'  </div>'
             +'  <div class="global-nav__nav-item">'
             +'   <a href="'+data.wap_site_url+'/tmpl/cart_list.html" class="global-nav__nav-link">'
             +'     <i class="fa fa-shopping-cart"></i>'
             +'     <span class="global-nav__nav-tit">购物车</span>'
             +'     <span class="global-nav__nav-shop-cart-num" id="carId">'+data.cart_num+'</span>'
             +'   </a>'
             +'  </div>'
             +'  <div class="global-nav__nav-item">'
             +'    <a href="'+data.wap_site_url+'/tmpl/member/member.html?act=member" class="global-nav__nav-link">'
             +'      <i class="fa fa-user"></i>'
             +'      <span class="global-nav__nav-tit">个人中心</span>'
             +'    </a>'
             +'  </div>'
             +'</div>'
             +'<div class="global-nav__operate-wrap">'
             +'  <span class="global-nav__yhd-logo"></span>'
             +'  <span class="global-nav__operate-cart-num" id="globalId">'+data.cart_num+'</span>'
             +'</div>';
    return tmpl;
}

function get_main_menu_tmpl(data){
    var tmpl ='';
    if(data.goods_class && data.goods_class.length >0){
        tmpl +='<ul>';
        var tempgc1;
        var tempgc2;
	    var tempgc3; 
	    var locUrl = "";	
	    for(i=0;i<data.goods_class.length;i++){
	        tempgc1 = data.goods_class[i];  
	        if (tempgc1.store_id && tempgc1.store_id>0){
		        locUrl = WapSiteUrl+'/tmpl/product_list.html?stc_id='+tempgc1.gc_id;
	        }else{
                if (tempgc1.child2 && tempgc1.child2.length >0){
	                locUrl = WapSiteUrl+'/tmpl/product_second_categroy.html?gc_id='+tempgc1.gc_id;
	            }else{
		            locUrl = WapSiteUrl+'/tmpl/product_list.html?gc_id='+tempgc1.gc_id;
	            }
	        }

            tmpl +='<li>';
            tmpl +='<a href="'+locUrl+'"> '+tempgc1.gc_name+'</a>';	  
            if(tempgc1.child2 && tempgc1.child2.length >0){
                tmpl +='<ul>';
                for(j=0;j<tempgc1.child2.length;j++){
		            tempgc2 = tempgc1.child2[j];
		            if (tempgc2.store_id && tempgc2.store_id>0){
		                locUrl = WapSiteUrl+'/tmpl/product_list.html?stc_id='+tempgc2.gc_id;
	                }else{
		                if (tempgc2.child3 && tempgc2.child3.length >0){
	                        locUrl = WapSiteUrl+'/tmpl/product_second_categroy.html?gc_id='+tempgc2.gc_id;
	                    }else{
		                    locUrl = WapSiteUrl+'/tmpl/product_list.html?gc_id='+tempgc2.gc_id;
	                    }
	                }
                    tmpl +='<li>';
                    tmpl +='  <a href="'+locUrl+'"> '+tempgc2.gc_name+'</a>';
                    if(tempgc2.child3 && tempgc2.child3.length >0){
                        tmpl +='  <ul>';
                        for(k=0;k<tempgc2.child3.length;k++){
		                    tempgc3 = tempgc2.child3[k];
			                if (tempgc3.store_id && tempgc3.store_id>0){
		                        locUrl = WapSiteUrl+'/tmpl/product_list.html?stc_id='+tempgc3.gc_id;
	                        }else{
		                        locUrl = WapSiteUrl+'/tmpl/product_list.html?gc_id='+tempgc3.gc_id;
			                }
                            tmpl +='<li><a href="'+locUrl+'"> '+tempgc3.gc_name+'</a></li>';
                        }
                        tmpl +='</ul>';
                    }
                    tmpl +='</li>';
                }
                tmpl +='</ul>';  
            }
            tmpl +='</li>';
        }
        tmpl +='</ul>';
    }  
    return tmpl;
}