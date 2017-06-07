<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="javascript:void(0)" class="imsc-btn imsc-btn-green" im_type="dialog" dialog_title="新增广告" dialog_id="my_mb_ad_add" dialog_width="480" uri="<?php echo urlShop('mb_ad', 'mb_ad_add');?>" title="新增广告">新增广告</a>
</div>
  
<table class="imsc-default-table" id="my_category" server="index.php?act=store_goods_class&op=goods_class_ajax" >
  <thead>
    <tr im_type="table_header">
      <th>&nbsp;</th>
      <th><?php echo $lang['im_sort'];?></th>
      <th><?php echo $lang['link_index_title'];?></th>
      <th><?php echo $lang['link_index_pic_sign'];?></th>
      <th>广告链接</th>
      <th class="align-center"><?php echo $lang['im_handle'];?></th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($output['link_list']) && is_array($output['link_list'])){ ?>
    <?php foreach($output['link_list'] as $k => $v){ ?>
    <tr class="hover edit">
      <td class="w24"></td>
      <td class="w48 sort"><span class="tooltip editable" title="<?php echo $lang['im_editable'];?>" ajax_branch='link_sort' datatype="number" fieldid="<?php echo $v['link_id'];?>" fieldname="link_sort" im_type="inline_edit"><?php echo $v['link_sort'];?></span></td>
      <td><?php echo $v['link_title'];?></td>
      <td class="picture">
	  <?php 
	    if($v['link_pic'] != ''){
		  echo "<div class='size-88x31'><span class='thumb size-88x31'><i></i><img width=\"88\" height=\"31\" src='".$v['link_pic_url']."' onload='javascript:DrawImage(this,88,31);' /></span></div>";
		}
	  ?>
      </td>
      <td><?php echo $v['link_keyword'];?></td>
      <td class="w96 align-center">
        <a href="javascript:void(0)" im_type="dialog" dialog_title="修改广告" dialog_id="my_mb_ad_edit" dialog_width="480" uri="<?php echo urlShop('mb_ad', 'mb_ad_edit',array('link_id'=>$v['link_id']));?>" title="修改广告">修改</a>| 
        <a href="javascript:void(0)" onclick="javascript:ajax_get_confirm('真的要删除吗?','index.php?act=mb_ad&op=mb_ad_del&link_id=<?php echo $v['link_id'];?>')">删除</a>　
      </td>
    </tr>
    <?php } ?>
    <?php }else { ?>
    <tr class="no_data">
      <td colspan="10"><?php echo $lang['im_no_record'];?></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if(!empty($output['link_list']) && is_array($output['link_list'])){ ?>
    <tr class="tfoot" id="dataFuncs">
      <td></td>
      <td colspan="16" id="batchAction">
        <div class="pagination"> <?php echo $output['page'];?> </div>
      </td>
    </tr>
  </tfoot>
  <?php } ?>
</table>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
