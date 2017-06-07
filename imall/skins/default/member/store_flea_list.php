<?php defined('InIMall') or exit('Access Invalid!');?>
<style>
a.imcu-btn3 {  font-weight: 700;   line-height: 24px;   color: #FFF;   background: #ff4545;   display: block;   height: 25px;   padding: 3px 12px;   border: solid 1px #FF4545;   border-radius: 0px;   position: absolute;   z-index: 1;   top: -2px;   right: 0px;   box-shadow: 1px 1px 1px rgba(0,0,0,0.1);}
a:hover.imcu-btn3 { color: #FFF; background-position: 0 -30px; /* border-color:#AED2FF; */ box-shadow: none;}
.search-form {  width: 100%;   border-bottom: solid 1px #E7E7E7;}
.search-form th { line-height: 22px; width: 80px; padding: 10px 0; text-align: right; color: #777;}
.search-form td { padding: 10px 0; text-align: left;}
.search-form select { height: 22px; background-color:#FFF; border: solid 1px; border-color: #CCC #DDD #DDD #CCC;  box-shadow: 2px 2px 1px 0 #E7E7E7 inset; -moz-box-shadow: 2px 2px 1px 0 #E7E7E7 inset/* if FF*/; -webkit--box-shadow: 2px 2px 1px 0 #E7E7E7 inset/* if Webkie*/;}
.search-form select:hover { background-color:#FFF;}
.search-form select:focus { background-color:#FFF; border-color: #CCC; box-shadow: 1px 1px 1px 0 #E7E7E7; -moz-box-shadow: 1px 1px 1px 0 #E7E7E7/* if FF*/; -webkit--box-shadow: 1px 1px 1px 0 #E7E7E7/* if Webkie*/;}
.search-form  select option { background-color: #FFF; height: 20px; padding-left: 12px;}
.imcu-table-style { line-height:20px; width: 100%; border-collapse: collapse; clear: both;}
.imcu-table-style thead th { /* color:#5F718B; */ /* background: url(../images/member/ncus_repeat_x.png) repeat-x scroll center -40px; */ padding: 5px 0 6px 0; border-bottom: solid 1px #E7E7E7; /* text-align:center; */}
.imcu-table-style thead td { color: #5F718B; background-color: #F7F7F7; padding-top: 5px; border-top: solid 1px #EEEEEE;}
.imcu-table-style thead td label, .imcu-table-style tfoot td label { color: #555; display:inline; float:left; margin-right:10px; cursor:pointer; }
.imcu-table-style tbody th { background-color: #EDF5FF; border-top: solid 1px #AED2FF; padding: 4px 0;}
.imcu-table-style tbody th a { color:#0579C6;}
.imcu-table-style tbody td { background-color: #FFF; text-align: center; padding: 12px 0;}
.imcu-table-style tfoot td { color: #5F718B;border-top: solid 1px #C4D5E0; background-color: #FFF; padding: 5px 0 6px 0;}
</style>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('member/member_submenu');?>
    <a href="javascript:void(0)" class="imcu-btn3" onclick="go('index.php?act=member_flea&op=add_goods');" title="<?php echo $lang['store_goods_index_new_goods'];?>"><?php echo $lang['store_goods_index_new_flea'];?></a> </div>
  <form method="get" action="index.php">
    <table class="search-form">
      <input type="hidden" name="act" value="member_flea" />
      <input type="hidden" name="op" value="flea_list" />
      <tr>
        <td>&nbsp;</td>
        <th style="width:90px;"><?php echo $lang['flea_goods_name'].$lang['im_colon'];?></th>
        <td class="w150"><input type="text" class="text" name="keyword" value="<?php echo $_GET['keyword']; ?>"/></td>
        <td class="w90 tc"><input type="submit" class="submit" value="<?php echo $lang['im_search'];?>" /></td>
      </tr>
    </table>
  </form>
  <table class="imcu-table-style">
    <thead>
      <tr im_type="table_header">
        <th class="w30"></th>
        <th class="w70"></th>
        <th class="tl"><?php echo $lang['flea_goods_name'];?></th>
        <th class="w200"><?php echo $lang['flea_goods_gc_name'];?></th>
        <th class="w100"><?php echo $lang['flea_goods_price'];?></th>
        <th class="w90"><?php echo $lang['im_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['list_goods'])>0) { ?>
      <?php foreach($output['list_goods'] as $val) { ?>
      <tr class="bd-line">
        <td></td>
        <td><div class="goods-pic-small"> <span class="thumb size60"> <i></i><a href="index.php?act=flea_goods&goods_id=<?php echo $val['goods_id']; ?>" target="_blank"><img height="50" width="50" src="<?php if ($val['goods_image']) { echo $val['goods_image']; } else { echo SHOP_SKINS_URL.'/images/member/default_image.png'; } ?>" onload="javascript:DrawImage(this,60,60);" /></a></span></div></td>
        <td class="tl"><dl class="goods-name">
            <dt><?php echo $val['goods_name']; ?></dt>
          </dl></td>
        <td><?php echo $val['gc_name']; ?></td>
        <td><?php echo $val['goods_store_price']; ?></td>
        <td><p><a href="index.php?act=member_flea&op=edit_goods&goods_id=<?php echo $val['goods_id']; ?>"><?php echo $lang['store_goods_index_edit_flea'];?></a></p>
          <p><a href="javascript:void(0)" onclick="if(confirm('<?php echo $lang['im_ensure_del']?>')){location.href='index.php?act=member_flea&op=flea_del&goods_id=<?php echo $val['goods_id']; ?>'}"class="imcu-btn2 mt5"><?php echo $lang['im_del']?></a></p></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (count($output['list_goods'])>0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>