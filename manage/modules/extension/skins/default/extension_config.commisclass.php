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
      <li>推广佣金模板：用于店铺发布商品时快速选择适用于该商品的推广佣金。</li>
    </ul>
  </div>
  
  <div class="fixed-empty"></div>
  <div style="text-align: right;">
    <a class="imap-btn imap-btn-green" href="javascript:void(0)"  onclick="ajax_form('my_commisclass_edit','添加推广佣金模板','<?php echo urlAdminExtension('extension_config','commisclass_edit');?>',520);"><span>添加推广佣金模板</span></a>
  </div>
  <table class="table tb-type2">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w80 tl">模板编号</th>
        <th class="w120 tl">模板名称</th>
        <th class="w100 tc">返佣方式</th>
        <th class="w100 tc">佣金类型</th>      
        <th class="w100 tc">比率</th>
        <th class="w120 tc">操作</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($output['commisclass_list']) && is_array($output['commisclass_list'])) { ?>
      <?php foreach($output['commisclass_list'] as $v) { ?>
      <tr class="hover">
        <td></td>
        <td class="tl"><?php echo $v['commis_id'];?></td>
        <td class="tl"><?php echo $v['commis_name'];?></td>
        <td class="tc">
          <?php 
		    switch($v['commis_mode']) {
              case 0:
                  echo '积分';
                  break;
              case 1:
                  echo '云币';
                  break;
              case 2:
                  echo '现金';
                  break;
              default:
                  echo '余额';
                  break;
          }?>
        </td>
        <td class="tc">
	      <?php if ($v['commis_class']==0){echo '店铺自定';} elseif ($v['commis_class']==1){echo '固定佣金';} elseif ($v['commis_class']==2){echo '售价的百分比';} else{echo '利润的百分比';}?>
        </td>
        <td class="tc">
          <?php 
		    if ($v['commis_class']==1){
		    	if($v['commis_rate']==1){
		    		echo $v['commis_mode'].'云币';
		    	}else{
				  echo $v['commis_rate'].'元';
		    	}
		    } elseif ($v['commis_class']==2){
			  echo $v['commis_rate'].'%';
		    } elseif ($v['commis_class']==3){
			  echo $v['commis_rate'].'%';
		    }else{
			  echo $v['commis_rate'].'';
		    }
		  ?>
        </td>
        <td class="tc">
          <a href="javascript:void(0)" onclick="ajax_form('my_commisclass_edit','修改推广佣金模板','<?php echo urlAdminExtension('extension_config','commisclass_edit',array('commis_id'=>$v['commis_id']));?>',520);">修改</a>|
          <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','<?php echo urlAdminExtension('extension_config', 'commisclass_del',array('commis_id'=>$v['commis_id']));?>')">删除</a>
        </td>
      </tr>
      <?php }?>
      <?php } else { ?>
      <tr class="no_data">
        <td colspan="15">暂无推广佣金模板</td>
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