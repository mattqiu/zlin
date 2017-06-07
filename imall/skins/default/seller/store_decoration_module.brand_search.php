<?php defined('InIMall') or exit('Access Invalid!');?>
<?php   
  $brand_list = $output['brand_list'];
?>
<?php if(!empty($brand_list) && is_array($brand_list)){?>

  <?php foreach($brand_list as $key=>$val){?>
  <?php 
        $brand_url = urlShop('brand','list',array('brand'=>$val['brand_id']));
		$brand_img = brandImage($val['brand_pic']);
  ?>
  <li imtype="brand_item" class="brand_item" data-brand-name="<?php echo $val['brand_name'];?>" data-brand-img="<?php echo $brand_img;?>" data-brand-url="<?php echo $brand_url;?>">
    <a href="<?php echo $brand_url;?>" target="_blank" title="<?php echo $val['brand_name'];?>"> 
      <img width="90" height="30" border="0" alt="<?php echo $val['brand_name'];?>" src="<?php echo $brand_img;?>">
    </a> 
    <a imtype="btn_module_brand_operate" class="imsc-btn-mini" href="javascript:;"><i class="fa fa-plus"></i>选择添加</a>
  </li>
  <?php }?> 
  <div class="pagination"><?php echo $output['show_page']; ?></div>
<?php } else { ?>
  <li class="brand_item">暂无品牌</li>
<?php } ?>