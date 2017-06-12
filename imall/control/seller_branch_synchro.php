<?php
/**
 * 数据同步
 *
 * 
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class seller_branch_synchroControl extends BaseBranchControl {
	
    public function __construct() {
        parent::__construct ();
        Language::read ('member_store_goods_index, seller_branch');
    }

    public function indexOp() {
		$this->synchro_goodsOp();      
    }
    
    /**
     * 商品信息同步
     */
    public function synchro_goodsOp() {
        $model_goods = Model('goods');
        
        $where = array();
        $where['store_id'] = $_SESSION['store_id'];
        if (intval($_GET['stc_id']) > 0) {
            $where['goods_stcids'] = array('like', '%' . intval($_GET['stc_id']) . '%');
        }
        if (trim($_GET['keyword']) != '') {
            switch ($_GET['search_type']) {
                case 0:
                    $where['goods_name'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 1:
                    $where['goods_serial'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 2:
                    $where['goods_commonid'] = intval($_GET['keyword']);
                    break;
            }
        }
        $goods_list = $model_goods->getGoodsCommonStubbsList($where);
        Tpl::output('show_page', $model_goods->showpage());
        Tpl::output('goods_list', $goods_list);
        
        // 计算库存
        $storage_array = $model_goods->calculateStorage($goods_list);
        Tpl::output('storage_array', $storage_array);
        
        // 商品分类
        $store_goods_class = Model('my_goods_class')->getClassTree(array(
                                    'store_id' => $_SESSION['store_id'],
                                    'stc_state' => '1' 
                                ));
        Tpl::output('store_goods_class', $store_goods_class);
		//分店列表
		$model_branch = Model('store');
		$condition=array();
		$condition['parent_id'] = $_SESSION['store_id'];
		$branch_list = $model_branch->getStoreList($condition,10);	
		Tpl::output('branch_list',$branch_list);

        $this->profile_menu('goods');
        Tpl::showpage('seller_branch_synchro.goods');
    }
	
	 /**
     * 保存商品同步信息
     */
    public function goods_saveOp() {
        $data_goods = $_GET['data'];
        if(!empty($data_goods)){
        	$src_id = explode(',', $data_goods);
        	$where['goods_id'] = array('in', $src_id);
        }
		
        //如果为空则同步全部的商品
	//if (empty($src_id) || !is_array($src_id)) {
	//  showdialog('请选择要同步的商品！', 'reload');
	//  exit;
	//}
		
	$model_goods = Model('goods');
		
	$where['store_id'] = $_SESSION['store_id'];
        $src_list = Model('goods')->getGoodsList($where);
		if (!is_array($src_list) || empty($src_list)) {
		  showdialog('请选择要同步的商品！', 'reload');
		  exit;
		}
			
		$stores = $_GET['stores'];
		$store_list = explode(',', $stores);
		if (!is_array($store_list) || empty($store_list)) {
		  showdialog('请选择要同步的分店！', 'reload');
		  exit;
		}	
        		
		$model_type = Model('type');
		$model_store = Model('store');
		$model_spec = Model('spec');
		// 生成商品二维码
        require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
		$PhpQRCode = new PhpQRCode();
		
		foreach ($store_list as $k => $store_id ) {
		  //店铺信息
		  $store_info=$model_store->getStoreInfoByID($store_id);
		  //店铺已存在的商品ID
		  $where = array('src_id' => array('in', $src_id), 'store_id' => $store_id);
		  $destid_list = $model_goods->table('goods')->field('src_id')->where($where)->getfield();
		  //
		  $curr_commonid = '';
		  $old_commonid = '';
		  
		  $curr_gc_id = '';
		  $spec_value_id = array(); //规格ID映射数组

		  foreach ($src_list as $key => $src_info) {
			//商品规格处理
			if ($curr_gc_id != $src_info['gc_id']){
			    $curr_gc_id = $src_info['gc_id'];
			    $src_spec_list = $model_spec->getSpecValueList(array('gc_id'=>$curr_gc_id,'store_id'=>$_SESSION['store_id']));
			    $src_spec_list = array_under_reset($src_spec_list,'sp_value_name');
			
			    $dest_spec_list = $model_spec->getSpecValueList(array('gc_id'=>$curr_gc_id,'store_id'=>$store_id));
			    $dest_spec_list = array_under_reset($dest_spec_list,'sp_value_name');			
			    
			    if (!empty($src_spec_list) && is_array($src_spec_list)){
				    foreach ($src_spec_list as $k_spec => $spec_info) {
					    if (is_array($dest_spec_list[$k_spec])){
						    $spec_value_id[$spec_info['sp_value_id']] = $dest_spec_list[$k_spec]['sp_value_id'];
					    }else{
						    $add_value = array();
						    $add_value['sp_value_name'] = $spec_info['sp_value_name'];
						    $add_value['sp_id'] = $spec_info['sp_id'];
						    $add_value['gc_id'] = $spec_info['gc_id'];
						    $add_value['store_id'] = $store_id;
						    $add_value['sp_value_color'] = $spec_info['sp_value_color'];
						    $add_value['sp_value_sort'] = $spec_info['sp_value_sort'];
						    $new_value_id = $model_spec->addSpecValue($add_value);
						    $spec_value_id[$spec_info['sp_value_id']] = $new_value_id;
					    }					    
				    }
			    }
			}			
			
			//如果没有商品公共信息则需增加商品公共信息 goods_common 表
			$where = array('src_commonid' => $src_info['goods_commonid'], 'store_id' => $store_id);
			$destCommonInfo = $model_goods->getGoodsCommonInfo($where);
			
			$old_common_main_img = '';
			$new_common_main_img = '';
			$old_goods_main_img = '';
			$new_goods_main_img = '';
			
			if (!is_array($destCommonInfo) || empty($destCommonInfo)) {
			    $where = array('goods_commonid' => $src_info['goods_commonid'], 'store_id' => $_SESSION['store_id']);
			    $srcCommonInfo = $model_goods->getGoodsCommonInfo($where);
				//处理规格值
				$src_spec_val = unserialize($srcCommonInfo['spec_value']);
				$new_spec_value = array();
				if (is_array($src_spec_val)){
					foreach ($src_spec_val as $sp_id => $value_info) {
						if (is_array($value_info)){
							foreach ($value_info as $val_id => $val_name) {
								$new_spec_value[$sp_id][$spec_value_id[$val_id]] = $val_name;
							}
						}
					}
				}
				$old_common_main_img = $srcCommonInfo['goods_image'];
				$new_common_main_img = str_replace($_SESSION['store_id'].'_', $store_id.'_', $old_common_main_img);	
				
			    $common_array = array();
                $common_array['goods_name']         = $srcCommonInfo['goods_name'];  //商品名称
                $common_array['goods_jingle']       = $srcCommonInfo['goods_jingle'];  //商品广告词
                $common_array['gc_id']              = $srcCommonInfo['gc_id'];  //商品分类
		        $common_array['gc_id_1']            = $srcCommonInfo['gc_id_1'];  //一级分类id
			    $common_array['gc_id_2']            = $srcCommonInfo['gc_id_2'];  //二级分类id
			    $common_array['gc_id_3']            = $srcCommonInfo['gc_id_3'];  //三级分类id
                $common_array['gc_name']            = $srcCommonInfo['gc_name'];  //商品分类
			    $common_array['store_id']           = $store_info['store_id'];  //店铺id
                $common_array['store_name']         = $store_info['store_name'];  //店铺名称
			    $common_array['store_type']         = $store_info['store_type'];  //店铺类型 0:商城自营 1:个人 2:企业
                $common_array['spec_name']          = $srcCommonInfo['spec_name'];  //规格名称
                $common_array['spec_value']         = serialize($new_spec_value);//$srcCommonInfo['spec_value'];	 //规格值		
                $common_array['brand_id']           = $srcCommonInfo['brand_id'];  //品牌id
                $common_array['brand_name']         = $srcCommonInfo['brand_name'];  //品牌名称
                $common_array['type_id']            = $srcCommonInfo['type_id'];  //类型id
                $common_array['goods_image']        = $new_common_main_img;  //商品主图
		        $common_array['goods_attr']         = $srcCommonInfo['goods_attr'];  //商品属性
                $common_array['goods_body']         = $srcCommonInfo['goods_body'];  //商品内容
			    $common_array['mobile_body']        = $srcCommonInfo['mobile_body'];  //手机端商品描述
			    $common_array['goods_state']        = 0;  //商品状态 0下架，1正常，10违规（禁售）			
			    //$common_array['goods_stateremark']  = $srcCommonInfo['goods_stateremark'];  //违规原因
			    $common_array['goods_verify']       = $srcCommonInfo['goods_verify'];  //商品审核 1通过，0未通过，10审核中
			    //$common_array['goods_verifyremark'] = $srcCommonInfo['goods_verifyremark'];  //审核失败原因
			    $common_array['goods_lock']         = $srcCommonInfo['goods_lock'];  //商品锁定 0未锁，1已锁
			    $common_array['goods_addtime']      = TIMESTAMP;  //商品添加时间
                $common_array['goods_selltime']     = $srcCommonInfo['goods_selltime'];  //上架时间
                $common_array['goods_price']        = $srcCommonInfo['goods_price'];  //商品价格
                $common_array['goods_marketprice']  = $srcCommonInfo['goods_marketprice'];  //市场价
			    $common_array['goods_tradeprice']   = $srcCommonInfo['goods_tradeprice'];  //批发价
                $common_array['goods_costprice']    = $srcCommonInfo['goods_costprice'];  //成本价
                $common_array['goods_discount']     = $srcCommonInfo['goods_discount'];  //折扣
                $common_array['goods_serial']       = $srcCommonInfo['goods_serial'];	//商家编号			
			    $common_array['goods_storage_alarm']= $srcCommonInfo['goods_storage_alarm'];  //库存报警值 
			    $common_array['transport_id']       = $srcCommonInfo['transport_id'];  //运费模板
                $common_array['transport_title']    = $srcCommonInfo['transport_title'];  //运费模板名称			             
                $common_array['goods_commend']      = $srcCommonInfo['goods_commend'];  //商品推荐 1是，0否，默认为0
			    $common_array['goods_freight']      = $srcCommonInfo['goods_freight'];	//运费 0为免运费		
                $common_array['goods_vat']          = $srcCommonInfo['goods_vat'];  //是否开具增值税发票 1是，0否
                $common_array['areaid_1']           = $srcCommonInfo['areaid_1'];  //一级地区id
                $common_array['areaid_2']           = $srcCommonInfo['areaid_2'];  //二级地区id
                $common_array['goods_stcids']       = $srcCommonInfo['goods_stcids'];  //店铺分类id 首尾用,隔开
                $common_array['plateid_top']        = $srcCommonInfo['plateid_top'];  //顶部关联板式		
                $common_array['plateid_bottom']     = $srcCommonInfo['plateid_bottom'];	//底部关联板式		
			    $common_array['is_virtual']         = $srcCommonInfo['is_virtual'];  //是否为虚拟商品 1是，0否
			    $common_array['virtual_indate']     = $srcCommonInfo['virtual_indate'];  //虚拟商品有效期
			    $common_array['virtual_limit']      = $srcCommonInfo['virtual_limit'];  //虚拟商品购买上限
			    $common_array['virtual_invalid_refund']= $srcCommonInfo['virtual_invalid_refund'];  //是否允许过期退款， 1是，0否
			    $common_array['is_fcode']           = $srcCommonInfo['is_fcode'];  //是否为F码商品 1是，0否
			    $common_array['is_appoint']         = $srcCommonInfo['is_appoint'];  //是否是预约商品 1是，0否
			    $common_array['appoint_satedate']   = $srcCommonInfo['appoint_satedate'];	//预约商品出售时间			
			    $common_array['is_presell']         = $srcCommonInfo['is_presell'];  //是否是预售商品 1是，0否
			    $common_array['presell_deliverdate']= $srcCommonInfo['presell_deliverdate'];  //预售商品发货时间
			    $common_array['is_own_shop']        = $srcCommonInfo['is_own_shop'];  //是否为平台自营							
			    $common_array['view_grade']         = $srcCommonInfo['view_grade'];  //可见会员级别
		        $common_array['promotion_cid']      = $srcCommonInfo['promotion_cid'];  //推广员佣金类型
		        $common_array['saleman_cid']        = $srcCommonInfo['saleman_cid'];  //导购员提成类型
			    $common_array['src_commonid']       = $srcCommonInfo['goods_commonid'];  //派生商品ID
			    // 保存数据
                $common_id = $model_goods->addGoodsCommon($common_array);
				
			    $gc_id = $common_array['gc_id'];
			    $type_id = $common_array['type_id'];
			    $goods_attr = unserialize($common_array['goods_attr']);
			    $goods_image = $common_array['goods_image'];
			}else{
			    $common_id = $destCommonInfo['goods_commonid'];
			    $gc_id = $destCommonInfo['gc_id'];
			    $type_id = $destCommonInfo['type_id'];
			    $goods_attr = unserialize($destCommonInfo['goods_attr']);
			    $goods_image = $destCommonInfo['goods_image'];
			}	
			//如果不存在，则需增加商品信息	
			if ($common_id) {
			  //处理规格值
			  $src_goods_spec = unserialize($src_info['goods_spec']);
			  $new_goods_spec = array();
			  if (is_array($src_goods_spec)){
				foreach ($src_goods_spec as $sp_id => $val_name) {
				  $new_goods_spec[$spec_value_id[$sp_id]] = $val_name;
			    }
			  }	
			  $old_goods_main_img = $src_info['goods_image'];
			  $new_goods_main_img = str_replace($_SESSION['store_id'].'_', $store_id.'_', $old_goods_main_img);	
					  
			  $goods = array();
              $goods['goods_commonid']    = $common_id;  //商品公共表id
              $goods['goods_name']        = $src_info['goods_name']; //商品名称（+规格名称）
              $goods['goods_jingle']      = $src_info['goods_jingle'];  //商品广告词
              $goods['store_id']          = $store_info['store_id'];  //店铺id
              $goods['store_name']        = $store_info['store_name'];  //店铺名称
			  $goods['store_type']        = $store_info['store_type'];  //店铺类型 0:商城自营 1:个人 2:企业
              $goods['gc_id']             = $src_info['gc_id'];  //商品分类id
			  $goods['gc_id_1']           = $src_info['gc_id_1'];  //一级分类id
			  $goods['gc_id_2']           = $src_info['gc_id_2'];  //二级分类id
			  $goods['gc_id_3']           = $src_info['gc_id_3'];  //三级分类id
              $goods['brand_id']          = $src_info['brand_id'];  //品牌id
              $goods['goods_price']       = $src_info['goods_price'];  //商品价格
			  $goods['goods_promotion_price'] = $src_info['goods_promotion_price']; //商品促销价格
			  $goods['goods_promotion_type']  = $src_info['goods_promotion_type'];  //促销类型 0无促销，1抢购，2限时折扣
              $goods['goods_marketprice'] = $src_info['goods_marketprice'];  //市场价
			  $goods['goods_tradeprice']  = $src_info['goods_tradeprice'];  //市场价
			  $goods['goods_costprice']   = $src_info['goods_costprice'];  //成本价
              $goods['goods_serial']      = $src_info['goods_serial'];  //商家编号
			  $goods['goods_storage_alarm']= $src_info['goods_storage_alarm'];  //库存报警值
			  $goods['goods_click']       = 0;  //商品点击数量
			  $goods['goods_salenum']     = 0;  //销售数量
			  $goods['goods_collect']     = 0;  //收藏数量			
              $goods['goods_spec']        = serialize($new_goods_spec); //$src_info['goods_spec'];  //商品规格序列化
              $goods['goods_storage']     = 0;  //商品库存
              $goods['goods_image']       = $new_goods_main_img;  //商品主图
              $goods['goods_state']       = 0;  //商品状态 0下架，1正常，10违规（禁售）
              $goods['goods_verify']      = $src_info['goods_verify'];  //商品审核 1通过，0未通过，10审核中
              $goods['goods_addtime']     = TIMESTAMP;  //商品添加时间
              $goods['goods_edittime']    = TIMESTAMP;  //商品编辑时间
              $goods['areaid_1']          = $src_info['areaid_1'];  //一级地区id
              $goods['areaid_2']          = $src_info['areaid_2'];  //二级地区id
              $goods['color_id']          = $spec_value_id[$src_info['color_id']];  //颜色规格id
              $goods['transport_id']      = $src_info['transport_id'];  //运费模板id
			  $goods['have_gift']         = $src_info['have_gift'];  //是否拥有赠品
              $goods['goods_freight']     = $src_info['goods_freight'];  //运费 0为免运费
              $goods['goods_vat']         = $src_info['goods_vat'];  //是否开具增值税发票 1是，0否
              $goods['goods_commend']     = $src_info['goods_commend'];  //商品推荐 1是，0否 默认0
              $goods['goods_stcids']      = $src_info['goods_stcids'];  //店铺分类id 首尾用,隔开
		      $goods['evaluation_good_star'] = $src_info['evaluation_good_star'];	 //好评星级
			  $goods['evaluation_count']  = 0;  //评价数
			  $goods['view_grade']        = $src_info['view_grade'];  //可见会员级别
			  $goods['promotion_cid']     = $src_info['promotion_cid'];  //推广员佣金类型
			  $goods['saleman_cid']       = $src_info['saleman_cid'];  //导购员提成类型
			  $goods['src_id']            = $src_info['goods_id'];	//派生商品ID			
			  $goods['is_virtual']        = $src_info['is_virtual'];  //是否为虚拟商品 1是，0否
              $goods['virtual_indate']    = $src_info['virtual_indate'];  //虚拟商品有效期
              $goods['virtual_limit']     = $src_info['virtual_limit'];  //虚拟商品购买上限
              $goods['virtual_invalid_refund']= $src_info['virtual_invalid_refund'];  //是否允许过期退款， 1是，0否
              $goods['is_fcode']          = $src_info['is_fcode'];  //是否为F码商品 1是，0否
              $goods['is_appoint']        = $src_info['is_appoint'];  //是否是预约商品 1是，0否
              $goods['is_presell']        = $src_info['is_presell'];  //是否是预售商品 1是，0否
			  $goods['is_own_shop']       = $src_info['is_own_shop'];  //是否为平台自营			
			  
			  if (in_array($src_info['goods_id'],$destid_list)){
				  //$model_goods->editGoodsById($goods,array('goods_id'=>$src_info['goods_id']));
			  }else{
                  $goods_id = $model_goods->addGoods($goods);
				  //添加属性表数据
                  $model_type->addGoodsType($goods_id, $common_id, array('cate_id' => $gc_id, 'type_id' => $type_id, 'attr' => $goods_attr));
				  //生成商品二维码
                  $PhpQRCode->BuildGoodsQRCode($store_info['store_id'], $goods_id, $goods_image);
				  //复制商品图片开始
				  $curr_commonid = $common_id;
		          if ($curr_commonid != $old_commonid){					
				      $old_commonid = $curr_commonid;
				      $where = array('goods_commonid'=>$src_info['goods_commonid'],'store_id'=>$_SESSION['store_id']);
				      $goods_image = $model_goods->getGoodsImageList($where);

				      if (!empty($goods_image) && is_array($goods_image)){					  
				          //删除旧图片
				          $model_goods->delGoodsImages(array('goods_commonid' => $common_id, 'store_id' => $store_info['store_id']));
					
				          //添加新图片
				          $new_image = array();
					      $src_path = BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $_SESSION['store_id'];
					      $dest_path = BASE_UPLOAD_PATH . '/' . ATTACH_GOODS . '/' . $store_info['store_id'];
					      if (!is_dir($dest_path)) mkdir($dest_path);
				          foreach ($goods_image as $ik => $img_info) {							  
					          $src_img = $src_path . DS . $img_info['goods_image'];
							  $new_images_name = str_replace($_SESSION['store_id'].'_', $store_id.'_', $img_info['goods_image']);
					          $dest_img = $dest_path . DS . $new_images_name;	
							  if (file_exists($src_img)){ 					  
					              $ok	= @copy($src_img,$dest_img);
					              if ($ok){
					                  $temp = array();
					                  $temp['goods_commonid'] = $common_id;
					                  $temp['store_id'] = $store_info['store_id'];
					                  $temp['color_id'] = $spec_value_id[$img_info['color_id']];
					                  $temp['goods_image'] = $new_images_name;
					                  $temp['goods_image_sort'] = $img_info['goods_image_sort'];
					                  $temp['is_default'] = $img_info['is_default'];
				                      $new_image[] = $temp;
								  }
					          }					
				          }
				          if (!empty($new_image)){
					          $model_goods->addGoodsImagesAll($new_image);
				          }
						  //商品公共主图
						  if ($new_common_main_img !='' && file_exists($src_path . DS . $old_common_main_img)){ 					  
					          @copy($src_path . DS . $old_common_main_img, $dest_path . DS . $new_common_main_img);
						  }
						  //商品主图
						  if ($new_goods_main_img !='' && file_exists($src_path . DS . $old_goods_main_img)){ 					  
					          @copy($src_path . DS . $old_goods_main_img, $dest_path . DS . $new_goods_main_img);
						  }
				      }
		          }
				  //复制商品图片结束
			  }			            
			}
		  }//end for foreach
	    }//end for foreach
		showdialog('商品信息同步操作完成！', urlShop('seller_branch_synchro','index'), 'succ');
    }    
   
    /**
     * ajax获取商品列表
     */
    public function get_goods_list_ajaxOp() {
        $common_id = $_GET['commonid'];
        if ($common_id <= 0) {
            echo 'false';exit();
        }
        $model_goods = Model('goods');
        $goodscommon_list = $model_goods->getGoodsCommonInfo(array('store_id' => $_SESSION['store_id'], 'goods_commonid' => $common_id), 'spec_name');
        if (empty($goodscommon_list)) {
            echo 'false';exit();
        }
        $goods_list = $model_goods->getGoodsList(array('store_id' => $_SESSION['store_id'], 'goods_commonid' => $common_id), 'goods_id,goods_spec,store_id,goods_price,goods_tradeprice,goods_serial,goods_storage,goods_image');
        if (empty($goods_list)) {
            echo 'false';exit();
        }
        
        $spec_name = array_values((array)unserialize($goodscommon_list['spec_name']));
        foreach ($goods_list as $key => $val) {
            $goods_spec = array_values((array)unserialize($val['goods_spec']));
            $spec_array = array();
            foreach ($goods_spec as $k => $v) {
                $spec_array[] = '<div class="goods_spec">' . $spec_name[$k] . L('im_colon') . '<em title="' . $v . '">' . $v .'</em>' . '</div>';
            }
			$goods_list[$key]['goods_tradeprice'] = ($val['goods_tradeprice']<=0)?$val['goods_price']:$val['goods_tradeprice'];
            $goods_list[$key]['goods_image'] = thumb($val, '60');
            $goods_list[$key]['goods_spec'] = implode('', $spec_array);
            $goods_list[$key]['alarm'] = ($val['goods_storage_alarm'] != 0 && $val['goods_storage'] <= $val['goods_storage_alarm']) ? 'style="color:red;"' : '';
            $goods_list[$key]['url'] = urlShop('goods', 'index', array('goods_id' => $val['goods_id']));
        }

        /**
         * 转码
         */
        if (strtoupper(CHARSET) == 'GBK') {
            Language::getUTF8($goods_list);
        }
        echo json_encode($goods_list);
    }
	
	/**
     * 店铺配置信息同步
     */
    public function synchro_configOp() {
		
		//分店列表
		$model_branch = Model('store');
		$condition=array();
		$condition['parent_id'] = $_SESSION['store_id'];
		$branch_list = $model_branch->getStoreList($condition,10);	
		Tpl::output('branch_list',$branch_list);
		
		$this->profile_menu('config');
        Tpl::showpage('seller_branch_synchro.config');		
	}
	
	/**
     * 店铺配置信息同步保存
     */
    public function config_saveOp() {
		$data_item = $_GET['data'];
		$item_list = explode(',', $data_item);	
		if (empty($item_list) || !is_array($item_list)) {
		  showdialog('请选择要同步的项目！', 'reload');
		  exit;
		}		
		
		$stores = $_GET['stores'];
		$store_list = explode(',', $stores);
		if (!is_array($store_list) || empty($store_list)) {
		  showdialog('请选择要同步的分店！', 'reload');
		  exit;
		}
		//店铺信息
		if (in_array('store_info',$item_list)){
			$update_data = array();			
			$update_data['store_label'] = $this->store_info['store_label'];
			$update_data['store_banner'] = $this->store_info['store_banner'];
			$update_data['store_avatar'] = $this->store_info['store_avatar'];
			$update_data['store_weixin'] = $this->store_info['store_weixin'];			
			$update_data['store_keywords'] = $this->store_info['store_keywords'];
			$update_data['store_description'] = $this->store_info['store_description'];
			$update_data['store_copyright'] = $this->store_info['store_copyright'];
			$update_data['store_qq'] = $this->store_info['store_qq'];
			$update_data['store_ww'] = $this->store_info['store_ww'];
			$update_data['store_phone'] = $this->store_info['store_phone'];			
			$update_data['store_zy'] = $this->store_info['store_zy'];			
			$update_data['store_theme'] = $this->store_info['store_theme'];
			$update_data['store_stamp'] = $this->store_info['store_stamp'];
			$update_data['store_printdesc'] = $this->store_info['store_printdesc'];
			$update_data['store_workingtime'] = $this->store_info['store_workingtime'];
			$update_data['store_free_price'] = $this->store_info['store_free_price'];
			$update_data['extension_adv'] = $this->store_info['extension_adv'];			
			$update_data['promotion_open'] = $this->store_info['promotion_open'];
			$update_data['promotion_level'] = $this->store_info['promotion_level'];
			$update_data['promotion_limit'] = $this->store_info['promotion_limit'];
			$update_data['promotion_require'] = $this->store_info['promotion_require'];
			$update_data['saleman_open'] = $this->store_info['saleman_open'];			
			$update_data['saleman_limit'] = $this->store_info['saleman_limit'];
			$update_data['saleman_require'] = $this->store_info['saleman_require'];
			$update_data['store_vrcode_prefix'] = $this->store_info['store_vrcode_prefix'];

		    $model_store = Model('store');	
		    foreach ($store_list as $k => $store_id ) {				
		        $model_store->editStore($update_data,array('store_id'=>$store_id));				
		    }
		}
		
		//店铺分类
		if (in_array('store_class',$item_list)){
			$model_bind_class = Model('store_bind_class');
			$model_goods_class = Model('store_goods_class');	
		    foreach ($store_list as $k => $store_id ) {
				$src_bind_class = $model_bind_class->getStoreBindClassList(array('store_id'=>$_SESSION['store_id'],'state'=>1));				
				if (!empty($src_bind_class)){
					$dest_bind_class = array();
					$model_bind_class->delStoreBindClass(array('store_id'=>$store_id));
					foreach ($src_bind_class as $k => $bind_class ) {
						$temp_class = array();
						$temp_class['store_id'] = $store_id;
						$temp_class['commis_rate'] = $bind_class['commis_rate'];
						$temp_class['class_1'] = $bind_class['class_1'];
						$temp_class['class_2'] = $bind_class['class_2'];
						$temp_class['class_3'] = $bind_class['class_3'];
						$temp_class['state'] = $bind_class['state'];
						$dest_bind_class[] = $temp_class;						
					}
					$model_bind_class->addStoreBindClassAll($dest_bind_class);					
				}
				
				$src_goods_class = $model_goods_class->getShowTreeList($_SESSION['store_id']);				
				if (!empty($src_goods_class)){
					$dest_goods_class = array();
					$model_goods_class->delStoreGoodsClass(array('store_id'=>$store_id));
					foreach ($src_goods_class as $k1 => $class1 ) {
						$temp_class = array();
						$temp_class['store_id'] = $store_id;
						$temp_class['stc_name'] = $class1['stc_name'];
						$temp_class['stc_state'] = $class1['stc_state'];
						$temp_class['stc_sort'] = $class1['stc_sort'];
						$temp_class['stc_parent_id'] = 0;
						$class1_id = $model_goods_class->insert($temp_class);
						if (!empty($class1['children'])){
							foreach ($class1['children'] as $k2 => $class2 ) {
								$temp_class = array();
						        $temp_class['store_id'] = $store_id;
						        $temp_class['stc_name'] = $class2['stc_name'];
						        $temp_class['stc_state'] = $class2['stc_state'];
						        $temp_class['stc_sort'] = $class2['stc_sort'];
						        $temp_class['stc_parent_id'] = $class1_id;
						        $class2_id = $model_goods_class->insert($temp_class);
								if (!empty($class2['children'])){
							        foreach ($class2['children'] as $k3 => $class3 ) {
								        $temp_class = array();
						                $temp_class['store_id'] = $store_id;
						                $temp_class['stc_name'] = $class3['stc_name'];
						                $temp_class['stc_state'] = $class3['stc_state'];
						                $temp_class['stc_sort'] = $class3['stc_sort'];
						                $temp_class['stc_parent_id'] = $class2_id;
						                $class3_id = $model_goods_class->insert($temp_class);								
							        }
						        }//end for class 3								
							}
						}//end for class 2					
					}
					dcache($store_id, 'store_goods_class');	
				}//end for class 1		        			
		    }			
		}
		//店铺规格
		if (in_array('store_spec',$item_list)){
			$model_spec = Model('spec');
			$src_spec = $model_spec->getSpecValueList(array('store_id'=>$_SESSION['store_id']));
			if (!empty($src_spec)){
				$model_spec->delSpecValue(array('store_id'=>array('in',$store_list)));
				$dest_spec = array();
		        foreach ($store_list as $k => $store_id ) {
					foreach ($src_spec as $key => $spec_info ) {					
					    $temp_spec = array();
						$temp_spec['sp_value_name'] = $spec_info['sp_value_name'];
						$temp_spec['sp_id'] = $spec_info['sp_id'];
						$temp_spec['gc_id'] = $spec_info['gc_id'];
						$temp_spec['store_id'] = $store_id;
						$temp_spec['sp_value_color'] = $spec_info['sp_value_color'];
						$temp_spec['sp_value_sort'] = $spec_info['sp_value_sort'];
						$dest_spec[] = $temp_spec;
					}					
				}
				$model_spec->addSpecValueALL($dest_spec);				
			}
		}
		//推广配置
		if (in_array('store_extension',$item_list)){			
			$model_commis_rate = Model('extension_commis_rate');
			$src_commis_rate = $model_commis_rate->getCommisRateInfo($_SESSION['store_id']);
			if (!empty($src_commis_rate)){
				$model_commis_rate->where(array('store_id'=>array('in',$store_list)))->delete();
				unset($src_commis_rate['mcr_id']);
			    foreach ($store_list as $k => $store_id) {
					$src_commis_rate['store_id'] = $store_id;					
					$model_commis_rate->insert($src_commis_rate);
				}
			}
			
			$model_commis_class = Model('extension_commis_class');
			$src_commis_class = $model_commis_class->getCommisList($_SESSION['store_id']);
			if (!empty($src_commis_class)){
				$model_commis_class->where(array('store_id'=>array('in',$store_list)))->delete();
				$dest_commis_class = array();
				foreach ($src_commis_class as $k => $class_info) {
					unset($class_info['commis_id']);
					$src_commis_class[$k] = $class_info;
				}					
			    foreach ($store_list as $key => $store_id) {
					foreach ($src_commis_class as $k => $class_info) {
						$class_info['store_id'] = $store_id;
					    $dest_commis_class[] = $class_info;
				    }					
				}
				$model_commis_class->insertAll($dest_commis_class);				
			}
		}
		
		showdialog('店铺配置信息同步操作完成！', urlShop('seller_branch_synchro','synchro_config'), 'succ');		
	}

    /**
     * 用户中心右边，小导航
     * 
     * @param string $menu_type 导航类型
     * @param string $menu_key 当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array();
		$menu_array[0] = array('menu_key' => 'goods', 'menu_name' => '商品信息同步', 'menu_url' => urlShop('seller_branch_synchro', 'index'));
		$menu_array[1] = array('menu_key' => 'config', 'menu_name' => '店铺配置同步', 'menu_url' => urlShop('seller_branch_synchro', 'synchro_config'));
		
        Tpl::output ('member_menu', $menu_array);
        Tpl::output ('menu_key', $menu_key);
    }

}