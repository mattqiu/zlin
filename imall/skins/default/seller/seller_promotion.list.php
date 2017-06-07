<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="imsc-btn imsc-btn-green" im_type="dialog" dialog_title="添加推广员" dialog_id="my_promotion_add" dialog_width="480" uri="<?php echo urlShop('seller_promotion', 'promotion_add');?>" title="添加股东">添加股东</a>
</div>
<table class="imsc-table-style">
  <thead>
    <tr>
      <th class="w200">推广帐号</th>
      <th class="w100">身份类型</th>
      <th class="w80">实体门店</th>
      <th class="w80">总业绩</th>
      <th class="w80">总佣金</th>
      <th class="w80">本期业绩</th>
      <th class="w80">本期佣金</th>
      <th class="w200">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($output['promotion_list']) && is_array($output['promotion_list'])) {?>
    <?php foreach($output['promotion_list'] as $v) {?>
    <tr class="bd-line">
      <td class="subordinate_name">
        <?php if($v['have_child'] > 0){ ?>
        <img fieldid="<?php echo $v['member_id'];?>" fielddeep="<?php echo $v['deep'];?>" status="open" im_type="flex" src="<?php echo SHOP_SKINS_URL;?>/images/treetable/tv-expandable.gif">
        <?php }else{ ?>
        <img fieldid="<?php echo $v['member_id'];?>" fielddeep="<?php echo $v['deep'];?>" status="close" im_type="flex" src="<?php echo SHOP_SKINS_URL;?>/images/treetable/tv-item.gif">
        <?php } ?>
        <?php echo $v['member_name'];?>     
      </td>
      <td>
	    <?php 
		  switch($v['mc_id']) {
			case 5:
				$mc_name = '股东';
				$child_name = '首席';
				echo $mc_name;
                break;
			case 4:
				$mc_name = '首席';
				$child_name = '协理';
				echo $mc_name;
                break;
			case 3:
				$mc_name = '协理';
				$child_name = '经理';
				echo $mc_name;
                break;
            case 2:
				$mc_name = '经理';
				$child_name = '下级推广员';
				echo $mc_name;
                break;
			default:
			    $mc_name = '推广员';
				$child_name = '下级推广员';
				echo $mc_name;
				break;
        }?>
      </td>
      <td><?php echo $v['mc_real']==10?'是':'否';?></td>
      <td><?php echo $v['total_sales'];?></td>
      <td><?php echo $v['total_commis'];?></td>
      <td><?php echo $v['curr_sales'];?></td>
      <td><?php echo $v['curr_commis'];?></td>
      <td>
        <?php if ($v['mc_real']==10){?>
        <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要关闭实体门店吗?','<?php echo urlShop('seller_promotion', 'promotion_closereal',array('promotion_id'=>$v['member_id']));?>')">关闭门店</a>|
        <?php }else{?>
        <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要开通实体门店吗?','<?php echo urlShop('seller_promotion', 'promotion_openreal',array('promotion_id'=>$v['member_id']));?>')">开通门店</a>|
        <?php }?>
        <a href="javascript:void(0)" im_type="dialog" dialog_title="查看<?php echo $mc_name;?>信息" dialog_id="my_promotion_info" dialog_width="480" uri="<?php echo urlShop('seller_promotion', 'promotion_info',array('promotion_id'=>$v['member_id']));?>" title="查看<?php echo $mc_name;?>信息">查看</a>|
        <a href="javascript:void(0)" im_type="dialog" dialog_title="修改<?php echo $mc_name;?>信息" dialog_id="my_promotion_edit" dialog_width="480" uri="<?php echo urlShop('seller_promotion', 'promotion_edit',array('promotion_id'=>$v['member_id']));?>" title="修改<?php echo $mc_name;?>信息">修改</a>|
        <a href="javascript:void(0)" im_type="dialog" dialog_title="添加<?php echo $child_name;?>" dialog_id="my_promotion_add" dialog_width="480" uri="<?php echo urlShop('seller_promotion', 'promotion_add',array('parent_id'=>$v['member_id']));?>" title="添加<?php echo $child_name;?>">添加</a>|
        <a href="<?php echo urlShop('seller_promotion', 'promotion_detail',array('promotion_id'=>$v['member_id']));?>" title="查看业绩明细">明细</a>|        
        <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlShop('seller_promotion', 'promotion_del',array('promotion_id'=>$v['member_id']));?>')">删除</a>
      </td>
    </tr>
    <?php }?>
    <?php } else { ?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无推广员信息</span></div></td>
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

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.promotion_tree.js" charset="utf-8"></script> 