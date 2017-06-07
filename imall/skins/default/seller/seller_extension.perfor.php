<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="imsc-btn imsc-btn-green" im_type="dialog" dialog_title="添加门店补贴分配方案" dialog_id="my_perfor_edit" dialog_width="480" uri="<?php echo urlShop('seller_extension', 'perfor_edit');?>" title="添加门店补贴分配方案"><i class="fa fa-pencil-square-o"></i>添加门店补贴分配方案</a>
</div>
<div class="alert alert-block mt10">
  <ul class="mt5">
    <li>门店补贴：以推广员有效层内的推广业绩或推广单数为衡量标准。</li>
    <li>奖励数额=推广业绩X门店补贴抽佣比率X奖励比率。</li>
  </ul>
</div>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w10"></th>
      <th>奖励名称</th>
      <th>门店类型</th>
      <th>销售额</th>      
      <th>奖励比率(%)</th>
      <th>奖励分享层数</th>
      <th class="w120">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['perfor_list']) && is_array($output['perfor_list'])) { ?>
    <?php foreach($output['perfor_list'] as $v) { ?>
    <tr class="bd-line">
      <td></td>
      <td><?php echo $v['award_name'];?></td>
      <td>
        <?php 
		  switch($v['mc_id']) {
            case 2:
                echo '经理';
                break;
            case 3:
                echo '协理';
                break;
            case 4:
                echo '首席';
                break;
            case 5:
                echo '股东';
                break;
        }?>
      </td>
      <td><?php echo $v['achieve_val'];?></td>
      <td><?php echo $v['award_commis'];?></td>
      <td><?php echo $v['award_level'];?></td>
      <td>
        <a href="javascript:void(0)" im_type="dialog" dialog_title="修改绩优奖励方案" dialog_id="my_perfor_edit" dialog_width="480" uri="<?php echo urlShop('seller_extension', 'perfor_edit',array('ep_id'=>$v['ep_id']));?>" title="修改绩优奖励方案">修改</a>|
        <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlShop('seller_extension', 'perfor_del',array('ep_id'=>$v['ep_id']));?>')">删除</a>
      </td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无绩优奖励分配方案</span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <tr class="tfoot">
      <td colspan="15"></td>
    </tr>
  </tfoot>
</table>