<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w100">申请时间</th>
      <th class="w200">总店</th>
      <th class="w80">商品数目</th>
      <th class="w80">申请数量</th>
      <th class="w60">状态</th>      
      <th class="w100">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['apply_list']) && is_array($output['apply_list'])) {?>
    <?php foreach($output['apply_list'] as $v) { ++$i;?>
    <tr>
      <td><?php echo date("Y-m-d",$v['bp_addtime']);?></td>
      <td><?php echo $v['bp_store_name'];?></td>
      <td><?php echo $v['goods_nums'];?></td>
      <td><?php echo $v['goods_total'];?></td>
      <td>
	    <?php if($v['bp_dispose']==0){echo '未处理';}elseif($v['bp_dispose']==10){echo '拒绝';}elseif($v['bp_dispose']==20){echo '同意(待发货)';}elseif($v['bp_dispose']==30){echo '完成';}?>
      </td>
      <td>
        <a href="javascript:void(0)" im_type="dialog" dialog_title="查看补货申请信息" dialog_id="my_apply_info" dialog_width="480" uri="<?php echo urlShop('seller_distri_replenish', 'replenish_info',array('id'=>$v['bp_id']));?>" title="查看申请信息">查看</a>
        <?php if ($v['bp_dispose']!=30){?>
        <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要取消吗?','<?php echo urlShop('seller_distri_replenish', 'replenish_del',array('id'=>$v['bp_id']));?>')">|删除</a>
        <?php }?>      
      </td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无补货申请信息</span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr class="tfoot">
      <td colspan="10">
        <div class="pagination"> <?php echo $output['show_page'];?> </div>
      </td>
    </tr>
  </tfoot>
</table>