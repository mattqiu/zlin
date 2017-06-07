<style>
    /******************列表开始********************/
    .list{background:#fff;}
    .list_top{height:35px;width:100%;border-bottom: 1px solid #eeeeee;}
    .list_top .a_1 {display:block;float:left;text-align:center;width:16%;height:35px;line-height:35px;color:#fd6847;}
    .list_top .a_1:hover{color:#000;}
    .list_top .a_2 img{margin-left:6px;}

</style>
<div class="list auto_w">
    <div class="list_top">
        <a class="a_1" href="index.php?act=order"
            <?php if($_GET['order_state'] === '') echo 'style="color:#000;"';?>>全部</a>
        <a class="a_1" href="index.php?act=order&order_state=10"
        <?php if($_GET['order_state'] === '10') echo 'style="color:#000;"';?>>待付款</a>
        <a class="a_1" href="index.php?act=order&order_state=20"
            <?php if($_GET['order_state'] === '20') echo 'style="color:#000;"';?>>已付款</a>
        <a class="a_1" href="index.php?act=order&order_state=30"
            <?php if($_GET['order_state'] === '30') echo 'style="color:#000;"';?>>已发货</a>
        <a class="a_1" href="index.php?act=order&order_state=40"
            <?php if($_GET['order_state'] === '40') echo 'style="color:#000;"';?>>完成</a>
        <a class="a_1" href="index.php?act=order&order_state=100"
            <?php if($_GET['order_state'] === '0') echo 'style="color:#000;"';?>>取消</a>
    </div>
</div>