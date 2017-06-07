<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>销量分析</h3>
        <h5>平台针对销售量的各项数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <div class="imap-form-all imap-stat-general-single">
    <div class="title">
      <h3>销售收入情况一览</h3>
    </div>
    <dl class="row">
      <dd class="opt">
        <ul class="im-row">
          <li title="收款金额：<?php echo number_format($output['plat_data']['oot'],2); ?>元">
            <h4>收款金额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['plat_data']['oot'],2); ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="退款金额：<?php echo number_format($output['plat_data']['oort'],2); ?>元">
            <h4>退款金额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['plat_data']['oort'],2); ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="实收金额：<?php echo number_format($output['plat_data']['oot']-$output['plat_data']['oort'],2); ?>元">
            <h4>实收金额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['plat_data']['oot']-$output['plat_data']['oort'],2); ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="佣金总额：<?php echo number_format($output['plat_data']['oct'],2); ?>元">
            <h4>佣金总额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['plat_data']['oct'],2); ?>"></h2>
            <h6>元</h6>
          </li>
           <li title="店铺费用：<?php echo number_format($output['plat_data']['osct'],2); ?>元">
            <h4>佣金总额</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['plat_data']['osct'],2); ?>"></h2>
            <h6>元</h6>
          </li>
          <li title="总收入：<?php echo number_format($output['plat_data']['ort'],2); ?>元">
            <h4>总收入</h4>
            <h2 id="count-number" class="timer" data-speed="1500" data-to="<?php echo number_format($output['plat_data']['ort'],2); ?>"></h2>
            <h6>元</h6>
          </li>
        </ul>
      </dd>
    </dl>
  </div>
  <div id="flexigrid"></div>
  <div class="imap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="imap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>年份</dt>
            <dd>
              <label>
                <select name="search_year" id="search_year" class="s-select">
                  <?php foreach ($output['year_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $_GET['search_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
                  <?php } ?>
                </select>
              </label>
            </dd>
          </dl>
          <dl>
            <dt>月份</dt>
            <dd>
              <label>
                <select name="search_month" id="search_month" class="s-select">
                  <option value="01" <?php echo $_GET['search_month']=='01'?'selected':''; ?>>01月</option>
                  <option value="02" <?php echo $_GET['search_month']=='02'?'selected':''; ?>>02月</option>
                  <option value="03" <?php echo $_GET['search_month']=='03'?'selected':''; ?>>03月</option>
                  <option value="04" <?php echo $_GET['search_month']=='04'?'selected':''; ?>>04月</option>
                  <option value="05" <?php echo $_GET['search_month']=='05'?'selected':''; ?>>05月</option>
                  <option value="06" <?php echo $_GET['search_month']=='06'?'selected':''; ?>>06月</option>
                  <option value="07" <?php echo $_GET['search_month']=='07'?'selected':''; ?>>07月</option>
                  <option value="08" <?php echo $_GET['search_month']=='08'?'selected':''; ?>>08月</option>
                  <option value="09" <?php echo $_GET['search_month']=='09'?'selected':''; ?>>09月</option>
                  <option value="10" <?php echo $_GET['search_month']=='10'?'selected':''; ?>>10月</option>
                  <option value="11" <?php echo $_GET['search_month']=='11'?'selected':''; ?>>11月</option>
                  <option value="12" <?php echo $_GET['search_month']=='12'?'selected':''; ?>>12月</option>
                </select>
              </label>
            </dd>
          </dl>
        </div>
      </div>
      <div class="bottom"> <a href="javascript:void(0);" id="ncsubmit" class="imap-btn imap-btn-green">提交查询</a> </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/jquery.numberAnimation.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script>
<script>
function update_flex(){
	$('.imap-stat-general-single').load('index.php?act=stat_trade&op=get_plat_income&'+$("#formSearch").serialize(),
		function(){
			$('.timer').each(count);
     	});

    $("#flexigrid").flexigrid({
        url: 'index.php?act=stat_trade&op=get_income_xml&'+$("#formSearch").serialize(),
        colModel : [
            {display: '操作', name : 'operation', width : 60, sortable : false, align: 'center', className: 'handle-s'},
            {display: '店铺名称', name : 'ob_store_name', width : 150, sortable : false, align: 'center'},
            {display: '商家账号', name : 'member_name',  width : 120, sortable : false, align: 'center'},
            {display: '订单金额', name : 'ob_order_totals',  width : 100, sortable : true, align: 'center'},
            {display: '收取佣金', name : 'ob_commis_totals',  width : 60, sortable : true, align: 'center'},
            {display: '退单金额', name : 'ob_order_return_totals',  width : 100, sortable : true, align: 'center'},
            {display: '退回佣金', name : 'ob_commis_return_totals',  width : 60, sortable : true, align: 'center'},
            {display: '店铺费用', name : 'ob_store_cost_totals',  width : 100, sortable : true, align: 'center'},
            {display: '结算金额', name : 'ob_result_totals',  width : 100, sortable : true, align: 'center'}
            ],
        buttons : [
            {display: '<i class="fa fa-file-excel-o"></i>导出数据', name : 'excel', bclass : 'csv', title : '导出EXCEL文件', onpress : fg_operation }
        ],
        sortname: "ob_no",
        sortorder: "desc",
        usepager: true,
        rp: 15,
        title: '销售收入明细列表'
    });
}
$(function () {
	update_flex();
	$('#ncsubmit').click(function(){
	    $('.flexigrid').after('<div id="flexigrid"></div>').remove();
	    update_flex();
    });

	$('#searchBarOpen').click();
});
function fg_operation(name, bDiv){
    var stat_url = 'index.php?act=stat_trade&op=income';
    get_search_excel(stat_url,bDiv);
}
</script>