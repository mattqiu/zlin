<div class="list auto_w">
    <div class="list_top">
        <a class="a_1" href="index.php?act=goods&goods_state=1&all=<?php echo $_GET['all']?>"
        <?php if($_GET['goods_state'] === '1') echo 'style="color:#000;"';?>>销售中</a>
        <a class="a_1" href="index.php?act=goods&goods_state=0&all=<?php echo $_GET['all']?>"
            <?php if($_GET['goods_state'] === '0') echo 'style="color:#000;"';?>>未上架</a>
        <div class="dropdown">
            <a id="drop1" class="a_1 a_2 dropdown-toggle" role="button" data-toggle="dropdown" href="＃">
                <?php
                switch($_GET['order']){
                    case 'sale':
                        echo '销量排序';
                        break;
                    case 'price':
                        echo '售价排序';
                        break;
                    case 'collect':
                        echo '收藏排序';
                        break;
                    default:
                        echo '最新排序';
                }?>
                <img src="<?php echo LOGIN_TEMPLATES_URL;?>/css/images/xiala.png"/></a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="drop1" >
                <li><a tabindex="-1" href="index.php?act=goods&all=<?php echo $_GET['all']?>">最新排序</a></li>
                <li style="display: none;" class="divider"></li>
                <li style="display: none;"><a tabindex="-1" href="index.php?act=goods&order=sale&all=<?php echo $_GET['all']?>">销量排序</a></li>
                <li style="display: none;" class="divider"></li>
                <li style="display: none;"><a tabindex="-1" href="index.php?act=goods&order=price&all=<?php echo $_GET['all']?>">售价排序</a></li>
                <li style="display: none;" class="divider"></li>
                <li style="display: none;"><a tabindex="-1" href="index.php?act=goods&order=collect&all=<?php echo $_GET['all']?>">收藏排序</a></li>
            </ul>
        </div>
    </div>
</div>