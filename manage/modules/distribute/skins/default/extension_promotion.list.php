<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>推广管理</h3>
        <h5>管理平台推广员及申请</h5>
      </div>
      <?php echo $output['top_link'];?></div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>平台推广员管理。</li>
    </ul>
  </div>  

  <div class="fixed-empty"></div>
  <div style="text-align: right;">
    <a class="imap-btn imap-btn-green" href="javascript:void(0)"  onclick="ajax_form('my_promotion_add','添加平台代理','<?php echo urlAdminExtension('extension_promotion','promotion_add');?>',520);"><span>添加代理</span></a>
  </div>
  <table class="table tb-type2">
    <thead>
      <tr>
        <th class="w200 tl">代理帐号</th>
        <th class="w100 tc">代理类型</th>
        <th class="w80 tc">实体门店</th>
        <th class="w80 tc">总业绩</th>
        <th class="w80 tc">总佣金</th>
        <th class="w80 tc">本期业绩</th>
        <th class="w80 tc">本期佣金</th>
        <th class="w200 tc">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['promotion_list']) && is_array($output['promotion_list'])) {?>
      <?php foreach($output['promotion_list'] as $v) {?>
      <tr class="bd-line">
        <td class="subordinate_name tl">
          <?php if($v['have_child'] > 0){ ?>
          <img fieldid="<?php echo $v['member_id'];?>" fielddeep="<?php echo $v['deep'];?>" status="open" im_type="flex" src="<?php echo ADMIN_SKINS_URL;?>/images/tv-expandable.gif">
          <?php }else{ ?>
          <img fieldid="<?php echo $v['member_id'];?>" fielddeep="<?php echo $v['deep'];?>" status="close" im_type="flex" src="<?php echo ADMIN_SKINS_URL;?>/images/tv-item.gif">
          <?php } ?>
          <?php echo $v['member_name'];?>     
        </td>
        <td class="tc">
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
				$child_name = '下级代理';
				echo $mc_name;
                break;
			default:
			    $mc_name = '代理';
				$child_name = '下级代理';
				echo $mc_name;
				break;
	      }?>
        </td>
        <td class="tc"><?php echo $v['mc_real']==10?'是':'否';?></td>
        <td class="tc"><?php echo $v['total_sales'];?></td>
        <td class="tc"><?php echo $v['total_commis'];?></td>
        <td class="tc"><?php echo $v['curr_sales'];?></td>
        <td class="tc"><?php echo $v['curr_commis'];?></td>
        <td class="tc">
          <?php if ($v['mc_real']==10){?>
          <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要关闭实体门店吗?','<?php echo urlAdminExtension('extension_promotion', 'promotion_closereal',array('promotion_id'=>$v['member_id']));?>')">关闭门店</a>|
          <?php }else{?>
          <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要开通实体门店吗?','<?php echo urlAdminExtension('extension_promotion', 'promotion_openreal',array('promotion_id'=>$v['member_id']));?>')">开通门店</a>|
          <?php }?>
          <a href="javascript:void(0)"  onclick="ajax_form('my_promotion_info','查看代理信息','<?php echo urlAdminExtension('extension_promotion','promotion_info',array('promotion_id'=>$v['member_id']));?>',480);" title="查看代理信息"><span>查看</span></a>|
          <a href="javascript:void(0)"  onclick="ajax_form('my_promotion_edit','修改代理信息','<?php echo urlAdminExtension('extension_promotion','promotion_edit',array('promotion_id'=>$v['member_id']));?>',480);" title="修改代理信息"><span>修改</span></a>|
          <a href="javascript:void(0)"  onclick="ajax_form('my_promotion_edit','添加下级代理','<?php echo urlAdminExtension('extension_promotion','promotion_add',array('parent_id'=>$v['member_id']));?>',480);" title="添加下级代理"><span>添加</span></a>|
          <a href="<?php echo urlAdminExtension('extension_promotion', 'promotion_detail',array('promotion_id'=>$v['member_id']));?>" title="查看业绩明细">明细</a>|        
          <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlAdminExtension('extension_promotion', 'promotion_del',array('promotion_id'=>$v['member_id']));?>')">删除</a>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr class="no_data">
        <td colspan="15">暂无推广员信息</td>
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
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.promotion_ad.js" charset="utf-8"></script> 