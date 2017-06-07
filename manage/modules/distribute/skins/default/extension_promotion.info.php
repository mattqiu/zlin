<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="imsc-promotion-box">
  <div class="promotion-logo">
    <img src="<?php echo getMemberAvatarForID($output['promotion_info']['member_id']);?>" />
  </div>
  <dl class="imsc-promotion-info">
    <dt class="promotion-name">
      <h3><?php echo $output['promotion_info']['member_truename']; ?></h3>
      <h5>(推广帐号：<?php echo $output['promotion_info']['member_name']; ?>)</h5>
    </dt>
    <dd>性　　别：<strong><?php if ($output['promotion_info']['member_sex']==0){echo '保密';} elseif ($output['promotion_info']['member_sex']==1){echo '男';} else{echo '女';}?></strong></dd>
    <dd>　　QQ：<strong><?php echo $output['promotion_info']['member_qq'];?></strong></dd>
    <dd>手　　机：<strong><?php echo $output['promotion_info']['member_mobile'];?></strong></dd>
    <dd>加入日期：<strong><?php echo $output['promotion_info']['member_add'];?></strong></dd>
    
    <dd>　　　</dd>
    <dd>代理类型：<strong><?php echo $output['promotion_info']['mc_name'];?></strong></dd>
    <dd>一级代理人数：<strong><?php echo $output['promotion_info']['sub_nums'];?></strong>人</dd>
    <dd>团队人数：<strong><?php echo $output['promotion_info']['child_nums'];?></strong>人</dd>    
    <dd>累计业绩：<strong><?php echo $output['promotion_info']['total_sales'];?></strong></dd>
    <dd>累计佣金：<strong><?php echo $output['promotion_info']['total_commis'];?></strong></dd>
    <dd>本期业绩：<strong><?php echo $output['promotion_info']['curr_sales'];?></strong></dd>
    <dd>本期佣金：<strong><?php echo $output['promotion_info']['curr_commis'];?></strong></dd>
  </dl>
</div>