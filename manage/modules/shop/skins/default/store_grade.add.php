<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=store_grade&op=store_grade" title="返回列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['store_grade'];?> - 新增等级</h3>
        <h5><?php echo $lang['store_grade_subhead'];?></h5>
      </div>
    </div>
  </div>
  <form id="grade_form" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="sg_name"><em>*</em><?php echo $lang['store_grade_name'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="sg_name" name="sg_name" class="input-txt">
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="sg_price"><em>*</em><?php echo $lang['charges_standard'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="" id="sg_price" name="sg_price" class="input-txt">元/年
          <span class="err"></span>
          <p class="notic"><?php echo $lang['charges_standard_notice'];?></p>
        </dd>
      </dl>
      
      <dl class="row">
        <dt class="tit"><label for="store_type">适合商家</label></dt>
        <dd class="opt">
          <ul style="margin:10px 0;">
            <li style="display:inline-block; margin-right:10px;">
              <input type="radio" name="store_type" value="1" checked="checked" />
              <span>个人(无实体店)</span>
            </li>
            <li style="display:inline-block; margin-right:10px;">
              <input type="radio" name="store_type" value="2"  />
              <span>企业(有实体店)</span>
            </li>
          </ul>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label for="sg_confirm">开店是否需要审核</label></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="sg_confirm1" class="cb-enable selected" ><span>是</span></label>
            <label for="sg_confirm0" class="cb-disable" ><span>否</span></label>
            <input id="sg_confirm1" name="sg_confirm" checked="checked" value="1" type="radio">
            <input id="sg_confirm0" name="sg_confirm" value="0" type="radio">
          </div>
          <span class="err"></span>
          <p class="notic">如果申请开店时，总费用为0，且这里设置为否，则不需审核自动开店。</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label for="branch_op">是否充许开分店</label></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="branch_op1" class="cb-enable" ><span>是</span></label>
            <label for="branch_op0" class="cb-disable selected" ><span>否</span></label>
            <input id="branch_op1" name="branch_op" value="1" type="radio">
            <input id="branch_op0" name="branch_op" checked="checked" value="0" type="radio">
          </div>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label for="branch_limit">分店数量</label></dt>
        <dd class="opt">
          <input type="text" value="0" id="branch_limit" name="branch_limit" class="txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['zero_said_no_limit'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label for="extension_op">是否充许开启店铺推广</label></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="extension_op1" class="cb-enable" ><span>是</span></label>
            <label for="extension_op0" class="cb-disable selected" ><span>否</span></label>
            <input id="extension_op1" name="extension_op" value="1" type="radio">
            <input id="extension_op0" name="extension_op" checked="checked" value="0" type="radio">
          </div>
          <span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label for="promotion_limit">推广员数量</label></dt>
        <dd class="opt">
          <input type="text" value="0" id="promotion_limit" name="promotion_limit" class="txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['zero_said_no_limit'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><label for="saleman_limit">导购员数量</label></dt>
        <dd class="opt">
          <input type="text" value="0" id="saleman_limit" name="saleman_limit" class="txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['zero_said_no_limit'];?></p>
        </dd>
      </dl>
        
      <dl class="row">
        <dt class="tit">
          <label for="sg_goods_limit"><?php echo $lang['allow_pubilsh_product_num'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="0" id="sg_goods_limit" name="sg_goods_limit" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['zero_said_no_limit'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label> <?php echo $lang['allow_upload_album_num'];?></label>
        </dt>
        <dd class="opt">
          <input type="text" value="1000" id="sg_album_limit" name="sg_album_limit" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['zero_said_no_limit'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="skin_limit"><?php echo $lang['optional_template_num'];?></label>
        </dt>
        <dd class="opt"><span class="grey">(<?php echo $lang['in_store_grade_list_set'];?>)</span><span class="err"></span>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="skin_limit"><?php echo $lang['additional_features'];?></label>
        </dt>
        <dd class="opt">
          <ul class="nofloat">
            <li>
              <input type="checkbox" id="function_editor_multimedia" value="editor_multimedia" name="sg_function[]">
              <label for="function_editor_multimedia"><?php echo $lang['editor_media_features'];?></label>
            </li>
          </ul>
          </p>
        </dd>
      </dl>
      
      <dl class="row">
        <dt class="tit">
          <label for="sg_description"><?php echo $lang['application_note'];?></label>
        </dt>
        <dd class="opt">
          <textarea rows="6" class="tarea" id="sg_description" name="sg_description"></textarea>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['application_note_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><em>*</em><?php echo $lang['grade_sortname']; //级别?></label>
        </dt>
        <dd class="opt">
          <input type="text" id="sg_sort" name="sg_sort" class="input-txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['grade_sort_tip']; //数值越大表明级别越高?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
//按钮先执行验证再提交表单
$(function(){$("#submitBtn").click(function(){
    if($("#grade_form").valid()){
     $("#grade_form").submit();
	}
	});
});
//
$(document).ready(function(){
	$('#grade_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parent('dd').children('span.err');
            error_td.append(error);
        },

        rules : {
            sg_name : {
                required : true,
                remote   : {
                url :'index.php?act=store_grade&op=ajax&branch=check_grade_name',
                type:'get',
                data:{
                        sg_name : function(){
                        	return $('#sg_name').val();
                        },
                        sg_id  : ''
                    }
                }
            },
			sg_price : {
                required  : true,
                number : true,
                min : 0
            },
            sg_goods_limit : {
                digits  : true
            },
            sg_space_limit : {
                digits : true
            },
            sg_sort : {
            	required  : true,
                digits  : true,
                remote   : {
	                url :'index.php?act=store_grade&op=ajax&branch=check_grade_sort',
	                type:'get',
	                data:{
	                        sg_sort : function(){
	                        	return $('#sg_sort').val();
	                        },
	                        sg_id  : ''
	                    }
                }
            }
        },
        messages : {
            sg_name : {
                required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['store_grade_name_no_null'];?>',
                remote   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['now_store_grade_name_is_there'];?>'
            },
			sg_price : {
                required  : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['charges_standard_no_null'];?>',
                number : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['charges_standard_no_null'];?>',
                min : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['charges_standard_no_null'];?>'
            },
            sg_goods_limit : {
                digits : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['only_lnteger'];?>'
            },
            sg_space_limit : {
                digits  : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['only_lnteger'];?>'
            },
            sg_sort  : {
            	required : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['grade_add_sort_null_error']; //级别信息不能为空?>',
                digits   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['only_lnteger'];?>',
                remote   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['add_gradesortexist']; //级别已经存在?>'
            }
        }
    });
});
</script> 
