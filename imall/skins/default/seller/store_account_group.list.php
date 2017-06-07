<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="imsc-btn imsc-btn-green" onclick="go('index.php?act=store_account_group&op=group_add');" title="添加账号"><i class="fa fa-users"></i>添加组</a> </div>
<table class="imsc-default-table">
  <thead>
    <tr>
      <th class="w10"></th>
      <th class="W100">组名</th>
      <th class="w200"><?php echo $lang['im_handle'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($output['seller_group_list']) && is_array($output['seller_group_list'])){?>
    <?php foreach($output['seller_group_list'] as $key => $value){?>
    <tr class="bd-line">
      <td></td>
      <td><?php echo $value['group_name'];?></td>
      <td class="nscs-table-handle"><span><a href="<?php echo urlShop('store_account_group', 'group_edit', array('group_id' => $value['group_id']));?>" class="btn-blue"><i class="fa fa-pencil-square-o"></i>
        <p><?php echo $lang['im_edit'];?></p>
        </a></span><span><a imtype="btn_del_group" data-group-id="<?php echo $value['group_id'];?>" href="javascript:;" class="btn-red"><i class="fa fa-trash-o"></i>
        <p><?php echo $lang['im_del'];?></p>
        </a></span></td>
    </tr>
    <?php }?>
    <?php }else{?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php }?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
  </tfoot>
</table>
<form id="del_form" method="post" action="<?php echo urlShop('store_account_group', 'group_del');?>">
  <input id="del_group_id" name="group_id" type="hidden" />
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('[imtype="btn_del_group"]').on('click', function() {
            var group_id = $(this).attr('data-group-id');
            if(confirm('确认删除？')) {
                $('#del_group_id').val(group_id);
                ajaxpost('del_form', '', '', 'onerror');
            }
        });
    });
</script> 
