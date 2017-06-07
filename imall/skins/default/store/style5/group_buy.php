<?php defined('InIMall') or exit('Access Invalid!');?>

<?php if (!empty($output['groupbuy_list']) && is_array($output['groupbuy_list'])){?>
<div class="picScroll-left">
  <div class="hd">
    <div class="title_txt"> <img src="<?php echo SHOP_SKINS_URL;?>/store/<?php echo $output['store_theme'];?>/images/title_txt01.jpg" border="0"> </div>
    <div class="title_more"> <a href="group_buy.php">更多抢购</a> </div>
    <div class="title_btn">
      <div class="title_btn_right"> <a class="next"></a> </div>
      <ul>
      </ul>
      <div class="title_btn_left"> <a class="prev"></a> </div>
    </div>
  </div>
  <div class="bd">
    <ul class="picList">
      <?php foreach ($output['groupbuy_list'] as $groupbuy) {?>
      <li>
        <div class="time_num">
          <p class="endtime" value="<?php echo $groupbuy['end_time'];?>"></p>
        </div>
        <div class="time_center">
          <div class="time_img"> 
            <a href="<?php echo $groupbuy['groupbuy_url'];?>"><img src="<?php echo gthumb($groupbuy['groupbuy_image'],'mid');?>" alt="<?php echo $groupbuy['groupbuy_name'];?>"  width="200" height="200"/></a> 
          </div>
          <div class="time_price"><b>特卖价</b>
            <p><i><?php echo $lang['currency'];?></i><?php echo $groupbuy['groupbuy_price'];?></p>
          </div>
        </div>
        <div class="time_txt"><a href="<?php echo $groupbuy['groupbuy_url'];?>" title="<?php echo $groupbuy['groupbuy_name'];?>"><?php echo $groupbuy['groupbuy_name'];?></a> </div>
        <div class="time_btn"> <a href="<?php echo $groupbuy['groupbuy_url'];?>"></a> </div>
        <div class="time_bottom"> <b><span><?php echo $groupbuy['buyer_count'];?></span>人已参抢</b>
          <p>数量有限下手要快哦</p>
        </div>
        <div style="clear:both;"></div>
      </li>      
      <?php }?>
    </ul>
  </div>
</div>
<script type="text/javascript">
	jQuery(".picScroll-left").slide({titCell:".hd ul",mainCell:".bd ul",autoPage:true,effect:"left",vis:5,scroll:5,easing:"easeOutBounce",delayTime:0,trigger:"click"});
	
	$(function(){
		var datename = new Date();
		var Offset = datename.getTimezoneOffset() * 28800;
		
		setInterval(function(){
		  $(".endtime").each(function(){
			var obj = $(this);
			var endTime = new Date(parseInt(obj.attr('value')) * 1000 - Offset) ;
			var show_day =  obj.attr('showday');
			var nowTime = new Date();
			var nMS=endTime.getTime() - nowTime.getTime();
			var myD=Math.floor(nMS/(1000 * 60 * 60 * 24));
			var myH_show=Math.floor(nMS/(1000*60*60) % 24);
			var myH=Math.floor(nMS/(1000*60*60));
			var myM=Math.floor(nMS/(1000*60)) % 60;
			var myS=Math.floor(nMS/1000) % 60;
			var myMS=Math.floor(nMS/100) % 10;
			
			if(myS>=0){
				if(show_day == 'show')
				{
					var str = '还剩<strong class="tcd-d">'+myD+'</strong>天<strong class="tcd-h">'+myH_show+'</strong>小时<strong class="tcd-m">'+myM+'</strong>分<strong class="tcd-s">'+myS+'</strong>秒';
				}
				else
				{
					var str = '<span>'+myH+'</span>时<span>'+myM+'</span>分<span>'+myS+'</span>秒';
				}
			}else{
				var str = "已结束！";	
			}
			obj.html(str);
		  });
		}, 100);
	})	
</script> 
<?php }?>