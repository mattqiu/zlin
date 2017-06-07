<?php defined('InIMall') or exit('Access Invalid!');?>

<?php if (!empty($output['viewed_goods']) && is_array($output['viewed_goods'])){?>
<div class="content_white" id="lookedList">
  <div class="contentBody">
    <div class="white_title"> <b>您最近浏览过的商品</b>
      <p id="clear_btn"><a href="javascript:void(0);" onclick="clear_history();">清除浏览记录</a></p>
    </div>
    <div class="title_body_02" id="history_list">
      <div class="white_left"> <a href="javascript::void(0);" id="left"></a> </div>
      <div class="white_body" id="list">
        <ul id="history_content" >
          <?php foreach ($output['viewed_goods'] as $val) {?>
          <li>
            <div class="white_img"> <a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>"> <img src="<?php echo thumb($val, 240);?>" border="0" width="170" height="170"> </a> </div>
            <div class="white_txt"> 
              <b><a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>"><?php echo $val['goods_name']?></a></b>
              <p>本店价：<?php echo $lang['currency'];?><?php echo $val['goods_price'];?><font><?php echo $val['goods_marketprice'];?></font></p>
            </div>
          </li>
          <?php }?>
        </ul>
      </div>
      <div class="white_right"> <a href="javascript::void(0);" id="right"></a> </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(function(){
	if ($("#history_content").html().replace(/\s/g,'').length<1){
		$('#lookedList').hide();
	}else{
		$('#lookedList').show();
	}	
	$("#history_list").slide({mainCell:".white_body ul",autoPage:true,effect:"left",autoPlay:true,vis:6,scroll:6,prevCell:"#left",nextCell:"#right"});
})

function clear_history()
{
	Ajax.call('user.php', 'act=clear_history',clear_history_Response, 'GET', 'TEXT',1,1);
}
function clear_history_Response(res)
{
	$("#history_content").html("");
	$("#lookedList").hide();
}
</script>
<?php }?>
<div class="blank5"></div>