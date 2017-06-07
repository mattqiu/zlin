<?php defined('InIMall') or exit('Access Invalid!');?>

<script type="text/javascript">
$(document).ready(function(){
    $('#add_time_from').datepicker();
    $('#add_time_to').datepicker();
});
</script>
<div class="tabmenu">
    <ul class="tab pngFix">
    <li class="active"><a href="javascript:void(0);">分销商管理</a></li>
  	</ul>
</div>
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="supplier_fx">
    <input type="hidden" name="op" value="index">
    <tbody><tr>
      <td>&nbsp;</td>
      <th>店铺类型</th>
      <td class="w160"><select name="distribution_character" class="w150">
          <option value="0" selected="">全部店铺</option>
          <option value="1">企业店铺</option>
          <option value="2">员工店铺</option>
        </select></td>
      <th>
          店铺名
      </th>
      <td class="w160"><input type="text" class="text w150" name="keyword" value=""></td>
      <td class="tc w70"><label class="submit-border">
          <input type="submit" class="submit" value="搜索">
        </label></td>
    </tr>
  </tbody></table>
</form>
<table class="ncsc-default-table">
    <thead>
    <tr im_type="table_header">
      <th class="w150">店铺LOGO</th>
      <th class="w150">认证类型</th>
      <th class="w300">分销商店铺名</th>
      <th class="w200">商品数量</th>
      <th class="w200">订单数量</th>
      <th class="w300">管理</th>
    </tr>
  </thead>
   <tbody>
        
    <tr class="bd-line">
      <td style="padding: 1px 0px"><a href="http://saas.shopnctest.com/shop/index.php?act=show_store&amp;op=index&amp;store_id=118" target="_blank"><img src="http://saas.shopnctest.com/data/upload/shop/store/store_default_logo.jpg" style="height: 44px; width: auto;"></a></td>
        <td>
            企业            <!--<img height="18px" width="auto" src="" >-->
        </td>
      <td>
          <a href="http://saas.shopnctest.com/shop/index.php?act=show_store&amp;op=index&amp;store_id=118" target="_blank">梓平生活馆</a><span style="clear: both"></span>
      </td>
<!--      <td><span>--><!--</span></td>-->
      <td><span>1</span></td>
      <td><span>0</span></td>
      <td class="nscs-table-handle">
          <span><a href="http://saas.shopnctest.com/shop/index.php?act=show_store&amp;op=index&amp;store_id=118" target="_blank" class="btn-bittersweet"><i class="icon-laptop"></i><p>PC店</p></a></span>
          <span><a href="http://saas.shopnctest.com/wap/tmpl/store.html?store_id=118" target="_blank" class="btn-bittersweet"><i class=" icon-tablet"></i><p>Wap店</p></a></span>
          <!--<span><a href="index.php?act=supplier_distribution&op=view&certifi_id=" class="btn-bittersweet"><i class="icon-external-link"></i><p>分享</p></a></span>-->
          <span><a href="index.php?act=supplier_fx&amp;op=cancelBusiness&amp;distribution_character=1&amp;store_id=118" class="btn-bittersweet"><i class="icon-remove"></i><p>取消分销商</p></a></span>
      </td>
    </tr>
          </tbody>
  <tfoot>
    <?php if (!empty($output['pdlog_list'])) { ?>
    <tr>
      <th class="tc"><input type="checkbox" id="all" class="checkall"/></th>
      <th colspan="10"><label for="all" ><?php echo $lang['im_select_all'];?></label>
        <a href="javascript:void(0);" im_type="batchbutton" uri="<?php echo urlShop('supplier_predeposit', 'drop_plate');?>" name="p_id" confirm="<?php echo $lang['im_ensure_del'];?>" class="ncbtn-mini"><i class="icon-trash"></i><?php echo $lang['im_del'];?></a>
       </th>
    </tr>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
