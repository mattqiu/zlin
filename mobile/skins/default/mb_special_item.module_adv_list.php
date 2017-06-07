<?php defined('InIMall') or exit('Access Invalid!'); ?>

<div class="adv_list">
    <div class="swipe-wrap">
    <?php foreach ((array) $vv['item'] as $item) { ?>
        <div class="item" imtype="item_image">
            <a imtype="btn_item" href="javascript:;" data-type="<?php echo $item['type']; ?>" data-data="<?php echo $item['data']; ?>">
                <img imtype="image" src="<?php echo $item['image']; ?>" alt="">
            </a>
        </div>
    <?php } ?>
    </div>
</div>
