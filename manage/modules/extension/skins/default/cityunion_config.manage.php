<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>城市联盟管理</h3>
        <h5>设置平台城市联盟体系的相关数据</h5>
      </div>
      <?php echo $output['top_link'];?></div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>高管补贴：以高管管理城市合伙人人数+团队人数+有效层内销售额+销售单数为衡量标准。</li>
      <li>补贴数额 = 销售额X管理奖比率X补贴比率。</li>
    </ul>
  </div>
  <div class="fixed-empty"></div>
  <div style="text-align: right;">
    <a class="imap-btn imap-btn-green" href="javascript:void(0)"  onclick="ajax_form('my_manage_edit','添加高管晋升补贴','<?php echo urlAdminExtension('cityunion_config','manage_edit');?>',520);"><span>添加高管晋升补贴</span></a>
  </div>
  <table class="table tb-type2">
    <thead>
      <tr>
        <th class="w12"></th>
        <th class="w120 tl">补贴名称</th>
        <th class="w84 tc">高管类型</th>
        <th class="w84 tc">合伙人数</th>
        <th class="w84 tc">团队人数</th>
        <th class="w84 tc">完成订单量</th>     
        <th class="w100 tc">完成销售额</th>      
        <th class="w84 tc">补贴比率(%)</th>
        <th class="w84 tc">补贴分享层数</th>
        <th class="w120 tc">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['manage_list']) && is_array($output['manage_list'])) { ?>
      <?php foreach($output['manage_list'] as $v) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="tl"><?php echo $v['award_name'];?></td>
        <td class="tc">
          <?php 
		    switch($v['mc_id']) {
              case 2:
                  echo '合伙人';
                  break;
              case 3:
                  echo '区代';
                  break;
              case 4:
                  echo '总代';
                  break;
          }?>
        </td>
        <td class="tc"><?php echo $v['sub_nums'];?></td>
        <td class="tc"><?php echo $v['child_nums'];?></td>
        <td class="tc"><?php echo $v['order_nums'];?></td>
        <td class="tc"><?php echo $v['achieve_val'];?></td>
        <td class="tc"><?php echo $v['award_rate'];?></td>
        <!-- <td class="tc"><?php echo $v['points_rate'];?></td> -->
        <td class="tc"><?php echo $v['award_level'];?></td>
        <td class="tc">
          <a href="javascript:void(0)" onclick="ajax_form('my_manage_edit','修改高管晋升补贴','<?php echo urlAdminExtension('cityunion_config','manage_edit',array('em_id'=>$v['em_id']));?>',520);">修改</a>|
          <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlAdminExtension('cityunion_config', 'manage_del',array('em_id'=>$v['em_id']));?>')">删除</a>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr class="no_data">
        <td colspan="15">暂无高管晋升补贴</td>
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