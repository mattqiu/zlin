<?php defined('InIMall') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<link href="<?php echo RESOURCE_SITE_URL;?>/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.msg { font: 100 18px/24px arial,"microsoft yahei"; color: #555; background-color: #FFF; text-align: center; width: 100%; margin-bottom: 10px; padding: 50px 0;}
.msg i { font-size: 24px; vertical-align: middle; margin-right: 10px;}
</style>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js"></script>
</head>
<body>
<div class="msg">
  <?php if($output['msg_type'] == 'error'){ ?>
  <i class="fa fa-info-circle" style="color: #39C;"></i>
  <?php }else { ?>
  <i class="fa fa-check-circle" style=" color: #099;"></i>
  <?php } ?>
  <?php require_once($tpl_file);?>
</div>
<script type="text/javascript">
<?php if (!empty($output['url'])){?>
  window.setTimeout("javascript:location.href='<?php echo $output['url'];?>'", <?php echo $time;?>);
<?php }else{?>
  window.setTimeout("javascript:history.back()", <?php echo $time;?>);
<?php }?>
</script>
</body>
</html>