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
      <li>平台推广员申请管理。</li>
    </ul>
  </div>  

  <div class="fixed-empty"></div>
  <table class="table tb-type2">
    <thead>
      <tr>
        <th class="w100 tc">申请时间</th>
        <th class="w100 tc">真实姓名</th>
        <th class="w100 tc">电子邮箱</th>
        <th class="w80 tc">手机</th>
        <th class="w80 tc">QQ</th>
        <th class="w60 tc">状态</th>      
        <th class="w100 tc">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['apply_list']) && is_array($output['apply_list'])) {?>
      <?php foreach($output['apply_list'] as $v) { ++$i;?>
      <tr>
        <td class="tc"><?php echo date("Y-m-d",$v['ai_addtime']);?></td>
        <td class="tc"><?php echo $v['truename'];?></td>
        <td class="tc"><?php echo $v['email'];?></td>
        <td class="tc"><?php echo $v['mobile'];?></td>
        <td class="tc"><?php echo $v['qq'];?></td>
        <td class="tc"><?php if($v['ai_dispose']==0){echo '未处理';}elseif($v['ai_dispose']==1){echo '拒绝';}elseif($v['ai_dispose']==2){echo '同意';}?></td>
        <td class="tc">
          <a href="javascript:void(0)"  onclick="ajax_form('my_apply_info','查看申请人信息','<?php echo urlAdminExtension('extension_promotion','apply_info',array('id'=>$v['ai_id']));?>',280);" title="查看申请人信息"><span>查看</span></a>|          <?php if ($v['ai_dispose']==0){?>
          <a href="javascript:void(0)"  onclick="ajax_form('my_apply_edit','推广员申请审核','<?php echo urlAdminExtension('extension_promotion','apply_edit',array('id'=>$v['ai_id']));?>',480);" title="审核申请"><span>审核</span></a>
          <?php }else{?>
          <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlAdminExtension('extension_promotion', 'apply_del',array('id'=>$v['ai_id']));?>')">删除</a>
          <?php }?>      
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr class="no_data">
        <td colspan="15">暂无推广员申请信息</td>
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