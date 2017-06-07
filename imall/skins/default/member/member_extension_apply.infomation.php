<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="imsc-promotion-box">
  <dl class="imsc-promotion-info" style="width:100%">
    <dd>申请日期：<strong><?php echo date('Y-m-d',$output['apply_info']['ai_addtime']);?></strong></dd>
    <dd>姓　　名：<strong><?php echo $output['apply_info']['truename'];?></strong></dd>
    <dd>　Email：<strong><?php echo $output['apply_info']['email'];?></strong></dd>    
    <dd>手　　机：<strong><?php echo $output['apply_info']['mobile'];?></strong></dd>
    <dd>　　QQ：<strong><?php echo $output['apply_info']['qq'];?></strong></dd>    
    <dd>联系地址：<strong><?php echo $output['apply_info']['areainfo'];?></strong></dd>
    <dd>申请说明：<strong><?php echo $output['apply_info']['describe'];?></strong></dd>
    <?php if ($output['apply_info']['ai_dispose']>0){?>
    <dd><hr /></dd>
    <dd>审核结果：<strong><?php if ($output['apply_info']['ai_dispose']==1){echo '拒绝';}elseif($output['apply_info']['ai_dispose']==2){echo '同意';}?></strong></dd>
    <dd>审核意见：<strong><?php echo $output['apply_info']['ai_replyinfo'];?></strong></dd>
    <dd>审核日期：<strong><?php echo date('Y-m-d',$output['apply_info']['ai_distime']);?></strong></dd>
    <?php }?> 
  </dl>
</div>