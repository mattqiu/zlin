$(function(){
	var key = getCookie('key');
	if(!key){
		location.href = 'member/login.html';
	}
    //渲染页面
    $.ajax({
        url:ApiUrl+"/index.php?act=extension_apply&op=apply_extension",
        type:"get",
        data:{key:key},
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
        	truename:"required",
			mobile:"required"
        },
        messages:{
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
	
	var key = getCookie('key');
	if(!key){
		location.href = 'member/login.html';
	}
	
	$('#applybtn').click(function(){	
		var truename = $("input[name=truename]").val();
		var email = $("input[name=email]").val();
		var mobile = $("input[name=mobile]").val();
		var qq = $("input[name=qq]").val();
		var areainfo = $("input[name=areainfo]").val();
		var describe = $("input[name=describe]").val();

		if($.sValid()){			
			$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=extension_apply&op=apply_extension_save",	
				data:{key:key,truename:truename,email:email,mobile:mobile,qq:qq,areainfo:areainfo,describe:describe},
				dataType:'json',
				success:function(result){
					if (!CheckDataSucceed(result)){return false;}
					$.sDialog({
                        skin:"red",
                        content:result.datas.msg,
                        okBtn:true,
                        cancelBtn:false,
					    okFn: function() {location.href = WapSiteUrl+'/tmpl/member/member.html';}
				    });
					//$(".error-tips").hide();
				},
				error: function(){
			        ShowGetDataError();
		        }
			});			
		}
	});
}