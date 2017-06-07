$(function(){
	var key = getCookie('key');
	
	var store_id = getQueryString("store_id");			
    //渲染页面
    $.ajax({
        url:ApiUrl+"/index.php?act=store_addapply&op=apply_extension",
        type:"get",
        data:{store_id:store_id,key:key},
        dataType:"json",
        success:function(result){
			if (!CheckDataSucceed(result)){return false;}
			
            var data = result.datas;
			data.WapSiteUrl = WapSiteUrl;
			
			var html = template.render('join-top-tmpl', data);
            $("#header").html(html);

			var html = template.render('apply_info_form', data);
            $("#apply_info").html(html);
			initApplyForm();			
        },
		error: function(){
			ShowGetDataError();
		}
    });	

});

function initApplyForm(){
	$.sValid.init({//注册验证
        rules:{
			username:"required",
            userpwd:"required",            
            password_confirm:"required",
        	truename:"required",
            mobile:"required"
        },
        messages:{
			username:"用户名必须填写！",
            userpwd:"密码必填!", 
            password_confirm:"确认密码必填!",
            truename:"姓名必须填写！",            
			mobile:"手机号码必填!"
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
	
	$('#registerbtn').click(function(){	
	    var username = $("input[name=username]").val();
		var pwd = $("input[name=pwd]").val();
		var password_confirm = $("input[name=password_confirm]").val();		
		var truename = $("input[name=truename]").val();
		var email = $("input[name=email]").val();
		var mobile = $("input[name=mobile]").val();
		var qq = $("input[name=qq]").val();
		var areainfo = $("input[name=areainfo]").val();
		var describe = $("input[name=describe]").val();
		var ai_from = $("input[name=ai_from]").val();
		var ai_target = $("input[name=ai_target]").val();
		var ai_type = $("input[name=ai_type]").val();//$("input[name='ai_type']:checked").val();
		var client = "wap";

		if($.sValid()){
			$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=store_addapply&op=reg_extension_save&store_id="+ai_target,	
				data:{ai_type:ai_type,username:username,password:pwd,password_confirm:password_confirm,truename:truename,email:email,mobile:mobile,qq:qq,areainfo:areainfo,describe:describe,client:client},
				dataType:'json',
				success:function(result){
					if (!CheckDataSucceed(result)){return false;}
					
					addCookie('username',result.datas.username);
					addCookie('key',result.datas.key);
					$.sDialog({
                        skin:"red",
                        content:result.datas.msg,
                        okBtn:true,
                        cancelBtn:false,
					    okFn: function() {location.href = WapSiteUrl+'/tmpl/member/member.html';}
				    });
				},
				error: function(){
			        ShowGetDataError();
		        }						
			});			
		}
	});
}