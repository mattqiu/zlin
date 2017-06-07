<?php defined('InIMall') or exit('Access Invalid!');?>

<!--link Begin-->
<div class="full_module wrapper">
  <h2><b><?php echo $lang['index_index_link'];?></b></h2>
  <div class="piclink">
  <?php if(is_array($output['$link_list']) && !empty($output['$link_list'])) {
          foreach($output['$link_list'] as $val) {
		    if($val['link_pic'] != ''){
  ?>
    <span><a href="<?php echo $val['link_url']; ?>" target="_blank"><img src="<?php echo $val['link_pic']; ?>" title="<?php echo $val['link_title']; ?>" alt="<?php echo $val['link_title']; ?>" width="88" height="31" ></a></span>
   <?php
            }
		   }
		 }
    ?>
    <div class="clear"></div>
  </div>
  <div class="textlink">
    <?php 
	  if(is_array($output['$link_list']) && !empty($output['$link_list'])) {
	    foreach($output['$link_list'] as $val) {
		  if($val['link_pic'] == ''){
	?>
    <span><a href="<?php echo $val['link_url']; ?>" target="_blank" title="<?php echo $val['link_title']; ?>"><?php echo str_cut($val['link_title'],16);?></a></span>
    <?php
		  }
		}
	  }
    ?>
    <div class="clear"></div>
  </div>
</div>
<!--link end-->