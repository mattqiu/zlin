<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>计划任务生成工具</title>
</head>

<body>
<?php
if(isset($_POST['ym']))
{
	$t1='';
	$t2='';
	$t3='';
	$t4='';
   $ym=	$_POST['ym'];
   if(isset($_POST['minutes']))
   {
	      $minutes= $_POST['minutes'];
		  $t1='@echo off
mshta vbscript:createobject("wscript.shell").run("""iexplore"" '.$ym.'/crontab/index.php?act=minutes",0)(window.close) 
';
												
   }else $minutes="";
if(isset($_POST['hour']))
   {
	      $hour= $_POST['hour'];
		  		  $t2='@echo off
mshta vbscript:createobject("wscript.shell").run("""iexplore"" '.$ym.'/crontab/index.php?act=hour",0)(window.close) 
';
   }else $hour="";
   if(isset($_POST['date']))
   {
	      $date= $_POST['date'];
		  		  		  $t3='@echo off
mshta vbscript:createobject("wscript.shell").run("""iexplore"" '.$ym.'/crontab/index.php?act=date",0)(window.close) 
';
   }else $date="";
   if(isset($_POST['month']))
   {
	      $month= $_POST['month'];
		  		  		  		  $t4='@echo off
mshta vbscript:createobject("wscript.shell").run("""iexplore"" '.$ym.'/crontab/index.php?act=month",0)(window.close) 
';
   }else $month="";
   
$of = fopen('dir.txt','w');//创建并打开dir.txt
if($of){
	$c=$t1.$t2.$t3.$t4;
	$c.='echo 1
	';
	$f='taskkill /f /im iexplore.exe ';
	$c.=$f;
 fwrite($of,$c);//把执行文件的结果写入txt文件
}
fclose($of);

rename("dir.txt", "dir.bat");
echo "<h1>生成成功</h1>";
	//echo $ym."/".$minutes."/".$hour."/".$date."/". $month;
	
}
?>
<div>
<h1>计划任务 触发器</h1>
<p>请选择功能</p>
<div>
<form action="config.php" method="post">
<p>请输入你的域名或者ip</p>
<input type="text" name="ym" /></br><input name="minutes" type="checkbox" value="minutes" />分钟任务
</br>（包括更新首页的商品价格信息、发送邮件消息、执行通用任务有* 上架* 根据商品id更新商品促销价格* 优惠套装过期* 推荐展位过期* 抢购开始更新商品促销价格 * 抢购过期 * 限时折扣过期）</br><input name="hour" type="checkbox" value="hour" />小时任务</br>（包括更新全文搜索内容）</br>
<input name="date" type="checkbox" value="date" />天任务</br>（包括//更新订单商品佣金值、//订单超期后不允许评价、未付款订单超期自动关闭、//订单自动完成、自提点中，已经关闭的订单删除、更新订单扩展表收货人所在省份ID、更新退款申请超时处理、代金券即将过期提醒、虚拟兑换码即将过期提醒、更新商品访问量、更新商品促销到期状态、商品到货通知提醒、更新浏览量、缓存订单及订单商品相关数据、会员相关数据统计等等）</br>
<input name="month" type="checkbox" value="month" />月任务</br>（包括更新订单商品佣金值、订单结算等等。。。）</br><input type="submit" value="生成计划任务bat">
</form>
</div>
</div>
</body>
</html>