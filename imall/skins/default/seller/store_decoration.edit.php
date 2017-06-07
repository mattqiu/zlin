<?php defined('InIMall') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_SKINS_URL?>/css/shop_custom.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.SuperSlide.js" charset="utf-8"></script>

<div class="imsc-path"><i class="fa fa-desktop"></i>商家管理中心<i class="fa fa-angle-right"></i>店铺<i class="fa fa-angle-right"></i>店铺装修<i class="fa fa-angle-right"></i>页面设计</div>
<div class="imsc-decoration-layout">
  <div class="imsc-decoration-menu" id="waypoints">
    <div class="title"><i class="icon"></i>
      <h3>店铺装修选项</h3>
      <h5>店铺首页模板设计操作</h5>
    </div>
    <ul class="menu">
      <li><a id="btn_edit_background" href="javascript:void(0);"><i class="background"></i>编辑背景</a></li>
      <li><a id="btn_edit_head" href="javascript:void(0);"><i class="head"></i>编辑头部</a></li>
      <li><a id="btn_add_block" href="javascript:void(0);"><i class="block"></i>添加布局块</a></li>
      <li><a id="btn_preview" href="<?php echo urlShop('store_decoration', 'decoration_preview', array('decoration_id' => $_GET['decoration_id']));?>" target="_blank"><i class="preview"></i>设计预览</a></li>
      <li><a id="btn_close" href="javascript:void(0);"><i class="close"></i>完成退出</a></li>
    </ul>
    <div class="faq">下方区域为1180像素宽度即时编辑区域；“添加布局块”后选择模块类型进行详细设置；“设计预览”可查看生成后效果；内容将实时保存，设置完成后直接选择“完成退出”。</div>
  </div>
  <div id="store_decoration_content" style="<?php echo $output['decoration_background_style'];?>">
    <div id="decoration_banner" class="ncsl-nav"> </div>
    <div id="decoration_nav" class="ncsl-nav">
      <div class="imcs-nav">
        <ul>
          <li class="active"><a href="javascript:void(0);"><span>店铺首页<i></i></span></a></li>
          <li><a href="javascript:void(0);"><span>店铺动态<i></i></span></a></li>
        </ul>
      </div>
    </div>
    <div id="store_decoration_area" class="store-decoration-page">
      <?php if(!empty($output['block_list']) && is_array($output['block_list'])) {?>
      <?php foreach($output['block_list'] as $block) {?>
      <?php 
	    if ($block['block_module_type']=='goods'){
	      require('store_decoration_block_good.php');
		}else{
		  require('store_decoration_block.php');
		}
      ?>
      <?php } ?>
      <?php } ?>
    </div>
  </div>
</div>
<!-- 背景编辑对话框 -->
<div id="dialog_edit_background" class="eject_con dialog-decoration-edit" style="display:none;">
  <dl>
    <dt>背景颜色：</dt>
    <dd>
      <input id="txt_background_color" class="text w80" type="text" name="" value="<?php echo $output['decoration_setting']['background_color'];?>" maxlength="7">
      <p class="hint">设置背景颜色请使用十六进制形式(#XXXXXX)，默认留空为白色背景。</p>
    </dd>
  </dl>
  <dl>
    <dt>背景图：</dt>
    <dd>
      <div class="imsc-upload-btn"> <a href="javascript:void(0);"><span>
        <input type="file" hidefocus="true" size="1" class="input-file" id="file_background_image" name="file"/>
        </span>
        <p><i class="fa fa-upload"></i>图片上传</p>
        </a> </div>
      <div id="div_background_image" <?php if(empty($output['decoration_setting']['background_image'])) { echo "style='display:none;'";} ?> class="background-image-thumb"> <img id="img_background_image" src="<?php echo $output['decoration_setting']['background_image_url'];?>" alt="">
        <input id="txt_background_image" type="hidden" name="" value="<?php echo $output['decoration_setting']['background_image'];?>">
        <a id="btn_del_background_image" class="del" href="javascript:void(0);" title="移除背景图">X</a></div>
    </dd>
  </dl>
  <dl>
    <dt>背景图定位：</dt>
    <dd>
      <input id="txt_background_position_x" class="text w40" type="text" value="<?php echo $output['decoration_setting']['background_position_x'];?>"><label class="add-on">X</label>
      &#12288;&#12288;
      <input id="txt_background_position_y" class="text w40" type="text" value="<?php echo $output['decoration_setting']['background_position_y'];?>"><label class="add-on">Y</label>
      <p class="hint">设置背景图像的起始位置。</p>
    </dd>
  </dl>
  <dl>
    <dt>背景图填充方式：</dt>
    <dd>
      <?php $repeat = $output['decoration_setting']['background_image_repeat'];?>
      <input id="input_no_repeat" type="radio" value="no-repeat" name="background_repeat" <?php if(empty($repeat) || $repeat == 'no-repeat') {echo 'checked';}?>>
      <label for="input_no_repeat">不重复</label>
      <input id="input_repeat" type="radio" value="repeat" name="background_repeat" <?php if($repeat == 'repeat') {echo 'checked';}?>>
      <label for="input_repeat">平铺</label>
      <input id="input_repeat_x" type="radio" value="repeat-x" name="background_repeat" <?php if($repeat == 'repeat-x') {echo 'checked';}?>>
      <label for="input_repeat_x">x轴平铺</label>
      <input id="input_repeat_y" type="radio" value="repeat-y" name="background_repeat" <?php if($repeat == 'repeat-y') {echo 'checked';}?>>
      <label for="input_repeat_y">y轴平铺</label>
    </dd>
  </dl>
  <dl>
    <dt>背景滚动模式：</dt>
    <dd>
      <input id="txt_background_attachment" class="text w80" type="text" value="<?php echo $output['decoration_setting']['background_attachment'];?>">
      <p class="hint">设置背景随屏幕滚动或固定，例如："scroll"或"fixed"。 </p>
    </dd>
  </dl>
  <div class="bottom">
    <label class="submit-border"><a id="btn_save_background" class="submit" href="javascript:void(0);">保存</a></label>
  </div>
</div>
<!-- 头部编辑对话框 -->
<div id="dialog_edit_head" class="eject_con dialog-decoration-edit" style="display:none;">
  <div id="dialog_edit_head_tabs">
    <ul>
      <li><a href="#dialog_edit_head_tabs_1">头部导航</a></li>
      <li><a href="#dialog_edit_head_tabs_2">头部图片</a></li>
    </ul>
    <div id="dialog_edit_head_tabs_1">
      <dl>
        <dt>是否显示：</dt>
        <dd>
          <label for="decoration_nav_display_true">
            <input id="decoration_nav_display_true" type="radio" class="radio" value="true" name="decoration_nav_display" <?php if(empty($output['decoration_nav']) || $output['decoration_nav']['display'] == 'true') { echo 'checked'; }?>>
            显示</label>
          <label for="decoration_nav_display_false">
            <input id="decoration_nav_display_false" type="radio" class="radio" value="false" name="decoration_nav_display" <?php if($output['decoration_nav']['display'] == 'false') { echo 'checked'; }?>>
            不显示</label>
          <p class="hint">“头部导航”为店铺首页店铺导航条，可设置是否显示， 默认为显示。</p>
        </dd>
      </dl>
      <dl>
        <dt>导航样式：</dt>
        <dd>
          <textarea id="decoration_nav_style" class="w400 h100"><?php echo $output['decoration_nav']['style'];?></textarea>
          <p> <a id="btn_default_nav_style" class="imsc-btn-mini" href="javascript:void(0);"><i class="fa fa-refresh"></i>恢复默认</a> </p>
          <p class="hint">导航条对应CSS文件，如修改后显示效果不符可恢复默认值。</p>
        </dd>
      </dl>
      <div class="bottom">
        <label class="submit-border"><a id="btn_save_decoration_nav" class="submit" href="javascript:void(0);">保存</a></label>
      </div>
    </div>
    <div id="dialog_edit_head_tabs_2">
      <dl>
        <dt>是否显示：</dt>
        <dd>
          <label for="decoration_banner_display_true">
            <input id="decoration_banner_display_true" type="radio" class="radio" value="true" name="decoration_banner_display" <?php if(empty($output['decoration_banner']['display']) || $output['decoration_banner']['display'] == 'true') { echo 'checked'; }?>>
            显示</label>
          <label for="decoration_banner_display_false">
            <input id="decoration_banner_display_false" type="radio" class="radio" value="false" name="decoration_banner_display" <?php if($output['decoration_banner']['display'] == 'false') { echo 'checked'; }?>>
            不显示</label>
          <p class="hint">“头部图片”为店铺首页最上方图片，可设置是否显示。</p>
        </dd>
      </dl>
      <dl>
        <dt>图片：</dt>
        <dd>
          <div id="div_banner_image" <?php if(empty($output['decoration_banner']['image'])) { echo "style='display:none;'";} ?> class="background-image-thumb"> <img id="img_banner_image" src="<?php echo $output['decoration_banner']['image_url'];?>" alt="">
            <input id="txt_banner_image" type="hidden" name="" value="<?php echo $output['decoration_banner']['image'];?>">
            <a id="btn_del_banner_image" class="del" href="javascript:void(0);" title="移除">X</a> </div>
          <div class="imsc-upload-btn"> <a href="javascript:void(0);"> <span>
            <input type="file" hidefocus="true" size="1" class="input-file" id="file_decoration_banner" name="file"/>
            </span>
            <p><i class="fa fa-upload"></i>图片上传</p>
            </a> </div>
          <p class="hint">选择上传头部图片，建议使用宽度为1000像素，大小不超过1M的gif\jpg\png格式图片。</p>
        </dd>
      </dl>
      <div class="bottom">
        <label class="submit-border"><a id="btn_save_decoration_banner" class="submit" href="javascript:void(0);">保存设置</a></label>
      </div>
    </div>
  </div>
</div>
<!-- 选择模块对话框 -->
<div id="dialog_select_module" class="dialog-decoration-module" style="display:none;">
  <ul>
    <li><a imtype="btn_show_module_dialog" data-module-type="slide" href="javascript:void(0);"><i class="slide"></i>
      <dl>
        <dt>图片和幻灯</dt>
        <dd>添加图片和可切换幻灯</dd>
      </dl>
      </a>
    </li>    
    <li><a imtype="btn_show_module_dialog" data-module-type="hot_area" href="javascript:void(0);"><i class="hotarea"></i>
      <dl>
        <dt>图片热点</dt>
        <dd>添加图片并设置热点区域链接</dd>
      </dl>
      </a>
    </li>      
    <li class="good"> 
      <a imtype="btn_show_module_dialog" data-module-type="goods" href="javascript:void(0);"><i class="goods"></i>
      <dl>
        <dt>店铺商品</dt>
        <dd>选择添加店铺内的在售商品</dd>
      </dl>
      </a> 
    </li>
    <li>
      <a imtype="btn_show_module_dialog" data-module-type="brand" href="javascript:void(0);"><i class="brand"></i>
      <dl>
        <dt>品牌展示橱窗</dt>
        <dd>选择添加店铺内的品牌</dd>
      </dl>
      </a>
    </li>  
    <li> <a imtype="btn_show_module_dialog" data-module-type="html" href="javascript:void(0);"><i class="html"></i>
      <dl>
        <dt>自定义</dt>
        <dd>使用编辑器自定义编辑html</dd>
      </dl>
      </a> 
    </li>
  </ul>
</div>

<!-- 自定义模块编辑对话框 -->
<div id="dialog_module_html" class="eject_con dialog-decoration-edit" style="display:none;">
  <div class="alert">
    <ul>
      <li>1. 可将预先设置好的网页文件内容复制粘贴到文本编辑器内，或直接在工作窗口内进行编辑操作。</li>
      <li>2. 默认为可视化编辑，选择第一个按钮切换到html代码编辑。css文件可以Style=“...”形式直接写在对应的html标签内。</li>
    </ul>
  </div>
  <textarea id="module_html_editor" name="module_html_editor" style=" width:1016px; height:400px; visibility:hidden;"></textarea>
  <div class="bottom">
    <label class="submit-border"><a id="btn_save_module_html" class="submit" href="javascript:void(0);">保存设置</a></label>
  </div>
</div>
<!-- 幻灯模块编辑对话框 -->
<div id="dialog_module_slide" class="eject_con dialog-decoration-edit" style="display:none;">
  <div class="alert">
    <ul>
      <li>1. 可选择图片以全屏或非全屏形式显示，<strong class="orange">且必须设定图片的高度</strong>，否则将无法正常浏览。</li>
      <li>2. 上传单张图片时系统默认显示为<strong>“图片链接”</strong>形式显示，如一次上传多图将以<strong>“幻灯片”</strong>形式显示。</li>
    </ul>
  </div>
  <div id="module_slide_html" class="slide-upload-thumb">
    <ul class="module-slide-content">
    </ul>
  </div>
  <h4>相关设置：</h4>
  <dl class="display-set">
    <dt>显示设置：</dt>
    <dd><span>全屏显示
      <input id="txt_slide_full_width" type="checkbox" class="checkobx" name="">
      </span><span><strong class="orange">*</strong> 显示高度
      <input id="txt_slide_height" type="text" class="text w40" value=""><em class="add-on">像素</em></span>
      <p><a id="btn_add_slide_image" class="imsc-btn mt5" href="javascript:void(0);"><i class="fa fa-plus"></i>添加图片</a></p>
    </dd>
  </dl>
  <div id="div_module_slide_upload" style="display:none;">
    <form action="">
      <dl>
        <dt>图片上传：</dt>
        <dd>
          <div id="div_module_slide_image" class="module-upload-image-preview"></div>
          <div class="imsc-upload-btn"> <a href="javascript:void(0);"> <span>
            <input type="file" hidefocus="true" size="1" class="input-file" name="file" id="file"  imtype="btn_module_slide_upload"/>
            </span>
            <p><i class="fa fa-upload"></i>图片上传</p>
            </a> </div>
          <p class="hint">请上传宽度为1000像素的jpg/gif/png格式图片。</p>
        </dd>
      </dl>
      <dl>
        <dt>图片链接：</dt>
        <dd>
          <input id="module_slide_url" class="text w400" type="text">
          <p class="hint">请输入以http://为开头的图片链接地址，仅作为图片使用时请留空此选项</p>
          <p class="mt5"><a id="btn_save_add_slide_image" class="imsc-btn imsc-btn-acidblue" href="javascript:void(0);">添加</a> <a id="btn_cancel_add_slide_image" class="imsc-btn imsc-btn-orange" href="javascript:void(0);">取消</a></p>
        </dd>
      </dl>
    </form>
  </div>
  <div class="bottom">
    <label class="submit-border"><a id="btn_save_module_slide" class="submit" href="javascript:void(0);">保存设置</a></label>
  </div>
</div>
<!-- 图片热点模块编辑对话框 -->
<div id="dialog_module_hot_area" class="eject_con dialog-decoration-edit" style="display:none;">
  <div class="alert">
    <ul>
      <li>1. 在已上传的图片范围拖动鼠标形成选择区域，对该区域添加以http://格式开头的链接地址并点击“添加网址”按钮生效。</li>
      <li>2. 对已添加的热点可做编辑链接地址修改，如需调整位置，请删除该热点区域并保存，之后重新选择添加。</li>
    </ul>
  </div>
  <div id="div_module_hot_area_image" class="hot-area-image" style="position: relative;"></div>
  <ul id="module_hot_area_select_list" class="hot-area-select-list">
  </ul>
  <h4>相关设置：</h4>
  <form action="">
    <dl>
      <dt>图片上传：</dt>
      <dd>
        <div class="imsc-upload-btn"> <a href="javascript:void(0);"> <span>
          <input type="file" hidefocus="true" size="1" class="input-file" name="file" id="file"  imtype="btn_module_hot_area_upload"/>
          </span>
          <p><i class="fa fa-upload"></i>图片上传</p>
          </a> </div>
        <p class="hint">选择上传jpg/gif/png格式图片，建议宽度不超过1000像素，高度不超过400像素，如超出此范围，请先自行对图片进行裁切调整。</p>
      </dd>
    </dl>
  </form>
  <dl>
    <dt>热点链接设置：</dt>
    <dd>
      <input id="module_hot_area_url" class="text w400" type="text" />
      <a id="btn_module_hot_area_add" class="imsc-btn ml5" href="javascript:void(0);"><i class="fa fa-anchor"></i>添加网址</a>
      <p class="hint">在输入框中添加以“http://”格式开头的热点区域跳转网址。</p>
    </dd>
  </dl>
  <div class="bottom">
    <label class="submit-border"><a id="btn_save_module_hot_area" class="submit" href="javascript:void(0);">保存设置</a></label>
  </div>
</div>

<!-- 商品模块头部编辑对话框 -->
<div id="dialog_module_goods_title" class="eject_con dialog-decoration-edit" style="display:none;">
  <div id="dialog_module_goods_title_tabs">
    <ul>
      <li><a href="#dialog_module_goods_title_tabs_tit">模块标题</a></li>
      <li><a href="#dialog_module_goods_title_tabs_adv">模块广告</a></li>
    </ul>
    <div id="dialog_module_goods_title_tabs_tit">
      <div class="alert">
        <ul>
          <li>“模块标题”为模块上部的标题，可设置为图片或文字， 默认为文字。</li>
        </ul>
      </div>
      <dl>
        <dt>类型：</dt>
        <dd>
          <label for="module_goods_title_type_text">
            <input id="module_goods_title_type_text" type="radio" class="radio" value="text" name="module_goods_title_type" imtype="module_goods_title_type">
            文字</label>
          <label for="module_goods_title_type_img">
            <input id="module_goods_title_type_img" type="radio" class="radio" value="img" name="module_goods_title_type" imtype="module_goods_title_type">
            图片</label>
        </dd>
      </dl>
      
      <div id="div_module_goods_title_text" style="display:none;">
        <dl>
          <dt>文字标题：</dt>
          <dd>
            <input id="module_goods_title_text" class="text w400" type="text">
            <p class="hint">输入标题文字不多于8个字，显示楼层以|号隔开如： 1F|特别推荐</p>
          </dd>
        </dl>
      </div>
      
      <div id="div_module_goods_title_img" style="display:none;">
        <div id="div_module_goods_title_img_pre" class="module-upload-image-preview"></div>
        <dl>
          <dt>图片上传：</dt>
          <dd>            
            <div class="imsc-upload-btn"> 
              <a href="javascript:void(0);"> 
              <span>
                <input type="file" hidefocus="true" size="1" class="input-file" name="file" id="file"  imtype="btn_module_goods_title_img_upload"/>
              </span>
              <p><i class="fa fa-upload"></i>图片上传</p>
              </a> 
            </div>
            <p class="hint">请上传宽度为250像素高度为40象素的jpg/gif/png格式图片。</p>
          </dd>
        </dl>
      </div>
    </div>
    
    <div id="dialog_module_goods_title_tabs_adv">
      <div class="alert">
        <ul>
          <li>模块广告以文字链接的形式显示，最多显示5个。</li>
        </ul>
      </div>
      <div id="module_goods_title_adv_list" class="adv-caption-list-pre">
        <ul class="module-slide-content">
        </ul>
      </div>
      <dl class="display-set">
        <dd>
          <p><a id="btn_add_goods_adv_caption" class="imsc-btn mt5" href="javascript:void(0);"><i class="fa fa-plus"></i>添加广告</a></p>
        </dd>
      </dl>
      <div id="div_module_goods_title_adv" style="display:none;">
        <dl>
          <dt>广告标题：</dt>
          <dd>
            <input id="module_goods_adv_caption" class="text w400" type="text">
          </dd>
        </dl>
        <dl>
          <dt>广告链接：</dt>
          <dd>
            <input id="module_goods_adv_url" class="text w400" type="text">
            <p class="hint">在输入框中添加以“http://”格式开头的热点区域跳转网址。</p>
            <p class="mt5">
              <a id="btn_save_add_goods_adv_caption" class="imsc-btn imsc-btn-acidblue" href="javascript:void(0);">添加</a> 
              <a id="btn_cancel_add_goods_adv_caption" class="imsc-btn imsc-btn-orange" href="javascript:void(0);">取消</a>
            </p>
          </dd>
        </dl>
      </div>    
    </div>
  </div>
  <div class="bottom">
    <label class="submit-border"><a id="btn_save_module_goods_title" class="submit" href="javascript:void(0);">保存设置</a></label>
  </div>
</div>
<!-- 商品模块编辑对话框 -->
<div id="dialog_module_goods_goods" class="eject_con dialog-decoration-edit" style="display:none;">
  <div class="alert">
    <ul>
      <li>1. 搜索店铺内在售商品并“选择添加”，设置窗口上部将出现已选择的商品列表，也可对其进行“取消选择”操作，点击保存设置后生效。</li>
      <li>2. 当已选择的商品超过10个时，系统默认未全部显示，可通过在已选区域滚动鼠标或拉动侧边条进行查看及操作。</li>
    </ul>
  </div>
  <div id="decorationGoods">
    <ul id="div_module_goods_list" class="goods-list">
    </ul>
  </div>
  <h4 class="mt10">店铺在售商品选择</h4>
  <div class="decoration-search-goods">
    <div class="search-bar">输入商品关键字：
      <input id="txt_goods_search_keyword" type="text" class="text w200 vm" name="">
      <a id="btn_module_goods_search" class="imsc-btn" href="javascript:void(0);">搜索</a><span class="ml10 orange">小提示： 留空搜索显示店铺全部在售商品，每页显示10个。</span>
    </div>
    <div id="decorationGoods_search">
      <ul id="div_module_goods_search_list" class="goods-list"></ul>
    </div>
  </div>
  <div class="bottom"><label class="submit-border"><a id="btn_save_module_goods" class="submit" href="javascript:void(0);">保存设置</a></label></div>
</div>
<!-- 商品幻灯模块编辑对话框 -->
<div id="dialog_module_goods_slide" class="eject_con dialog-decoration-edit" style="display:none;">
  <div class="alert">
    <ul>
      <li>1. 上传单张图片时系统默认显示为<strong>“图片链接”</strong>形式显示，如一次上传多图将以<strong>“幻灯片”</strong>形式显示。</li>
    </ul>
  </div>
  <div id="module_goods_slide_html" class="slide-upload-thumb">
    <ul class="module-slide-content">
    </ul>
  </div>
  <h4>相关设置：</h4>
  <dl class="display-set">
    <dd>
      <p><a id="btn_add_goods_slide_image" class="imsc-btn mt5" href="javascript:void(0);"><i class="fa fa-plus"></i>添加图片</a></p>
    </dd>
  </dl>
  <div id="div_module_goods_slide_upload" style="display:none;">
    <form action="">
      <dl>
        <dt>图片上传：</dt>
        <dd>
          <div id="div_module_goods_slide_image" class="module-upload-image-preview"></div>
          <div class="imsc-upload-btn"> <a href="javascript:void(0);"> <span>
            <input type="file" hidefocus="true" size="1" class="input-file" name="file" id="file"  imtype="btn_module_goods_slide_upload"/>
            </span>
            <p><i class="fa fa-upload"></i>图片上传</p>
            </a> 
          </div>
          <p class="hint">请上传宽度为248像素的jpg/gif/png格式图片。</p>
        </dd>
      </dl>
      <dl>
        <dt>图片链接：</dt>
        <dd>
          <input id="module_goods_slide_url" class="text w400" type="text">
          <p class="hint">请输入以http://为开头的图片链接地址，仅作为图片使用时请留空此选项</p>
          <p class="mt5">
            <a id="btn_save_add_goods_slide_image" class="imsc-btn imsc-btn-acidblue" href="javascript:void(0);">添加</a> 
            <a id="btn_cancel_add_goods_slide_image" class="imsc-btn imsc-btn-orange" href="javascript:void(0);">取消</a>
          </p>
        </dd>
      </dl>
    </form>
  </div>
  <div class="bottom">
    <label class="submit-border"><a id="btn_save_module_goods_slide" class="submit" href="javascript:void(0);">保存设置</a></label>
  </div>
</div>
<!-- 商品品牌模块编辑对话框 -->
<div id="dialog_module_goods_brand" class="eject_con dialog-decoration-edit" style="display:none;">
  <div class="alert">
    <ul>
      <li>1. 搜索店铺内品牌并“选择添加”，设置窗口上部将出现已选择的品牌列表，也可对其进行“取消选择”操作，点击保存设置后生效。</li>
      <li>2. 当已选择的品牌超过10个时，系统默认未全部显示，可通过在已选区域滚动鼠标或拉动侧边条进行查看及操作。</li>
    </ul>
  </div>
  <div id="decorationBrand">
    <ul id="div_module_brand_list" class="brand-list">
    </ul>
  </div>
  <h4 class="mt10">店铺品牌选择</h4>
  <div class="decoration-search-brand">
    <div class="search-bar">输入品牌关键字：
      <input id="txt_brand_search_keyword" type="text" class="text w200 vm" name="">
      <a id="btn_module_brand_search" class="imsc-btn" href="javascript:void(0);">搜索</a><span class="ml10 orange">小提示： 留空搜索显示店铺全部品牌，每页显示10个。</span>
    </div>
    <div id="decorationBrand_search">
      <ul id="div_module_brand_search_list" class="brand-list"></ul>
    </div>
  </div>
  <div class="bottom"><label class="submit-border"><a id="btn_save_module_brand" class="submit" href="javascript:void(0);">保存设置</a></label></div>
</div>
<!-- 品牌模块编辑对话框开始 ---------------------------------------------------------------------------->
<div id="dialog_module_brand" class="eject_con dialog-decoration-edit" style="display:none;">
  <div class="alert">
    <ul>
      <li>1. 搜索店铺内品牌并“选择添加”，设置窗口上部将出现已选择的品牌列表，也可对其进行“取消选择”操作，点击保存设置后生效。</li>
      <li>2. 当已选择的品牌超过10个时，系统默认未全部显示，可通过在已选区域滚动鼠标或拉动侧边条进行查看及操作。</li>
    </ul>
  </div>
  <div class="slideTxtBox" id="brand_module_brand_list" style="height:165px;">
    <div class="brand-list-tit-pre" id="brand_hd">
      <ul class="module-slide-content">
      </ul>
    </div>
    <div class="brand-list-logo-pre" id="brand_bd">

    </div>
  </div>
  <dl class="display-set" id="brand_btn_add_group">
    <dd>
      <p>
        <a id="brand_btn_add_row" class="imsc-btn mt5" href="javascript:void(0);"><i class="fa fa-plus"></i>添加品牌橱窗</a>&nbsp&nbsp&nbsp&nbsp&nbsp
        <a id="brand_btn_add_item" class="imsc-btn mt5" href="javascript:void(0);"><i class="fa fa-plus"></i>添加品牌</a>
      </p>
    </dd>
  </dl>
  
  <div id="brand_div_module_row" style="display:none;">
    <dl>
      <dt></dt>
      <dd><b>添加品牌展示橱窗</b></dd>      
    </dl>
    <dl>
      <dt>标题：</dt>
      <dd>
        <input id="brand_module_row_caption" class="text w400" type="text">
      </dd>
    </dl>
    <dl>
      <dt>副标题：</dt>
      <dd>
        <input id="brand_module_row_sec_caption" class="text w400" type="text">        
      </dd>
    </dl>
    <p class="mt5" style="padding-left:200px;">
      <a id="brand_btn_save_add_row" class="imsc-btn imsc-btn-acidblue" href="javascript:void(0);">添加</a> 
      <a id="brand_btn_cancel_add_row" class="imsc-btn imsc-btn-orange" href="javascript:void(0);">取消</a>
    </p>
  </div> 
  
  <div id="brand_div_module_item" style="display:none;">
    <dl>
      <dt></dt>
      <dd><b>添加品牌</b></dd>      
    </dl>
    <dl>
      <dt>品牌图片：</dt>
      <dd>
        <div id="brand_div_module_pic_pre" class="brand-module-upload-pic-preview"></div>
        <div class="imsc-upload-btn"> 
          <a href="javascript:void(0);"> 
          <span>
            <input type="file" hidefocus="true" size="1" class="input-file" name="file" id="file"  imtype="brand_module_pic_upload"/>
          </span>
          <p><i class="fa fa-upload"></i>图片上传</p>
          </a> 
        </div>
        <p class="hint">请上传宽度为150X60像素的jpg/gif/png格式图片。</p>
      </dd>
    </dl>
    <dl>
      <dt>品牌名称：</dt>
      <dd>
        <input id="brand_module_item_name" class="text w400" type="text">
      </dd>
    </dl>
    <dl>
      <dt>品牌链接：</dt>
      <dd>
        <input id="brand_module_item_url" class="text w400" type="text">        
      </dd>
    </dl>
    <p class="mt5" style="padding-left:200px;">
      <a id="brand_btn_save_add_item" class="imsc-btn imsc-btn-acidblue" href="javascript:void(0);">添加</a> 
      <a id="brand_btn_cancel_add_item" class="imsc-btn imsc-btn-orange" href="javascript:void(0);">取消</a>
    </p>
  </div>    

  <h4 class="mt10">店铺品牌选择</h4>
  <div class="decoration-search-brand">
    <div class="search-bar">输入品牌关键字：
      <input id="brand_search_keyword" type="text" class="text w200 vm" name="">
      <a id="brand_module_btn_search" class="imsc-btn" href="javascript:void(0);">搜索</a><span class="ml10 orange">小提示： 留空搜索显示店铺全部品牌，每页显示10个。</span>
    </div>
    <div id="brand_decoration_search">
      <ul id="brand_div_module_search_list" class="brand-list"></ul>
    </div>
  </div>
  <div class="bottom"><label class="submit-border"><a id="brand_btn_save_module_list" class="submit" href="javascript:void(0);">保存设置</a></label></div>
</div>
<!-- 品牌模块编辑对话框结束 ---------------------------------------------------------------------------->
<!-- 幻灯模板 --> 
<script id="template_module_slide_image_list" type="text/html">
<li data-image-name="<%=image_name%>" data-image-url="<%=image_url%>" data-image-link="<%=image_link%>">
<span><img src="<%=image_url%>"></span>
<a imtype="btn_del_slide_image" href="javascript:void(0);" title="删除">X</a>
</li>
</script> 
<!-- 商品幻灯模板 --> 
<script id="template_module_goods_slide_image_list" type="text/html">
<li data-image-name="<%=image_name%>" data-image-url="<%=image_url%>" data-image-link="<%=image_link%>">
<span><img src="<%=image_url%>"></span>
<a imtype="btn_del_goods_slide_image" href="javascript:void(0);" title="删除">X</a>
</li>
</script> 
<!-- 商品广告模板 --> 
<script id="template_module_goods_adv_list" type="text/html">
<li imtype="adv_item" data-adv-name="<%=adv_name%>" data-adv-url="<%=adv_url%>">
<span><%=adv_name%></span>
<a imtype="btn_del_goods_adv_caption" href="javascript:void(0);" title="删除">X</a>
</li>
</script>
<!-- 品牌模板展示橱窗 --> 
<script id="template_module_brand_row_list" type="text/html">
<li imtype="brand_row_item" data-row-caption="<%=row_caption%>" data-row-sec-caption="<%=row_sec_caption%>" data-row-index="<%=row_count%>" onclick="javascript:brand_row_item_click($(this));"  onmouseover="javascript:brand_row_item_over($(this));" onmouseout="javascript:brand_row_item_out($(this));" <% if(row_show){%>class="on"<%}%>>
<b><%=row_caption%></b>
<p><%=row_sec_caption%></p>
<a imtype="brand_btn_del_row" class="del" href="javascript:void(0);" title="删除">X</a>
</li>
</script> 
<!-- 品牌 --> 
<script id="template_module_brand_item_list" type="text/html">
<li imtype="brand_item" class="brand_item" data-brand-name="<%=brand_name%>" data-brand-img="<%=brand_img%>" data-brand-url="<%=brand_url%>">
<a href="<%=brand_url%>" target="_blank" title="<%=brand_name%>"> 
<img width="90" height="30" border="0" alt="<%=brand_name%>" src="<%=brand_img%>">
</a> 
<a imtype="btn_module_brand_operate" class="imsc-btn-mini" href="javascript:;"><i class="fa fa-ban"></i>取消选择</a>
</li>
</script> 
<!-- 热点块控制模板 --> 
<script id="template_module_hot_area_list" type="text/html">
<li data-hot-area-link="<%=link%>" data-hot-area-position="<%=position%>">
<i></i>
<p>热点区域<%=index%></p>
<p><a imtype="btn_module_hot_area_select" data-hot-area-position="<%=position%>" class="imsc-btn-mini imsc-btn-acidblue" href="javascript:void(0);">选择</a>
<a data-index="<%=index%>" imtype="btn_module_hot_area_del" class="imsc-btn-mini imsc-btn-red" href="javascript:void(0);">删除</a></p>
</li>
</script> 
<!-- 热点块标识模板 --> 
<script id="template_module_hot_area_display" type="text/html">
<div class="store-decoration-hot-area-display" style="width:<%=width%>px;height:<%=height%>px;position:absolute;left:<%=left%>px;top:<%=top%>px;border:1px solid #cccccc;" id="hot_area_display_<%=index%>">热点区域<%=index%></div>
</li>
</script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/template.min.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/kindeditor/kindeditor-min.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/kindeditor/lang/zh_CN.js" charset="utf-8"></script>
<link media="all" rel="stylesheet" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.imgareaselect/imgareaselect-animated.css" type="text/css" />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.imgareaselect/jquery.imgareaselect.min.js"></script> 
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script> 
<script type="text/javascript"> 
    //定义api常量
    var DECORATION_ID = <?php echo $_GET['decoration_id'];?>;
    var URL_DECORATION_ALBUM_UPLOAD = '<?php echo urlShop('store_decoration', 'decoration_album_upload');?>';
    var URL_DECORATION_BACKGROUND_SETTING_SAVE = '<?php echo urlShop('store_decoration', 'decoration_background_setting_save');?>';
    var URL_DECORATION_NAV_SAVE = '<?php echo urlShop('store_decoration', 'decoration_nav_save');?>';
    var URL_DECORATION_BANNER_SAVE = '<?php echo urlShop('store_decoration', 'decoration_banner_save');?>';
    var URL_DECORATION_BLOCK_ADD = '<?php echo urlShop('store_decoration', 'decoration_block_add');?>';
    var URL_DECORATION_BLOCK_DEL = '<?php echo urlShop('store_decoration', 'decoration_block_del');?>';
    var URL_DECORATION_BLOCK_SAVE = '<?php echo urlShop('store_decoration', 'decoration_block_save');?>';
	var URL_DECORATION_BLOCK_SAVE_GOODS = '<?php echo urlShop('store_decoration', 'decoration_block_save_goods');?>';
    var URL_DECORATION_BLOCK_SORT = '<?php echo urlShop('store_decoration', 'decoration_block_sort');?>';
    var URL_DECORATION_GOODS_SEARCH = '<?php echo urlShop('store_decoration', 'goods_search');?>';
	var URL_DECORATION_BRAND_SEARCH = '<?php echo urlShop('store_decoration', 'brand_search');?>';
    var LOADING_IMAGE = '<?php echo SHOP_SKINS_URL . DS . 'images/loading.gif';?>';
    var POSHYTIP = {
        className: 'tip-yellowsimple',
        showTimeout: 1,
        alignTo: 'target',
        alignX: 'top',
        alignY: 'left',
        offsetX: -300,
        offsetY: -5,
        allowTipHover: false
    };


    $(document).ready(function(){
        //浮动导航  waypoints.js
        $("#waypoints").waypoint(function(event, direction) {
            $(this).parent().toggleClass('sticky', direction === "down");
            event.stopPropagation();
        });

        //商品模块已选商品滚动条
        $('#decorationGoods').perfectScrollbar();

		//title提示
    	$('.tip').poshytip(POSHYTIP);
    });		

</script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/decoration/store_decoration.js" charset="utf-8"></script> 
