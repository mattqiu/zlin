<?php defined('InIMall') or exit('Access Invalid!'); ?>

<script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/common_select.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8" type="text/javascript"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/fileupload/jquery.ui.widget.js" charset="utf-8" type="text/javascript"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/fileupload/jquery.fileupload.js" charset="utf-8" type="text/javascript"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.poshytip.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.mousewheel.js"></script>
<script src="<?php echo RESOURCE_SITE_URL; ?>/js/jquery.charCount.js" type="text/javascript"></script>
<!--[if lt IE 8]>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/json2.js"></script>
<![endif]-->
<script src="<?php echo MOBILE_SKINS_URL; ?>/js/store_goods_add.step2.js"></script>
<link rel="stylesheet" type="text/css"
      href="<?php echo RESOURCE_SITE_URL; ?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"/>
<link rel="stylesheet" type="text/css"
      href="<?php echo MOBILE_SKINS_URL; ?>/css/dist/css/wangEditor.min.css"/>
<link rel="stylesheet" type="text/css"
      href="<?php echo MOBILE_SKINS_URL; ?>/css/goods-add.css"/>
<div id="fixedNavBa" style="display:none;">
    <h3>页面导航</h3>
    <ul>
        <li><a id="demo1Btn" href="#demo1" class="demoBtn">基本信息</a></li>
        <li><a id="demo2Btn" href="#demo2" class="demoBtn">详情描述</a></li>
        <?php if ($output['goods_class']['gc_virtual'] == 1) { ?>
            <li><a id="demo3Btn" href="#demo3" class="demoBtn">特殊商品</a></li>
        <?php } ?>
        <li><a id="demo4Btn" href="#demo4" class="demoBtn">物流运费</a></li>
        <li><a id="demo5Btn" href="#demo5" class="demoBtn">发票信息</a></li>
        <li><a id="demo6Btn" href="#demo6" class="demoBtn">其他信息</a></li>
    </ul>
</div>
<link href="<?php echo MOBILE_SKINS_URL; ?>/css/promotion.css" rel="stylesheet" type="text/css">
<style>
    .header_title {
        width: 90%;
    }
    .tabmenu ul li {
        width: 50%;
        text-align: center;
    }
</style>
<div class="warp">
    <div class="goods-img">
        <div class="loading-box">
	        <img src="<?php echo MOBILE_SKINS_URL; ?>/images/loading.gif"/>
	        <span class="load-text">图片上传中。。。</span>
        </div>
        <?php echo $output['goods']; ?>
        <img src="<?php echo thumb($output['goods'], 240); ?>" id="img-view">
        <label for="upload_img" class="save_img-btn"><img src="<?php echo MOBILE_SKINS_URL; ?>/images/paizhao.png"/></label>
        <input id="upload_img" type="file" accept="image/*" name="goods_image" hidefocus="true" size="1">
    </div>
    <div class="header navbar-fixed-top">
        <div class="return fl">
            <a href="javascript:window.history.go(-1);">
            	<img width="15" height="25" src="<?php echo MOBILE_SKINS_URL; ?>/css/images/return_img.jpg"/>
            </a>
        </div>
        <div class="header_title hh">
            <?php if ($output['edit_goods_sign']) {
                echo '编辑商品';
            } else {
                echo '新增自营商品';
            } ?></div>
        <div class="clear"></div>
    </div>
    <?php if ($output['edit_goods_sign']) { ?>
        <div class="tabmenu">
            <?php include template('layout/submenu'); ?>
        </div>
    <?php } ?>
    <div class="item-publish">
        <form method="post" id="goods_form" action="
	        <?php if ($output['edit_goods_sign']) {
	            echo urlMobile('store_goods_online', 'edit_save_goods');
	        } else {
	            echo urlMobile('store_goods_add', 'save_goods');
	        } ?>">
            <input type="hidden" name="form_submit" value="ok"/>
            <input type="hidden" name="commonid" value="<?php echo $output['goods']['goods_commonid']; ?>"/>
            <input type="hidden" name="type_id" value="<?php echo $output['goods_class']['type_id']; ?>"/>
            <input type="hidden" name="ref_url" value="<?php echo $_GET['ref_url'] ? $_GET['ref_url'] : getReferer(); ?>"/>
            <div class="ncsc-form-goods">
                <h3>商品分类</h3>
                <dl>
                    <dd id="gcategory">
						<i class="required">*</i><?php echo $lang['store_goods_index_goods_class'] . $lang['nc_colon']; ?>
	                	<input type="hidden" class="ncbtn gcategory_href" value="
	                        <?php if ($output['edit_goods_sign']) {
	                            echo urlMobile('store_goods_online', 'edit_class', array('commonid' => $output['goods']['goods_commonid'], 'ref_url' => getReferer()));
	                        } else {
	                            echo urlMobile('store_goods_add', 'add_step_one');
	                        } ?>">
	               		<span class="right-icon"><img src="<?php echo MOBILE_SKINS_URL; ?>/images/right.png"> </span>
	                    <?php if ($output['edit_goods_sign']) { ?>
	                    <span class="inblock vertical-middle width70 gcategory_input word-break" style="text-align:right; ">  
	                		<?php echo $output['goods_class']['gc_tag_name'] ? $output['goods_class']['gc_tag_name'] : '请选择分类'; ?>
	                   	</span>
	                    <?php } else { ?>
	                            <span class="inblock vertical-middle width70 gcategory_input no_edit_sign word-break" style="text-align:right; "> </span>
	                   	<?php } ?>
	                   	<span class="init"></span>
	                	<input type="hidden" name="cate_name" value="<?php echo $output['goods_class']['gc_tag_name']; ?>" class="text"/>
               		</dd>
                </dl>
                <h3 id="demo1"><?php echo $lang['store_goods_index_goods_base_info'] ?></h3>
                <!-- 商品标题 -->
                <dl>
                    <dd class="bgfff vertical-middle goods-key-btn">
<!--                	<span class="inblock vertical-middle"><i class="required">*</i>--><?php //echo $lang['store_goods_index_goods_name'] . $lang['nc_colon']; ?>
<!--                    </span>-->
                       	<span class="inblock vertical-middle goods-title">
                           	<textarea name="g_name" placeholder="*输入商品名称" class="goods-input"><?php echo $output['goods']['goods_name']; ?></textarea>
                           <span class="key-number"><b class="good-number">0</b>/60</span>
                       	</span>
                       	<span class="init"></span>
                    </dd>
                </dl>
                <!-- 商品卖点 -->
                <dl>
                    <dd class="bgfff vertical-middle ">
                       <span class="inblock vertical-middle goods-title">
                           	<textarea name="g_jingle" class="selle-banner" placeholder="商品卖点"><?php echo $output['goods']['goods_jingle']; ?></textarea>
                        	<span class="key-number"><b class="banner-number">0</b>/140</span>
                       	</span>
                        <span class="init"></span>
                    </dd>
                </dl>
                <!-- 商家货号  -->
                <dl>
                    <dd class="bgfff vertical-middle" nc_type="no_spec">
                    	<span class="inblock vertical-middle">
                        	<i class="required">*</i><?php echo $lang['store_goods_index_goods_no'] . $lang['nc_colon']; ?>
                        </span>
                       	<span class="inblock vertical-middle width70">
                       		<input name="g_serial" placeholder="" value="<?php if ($output['edit_goods_sign']) { echo ncPriceFormat($output['goods']['goods_serial']);} ?>"
                            	type="text" class="form-control g_serial"/>
                       	</span>
                        <span class="init"></span>
                    </dd>
                </dl>
                <!-- 会员价格  -->
                <dl>
                    <dd class="bgfff vertical-middle" nc_type="no_spec">
                    	<span class="inblock vertical-middle">
                        	<i class="required">*</i><?php echo $lang['store_goods_index_store_price'] . $lang['nc_colon']; ?>
                        </span>
                       	<span class="inblock vertical-middle width70">
                       		<input name="g_price" placeholder="0.00" value="<?php if ($output['edit_goods_sign']) { echo ncPriceFormat($output['goods']['goods_price']);} ?>"
                            	type="text" class="form-control g_price"/>
                        </span>
                        <span class="init"></span>
                    </dd>
                </dl>
				<!-- 吊牌价格  -->
                <dl>
                    <dd class="bgfff vertical-middle">
                    	<span class="inblock vertical-middle">
                    		<i class="required">*</i>吊牌价<?php echo $lang['nc_colon']; ?>
                      	</span>
                       	<span class="inblock vertical-middle width70">   
                       		<input name="g_marketprice"
                                   value="<?php if ($output['edit_goods_sign']) { echo ncPriceFormat($output['goods']['goods_marketprice']);} ?>"
                                   type="text" class="form-control g_marketprice" placeholder="0.00"/>
                        </span><span class="init"></span>
                    </dd>
                </dl>
                <dl>
                    <dd class="bgfff vertical-middle">
                    	<span class="inblock vertical-middle">
                    		批发价<?php echo $lang['nc_colon']; ?>
                      	</span>
                       	<span class="inblock vertical-middle width70">   
                       		<input name="g_tradeprice"
                                   value="<?php if ($output['edit_goods_sign']) { echo ncPriceFormat($output['goods']['goods_tradeprice']);} ?>"
                                   type="text" class="form-control g_tradeprice" placeholder="0.00"/>
                        </span><span class="init"></span>
                    </dd>
                </dl>
                <dl>
                    <dd class="bgfff vertical-middle">
                    	<span class="inblock vertical-middle">
                    		店铺成本<?php echo $lang['nc_colon']; ?>
                      	</span>
                       	<span class="inblock vertical-middle width70">   
                       		<input name="g_costprice"
                                   value="<?php if ($output['edit_goods_sign']) { echo ncPriceFormat($output['goods']['goods_costprice']);} ?>"
                                   type="text" class="form-control g_costprice" placeholder="0.00"/>
                        </span><span class="init"></span>
                    </dd>
                </dl>
                <!-- 
                <h3>代理商分销利润</h3>
                <dl>
                    <dd class="bgfff vertical-middle">
                        <span class="distribution-description"><a href="../wap/tmpl/seller/fenxiaospeak.html">分销说明</a> </span>
                        <div>
                        	<span class="inblock w30">代理商分销利润(%)</span>
                       		<span class="inblock width70">
                           		<input name="baifen" type="number" class="form-control" placeholder="%" value="<?php echo $output['goods']['baifen']; ?>"/>
                           	</span>
                            <b class="clear"></b>
                        </div>
                        <span></span>
                    </dd>
                </dl>
                <dl>
                    <h3>三级分销利润<?php echo $lang['nc_colon'] ?>
                        <?php if (C('fencheng_ms') == 2) { ?>
                            <input name="fencheng_ms" type="radio" 
                            	<?php echo empty($output['goods']['fencheng_ms']) ? 'checked' : '' ?>
                                   placeholder="元" value="0"/>金额模式(元)
                            <input name="fencheng_ms" type="radio" 
                            	<?php echo empty($output['goods']['fencheng_ms']) ? '' : 'checked'; ?>
                                   value="0" placeholder="%"/>比例模式(%)
                        <?php } else {
                            if (C('fencheng_ms') == 0) { ?>
                                <input type="hidden" name="fencheng_ms" value="0" placeholder="元"/>金额返佣(元)
                            <?php } else { ?>
                                <input type="hidden" name="fencheng_ms" value="1" placeholder="%"/>比例返佣(%)
                            <?php }
                        } ?>
                        <span></span>
                    </h3>
                </dl>
                <dl>
                    <dd class="bgfff vertical-middle">
                  		<span class="inblock w30">一级分销利润<?php echo $lang['nc_colon']; ?></span>
                       	<span class="inblock vertical-middle width70">  
                       		<input name="fencheng1" type="number" class="form-control" value="<?php echo $output['goods']['fencheng1']; ?>" placeholder="%"/>
                        </span>
                        <span></span>
                    </dd>
                </dl>
                <dl>
                    <dd class="bgfff vertical-middle">
                        <span class="inblock w30">二级分销利润<?php echo $lang['nc_colon']; ?></span>
                       	<span class="inblock vertical-middle width70">   
                       		<input name="fencheng2" type="number" class="form-control" value="<?php echo $output['goods']['fencheng2']; ?>" placeholder="%"/>
                        </span>
                        <span></span>
                    </dd>
                </dl>
                <dl>
                    <dd class="bgfff vertical-middle">
                    	<span class="inblock vertical-middle">三级分销利润<?php echo $lang['nc_colon']; ?></span>
                       	<span class="inblock vertical-middle width70">          
                       		<input name="fencheng3" type="number" class="form-control" value="<?php echo $output['goods']['fencheng3']; ?>" placeholder="%"/>
                        </span>
                        <span></span>
                    </dd>
                </dl>
                 -->
                <h3>商品详情描述</h3>
                <div class="goods-desc">
                    <i class="required">*</i>商品详情描述
                    <span class="right-icon"><img src="<?php echo MOBILE_SKINS_URL; ?>/images/right.png"/> </span>
                </div>
                <?php if (is_array($output['spec_list']) && !empty($output['spec_list'])) { ?>
                    <h3>商品规格</h3>
                    <?php $i = '0'; ?>
                    <?php foreach ($output['spec_list'] as $k => $val) { ?>
                        <dl nc_type="spec_group_dl_<?php echo $i; ?>" nctype="spec_group_dl" class="spec-bg"
                            <?php if ($k == '1'){ ?>spec_img="t"<?php } ?>>
                            <dd <?php if ($k == '1'){ ?>nctype="sp_group_val"<?php } ?>>
                                <input name="sp_name[<?php echo $k; ?>]" type="hidden" class="form-control" style="width: 70px;" title="自定义规格类型名称，规格值名称最多不超过4个字"
                                       value="<?php if (isset($output['goods']['spec_name'][$k])) {
                                           echo $output['goods']['spec_name'][$k];
                                       } else {
                                           echo $val['sp_name'];
                                       } ?>" maxlength="4" nctype="spec_name" 
                                       data-param="{id:<?php echo $k; ?>,name:'<?php echo $val['sp_name']; ?>'}"/>
                                <?php if (isset($output['goods']['spec_name'][$k])) {
                                    echo $output['goods']['spec_name'][$k];
                                } else {
                                    echo $val['sp_name'];
                                } ?><?php echo $lang['nc_colon'] ?>
                                <ul class="spec">
                                    <?php if (is_array($val['value'])) { ?>
                                        <?php foreach ($val['value'] as $v) { ?>
                                            <li style="width: 100%;">
	                                            <span nctype="input_checkbox">
	              								<input type="checkbox" value="<?php echo $v['sp_value_name']; ?>"
	                     						nc_type="<?php echo $v['sp_value_id']; ?>" <?php if ($k == '1'){ ?>class="sp_val"<?php } ?>
	                     						name="sp_val[<?php echo $k; ?>][<?php echo $v['sp_value_id'] ?>]">
	              								</span>
	              								<span nctype="pv_name"><?php echo $v['sp_value_name']; ?></span>
              								</li>
                                        <?php } ?>
                                    <?php } ?>
                                    <li data-param="{gc_id:<?php echo $output['goods_class']['gc_id']; ?>,sp_id:<?php echo $k; ?>,url:'<?php echo urlMobile('store_goods_add', 'ajax_add_spec'); ?>'}"
                                        style="width: 100%">
                                        <div nctype="specAdd1">
                                        	<a href="javascript:void(0);" class="ncbtn" nctype="specAdd"><i class="icon-plus"></i>添加规格值</a>
                                        </div>
                                        <div nctype="specAdd2" style="display:none;">
                                            <input class="text w60" type="text" placeholder="规格值名称" maxlength="40">
                                            <a href="javascript:void(0);" nctype="specAddSubmit" class="ncbtn ncbtn-aqua ml5 mr5">确认</a>
                                            <a href="javascript:void(0);" nctype="specAddCancel" class="ncbtn ncbtn-bittersweet">取消</a>
                                        </div>
                                    </li>
                                </ul>
                                <?php if ($output['edit_goods_sign'] && $k == '1') { ?>
<!--                                    <p class="hint">添加或取消颜色规格时，提交后请编辑图片以确保商品图片能够准确显示。</p>-->
                                <?php } ?>
                            </dd>
                        </dl>
                        <?php $i++; ?>
                    <?php } ?>
                <?php } else {?>
                	<div id="goods_spec_group" style="display:none;">
	                	
					</div>
                <?php } ?>
				<!-- 库存配置 -->
                <h3><?php echo $lang['srore_goods_index_goods_stock_set'] . $lang['nc_colon']; ?></h3>
                <dl nc_type="spec_dl" class="spec-bg" style="display:none;">
                    <dd class="spec-dd">
                        <div nctype="spec_div" class="spec-div" style="overflow-y: auto;overflow-x: auto;height:150px;">
                            <table border="0" cellpadding="0" cellspacing="0" class="spec_table">
                                <thead>
                                <?php if (is_array($output['spec_list']) && !empty($output['spec_list'])) { ?>
                                    <?php foreach ($output['spec_list'] as $k => $val) { ?>
                                        <th nctype="spec_name_<?php echo $k; ?>"><?php if (isset($output['goods']['spec_name'][$k])) {
                                                echo $output['goods']['spec_name'][$k];
                                            } else {
                                                echo $val['sp_name'];
                                            } ?></th>
                                    <?php } ?>
                                <?php } ?>
                                <th class="w90"><span class="red">*</span>吊牌价
                                    <div class="batch">
                                    	<i class="icon-edit" title="批量操作"></i>
                                        <div class="batch-input" style="display:none;">
                                            <h6>批量设置价格：</h6>
                                            <a href="javascript:void(0)" class="close">X</a>
                                            <input name="" type="text" class="form-control" placeholder="0.00"/>
                                            <a href="javascript:void(0)" class="ncbtn-mini" data-type="marketprice">设置</a>
                                            <span class="arrow"></span>
                                     	</div>
                                    </div>
                                </th>
                                <th class="w90">
                                	<span class="red">*</span><?php echo $lang['store_goods_index_price']; ?>
                                    <div class="batch">
                                    	<i class="icon-edit" title="批量操作"></i>
                                        <div class="batch-input" style="display:none;">
                                            <h6>批量设置价格：</h6>
                                            <a href="javascript:void(0)" class="close">X</a>
                                            <input name="" type="text" class="form-control"/>
                                            <a href="javascript:void(0)" class="ncbtn-mini" data-type="price">设置</a>
                                            <span class="arrow"></span>
                                        </div>
                                    </div>
                                </th>
                                <th class="w60">
                                	<span class="red">*</span><?php echo $lang['store_goods_index_stock']; ?>
                                    <div class="batch">
                                    	<i class="icon-edit" title="批量操作"></i>
                                        <div class="batch-input" style="display:none;">
                                            <h6>批量设置库存：</h6>
                                            <a href="javascript:void(0)" class="close">X</a>
                                            <input name="" type="text" class="form-control"/>
                                            <a href="javascript:void(0)" class="ncbtn-mini" data-type="stock">设置</a>
                                            <span class="arrow"></span>
                                       	</div>
                                    </div>
                                </th>
                                <th class="w100">
                                	<?php echo $lang['store_goods_index_goods_no']; ?>
                                	<div class="batch">
                                    	<i class="icon-edit" title="批量操作"></i>
                                        <div class="batch-input" style="display:none;">
                                            <h6>批量设置货号：</h6>
                                            <a href="javascript:void(0)" class="close">X</a>
                                            <input name="" type="text" class="form-control"/>
                                            <a href="javascript:void(0)" class="ncbtn-mini" data-type="sku">设置</a>
                                            <span class="arrow"></span>
                                    	</div>
                                    </div>
                                </th>
                                <th class="w100">商品条形码
                                	<div class="batch">
                                    	<i class="icon-edit" title="批量操作"></i>
                                        <div class="batch-input" style="display:none;">
                                            <h6>批量设置条形码：</h6>
                                            <a href="javascript:void(0)" class="close">X</a>
                                            <input name="" type="text" class="form-control"/>
                                            <a href="javascript:void(0)" class="ncbtn-mini" data-type="barcode">设置</a>
                                            <span class="arrow"></span>
                                    	</div>
                                    </div>
                                </th>
                                </thead>
                                <tbody nc_type="spec_table">
                                </tbody>
                            </table>
                        </div>
                    </dd>
                </dl>
                <dl>
                    <dd class="bgfff vertical-middle" nc_type="no_spec">
                    	<span class="inblock vertical-middle">
                    		<i class="required">*</i><?php echo $lang['store_goods_index_goods_stock'] . $lang['nc_colon']; ?>
                      	</span>
                       	<span class="inblock vertical-middle width70">
                        	<input name="g_storage"
                               value="<?php echo $output['goods']['g_storage'] > 0 ? $output['goods']['g_storage'] : 1; ?>"
                               type="text" class="form-control g_storage"/>
                        </span>
                        <span class="init"></span>
                    </dd>
                </dl>
                <!--             <dl>-->
                <!--                  <dd>-->
                <!--                    <i class="required">*</i>--><?php //echo $lang['store_goods_album_goods_pic'] . $lang['nc_colon']; ?>
                <!--                      <div class="ncsc-goods-default-pic">-->
                <!--                            <div class="goodspic-uplaod">-->
                <!--                                <div class="upload-thumb"><label for="goods_image"><img nctype="goods_image"-->
                <!--                                                               src="-->
                <?php //echo thumb($output['goods'], 240); ?><!--"/></label></div>-->
                <!--                                <input type="hidden" name="image_path" id="image_path" nctype="goods_image"-->
                <!--                                       value="-->
                <?php //echo $output['goods']['goods_image'] ?><!--"/>-->
                <!--                                <span></span>-->
                <!---->
                <!--                                <p class="hint">-->
                <?php //echo $lang['store_goods_step2_description_one']; ?><!---->
                <?php //printf($lang['store_goods_step2_description_two'], intval(C('image_max_filesize')) / 1024); ?><!--</p>-->
                <!---->
                <!--                                <div class="handle">-->
                <!--                                 <div class="ncsc-upload-btn"><a href="javascript:void(0);"><span>-->
                <!--                <input type="file" hidefocus="true" size="1" class="input-file" name="goods_image" id="goods_image" style="opacity:0;">-->
                <!--                  </span>-->
                <!---->
                <!--                                          <p style="opacity:0;"><i class="icon-upload-alt"></i>图片上传</p>-->
                <!---->
                <!--                                     </a></div>-->
                <!--                                    <a class="ncbtn mt5" style="display: none;" nctype="show_image"-->
                <!--                                       href="-->
                <?php //echo urlMobile('store_album', 'pic_list', array('item' => 'goods')); ?><!--"><i-->
                <!--                                            class="icon-picture"></i>从图片空间选择</a> <a href="javascript:void(0);"-->
                <!--                                                                                    nctype="del_goods_demo"-->
                <!--                                                                                    class="ncbtn mt5"-->
                <!--                                                                                    style="display: none;"><i-->
                <!--                                            class="icon-circle-arrow-up"></i>关闭相册</a></div>-->
                <!--                            </div>-->
                <!--                        </div>-->
                <!--                        <div id="demo"></div>-->
                <!--                    </dd>-->
                <!--                </dl>-->
                <!--                <h3 id="demo2">-->
                <?php //echo $lang['store_goods_index_goods_detail_info'] ?><!--</h3>-->
                <input type="hidden" name="image_path" id="image_path" nctype="goods_image" value="<?php echo $output['goods']['goods_image'] ?>"/>
                <dl style="overflow: visible;display: none;">
                    <dd><?php echo $lang['store_goods_index_goods_brand'] . $lang['nc_colon']; ?>
                        <div class="ncsc-brand-select">
                            <div class="selection">
                                <input name="b_name" id="b_name" value="<?php echo $output['goods']['brand_name']; ?>" type="text" class="text w180" readonly/>
                                <input type="hidden" name="b_id" id="b_id" value="<?php echo $output['goods']['brand_id']; ?>"/>
                                <em class="add-on"><i class="icon-collapse"></i></em>
                            </div>
                            <div class="ncsc-brand-select-container">
                                <div class="brand-index" data-tid="<?php echo $output['goods_class']['type_id']; ?>"
                                     data-url="<?php echo urlMobile('store_goods_add', 'ajax_get_brand'); ?>">
                                    <div class="letter" nctype="letter">
                                        <ul>
                                            <li><a href="javascript:void(0);" data-letter="all">全部品牌</a></li>
                                            <li><a href="javascript:void(0);" data-letter="A">A</a></li>
                                            <li><a href="javascript:void(0);" data-letter="B">B</a></li>
                                            <li><a href="javascript:void(0);" data-letter="C">C</a></li>
                                            <li><a href="javascript:void(0);" data-letter="D">D</a></li>
                                            <li><a href="javascript:void(0);" data-letter="E">E</a></li>
                                            <li><a href="javascript:void(0);" data-letter="F">F</a></li>
                                            <li><a href="javascript:void(0);" data-letter="G">G</a></li>
                                            <li><a href="javascript:void(0);" data-letter="H">H</a></li>
                                            <li><a href="javascript:void(0);" data-letter="I">I</a></li>
                                            <li><a href="javascript:void(0);" data-letter="J">J</a></li>
                                            <li><a href="javascript:void(0);" data-letter="K">K</a></li>
                                            <li><a href="javascript:void(0);" data-letter="L">L</a></li>
                                            <li><a href="javascript:void(0);" data-letter="M">M</a></li>
                                            <li><a href="javascript:void(0);" data-letter="N">N</a></li>
                                            <li><a href="javascript:void(0);" data-letter="O">O</a></li>
                                            <li><a href="javascript:void(0);" data-letter="P">P</a></li>
                                            <li><a href="javascript:void(0);" data-letter="Q">Q</a></li>
                                            <li><a href="javascript:void(0);" data-letter="R">R</a></li>
                                            <li><a href="javascript:void(0);" data-letter="S">S</a></li>
                                            <li><a href="javascript:void(0);" data-letter="T">T</a></li>
                                            <li><a href="javascript:void(0);" data-letter="U">U</a></li>
                                            <li><a href="javascript:void(0);" data-letter="V">V</a></li>
                                            <li><a href="javascript:void(0);" data-letter="W">W</a></li>
                                            <li><a href="javascript:void(0);" data-letter="X">X</a></li>
                                            <li><a href="javascript:void(0);" data-letter="Y">Y</a></li>
                                            <li><a href="javascript:void(0);" data-letter="Z">Z</a></li>
                                            <li><a href="javascript:void(0);" data-letter="0-9">其他</a></li>
                                        </ul>
                                    </div>
                                    <div class="search" nctype="search">
                                        <input name="search_brand_keyword" id="search_brand_keyword" type="text" class="text" placeholder="品牌名称关键字查找"/>
                                        <a href="javascript:void(0);"  class="ncbtn-mini" style="vertical-align: top;">Go</a>
                                    </div>
                                </div>
                                <div class="brand-list" nctype="brandList">
                                    <ul nctype="brand_list">
                                        <?php if (is_array($output['brand_list']) && !empty($output['brand_list'])) { ?>
                                            <?php foreach ($output['brand_list'] as $val) { ?>
                                                <li data-id='<?php echo $val['brand_id']; ?>'
                                                    data-name='<?php echo $val['brand_name']; ?>'>
                                                    <em><?php echo $val['brand_initial']; ?></em><?php echo $val['brand_name']; ?>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <div class="no-result" nctype="noBrandList" style="display: none;">
                                                                                                              没有符合"<strong>搜索关键字</strong>"条件的品牌
                                </div>
                                <div class="tc">
                                	<a href="javascript:void(0);" class="ncbtn-mini" onclick="$(this).parents('.ncsc-brand-select-container:first').hide();">关闭品牌列表</a>
                                </div>
                            </div>
                        </div>
                    </dd>
                </dl>
                <?php if (false && is_array($output['attr_list']) && !empty($output['attr_list'])) { ?>
                    <dl style="display: none;">
                        <dt><?php echo $lang['store_goods_index_goods_attr'] . $lang['nc_colon']; ?></dt>
                        <dd>
                            <?php foreach ($output['attr_list'] as $k => $val) { ?>
                                <span class="property">
          							<label class="mr5"><?php echo $val['attr_name'] ?></label>
          							<input type="hidden" name="attr[<?php echo $k; ?>][name]" value="<?php echo $val['attr_name'] ?>"/>
                                    <?php if (is_array($val) && !empty($val)) { ?>
                                        <select name="" attr="attr[<?php echo $k; ?>][__NC__]" nc_type="attr_select">
                                            <option value='不限' nc_type='0'>不限</option>
                                            <?php foreach ($val['value'] as $v) { ?>
                                                <option value="<?php echo $v['attr_value_name'] ?>"
                                                	<?php if (isset($output['attr_checked']) && in_array($v['attr_value_id'], $output['attr_checked'])){ ?>selected="selected"<?php } ?>
                                                    nc_type="<?php echo $v['attr_value_id']; ?>"><?php echo $v['attr_value_name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    <?php } ?>
          						</span>
                            <?php } ?>
                        </dd>
                    </dl>
                <?php } ?>
                <?php if (false && !empty($output['custom_list'])) { ?>
                    <dl style="display: none;">
                        <dt>自定义属性：</dt>
                        <dd>
                            <?php foreach ($output['custom_list'] as $val) { ?>
                                <span class="property">
            					<label class="mr5"><?php echo $val['custom_name']; ?></label>
            					<input type="hidden" name="custom[<?php echo $val['custom_id']; ?>][name]"	value="<?php echo $val['custom_name']; ?>"/>
            					<input class="text w60" type="text" name="custom[<?php echo $val['custom_id']; ?>][value]"
                   						value="<?php if ($output['goods']['goods_custom'][$val['custom_id']]['value'] != '') {
                       					echo $output['goods']['goods_custom'][$val['custom_id']]['value'];
                   						} ?>"/>
          						</span>
                            <?php } ?>
                        </dd>
                    </dl>
                <?php } ?>
                <!--                <input type="type" name="g_body" id="edithiddle" value="-->
                <?php //echo  $output['goods']['goods_body']; ?><!--">-->
                <textarea name="g_body" id="edithiddle"><?php echo $output['goods']['goods_body']; ?> </textarea>

                <!--                <dl>-->
                <!--                    <dd id="ncProductDetails">-->
                <!--                        --><?php ////echo $lang['store_goods_index_goods_desc'].$lang['nc_colon'];?>
                <!--                        <div class="tabs">-->
                <!--                            <div id="panel-1" class="ui-tabs-panel">-->
                <!--                                --><?php //showEditor('g_body', $output['goods']['goods_body'], '100%', '300px', 'visibility:hidden;', "false", $output['editor_multimedia'], 'basic'); ?>
                <!--                                <div class="hr8">-->
                <!--                                    <div class="ncsc-upload-btn"><a href="javascript:void(0);"><span>-->
                <!--                  <input type="file" hidefocus="true" size="1" class="input-file" name="add_album" id="add_album"-->
                <!--                         multiple>-->
                <!--                  </span>-->
                <!---->
                <!--                                            <p><i class="icon-upload-alt" data_type="0" nctype="add_album_i"></i>图片上传-->
                <!--                                            </p>-->
                <!--                                        </a></div>-->
                <!--                                    <a class="ncbtn mt5" nctype="show_desc"-->
                <!--                                       href="index.php?act=store_album&op=pic_list&item=des"><i-->
                <!--                                            class="icon-picture"></i>--><?php //echo $lang['store_goods_album_insert_users_photo']; ?>
                <!--                                    </a> <a href="javascript:void(0);" nctype="del_desc" class="ncbtn mt5"-->
                <!--                                            style="display: none;"><i class=" icon-circle-arrow-up"></i>关闭相册</a></div>-->
                <!--                                <p id="des_demo"></p>-->
                <!--                            </div>-->
                <!---->
                <!--                        </div>-->
                <!--                    </dd>-->
                <!--                </dl>-->

                <!-- 只有可发布虚拟商品才会显示 S -->
                <?php if (false && $output['goods_class']['gc_virtual'] == 1) { ?>
                    <h3 id="demo3">特殊商品</h3>
                    <dl class="special-01">
                        <dd>虚拟商品<?php echo $lang['nc_colon']; ?>
                            <?php if ($output['edit_goods_sign']) { ?>
                                <input type="hidden" name="is_gv" value="<?php echo $output['goods']['is_virtual']; ?>">
                            <?php } ?>
                            <ul class="ncsc-form-radio-list">
                                <li>
                                    <input type="radio" name="is_gv" value="1" id="is_gv_1"
                                           <?php if ($output['goods']['is_virtual'] == 1) { ?>checked<?php } ?>
                                           <?php if ($output['edit_goods_sign']) { ?>disabled<?php } ?>>
                                    <label for="is_gv_1">是</label>
                                </li>
                                <li>
                                    <input type="radio" name="is_gv" value="0" id="is_gv_0"
                                           <?php if ($output['goods']['is_virtual'] == 0) { ?>checked<?php } ?>
                                           <?php if ($output['edit_goods_sign']) { ?>disabled<?php } ?>>
                                    <label for="is_gv_0">否</label>
                                </li>
                            </ul>
<!--                            <p class="hint vital">*虚拟商品不能参加限时折扣和组合销售两种促销活动。也不能赠送赠品和推荐搭配。</p>-->
                        </dd>
                    </dl>
                    <dl class="special-01" nctype="virtual_valid"
                        <?php if ($output['goods']['is_virtual'] == 0) { ?>style="display:none;"<?php } ?>>
                        <dt><i class="required">*</i>虚拟商品有效期至<?php echo $lang['nc_colon']; ?></dt>
                        <dd>
                            <input type="text" name="g_vindate" id="g_vindate" class="w80 text"
                                   value="<?php if ($output['goods']['is_virtual'] == 1 && !empty($output['goods']['virtual_indate'])) {
                                       echo date('Y-m-d', $output['goods']['virtual_indate']);
                                   } ?>"><em class="add-on"><i class="icon-calendar"></i></em>
                            <span></span>

<!--                            <p class="hint">虚拟商品可兑换的有效期，过期后商品不能购买，电子兑换码不能使用。</p>-->
                        </dd>
                    </dl>
                    <dl class="special-01" nctype="virtual_valid"
                        <?php if ($output['goods']['is_virtual'] == 0) { ?>style="display:none;"<?php } ?>>
                        <dt><i class="required">*</i>虚拟商品购买上限<?php echo $lang['nc_colon']; ?></dt>
                        <dd>
                            <input type="text" name="g_vlimit" id="g_vlimit" class="w80 text"
                                   value="<?php if ($output['goods']['is_virtual'] == 1) {
                                       echo $output['goods']['virtual_limit'];
                                   } ?>">
                            <span></span>
<!--                            <p class="hint">请填写1~10之间的数字，虚拟商品最高购买数量不能超过10个。</p>-->
                        </dd>
                    </dl>
                    <dl class="special-01" nctype="virtual_valid"
                        <?php if ($output['goods']['is_virtual'] == 0) { ?>style="display:none;"<?php } ?>>
                        <dt>支持过期退款<?php echo $lang['nc_colon']; ?></dt>
                        <dd>
                            <ul class="ncsc-form-radio-list">
                                <li>
                                    <input type="radio" name="g_vinvalidrefund" id="g_vinvalidrefund_1" value="1"
                                           <?php if ($output['goods']['virtual_invalid_refund'] == 1) { ?>checked<?php } ?>>
                                    <label for="g_vinvalidrefund_1">是</label>
                                </li>
                                <li>
                                    <input type="radio" name="g_vinvalidrefund" id="g_vinvalidrefund_0" value="0"
                                           <?php if ($output['goods']['virtual_invalid_refund'] == 0) { ?>checked<?php } ?>>
                                    <label for="g_vinvalidrefund_0">否</label>
                                </li>
                            </ul>
<!--                            <p class="hint">兑换码过期后是否可以申请退款。</p>-->
                        </dd>
                    </dl>
                <?php } ?>
                <!-- 只有可发布虚拟商品才会显示 E -->
                <!-- 商品物流信息 S -->
                <h3 id="demo4">运费信息</h3>
                <dl style="display: none;">
                    <dd>

                        <?php echo $lang['store_goods_index_goods_szd'] . $lang['nc_colon'] ?>
                        <input type="hidden"
                               value="<?php echo $output['goods']['areaid_2'] ? $output['goods']['areaid_2'] : $output['goods']['areaid_1']; ?>"
                               name="region" id="region">
                        <input type="hidden" value="<?php echo $output['goods']['areaid_1']; ?>" name="province_id"
                               id="_area_1">
                        <input type="hidden" value="<?php echo $output['goods']['areaid_2']; ?>" name="city_id"
                               id="_area_2">
                        </p>
                    </dd>
                </dl>
                <dl nctype="virtual_null"
                    <?php if ($output['goods']['is_virtual'] == 1) { ?>style="display:none;"<?php } ?>>
                    <dd>
                        <?php //echo $lang['store_goods_index_goods_transfee_charge'].$lang['nc_colon']; ?>
                        <ul class="ncsc-form-radio-list">
                            <li style="width:100%;margin:0;">
                                <input id="freight_0" nctype="freight" name="freight" class="radio"
                                       style="display: none;" type="radio"
                                       <?php if (intval($output['goods']['transport_id']) == 0) { ?>checked="checked"<?php } ?>
                                       value="0">
                                <label for="freight_0" style="">固定运费</label>

                                <div nctype="div_freight"
                                     <?php if (intval($output['goods']['transport_id']) != 0) { ?>style="display: none;"<?php } ?> style="float: right;text-align: right;">
                                    <input id="g_freight" class="form-control" nc_type='transport' type="text"
                                           value="<?php if ($output['edit_goods_sign']) { printf('%.2f', floatval($output['goods']['goods_freight'])); }?>"
                                           name="g_freight" placeholder="0.00"></div>
                                <b class="clear"></b>
                            </li>
                            <li style="display: none;">
                                <input id="freight_1" nctype="freight" name="freight" class="radio" type="radio"
                                       <?php if (intval($output['goods']['transport_id']) != 0) { ?>checked="checked"<?php } ?>
                                       value="1">
                                <label for="freight_1"><?php echo $lang['store_goods_index_use_tpl']; ?></label>

                                <div nctype="div_freight"
                                     <?php if (intval($output['goods']['transport_id']) == 0) { ?>style="display: none;"<?php } ?>>
                                    <input id="transport_id" type="hidden"
                                           value="<?php echo $output['goods']['transport_id']; ?>" name="transport_id">
                                    <input id="transport_title" type="hidden"

                                           value="<?php echo $output['goods']['transport_title']; ?>"
                                           name="transport_title">
                                    <label for="freight_0" style="">固定运费</label>
                                    <span id="postageName" class="transport-name"
                                          <?php if ($output['goods']['transport_title'] != '' && intval($output['goods']['transport_id'])) { ?>style="display: inline-block;"<?php } ?>><?php echo $output['goods']['transport_title']; ?></span><a
                                        href="JavaScript:void(0);"
                                        onclick="window.open('index.php?act=store_transport&type=select')" class="ncbtn"
                                        id="postageButton"><i
                                            class="icon-truck"></i><?php echo $lang['store_goods_index_select_tpl']; ?>
                                    </a></div>
                            </li>
                        </ul>
<!--                        <p class="hint">运费设置为 0 元，前台商品将显示为免运费。</p>-->
                    </dd>
                </dl>
                <!-- 商品物流信息 E -->
                <h3 id="demo6">  <?php echo $lang['store_goods_index_store_goods_class'] . $lang['nc_colon']; ?></h3>
                <dl class="storeClassification-collect">
                    <dd>
                        <?php echo $lang['store_goods_index_store_goods_class'] . $lang['nc_colon']; ?>

                        <span class="right-icon"><img
                                src="<?php echo MOBILE_SKINS_URL; ?>/images/right.png"> </span>
                        <span id="storeClassification-text">
                        </span>
                        <b class="clear"></b>

                    </dd>
                </dl>
                <h3><?php echo $lang['store_goods_index_goods_show'] . $lang['nc_colon']; ?></h3>
                <dl>
                    <dd>

                        <ul class="ncsc-form-radio-list">
                            <li>
                                <label>

                                    <input name="g_state" value="1" type="radio"
                                           <?php if (empty($output['goods']) || $output['goods']['goods_state'] == 1 || $output['goods']['goods_state'] == 10) { ?>checked="checked"<?php } ?> />
                                    <?php echo $lang['store_goods_index_immediately_sales']; ?> </label>
                            </li>
                            <li style="display: none;">
                                <label>
                                    <input name="g_state" value="0" type="radio" nctype="auto"/>
                                    <?php echo $lang['store_goods_step2_start_time']; ?> </label>
                                <input type="text" class="w80 text" name="starttime" disabled="disabled"
                                       style="background:#E7E7E7 none;" id="starttime"
                                       value="<?php echo date('Y-m-d'); ?>"/>
                                <select disabled="disabled" style="background:#E7E7E7 none;" name="starttime_H"
                                        id="starttime_H">
                                    <?php foreach ($output['hour_array'] as $val) { ?>
                                        <option value="<?php echo $val; ?>" <?php $sign_H = 0;
                                        if ($val >= date('H') && $sign_H != 1){ ?>selected="selected"<?php $sign_H = 1;
                                        } ?>><?php echo $val; ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo $lang['store_goods_step2_hour']; ?>
                                <select disabled="disabled" style="background:#E7E7E7 none;" name="starttime_i"
                                        id="starttime_i">
                                    <?php foreach ($output['minute_array'] as $val) { ?>
                                        <option value="<?php echo $val; ?>" <?php $sign_i = 0;
                                        if ($val >= date('i') && $sign_i != 1){ ?>selected="selected"<?php $sign_i = 1;
                                        } ?>><?php echo $val; ?></option>
                                    <?php } ?>
                                </select>
                                <?php echo $lang['store_goods_step2_minute']; ?> </li>
                            <li>
                                <label>
                                    <input name="g_state" value="0" type="radio"
                                           <?php if (!empty($output['goods']) && $output['goods']['goods_state'] == 0) { ?>checked="checked"<?php } ?> />
                                    <?php echo $lang['store_goods_index_in_warehouse']; ?> </label>
                            </li>
                        </ul>
                    </dd>
                </dl>
                <h3><?php echo $lang['store_goods_index_goods_recommend'] . $lang['nc_colon']; ?></h3>
                <dl>
                    <dd>
                        <ul class="ncsc-form-radio-list">
                            <li>
                                <label>
                                    <input name="g_commend" value="1"
                                           <?php if (empty($output['goods']) || $output['goods']['goods_commend'] == 1) { ?>checked="checked" <?php } ?>
                                           type="radio"/>是</label>
                            </li>
                            <li>
                                <label>
                                    <input name="g_commend" value="0"
                                           <?php if (!empty($output['goods']) && $output['goods']['goods_commend'] == 0) { ?>checked="checked" <?php } ?>
                                           type="radio"/>否</label>
                            </li>
                        </ul>
                        <p class="hint"><?php echo $lang['store_goods_index_recommend_tip']; ?></p>
                    </dd>
                </dl>
                <?php if (is_array($output['supplier_list'])) { ?>
                    <h3> 供货商：</h3>
                    <dl>
                        <dd>

                            <select name="sup_id">
                                <option value="0"><?php echo $lang['nc_please_choose']; ?></option>
                                <?php foreach ($output['supplier_list'] as $val) { ?>
                                    <option value="<?php echo $val['sup_id']; ?>"
                                            <?php if ($output['goods']['sup_id'] == $val['sup_id']) { ?>selected<?php } ?>><?php echo $val['sup_name'] ?></option>
                                <?php } ?>
                            </select>

<!--                            <p class="hint">可以选择商品的供货商。</p>-->
                        </dd>
                    </dl>
                <?php } ?>
            </div>
            <span class="storeClass_edit">
            <span class="storeClass_fid"><?php if ($output['edit_goods_sign']){echo $output['store_class_goods'][0];}?></span>
            <span class="storeClass_pid"><?php if ($output['edit_goods_sign']){echo $output['store_class_goods'][1];}?></span>
                </span>
            <input type="hidden" name="sgcate_id[]" class="storeClassification-hidden" value="<?php if ($output['edit_goods_sign']){
               echo $output['store_class_goods'][1];}?>">
            <input type="hidden" name="cate_id" id="gcategory_hidden" value="<?php if ($output['edit_goods_sign']) {
                echo $output['goods_class']['gc_id'];
            }; ?>"/>
            <div class="bottom tc hr32" style="text-align:center;margin:20px auto;">
                <label class="submit-border">
                    <input type="submit" class="submit" value="<?php if ($output['edit_goods_sign']) {
                        echo '提交';
                    } else { ?><?php echo $lang['store_goods_add_next']; ?>，上传商品图片<?php } ?>"/>
                </label>
            </div>
        </form>
    </div>
</div>


<!--输入表单-->
<!--本店分类-->
<div class="storeClassification">
    <div class="warp">
        <div class="header navbar-fixed-top">
            <div class="storeClassification-return fl" onclick="storeback();">
                <a href="javascript:void(0);"><img width="15" height="25"
                                                   src="<?php echo MOBILE_SKINS_URL; ?>/images/return_img.jpg"/></a>
            </div>
            <div class="header_title hh">选择店铺分类</div>
<!--            <div class="yulan">-->
<!--            </div>-->
            <div class="clear"></div>
        </div>
    </div>
    <div class="storeClassification-box">
        <div class="wrapper_search">
            <div class="wp_sort">
                <div class="store_list1">
                    <div class="sort_list ">
                        <div class="wp_category_list">
                            <div id="class_div_1" class="category_list">
                                <ul id="first_storeClassification">

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="store_list2">
                    <div class="sort_list ">
                        <div class="wp_category_list">
                            <div id="class_div_1" class="category_list">
                                <ul id="second_storeClassification">

                                </ul>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>


        <!--商品详情描述-->
        <div class="editor-box">
            <div id="edit-text" style="">
                <p><?php echo $output['goods']['goods_body']; ?></p>
            </div>
            <div class="btn-box">
               	 完成
            </div>
        </div>
        <!--商品分类选择-->
        <div class="gcategory_box">
            <link href="<?php echo WAP_SITE_URL; ?>/css/store/promotion.css" rel="stylesheet" type="text/css">
            <style>
                .header_title {
                    width: 90%;
                }
            </style>
            <div class="warp">
                <div class="header navbar-fixed-top">
                    <div class="return fl" onclick="gcategory_hide()">
                        <a href="javascript:void(0);"><img width="15" height="25"
                                                           src="<?php echo MOBILE_SKINS_URL; ?>/images/return_img.jpg"/></a>
                    </div>
                    <div class="header_title hh">选择商品分类</div>
                    <div class="yulan">

                    </div>
                    <div class="clear"></div>
                </div>
                <div class="wrapper_search">
                    <div class="wp_sort">
                        <div id="dataLoading" class="wp_data_loading">
                            <div class="data_loading"><?php echo $lang['store_goods_step1_loading']; ?></div>
                        </div>
                		<div id="class_div" class="wp_sort_block">
		                    <div class="sort_list1" >
		                        <div class="sort_list ">
		                            <div class="wp_category_list">
		                                <div id="class_div_1" class="category_list"  style="overflow:scroll;overflow-x:hidden">
		                                    <ul id="first_gcategory">
		                                    </ul>
		                                </div>
		                            </div>
		                        </div>
		                    </div>

		                    <div class="sort_list2">
		                        <div class="sort_list ">
		                            <div class="wp_category_list blank">
		                                <div id="class_div_2" class="category_list">
		                                    <ul>
		                                    </ul>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                    <div class="sort_list3">
		                        <div class="sort_list sort_list_last sort_list3">
		                            <div class="wp_category_list blank">
		                                <div id="class_div_3" class="category_list">
		                                    <ul>
		                                    </ul>
<!--                                    	<div class="wp_confirm">-->
<!--                                        	<div class="bottom tc" style="text-align: center;">-->
<!--                                            	<label class="submit-border"><input nctype="buttonNextStep" value="确定选择" type="button"-->
<!--                                                                                class="submit" style=" width: 200px;"/></label>-->
<!--                                        	</div>-->
<!--                                    	</div>-->
		                                </div>
		                            </div>
		                        </div>
		                    </div>
                		</div>
                		<b class="clear"></b>
                    </div>
                </div>
            </div>
            <script src="<?php echo MOBILE_SKINS_URL; ?>/js/store_goods_add.step1.js"></script>
            <script>
                SEARCHKEY = '<?php echo $lang['store_goods_step1_search_input_text'];?>';
                RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';
            </script>
        </div>
        <!---->
        <!---->

        <script type="text/javascript">
            var SITEURL = "<?php echo MOBILE_SITE_URL; ?>";
            var DEFAULT_GOODS_IMAGE = "<?php echo thumb(array(), 60);?>";
            var SHOP_RESOURCE_SITE_URL = "<?php echo SHOP_RESOURCE_SITE_URL;?>";

            $(function () {
                inputkey();
                function inputkey(){
                    $('.goods-input').focus(function () {
                        $('.goods-input').parent().next('span.init').html('');
                        $(this).parent().css('border','none');
                    })
                    $('.g_price').focus(function () {
                        $('.g_price').parent().next('span.init').html('');
                        $(this).parent().parent().css('border','none');
                    })
                    $('.g_marketprice').focus(function () {
                        $('.g_marketprice').parent().next('span.init').html('');
                        $(this).parent().parent().css('border','none');
                    })
                    $('.g_storage').focus(function () {
                            $('.g_storage').parent().next('span.init').html('');
                            $(this).attr('placeholder','0.00').parent().parent().css('border','none');
                        })
                    $('#gcategory').click(function () {
                        $(this).find('span.init').html('');
                        $(this).css('border','none');
                    })
                    $('.goods-img').click(function () {
                        $(this).css('border','none');
                    })
                    $('#save_img-btn').click(function(){
                        $('.goods-img').css('border','none');
                    })

                }
				$('form').submit(function () {
				    var check=checkinput();
				 	if(check==1){
				     return false;
				 	}
				    return true;
				})

                function checkinput() {
                    var goods_length=$('.goods-input').val().length;
                   	var g_price_val=$('.g_price').val();
                    var g_marketprice_val=$('.g_marketprice').val();
                    if($('#image_path').val()==''){
                        $('.goods-img').css('border','1px solid #ff0000');
                        var top= $('.goods-img').offset().top-50;
                        $(window).scrollTop(top);
                        return 1;
                    }
                    if($('#gcategory_hidden').val()==''){
                        $('.no_edit_sign').parent().css('border','1px solid #ff0000');
                        $('.no_edit_sign').next('span.init').html('你还未选择商品分类');
                        var top= $('.no_edit_sign').parent().offset().top-50;
                        $(window).scrollTop(top);
                        return 1;
                    }
                    if(goods_length<5||goods_length>60){
                        $('.goods-input').parent().css('border','1px solid #ff0000');
                        $('.goods-input').parent().next('span.init').html('你输入的字数少于5个或大于60个');
                        var top=$('.goods-input').parent().offset().top-50;
                        $(window).scrollTop(top);
                        return 1;
                    }
                    if(g_price_val<=0||g_price_val==''){
                        $('.g_price').parent().parent().css('border','1px solid #ff0000');
                        $('.g_price').parent().next('span.init').html('你输入的价格少于0或为空');
                        var top= $('.g_price').parent().offset().top-50;
                        $(window).scrollTop(top);
                        return 1;
                    }
                    if(g_marketprice_val<=0||g_marketprice_val==''){
                        $('.g_marketprice').attr('placeholder','你输入的吊牌价有误，不能为空').parent().parent().css('border','1px solid #ff0000');
                        $('.g_marketprice').parent().next('span.init').html('你输入的吊牌价有误，不能为空');
                        var top=  $('.g_marketprice').parent().offset().top-50;
                        $(window).scrollTop(top);
                        return 1;
                    }
                    if(g_price_val>g_marketprice_val){
                        $('.g_price').parent().parent().css('border','1px solid #ff0000');
                        $('.g_marketprice').parent().parent().css('border','1px solid #ff0000');
                        $('.g_price').parent().next('span.init').html('你输入的价格大于吊牌价'.g_price_val.'---'.g_marketprice_val);
                        var top= $('.g_price').parent().offset().top-50;
                        $(window).scrollTop(top);
                        return 1;
                    }
                    if($('#edithiddle').val().length<1){

                        var top=   $('.goods-desc').parent().offset().top-50;
                        $(window).scrollTop(top);
                        return 1;
                    }
                    if($('.g_storage').val()<1||$('.g_storage').val()==''){
                        $('.g_storage').parent().parent().css('border','1px solid #ff0000');
                        $('.g_storage').parent().next('span.init').html('你输入的库存少于1');
                                         var top= $('.g_storage').parent().offset().top-50;
                                         $(window).scrollTop(top);
                        return 1;
                    }

                }
				
                <?php if (isset($output['goods'])) {?>
                setTimeout("setArea(<?php echo $output['goods']['areaid_1'];?>, <?php echo $output['goods']['areaid_2'];?>)", 1000);
                <?php }?>
            });
            // 按规格存储规格值数据
            var spec_group_checked = [<?php for ($i = 0; $i < $output['sign_i']; $i++) {
                if ($i + 1 == $output['sign_i']) {
                    echo "''";
                } else {
                    echo "'',";
                }
            }?>];
            var str = '';
            var V = new Array();

            <?php for ($i = 0; $i < $output['sign_i']; $i++){?>
            var spec_group_checked_<?php echo $i;?> = new Array();
            <?php }?>

            $(function () {
                $('dl[nctype="spec_group_dl"]').on('click', 'span[nctype="input_checkbox"] > input[type="checkbox"]', function () {
                    into_array();
                    goods_stock_set();
                });

                // 提交后不没有填写的价格或库存的库存配置设为默认价格和0
                // 库存配置隐藏式 里面的input加上disable属性
                $('input[type="submit"]').click(function () {
                    $('input[data_type="price"]').each(function () {
                        if ($(this).val() == '') {
                            $(this).val($('input[name="g_price"]').val());
                        }
                    });
                    $('input[data_type="sku"]').each(function () {
                        if ($(this).val() == '') {
                            $(this).val($('input[name="g_serial"]').val());
                        }
                    });
                    $('input[data_type="stock"]').each(function () {
                        if ($(this).val() == '') {
                            $(this).val('0');
                        }
                    });
                    $('input[data_type="alarm"]').each(function () {
                        if ($(this).val() == '') {
                            $(this).val('0');
                        }
                    });
                    if ($('dl[nc_type="spec_dl"]').css('display') == 'none') {
                        $('dl[nc_type="spec_dl"]').find('input').attr('disabled', 'disabled');
                    }
                });

            });

            // 将选中的规格放入数组
            function into_array() {
            	var sign_i = $('input[name="sign_i"]').val();
                
                	<?php for ($i = 0; $i < $output['sign_i']; $i++){?>
                    spec_group_checked_<?php echo $i;?> = new Array();
                    $('dl[nc_type="spec_group_dl_<?php echo $i;?>"]').find('input[type="checkbox"]:checked').each(function () {
                        i = $(this).attr('nc_type');
                        v = $(this).val();
                        c = null;
                        if ($(this).parents('dl:first').attr('spec_img') == 't') {
                            c = 1;
                        }
                        spec_group_checked_<?php echo $i;?>[spec_group_checked_<?php echo $i;?>.length] = [v, i, c];
                    });
                    spec_group_checked[<?php echo $i;?>] = spec_group_checked_<?php echo $i;?>;
                    <?php }?>
                
            }

            // 生成库存配置
            function goods_stock_set() {
                //  店铺价格 商品库存改为只读
                $('input[name="g_price"]').attr('readonly', 'readonly').css('background', '#E7E7E7 none');
                $('input[name="g_storage"]').attr('readonly', 'readonly').css('background', '#E7E7E7 none');

                $('dl[nc_type="spec_dl"]').show();
                str = '<tr>';
                <?php recursionSpec(0, $output['sign_i']);?>                
                
                if (str == '<tr>') {
                    //  店铺价格 商品库存取消只读
                    $('input[name="g_price"]').removeAttr('readonly').css('background', '');
                    $('input[name="g_storage"]').removeAttr('readonly').css('background', '');
                    $('dl[nc_type="spec_dl"]').hide();
                } else {
                    $('tbody[nc_type="spec_table"]').empty().html(str)
                        .find('input[nc_type]').each(function () {
                        s = $(this).attr('nc_type');
                        try {
                            $(this).val(V[s]);
                        } catch (ex) {
                            $(this).val('');
                        }
                        ;
                        if ($(this).attr('data_type') == 'marketprice' && $(this).val() == '') {
                            $(this).val($('input[name="g_marketprice"]').val());
                        }
                        if ($(this).attr('data_type') == 'price' && $(this).val() == '') {
                            $(this).val($('input[name="g_price"]').val());
                        }
                        if ($(this).attr('data_type') == 'sku' && $(this).val() == '') {
                            $(this).val($('input[name="g_serial"]').val());
                        }
                        if ($(this).attr('data_type') == 'stock' && $(this).val() == '') {
                            $(this).val('0');
                        }
                        if ($(this).attr('data_type') == 'alarm' && $(this).val() == '') {
                            $(this).val('0');
                        }
                    }).end()
                        .find('input[data_type="stock"]').change(function () {
                        computeStock();    // 库存计算
                    }).end()
                        .find('input[data_type="price"]').change(function () {
                        computePrice();     // 价格计算
                    }).end()
                        .find('input[nc_type]').change(function () {
                        s = $(this).attr('nc_type');
                        V[s] = $(this).val();
                    });
                }
                $('div[nctype="spec_div"]').perfectScrollbar('update');
            }

            <?php
            /**
             *
             *
             *  生成需要的js循环。递归调用    PHP
             *
             *  形式参考 （ 2个规格）
             *  $('input[type="checkbox"]').click(function(){
             *      str = '';
             *      for (var i=0; i<spec_group_checked[0].length; i++ ){
             *      td_1 = spec_group_checked[0][i];
             *          for (var j=0; j<spec_group_checked[1].length; j++){
             *              td_2 = spec_group_checked[1][j];
             *              str += '<tr><td>'+td_1[0]+'</td><td>'+td_2[0]+'</td><td><input type="text" /></td><td><input type="text" /></td><td><input type="text" /></td>';
             *          }
             *      }
             *      $('table[class="spec_table"] > tbody').empty().html(str);
             *  });
             */
            function recursionSpec($len, $sign)
            {
                if ($len < $sign) {
                    echo "for (var i_" . $len . "=0; i_" . $len . "<spec_group_checked[" . $len . "].length; i_" . $len . "++){td_" . (intval($len) + 1) . " = spec_group_checked[" . $len . "][i_" . $len . "];\n";
                    $len++;
                    recursionSpec($len, $sign);
                } else {
                    echo "var tmp_spec_td = new Array();\n";
                    for ($i = 0; $i < $len; $i++) {
                        echo "tmp_spec_td[" . ($i) . "] = td_" . ($i + 1) . "[1];\n";
                    }
                    echo "tmp_spec_td.sort(function(a,b){return a-b});\n";
                    echo "var spec_bunch = 'i_';\n";
                    for ($i = 0; $i < $len; $i++) {
                        echo "spec_bunch += tmp_spec_td[" . ($i) . "];\n";
                    }
                    echo "str += '<input type=\"hidden\" name=\"spec['+spec_bunch+'][goods_id]\" nc_type=\"'+spec_bunch+'|id\" value=\"\" />';";
                    for ($i = 0; $i < $len; $i++) {
                        echo "if (td_" . ($i + 1) . "[2] != null) { str += '<input type=\"hidden\" name=\"spec['+spec_bunch+'][color]\" value=\"'+td_" . ($i + 1) . "[1]+'\" />';}";
                        echo "str +='<td><input type=\"hidden\" name=\"spec['+spec_bunch+'][sp_value]['+td_" . ($i + 1) . "[1]+']\" value=\"'+td_" . ($i + 1) . "[0]+'\" />'+td_" . ($i + 1) . "[0]+'</td>';\n";
                    }
                    echo "str +='<td><input class=\"text price\" type=\"text\" name=\"spec['+spec_bunch+'][marketprice]\" data_type=\"marketprice\" nc_type=\"'+spec_bunch+'|marketprice\" value=\"\" /><em class=\"add-on\"><i class=\"icon-renminbi\"></i></em></td>' +
                        '<td><input class=\"text price\" type=\"text\" name=\"spec['+spec_bunch+'][price]\" data_type=\"price\" nc_type=\"'+spec_bunch+'|price\" value=\"\" /><em class=\"add-on\"><i class=\"icon-renminbi\"></i></em></td>' +
                        '<td><input class=\"text stock\" type=\"text\" name=\"spec['+spec_bunch+'][stock]\" data_type=\"stock\" nc_type=\"'+spec_bunch+'|stock\" value=\"\" /></td>' +
                        '<td><input class=\"text stock\" type=\"text\" name=\"spec['+spec_bunch+'][alarm]\" data_type=\"alarm\" nc_type=\"'+spec_bunch+'|alarm\" value=\"\" /></td>' +
                        '<td><input class=\"text sku\" type=\"text\" name=\"spec['+spec_bunch+'][sku]\" nc_type=\"'+spec_bunch+'|sku\" value=\"\" /></td>' +
                        '<td><input class=\"text barcode\" type=\"text\" name=\"spec['+spec_bunch+'][barcode]\" nc_type=\"'+spec_bunch+'|barcode\" value=\"\" /></td>' +
                        '</tr>';\n";
                    for ($i = 0; $i < $len; $i++) {
                        echo "}\n";
                    }
                }
            }

            ?>

            <?php if (!empty($output['goods']) && $_GET['class_id'] <= 0 && !empty($output['sp_value']) && !empty($output['spec_checked']) && !empty($output['spec_list'])){?>
            //  编辑商品时处理JS
            $(function () {
                var E_SP = new Array();
                var E_SPV = new Array();
                <?php
                $string = '';
                foreach ($output['spec_checked'] as $v) {
                    $string .= "E_SP[" . $v['id'] . "] = '" . $v['name'] . "';";
                }
                echo $string;
                echo "\n";
                $string = '';
                foreach ($output['sp_value'] as $k => $v) {
                    $string .= "E_SPV['{$k}'] = '{$v}';";
                }
                echo $string;
                ?>
                V = E_SPV;
                $('dl[nc_type="spec_dl"]').show();
                $('dl[nctype="spec_group_dl"]').find('input[type="checkbox"]').each(function () {
                    //  店铺价格 商品库存改为只读
                    $('input[name="g_price"]').attr('readonly', 'readonly').css('background', '#E7E7E7 none');
                    $('input[name="g_storage"]').attr('readonly', 'readonly').css('background', '#E7E7E7 none');
                    s = $(this).attr('nc_type');
                    if (!(typeof(E_SP[s]) == 'undefined')) {
                        $(this).attr('checked', true);
                        v = $(this).parents('li').find('span[nctype="pv_name"]');
                        if (E_SP[s] != '') {
                            $(this).val(E_SP[s]);
                            v.html('<input type="text" maxlength="20" value="' + E_SP[s] + '" />');
                        } else {
                            v.html('<input type="text" maxlength="20" value="' + v.html() + '" />');
                        }
                        change_img_name($(this));			// 修改相关的颜色名称
                    }
                });

                into_array();	// 将选中的规格放入数组
                str = '<tr>';
                <?php recursionSpec(0, $output['sign_i']);?>
                if (str == '<tr>') {
                    $('dl[nc_type="spec_dl"]').hide();
                    $('input[name="g_price"]').removeAttr('readonly').css('background', '');
                    $('input[name="g_storage"]').removeAttr('readonly').css('background', '');
                } else {
                    $('tbody[nc_type="spec_table"]').empty().html(str)
                        .find('input[nc_type]').each(function () {
                        s = $(this).attr('nc_type');
                        try {
                            $(this).val(E_SPV[s]);
                        } catch (ex) {
                            $(this).val('');
                        }
                        ;
                    }).end()
                        .find('input[data_type="stock"]').change(function () {
                        computeStock();    // 库存计算
                    }).end()
                        .find('input[data_type="price"]').change(function () {
                        computePrice();     // 价格计算
                    }).end()
                        .find('input[type="text"]').change(function () {
                        s = $(this).attr('nc_type');
                        V[s] = $(this).val();
                    });
                }
                $('div[nctype="spec_div"]').perfectScrollbar('update');
            });
            <?php }?>
        </script>
        <script src="<?php echo SHOP_RESOURCE_SITE_URL; ?>/js/scrolld.js"></script>
        <script type="text/javascript">$("[id*='Btn']").stop(true).on('click', function (e) {
                e.preventDefault();
                $(this).scrolld();
            })</script>
        <!--<script src="--><?php //echo LOGIN_TEMPLATES_URL; ?><!--/js/photoclip/js/jquery-2.1.3.min.js"></script>-->
        <script src="<?php echo MOBILE_SKINS_URL; ?>/js/photoclip/js/hammer.js"></script>
        <script src="<?php echo MOBILE_SKINS_URL; ?>/js/photoclip/js/iscroll-zoom.js"></script>
        <script src="<?php echo MOBILE_SKINS_URL; ?>/js/photoclip/js/lrz.all.bundle.js"></script>
        <script src="<?php echo MOBILE_SKINS_URL; ?>/js/photoclip/js/jquery.photoClip.min.js"></script>
        <script src="<?php echo MOBILE_SKINS_URL; ?>/css/dist/js/wangEditor.min.js"></script>
        <!--<script src="--><?php //echo MOBILE_SKINS_URL; ?><!--/css/dist2/js/lib/zepto.js"></script>-->
        <!--<script src="--><?php //echo MOBILE_SKINS_URL; ?><!--/css/dist2/js/lib/zepto.touch.js"></script>-->
        <script src="<?php echo MOBILE_SKINS_URL; ?>/js/goods-add.js"></script>
        <script type="text/javascript">
            var editor = new wangEditor('edit-text');
            wangEditor.config.printLog = false;
            editor.config.pasteText = true;
            editor.config.menus = [
                'bold',
                'eraser',
                'forecolor',
                'img',
                'fontfamily',
                'fontsize',
                'alignleft',
                'aligncenter',
                'alignright',
                'link',
                'unlink',
                'lineheight',
                'indent',
                'fullscreen'
            ];
            editor.config.uploadImgFileName = 'add_album';
            editor.config.uploadHeaders = {
                'Accept': 'application/json',
            };
            editor.config.uploadImgUrl = SITEURL + '/index.php?act=store_goods_add&op=image_upload';
            editor.config.uploadParams = {
                name: 'add_album'
            };

            editor.config.uploadImgFns.onload = function (resultText, xhr) {
                var originalName = editor.uploadImgOriginalName || '';
                var src = eval('(' + resultText + ')');
                editor.command(null, 'insertHtml', '<img src="' + src.thumb_name + '" style="max-width:100%;"/>');
            };
            var _height = $(window).height();
            $('#edit-text').height(_height - 33);
            editor.create();
            $('.btn-box').click(function () {
                $('#edithiddle').val(editor.$txt.html());
                $('.editor-box').animate({'right': '-150%'}, 500);
                $('html,body').css({'height': 'auto', 'overflow': 'visible'});
            })
        </script>


        <script>
            $.ajax({
                type: "get",
                url: '<?php echo MOBILE_SITE_URL ?>/index.php?act=member_index&op=store_goods_class',
                data: {key: getCookie('key')},
                dataType: "json",
                success: function (r) {
                    $('.sele-box').empty();
                    var fid = $('.storeClass_edit').find('.storeClass_fid').text();
                    var pid = $('.storeClass_edit').find('.storeClass_pid').text();
                    var datas = r.datas.store_goods_class;
                   
                    $.map(datas, function (f) {
                        if(f){
                        if (f.id == fid) {
                            var storeftext = f.name;
                            $('#storeClassification-text').html(storeftext);
                            if(f.child){
                                $.map(f.child, function (p) {
                                    if (p.id == pid) {
                                        var storeptext = p.name;
                                        $('#storeClassification-text').html(storeftext+'>'+storeptext);
                                    }
                                })
                            }
                        }
                        }
                    })
                    if (datas.length <= 0) {
                        var list = '<li>你的店铺还未没添加分类<p class="hint"><?php echo $lang['store_goods_index_belong_multiple_store_class']; ?></li>';
                        $('#first_storeClassification').append(list);
                    }
                    $.map(datas, function (h) {
                        if (h.child.length > 0) {
                            var child = 1;
                        } else {
                            var child = 2;
                        }
                        var list = '<li data-id="' + h.id + '" class="" data-child="' + child + '">' + h.name + '</li>';
                        $('#first_storeClassification').append(list);
                    })

                    $('#first_storeClassification').find('li').each(function (index) {
                        $(this).click(function () {
                            var sel = "#first_storeClassification";
                            addactive(sel, index)
                        })
                    })
                }
            })
            var storeClasstext=null;
            function childajax(selid) {
                $.ajax({
                    type: "get",
                    url: '<?php echo MOBILE_SITE_URL ?>/index.php?act=member_index&op=store_goods_class',
                    data: {key: getCookie('key'), level: 2},
                    dataType: "json",
                    success: function (r) {
                        $('.store_list1').hide();
                        $('.store_list2').show();
                        var datac = r.datas.store_goods_class;
                        $('#second_storeClassification').empty();
                        $.map(datac, function (h) {
                            $.map(h.child, function (c) {
                                if (selid == c.pid) {
                                    var listch = '<li data-id="' + c.id + '">' + c.name + '</li>';
                                    $('#second_storeClassification').append(listch);
                                    $('#second_storeClassification').find('li').each(function (index) {
                                        $(this).click(function () {
                                            storeClasstext= $('#first_storeClassification').find('li').eq(index).text();
                                            var sel = "#second_storeClassification";
                                            addactive(sel, index);
                                        })
                                    });
                                }
                            })
                        })

                    }
                })
            }
            function addactive(sel, index,selid) {
                var child = $(sel).find('li').eq(index).data('child');
                var selid = $(sel).find('li').eq(index).data('id');
                if (child == 1) {
                    childajax(selid);
                } else if (child == 2) {
                    storeClasstext=$(sel).find('li').eq(index).text();
                    var storetrxt = storeClasstext;
                    $('#storeClassification-text').html(storetrxt);
                    storeClasstext=null;
                    $('.storeClassification-hidden').val(selid);
                    $('.storeClassification').animate({'right': '-150%'}, 500);
                }else if(child != 2||child != 1){
                    storeClasstext+='>'+$(sel).find('li').eq(index).text();
                    var storetrxt = storeClasstext;
                    $('.storeClassification-text').html(storetrxt);
                    $('.storeClassification-hidden').val(selid);
                    $('.storeClassification').animate({'right': '-150%'}, 500);
                }

            }
            function storeback() {
                $('.store_list2').hide();
                $('.store_list1').show();
                $('.storeClassification').animate({'right': '-150%'}, 500);
            }

        </script>
<!--        编辑时读取店铺分类-->
