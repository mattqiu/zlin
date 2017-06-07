<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
    <a href="<?php echo urlShop('store_navigation', 'navigation_add');?>" class="imsc-btn imsc-btn-green" title="添加导航">添加导航</a> </div>
  <table class="imsc-default-table">
    <thead>
      <tr>
        <th class="w60"><?php echo $lang['store_goods_class_sort'];?></th>
        <th class="tl"><?php echo $lang['store_navigation_name'];?></th>
        <th class="w120"><?php echo $lang['store_navigation_display'];?></th>
        <th class="w110"><?php echo $lang['im_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['navigation_list'])){?>
      <?php foreach($output['navigation_list'] as $key=> $value){?>
      <tr class="bd-line">
        <td><?php echo $value['sn_sort'];?></td>
        <?php $sn_href = empty($value['sn_url'])?urlShop('show_store', 'show_article', array('store_id' => $_SESSION['store_id'], 'sn_id' => $value['sn_id'])):$value['sn_url'];?>
        <td class="tl"><dl class="goods-name"><dt><a href="<?php echo $sn_href;?>" ><?php echo $value['sn_title'];?></a></dt></dl></td>
        <td><?php if($value['sn_if_show']){echo $lang['im_yes'];}else{echo $lang['im_no'];}?></td>
        <td class="nscs-table-handle"><span><a href="<?php echo urlShop('store_navigation', 'navigation_edit', array('sn_id' => $value['sn_id']));?>" class="btn-blue"><i class="fa fa-pencil-square-o"></i>
          <p> <?php echo $lang['im_edit'];?></p>
          </a></span><span> <a href="javascript:;" imtype="btn_del" data-sn-id="<?php echo $value['sn_id'];?>"class="btn-red"><i class="fa fa-trash-o"></i>
          <p><?php echo $lang['im_del'];?></p>
          </a></span></td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php }?>
    </tbody>
  </table>
</div>
<form id="del_form" method="post" action="<?php echo urlShop('store_navigation', 'navigation_del');?>">
  <input id="del_sn_id" name="sn_id" type="hidden" />
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('[imtype="btn_del"]').on('click', function() {
            var sn_id = $(this).attr('data-sn-id');
            if(confirm('确认删除？')) {
                $('#del_sn_id').val(sn_id);
                ajaxpost('del_form', '', '', 'onerror')
            }
        });
    });
</script>
