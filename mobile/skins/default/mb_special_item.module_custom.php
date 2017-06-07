<?php defined('InIMall') or exit('Access Invalid!');?>
      <?php if($item_edit_flag) { ?>
<table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12" class="nobg"> <div class="title nomargin">
            <h5><?php echo $lang['im_prompts'];?></h5>
            <span class="arrow"></span> </div>
        </th>
      </tr>
      <tr>
        <td><ul>
            <li>鼠标移动到内容上出现编辑按钮可以对内容进行修改</li>
            <li>操作完成后点击保存编辑按钮进行保存</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <?php } ?>
<div class="index_block custom">
      <?php if($item_edit_flag) { ?>
  <h3>自定义模块</h3>
  <?php } ?>
  <div class="title">
    <?php if($item_edit_flag) { ?>
    <h5>标题：</h5>
    <input id="custom_title" type="text" class="txt w200" name="item_data[title]" value="<?php echo $item_data['title'];?>">
    <?php } else { ?>
    <span><?php echo $item_data['title'];?></span>
    <?php } ?>
  </div>
  <?php if($item_edit_flag) { ?>
  <div imtype="item_content" style="display: none;">
       <input id="custom_content" type="text" class="txt w200" name="item_data[content]" value="<?php echo $item_data['content'];?>">
  </div>
  <?php } ?>
  <div class="content">
    <?php if($item_edit_flag) { ?>
    <h5>内容：</h5>    
    <?php showEditor('content',$item_data['content'],'80%','480px','visibility:hidden;',"true","true",'simple');?>
    <?php }else{ ?>
    <?php echo html_entity_decode($item_data['content']);?>
    <?php } ?>
  </div>
</div>
