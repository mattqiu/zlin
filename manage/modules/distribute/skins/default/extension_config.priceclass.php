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
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> 
    </div>
    <ul>
      <li>平台定价模板：用于店铺发布商品时快速选择适用于该商品的平台定价。</li>
      <li>平台定价规则：供应商回款 + 平台扣点 + 门店租金补贴 + 3级推广 和 高管管理补贴 = 100% </li>
    </ul>
  </div>
  
  <div class="fixed-empty"></div>
  <div style="text-align: right;">
    <a class="imap-btn imap-btn-green" href="javascript:void(0)"  onclick="ajax_form('my_priceclass_edit','添加平台定价模板','<?php echo urlAdminExtension('extension_config','priceclass_edit');?>',520);"><span>添加平台定价模板</span></a>
  </div>
  <table class="table tb-type2">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w80 tl">平台定价编号</th>
        <th class="w120 tl">平台定价模板名称</th>
        <th class="w100 tc">平台定价类型</th>
        <th class="w100 tc">供应商利润比</th>
        <th class="w100 tc">供应商回款比</th>
        <th class="w120 tc">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['priceclass_list']) && is_array($output['priceclass_list'])) { ?>
      <?php foreach($output['priceclass_list'] as $v) { ?>
      <tr class="hover">
        <td></td>
        <td class="tl"><?php echo $v['pid'];?></td>
        <td class="tl"><?php echo $v['pname'];?></td>
        <td class="tc">
	      <?php if ($v['ptype']==0){echo '正常销售';} elseif ($v['ptype']==1){echo '众筹模式';} elseif ($v['ptype']==2){echo '预售商品';}elseif ($v['ptype']==3){echo '旺销商品';} else{echo '库存尾货';}?>
        </td>
        <td class="tc">
          <?php 
		    if ($v['ptype']==1){
			  echo $v['profit_rate'].'%';
		    } elseif ($v['ptype']==2){
			  echo $v['profit_rate'].'%';
		    } elseif ($v['ptype']==3){
			  echo $v['profit_rate'].'%';
		    }elseif ($v['ptype']==4){
			  echo $v['profit_rate'].'%';
		    }else{
			  echo '';
		    }
		  ?>
        </td>
        <td class="tc">
          <?php 
			  echo $v['huik_rate'].'%';
		  ?>
        </td>
        <td class="tc">
          <a href="javascript:void(0)" onclick="ajax_form('my_priceclass_edit','修改平台定价模板','<?php echo urlAdminExtension('extension_config','priceclass_edit',array('pid'=>$v['pid']));?>',520);">修改</a>|
          <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlAdminExtension('extension_config', 'priceclass_del',array('pid'=>$v['pid']));?>')">删除</a>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr class="no_data">
        <td colspan="15">暂无平台定价模板</td>
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