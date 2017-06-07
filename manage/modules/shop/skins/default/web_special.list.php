<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['im_cms_special_manage'];?></h3>
        <h5>商城的专辑及专辑内容管理</h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li><?php echo $lang['cms_special_list_tip1'];?></li>
      <li>专题类型分为资讯和商城，资讯专题将出现在资讯频道内，商城专题出现在商城使用商城统一风格</li>
    </ul>
  </div> 
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=web_special&op=get_special_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center', className: 'handle'},
            {display: '标题', name : 'special_title', width : 200, sortable : true, align: 'center'},
            {display: '类型', name : 'special_type', width : 80, sortable : false, align: 'left'},
            {display: '封面', name : 'special_image', width : 100, sortable : true, align: 'center'},
            {display: '描述', name : 'special_desc', width : 200, sortable : true, align: 'center'},
            {display: '状态', name : 'special_state', width : 80, sortable : false, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>新增专题', name : 'add', bclass : 'add', title : '新增专题', onpress : fg_operation }
            ],
        searchitems : [
            {display: '专辑标题', name : 'special_title'}
            ],
        sortname: "special_id",
        sortorder: "desc",
        title: '专辑列表'
    });
});

function fg_operation(name, bDiv) {
    if (name == 'add') {
        window.location.href = 'index.php?act=web_special&op=web_special_add';
    }
}

// 删除
function fg_operation_del(id) {
    if(confirm('删除后将不能恢复，确认删除这项吗？')){
        $.getJSON('index.php?act=web_special&op=web_special_drop', {special_id:id}, function(data){
            if (data.state) {
                $("#flexigrid").flexReload();
            } else {
                showError(data.msg)
            }
        });
    }
}
</script>