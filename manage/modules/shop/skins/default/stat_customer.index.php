<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>客流统计</h3>
        <h5>平台针对店铺实体店的客流数据统计</h5>
      </div>
      <?php echo $output['top_link'];?> 
    </div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> 
    </div>
    <ul>
      <li>统计图展示了实体店铺在搜索时间段内的客流走势情况</li>
    </ul>
  </div>
  <div id="container"></div>

  <div class="imap-search-ban-s" id="searchBarOpen"><i class="fa fa-search-plus"></i>高级搜索</div>
  <div class="imap-search-bar">
    <div class="handle-btn" id="searchBarClose"><i class="fa fa-search-minus"></i>收起边栏</div>
    <div class="title">
      <h3>高级搜索</h3>
    </div>
    <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="stat_customer" />
    <input type="hidden" name="op" value="customerflow" />
      <div id="searchCon" class="content">
        <div class="layout-box">
          <dl>
            <dt>按店铺统计</dt>
            <dd>
              <label><select name="search_shopname" id="search_shopname" class="s-select">
                <option value="">-请选择-</option>
                <?php foreach ($output['shop_list'] as $k => $v){ ?>
                <option value="<?php echo $v;?>" <?php echo $output['search_arr']['search_shopname'] == $v?'selected':''; ?>><?php echo $v;?></option>
                <?php } ?>
              </select>
              </label>
            </dd>
          </dl>
          <dl>
            <dt>按时间周期筛选</dt>
            <dd>
              <label>
                <select name="search_type" id="search_type" class="s-select">              
                  <option value="day" <?php echo $output['search_arr']['search_type']=='day'?'selected':''; ?>>按天统计</option>
                  <option value="week" <?php echo $output['search_arr']['search_type']=='week'?'selected':''; ?>>按周统计</option>
                  <option value="month" <?php echo $output['search_arr']['search_type']=='month'?'selected':''; ?>>按月统计</option>
                  <option value="quarter" <?php echo $output['search_arr']['search_type']=='quarter'?'selected':''; ?>>按季统计</option>
                  <option value="year" <?php echo $output['search_arr']['search_type']=='year'?'selected':''; ?>>按年统计</option>
                </select>
              </label>
            </dd>          
            <dd id="searchtype_day" style="display:none;">
              <label>
                <input class="txt" type="text" id="search_time" name="search_time" value="<?php echo @date('Y-m-d',$output['search_arr']['day']['search_time']);?>">
              </label>
            </dd>
            <dd id="searchtype_week" style="display:none;">
              <label>
                <select name="searchweek_year" class="s-select">
                  <?php foreach ($output['year_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchweek_month" class="s-select">
                  <?php foreach ($output['month_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_month'] == $k?'selected':'';?>><?php echo $v; ?>月</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchweek_week" class="s-select">
                  <?php foreach ($output['week_arr'] as $k => $v){?>
                  <option value="<?php echo $v['key'];?>" <?php echo $output['search_arr']['week']['current_week'] == $v['key']?'selected':'';?>><?php echo $v['val']; ?></option>
                  <?php } ?>
                </select>
              </label>
            </dd>
            <dd id="searchtype_month" style="display:none;">
              <label>
                <select name="searchmonth_year" class="s-select">
                  <?php foreach ($output['year_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchmonth_month" class="s-select">
                  <?php foreach ($output['month_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_month'] == $k?'selected':'';?>><?php echo $v; ?>月</option>
                  <?php } ?>
                </select>
              </label>
            </dd>
            <dd id="searchtype_quarter" style="display:none;">
              <label>
                <select name="searchquarter_year" class="s-select">
                  <?php foreach ($output['year_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['quarter']['current_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
                  <?php } ?>
                </select>
              </label>
              <label>
                <select name="searchquarter_quarter" class="s-select">
                  <?php foreach ($output['quarter_arr'] as $k=>$v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['quarter']['current_quarter'] == $k?'selected':'';?>><?php echo $v; ?></option>
                  <?php } ?>
                </select>
              </label>
            </dd>
            <dd id="searchtype_year" style="display:none;">
              <label>
                <select name="searchyear_year" class="s-select">
                  <?php foreach ($output['year_arr'] as $k => $v){?>
                  <option value="<?php echo $k;?>" <?php echo $output['search_arr']['year']['current_year'] == $k?'selected':'';?>><?php echo $v; ?>年</option>
                  <?php } ?>
                </select>
              </label>
            </dd>            
          </dl>
        </div>
      </div>
      <div class="bottom">
        <a href="javascript:void(0);" id="ncsubmit" class="imap-btn imap-btn-green">提交查询</a>
      </div>
    </form>
  </div>  
</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/Highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo ADMIN_RESOURCE_URL?>/js/statistics.js"></script>
  
<script>
$(function () {
	//统计数据类型
	var s_type = $("#search_type").val();
	$('#search_time').datepicker({dateFormat: 'yy-mm-dd'});

	show_searchtime();
	$("#search_type").change(function(){
		show_searchtime();
	});

	//更新周数组
	$("[name='search_time_month']").change(function(){
		var year = $("[name='search_time_year']").val();
		var month = $("[name='search_time_month']").val();
		$("[name='search_time_week']").html('');
		$.getJSON('<?php echo ADMIN_SITE_URL?>/index.php?act=common&op=getweekofmonth',{y:year,m:month},function(data){
	        if(data != null){
	        	for(var i = 0; i < data.length; i++) {
	        		$("[name='search_time_week']").append('<option value="'+data[i].key+'">'+data[i].val+'</option>');
			    }
	        }
	    });
	});

	$('#searchBarOpen').click();

	$('#container').highcharts(<?php echo $output['stat_json'];?>);

	$('#ncsubmit').click(function(){
    	$('#formSearch').submit();
    });

});
//展示搜索时间框
function show_searchtime(){
	s_type = $("#search_type").val();
	$("[id^='searchtype_']").hide();
	$("#searchtype_"+s_type).show();
}
</script>