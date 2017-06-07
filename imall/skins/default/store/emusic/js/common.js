$(function(){
	
	//头部主导航鼠标经过效果	   
	$(".subNav ul li ").mouseenter(function(){
		$(this).find(".sub_nav").show();
	});
	
	$(".subNav ul li ").mouseleave(function(){
		$(this).find(".sub_nav").hide();
	});
	
	//商品分类树
	$(".leftNav ul li").mouseenter(function(){
		$(this).find(".leftSubNav_list").show();
	  	$(this).addClass("current");
	});
	
	$(".leftNav ul li").mouseleave(function(){
		$(this).find(".leftSubNav_list").hide();
	  	$(this).removeClass("current");
	});
	
	//商品分类树右侧切换效果
	$(".slideBox").mouseenter(function(){
		$(this).find(".banner_btn_left").show();
	  	$(this).addClass("banner_btn_left_hover");
		
		$(this).find(".banner_btn_right").show();
	  	$(this).addClass("banner_btn_right_hover");
	});
	
	$(".slideBox").mouseleave(function(){
		$(this).find(".banner_btn_left").hide();
	  	$(this).removeClass("banner_btn_left_hover");
		
		$(this).find(".banner_btn_right").hide();
	  	$(this).removeClass("banner_btn_right_hover");
	});	
    
	 //除首页商品分类鼠标移上去效果
	 $(".classNav ").mouseenter(function(){
		$(this).find(".left_nav").show();
	  	
	}); 
	
	$(".classNav ").mouseleave(function(){
		$(this).find(".left_nav").hide();
	  	
	});
	  
	  
	 //轮播广告下侧图片上下浮动
	 
    $("#banner_01 .big_txt ").mouseenter(function(){
		$("#banner_01 .big_txt  ").stop();
		$(this).animate({top: '173px'}, 300);
	})
	
	$(" #banner_01 .big_txt ").mouseleave(function(){
		$("#banner_01 .big_txt  ").stop();
		$(this).animate({top: '153px'}, 300);
	})
	
	 $("#banner_02 .big_txt ").mouseenter(function(){
		$("#banner_02 .big_txt  ").stop();
		$(this).animate({top: '173px'}, 300);
	})
	
	$(" #banner_02 .big_txt ").mouseleave(function(){
		$("#banner_02 .big_txt  ").stop();
		$(this).animate({top: '153px'}, 300);
	})
	
	$("#banner_03 .big_txt ").mouseenter(function(){
		$("#banner_03 .big_txt  ").stop();
		$(this).animate({top: '173px'}, 300);
	})
	
	$(" #banner_03 .big_txt ").mouseleave(function(){
		$("#banner_03 .big_txt  ").stop();
		$(this).animate({top: '153px'}, 300);
	})
	
	$("#banner_04 .big_txt ").mouseenter(function(){
		$("#banner_04 .big_txt  ").stop();
		$(this).animate({top: '173px'}, 300);
	})
	
	$(" #banner_04 .big_txt ").mouseleave(function(){
		$("#banner_04 .big_txt  ").stop();
		$(this).animate({top: '153px'}, 300);
	})


    //本周热推鼠标移上去的效果	   
	$(".hot_list01 dd").mouseenter(function(){
		$(this).find(".hot_btn").show();
		$(this).find(".hot_line").show();
	});
	
	$(".hot_list01 dd").mouseleave(function(){
		$(this).find(".hot_btn").hide();
		$(this).find(".hot_line").hide();
	});
	
	
	$(".hot_list02 dt").mouseenter(function(){
		$(this).find(".hot_btn").show();
		$(this).find(".hot_line").show();
	});
	
	$(".hot_list02 dt").mouseleave(function(){
		$(this).find(".hot_btn").hide();
		$(this).find(".hot_line").hide();
	});
	
	$(".hot_list02 dd").mouseenter(function(){
		$(this).find(".hot_btn").show();
		$(this).find(".hot_line").show();
	});
	
	$(".hot_list02 dd").mouseleave(function(){
		$(this).find(".hot_btn").hide();
		$(this).find(".hot_line").hide();
	});
	
	
	$(window).scroll(function(){
		if($(".inDetail_box").length > 0)
		{
			if(isIE6)
			{	
				var a = $(window).scrollTop();
				var b = $(".inDetail_box").offset();
				var c = b.top;
				var d = a-c;
				var e = d+150;
	
				$("#commentform").css({position:"absolute",top:e});	
				$("#boxOverlay").css({position:"absolute",top:d});	
			}
		}
	})
	
	
})

