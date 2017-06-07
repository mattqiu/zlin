<?php defined('InIMall') or exit('Access Invalid!');?>
<script type="text/javascript">
$(function(){
    $('#consulting_demo').find('.demo').ajaxContent({
        event:'click', //mouseover
        loaderType:"img",
        loadingMsg:"<?php echo SHOP_SKINS_URL;?>/images/transparent.gif",
        target:'#consulting_demo'
    });

    $('#consulting_tab').find('a').ajaxContent({
        event:'click',
        loaderType:'img',
        loadingMsg:'<?php echo SHOP_SKINS_URL;?>/images/transparent.gif',
        target:'#consulting_demo'
    });
});
</script>

<div class="top" style="overflow: hidden;">
  <div class="imcs-cosult-tips"> <i></i>
    <p><?php echo html_entity_decode(C('consult_prompt'));?></p>
  </div>
  <div class="imcs-cosult-askbtn"><a href="<?php echo urlShop('goods', 'consulting_list', array('goods_id' => $_GET['goods_id']));?>#askQuestion" target="_blank" class="imcs-btn imcs-btn-red">我要提问</a></div>
</div>
<div class="imcs-goods-title-nav">
  <ul id="consulting_tab">
    <li class="<?php if (intval($_GET['ctid']) == 0) {?>current<?php }?>"><a href="<?php echo urlShop('goods', 'consulting', array('goods_id' => $_GET['goods_id'], 'store_id' => $_GET['store_id']));?>">全部</a></li>
    <?php if (!empty($output['consult_type'])) {?>
    <?php foreach ($output['consult_type'] as $val) {?>
    <li class="<?php if (intval($_GET['ctid']) == $val['ct_id']) {?>current<?php }?>"><a href="<?php echo urlShop('goods', 'consulting', array('goods_id' => $_GET['goods_id'], 'ctid' => $val['ct_id'], 'store_id' => $_GET['store_id']));?>"><?php echo $val['ct_name'];?></a></li>
    <?php }?>
    <?php }?>
  </ul>
</div>
<div class="imcs-cosult-main">
  <?php if(!empty($output['consult_list'])) { ?>
  <?php foreach($output['consult_list'] as $k=>$v){ ?>
  <div class="imcs-cosult-list">
    <dl class="asker">
      <dt>咨询网友<?php echo $lang['im_colon'];?></dt>
      <dd>
        <?php if($v['member_id']== '0') echo $lang['im_guest']; else if($v['isanonymous'] == 1){?>
        <?php echo str_cut($v['member_name'],2).'***';?>
        <?php }else{?>
        <a href="index.php?act=member_snshome&mid=<?php echo $v['member_id'];?>" target="_blank" data-param="{'id':<?php echo $v['member_id'];?>}" imtype="mcard"><?php echo str_cut($v['member_name'],8);?></a>
        <?php }?>
        <time datetime="<?php echo date("Y-m-d H:i:s",$v['consult_addtime']);?>" pubdate="pubdate" class="ml20"><?php echo date("Y-m-d H:i:s",$v['consult_addtime']);?></time>
      </dd>
    </dl>
    <dl class="ask-con">
      <dt><?php echo $lang['goods_index_consult_content'];?><?php echo $lang['im_colon'];?></dt>
      <dd>
        <p><?php echo nl2br($v['consult_content']);?></p>
      </dd>
    </dl>
    <?php if($v['consult_reply']!=""){?>
    <dl class="reply">
      <dt><?php echo $lang['goods_index_seller_reply'];?></dt>
      <dd>
        <p><?php echo nl2br($v['consult_reply']);?></p>
        <time datetime="<?php echo date("Y-m-d H:i:s",$v['consult_reply_time']);?>" pubdate="pubdate">[<?php echo date("Y-m-d H:i:s",$v['consult_reply_time']);?>]</time>
      </dd>
    </dl>
    <?php }?>
  </div>
  
  <?php }?><div class="more"><a href="<?php echo urlShop('goods', 'consulting_list', array('goods_id' => $_GET['goods_id']));?>" target="_blank" >查看全部咨询>></a></div>
  <?php } else { ?>
  <div class="imcs-norecord"><?php echo $lang['goods_index_no_reply'];?></div>
  <?php } ?>
  
</div>
