<?php defined('InIMall') or exit('Access Invalid!');?>

<?php if ($output['hidden_sidetoolbar'] != 1) {?>
<!-- publicRightSidebar Begin -->
<link href="<?php echo SHOP_SKINS_URL;?>/css/sidebar_right.css" rel="stylesheet" type="text/css">
<?php $mall_consult = unserialize(C('mall_consult'));?>
<div id="J_sidebar" class="side_right">
  <div class="side-box">
    <?php if ($_GET['act'] !='index'){?>
    <ul class="side-oper home">
      <li class="normal side-love">
        <a class="linksbtn" id="J_home" href="<?php echo urlShop('index','index');?>">
          <i class="normal-icon go_home"></i>
        </a>
        <div class="tab-tips">返回首页<div class="arr-icon">◆</div> </div>
      </li>
    </ul> 
    <?php }?>   
    <ul class="side-oper">
      <li class="normal side-user">
        <a class="linksbtn" id="J_user" href="<?php echo urlShop('member','home');?>"><i class="normal-icon i-user"></i></a>
        <div id="side-login" class="tab-tips tab-login">
          <div class="user-box">     
			<div class="pic"><img src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"></div>
            <p class="txt"></p>
            <dl>
              <?php if (OPEN_STORE_EXTENSION_STATE > 0 && ($_SESSION['M_mc_id']==1 || $_SESSION['M_mc_id']==2)){?>
              <dd id="sb_rebates">                   
                <a href="index.php?act=member_extension&op=my_income" title="查看我的推广收益">              
                  <span class="icon"></span>
                </a>
              </dd>
              <?php }?>
              <dd id="sb_voucher">
                <a href="index.php?act=member_voucher&op=index" title="查看我的代金券">              
                  <span class="icon"></span>
                </a>
              </dd>
              <dd id="sb_favorites">
                <a href="index.php?act=member_favorites&op=fglist" title="查看我收藏的商品">              
                  <span class="icon"></span>
                </a>
              </dd>
              <dd id="sb_browse">
                <a href="index.php?act=member_goodsbrowse&op=list" title="查看我看过的商品">              
                  <span class="icon"></span>
                </a>
              </dd>
              <dd id="sb_messages">
                <a href="index.php?act=member_message&op=message" title="查看我收到的消息">              
                  <span class="icon"></span>
                </a>
              </dd>
              
            </dl>
          </div>
          <i class="close">×</i>
          <div class="arr-icon">◆</div>
        </div>
      </li>
      <li class="normal side-cart">
        <a class="linksbtn links-cart" id="J_cart" href="javascript:void(0);">
          <i class="normal-icon i-cart"></i>
          <em class="num cartnum">
          <?php if ($output['cart_goods_num'] > 0) {echo $output['cart_goods_num'];}else{echo '0';}?>
          </em>
        </a>
        <div class="tab-tips tab-tag">
          <div class="carttime">我的购物车</div><div class="arr-icon">◆</div>
        </div>                   
      </li>   
      <li class="normal side-compare" id="sidebar_item_compare">
        <a class="linksbtn" id="J_compare" href="javascript:void(0);">
          <i class="normal-icon i-compare"></i>
        </a>
        <div class="tab-tips">商品对比<div class="arr-icon">◆</div> </div>
      </li>     
      <li class="normal side-chat">        
        <?php if ($output['store_info']['member_id']>0){?>
        <a class="linksbtn" href="javascript:void(0);" onclick="chat(<?php echo $output['store_info']['member_id'];?>);">
          <i class="normal-icon chat_show_user"></i>
        </a>
        <?php }elseif($mall_consult['im']>0){?>   
        <a class="linksbtn" href="javascript:void(0);" onclick="chat(<?php echo $mall_consult['im'];?>);">
          <i class="normal-icon chat_show_user"></i>
        </a> 
        <?php }else{?>    
        <a class="linksbtn" id="chat_show_user" href="javascript:void(0);">
          <i class="normal-icon chat_show_user"></i>
        </a>
        <?php }?>
        <div class="tab-tips">呼叫店主！<div class="arr-icon">◆</div> </div>
      </li>
    </ul>
    <ul class="side-oper other">             
	  <li class="normal side-ad" id="sidebar_item_ad">
	    <a class="linksbtn links_ad" href="javascript:void(0);"  target="_blank">
	      <img src="<?php echo SHOP_SKINS_URL;?>/images/sidebar/style-nav.jpg">
	    </a>
	    <div class="tab-tips">
          <?php echo loadadv(1050);?>
	    </div>
	  </li>      
      <li class="normal side-code" id="sidebar_item_code">
        <a class="linksbtn" id="J_code" href="javascript:;">
          <i class="normal-icon i-code"></i>
        </a>
        <div class="tab-tips tab-other">
          <div class="code-box">
            <p class="code"><img src="<?php echo SHOP_SKINS_URL;?>/images/weixin_img.jpg" width="90px"></p>
            <p class="txt">扫描下载二维码</p>
          </div>
          <div class="arr-icon">◆</div>
        </div>
      </li>
      <li class="normal side-complain" id="sidebar_item_complain">
        <a class="linksbtn" id="J_complain" href="javascript:void(0);">
          <i class="normal-icon i-complain"></i>
        </a>
        <div class="tab-tips">
          <div class="consult-box">
            <div class="consult-list">
              <dl>
                <?php $mall_consult = unserialize(C('mall_consult'));?>
                <?php if (!empty($mall_consult['qq'])){?>
                <dd id="sb_qq">
                  <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $mall_consult['qq'];?>&site=qq&menu=yes" title="QQ客服">              
                    <span class="icon"></span>
                  </a>
                </dd>
                <?php }?>
                <?php if (!empty($mall_consult['ww'])){?>
                <dd id="sb_ww">
                  <a href="http://amos.im.alisoft.com/msg.aw?v=2&amp;uid=<?php echo $mall_consult['ww'];?>&site=cntaobao&s=1&charset=UTF-8" title="旺旺客服">              
                    <span class="icon"></span>
                  </a>
                </dd>
                <?php }?>
                <?php if (!empty($mall_consult['im'])){?>
                <dd id="sb_im">
                  <a member_id="<?php echo $mall_consult['im'];?>" show_icon="no" href="javascript:void(0);" onclick="javascript:chat(<?php echo $mall_consult['im'];?>);" title="站内IM客服">              
                    <span class="icon"></span>
                  </a>
                </dd>
                <?php }?>
                <dd id="sb_cp">
                  <a href="<?php echo urlShop('member_mallconsult','add_mallconsult');?>" title="我要留言">              
                    <span class="icon"></span>
                  </a>
                </dd>
              </dl>
            </div>
            <p class="txt">平台客服</p>
          </div>
          <div class="arr-icon">◆</div>        
        </div>
      </li>
      <li class="normal side-backtop">
        <a class="linksbtn" id="J_backtop" href="javascript:;"><i class="normal-icon i-backtop"></i></a>
        <div class="tab-tips">返回顶部<div class="arr-icon">◆</div> </div>
      </li>
    </ul>    
  </div>
  
  <div class="content-box" id="content-compare">
    <div class="top">
      <h3>商品对比</h3>
      <a href="javascript:void(0);" class="close" title="隐藏"></a>
    </div>
    <div id="comparelist"></div>
  </div>

  <div class="content-box" id="content-cart">
    <div class="top">
      <h3>我的购物车</h3>
      <a href="javascript:void(0);" class="close" title="隐藏"></a>
    </div>
    <div id="rtoolbar_cartlist"></div>
  </div>
  
  <div class="imcss-hidebar" id="imHideBar">
    <div class="imcss-hidebar-bg">
      <?php if ($_SESSION['is_login']) {?>
      <div class="user-avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"/></div>
      <?php } else {?>
      <div class="user-avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"/></div>
      <?php }?>
      <div class="frame"></div>
      <div class="show"></div>
    </div>
  </div>
        
</div>	
<script type="text/javascript">
(function($){
    $.fn.slowShow = function(ele,time){
        time = time == undefined?100:time;
        var timer=null;
        clearInterval(timer);
        this.hover(function(){
            clearTimeout(timer);
            timer=setTimeout(function(){
                ele.show();
            },time);
        },
        function(){
            clearTimeout(timer);
            timer=setTimeout(function(){
                ele.hide();
            },time);
        }
    )}
})(jQuery);

(function($){
    /**
     * 右侧返回顶部
     */
    var $navFun_2 = function() {
        var st = $(document).scrollTop(),
            winh = $(window).height(),
            doch = $(document).height(),
            headh = $("#toolbar").height(),
            header = $(".header").height(),
            $nav_classify = $("div.side_right");
			
        if(st > headh + header){
            $nav_classify.show()
            $nav_classify.addClass('fix')
        } else {
            $nav_classify.hide()
            $nav_classify.removeClass('fix')
        }
    };

    var $navFun = function(){
        $navFun_1();
        $navFun_2();
    }

    /**
     * 绑定滚动函数
     */ 
    $('a.go-top').click(function(){
        $('body,html').animate({scrollTop:0},500);
    });
	
    $('#J_sidebar .side-box a#J_backtop').click(function(){
        $('body,html').animate({scrollTop:0},500);
    });
    //显示回到顶部按钮
    var backtop_show=function(){
        $(window).scroll(function(){
            var st=$(window).scrollTop();
            if(st>0){
               $("a#J_backtop").css("display","block"); 
            }
            else{
                $("a#J_backtop").css("display","none");
                $("a#J_backtop").parents().find(".tab-tips").css({"opacity":"0","display":"none","right":"62px"});
            }
        })
    }
    backtop_show();

    /**
     * 右侧鼠标移入弹出说明
     * */
    var $obj=null;
    var timer=null;
    var normal_show_fun=function(){
        clearInterval(timer);
        $('#J_sidebar .side-oper li').hover(function(){
            $('#J_sidebar .side-oper li').find(".tab-tips").css({"opacity":"0","display":"none","right":"62px"})
            $('#J_sidebar .side-oper li').removeClass("curr");
            $("#J_sidebar .side-oper li.side-cart").removeClass("selected");
            $obj=$(this);
            clearTimeout(timer);
            timer=setTimeout(function(){
                $obj.addClass("curr");
                if($obj.hasClass("side-cart")){
                    if($obj.find(".carttime").html()=="" || $obj.find("em.cartnum").html()=="0"){
                        $('.carttime').hide();
                        return;
                    }
                }
                if(($obj.hasClass("side-backtop") && $obj.find("a.linksbtn").css("display")=="none")||($obj.hasClass("side-cart") && $obj.find("#side-empty").css("display")=="block")){
                    return;
                }else{
                    $obj.find(".tab-tips").css("opacity","1");
                    $obj.find(".tab-tips").animate({
                        right: 36,opacity: 'show'
                    }, 300);
                }
            },100);
            if($obj.hasClass("side-user")){
                $obj.find(".close").on('click',function(){
                    $obj.find(".tab-tips").css({"opacity":"0","display":"none","right":"62px"});
                })
            }
        },
        function(){
            clearTimeout(timer);
            timer=setTimeout(function(){
                $obj.removeClass("curr");
                $obj.find(".tab-tips").css({"opacity":"0","display":"none","right":"62px"});
                if($obj.hasClass("side-cart")){
                    $obj.removeClass("selected");
                }
            },100);
        })

        //会员中心特殊处理
        $('#J_sidebar .side-oper li.side-user').hover(function(){
            <?php if ($_SESSION['is_login']) {?>
			$(this).find('#side-login .user-box p.txt').html('<a target="_blank" href="<?php echo urlShop('member','home');?>">你好！<?php echo $_SESSION['member_name'];?></a>');
            $(this).find('#side-login .user-box .pic img').attr('src', '<?php echo getMemberAvatar($_SESSION['avatar']);?>');
			<?php } else {?>
            $(this).find('#side-login .user-box p.txt').html('快来<a target="_blank" href="<?php echo urlShop('login','index');?>">登录</a>吧，么么哒！');
            <?php }?>
        })        
    }	
    normal_show_fun(); //鼠标移入在左侧显示信息的效果
	
	$('#J_collapsable').click(function() {
		$('#content-cart').animate({'right': '-210px'});
		$('#content-compare').animate({'right': '-210px'});
		$('#J_sidebar').animate({'right': '-60px'}, 300,
		function() {
			$('#imHideBar').animate({'right': '59px'},	300);
		});
	    $('div[imtype^="bar"]').hide();
	});
	
	$('#imHideBar').click(function() {
		$('#imHideBar').animate({
			'right': '-79px'
		},
		300,
		function() {
			$('#content-cart').animate({'right': '-210px'});
			$('#content-compare').animate({'right': '-210px'});
			$('#J_sidebar').animate({'right': '0'},300);
		});
	});
	
	$("#J_compare").click(function(){
    	if ($("#content-compare").css('right') == '-210px') {
 		   loadCompare(false);
 		   $('#content-cart').animate({'right': '-210px'});
  		   $("#content-compare").animate({right:'36px'});
    	} else {
    		$(".close").click();
    		$(".chat-list").css("display",'none');
        }
	});
    $("#J_cart").click(function(){
        if ($("#content-cart").css('right') == '-210px') {
         	$('#content-compare').animate({'right': '-210px'});
    		$("#content-cart").animate({right:'36px'});
    		if (!$("#rtoolbar_cartlist").html()) {
    			$("#rtoolbar_cartlist").load('index.php?act=cart&op=ajax_load&type=html');
    		}
        } else {
        	$(".close").click();
        	$(".chat-list").css("display",'none');
        }
	});
	//关闭弹出的信息框
	$(".close").click(function(){
		$(".content-box").animate({right:'-210px'});
    });
	//低分辨率屏幕隐藏部分图标
	if (window.screen.height<900){
		$("#sidebar_item_ad").hide();	
		//$("#sidebar_item_code").hide();
		//$("#sidebar_item_complain").hide();	
		//$("#sidebar_item_love").hide();	
		//$("#sidebar_item_quan").hide();	
	}
})(jQuery);
	
</script>
<!-- publicRightSidebar End -->
<?php } ?>