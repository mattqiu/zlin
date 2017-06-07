<?php defined('InIMall') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="alert mt10" style="clear:both;">
	<ul class="mt5">
		<li>1、<?php echo $lang['stat_validorder_explain'];?></li>
    <li>2、为了统计的准确性，请到菜单【店铺-实体店铺】中设置好实体店铺名称及员工数和面积。</li>
    </ul>
</div>
<form method="get" action="index.php" target="_self">
  <input type="hidden" name="act" value="statistics_general" />
  <input type="hidden" name="op" value="general" />
  <table class="search-form">
    <tr>
      <td class="tr">
    	<div class="fr">
    	  <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['im_common_search'];?>" /></label>
    	</div>
    	<div class="fr">          
    	  <div class="fl" style="margin-right:3px;">
        	<select name="search_type" id="search_type" class="querySelect">
              <option value="day" <?php echo $output['search_arr']['search_type']=='day'?'selected':''; ?>>按天统计</option>
              <option value="week" <?php echo $output['search_arr']['search_type']=='week'?'selected':''; ?>>按周统计</option>
              <option value="month" <?php echo $output['search_arr']['search_type']=='month'?'selected':''; ?>>按月统计</option>
              <option value="quarter" <?php echo $output['search_arr']['search_type']=='quarter'?'selected':''; ?>>按季统计</option>
              <option value="year" <?php echo $output['search_arr']['search_type']=='year'?'selected':''; ?>>按年统计</option>
        	</select>
          </div>
          <div id="searchtype_day" style="display:none;" class="fl">
        	<input type="text" class="text w70" name="search_time" id="search_time" value="<?php echo @date('Y-m-d',$output['search_arr']['day']['search_time']);?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>
          </div>
          <div id="searchtype_week" style="display:none;" class="fl">
            <select name="searchweek_year" class="querySelect">
              <?php foreach ($output['year_arr'] as $k=>$v){?>
              <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_year'] == $k?'selected':'';?>><?php echo $v; ?></option>
              <?php } ?>
            </select>
            <select name="searchweek_month" class="querySelect">
              <?php foreach ($output['month_arr'] as $k=>$v){?>
              <option value="<?php echo $k;?>" <?php echo $output['search_arr']['week']['current_month'] == $k?'selected':'';?>><?php echo $v; ?></option>
              <?php } ?>
            </select>
            <select name="searchweek_week" class="querySelect">
              <?php foreach ($output['week_arr'] as $k=>$v){?>
              <option value="<?php echo $v['key'];?>" <?php echo $output['search_arr']['week']['current_week'] == $v['key']?'selected':'';?>><?php echo $v['val']; ?></option>
              <?php } ?>
            </select>
          </div>          
          <div id="searchtype_month" style="display:none;" class="fl">
            <select name="searchmonth_year" class="querySelect">
              <?php foreach ($output['year_arr'] as $k=>$v){?>
              <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_year'] == $k?'selected':'';?>><?php echo $v; ?></option>
              <?php } ?>
            </select>
            <select name="searchmonth_month" class="querySelect">
              <?php foreach ($output['month_arr'] as $k=>$v){?>
              <option value="<?php echo $k;?>" <?php echo $output['search_arr']['month']['current_month'] == $k?'selected':'';?>><?php echo $v; ?></option>
              <?php } ?>
            </select>
          </div>
          <div id="searchtype_quarter" style="display:none;" class="fl">
            <select name="searchquarter_year" class="querySelect">
              <?php foreach ($output['year_arr'] as $k=>$v){?>
              <option value="<?php echo $k;?>" <?php echo $output['search_arr']['quarter']['current_year'] == $k?'selected':'';?>><?php echo $v; ?></option>
              <?php } ?>
            </select>
            <select name="searchquarter_quarter" class="querySelect">
              <?php foreach ($output['quarter_arr'] as $k=>$v){?>
              <option value="<?php echo $k;?>" <?php echo $output['search_arr']['quarter']['current_quarter'] == $k?'selected':'';?>><?php echo $v; ?></option>
              <?php } ?>
            </select>
          </div>
          <div id="searchtype_year" style="display:none;" class="fl">
            <select name="searchyear_year" class="querySelect">
              <?php foreach ($output['year_arr'] as $k=>$v){?>
              <option value="<?php echo $k;?>" <?php echo $output['search_arr']['year']['current_year'] == $k?'selected':'';?>><?php echo $v; ?></option>
              <?php } ?>
            </select>
          </div>          
        </div>
      </td>
    </tr>
  </table>
</form>
<div class="alert alert-info mt10" style="clear:both;">
    <ul class="mt5">
    <li>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺统计时间内的进店人数" class="tip fa fa-question-circle"></i>
    		客流量：<strong><?php echo $output['stat_arr']['customer'].'人';?></strong>
    	</span>
		<span class="w210 fl h30" style="display:block;">
			<i title="店铺统计时间内的成交金额" class="tip fa fa-question-circle"></i>
			成交金额：<strong><?php echo $output['stat_arr']['order_amount'].$lang['currency_zh'];?></strong>
		</span>
		<span class="w210 fl h30" style="display:block;">
			<i title="店铺统计时间内的成交人数" class="tip fa fa-question-circle"></i>
			成交人数：<strong><?php echo $output['stat_arr']['order_member'];?></strong>
		</span>
		<span class="w210 fl h30" style="display:block;">
			<i title="店铺统计时间内的成交商品数量" class="tip fa fa-question-circle"></i>
			成交商品数：<strong><?php echo $output['stat_arr']['order_goods'];?></strong>
		</span>
    </li>
    <li>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺统计时间内的平均每件商品的交易价格" class="tip fa fa-question-circle"></i>
    		件单价：<strong><?php echo $output['stat_arr']['stat_rate1'].$lang['currency_zh'];?></strong>
    	</span>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺统计时间内的平均每位消费者的消费金额" class="tip fa fa-question-circle"></i>
    		客单价：<strong><?php echo $output['stat_arr']['stat_rate2'].$lang['currency_zh'];?></strong>
    	</span>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺统计时间内的成交人数与进店人数比率" class="tip fa fa-question-circle"></i>
    		转化率：<strong><?php echo $output['stat_arr']['stat_rate3'];?>%</strong>
    	</span>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺统计时间内的成交金额与店铺面积的比率" class="tip fa fa-question-circle"></i>
    		坪效比：<strong><?php echo $output['stat_arr']['stat_rate4'];?>%</strong>
    	</span>
        <span class="w210 fl h30" style="display:block;">
    		<i title="店铺统计时间内的成交金额与店铺员工数的比率" class="tip fa fa-question-circle"></i>
    		人效比：<strong><?php echo $output['stat_arr']['stat_rate5'];?>%</strong>
    	</span>
    </li>
    <li>
    	<span class="w210 fl h30" style="display:block;">
    		<i title="店铺统计时间内的成交件数与成交人数的比率" class="tip fa fa-question-circle"></i>
    		连单率：<strong><?php echo $output['stat_arr']['stat_rate6'];?>%</strong>
    	</span>
        <span class="w210 fl h30" style="display:block;">
    		<i title="顾客二次消费次数与VIP人数的比率" class="tip fa fa-question-circle"></i>
    		回头率：<strong><?php echo $output['stat_arr']['stat_rate7'];?>%</strong>
    	</span>
    </li>
  </ul>
  <div style="clear:both;"></div>
</div>

<div id="container"></div>

<div class="h30 cb">&nbsp;</div>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/highcharts/highcharts.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/statistics.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
 
<script>
$(function(){
	//Ajax提示
    $('.tip').poshytip({
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'center',
        alignY: 'top',
        offsetY: 5,
        allowTipHover: false
    });
	//统计数据类型
	var s_type = $("#search_type").val();
	$('#search_time').datepicker({dateFormat: 'yy-mm-dd'});
	show_searchtime();
	$("#search_type").change(function(){
		show_searchtime();
	});
	//更新周数组
	$("[name='searchweek_month']").change(function(){
		var year = $("[name='searchweek_year']").val();
		var month = $("[name='searchweek_month']").val();
		$("[name='searchweek_week']").html('');
		$.getJSON('index.php?act=index&op=getweekofmonth',{y:year,m:month},function(data){
	        if(data != null){
	        	for(var i = 0; i < data.length; i++) {
	        		$("[name='searchweek_week']").append('<option value="'+data[i].key+'">'+data[i].val+'</option>');
			    }
	        }
	    });
	});
    
	$('#container').highcharts(<?php echo $output['stat_json'];?>);
});
//展示搜索时间框
function show_searchtime(){
	s_type = $("#search_type").val();
	$("[id^='searchtype_']").hide();
	$("#searchtype_"+s_type).show();
}
</script>
