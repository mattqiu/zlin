<?php defined('InIMall') or exit('Access Invalid!');?>

<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"/>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<script type="text/javascript">
    function show_list(t_id){
        var obj = $(".sidebar dl[show_id='"+t_id+"']");
    	var show_class=obj.find("dt i").attr('class');
    	if(show_class=='hide') {
    		obj.find("dd").show();
    		obj.find("dt i").attr('class','show');
    	}else{
    		obj.find("dd").hide();
    		obj.find("dt i").attr('class','hide');
    	}
    }
</script>
<div class="breadcrumb">
  <span class="icon-home"></span><span><a href="<?php echo urlShop('index','index');?>">首页</a></span> 
  <span class="arrow">></span><span>商家入驻申请</span> 
</div>
<div class="main">
  <div class="sidebar">
    <div class="title">
      <h3>商家入驻申请</h3>
    </div>
    <div class="content">
      <dl show_id="99">
        <dt onclick="show_list('99');" style="cursor: pointer;"> <i class="hide"></i>入驻流程</dt>
        <dd style="display:none;">
          <ul>
            <li><i></i><a href="<?php echo urlShop('document', 'index',array('code'=>'ServicerFlow_Agreement'));?>" target="_blank">签署入驻协议</a></li>
            <li><i></i><a href="<?php echo urlShop('document', 'index',array('code'=>'ServicerFlow_Info'));?>" target="_blank">商家信息提交</a></li>
            <li><i></i><a href="<?php echo urlShop('document', 'index',array('code'=>'ServicerFlow_Verify'));?>" target="_blank">平台审核资质</a></li>
            <li><i></i><a href="<?php echo urlShop('document', 'index',array('code'=>'ServicerFlow_Payment'));?>" target="_blank">商家缴纳费用</a></li>
            <li><i></i><a href="<?php echo urlShop('document', 'index',array('code'=>'ServicerFlow_Open'));?>" target="_blank">店铺开通</a></li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt class="<?php echo $output['step'] == 0 ? 'current' : '';?>"> <i class="hide"></i>签订入驻协议</dt>
      </dl>
      <dl show_id="0">
        <dt onclick="show_list('0');" style="cursor: pointer;"> <i class="show"></i>提交申请</dt>
        <dd>
          <ul>    
            <li class="<?php echo $output['step'] == 1 ? 'current' : '';?>"><i></i>个人资质信息</li>
            <li class="<?php echo $output['step'] == 2 ? 'current' : '';?>"><i></i>财务资质信息</li>
            <li class="<?php echo $output['step'] == 3 ? 'current' : '';?>"><i></i>店铺经营信息</li>
          </ul>
        </dd>
      </dl>
      <dl>
        <dt class="<?php echo $output['step'] == 4 ? 'current' : '';?>"> <i class="hide"></i>合同签订及缴费</dt>
      </dl>
      <dl>
        <dt class="<?php echo $output['step'] == 5 ? 'current' : '';?>"> <i class="hide"></i>店铺开通</dt>
      </dl>
    </div>
    <div class="title">
      <h3>平台联系方式</h3>
    </div>
    <div class="content">
      <ul>
        <?php if(is_array($output['phone_array']) && !empty($output['phone_array'])) {?>
		<?php foreach($output['phone_array'] as $key => $val) {?>
        <li>电话<?php echo ($key+1).$lang['im_colon'];?><?php echo $val;?></li>
        <?php }?>
        <?php }?>
        <li>邮箱：<?php echo C('site_email');?></li>
      </ul>
    </div>
  </div>
  <div class="right-layout">
    <div class="joinin-step">
      <ul>
        <li class="step1 <?php echo $output['step'] >=0 ? 'current' : '';?>"><span>签订入驻协议</span></li>
        <li class="<?php echo $output['step'] >=1 ? 'current' : '';?>"><span>个人资质信息</span></li>
        <li class="<?php echo $output['step'] >=2 ? 'current' : '';?>"><span>财务资质信息</span></li>
        <li class="<?php echo $output['step'] >=3 ? 'current' : '';?>"><span>店铺经营信息</span></li>
        <li class="<?php echo $output['step'] >=4 ? 'current' : '';?>"><span>合同签订及缴费</span></li>
        <li class="step6 <?php echo $output['step'] >=5 ? 'current' : '';?>"><span>店铺开通</span></li>
      </ul>
    </div>
    <div class="joinin-concrete">
      <?php require('store_joinin_apply_p.step'.$output['sub_step'].'.php'); ?>
    </div>
  </div>
</div>