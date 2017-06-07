<?php defined('InIMall') or exit('Access Invalid!');?>
<style>
.imm-default-table td{text-align:left;}
</style>
<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="alert" align="center">
    <span class="mr30">直推：<strong class="mr5 red" style="font-size: 18px;"><?php echo ($output['my_subordinate']>0)?$output['my_subordinate']:0; ?></strong>人</span>
    <span class="mr30">团队：<strong class="mr5 blue" style="font-size: 18px;"><?php echo ($output['my_subordinate_all']>0)?$output['my_subordinate_all']:0; ?></strong>人</span>
    <span>待审核：<strong class="mr5 green" style="font-size: 18px;"><?php echo ($output['my_apply_count']>0)?$output['my_apply_count']:0; ?></strong>人</span>
  </div>

  <table class="imm-default-table">
    <thead>
      <tr>
        <th class="w200">推广帐号</th>
        <th class="w100">身份类型</th>
        <th class="w80">总业绩</th>
        <th class="w80">总佣金</th>
        <th class="w80">本期业绩</th>
        <th class="w80">本期佣金</th>
        <th class="w100">操作</th>
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
				$child_name = '代理';
				echo $mc_name;
                break;
			default:
			    $mc_name = '代理';
				$child_name = '代理';
				echo $mc_name;
				break;
		  }
		?>
		</td>
        <td><?php echo $v['total_sales'];?></td>
        <td><?php echo $v['total_commis'];?></td>
        <td><?php echo $v['curr_sales'];?></td>
        <td><?php echo $v['curr_commis'];?></td>
        <td>
          <a href="javascript:void(0)" im_type="dialog" dialog_title="查看代理信息" dialog_id="my_promotion_info" dialog_width="480" uri="<?php echo urlShop('member_extension', 'promotion_info',array('promotion_id'=>$v['member_id']));?>" title="查看代理信息">查看</a>|
          <a href="<?php echo urlShop('member_extension', 'promotion_detail',array('promotion_id'=>$v['member_id']));?>" title="查看业绩明细">明细</a>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无代理信息</span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr>
        <td colspan="20">
          <div class="pagination"> <?php echo $output['page'];?> </div>
        </td>
      </tr>
    </tfoot>
  </table>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.subordinate_tree.js" charset="utf-8"></script> 