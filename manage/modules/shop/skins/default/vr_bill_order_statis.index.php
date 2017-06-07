<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>虚拟订单结算</h3>
        <h5>虚拟商品订单结算索引及商家账单表</h5>
      </div>
      <ul class="tab-base im-row">
        <li><a class="current" href="JavaScript:void(0);">结算管理</a></li>
        <li><a href="index.php?act=vr_bill&op=show_statis">账单列表</a></li>
      </ul>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span>
    </div>
    <ul>
      <li>此处列出了平台每月的结算信息汇总，点击查看可以查看本月详细的店铺账单信息列表</li>
    </ul>
  </div>
  <div id="flexigrid"></div>
</div>
<script type="text/javascript">
$(function(){
    $("#flexigrid").flexigrid({
        url: 'index.php?act=vr_bill&op=get_statis_xml',
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '账单（月）', name : 'os_month', width : 70, sortable : true, align: 'center'}, 
			{display: '开始日期', name : 'os_start_date', width : 90, sortable : true, align : 'center'},           
			{display: '结束日期', name : 'os_end_date', width : 90, sortable : true, align: 'center'},
			{display: '订单金额', name : 'os_order_totals', width : 120, sortable : true, align: 'left'},
            {display: '收取佣金', name : 'os_commis_totals', width : 70, sortable : true, align: 'left'},
            {display: '本期应结', name : 'os_result_totals', width : 90, sortable : true, align: 'left'}                     
            ],
        searchitems : [
           {display: '年份', name : 'os_year'}
        ],
        sortname: "os_month",
        sortorder: "desc",
        title: '虚拟结算单按月汇总列表'
    });
    $('input[name="q"]').prop('placeholder','例如：<?php echo date('Y',time());?>');
});
</script>