<?php defined('InIMall') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="imsc-btn imsc-btn-green" onclick="go('index.php?act=store_account&op=account_add');" title="添加账号">添加账号</a> </div>
<table class="imsc-default-table">
  <thead>
    <tr><th class="w10"></th>
      <th class="w60">账号名</th>
      <th class="w60">昵称</th>
      <th class="w80">身份</th>
      <th class="w80">管辖导购</th>
      <th class="w200">账号组</th>
      <th class="w100"><?php echo $lang['im_handle'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($output['seller_list']) && is_array($output['seller_list'])){?>
    <?php foreach($output['seller_list'] as $key => $value){?>
    <tr class="bd-line">
    <td></td>
      <td class="w60"><?php echo $value['seller_name'];?></td>
      <td class="w60"><?php echo $value['nick_name'];?></td>
      <td class="w60">
      	<?php if($value['is_owner']==1){?>
      		店长
      	<?php } elseif($value['is_owner']==2){?>
      		导购员
      	<?php } else {?>
          	员工
        <?php } ?>
      </td>
      <td>
     	<?php if(!empty($value['salemen'])){?>
      		<?php echo $value['salemen'];?>
      	<?php } else {?>
          	暂无
        <?php } ?>
      </td>
      <td><?php echo $output['seller_group_array'][$value['seller_group_id']]['group_name'];?></td>
      <td class="nscs-table-handle">
          <span><a href="<?php echo urlShop('store_account', 'account_edit', array('seller_id' => $value['seller_id']));?>" class="btn-blue"><i class="fa fa-pencil-square-o"></i>
        <p><?php echo $lang['im_edit'];?></p></a>
          </span><span><a imtype="btn_del_account" data-seller-id="<?php echo $value['seller_id'];?>" href="javascript:;" class="btn-red"><i class="fa fa-trash-o"></i>
        <p><?php echo $lang['im_del'];?></p></a></span>
      </td>
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
<form id="del_form" method="post" action="<?php echo urlShop('store_account', 'account_del');?>">
  <input id="del_seller_id" name="seller_id" type="hidden" />
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('[imtype="btn_del_account"]').on('click', function() {
            var seller_id = $(this).attr('data-seller-id');
            if(confirm('确认删除？')) {
                $('#del_seller_id').val(seller_id);
                ajaxpost('del_form', '', '', 'onerror');
            }
        });
    });
</script> 
