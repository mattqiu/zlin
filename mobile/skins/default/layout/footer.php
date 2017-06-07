<?php defined('InIMall') or exit('Access Invalid!');?>
<!-- HTML5 Support for IE -->
<!--[if lt IE 9]>
<script src="<?php echo MOBILE_SKINS_URL;?>/js/html5shim.js"></script>
<![endif]-->
<script src="<?php echo MOBILE_SKINS_URL;?>/js/bootstrap.min.js"></script>
<script src="<?php echo MOBILE_SKINS_URL;?>/js/bootstrap-switch.min.js"></script>
<script src="<?php echo MOBILE_SKINS_URL;?>/js/jquery-confirm.min.js"></script>

<script>
    jconfirm.defaults = {
        title: 'Hello',
        content: 'Are you sure to continue?',
        contentLoaded: function(){
        },
        icon: '',
        confirmButton: '确认',
        cancelButton: '取消',
        confirmButtonClass: 'btn-default',
        cancelButtonClass: 'btn-default',
        theme: 'white',
        animation: 'zoom',
        closeAnimation: 'scale',
        animationSpeed: 400,
        animationBounce: 1.2,
        keyboardEnabled: false,
        rtl: false,
        confirmKeys: [13, 32], // ENTER or SPACE key
        cancelKeys: [27], // ESC key
        container: 'body',
        confirm: function () {
        },
        cancel: function () {
        },
        backgroundDismiss: true,
        autoClose: false,
        closeIcon: true,
        columnClass: 'col-md-6 col-md-offset-3',
        onOpen: function(){
        },
        onClose: function(){
        },
        onAction: function(){
        }
    };

</script>
<script>
    function getQueryString(e) {
        var t = new RegExp("(^|&)" + e + "=([^&]*)(&|$)");
        var a = window.location.search.substr(1).match(t);

        if (a != null) return a[2];
        return ""
    }
    var goodstate=getQueryString('goods_state');
    if(goodstate==0){
        var g='<input id="selectAll" type="checkbox"/>全选<a class="f_a1" id="delGoods" href="#">删除</a><a class="state f_a2" href="#" state="1">上架</a>';
        $('.navbar-fixed-bottom').empty().append(g);
    }else if(goodstate==1) {
        var g = '<input id="selectAll" type="checkbox"/>全选<a class="f_a1" id="delGoods" href="#">删除</a><a class="state f_a2" href="#" state="0">下架</a>';
        $('.navbar-fixed-bottom').empty().append(g);;
    }

    $('#selectAll').click(function(){
        $('input[type=checkbox]').prop('checked', $(this).prop('checked'));

    });
    $('#Off_shelf').click(function () {

    })

    $('.state').click(function() {
        var ids = '';
        $(".goods_id").each(function () {
            if (this.checked) {
                ids += $(this).val() + ',';
            }
        });
        if (ids != '') {
            ids = ids.substring(0, ids.length - 1)
            var state = $(this).attr('state');
            var title = '上架';
            if (state == 0) {
                title = '下架';
            }
            $.confirm({
                title: '警告',
                content: '确定' + title + '所选商品？',
                confirm: function () {

                    document.location.href = 'index.php?act=goods&goods_state=<?php echo $_GET['goods_state']?>&state=' + state + '&id=' + ids + '&name=state&all=<?php echo $_GET['all'];?>';
                },
                cancel: function () {

                }
            });
        }

        $('#delGoods').click(function () {
            var ids = '';
            $(".goods_id").each(function () {
                if (this.checked) {
                    ids += $(this).val() + ',';
                }
            });
            if (ids != '') {
                ids = ids.substring(0, ids.length - 1)
                $.confirm({
                    title: '警告',
                    content: '确定删除所选商品？',
                    confirm: function () {
                        document.location.href = 'index.php?act=goods&goods_state=<?php echo $_GET['goods_state']?>&id=' + ids + '&name=del&all=<?php echo $_GET['all'];?>';

                    },
                    cancel: function () {

                    }
                });
            }
        });
    })
</script>
</body>
</html>