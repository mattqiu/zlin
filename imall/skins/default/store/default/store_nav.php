<?php defined('InIMall') or exit('Access Invalid!');?>

<?php if(!empty($output['decoration_nav'])) {?>
  <style><?php echo $output['decoration_nav']['style'];?></style>
<?php } ?>
<div class="public-nav-layout">
  <?php if(empty($output['decoration_nav']) || $output['decoration_nav']['display'] == 'true') {?>
  <div id="nav" class="imcs-nav">
    <ul>
      <li class="<?php if($output['page'] == 'index'){?>active<?php }else{?>normal<?php }?>">
        <a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['store_info']['store_id']));?>"><span><?php echo $lang['im_store_index'];?><i></i></span></a>
      </li>      
      <?php 
		if(!empty($output['store_navigation_list'])){
      	  foreach($output['store_navigation_list'] as $value){
            if($value['sn_if_show']) {
      		  if($value['sn_url'] != ''){
	  ?>
      <li class="<?php if($output['page'] == $value['sn_id']){?>active<?php }else{?>normal<?php }?>">
        <a href="<?php echo $value['sn_url']; ?>" <?php if($value['sn_new_open']){?>target="_blank"<?php }?>><span><?php echo $value['sn_title'];?><i></i></span></a>
      </li>
      <?php }else{ ?>
      <li class="<?php if($output['page'] == $value['sn_id']){?>active<?php }else{?>normal<?php }?>">
        <a href="<?php echo urlShop('show_store', 'show_article', array('store_id' => $output['store_info']['store_id'], 'sn_id' => $value['sn_id']));?>"><span><?php echo $value['sn_title'];?><i></i></span></a>
      </li>
      <?php }}}} ?>
      <li class="<?php if ($output['page'] == 'store_sns') {?>active<?php }else{?>normal<?php }?>">
        <a href="<?php echo urlShop('store_snshome', 'index', array('store_id' => $output['store_info']['store_id']))?>"><span>店铺动态<i></i></span></a>
      </li>
    </ul>
  </div>
  <?php } ?>
</div>