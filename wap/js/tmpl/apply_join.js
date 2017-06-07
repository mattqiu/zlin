$(function(){
	var key = getCookie('key');
	if(!key){
		location.href = 'member/login.html';
	}
	
	var store_id = getQueryString("store_id");
    //渲染页面
    $.ajax({
        url:ApiUrl+"/index.php?act=store_addapply&op=apply_join",
        type:"get",
        data:{store_id:store_id,key:key},
        dataType:"json",
        success:function(result){
		    if (!CheckDataSucceed(result)){return false;}
			
            var data = result.datas;
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
            email:{
            	required:true,
            	email:true
            },			           
            mobile:"required",
			qq:"required",
			areainfo:"required"
        },
        messages:{
            truename:"姓名必须填写！",            
            email:{
            	required:"邮件必填!",
            	email:"邮件格式不正确"           	
            },
			mobile:"手机号码必填!",
			qq:"QQ号码必填!",
			areainfo:"联系地址必填!"
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
		var ai_from = $("input[name=ai_from]").val();
		var ai_target = $("input[name=ai_target]").val();
		
		if($.sValid()){
			$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=store_addapply&op=apply_join_save&store_id="+ai_target,	
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