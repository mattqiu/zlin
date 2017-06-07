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

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>推广管理</h3>
        <h5>查看平台推广统计数据</h5>
      </div>
      <?php echo $output['top_link'];?></div>
  </div>
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>查看平台推广抽佣排行。</li>
    </ul>
  </div>  

  <div class="fixed-empty"></div>
  <form method="get" action="index.php">
    <input type="hidden" name="act" value="extension_statistics" />
    <input type="hidden" name="op" value="commis_paihang" />
    <table class="tb-type1 noborder search">
      <tbody>    
        <tr>
          <td>
            <a href="javascript:void(0);" class="imap-btn imap-btn-green" id="today_flow"><span>今日结算</span></a>
            <a href="javascript:void(0);" class="imap-btn imap-btn-green" id="week_flow"><span>本周结算</span></a>
            <a href="javascript:void(0);" class="imap-btn imap-btn-green" id="month_flow"><span>本月结算</span></a>
            <a href="javascript:void(0);" class="imap-btn imap-btn-green" id="year_flow"><span>今年结算</span></a>
          </td>
          <th class="w30">时段</th>
          <td class="w240">
            <input type="text" class="text w70" name="add_time_from" id="add_time_from" value="<?php echo $_GET['add_time_from']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>&nbsp;&#8211;&nbsp;
            <input type="text" class="text w70" id="add_time_to" name="add_time_to" value="<?php echo $_GET['add_time_to']; ?>" /><label class="add-on"><i class="fa fa-calendar"></i></label>
          </td>
          <td class="w70">
            <select name="give_status">              
              <option value="0" <?php if (isset($_GET['give_status']) && $_GET['give_status'] == 0) {?>selected="selected"<?php }?>>未结算</option> 
              <option value="1" <?php if (isset($_GET['give_status']) && $_GET['give_status'] == 1) {?>selected="selected"<?php }?>>已结算</option>    
            </select>
          </td>
          <th class="w60">名称查询</th>
          <td class="w80">
            <input type="text" class="text w70" name="saleman_name" id="saleman_name" value="<?php echo $_GET['saleman_name']; ?>" />
          </td>
          <td class="w60 tc"><label class="submit-border"><input type="submit" class="submit" value="查询" /></label></td>
        </tr>
      </tbody>
    </table>
  </form>    
  <!-- JS统计图表 -->
  <div id="container" style="width: 800px; height: 450px; margin: 0 auto"></div>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script> 
<script type="text/javascript">
	$(function(){
	    $('#add_time_from').datepicker({dateFormat: 'yymmdd'});
	    $('#add_time_to').datepicker({dateFormat: 'yymmdd'});
		
		$('#today_flow').click(function(){
	    	window.location.href = 'index.php?act=extension_statistics&op=commis_paihang&type=today';
		})		
	    $('#week_flow').click(function(){
	    	window.location.href = 'index.php?act=extension_statistics&op=commis_paihang&type=week';
		})
		$('#month_flow').click(function(){
	    	window.location.href = 'index.php?act=extension_statistics&op=commis_paihang&type=month';
		})
		$('#year_flow').click(function(){
	    	window.location.href = 'index.php?act=extension_statistics&op=commis_paihang&type=year';
		})
	});
</script>
