<?php defined('InIMall') or exit('Access Invalid!');?>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/highcharts.js" charset="utf-8"></script>
<script type="text/javascript">
$(function () {
    var chart;
    $(document).ready(function() {
    	chart = new Highcharts.Chart({
    		chart: {
    			renderTo: 'container',
    			type: 'bar'
    		},
    		title: {
    			text: '<?php echo $output['main_title']; ?>'
    		},
    		subtitle: {
    			text: '<?php echo $output['sub_title']; ?>'
    		},
    		xAxis: {
    			categories: [<?php echo $output['result_saleman_name_str']; ?>],
    			title: {
    				text: null
    			},
    			labels: {
	   		         style: {
		   		        fontSize: '12px'
	   		         }
 		      	}
    		},
    		yAxis: {
    			min: 0,
          allowDecimals: false,
    			title: {
    				text: '<?php echo '单位：元'; ?>',
    				align: 'high'
    			},
    			labels: {
    				overflow: 'justify'
    			}
    		},
    		tooltip: {
    			formatter: function() {
    				return ''+
    					this.series.name +': '+ this.y +' <?php echo '元'; ?>';
    			}
    		},
    		plotOptions: {
    			bar: {
    				dataLabels: {
    					enabled: true
    				}
    			}
    		},
    		credits: {
    			enabled: false
    		},
    			series: [{
    			name: '<?php echo '佣金金额' ?>',
    			data: [<?php echo $output['result_commisnum_str']; ?>]
    		}]
    	});
    });
    
});
</script>

  <div class="tabmenu">
    <?php include template('layout/submenu');?>
    </div>
  <form method="get" action="index.php">
    <table class="search-form">
      <input type="hidden" name="act" value="statistics_commis" />
      <input type="hidden" name="op" value="<?php echo $output['op_key'];?>" />
      <tr>
        <td>
          <a href="javascript:void(0);" class="imsc-btn-mini" id="today_flow">今日统计</a>
          <a href="javascript:void(0);" class="imsc-btn-mini" id="week_flow"><?php echo $lang['stat_week_rank']; ?></a>
          <a href="javascript:void(0);" class="imsc-btn-mini" id="month_flow"><?php echo $lang['stat_month_rank']; ?></a>
          <a href="javascript:void(0);" class="imsc-btn-mini" id="year_flow"><?php echo $lang['stat_year_rank']; ?></a>
        </td>
        <th><?php echo $lang['stat_time_search'];?></th>
        <td class="w240">
          <input type="text" class="text w70" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>&nbsp;&#8211;&nbsp;
          <input type="text" class="text w70" id="add_time_to" name="add_time_to" value="<?php echo $_GET['add_time_to']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>
        </td>
        <td class="w70">
          <select name="give_status">
            <option value="0" <?php if ($_GET['give_status'] == 0) {?>selected="selected"<?php }?>>未结算</option>
            <option value="1" <?php if ($_GET['give_status'] == 1) {?>selected="selected"<?php }?>>已结算</option>          
          </select>
        </td>
        <th>名称查询</th>
        <td class="w100">
          <input type="text" class="text w70" name="saleman_name" id="saleman_name" value="<?php echo $_GET['saleman_name']; ?>" />
        </td>
        <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['im_search'];?>" /></label></td>
      </tr>
    </table>
  </form>
  <!-- JS统计图表 -->
  <div id="container" style="width: 800px; height: 450px; margin: 0 auto"></div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 
<script type="text/javascript">
	$(function(){
	    $('#add_time_from').datepicker({dateFormat: 'yymmdd'});
	    $('#add_time_to').datepicker({dateFormat: 'yymmdd'});
		
		$('#today_flow').click(function(){
	    	window.location.href = 'index.php?act=statistics_commis&op=<?php echo $output['op_key'];?>&type=today';
		})		
	    $('#week_flow').click(function(){
	    	window.location.href = 'index.php?act=statistics_commis&op=<?php echo $output['op_key'];?>&type=week';
		})
		$('#month_flow').click(function(){
	    	window.location.href = 'index.php?act=statistics_commis&op=<?php echo $output['op_key'];?>&type=month';
		})
		$('#year_flow').click(function(){
	    	window.location.href = 'index.php?act=statistics_commis&op=<?php echo $output['op_key'];?>&type=year';
		})
	});
</script>
