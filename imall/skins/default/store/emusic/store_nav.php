<?php defined('InIMall') or exit('Access Invalid!');?>

<script type="text/javascript" src="<?php echo SHOP_SKINS_URL;?>/store/<?php echo $output['store_theme'];?>/js/common.js" charset="utf-8"></script>

<?php if(!empty($output['decoration_nav'])) {?> 
  <style><?php echo $output['decoration_nav']['style'];?></style>
<?php } ?>
<!-- 导航条 -->
<div class="header">
  <div class="headBody">
    <div class="headNav" style="position:relative; z-index:99;">
      <div class="classNav"> 
        <a class="classNav_a" href="<?php echo urlShop('store_search', 'index', array('store_id' => $output['store_info']['store_id']));?>">全部商品分类</a>
        <div class="left_nav" style="position:absolute; left:0; top:33px;">
          <div class="leftNav" id="J_mainCata">
            <ul>
              <?php if(!empty($output['goods_class_list']) && is_array($output['goods_class_list'])){$i=0;?>
              <?php foreach($output['goods_class_list'] as $value){$i++;?>
              <li>
                <p class="leftNav_p0<?php echo $i;?>">
                  <a class="a1" href="<?php echo urlShop('store_search', 'index', array('store_id' => $output['store_info']['store_id'], 'stc_id' => $value['stc_id']));?>" title="<?php echo $value['stc_name'];?>"><?php echo $value['stc_name'];?></a>
                </p>
                <div class="childer_hide" >
                  <?php if(!empty($value['children']) && is_array($value['children'])){?>
                  <?php foreach($value['children'] as $value1){?>
                  <a href="<?php echo urlShop('store_search', 'index', array('store_id' => $output['store_info']['store_id'], 'stc_id' => $value1['stc_id']));?>" target="_blank"><?php echo $value1['stc_name'];?></a>
                  <?php }?>
                  <?php }?>
                </div>
			    <div class="J_arrowBtn" style="top: 19px; display: block;"></div>
			    <div class="leftSubNav" id="J_subCata" style="opacity: 1; left: 220px; display: block; top: 0px;">
                  <div class="leftSubNav_list" >
                    <div class="leftSubNav_left">
			          <?php if(!empty($value['children']) && is_array($value['children'])){?>
                      <?php foreach($value['children'] as $value1){?>
                      <div class="leftSubNav_left_txt none">
                        <p class="p1" style=" background:none;">
                          <a href="<?php echo urlShop('store_search', 'index',array('store_id'=>$output['store_info']['store_id'], 'stc_id'=>$value1['stc_id']));?>" target="_blank"><?php echo $value1['stc_name'];?></a>
                        </p>
                        <dl>
                          <?php if(!empty($value1['children']) && is_array($value1['children'])){?>
                          <?php foreach($value1['children'] as $value2){?>
                          <dd>
                            <a href="<?php echo urlShop('store_search', 'index',array('store_id'=>$output['store_info']['store_id'], 'stc_id'=>$value2['stc_id']));?>" target="_blank"><?php echo $value2['stc_name'];?></a>
                          </dd>
                          <?php }?>
                          <?php }?>
				          <div class="blank"></div>
                        </dl>
                      </div>
			          <?php }?>
                      <?php }?>
                    </div>
			
                    <div class="leftSubNav_list_right">
                      <dl>
                        <?php if(!empty($value['brands']) && is_array($value['brands'])){?>
                        <?php foreach($value['brands'] as $brand){?>
                        <dd>
                          <?php if (!empty($brand['brand_logo'])){?>
                          <a href="<?php echo urlShop('brand','list',array('store_id'=>$output['store_info']['store_id'], 'brand'=>$brand['brand_id']));?>" target="_blank" >
                            <img border="0" width="78" height="38" src="<?php echo $brand['brand_pic']; ?>" alt="<?php echo $brand['brand_name']; ?> (<?php echo $brand['goods_num'];?>)" /></a>
                          <?php }else{?>
                          <a href="<?php echo urlShop('brand','list',array('store_id'=>$output['store_info']['store_id'], 'brand'=>$brand['brand_id']));?>"><?php echo $brand['brand_name']; ?></a>
                          <?php }?>
                        </dd>
                        <?php }?>
                        <?php }?>
                      </dl>
                    </div>
                    
                  </div>
                </div>
                <div class="blank"></div>
              </li>
              <?php }?>
              <?php }?>
            </ul>
          </div>
        </div>
      </div>
      <div class="subNav">
        <ul>
          <li <?php if($output['page'] == 'index'){?>class="current"<?php }?>> 
            <a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['store_info']['store_id']));?>"><?php echo $lang['im_store_index'];?></a>
          </li>
          <?php 
		  if(!empty($output['store_navigation_list'])){
      	  foreach($output['store_navigation_list'] as $value){
            if($value['sn_if_show']) {
      		  if($value['sn_url'] != ''){
	      ?>
          <li <?php if($output['page'] == $value['sn_id']){?>class="current"<?php }?>>
            <a href="<?php echo $value['sn_url']; ?>" <?php if($value['sn_new_open']){?>target="_blank" <?php }?>><?php echo $value['sn_title'];?></a>
            <?php if (!empty($value['cat_list']) && is_array($value['cat_list'])){?>
            <div class='sub_nav'>
              <dl>
                <?php foreach($value['cat_list'] as $cat){ ?>
                <dd>
                  <a class="t" href="<?php echo $cat['sn_url']; ?>"><?php echo $cat['sn_title'];?></a>
                </dd>
                <?php }?>
              </dl>
            </div>
            <?php }?>      
          </li>
          <?php }else{?>
          <li <?php if($output['page'] == $value['sn_id']){?>class="current"<?php }?>>
            <a href="<?php echo urlShop('show_store', 'show_article', array('store_id' => $output['store_info']['store_id'], 'sn_id' => $value['sn_id']));?>"><span><?php echo $value['sn_title'];?><i></i></span></a>
          </li>
          <?php }?>
          <?php }?>
          <?php }?>
          <?php }?>
          <li <?php if ($output['page'] == 'store_sns') {?>class="current"<?php }?>>
            <a href="<?php echo urlShop('store_snshome', 'index', array('store_id' => $output['store_info']['store_id']))?>">店铺动态</a>
          </li>
        </ul>
      </div>
      <div class="rightNav">
        <ul>
          <li></li>
        </ul>
      </div>
    </div>
  </div>
</div>