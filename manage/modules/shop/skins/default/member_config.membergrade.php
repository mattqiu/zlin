<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>会员等级管理</h3>
        <h5>商城会员等级使用的折扣</h5>
      </div>
      <?php echo $output['top_link'];?></div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>“会员等级设置” 添加完成后，当会员在购买商品的时候，根据会员级别来判定商品的会员价，所有模式下都将收到该比例的影响。</li>
      <li>VIP模式：开启后，会员等级越高实际购买价越低。<br>
            	折扣店模式：开启后，会员等级越高使用云币购买就越少。<br>
            	品牌店模式：开启后，会员等级越高返利积分就越多。<br>
            	会员店模式：开启后，会员等级越高可用云币抵扣越多。</li>
    </ul>
  </div>
  <div class="fixed-empty"></div>
  <div style="text-align: right;">
    <a class="imap-btn imap-btn-green" href="javascript:void(0)"  onclick="ajax_form('my_membergrade_edit','添加会员等级购物云币比率','<?php echo urlAdminShop('member','membergrade_edit');?>',520);"><span>添加会员等级购物云币比率</span></a>
  </div>
  <table class="table tb-type2">
    <thead>
      <tr>
        <th class="w12"></th>
        <th class="w120 tl">会员等级名称</th>
        <th class="w84 tc">会员级别</th>
        <th class="w84 tc">团队人数</th>
        <th class="w84 tc">完成订单量</th>     
        <th class="w100 tc">完成销售额</th>
        <th class="w84 tc">会员等级折扣(%)</th>
        <th class="w120 tc">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['membergrade_list']) && is_array($output['membergrade_list'])) { ?>
      <?php foreach($output['membergrade_list'] as $v) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="tl"><?php echo $v['grade_name'];?></td>
        <td class="tc">V<?php echo $v['grade_level'];?></td>
        <td class="tc"><?php echo $v['child_nums'];?></td>
        <td class="tc"><?php echo $v['order_nums'];?></td>
        <td class="tc"><?php echo $v['team_amount'];?></td>
        <td class="tc"><?php echo $v['level_rate'];?>%</td>
        <td class="tc">
          <a href="javascript:void(0)" onclick="ajax_form('my_membergrade_edit','修改购物云币比率','<?php echo urlAdminShop('member','membergrade_edit',array('mg_id'=>$v['mg_id']));?>',520);">修改</a>|
          <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlAdminShop('member', 'membergrade_del',array('mg_id'=>$v['mg_id']));?>')">删除</a>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr class="no_data">
        <td colspan="15">暂无购物云币比率</td>
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