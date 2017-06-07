<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>推广管理</h3>
        <h5>设置平台推广体系的相关数据</h5>
      </div>
      <?php echo $output['top_link'];?></div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>门店租金补贴：以有效层内的销售额为衡量标准。</li>
      <li>租金补贴数额 = 销售额X绩优奖比率X补贴比率。</li>
    </ul>
  </div>
  
  <div class="fixed-empty"></div>
  <div style="text-align: right;">
    <a class="imap-btn imap-btn-green" href="javascript:void(0)"  onclick="ajax_form('my_perfor_edit','添加门店租金补贴方案','<?php echo urlAdminExtension('extension_config','perfor_edit');?>',520);"><span>添加门店租金补贴方案</span></a>
  </div>
  <table class="table tb-type2">
    <thead>
      <tr>
        <th class="w12"></th>
        <th class="w120 tl">补贴名称</th>
        <th class="w84 tc">门店类型</th>
        <th class="w100 tc">销售额</th>      
        <th class="w100 tc">补贴比率(%)</th>
        <th class="w100 tc">补贴分享层数</th>
        <th class="w120 tc">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['perfor_list']) && is_array($output['perfor_list'])) { ?>
      <?php foreach($output['perfor_list'] as $v) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="tl"><?php echo $v['award_name'];?></td>
        <td class="tc">
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
        <td class="tc"><?php echo $v['achieve_val'];?></td>
        <td class="tc"><?php echo $v['award_rate'];?></td>
        <td class="tc"><?php echo $v['award_level'];?></td>
        <td class="tc">
          <a href="javascript:void(0)" onclick="ajax_form('my_perfor_edit','修改门店租金补贴方案','<?php echo urlAdminExtension('extension_config','perfor_edit',array('ep_id'=>$v['ep_id']));?>',520);">修改</a>|
          <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlAdminExtension('extension_config', 'perfor_del',array('ep_id'=>$v['ep_id']));?>')">删除</a>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr class="no_data">
        <td colspan="15">暂无门店租金补贴方案</td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="16">
          <div class="pagination"><?php echo $output['page'];?></div>
        </td>
      </tr>
    </tfoot>
  </table>
</div>