<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w100">申请时间</th>
      <th class="w100">真实姓名</th>
      <th class="w100">电子邮箱</th>
      <th class="w80">手机</th>
      <th class="w80">QQ</th>
      <th class="w60">状态</th>      
      <th class="w100">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['apply_list']) && is_array($output['apply_list'])) {?>
    <?php foreach($output['apply_list'] as $v) { ++$i;?>
    <tr>
      <td><?php echo date("Y-m-d",$v['ai_addtime']);?></td>
      <td><?php echo $v['truename'];?></td>
      <td><?php echo $v['email'];?></td>
      <td><?php echo $v['mobile'];?></td>
      <td><?php echo $v['qq'];?></td>
      <td><?php if($v['ai_dispose']==0){echo '未处理';}elseif($v['ai_dispose']==1){echo '拒绝';}elseif($v['ai_dispose']==2){echo '同意';}?></td>
      <td>
        <a href="javascript:void(0)" im_type="dialog" dialog_title="查看申请人信息" dialog_id="my_apply_info" dialog_width="480" uri="<?php echo urlShop('seller_promotion', 'apply_info',array('id'=>$v['ai_id']));?>" title="查看申请信息">查看</a>|
        <?php if ($v['ai_dispose']==0){?>
        <a href="javascript:void(0)" im_type="dialog" dialog_title="推广员申请审核" dialog_id="my_apply_edit" dialog_width="480" uri="<?php echo urlShop('seller_promotion', 'apply_edit',array('id'=>$v['ai_id']));?>" title="审核申请">审核</a>        
        <?php }else{?>
        <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlShop('seller_promotion', 'apply_del',array('id'=>$v['ai_id']));?>')">删除</a>
        <?php }?>      
      </td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无推广员申请信息</span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr class="tfoot">
      <td colspan="10">
        <div class="pagination"> <?php echo $output['page'];?> </div>
      </td>
    </tr>
  </tfoot>
</table>