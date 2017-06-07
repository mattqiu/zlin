
    function StepTimes() {
		$num = parseInt($('#show_times').html());
		$num = $num - 1;
		$('#show_times').html($num);
		if ($num <= 0) {
			$('#send_success_tips').hide();
			$('#sending_tips').show();
			$('#sending_btn').removeClass('disabled');
			$('#sending_btn').attr("disabled",false);
			$("#mobile").find(".makecode").trigger("click");
		} else {
			setTimeout(StepTimes,1000);
		}
	}
	
	function get_sms_captcha(type){			
        if($("#phone").val().length == 11 && $("#mobile_captcha").val().length == 4){
            var ajaxurl = 'index.php?act=connect_sms&op=get_smscaptcha&imhash=1&type='+type;
            ajaxurl += '&captcha='+$('#mobile_captcha').val()+'&phone='+$('#phone').val();			
			$.ajax({
				type: "GET",
				url: ajaxurl,
				dataType: 'json',
				async: false,
				success: function(rs){
                    if(rs.state == 'true') {
                    	//$("#sms_text").html('<span class="smssend"><i class="fa fa-wifi"></i>'+rs.msg+'</span>');
						$('#sending_btn').addClass('disabled');
						$('#sending_btn').attr("disabled",true);
						$('#sending_tips').hide();
						$('#send_success_tips').show();		
						$('#show_times').html(60);
			            setTimeout(StepTimes,1000);		
                    } else {
                        showError(rs.msg);
						$("#mobile").find(".makecode").trigger("click");					
                    }					
			    }
			});
    	}else{
			showError('请输入正确的手机号码和验证码!');
		}
	}
	
	function check_captcha(){
        if($("#phone").val().length == 11 && $("#sms_captcha").val().length == 6){
            var ajaxurl = 'index.php?act=connect_sms&op=check_captcha';
            ajaxurl += '&sms_captcha='+$('#sms_captcha').val()+'&phone='+$('#phone').val();			
			$.ajax({
				type: "GET",
				url: ajaxurl,
				dataType: 'json',
				async: false,
				success: function(rs){					
            	    if(rs.state == 'true') {
            	        $.getScript('index.php?act=connect_sms&op=register'+'&phone='+$('#phone').val());
            	        $("#register_sms_form").show();
            	        $("#mobile_form").hide();
            	    } else {
            	        showError(rs.msg);
            	    }
			    }
			});
    	}else{
			showError('请输入正确的手机号码和验证码!');
		}
	}