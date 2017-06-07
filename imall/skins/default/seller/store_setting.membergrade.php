<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="imsc-btn imsc-btn-green" im_type="dialog" dialog_title="添加会员等级折扣方案" dialog_id="my_membergrade_edit" dialog_width="480" uri="<?php echo urlShop('store_setting', 'membergrade_edit');?>" title="添加会员等级折扣方案"><i class="fa fa-pencil-square-o"></i>添加会员等级折扣方案</a>
  </div>
<div class="alert alert-block mt10">
  <ul class="mt5">
      <li>“会员等级设置” 添加完成后，当会员在购买商品的时候，根据会员级别来判定商品的会员价，所有模式下都将收到该比例的影响。</li>
      <li>VIP模式：开启后，会员等级越高实际购买价越低。<br>
            	折扣店模式：开启后，会员等级越高使用云币购买就越少。<br>
            	品牌店模式：开启后，会员等级越高返利积分就越多。<br>
            	会员店模式：开启后，会员等级越高可用云币抵扣越多。</li>
    </ul>
  </div>
<table class="imsc-table-style">
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
          <a href="javascript:void(0)" onclick="ajax_form('my_membergrade_edit','修改购物云币比率','<?php echo urlShop('store_setting','membergrade_edit',array('mg_id'=>$v['mg_id']));?>',520);">修改</a>|
          <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlShop('store_setting', 'membergrade_del',array('mg_id'=>$v['mg_id']));?>')">删除</a>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
    <tr>
      <td colspan="8" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span>暂无会员等级分配方案</span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="8">
          <div class="pagination"><?php echo $output['page'];?></div>
        </td>
      </tr>
    </tfoot>
  </table>
