<?php defined('InIMall') or exit('Access Invalid!'); ?>

<div class="index_block home3">
    <?php if(!empty($vv['title'])) {?>
    <div class="title"><?php echo $vv['title']; ?></div>
    <?php } ?>
    <div class="content">
    <?php foreach ((array) $vv['item'] as $item) { ?>
        <div imtype="item_image" class="item">
            <a imtype="btn_item" href="javascript:;" data-type="<?php echo $item['type']; ?>" data-data="<?php echo $item['data']; ?>"><img imtype="image" src="<?php echo $item['image']; ?>" alt=""></a>
        </div>
    <?php } ?>
    </div>
</div>
