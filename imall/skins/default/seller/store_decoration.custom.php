<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
    <a href="javascript:void(0)" class="imsc-btn imsc-btn-green" im_type="dialog" dialog_title="添加展厅装修" dialog_id="my_custom_decoration_apply" dialog_width="480" uri="index.php?act=store_decoration&op=custom_add">添加展厅</a>
  </div>
  <table class="imsc-default-table">
    <thead>
      <tr>
        <th class="w60">序号</th>
        <th class="tc">展厅名称</th>
        <th class="w300"><?php echo $lang['im_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(!empty($output['customdecoration_list'])){$i=0;?>
      <?php foreach($output['customdecoration_list'] as $key=> $value){$i++;?>
      <tr class="bd-line">
        <td><?php echo $i;?></td>
        <td class="tc"><?php echo $value['decoration_name'];?></td>
        <td class="nscs-table-handle">
          <span>
            <a href="javascript:void(0)" im_type="dialog" dialog_width="480" dialog_title="修改展厅装修" dialog_id="my_custom_decoration_apply" uri="index.php?act=store_decoration&op=custom_edit&id=<?php echo $value['decoration_id']; ?>" class="btn-blue"><i class="fa fa-pencil-square-o"></i>
            <p><?php echo $lang['im_edit'];?></p>
            </a>
          </span> 
          <span>
            <a href="javascript:void(0)" onclick="ajax_get_confirm('您确定要删除此展厅吗?', 'index.php?act=store_decoration&op=custom_del&id=<?php echo $value['decoration_id']; ?>');" class="btn-red"><i class="fa fa-trash-o"></i>
            <p><?php echo $lang['im_del'];?></p>
            </a>            
          </span>
          <a href="<?php echo urlShop('store_decoration', 'decoration_edit', array('decoration_id' => $value['decoration_id']));?>" class="imsc-btn imsc-btn-acidblue mr5" target="_blank"><i class="fa fa-puzzle-piece"></i>装修页面</a> 
          <a im_type="btn_build" href="<?php echo urlShop('store_decoration', 'decoration_build', array('decoration_id' => $value['decoration_id']));?>" class="imsc-btn imsc-btn-orange" target="_blank"><i class="fa fa-magic"></i>生成页面</a>
        </td>
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
<script type="text/javascript">
    $(document).ready(function(){
        $("[im_type='btn_build']").on('click', function() {
            $.getJSON($(this).attr('href'), function(data) {
                if(typeof data.error == 'undefined') {
                    showSucc(data.message);
                } else {
                    showError(data.error);
                }
            });
            return false;
        });
    });
</script> 