<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="wrapper" >
  <div class="imcs-article">
    <div class="imcs-main-container">
      <div class="title">
        <h4><?php echo nl2br($output['store_navigation_info']['sn_title']);?></h4>
      </div>
      <div class="content">
        <div class="default"><?php echo $output['store_navigation_info']['sn_content'];?></div>
      </div>
    </div>
  </div>    
</div>