<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="imsc-btn imsc-btn-green" im_type="dialog" dialog_title="添加佣金类型" dialog_id="my_category_add" dialog_width="480" uri="<?php echo urlShop('seller_extension', 'commisclass_edit');?>" title="添加佣金类型"><i class="fa fa-pencil-square-o"></i>添加佣金类型</a>
</div>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w10"></th>
      <th>编号</th>
      <th>名称</th>
      <th>佣金类型</th>      
      <th>比率</th>
      <th class="w120">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['commisclass_list']) && is_array($output['commisclass_list'])) { ?>
    <?php foreach($output['commisclass_list'] as $v) { ?>
    <tr class="bd-line">
      <td></td>
      <td><?php echo $v['commis_id'];?></td>
      <td><?php echo $v['commis_name'];?></td>
      <td>
	    <?php if ($v['commis_class']==0){echo '无';} elseif ($v['commis_class']==1){echo '固定佣金';} elseif ($v['commis_class']==2){echo '售价的百分比';} else{echo '利润的百分比';}?>
      </td>
      <td>
        <?php 
		  if ($v['commis_class']==1){
			echo $v['commis_rate'].'元';
		  } elseif ($v['commis_class']==2){
			echo $v['commis_rate'].'%';
		  } elseif ($v['commis_class']==3){
			echo $v['commis_rate'].'%';
		  }else{
			echo '';
		  }
		?>
      </td>
      <td>
        <a href="javascript:void(0)" im_type="dialog" dialog_title="修改佣金类型" dialog_id="my_category_add" dialog_width="480" uri="<?php echo urlShop('seller_extension', 'commisclass_edit',array('commis_id'=>$v['commis_id']));?>" title="修改佣金类型">修改</a>|
        <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlShop('seller_extension', 'commisclass_del',array('commis_id'=>$v['commis_id']));?>')">删除</a>
      </td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无佣金类型</span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr class="tfoot">
      <td colspan="15"></td>
    </tr>
  </tfoot>
</table>