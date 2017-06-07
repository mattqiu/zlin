<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['link_index_mb_ad'];?></h3>
        <h5>手机端首页广告管理</h5>
      </div>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['link_help1'];?></li>
    </ul>
  </div>
  <form method='post' id="form_link">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="flex-table">
      <thead>
        <tr>
          <th width="24" align="center" class="sign"><i class="ico-check"></i></th>
          <th width="150" align="center" class="handle"><?php echo $lang['im_handle'];?></th>          
          <th width="100"><?php echo $lang['link_index_title'];?></th>
          <th width="200"><?php echo $lang['link_index_pic_sign'];?></th>          
          <th width="300">广告链接</th>
          <th><?php echo $lang['im_sort'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['link_list']) && is_array($output['link_list'])){ ?>
        <?php foreach($output['link_list'] as $k => $v){ ?>
        <tr class="edit">
          <td class="sign"><i class="ico-check"></i></td>
          <td class="handle">
            <a href="javascript:if(confirm('<?php echo $lang['im_ensure_del'];?>'))window.location = 'index.php?act=mb_ad&op=mb_ad_del&link_id=<?php echo $v['link_id'];?>';" class="btn red"><i class="fa fa-trash-o"></i><?php echo $lang['im_del'];?></a>
            <a href="index.php?act=mb_ad&op=mb_ad_edit&link_id=<?php echo $v['link_id'];?>" class="btn blue"><i class="fa fa-pencil-square-o"></i><?php echo $lang['im_edit'];?></a>
          </td>
          <td><?php echo $v['link_title'];?></td>
          <td class="picture">
		    <?php 
			  if($v['link_pic'] != ''){
				echo "<div class='size-88x31'><span class='thumb size-88x31'><i></i><img width=\"88\" height=\"31\" src='".$v['link_pic_url']."' onload='javascript:DrawImage(this,88,31);' /></span></div>";
			  }
			?>
          </td>
          <td><?php echo $v['link_keyword'];?></td>
          <td class="w48 sort">
            <?php echo $v['link_sort'];?>
          </td>         
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
		title: '移动客户端首页广告列表',// 表格标题
		reload: false,// 不使用刷新
		columnControl: false,// 不使用列控制
        buttons : [
                   {display: '<i class="fa fa-plus"></i>添加广告', name : 'add', bclass : 'add', title : '添加首页广告', onpress : fg_operation }
               ]
		});

    });

	function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=mb_ad&op=mb_ad_add';
    }
}
</script>
