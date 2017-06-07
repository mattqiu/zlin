<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="imsc-btn imsc-btn-green" im_type="dialog" dialog_title="添加分店" dialog_id="my_branch_add" dialog_width="600" uri="<?php echo urlShop('seller_branch', 'branch_edit');?>" title="添加分店">添加分店</a>
</div>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w30">编号</th>
      <th>店铺帐号</th>
      <th>店铺名称</th>
      <th>店主</th>      
      <th>店铺等级</th>
      <th>支付方式</th>
      <th>店主手机</th>
      <th>店主QQ</th>
      <th>加入时间</th>
      <th class="w120">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['branch_list']) && is_array($output['branch_list'])) { $i=0;?>
    <?php foreach($output['branch_list'] as $v) { ++$i;?>
    <tr class="bd-line">
      <td><?php echo $i;?></td>
      <td><?php echo $v['seller_name'];?></td>
      <td><?php echo $v['store_name'];?></td>
      <td><?php echo $v['member_truename'];?></td>
      <td><?php echo $v['grade_name'];?></td>
      <td><?php if ($v['payment_method']==0){echo '支付到卖家';} elseif ($v['payment_method']==1){echo '支付到平台';} else {echo '支付到总店';}?></td>
      <td><?php echo $v['member_mobile'];?></td>
      <td><?php echo $v['member_qq'];?></td>
      <td><?php echo date("Y-m-d",$v['store_time']);?></td>
      <td>
        <a href="javascript:void(0)" im_type="dialog" dialog_title="修改分店信息" dialog_id="my_category_add" dialog_width="480" uri="<?php echo urlShop('seller_branch', 'branch_edit',array('branch_id'=>$v['store_id']));?>" title="修改分店信息">修改</a>|
        <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlShop('seller_branch', 'branch_del',array('branch_id'=>$v['store_id']));?>')">删除</a>
      </td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无分店信息</span></div></td>
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