<?php defined('InIMall') or exit('Access Invalid!'); ?>

<div class="index_block goods">
    <?php if(!empty($vv['title'])) {?>
    <div class="title"><?php echo $vv['title']; ?></div>
    <?php } ?>
    <div imtype="item_content" class="content">
    <?php foreach ((array) $vv['item'] as $item) { ?>
        <div imtype="item_image" class="goods-item">
            <a imtype="btn_item" href="javascript:;" data-type="goods" data-data="<?php echo $item['goods_id']; ?>">
                <div class="goods-item-pic"><img imtype="goods_image" src="<?php echo $item['goods_image']; ?>" alt=""></div>
                <div class="goods-item-name"><?php echo $item['goods_name']; ?></div>
                <div class="goods-item-price">￥<?php echo $item['goods_promotion_price']; ?></div>
            </a>
        </div>
    <?php } ?>
    </div>
</div>
