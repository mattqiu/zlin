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
      <li>导购补贴：以极差至的方式，但和高管补贴的方式是相反的，从实习往上级店长来分配，即实习分配比例最高。</li>
      <li>补贴数额 = 实际销售额*管理奖比率*补贴比率。</li>
    </ul>
  </div>
  <div class="fixed-empty"></div>
  <div style="text-align: right;">
    <a class="imap-btn imap-btn-green" href="javascript:void(0)"  onclick="ajax_form('my_saleman_edit','添加导购服务补贴','<?php echo urlAdminExtension('extension_config','saleman_edit');?>',520);"><span>添加导购服务补贴</span></a>
  </div>
  <table class="table tb-type2">
    <thead>
      <tr>
        <th class="w12"></th>
        <th class="w120 tl">补贴名称</th>
        <th class="w84 tc">导购类型</th>
        <th class="w84 tc">保底薪资</th>
        <th class="w84 tc">服务人数</th>
        <th class="w84 tc">服务单量</th>     
        <th class="w100 tc">服务业绩</th>      
        <th class="w84 tc">补贴比率(%)</th>
        <th class="w120 tc">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['saleman_list']) && is_array($output['saleman_list'])) { ?>
      <?php foreach($output['saleman_list'] as $v) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="tl"><?php echo $v['award_name'];?></td>
        <td class="tc">
          <?php 
		    switch($v['mc_id']) {
              case 12:
                  echo '导购';
                  break;
              case 11:
                  echo '经理';
                  break;
              case 10:
                  echo '店长';
                  break;
              default:
                  echo '兼职';
                  break;
          }?>
        </td>
        <td class="tc"><?php echo $v['base_salary'];?></td>
        <td class="tc"><?php echo $v['serve_nums'];?></td>
        <td class="tc"><?php echo $v['order_nums'];?></td>
        <td class="tc"><?php echo $v['achieve_val'];?></td>
        <td class="tc"><?php echo $v['award_rate'];?></td>
        <td class="tc">
          <a href="javascript:void(0)" onclick="ajax_form('my_saleman_edit','修改导购服务补贴','<?php echo urlAdminExtension('extension_config','saleman_edit',array('sm_id'=>$v['sm_id']));?>',520);">修改</a>|
          <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlAdminExtension('extension_config', 'saleman_del',array('sm_id'=>$v['sm_id']));?>')">删除</a>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr class="no_data">
        <td colspan="15">暂无导购服务补贴</td>
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