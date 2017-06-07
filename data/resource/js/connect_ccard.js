
function check_ccard(){
    if($("#ccard_sn").val().length >= 16 && $("#ccard_sn").val().length <= 18 && $("#active_pwd").val().length == 6){
        var ajaxurl = 'index.php?act=connect_ccard&op=check_ccard';
        ajaxurl += '&ccard_sn='+$('#ccard_sn').val()+'&active_pwd='+$('#active_pwd').val();			
	    $.ajax({
			type: "GET",
			url: ajaxurl,
			dataType: 'json',
			async: false,
			success: function(rs){					
            	if(rs.state == 'true') {
            	    $.getScript('index.php?act=connect_ccard&op=register'+'&ccard_sn='+$('#ccard_sn').val());
					$("#ccard_form").hide();
            	    $("#register_ccard_form").show();            	    
            	} else {
            	    showError(rs.msg);
            	}
			}
	    });
    }else{
		showError('请输入正确的养老卡号和激活码!');
	}
}