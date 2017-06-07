<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>友情连接</h3>
        <h5>查看和编辑合作伙伴及友情链接</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>通过合作伙伴管理你可以，编辑、查看、删除合作伙伴信息</li>
      <li>在搜索处点击图片则表示将搜索图片标识仅为图片的相关信息，点击文字则表示将搜索图片标识仅为文字的相关信息，点击全部则搜索所有相关信息</li>
    </ul>
  </div>
  <div id="flexigrid"></div>  
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=link&op=get_xml',
        colModel : [
		    {display: '操作', name : 'operation', width : 150, sortable : false, align: 'center'},
            {display: '序号', name : 'link_sort', width : 40, sortable : true, align: 'center'},
            {display: '合作伙伴', name : 'link_title', width : 200, sortable : true, align: 'left'},
			{display: '图片标识', name : 'link_pic', width : 100, sortable : true, align: 'left'},
			{display: '链接', name : 'link_url', width : 200, sortable : true, align: 'center', className: 'normal'}      
            ],
        buttons : [
            {display: '<i class="fa fa-plus"></i>添加合作伙伴', name : 'add', bclass : 'add', title : '添加合作伙伴', onpress : op_add }
            ],
        searchitems : [
		    {display: '合作伙伴名称', name : 'link_title'}
            ],
        sortname: "link_id",
        sortorder: "desc",
        title: '合作伙伴列表'
    });	
});
//添加
function op_add(name, bDiv) {
	window.location.href = 'index.php?act=link&op=link_add';
}

//编辑
function op_edit(name, bDiv) {
	window.location.href = 'index.php?act=link&op=link_edit&link_id=' + name;
}

//删除
function op_del(id) {
    if(!confirm('删除后将不能恢复，确认删除这项吗？')){
        return false;
    }
    $.getJSON('index.php?act=link&op=link_del', {link_id:id}, function(data){
        if (data.state) {
            $("#flexigrid").flexReload();
        } else {
            showError(data.msg)
        }
    });
}
</script> 