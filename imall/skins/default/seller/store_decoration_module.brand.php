<?php defined('InIMall') or exit('Access Invalid!');?>

<?php 
  $block_content = empty($block_content) ? $output['block_content'] : $block_content;
  $block_content = unserialize($block_content);
  $brand_row = count($block_content);
  $row_height =$brand_row*55;
?>
<div class="mainBody">
  <div class="slideTxtBox" style="height:<?php echo $row_height;?>px">
    <div class="hd" style="height:<?php echo $row_height;?>px">
      <ul style="height:<?php echo $row_height;?>px">
        <?php if(!empty($block_content) && is_array($block_content)) {$i=0;?>
        <?php foreach($block_content as $value) {$i++;?>
        <li data-row-caption="<?php echo $value['brand_group']['row_caption'];?>" data-row-sec-caption="<?php echo $value['brand_group']['row_sec_caption'];?>" data-row-index="<?php echo $i;?>"> 
          <b><?php echo $value['brand_group']['row_caption'];?></b>
          <p><?php echo $value['brand_group']['row_sec_caption'];?></p>
        </li>
        <?php } ?>
        <?php } ?>
      </ul>
    </div>
    <div class="bd" style="height:<?php echo $row_height;?>px">
      <?php if(!empty($block_content) && is_array($block_content)) {$i=0;?>
      <?php foreach($block_content as $value) {$i++;?>
      <ul>
        <?php if(!empty($value['brand_items']) && is_array($value['brand_items'])) {$j=0;?>
        <?php foreach($value['brand_items'] as $item) {$j++;if($j>16 || $j>($brand_row*8)){break;}?>
        <li <?php if ($j==1){?>class="first"<?php }?> data-brand-name="<?php echo $item['brand_name'];?>" data-brand-img="<?php echo $item['brand_img'];?>" data-brand-url="<?php echo $item['brand_url'];?>">
          <a href="<?php echo $item['brand_url'];?>" target="_blank" title="<?php echo $item['brand_name'];?>">
          <img src="<?php echo $item['brand_img'];?>" border="0" width="100" height="53" alt="<?php echo $item['brand_name'];?>">
          </a>
        </li>
        <?php } ?>
        <?php } ?>
      </ul>
      <?php } ?>
      <?php } ?>
    </div>
  </div>
  <script type="text/javascript">
    jQuery(".slideTxtBox").slide({autoPlay:"true"});		  
  </script> 
</div>