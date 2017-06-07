<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="imsc-btn imsc-btn-green" im_type="dialog" dialog_title="添加导购员" dialog_id="my_saleman_add" dialog_width="480" uri="<?php echo urlShop('seller_saleman', 'saleman_edit');?>" title="添加导购员">添加导购员</a>
</div>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w30">编号</th>
      <th class="w100">帐号</th>
      <th class="w100">姓名</th>
      <th class="w30">性别</th>      
      <th class="w100">手机</th>
      <th class="w80">累计业绩</th>
      <th class="w80">累计佣金</th>
      <th class="w80">本期业绩</th>
      <th class="w80">本期佣金</th>
      <th class="w150">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['saleman_list']) && is_array($output['saleman_list'])) { $i=0;?>
    <?php foreach($output['saleman_list'] as $v) { ++$i;?>
    <tr class="bd-line">
      <td><?php echo $i;?></td>
      <td><?php echo $v['member_name'];?></td>
      <td><?php echo $v['member_truename'];?></td>
      <td>
	    <?php if ($v['member_sex']==0){echo '保密';} elseif ($v['member_sex']==1){echo '男';} else{echo '女';}?>
      </td>
      <td><?php echo $v['member_mobile'];?></td>
      <td><?php echo $v['total_sales'];?></td>
      <td><?php echo $v['total_commis'];?></td>
      <td><?php echo $v['curr_sales'];?></td>
      <td><?php echo $v['curr_commis'];?></td>
      <td>
        <a href="javascript:void(0)" im_type="dialog" dialog_title="查看导购员信息" dialog_id="my_saleman_info" dialog_width="480" uri="<?php echo urlShop('seller_saleman', 'saleman_info',array('saleman_id'=>$v['member_id']));?>" title="查看导购员信息">查看</a>|
        <a href="javascript:void(0)" im_type="dialog" dialog_title="修改导购员信息" dialog_id="my_saleman_add" dialog_width="480" uri="<?php echo urlShop('seller_saleman', 'saleman_edit',array('saleman_id'=>$v['member_id']));?>" title="修改导购员信息">修改</a>|
        <a href="<?php echo urlShop('seller_saleman', 'saleman_detail',array('saleman_id'=>$v['member_id']));?>" title="查看业绩明细">明细</a>|    
        <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlShop('seller_saleman', 'saleman_del',array('saleman_id'=>$v['member_id']));?>')">删除</a>
      </td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无导购员信息</span></div></td>
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