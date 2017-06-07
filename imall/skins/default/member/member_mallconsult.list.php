<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
    <a class="imm-btn imm-btn-orange" href="<?php echo urlShop('member_mallconsult', 'add_mallconsult');?>"><i class="fa fa-comments"></i>平台客服</a></div>
  <table class="imm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="tl">咨询内容</th>
        <th class="w150">咨询时间</th>
        <th class="w150">状态</th>
        <th class="w110">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php  if (!empty($output['consult_list'])) { ?>
      <?php foreach($output['consult_list'] as $val) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="tl"><?php echo $val['mc_content'];?></td>
        <td><?php echo date('Y-m-d H:i:s', $val['mc_addtime']);?></td>
        <td class=""><?php echo $output['state'][$val['is_reply']];?></td>
        <td class="imm-table-handle">
          <span><a href="<?php echo urlShop('member_mallconsult', 'mallconsult_info', array('id' => $val['mc_id']));?>" class="btn-blue"><i class="fa fa-eye"></i><p>查看</p></a></span>
          <span><a href="javascript:void(0);" onclick="ajax_get_confirm('<?php echo $lang['im_common_op_confirm'];?>', '<?php echo urlShop('member_mallconsult', 'del_mallconsult', array('id' => $val['mc_id']));?>');" class="btn-blue"><i class="fa fa-trash-o"></i><p>删除</p></a></span>
        </td>
      </tr>
      <?php } ?>
      <?php } else {?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (!empty($output['consult_list'])) { ?>
      <tr>
        <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
