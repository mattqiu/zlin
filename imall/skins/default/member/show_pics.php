<?php defined('InIMall') or exit('Access Invalid!');?>

<?php
foreach ($output['pics'] as $pic) {
?>
    <p><img src="<?php echo $output['pic_path'].$pic;?>" alt="" /><p>
<?php
    }
?>
