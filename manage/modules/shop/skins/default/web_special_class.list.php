<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['im_cms_special_manage'];?></h3>
        <h5>商城的专辑分类管理</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['cms_article_class_list_tip1'];?></li>
    </ul>
  </div> 
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=web_special&op=get_class_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '排序', name : 'class_sort', width : 200, sortable : true, align: 'center'},
            {display: '分类名称', name : 'class_name', width : 80, sortable : false, align: 'left'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增分类', name : 'add', bclass : 'add', title : '新增分类', onpress : fg_operation }
            ],
        searchitems : [
            {display: '分类名称', name : 'class_name'}
            ],
        sortname: "class_id",
        sortorder: "desc",
        title: '专辑分类列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=web_special&op=special_class_add';
    }
}

// 删除
function fg_operation_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=web_special&op=web_special_class_drop', {class_id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>