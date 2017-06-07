<?php defined('InIMall') or exit('Access Invalid!'); ?>

<div class="index_block home1">
    <?php if(!empty($vv['title'])) {?>
    <div class="title"><?php echo $vv['title']; ?></div>
    <?php } ?>
    <div class="content">
        <div imtype="item_image" class="item">
            <a imtype="btn_item" href="javascript:;" data-type="<?php echo $vv['type']; ?>" data-data="<?php echo $vv['data']; ?>">
                <img imtype="image" src="<?php echo $vv['image']; ?>" alt="">
            </a>
        </div>
    </div>
</div>
