<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <a class="back" href="index.php?act=mb_ad&op=mb_ad_list" title="返回首页广告列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3><?php echo $lang['link_index_mb_ad'];?> - <?php echo $lang['im_new'];?></h3>
        <h5>添加手机端首页广告</h5>
      </div>
    </div>
  </div>
  <form id="link_form" enctype="multipart/form-data" method="post">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="link_title"><em>*</em><?php echo $lang['link_index_title'];?>:</label>
        </dt>
        <dd class="opt">
          <input type="text" value="" name="link_title" id="link_title" class="txt">
          <span class="err"></span>
          <p class="notic">广告列表显示的标题</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="link_keyword"> 广告链接:</label>
        </dt>
        <dd class="opt">
          <input type="text" name="link_keyword" id="link_keyword" class="txt" >
          <span class="err"></span>
          <p class="notic">点击广告后打开的链接</p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="link_pic"><?php echo $lang['link_index_pic_sign'];?>:</label>
        </dt>
        <dd class="opt">
          <div class="input-file-show"> 
            <span class="type-file-box">
              <input name="link_pic" type="file" id="link_pic" class="type-file-file" size="30" hidefocus="true">
            </span>
          </div>
          <span class="err"></span>
          <p class="notic"><?php echo $lang['link_add_sign'];?></p>
        </dd>
      </dl> 
      <dl class="row">
        <dt class="tit">
          <label for="link_sort"><?php echo $lang['im_sort'];?>:</label>
        </dt>
        <dd class="opt">  
          <input type="text" value="255" name="link_sort" id="link_sort" class="txt">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['link_add_sort_tip'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){
	var textButton="<input type='text' name='textfield' id='textfield1' class='type-file-text' /><input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />"
	$(textButton).insertBefore("#link_pic");
	$("#link_pic").change(function(){
	    $("#textfield1").val($("#link_pic").val());
    });
	//按钮先执行验证再提交表单
	$("#submitBtn").click(function(){
        if($("#link_form").valid()){
            $("#link_form").submit();
	    }
	});

	$('#link_form').validate({
        errorPlacement: function(error, element){
			var error_td = element.parents('dl').find('span.err');
            error_td.append(error);
        },
        rules : {
            link_title : {
                required : true
            },
            link_pic  : {
                required : true,
            },     
            link_sort : {
                number   : true
            }
        },
        messages : {
            link_title : {
                required : '<?php echo $lang['link_add_title_null'];?>'
            },
            link_pic  : {
                required : '<?php echo $lang['link_add_pic_null'];?>',
            },
            link_sort  : {
                number   : '<?php echo $lang['link_add_sort_int'];?>'
            }
        }
    });
});
</script> 