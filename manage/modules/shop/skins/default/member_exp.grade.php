<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>会员级别</h3>
        <h5>商城注册会员的级别设定</h5>
      </div>
      <?php echo $output['top_link'];?> 
      <!-- 
      <ul class="tab-base im-row">
        <li><a href="index.php?act=member_exp&op=index" >经验值明细</a></li>
        <li><a href="index.php?act=member_exp&op=expsetting">规则设置</a></li>
        <li><a href="JavaScript:void(0);" class="current">等级设定</a></li>
      </ul>
      -->
    </div>
  </div>
  
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>“会员级别设置”提交后，当会员符合某个级别后将自动升至该级别，请谨慎设置会员级别</li>
      <li>建议：一、级别应该是逐层递增，例如“级别2”所需经验值高于“级别1”；二、设置的第一个级别所需经验值应为0；三、信息应填写完整</li>
    </ul>
  </div>
  <form method="post" id="mg_form" name="mg_form" enctype="multipart/form-data">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default" id="mg_tbody">
      <div class="title">
        <h3>会员级别设置：</h3>
      </div>
      <dl class="row" id="row_0">
        <dt class="tit">会员级别<strong>V0</strong></dt>
        <dd class="opt">
          晋级需 <input type="text" name="mg[0][exppoints]" value="<?php echo $output['list_setting']['member_grade'][0]['exppoints'];?>" class="w60" readonly/> 经验值</dd>
      </dl>
      <dl class="row" id="row_1">
        <dt class="tit">会员级别<strong>V1</strong></dt>
        <dd class="opt">
          晋级需 <input type="text" name="mg[1][exppoints]" value="<?php echo $output['list_setting']['member_grade'][1]['exppoints'];?>" class="w60" im_type="verify" data-param='{"name":"经验值","type":"int"}'/>
          经验值</dd>
      </dl>
      <dl class="row" id="row_2">
        <dt class="tit">会员级别<strong>V2</strong></dt>
        <dd class="opt">
          晋级需 <input type="text" name="mg[2][exppoints]" value="<?php echo $output['list_setting']['member_grade'][2]['exppoints'];?>" class="w60" im_type="verify" data-param='{"name":"经验值","type":"int"}'/>
          经验值</dd>
      </dl>
      <dl class="row" id="row_3">
        <dt class="tit">会员级别<strong>V3</strong></dt>
        <dd class="opt">
          晋级需 <input type="text" name="mg[3][exppoints]" value="<?php echo $output['list_setting']['member_grade'][3]['exppoints'];?>" class="w60" im_type="verify" data-param='{"name":"经验值","type":"int"}'/>
          经验值</dd>
      </dl>
      <dl class="row" id="row_4">
        <dt class="tit">会员级别<strong>V4</strong></dt>
        <dd class="opt">
          晋级需 <input type="text" name="mg[4][exppoints]" value="<?php echo $output['list_setting']['member_grade'][4]['exppoints'];?>" class="w60" im_type="verify" data-param='{"name":"经验值","type":"int"}'/>
          经验值</dd>
      </dl>
      <dl class="row" id="row_5">
        <dt class="tit">会员级别<strong>V5</strong></dt>
        <dd class="opt">
          晋级需 <input type="text" name="mg[5][exppoints]" value="<?php echo $output['list_setting']['member_grade'][5]['exppoints'];?>" class="w60" im_type="verify" data-param='{"name":"经验值","type":"int"}'/>
          经验值</dd>
      </dl>
      <dl class="row" id="row_6">
        <dt class="tit">会员级别<strong>V6</strong></dt>
        <dd class="opt">
          晋级需 <input type="text" name="mg[6][exppoints]" value="<?php echo $output['list_setting']['member_grade'][6]['exppoints'];?>" class="w60" im_type="verify" data-param='{"name":"经验值","type":"int"}'/>
          经验值</dd>
      </dl>
      <dl class="row" id="row_7">
        <dt class="tit">会员级别<strong>V7</strong></dt>
        <dd class="opt">
          晋级需 <input type="text" name="mg[7][exppoints]" value="<?php echo $output['list_setting']['member_grade'][7]['exppoints'];?>" class="w60" im_type="verify" data-param='{"name":"经验值","type":"int"}'/>
          经验值</dd>
      </dl>
      <dl class="row" id="row_8">
        <dt class="tit">会员级别<strong>V8</strong></dt>
        <dd class="opt">
          晋级需 <input type="text" name="mg[8][exppoints]" value="<?php echo $output['list_setting']['member_grade'][8]['exppoints'];?>" class="w60" im_type="verify" data-param='{"name":"经验值","type":"int"}'/>
          经验值</dd>
      </dl>
      <dl class="row" id="row_9">
        <dt class="tit">会员级别<strong>V9</strong></dt>
        <dd class="opt">
          晋级需 <input type="text" name="mg[9][exppoints]" value="<?php echo $output['list_setting']['member_grade'][9]['exppoints'];?>" class="w60" im_type="verify" data-param='{"name":"经验值","type":"int"}'/>
          经验值</dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('#submitBtn').click(function(){
		var result = true;
		var error = new Array();
		$("#mg_tbody").find("[im_type='verify']").each(function(){
			if(result){
				data = $(this).val();
				if(!data){
					result = false;
					//error.push('请将信息填写完整');
					error = '请将信息填写完整';
				}
				//验证类型
				if(result){
					var data_str = $(this).attr('data-param');
				    if(data_str){
				    	eval( "data_str = "+data_str);
				    	switch(data_str.type){
				    	   case 'int':
				    		   result = (data = parseInt(data)) > 0?true:false;
				    		   error = (result == false)?(data_str.name + '应为整数'):'';
				    	}
				    }
				}				
			}
		});
		if(result){
			$('#mg_form').submit();
		} else {
			showDialog(error);
		}
    });
})
</script>