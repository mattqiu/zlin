<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>分类导航</h3>
        <h5>手机客户端分类导航图标/图片设置</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>设置的导航信息将显示在手机端首页</li>
    </ul>
  </div>
  <form method='post' id="form_link">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" class="sign" align="center"><i class="ico-check"></i></th>
          <th width="60" class="sort" align="center">排序</th>
          <th width="200">导航名称</th>
          <th width="60" align="center">是否显示</th>
          <th width="150" class="handle" align="center">操作</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['navigation_list']) && is_array($output['navigation_list'])){ ?>
        <?php foreach($output['navigation_list'] as $k => $v){ ?>
        <tr>
          <td class="sign" align="center"><i class="ico-check"></i></td>
          <td class="sort" align="center"><?php echo $v['mn_sort'];?></td>
          <td>            
            <a href="<?php echo $mn_href;?>" ><?php echo $v['mn_title'];?></a>
            <?php if ($v['mn_thumb'] != '') { ?>
            <a class="pic-thumb-tip" onmouseover="toolTip('<img src=<?php echo UPLOAD_SITE_URL.'/'.ATTACH_MOBILE.DS.'category' .DS.$v['mn_thumb']; ?>>')" onmouseout="toolTip()" href="javascript:void(0);"> <i class="fa fa-picture-o"></i></a>
            <?php } ?>
          </td>
          <td align="center"><?php if($v['mn_if_show']){echo $lang['im_yes'];}else{echo $lang['im_no'];}?></td>
          <td class="handle" align="center">
            <a href="javascript:if(confirm('<?php echo $lang['im_ensure_del'];?>'))window.location = 'index.php?act=mb_navigation&op=navigation_del&mn_id=<?php echo $v['mn_id'];?>';" class="btn red"><i class="fa fa-trash-o"></i><?php echo $lang['im_del'];?></a>
            <a href="index.php?act=mb_navigation&op=navigation_edit&mn_id=<?php echo $v['mn_id'];?>" class="btn blue"><i class="fa fa-pencil-square-o"></i><?php echo $lang['im_edit'];?></a>
          </td>
          <td></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr>
          <td class="no-data" colspan="100"><i class="fa fa-exclamation-triangle"></i><?php echo $lang['im_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
$(function(){
	$('.flex-table').flexigrid({
		height:'auto',// 高度自动
		usepager: false,// 不翻页
		striped:false,// 不使用斑马线
		resizable: false,// 不调节大小
		title: '移动客户端首页导航列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>新增导航项目', name : 'add', bclass : 'add', title : '新增导航项目', onpress : fg_add }
               ]
		});

    });

	function fg_add(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=mb_navigation&op=navigation_add';
    }
}
</script>