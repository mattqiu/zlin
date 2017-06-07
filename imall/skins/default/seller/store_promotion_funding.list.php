<?php defined('InIMall') or exit('Access Invalid!');?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
<?php if ($output['isOwnShop']) { ?>
  <a class="imsc-btn imsc-btn-green" href="<?php echo urlShop('store_promotion_funding', 'funding_add');?>"><i class="fa fa-plus-circle"></i><?php echo $lang['funding_add'];?></a>

<?php } else { ?>

  <?php if(!empty($output['current_funding_quota'])) { ?>
  <a class="imsc-btn imsc-btn-green" style="right:100px" href="<?php echo urlShop('store_promotion_funding', 'funding_add');?>"><i class="fa fa-plus-circle"></i><?php echo $lang['funding_add'];?></a> <a class="imsc-btn imsc-btn-acidblue" href="<?php echo urlShop('store_promotion_funding', 'funding_quota_add');?>" title=""><i class="fa fa-money"></i>套餐续费</a>
  <?php } else { ?>
  <a class="imsc-btn imsc-btn-acidblue" href="<?php echo urlShop('store_promotion_funding', 'funding_quota_add');?>" title=""><i class="fa fa-money"></i><?php echo $lang['funding_quota_add'];?></a>
  <?php } ?>

<?php } ?>
</div>

<?php if ($output['isOwnShop']) { ?>
<div class="alert alert-block mt10">
  <ul class="mt5">
    <li>1、点击新增抢购按钮可以添加抢购活动</li>
    <li>2、如发布虚拟商品的抢购活动，请点击新增虚拟抢购按钮</li>
  </ul>
</div>
<?php } else { ?>
<div class="alert alert-block mt10">
  <?php if(!empty($output['current_groupbuy_quota'])) { ?>
  <strong>套餐过期时间<?php echo $lang['im_colon'];?></strong><strong style="color: #F00;"><?php echo date('Y-m-d H:i:s', $output['current_groupbuy_quota']['end_time']);?></strong>
  <?php } else { ?>
  <strong>当前没有可用套餐，请先购买套餐</strong>
  <?php } ?>
  <ul class="mt5">
    <li>1、点击购买套餐和套餐续费按钮可以购买或续费套餐</li>
    <li>2、点击新增抢购按钮可以添加抢购活动</li>
    <li>3、如发布虚拟商品的抢购活动，请点击新增虚拟抢购按钮</li>
    <li>4、<strong style="color: red">相关费用会在店铺的账期结算中扣除</strong></li>
  </ul>
</div>
<?php } ?>

<table class="search-form">
  <form method="get">
    <input type="hidden" name="act" value="store_groupbuy" />
    <tr>
      <td>&nbsp;</td>
      <th><?php echo $lang['groupbuy_index_activity_state'];?></th>
      <td class="w100"><select name="groupbuy_state" class="w90">
          <?php if(is_array($output['groupbuy_state_array'])) { ?>
          <?php foreach($output['groupbuy_state_array'] as $key=>$val) { ?>
          <option value="<?php echo $key;?>" <?php if($key == $_GET['groupbuy_state']) { echo 'selected';}?>><?php echo $val;?></option>
          <?php } ?>
          <?php } ?>
        </select></td>
      <th><?php echo $lang['group_name'];?></th>
      <td class="w160"><input class="text" type="text" name="groupbuy_name" value="<?php echo $_GET['groupbuy_name'];?>"/></td>
      <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['im_search'];?>" /></label></td>
    </tr>
  </form>
</table>

<table class="imsc-default-table">
  <thead>
    <tr>
      <th class="w10"></th>
      <th class="w50"></th>
      <th class="tl">商品名称</th>
      <th class="w130">开始时间</th>
      <th class="w130">结束时间</th>
      <th class="w90">浏览数</th>
      <th class="w90">已购买</th>
      <th class="w110">活动状态</th>
    </tr>
  </thead>
  <tbody>
    <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
    <?php foreach($output['goods_list'] as $key=>$goods){?>
    <tr class="bd-line">
      <td></td>
      <td><div class="pic-thumb"><a href="<?php echo $goods['goods_url'];?>" target="_blank"><img src="<?php echo gthumb($goods['goods_image'], 'small');?>"/></a></div></td>
      <td class="tl">
        <dl class="goods-name">
          <dt>
            <a target="_blank" href="<?php echo $goods['goods_url'];?>"><?php echo $goods['goods_name'];?></a>
          </dt>
        </dl>
      </td>
      <td><?php echo date('Y-m-d H:i:s', $goods['start_time']);?></td>
      <td><?php echo date('Y-m-d H:i:s', $goods['end_time']);?></td>
      <td><?php echo $goods['views'];?></td>
      <td><?php echo $goods['buy_quantity'];?></td>
      <td><?php echo $goods['is_recommend'];?></td>
    </tr>
    <?php }?>
    <?php }else{?>
    <tr>
      <td colspan="20" class="norecord"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php }?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
  </tfoot>
</table>


<form method="get">
  <table class="search-form">
    <input type="hidden" name="act" value="store_promotion_funding" />
    <input type="hidden" name="op" value="funding_list" />
    <tr>
      <td>&nbsp;</td>
      <th>状态</th>
      <td class="w100"><select name="state">
          <?php if(is_array($output['funding_state_array'])) { ?>
          <?php foreach($output['funding_state_array'] as $key=>$val) { ?>
          <option value="<?php echo $key;?>" <?php if(intval($key) === intval($_GET['state'])) echo 'selected';?>><?php echo $val;?></option>
          <?php } ?>
          <?php } ?>
        </select></td>
      <th class="w110"><?php echo $lang['funding_name'];?></th>
      <td class="w160"><input type="text" class="text w150" name="funding_name" value="<?php echo $_GET['funding_name'];?>"/></td>
      <td class="w70 tc"><label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['im_search'];?>" /></label></td>
    </tr>
  </table>
</form>
<table class="imsc-default-table">
  <thead>
    <tr>
      <th class="w30"></th>
      <th class="tl"><?php echo $lang['funding_name'];?></th>
      <th class="w180"><?php echo $lang['start_time'];?></th>
      <th class="w180"><?php echo $lang['end_time'];?></th>
      <th class="w80">购买下限</th>
      <th class="w80">状态</th>
      <th class="w150"><?php echo $lang['im_handle'];?></th>
    </tr>
  </thead>
  <?php if(!empty($output['list']) && is_array($output['list'])){?>
  <?php foreach($output['list'] as $key=>$val){?>
  <tbody id="funding_list">
    <tr class="bd-line">
      <td></td>
      <td class="tl"><dl class="goods-name">
          <dt><?php echo $val['funding_name'];?></dt>
        </dl></td>
      <td class="goods-time"><?php echo date("Y-m-d H:i",$val['start_time']);?></td>
      <td class="goods-time"><?php echo date("Y-m-d H:i",$val['end_time']);?></td>
      <td><?php echo $val['lower_limit'];?></td>
      <td><?php echo $val['funding_state_text'];?></td>
      <td class="nscs-table-handle tr">
          <?php if($val['editable']) { ?>
          <span>
              <a href="index.php?act=store_promotion_funding&op=funding_edit&funding_id=<?php echo $val['funding_id'];?>" class="btn-blue">
                  <i class="fa fa-pencil-square-o"></i>
                  <p><?php echo $lang['im_edit'];?></p>
              </a>
          </span>
          <?php } ?>
          <span>
              <a href="index.php?act=store_promotion_funding&op=funding_manage&funding_id=<?php echo $val['funding_id'];?>" class="btn-green">
                  <i class="fa fa-cog"></i>
                  <p><?php echo $lang['im_manage'];?></p>
              </a>
          </span>
          <span>
              <a href="javascript:;" imtype="btn_del_funding" data-funding-id=<?php echo $val['funding_id'];?> class="btn-red">
                  <i class="fa fa-trash-o"></i>
                  <p><?php echo $lang['im_delete'];?></p>
              </a>
          </span>
      </td>
  </tr>
  <?php }?>
  <?php }else{?>
  <tr id="funding_list_norecord">
      <td class="norecord" colspan="20"><div class="warning-option"><i class="fa fa-exclamation-triangle"></i><span><?php echo $lang['no_record'];?></span></div></td>
  </tr>
  <?php }?>
  </tbody>
  <tfoot>
    <?php if(!empty($output['list']) && is_array($output['list'])){?>
    <tr>
      <td colspan="20"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<form id="submit_form" action="" method="post" >
  <input type="hidden" id="funding_id" name="funding_id" value="">
</form>
<script type="text/javascript">
    $(document).ready(function(){
        $('[imtype="btn_del_funding"]').on('click', function() {
            if(confirm('<?php echo $lang['im_ensure_del'];?>')) {
                var action = "<?php echo urlShop('store_promotion_funding', 'funding_del');?>";
                var funding_id = $(this).attr('data-funding-id');
                $('#submit_form').attr('action', action);
                $('#funding_id').val(funding_id);
                ajaxpost('submit_form', '', '', 'onerror');
            }
        });
    });
</script>
