<?php defined('InIMall') or exit('Access Invalid!');?>
<script type="text/javascript">
var SHOP_SITE_URL = "<?php echo SHOP_SITE_URL; ?>";
var UPLOAD_SITE_URL = "<?php echo UPLOAD_SITE_URL; ?>";
</script>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title"><a class="back" href="index.php?act=web_channel&op=floor_list" title="返回模块列表"><i class="fa fa-arrow-circle-o-left"></i></a>
      <div class="subject">
        <h3>频道管理 - 编辑“<?php echo $output['floor']['web_name'];?>”模块</h3>
        <h5>商城的频道及模块内容管理</h5>
      </div>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>所有相关设置完成，使用底部的“更新模块内容”前台展示页面才会变化。</li>
      <li>中部的“商品推荐模块”由于页面宽度只能加4个，商品数为8个(已选择的可以拖动进行排序，单击选中，双击删除)。</li>
    </ul>
  </div>
  <div class="imap-form-all">
    <dl class="row">
      <dd class="opt">
        <div class="vip-templates-board-layout style-<?php echo $output['floor']['style_name'];?>">
          <div class="left">
            <dl id="left_tit">
              <dt>
                <h4>背景图片</h4>
                <a href="JavaScript:show_dialog('upload_bg');"><i class="fa fa-pencil-square-o"></i><?php echo $lang['im_edit'];?></a>
              </dt>
              <dd class="tit-pic">
                <div id="picture_tit" class="picture"> <img src="<?php echo UPLOAD_SITE_URL.'/'.$output['code_tit']['code_info']['pic'];?>"/> </div>
              </dd>
            </dl>            
            <dl>
              <dt>
                <h4>顶部广告图</h4>
                <a href="JavaScript:show_dialog('upload_adv');"><?php echo $lang['im_edit'];?></a>
              </dt>
              <dd class="adv-pic">
                <div id="picture_adv" class="picture">
                  <ul>
                    <?php foreach ($output['code_adv']['code_info'] as $key => $val) { ?>
                    <?php if(is_array($val) && !empty($val)) {?>
                    <li>
                      <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>"/>
                    </li>
                    <?php } ?>
                    <?php } ?>
                  </ul>
                </div>
              </dd>
            </dl> 
          </div>
          <div class="middle">
            <div>
              <?php if (is_array($output['code_recommend_list']['code_info']) && !empty($output['code_recommend_list']['code_info'])) { ?>
              <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) { ?>
              <dl recommend_id="<?php echo $key;?>">
                <dt>
                  <h4><?php echo $val['recommend']['name'];?></h4>
                  <a href="JavaScript:del_recommend(<?php echo $key;?>);"><i class="fa fa-trash"></i><?php echo $lang['im_del'];?></a> 
                  <a href="JavaScript:show_vip_recommend_dialog(<?php echo $key;?>);"><i class="fa fa-shopping-cart"></i><?php echo '商品块';?></a> 
                  <a href="JavaScript:show_vip_recommend_pic_dialog(<?php echo $key;?>);"><i class="icon-lightbulb"></i><?php echo '广告块';?></a> 
                </dt>
                <dd>
                  <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
                  <ul class="goods-list">
                    <?php foreach($val['goods_list'] as $k => $v) { ?>
                    <li>
                      <span><a href="javascript:void(0);"> <img title="<?php echo $v['goods_name'];?>" src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>"/></a></span>
                    </li>
                    <?php } ?>
                  </ul>
                  <?php } elseif (!empty($val['pic_list']) && is_array($val['pic_list'])) { ?>
                  <div class="middle-banner"> 
                    <a href="javascript:void(0);" class="left-a">
                      <img pic_url="<?php echo $val['pic_list']['11']['pic_url'];?>" title="<?php echo $val['pic_list']['11']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['11']['pic_img'];?>"/>
                    </a> 
                    <a href="javascript:void(0);" class="left-b">
                      <img pic_url="<?php echo $val['pic_list']['12']['pic_url'];?>" title="<?php echo $val['pic_list']['12']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['12']['pic_img'];?>"/>
                    </a> 
                    <a href="javascript:void(0);" class="middle-a">
                      <img pic_url="<?php echo $val['pic_list']['14']['pic_url'];?>" title="<?php echo $val['pic_list']['14']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['14']['pic_img'];?>"/>
                    </a> 
                    <a href="javascript:void(0);" class="right-a">
                      <img pic_url="<?php echo $val['pic_list']['21']['pic_url'];?>" title="<?php echo $val['pic_list']['21']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['21']['pic_img'];?>"/>
                    </a> 
                    <a href="javascript:void(0);" class="right-b">
                      <img pic_url="<?php echo $val['pic_list']['24']['pic_url'];?>" title="<?php echo $val['pic_list']['24']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['24']['pic_img'];?>"/>
                    </a> 
                    <a href="javascript:void(0);" class="bottom-a">
                      <img pic_url="<?php echo $val['pic_list']['31']['pic_url'];?>" title="<?php echo $val['pic_list']['31']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['31']['pic_img'];?>"/>
                    </a> 
                    <a href="javascript:void(0);" class="bottom-b">
                      <img pic_url="<?php echo $val['pic_list']['32']['pic_url'];?>" title="<?php echo $val['pic_list']['32']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['32']['pic_img'];?>"/>
                    </a> 
                    <a href="javascript:void(0);" class="bottom-c">
                      <img pic_url="<?php echo $val['pic_list']['33']['pic_url'];?>" title="<?php echo $val['pic_list']['33']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['33']['pic_img'];?>"/>
                    </a> 
                    <a href="javascript:void(0);" class="bottom-d">
                      <img pic_url="<?php echo $val['pic_list']['34']['pic_url'];?>" title="<?php echo $val['pic_list']['34']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['34']['pic_img'];?>"/>
                    </a> 
                  </div>
                  <?php }else { ?>
                  <ul class="goods-list">
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                    <li><span><i class="icon-gift"></i></span></li>
                  </ul>
                  <?php } ?>
                </dd>
              </dl>
              <?php } ?>
              <?php } ?>
              <div class="add-tab" id="btn_add_list">
                <a href="JavaScript:add_vip_recommend();"><i class="icon-plus-sign-alt"></i><?php echo $lang['web_config_add_recommend'];?></a><?php echo $lang['web_config_recommend_max'];?>
              </div>
            </div>
          </div>
        </div>
      </dd>
    </dl>
  </div>
  <div class="bot"><a href="index.php?act=web_channel&op=html_update&web_id=<?php echo $_GET['web_id'];?>" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['web_config_web_html'];?></a> </div>
</div>

<!-- 背景图片 -->
<div id="upload_bg_dialog" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i>模块背景设置</div>
  <form id="upload_bg_form" name="upload_bg_form" enctype="multipart/form-data" method="post" action="index.php?act=web_config&op=upload_bg_pic" target="upload_pic">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="web_id" value="<?php echo $output['code_tit']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_tit']['code_id'];?>">
    <input type="hidden" name="tit[pic]" value="<?php echo $output['code_tit']['code_info']['pic'];?>">
    <input type="hidden" name="tit[url]" value="">
    <input type="hidden" name="tit[type]" value="pic">
    <div class="imap-form-default">
      <dl id="upload_tit_type_pic" class="row">
        <dt class="tit"><?php echo '模块背景图片'.$lang['im_colon'];?></dt>
        <dd class="opt">
          <div class="input-file-show"> 
            <span class="type-file-box">
              <input type='text' name='textfield' id='textfield1' class='type-file-text' />
              <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
              <input name="pic" id="pic" type="file" class="type-file-file" size="30">
            </span>
          </div>
          <p class="notic">建议上传宽1920像素GIF\JPG\PNG格式图片。</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" onclick="$('#upload_bg_form').submit();" class="imap-btn-big imap-btn-green"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<!-- 商品推荐模块 -->
<div id="recommend_list_dialog" style="display:none;">
  <div class="s-tips"><i></i><?php echo $lang['web_config_recommend_goods_tips'];?></div>
  <form id="recommend_list_form">
    <input type="hidden" name="web_id" value="<?php echo $output['code_recommend_list']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_recommend_list']['code_id'];?>">
    <div id="recommend_input_list" style="display:none;"><!-- 推荐拖动排序 --></div>
    <?php if (is_array($output['code_recommend_list']['code_info']) && !empty($output['code_recommend_list']['code_info'])) { ?>
    <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) { ?>
    <div class="imap-form-default" select_recommend_id="<?php echo $key;?>" style="overflow:visible;">
      <dl class="row">
        <dt class="tit"> <?php echo $lang['web_config_recommend_title'];?></dt>
        <dd class="opt">
          <input name="recommend_list[<?php echo $key;?>][recommend][name]" value="<?php echo $val['recommend']['name'];?>" type="text" class="input-txt">
          <p class="notic"><?php echo $lang['web_config_recommend_tips'];?></p>
        </dd>
      </dl>    
    </div>
    <div class="imap-form-all" select_recommend_id="<?php echo $key;?>">
      <dl class="row">
        <dt class="tit"><?php echo $lang['web_config_recommend_goods'];?></dt>
        <dd class="opt">
          <ul class="dialog-goodslist-s1 goods-list">
            <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
            <?php foreach($val['goods_list'] as $k => $v) { ?>
            <li id="select_recommend_<?php echo $key;?>_goods_<?php echo $k;?>">
              <div ondblclick="del_recommend_goods(<?php echo $v['goods_id'];?>);" class="goods-pic"> 
                <span class="ac-ico" onclick="del_recommend_goods(<?php echo $v['goods_id'];?>);"></span> 
                <span class="thumb size-72x72">
                  <i></i><img select_goods_id="<?php echo $v['goods_id'];?>" title="<?php echo $v['goods_name'];?>" goods_name="<?php echo $v['goods_name'];?>" src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>" onload="javascript:DrawImage(this,72,72);" />
                </span>
              </div>
              <div class="goods-name"><a href="<?php echo SHOP_SITE_URL."/index.php?act=goods&goods_id=".$v['goods_id'];?>" target="_blank"><?php echo $v['goods_name'];?></a></div>
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_id]" value="<?php echo $v['goods_id'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][market_price]" value="<?php echo $v['market_price'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_name]" value="<?php echo $v['goods_name'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_price]" value="<?php echo $v['goods_price'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_pic]" value="<?php echo $v['goods_pic'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_advpic]" value="<?php echo $v['goods_advpic'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][promotion_amount]" value="<?php echo $v['promotion_amount'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_jingle]" value="<?php echo $v['goods_jingle'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][have_gift]" value="<?php echo $v['have_gift'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_freight]" value="<?php echo $v['goods_freight'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][goods_list][<?php echo $v['goods_id'];?>][goods_commend]" value="<?php echo $v['goods_commend'];?>" type="hidden">
            </li>
            <?php } ?>
            <?php } elseif (!empty($val['pic_list']) && is_array($val['pic_list'])) { ?>
            <?php foreach($val['pic_list'] as $k => $v) { ?>
            <li id="select_recommend_<?php echo $key;?>_pic_<?php echo $k;?>" style="display:none;">
              <input name="recommend_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_id]" value="<?php echo $v['pic_id'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_name]" value="<?php echo $v['pic_name'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_url]" value="<?php echo $v['pic_url'];?>" type="hidden">
              <input name="recommend_list[<?php echo $key;?>][pic_list][<?php echo $v['pic_id'];?>][pic_img]" value="<?php echo $v['pic_img'];?>" type="hidden">
            </li>
            <?php } ?>
            <?php } ?>
          </ul>
        </dd>
      </dl>
    </div>
    <?php } ?>
    <?php } ?>
    <div id="add_recommend_list" style="display:none;"></div>
    <div class="imap-form-all">
      <dl class="row">
        <dt class="tit"><?php echo $lang['web_config_recommend_add_goods'];?></dt>
        <dd class="opt">
          <div class="search-bar">
            <label id="recommend_gcategory">商品分类
              <input type="hidden" id="cate_id" name="cate_id" value="0" class="mls_id" />
              <input type="hidden" id="cate_name" name="cate_name" value="" class="mls_names" />
              <select>
                <option value="0"><?php echo $lang['im_please_choose'];?></option>
                <?php if(!empty($output['goods_class']) && is_array($output['goods_class'])) { ?>
                <?php foreach($output['goods_class'] as $k => $v) { ?>
                <option value="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </label>
            <input type="text" value="" name="recommend_goods_name" id="recommend_goods_name" placeholder="输入商品名称或SKU编号" class="txt w150">
            <a href="JavaScript:void(0);" onclick="get_recommend_goods();" class="imap-btn"><?php echo $lang['im_query'];?></a></div>
          <div id="show_recommend_goods_list" class="show-recommend-goods-list"></div>
        </dd>
      </dl>
    </div>
    <div class="bot"><a href="JavaScript:void(0);" onclick="update_recommend();" class="imap-btn-big imap-btn-green"><span><?php echo $lang['web_config_save'];?></span></a></div>
  </form>
</div>
<!-- 中部推荐图片 -->
<div id="recommend_pic_dialog" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo '单击广告图选中对应的位置，在底部上传和修改图片信息。';?></div>
  <form id="recommend_pic_form" name="recommend_pic_form" enctype="multipart/form-data" method="post" action="index.php?act=web_config&op=recommend_pic" target="upload_pic">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="web_id" value="<?php echo $output['code_recommend_list']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_recommend_list']['code_id'];?>">
    <input type="hidden" name="key_id" value="">
    <input type="hidden" name="pic_id" value="">
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit"><?php echo '推荐模块标题名称';?></dt>
        <dd class="opt">
          <input name="recommend_list[recommend][name]" value="" type="text" class="input-txt">
          <p class="notic"><?php echo ' 修改该区域中部推荐模块选项卡名称，控制名称字符在4-8字左右，超出范围自动隐藏';?></p>
        </dd>
      </dl>   
      <dl class="row">
        <dt class="tit">广告图区域选择</dt>
        <dd class="opt" id="vip_recommend_pic">
          <?php if (is_array($output['code_recommend_list']['code_info']) && !empty($output['code_recommend_list']['code_info'])) { ?>
          <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) { ?>
          <?php if (!empty($val['pic_list']) && is_array($val['pic_list'])) { ?>
          <div select_recommend_pic_id="<?php echo $key;?>" class="middle-banner"> 
            <a recommend_pic_id="11" href="javascript:void(0);" class="left-a">
              <img pic_url="<?php echo $val['pic_list']['11']['pic_url'];?>" title="<?php echo $val['pic_list']['11']['pic_name'];?>" pic_name="<?php echo $val['pic_list']['11']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['11']['pic_img'];?>"/>
            </a> 
            <a recommend_pic_id="12" href="javascript:void(0);" class="left-b">
              <img pic_url="<?php echo $val['pic_list']['12']['pic_url'];?>" title="<?php echo $val['pic_list']['12']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['12']['pic_img'];?>"/>
            </a> 
            <a recommend_pic_id="14" href="javascript:void(0);" class="middle-a">
              <img pic_url="<?php echo $val['pic_list']['14']['pic_url'];?>" title="<?php echo $val['pic_list']['14']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['14']['pic_img'];?>"/>
            </a> 
            <a recommend_pic_id="21" href="javascript:void(0);" class="right-a">
              <img pic_url="<?php echo $val['pic_list']['21']['pic_url'];?>" title="<?php echo $val['pic_list']['21']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['21']['pic_img'];?>"/>
            </a> 
            <a recommend_pic_id="24" href="javascript:void(0);" class="right-b">
              <img pic_url="<?php echo $val['pic_list']['24']['pic_url'];?>" title="<?php echo $val['pic_list']['24']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['24']['pic_img'];?>"/>
            </a> 
            <a recommend_pic_id="31" href="javascript:void(0);" class="bottom-a">
              <img pic_url="<?php echo $val['pic_list']['31']['pic_url'];?>" title="<?php echo $val['pic_list']['31']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['31']['pic_img'];?>"/>
            </a> 
            <a recommend_pic_id="32" href="javascript:void(0);" class="bottom-b">
              <img pic_url="<?php echo $val['pic_list']['32']['pic_url'];?>" title="<?php echo $val['pic_list']['32']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['32']['pic_img'];?>"/>
            </a> 
            <a recommend_pic_id="33" href="javascript:void(0);" class="bottom-c">
              <img pic_url="<?php echo $val['pic_list']['33']['pic_url'];?>" title="<?php echo $val['pic_list']['33']['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['33']['pic_img'];?>"/>
            </a> 
          </div>
          <?php } ?>
          <?php } ?>
          <?php } ?>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo '文字标题';?></dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="pic_list[pic_name]" value="">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo '跳转链接';?></dt>
        <dd class="opt">
          <input class="input-txt" type="text" name="pic_list[pic_url]" value="<?php echo SHOP_SITE_URL;?>">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo '图片上传';?></dt>
        <dd class="opt">
          <div class="input-file-show">
            <span class="type-file-box">
              <input type='text' name='textfield' id='textfield1' value='' class='type-file-text' />
              <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
              <input name="pic" id="pic" type="file" class="type-file-file" value='' size="30">
            </span>
          </div>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" onclick="$('#recommend_pic_form').submit();" class="imap-btn-big imap-btn-green"><span><?php echo $lang['web_config_save'];?></span></a></div>
    </div>
  </form>
</div>
<!-- 切换广告图片 -->
<div id="upload_adv_dialog" class="upload_adv_dialog" style="display:none;">
  <div class="s-tips"><i class="fa fa-lightbulb-o"></i><?php echo '小提示：单击图片选中修改，拖动可以排序，最少保留1个，最多可加3个，保存后生效。';?></div>
  <form id="upload_adv_form" name="upload_adv_form" enctype="multipart/form-data" method="post" action="index.php?act=web_config&op=slide_adv" target="upload_pic">
    <input type="hidden" name="web_id" value="<?php echo $output['code_adv']['web_id'];?>">
    <input type="hidden" name="code_id" value="<?php echo $output['code_adv']['code_id'];?>">
    <div class="imap-form-all">
      <dl class="row">
        <dt class="tit"><?php echo '已上传图片';?></dt>
        <dd class="opt">
          <ul class="adv dialog-adv-s1">
            <?php if (is_array($output['code_adv']['code_info']) && !empty($output['code_adv']['code_info'])) { ?>
            <?php foreach ($output['code_adv']['code_info'] as $key => $val) { ?>
            <?php if (is_array($val) && !empty($val)) { ?>
            <li slide_adv_id="<?php echo $val['pic_id'];?>">
              <div class="adv-pic">
                <span class="ac-ico" onclick="del_slide_adv(<?php echo $val['pic_id'];?>);"></span>
                <img onclick="select_slide_adv(<?php echo $val['pic_id'];?>);" title="<?php echo $val['pic_name'];?>" src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>"/>
              </div>
              <input name="adv[<?php echo $val['pic_id'];?>][pic_id]" value="<?php echo $val['pic_id'];?>" type="hidden">
              <input name="adv[<?php echo $val['pic_id'];?>][pic_name]" value="<?php echo $val['pic_name'];?>" type="hidden">
              <input name="adv[<?php echo $val['pic_id'];?>][pic_url]" value="<?php echo $val['pic_url'];?>" type="hidden">
              <input name="adv[<?php echo $val['pic_id'];?>][pic_img]" value="<?php echo $val['pic_img'];?>" type="hidden">
            </li>
            <?php } ?>
            <?php } ?>
            <?php } ?>
          </ul>
          <a class="imap-btn" href="JavaScript:add_slide_adv();"><i class="fa fa-plus"></i><?php echo '新增图片';?>&nbsp;(最多3个)</a>
        </dd>
      </dl>
    </div>
    <div id="upload_slide_adv" class="imap-form-default" style="display:none;">
      <dl class="row">
        <dt class="tit"><?php echo '文字标题';?></dt>
        <dd class="opt">
          <input type="hidden" name="slide_id" value="">
          <input class="input-txt" type="text" name="slide_pic[pic_name]" value="">
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['web_config_upload_url'];?></label>
        </dt>
        <dd class="opt">
          <input name="slide_pic[pic_url]" value="" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['web_config_adv_url_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['web_config_upload_adv_pic'].$lang['im_colon'];?></dt>
        <dd class="opt">
          <div class="input-file-show">
            <span class="type-file-box">
              <input type='text' name='textfield' id='textfield1' class='type-file-text' />
              <input type='button' name='button' id='button1' value='选择上传...' class='type-file-button' />
              <input name="pic" id="pic" type="file" class="type-file-file" size="30">
            </span>
          </div>
          <p class="notic"><?php echo $lang['web_config_upload_pic_tips'];?></p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" onclick="$('#upload_adv_form').submit();" class="imap-btn-big imap-btn-green"><?php echo $lang['web_config_save'];?></a></div>
    </div>
  </form>
</div>
<iframe style="display:none;" src="" name="upload_pic"></iframe>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/colorpicker/evol.colorpicker.css" rel="stylesheet" type="text/css">
<script src="<?php echo RESOURCE_SITE_URL;?>/js/colorpicker/evol.colorpicker.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" type="text/javascript" charset="utf-8"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>

<script src="<?php echo ADMIN_RESOURCE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo ADMIN_RESOURCE_URL?>/js/web_index.js"></script>