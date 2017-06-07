$(function(){
	var extension = getQueryString("extension");
	
	$.ajax({
		type:'get',
		url:ApiUrl+"/index.php?act=login&op=joininfo",	
		data:{extension:extension},
		dataType:'json',
		//jsonp:'callback',
		success:function(result){
			if (!CheckDataSucceed(result,'/index.html')){return false;}
			
			var data = result.datas;			
			data.WapSiteUrl = WapSiteUrl;
			var html = template.render('join-top-tmpl', data);
            $("#header").html(html);
					
			var html = template.render('join-form-tmpl', data);
            $("#join_form").html(html);
				
		    //初始化
			initJoinPage();	
			return false;
		},
		error: function(){
			ShowGetDataError();
		}	  
	});	
	
    function initJoinPage() {	
	    $("#btn-opera").click(function (){
		    $(".main-opera-pannel").toggle();
	    });
	
	    $.sValid.init({//注册验证
            rules:{
				username:"required",
				userpwd:"required",
        	    password_confirm:"required",
                member_mobile:{
            	    required:true,
            	    mobile:true
                }
            },
            messages:{
				username:"用户名不能为空",
				userpwd:"登录密码不能为空",
                password_confirm:"确认密码不能为空",
                member_mobile:{
            	    required:"手机号必填!",
            	    mobile:"手机号码不正确"           	
                }
            },
            callback:function (eId,eMsg,eRules){
                if(eId.length >0){
                    var errorHtml = "";
                    $.map(eMsg,function (idx,item){
                        errorHtml += "<p>"+idx+"</p>";
                    });
                    $(".error-tips").html(errorHtml).show();
                }else{
                    $(".error-tips").html("").hide();
                }
            }  
        });
	
	    $('#join_btn').click(function(){	
		    var username = $("input[name=username]").val();
			var userpwd = $("input[name=userpwd]").val();
		    var password_confirm = $("input[name=password_confirm]").val();
		    var member_mobile = $("input[name=member_mobile]").val();
		    var client = $("input[name=client]").val();
		
		    if($.sValid()){
			    $.ajax({
				    type:'post',
				    url:ApiUrl+"/index.php?act=login&op=ccard_join",	
				    data:{username:username, password:userpwd, password_confirm:password_confirm, member_mobile:member_mobile, client:client, extension:extension},
				    dataType:'json',
				    success:function(result){
						if (!CheckDataSucceed(result)){return false;}
						
					    addCookie('username',result.datas.username);
					    addCookie('key',result.datas.key);
					    location.href = WapSiteUrl+'/tmpl/member/member.html';
				    },
				    error: function(){
			            ShowGetDataError();
		            }
			    });			
		    }
	    });		
	}
			
	var UA = navigator.appVersion.toLowerCase();
    var isqqBrowser = (UA.split("qq/").length > 1) ? true : false;
    var iswxBrowser = (UA.split("micromessenger/").length > 1) ? true : false;
	
	$(".qq").click(function() {
        location.href = ApiUrl + "/index.php?act=connect&op=get_qq_oauth2&extension="+extension;
    })
	if (iswxBrowser == true){		
	    $(".weixin").click(function() {
            location.href = ApiUrl + "/index.php?act=connect_wx&op=login&extension="+extension;
        })
	}else{
		$(".weixin").hide();
	}
});