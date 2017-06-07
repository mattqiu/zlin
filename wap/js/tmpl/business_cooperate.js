$(function(){
	
	var bs_type = getQueryString("bs_type");
    //渲染页面
    $.ajax({
        url:ApiUrl+"/index.php?act=business_cooperate&op=cooperate",
        type:"get",
        data:{bs_type:bs_type},
        dataType:"json",
        success:function(result){
		    if (!CheckDataSucceed(result)){return false;}
			
            var data = result.datas;
			var cooperate_title = "";
			var cooperate_desc = "";
			if (data.apply_info.bs_type==10){
				cooperate_title = "供应商";
				cooperate_desc  = "<li>1、增加线上销售商圈扩大销售渠道。</li>";
				cooperate_desc += "<li>2、增加行业竞争优势。</li>";
				cooperate_desc += "<li>3、创新经营模式。</li>";
				cooperate_desc += "<li>4、塑造企业公益形象增加平拍美誉度。</li>";
			}else if(data.apply_info.bs_type==20){
				cooperate_title = "消费商";
				cooperate_desc  = "<li>1、拥有更多折扣享受更多优惠。</li>";
				cooperate_desc += "<li>2、享受折扣、养老金和积分兑换。</li>";
				cooperate_desc += "<li>3、通过网址购物为自己零成本积攒养老金。</li>";
				cooperate_desc += "<li>4、保险公司理财复利滚利。</li>";
				cooperate_desc += "<li>5、推广赢积分可兑换现金和产品。</li>";
			}else if(data.apply_info.bs_type==30){
				cooperate_title = "爱心使者";
				cooperate_desc  = "<li>1、获得长期持续的会员积分收益。</li>";
				cooperate_desc += "<li>2、获得广告、聚到等收益。</li>";
				cooperate_desc += "<li>3、创新经营模式。</li>";
				cooperate_desc += "<li>4、塑造企业公益形象增加平拍美誉度。</li>";
			}else if(data.apply_info.bs_type==40){
				cooperate_title = "代理商";
				cooperate_desc  = "<li>1、在亲诚MMT商城购物给你消费养老+消费分红。</li>";
				cooperate_desc += "<li>2、获得商城内上万款一线品牌总代理（价值无法估量）</li>";
				cooperate_desc += "<li>3、获得亲诚MMT商城自动赚钱管家系统（0发货、0囤货、0进货、0投资、0风险、0压力的轻松创业平台）</li>";
				cooperate_desc += "<li>4、获得微信8亿多客户锁定（点击就被锁定、购物就赚钱）</li>";
			}else if(data.apply_info.bs_type==50){
				cooperate_title = "运营商";
				cooperate_desc  = "<li>1、代办购物养老卡。</li>";
				cooperate_desc += "<li>2、推荐客户赠送积分。</li>";
				cooperate_desc += "<li>3、积分可以兑换现金和产品。</li>";
			}
			$("#header_title").html("成为"+cooperate_title);
			$("#cooperate_sub_title").html("亲诚MMT"+cooperate_title+"福利");
			$("#cooperate_desc").html(cooperate_desc);
			
			var html = template.render('cooperate_title_form', data);
            $("#cooperate_require").html(html);
			
			html = template.render('cooperate_info_form', data);
            $("#cooperate_info").html(html);
			
			initApplyForm(data.apply_info.bs_type);			
        },
		error: function(){
			ShowGetDataError();
		}
    });	

});

function initApplyForm(bs_type){
	$.sValid.init({
        rules:{
        	bs_name:"required", 
			bs_contacts:"required", 
			bs_mobile:"required"
        },
        messages:{
            bs_name:"信息不全，请填写完整", 
			bs_contacts:"信息不全，请填写完整", 
			bs_mobile:"信息不全，请填写完整"
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
	
	$('#applybtn').click(function(){
		var bs_type = $("input[name=bs_type]").val()?$("input[name=bs_type]").val():10;
		if (bs_type==30){
			var bs_name = $("select[name=bs_name]").val()?$("select[name=bs_name]").val():'';
		}else{
		    var bs_name = $("input[name=bs_name]").val()?$("input[name=bs_name]").val():'';
		}
		var bs_contacts = $("input[name=bs_contacts]").val()?$("input[name=bs_contacts]").val():'';
		var bs_mobile = $("input[name=bs_mobile]").val()?$("input[name=bs_mobile]").val():'';
		var bs_shop = $("input[name=bs_shop]").val()?$("input[name=bs_shop]").val():'';
		var bs_staffs = $("input[name=bs_staffs]").val()?$("input[name=bs_staffs]").val():'';
		var bs_address = $("input[name=bs_address]").val()?$("input[name=bs_address]").val():'';
		var bs_referee = $("input[name=bs_referee]").val()?$("input[name=bs_referee]").val():'';
		var bs_type = $("input[name=bs_type]").val()?$("input[name=bs_type]").val():10;
		
		if($.sValid()){
			$.ajax({
				type:'post',
				url:ApiUrl+"/index.php?act=business_cooperate&op=cooperate_save",	
				data:{bs_name:bs_name,bs_contacts:bs_contacts,bs_mobile:bs_mobile,bs_shop:bs_shop,bs_staffs:bs_staffs,bs_address:bs_address,bs_referee:bs_referee,bs_type:bs_type},
				dataType:'json',
				success:function(result){
					if (!CheckDataSucceed(result)){return false;}
					
					$.sDialog({
                        skin:"red",
                        content:result.datas.msg,
                        okBtn:true,
                        cancelBtn:false,
					    okFn: function() {location.href = WapSiteUrl+'/main.html';}
				    });
				},
				error: function(){
			        ShowGetDataError();
		        }
			});			
		}
	});
}