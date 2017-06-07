<?php
/**
 * 商品调拔
 *
 * 
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class seller_branch_stubbsControl extends BaseBranchControl {
	protected $Replenish_appay_count = '';
	protected $Returned_appay_count = '';
	
    public function __construct() {
        parent::__construct ();
        Language::read ('member_store_goods_index, seller_branch');
		
		$model_apply = Model('branch_apply');
		//等处理补货申请数
		$this->Replenish_appay_count = $model_apply->getApplyCount(array('bp_store_id'=>$_SESSION['store_id'],'bp_type'=>1,'bp_dispose'=>0));
		if ($this->Replenish_appay_count<=0){
			$this->Replenish_appay_count = '';
		}else{
			$this->Replenish_appay_count = '('.$this->Replenish_appay_count.')';
		}
		//等处理退货申请数
		$this->Returned_appay_count = $model_apply->getApplyCount(array('bp_store_id'=>$_SESSION['store_id'],'bp_type'=>2,'bp_dispose'=>0));
		if ($this->Returned_appay_count<=0){
			$this->Returned_appay_count = '';
		}else{
			$this->Returned_appay_count = '('.$this->Returned_appay_count.')';
		}
    }
	//调拔订单
    public function indexOp() {
		$this->replenish_orderOp();      
    }
    
    /**
     * 商品调拔
     */
    public function stubbsOp() {
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

        $this->profile_menu('stubbs');
        Tpl::showpage('seller_branch_stubbs.out');
    }
	
	 /**
     * 保存商品调拔
     */
    public function stubbs_saveOp() {
        $data_goods = $_GET['data'];
        $data_goods_array = explode(',', $data_goods);
		if (is_array($data_goods_array) && !empty($data_goods_array)) {
		  $src_id=array();
		  $data_goods_info=array();
		  foreach ($data_goods_array as $k => $v ) {
			$goods_info = explode('|', $v);
			$src_id[]=$goods_info[0];
			$data_goods_info[$goods_info[0]]['prices']=$goods_info[1];
			$data_goods_info[$goods_info[0]]['nums']=$goods_info[2];
		  }
		}
		
		$model_goods = Model('goods');
		
		$where = array('goods_id' => array('in', $src_id), 'store_id' => $_SESSION['store_id']);
        $src_list = $model_goods->getGoodsList($where);
		if (!is_array($src_list) || empty($src_list)) {
		  showdialog('请选择要调拔的商品！', 'reload');
		  exit;
		}
			
		$stores = $_GET['stores'];
		$store_list = explode(',', $stores);
		if (!is_array($store_list) || empty($store_list)) {
		  showdialog('请选择要调拔的分店！', 'reload');
		  exit;
		}        
		
		$model_type = Model('type');
		$model_store = Model('store');
		$model_spec = Model('spec');
		// 生成商品二维码
        require_once(BASE_RESOURCE_PATH.DS.'phpqrcode'.DS.'index.php');
		$PhpQRCode = new PhpQRCode();
		//调拔商品		
		$model_stubbs = Model('branch_stubbs');
		$model_order = Model('branch_order');
		$stubbs_goods_list = array();
		
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
		  //调拔信息
		  $replenish_goods = array();
		  $replenish_totals = 0;
		  $order_sn = $this->makeOrderSn($_SESSION['store_id']);	

		  foreach ($src_list as $key => $src_info) {			
			
			if (in_array($src_info['goods_id'],$destid_list)){
			  //如果存在，则只需增加库存
			  $where = array('src_id' => $src_info['goods_id'], 'store_id' => $store_id);
			  //$model_goods->table('goods')->where($where)->setInc('goods_storage',$data_goods_info[$src_info['goods_id']]['nums']);
			  $goods = $model_goods->getGoodsInfo($where,'goods_id,goods_name,goods_commonid,goods_serial,goods_stcids,goods_price,goods_costprice,goods_image');
			  $goods_id = $goods['goods_id'];
			}else{
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
                $common_array['goods_selltime']     = TIMESTAMP;//$srcCommonInfo['goods_selltime'];  //上架时间	
                $common_array['goods_price']        = $srcCommonInfo['goods_price'];  //商品价格
                $common_array['goods_marketprice']  = $srcCommonInfo['goods_marketprice'];  //市场价
			    $common_array['goods_tradeprice']   = $srcCommonInfo['goods_price'];//$srcCommonInfo['goods_tradeprice'];  //批发价
                $common_array['goods_costprice']    = $data_goods_info[$src_info['goods_id']]['prices'];//$srcCommonInfo['goods_costprice'];  //成本价
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
			    $goods['goods_tradeprice']  = $src_info['goods_price'];//$src_info['goods_tradeprice'];  //批发价
			    $goods['goods_costprice']   = $data_goods_info[$src_info['goods_id']]['prices'];//$src_info['goods_costprice'];  //成本价
                $goods['goods_serial']      = $src_info['goods_serial'];  //商家编号
			    $goods['goods_storage_alarm']= $src_info['goods_storage_alarm'];  //库存报警值
			    $goods['goods_click']       = 0;  //商品点击数量
			    $goods['goods_salenum']     = 0;  //销售数量
			    $goods['goods_collect']     = 0;  //收藏数量			
                $goods['goods_spec']        = serialize($new_goods_spec); //$src_info['goods_spec'];  //商品规格序列化
                $goods['goods_storage']     = 0;//$data_goods_info[$src_info['goods_id']]['nums'];  //商品库存 分店未收货
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
				
                $goods_id = $model_goods->addGoods($goods);
                $model_type->addGoodsType($goods_id, $common_id, array('cate_id' => $gc_id, 'type_id' => $type_id, 'attr' => $goods_attr));
                // 生成商品二维码
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
			  }// end for if ($common_id) {
			}//end for if (in_array($src_info['goods_id'],$destid_list)){
			//添加调拔记录		
		    $stubbs_goods = array();
		    $stubbs_goods['store_id']=$_SESSION['store_id'];
			$stubbs_goods['store_name']=$_SESSION['store_name'];
			$stubbs_goods['goods_id']=$src_info['goods_id'];
			$stubbs_goods['goods_name']=$src_info['goods_name'];				
			$stubbs_goods['goods_commonid']=$src_info['goods_commonid'];
			$stubbs_goods['goods_serial']=$src_info['goods_serial'];
			$stubbs_goods['goods_stcids']=$src_info['goods_stcids'];			
			$stubbs_goods['goods_price']=$src_info['goods_price'];
			$stubbs_goods['goods_tradeprice']=$data_goods_info[$src_info['goods_id']]['prices'];//批发价
			$stubbs_goods['goods_image']=$src_info['goods_image'];							
			$stubbs_goods['branch_id']=$store_info['store_id'];
			$stubbs_goods['branch_name']=$store_info['store_name'];						
			$stubbs_goods['b_goods_id']=$goods_id;
			$stubbs_goods['b_goods_name']=$goods['goods_name'];				
			$stubbs_goods['b_goods_commonid']=$goods['goods_commonid'];
			$stubbs_goods['b_goods_serial']=$goods['goods_serial'];
			$stubbs_goods['b_goods_stcids']=$goods['goods_stcids'];				
			$stubbs_goods['b_goods_price']=$goods['goods_price'];
			$stubbs_goods['b_goods_costprice']=$goods['goods_costprice'];//进货价
			$stubbs_goods['b_goods_image']=$goods['goods_image'];							
			$stubbs_goods['goods_num']=$data_goods_info[$src_info['goods_id']]['nums'];			    
			$stubbs_goods['stubbs_time']=TIMESTAMP;
			$stubbs_goods['stubbs_type']=0; //调拔类型 0：总店调拔 1：总店代发 2:分店退货
			$stubbs_goods['stubbs_state']=1; //发货状态 0：待发货 1：已发货
			$stubbs_goods['b_order_sn']=$order_sn;
			
			$replenish_goods[] = $stubbs_goods;
			$replenish_totals += $stubbs_goods['goods_num']*$stubbs_goods['goods_tradeprice'];
			
			$stubbs_goods_list[]=array('p_id'=>$stubbs_goods['goods_id'],'nums'=>$stubbs_goods['goods_num']);
		  }//end for foreach ($src_list as $key => $src_info) {
		  $model_stubbs->insertAll($replenish_goods);

          //补货订单
		  $branch_order = array();
		  $branch_order['order_sn'] = $order_sn;
		  $branch_order['store_id'] = $_SESSION['store_id'];
		  $branch_order['store_name'] = $_SESSION['store_name'];
		  $branch_order['branch_id'] = $store_info['store_id'];
		  $branch_order['branch_name'] = $store_info['store_name'];
		  $branch_order['goods_amount'] = $replenish_totals;
		  $branch_order['shipping_fee'] = 0;
		  $branch_order['order_amount'] = $branch_order['goods_amount']+$branch_order['shipping_fee'];
		  $branch_order['add_time'] = TIMESTAMP;
		  $branch_order['payment_state'] = 0; //'付款状态：0(未付款) 1:已付款(分店付款 总店退款)'
		  $branch_order['payment_code'] = ''; //支付方式名称代码
		  $branch_order['payment_time'] = 0;
		  $branch_order['rcb_amount'] = 0; //充值卡支付金额
		  $branch_order['pd_amount'] = 0; //积分支付金额
		  $branch_order['bo_pay_content'] = ''; //支付备注
		  $branch_order['order_type'] = 0; //订单类型 0：总店调拔 1：总店代发 2:分店退货
		  $branch_order['order_state'] = 50;//订单状态：0(已取消)20:总店同意(分店待发货或分店待付款)30:分店已发货(总店待收货)40:总店已收货(确认待调拔)50:总店已发货(分店待收货确认)60:分店已收货(确认完成)
		  $branch_order['finnshed_time'] = TIMESTAMP; //订单完成时间
		  $branch_order['shipping_time'] = TIMESTAMP; //调拔配送时间
		  $branch_order['shipping_express_id'] = 0; //调拔配送公司ID
		  $branch_order['shipping_code'] = ''; //调拔物流单号
		  $branch_order['tui_shipping_time'] = 0; //退货配送时间
          $branch_order['tui_shipping_express_id'] = 0; //退货配送公司ID
		  $branch_order['tui_shipping_code'] = ''; //退货物流单号
		  $branch_order['bill_no'] = ''; //结算单编号
		  $branch_order['bo_admin'] = $_SESSION['seller_name']; //发货 退货 操作		
		  
		  $model_order->insert($branch_order);		  	
	    }//end for foreach ($store_list as $k => $store_id ) {
		//减总店库存
		if (!empty($stubbs_goods_list)){
			foreach ($stubbs_goods_list as $k => $val) {
				$model_goods->where(array('goods_id'=>$val['p_id'],'store_id'=>$_SESSION['store_id']))->setDec('goods_storage',$val['nums']);
			}
		}		
		showdialog('商品调拔操作完成！', urlShop('seller_branch_stubbs','index'), 'succ');
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
     * 分店补货单
     */
    public function replenish_orderOp() {
		$model_order = Model('branch_order');
		
		$where = array();
        $where['store_id'] = $_SESSION['store_id'];
        if ($_GET['order_sn'] != '') {
            $where['order_sn'] = $_GET['order_sn'];
        }
        if ($_GET['buyer_name'] != '') {
            $where['branch_name'] = $_GET['buyer_name'];
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $where['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
		
		$order_list = $model_order->getReplenishOrderList($where);
		if (!empty($order_list) && is_array($order_list)){
			$model_stubbs = Model('branch_stubbs');	
			$model_store = Model('store');
			foreach ($order_list as $key => $val) {
				$where = array();
				$where['b_order_sn'] = $val['order_sn'];
				$where['store_id'] = $_SESSION['store_id'];
				$goods_list = $model_stubbs->getReplenishStubbsList($where);
				$order_list[$key]['goods_list']=$goods_list;
				$order_list[$key]['goods_count']=count($goods_list);
				
				$where = array();
				$where['store_id'] = $val['branch_id'];
                $branch_info = $model_store->getStoreInfoByID($val['branch_id']);
				$order_list[$key]['extend_branch']=$branch_info;
				
				$state_desc = '';
				$shipping_express_id = 0;
				$shipping_code = '';
				$if_cancel = false;
				$if_send = false;
				switch ($val['order_state']) {
				  case 0:
				    $state_desc = '已取消';
					break;
				  case 20:
				    $state_desc = '待发货';
					$if_cancel = true;
					break;
				  case 50:
				    $state_desc = '已发货';
					$shipping_express_id = $val['shipping_express_id'];
				    $shipping_code = $val['shipping_code'];
					break;
				  case 60:
				    $state_desc = '已完成';
					$shipping_express_id = $val['shipping_express_id'];
				    $shipping_code = $val['shipping_code'];
					break;
				}
				$order_list[$key]['state_desc']=$state_desc;
				$order_list[$key]['shipping_express_id']=$shipping_express_id;
				$order_list[$key]['shipping_code']=$shipping_code;	
				
				$order_list[$key]['if_cancel']=$if_cancel;	
				$order_list[$key]['if_send']=$if_send;		
			}
		}
		
		Tpl::output('order_list',$order_list);
		Tpl::output('show_page', $model_order->showpage());
		
		$this->profile_menu('index');
        Tpl::showpage('seller_branch_replenishorder.list');	
	}	
	
	/**
     * 分店调拔申请
     */
    public function replenishapplyOp() {
		$model_apply = Model('branch_apply');
		
		$where = array();
        $where['bp_store_id'] = $_SESSION['store_id'];
		
		$apply_list = $model_apply->getReplenishApplyList($where);
		Tpl::output('apply_list',$apply_list);
		Tpl::output('show_page', $model_apply->showpage());
		
		$this->profile_menu('replenishapply');
        Tpl::showpage('seller_branch_replenishapply.list');			
	}
	
	/**
     * 查看调拔申请信息
     */
    public function replenish_infoOp() {
		$bp_id = $_GET['id'];
		if (empty($bp_id)){
			showDialog('非法操作！', 'reload', 'succ');
		}
		$model_apply = Model('branch_apply');		
		
		$apply_info = $model_apply->getApplyInfo($bp_id);
		if (empty($apply_info) || !is_array($apply_info)){
			showDialog('没有找到审请信息！', 'reload', 'succ');
		}
		$id_list = array();
		$good_nums = array();
		foreach ($apply_info['goods_list'] as $k => $val) {
			if (!empty($val['p_id'])){
			    $id_list[] = $val['p_id'];
				$good_nums[$val['p_id']] = $val['nums'];
			}
		}
		$where = array();
		$where['goods_id'] = array('in',$id_list);
		$where['store_id'] = $_SESSION['store_id'];
		$goods_list = Model('goods')->getGoodsList($where,'goods_id,goods_name,goods_costprice,goods_price,goods_tradeprice,goods_storage,goods_image');
		foreach ($goods_list as $k => $val) {
			$goods_list[$k]['nums'] = $good_nums[$val['goods_id']];
		}				
		Tpl::output('goods_list',$goods_list);				
		Tpl::output('apply_info',$apply_info);		
		
		$this->profile_menu('replenishapply');
        Tpl::showpage('seller_branch_replenishapply.info', 'null_layout');	
	}
	
	/**
     * 审核调拔申请信息
     */
    public function replenish_verifyOp() {
		$bp_id = $_GET['id'];
		if (empty($bp_id)){
			showDialog('非法操作！', 'reload', 'succ');
		}
		$model_apply = Model('branch_apply');		
		
		$apply_info = $model_apply->getApplyInfo($bp_id);
		if (empty($apply_info) || !is_array($apply_info)){
			showDialog('没有找到审请信息！', 'reload', 'succ');
		}
		$p_list = array();
		$good_nums = array();
		$b_list = array();
		foreach ($apply_info['goods_list'] as $k => $val) {
			if (!empty($val['p_id'])){
			    $p_list[] = $val['p_id'];
				$good_nums[$val['p_id']] = $val['nums'];
				$b_list[$val['p_id']] = $val['b_id'];
			}
		}
		$where = array();
		$where['goods_id'] = array('in',$p_list);
		$where['store_id'] = $_SESSION['store_id'];
		$goods_list = Model('goods')->getGoodsList($where,'goods_id,goods_name,goods_costprice,goods_price,goods_tradeprice,goods_storage,goods_storage_alarm,goods_image');
		foreach ($goods_list as $k => $val) {
			$goods_list[$k]['nums'] = $good_nums[$val['goods_id']];
			$goods_list[$k]['b_id'] = $b_list[$val['goods_id']];
		}				
		Tpl::output('goods_list',$goods_list);				
		Tpl::output('apply_info',$apply_info);		
		
		$this->profile_menu('returnedapply');
        Tpl::showpage('seller_branch_replenishapply.verify', 'null_layout');			
	}
	
	/**
     * 审核调拔申请保存
     */
    public function replenish_saveOp() {
		$op_id = intval($_GET['op_id']);
		
		$data_goods = $_GET['data'];
        $data_goods_array = explode(',', $data_goods);
		if (!empty($data_goods_array) && is_array($data_goods_array)) {
		  $p_id=array();
		  $b_id=array();
		  $data_goods_info=array();
		  foreach ($data_goods_array as $k => $v ) {
			$goods_info = explode('|', $v);
			if (!empty($goods_info[0]) && !empty($goods_info[1]) && !empty($goods_info[2])){
				$p_id[]=$goods_info[0];
			    $b_id[]=$goods_info[2];
			    $data_goods_info[$goods_info[0]]['p_id']=$goods_info[0]; //总店商品编号
			    $data_goods_info[$goods_info[0]]['nums']=$goods_info[1]; //数量
			    $data_goods_info[$goods_info[0]]['b_id']=$goods_info[2]; //分店商品编号
			}
		  }
		}
		$bp_id = $_GET['bp_id'];
		$model_apply = Model('branch_apply');	
		$apply_info = $model_apply->getApplyInfo($bp_id);
		if (empty($apply_info) || empty($p_id) || !is_array($p_id)){
			showDialog('商品信息缺失，补货审核失败！', getReferer() ? getReferer() : 'index.php?act=seller_branch_stubbs&op=replenishapply', 'succ', '', 2);
		}
		
		$order_sn = '';
		if ($op_id==20 || $op_id==50){
		  $order_sn = $this->makeOrderSn($_SESSION['store_id']);
		}
		
		$update_info = array();
		$update_info['bp_dispose'] = ($op_id==50)?30:$op_id;
		$update_info['bp_views'] = 1;
		$update_info['bp_replyinfo'] = '';
		$update_info['bp_distime'] = TIMESTAMP;
		$update_info['bp_order_sn'] = $order_sn;
		$update_info['bp_admin'] = $_SESSION['seller_name'];
		$model_apply->editApplyInfo($update_info,array('bp_id'=>$bp_id));			
		
		//10：拒绝 20:同意(等待分店付款或等待发货) 50：已发货
		if ($op_id==20 || $op_id==50){
		    $model_goods = Model('goods');
		    $model_stubbs = Model('branch_stubbs');		
		    //总店商品信息
		    $where = array();
		    $where['goods_id'] = array('in',$p_id);
		    $where['store_id'] = $apply_info['bp_store_id'];
		    $goods_list = $model_goods->getGoodsList($where,'goods_id,goods_name,goods_commonid,goods_serial,goods_stcids,goods_price,goods_tradeprice,goods_image');
			//分店商品信息
		    $where = array();
		    $where['goods_id'] = array('in',$b_id);
		    $where['store_id'] = $apply_info['bp_branch_id'];
		    $b_list = $model_goods->getGoodsList($where,'goods_id,goods_name,goods_commonid,goods_serial,goods_stcids,goods_price,goods_costprice,goods_image,src_id');
			$b_list = array_under_reset($b_list,'src_id');
			
		    $returned_goods = array();
		    $returned_totals = 0;		
		    foreach ($goods_list as $k => $val) {				
		        $stubbs_goods = array();
				$stubbs_goods['store_id']=$_SESSION['store_id'];
			    $stubbs_goods['store_name']=$_SESSION['store_name'];
				$stubbs_goods['goods_id']=$val['goods_id'];
				$stubbs_goods['goods_name']=$val['goods_name'];				
				$stubbs_goods['goods_commonid']=$val['goods_commonid'];
				$stubbs_goods['goods_serial']=$val['goods_serial'];
				$stubbs_goods['goods_stcids']=$val['goods_stcids'];				
				$stubbs_goods['goods_price']=$val['goods_price'];
				$stubbs_goods['goods_tradeprice']=$val['goods_tradeprice'];//批发价
				$stubbs_goods['goods_image']=$val['goods_image'];				
			    $stubbs_goods['branch_id']=$apply_info['bp_branch_id'];
			    $stubbs_goods['branch_name']=$apply_info['bp_branch_name'];				
				$stubbs_goods['b_goods_id']=$b_list[$val['goods_id']]['goods_id'];
				$stubbs_goods['b_goods_name']=$b_list[$val['goods_id']]['goods_name'];				
				$stubbs_goods['b_goods_commonid']=$b_list[$val['goods_id']]['goods_commonid'];
				$stubbs_goods['b_goods_serial']=$b_list[$val['goods_id']]['goods_serial'];
				$stubbs_goods['b_goods_stcids']=$b_list[$val['goods_id']]['goods_stcids'];				
				$stubbs_goods['b_goods_price']=$b_list[$val['goods_id']]['goods_price'];
				$stubbs_goods['b_goods_costprice']=$b_list[$val['goods_id']]['goods_costprice'];//进货价
				$stubbs_goods['b_goods_image']=$b_list[$val['goods_id']]['goods_image'];				
			    $stubbs_goods['goods_num']=$data_goods_info[$val['goods_id']]['nums'];			    
			    $stubbs_goods['stubbs_time']=TIMESTAMP;
			    $stubbs_goods['stubbs_type']=0; //调拔类型 0：总店调拔 1：总店代发 2:分店退货
			    $stubbs_goods['stubbs_state']=($op_id==50)?1:0; //发货状态 0：待发货 1：已发货
			    $stubbs_goods['b_order_sn']=$order_sn;
			    $returned_goods[] = $stubbs_goods;
			    $returned_totals += $stubbs_goods['goods_num']*$stubbs_goods['goods_tradeprice'];
		    }		
		    $model_stubbs->insertAll($returned_goods);
			
            //补货订单
		    $branch_order = array();
		    $branch_order['order_sn'] = $order_sn;
		    $branch_order['store_id'] = $_SESSION['store_id'];
		    $branch_order['store_name'] = $_SESSION['store_name'];
		    $branch_order['branch_id'] = $apply_info['bp_branch_id'];
		    $branch_order['branch_name'] = $apply_info['bp_branch_name'];
		    $branch_order['goods_amount'] = $returned_totals;
		    $branch_order['shipping_fee'] = 0;
		    $branch_order['order_amount'] = $branch_order['goods_amount']+$branch_order['shipping_fee'];
		    $branch_order['add_time'] = TIMESTAMP;
		    $branch_order['payment_state'] = 0; //'付款状态：0(未付款) 1:已付款(分店付款 总店退款)'
		    $branch_order['payment_code'] = ''; //支付方式名称代码
		    $branch_order['payment_time'] = 0;
		    $branch_order['rcb_amount'] = 0; //充值卡支付金额
		    $branch_order['pd_amount'] = 0; //积分支付金额
		    $branch_order['bo_pay_content'] = ''; //支付备注
		    $branch_order['order_type'] = 0; //订单类型 0：总店调拔 1：总店代发 2:分店退货
		    $branch_order['order_state'] = $op_id;//订单状态：0(已取消)20:总店同意(分店待发货或分店待付款)30:分店已发货(总店待收货)40:总店已收货(确认待调拔)50:总店已发货(分店待收货确认)60:分店已收货(确认完成)
		    $branch_order['finnshed_time'] = ($op_id==60)?TIMESTAMP:0; //订单完成时间
		    $branch_order['shipping_time'] = ($op_id==50)?TIMESTAMP:0; //调拔配送时间
		    $branch_order['shipping_express_id'] = 0; //调拔配送公司ID
		    $branch_order['shipping_code'] = ''; //调拔物流单号
		    $branch_order['tui_shipping_time'] = 0; //退货配送时间
            $branch_order['tui_shipping_express_id'] = 0; //退货配送公司ID
		    $branch_order['tui_shipping_code'] = ''; //退货物流单号
		    $branch_order['bill_no'] = ''; //结算单编号
		    $branch_order['bo_admin'] = $_SESSION['seller_name']; //发货 退货 操作
		
		    $model_order = Model('branch_order');
		    $model_order->insert($branch_order);
			
			if ($op_id==50){
				foreach ($data_goods_info as $k => $val) {
					$model_goods->where(array('goods_id'=>$val['p_id'],'store_id'=>$_SESSION['store_id']))->setDec('goods_storage',$val['nums']);
				}
			}
		}
		
		showDialog(L('im_common_op_succ'), 'reload', 'succ');		
	}	
	
	/**
     * 删除调拔申请记录
     */
    public function replenish_delOp() {
        $model_apply = Model('branch_apply');
		$bp_id = $_GET['id'];     
		
        $apply_info = $model_apply->getApplyInfo($bp_id,'bp_order_sn');		
		if (!empty($apply_info)){// && !empty($apply_info['bp_order_sn'])
			$order_sn = $apply_info['bp_order_sn'];
			//Model('branch_order')->delOrderInfo(array('order_sn'=>$order_sn,'store_id'=>$_SESSION['store_id'],'order_type'=>2));
			//Model('branch_stubbs')->delStubbsInfo(array('b_order_sn'=>$order_sn,'stubbs_type'=>2));
			
			$where = array();
            $where['bp_id'] = $bp_id;
			$where['bp_type'] = 1;
            $where['bp_store_id'] = $_SESSION['store_id'];
			$return = $model_apply->delApplyInfo($where);
		}else{
			$return = false;
		}
        if ($return) {
            showDialog('补货申请删除成功！', 'reload', 'succ');
        } else {
            showDialog('补货申请删除失败！', '', 'error');
        }
    } 
	
	//调拔商品明细
    public function goodsdetailOp() {		
		$model_stubbs = Model('branch_stubbs');
		
		$where = array();
        $where['store_id'] = $_SESSION['store_id'];
        if (intval($_GET['stc_id']) > 0) {
            $where['goods_stcids'] = array('like', '%' . intval($_GET['stc_id']) . '%');
        }
		if (intval($_GET['buyer_id']) > 0) {
            $where['branch_id'] = intval($_GET['buyer_id']);
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
        $stubbs_list = $model_stubbs->getReplenishStubbsList($where,'*',20);
		
        Tpl::output('show_page', $model_stubbs->showpage());
        Tpl::output('stubbs_list', $stubbs_list);
		
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
		$branch_list = $model_branch->getStoreList($condition);	
		Tpl::output('branch_list',$branch_list);
		
		$this->profile_menu('detail');
        Tpl::showpage('seller_branch_stubbs.detail');        
    }
	
	/**
     * 删除商品调拔记录
     */
    public function drop_goodsOp() {
        $model_stubbs = Model('branch_stubbs');
        $where = array();
        $where['bs_id'] = $_GET['bs_id'];
        $where['store_id'] = $_SESSION['store_id'];
        $return = $model_stubbs->where($where)->delete();
        if ($return) {
            showDialog('调拔记录删除成功！', 'reload', 'succ');
        } else {
            showDialog('调拔记录删除失败！', '', 'error');
        }
    }
	
	/**
     * 分店退货单
     */
    public function returned_orderOp() {
		$model_order = Model('branch_order');
		
		$where = array();
        $where['store_id'] = $_SESSION['store_id'];
        if ($_GET['order_sn'] != '') {
            $where['order_sn'] = $_GET['order_sn'];
        }
        if ($_GET['buyer_name'] != '') {
            $where['branch_name'] = $_GET['buyer_name'];
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $where['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
		
		$order_list = $model_order->getReturnedOrderList($where);
		if (!empty($order_list) && is_array($order_list)){
			$model_stubbs = Model('branch_stubbs');	
			$model_store = Model('store');
			foreach ($order_list as $key => $val) {
				$where = array();
				$where['b_order_sn'] = $val['order_sn'];
				$where['store_id'] = $_SESSION['store_id'];
				$goods_list = $model_stubbs->getReturnedStubbsList($where);
				$order_list[$key]['goods_list']=$goods_list;
				$order_list[$key]['goods_count']=count($goods_list);
				
				$where = array();
				$where['store_id'] = $val['branch_id'];
                $branch_info = $model_store->getStoreInfoByID($val['branch_id']);
				$order_list[$key]['extend_branch']=$branch_info;
				
				$state_desc = '';
				$shipping_express_id = 0;
				$shipping_code = '';
				$if_cancel = false;
				$if_send = false;
				switch ($val['order_state']) {
				  case 0:
				    $state_desc = '已取消';
					break;
				  case 20:
				    $state_desc = '分店待发货';
					$if_cancel = true;
					break;
				  case 30:
				    $state_desc = '分店已发货';
					$shipping_express_id = $val['tui_shipping_express_id'];
				    $shipping_code = $val['tui_shipping_code'];
					break;
				  case 40:
				    $state_desc = '总店已收货';
					$shipping_express_id = $val['tui_shipping_express_id'];
				    $shipping_code = $val['tui_shipping_code'];
				    $if_send = true;
					break;
				  case 50:
				    $state_desc = '总店已发货';
					$shipping_express_id = $val['shipping_express_id'];
				    $shipping_code = $val['shipping_code'];
					break;
				  case 60:
				    $state_desc = '分店已收货';
					$shipping_express_id = $val['shipping_express_id'];
				    $shipping_code = $val['shipping_code'];
					break;
				}
				$order_list[$key]['state_desc']=$state_desc;
				$order_list[$key]['shipping_express_id']=$shipping_express_id;
				$order_list[$key]['shipping_code']=$shipping_code;	
				
				$order_list[$key]['if_cancel']=$if_cancel;	
				$order_list[$key]['if_send']=$if_send;		
			}
		}
		
		Tpl::output('order_list',$order_list);
		Tpl::output('show_page', $model_order->showpage());
		
		$this->profile_menu('re_order');
        Tpl::showpage('seller_branch_returnedorder.list');	
	}	
	
	/**
     * 分店退货申请
     */
    public function returnedapplyOp() {
		$model_apply = Model('branch_apply');
		
		$where = array();
        $where['bp_store_id'] = $_SESSION['store_id'];
		
		$apply_list = $model_apply->getReturnedApplyList($where);
		Tpl::output('apply_list',$apply_list);
		Tpl::output('show_page', $model_apply->showpage());
		
		$this->profile_menu('returnedapply');
        Tpl::showpage('seller_branch_returnedapply.list');			
	}
	
	/**
     * 查看退货申请信息
     */
    public function returned_infoOp() {
		$bp_id = $_GET['id'];
		if (empty($bp_id)){
			showDialog('非法操作！', 'reload', 'succ');
		}
		$model_apply = Model('branch_apply');		
		
		$apply_info = $model_apply->getApplyInfo($bp_id);
		if (empty($apply_info) || !is_array($apply_info)){
			showDialog('没有找到审请信息！', 'reload', 'succ');
		}
		$id_list = array();
		$good_nums = array();
		foreach ($apply_info['goods_list'] as $k => $val) {
			if (!empty($val['p_id'])){
			    $id_list[] = $val['p_id'];
				$good_nums[$val['p_id']] = $val['nums'];
			}
		}
		$where = array();
		$where['goods_id'] = array('in',$id_list);
		$where['store_id'] = $_SESSION['store_id'];
		$goods_list = Model('goods')->getGoodsList($where,'goods_id,goods_name,goods_costprice,goods_price,goods_tradeprice,goods_storage,goods_image');
		foreach ($goods_list as $k => $val) {
			$goods_list[$k]['nums'] = $good_nums[$val['goods_id']];
		}				
		Tpl::output('goods_list',$goods_list);				
		Tpl::output('apply_info',$apply_info);		
		
		$this->profile_menu('returned');
        Tpl::showpage('seller_branch_returnedapply.info', 'null_layout');	
	}
	
	/**
     * 审核退货申请信息
     */
    public function returned_verifyOp() {
		$bp_id = $_GET['id'];
		if (empty($bp_id)){
			showDialog('非法操作！', 'reload', 'succ');
		}
		$model_apply = Model('branch_apply');		
		
		$apply_info = $model_apply->getApplyInfo($bp_id);
		if (empty($apply_info) || !is_array($apply_info)){
			showDialog('没有找到审请信息！', 'reload', 'succ');
		}
		$p_list = array();
		$good_nums = array();
		$b_list = array();
		foreach ($apply_info['goods_list'] as $k => $val) {
			if (!empty($val['p_id'])){
			    $p_list[] = $val['p_id'];
				$good_nums[$val['p_id']] = $val['nums'];
				$b_list[$val['p_id']] = $val['b_id'];
			}
		}
		$where = array();
		$where['goods_id'] = array('in',$p_list);
		$where['store_id'] = $_SESSION['store_id'];
		$goods_list = Model('goods')->getGoodsList($where,'goods_id,goods_name,goods_costprice,goods_price,goods_tradeprice,goods_storage,goods_storage_alarm,goods_image');
		foreach ($goods_list as $k => $val) {
			$goods_list[$k]['nums'] = $good_nums[$val['goods_id']];
			$goods_list[$k]['b_id'] = $b_list[$val['goods_id']];
		}				
		Tpl::output('goods_list',$goods_list);				
		Tpl::output('apply_info',$apply_info);		
		
		$this->profile_menu('returnedapply');
        Tpl::showpage('seller_branch_returnedapply.verify', 'null_layout');			
	}
	
	/**
     * 审核退货申请保存
     */
    public function returned_saveOp() {
		$op_id = intval($_GET['op_id']);
		
		$data_goods = $_GET['data'];
        $data_goods_array = explode(',', $data_goods);
		if (!empty($data_goods_array) && is_array($data_goods_array)) {
		  $p_id=array();
		  $b_id=array();
		  $data_goods_info=array();
		  foreach ($data_goods_array as $k => $v ) {
			$goods_info = explode('|', $v);
			if (!empty($goods_info[0]) && !empty($goods_info[1]) && !empty($goods_info[2])){
				$p_id[]=$goods_info[0];
			    $b_id[]=$goods_info[2];
			    $data_goods_info[$goods_info[0]]['p_id']=$goods_info[0]; //总店商品编号
			    $data_goods_info[$goods_info[0]]['nums']=$goods_info[1]; //数量
			    $data_goods_info[$goods_info[0]]['b_id']=$goods_info[2]; //分店商品编号
			}
		  }
		}
		$bp_id = $_GET['bp_id'];
		$model_apply = Model('branch_apply');	
		$apply_info = $model_apply->getApplyInfo($bp_id);
		if (empty($apply_info) || empty($p_id) || !is_array($p_id)){
			showDialog('商品信息缺失，退货申核失败！', getReferer() ? getReferer() : 'index.php?act=seller_branch_stubbs&op=returnedapply', 'succ', '', 2);
		}
		
		$order_sn = '';
		if ($op_id==20 || $op_id==30){
		  $order_sn = $this->makeOrderSn($_SESSION['store_id']);
		}
		
		$update_info = array();
		$update_info['bp_dispose'] = $op_id;
		$update_info['bp_views'] = 1;
		$update_info['bp_replyinfo'] = '';
		$update_info['bp_distime'] = TIMESTAMP;
		$update_info['bp_order_sn'] = $order_sn;
		$update_info['bp_admin'] = $_SESSION['seller_name'];
		$model_apply->editApplyInfo($update_info,array('bp_id'=>$bp_id));			
		
		//10：拒绝 20:同意(等待分店付款或等待分店发货) 30：已处理
		if ($op_id==20 || $op_id==30){
		    $model_goods = Model('goods');
		    $model_stubbs = Model('branch_stubbs');		
		    //总退商品信息
		    $where = array();
		    $where['goods_id'] = array('in',$p_id);
		    $where['store_id'] = $apply_info['bp_store_id'];
		    $goods_list = $model_goods->getGoodsList($where,'goods_id,goods_name,goods_commonid,goods_serial,goods_stcids,goods_price,goods_tradeprice,goods_image');
			//分店商品信息
		    $where = array();
		    $where['goods_id'] = array('in',$b_id);
		    $where['store_id'] = $apply_info['bp_branch_id'];
		    $b_list = $model_goods->getGoodsList($where,'goods_id,goods_name,goods_commonid,goods_serial,goods_stcids,goods_price,goods_costprice,goods_image,src_id');
			$b_list = array_under_reset($b_list,'src_id');
			
		    $returned_goods = array();
		    $returned_totals = 0;		
		    foreach ($goods_list as $k => $val) {				
		        $stubbs_goods = array();
				$stubbs_goods['store_id']=$_SESSION['store_id'];
			    $stubbs_goods['store_name']=$_SESSION['store_name'];
				$stubbs_goods['goods_id']=$val['goods_id'];
				$stubbs_goods['goods_name']=$val['goods_name'];				
				$stubbs_goods['goods_commonid']=$val['goods_commonid'];
				$stubbs_goods['goods_serial']=$val['goods_serial'];
				$stubbs_goods['goods_stcids']=$val['goods_stcids'];				
				$stubbs_goods['goods_price']=$val['goods_price'];
				$stubbs_goods['goods_tradeprice']=$val['goods_tradeprice'];//批发价
				$stubbs_goods['goods_image']=$val['goods_image'];				
			    $stubbs_goods['branch_id']=$apply_info['bp_branch_id'];
			    $stubbs_goods['branch_name']=$apply_info['bp_branch_name'];				
				$stubbs_goods['b_goods_id']=$b_list[$val['goods_id']]['goods_id'];
				$stubbs_goods['b_goods_name']=$b_list[$val['goods_id']]['goods_name'];				
				$stubbs_goods['b_goods_commonid']=$b_list[$val['goods_id']]['goods_commonid'];
				$stubbs_goods['b_goods_serial']=$b_list[$val['goods_id']]['goods_serial'];
				$stubbs_goods['b_goods_stcids']=$b_list[$val['goods_id']]['goods_stcids'];				
				$stubbs_goods['b_goods_price']=$b_list[$val['goods_id']]['goods_price'];
				$stubbs_goods['b_goods_costprice']=$b_list[$val['goods_id']]['goods_costprice'];//进货价
				$stubbs_goods['b_goods_image']=$b_list[$val['goods_id']]['goods_image'];				
			    $stubbs_goods['goods_num']=$data_goods_info[$val['goods_id']]['nums'];			    
			    $stubbs_goods['stubbs_time']=TIMESTAMP;
			    $stubbs_goods['stubbs_type']=2; //调拔类型 0：总店调拔 1：总店代发 2:分店退货
			    $stubbs_goods['stubbs_state']=($op_id==30)?1:0; //发货状态 0：待发货 1：已发货
			    $stubbs_goods['b_order_sn']=$order_sn;
			    $returned_goods[] = $stubbs_goods;
			    $returned_totals += $stubbs_goods['goods_num']*$stubbs_goods['goods_tradeprice'];
		    }		
		    $model_stubbs->insertAll($returned_goods);
			
            //退货订单
		    $branch_order = array();
		    $branch_order['order_sn'] = $order_sn;
		    $branch_order['store_id'] = $_SESSION['store_id'];
		    $branch_order['store_name'] = $_SESSION['store_name'];
		    $branch_order['branch_id'] = $apply_info['bp_branch_id'];
		    $branch_order['branch_name'] = $apply_info['bp_branch_name'];
		    $branch_order['goods_amount'] = $returned_totals;
		    $branch_order['shipping_fee'] = 0;
		    $branch_order['order_amount'] = $branch_order['goods_amount']+$branch_order['shipping_fee'];
		    $branch_order['add_time'] = TIMESTAMP;
		    $branch_order['payment_state'] = 0; //'付款状态：0(未付款) 1:已付款(分店付款 总店退款)'
		    $branch_order['payment_code'] = ''; //支付方式名称代码
		    $branch_order['payment_time'] = 0;
		    $branch_order['rcb_amount'] = 0; //充值卡支付金额
		    $branch_order['pd_amount'] = 0; //积分支付金额
		    $branch_order['bo_pay_content'] = ''; //支付备注
		    $branch_order['order_type'] = 2; //订单类型 0：总店调拔 1：总店代发 2:分店退货
		    $branch_order['order_state'] = ($op_id==30)?60:20;//订单状态：0(已取消)20:总店同意(分店待发货或分店待付款)30:分店已发货(总店待收货)40:总店已收货(确认待调拔)50:总店已发货(分店待收货确认)60:分店已收货(确认完成)
		    $branch_order['finnshed_time'] = ($op_id==30)?TIMESTAMP:0; //订单完成时间
		    $branch_order['shipping_time'] = 0; //调拔配送时间
		    $branch_order['shipping_express_id'] = 0; //调拔配送公司ID
		    $branch_order['shipping_code'] = ''; //调拔物流单号
		    $branch_order['tui_shipping_time'] = ($op_id==30)?TIMESTAMP:0; //退货配送时间
            $branch_order['tui_shipping_express_id'] = 0; //退货配送公司ID
		    $branch_order['tui_shipping_code'] = ''; //退货物流单号
		    $branch_order['bill_no'] = ''; //结算单编号
		    $branch_order['bo_admin'] = ($op_id==30)?$_SESSION['seller_name']:''; //发货 退货 操作
		
		    $model_order = Model('branch_order');
		    $model_order->insert($branch_order);
			
			if ($op_id==30){
				foreach ($data_goods_info as $k => $val) {
					$model_goods->where(array('goods_id'=>$val['p_id'],'store_id'=>$_SESSION['store_id']))->setInc('goods_storage',$val['nums']);
					$model_goods->where(array('goods_id'=>$val['b_id'],'store_id'=>$apply_info['bp_branch_id']))->setDec('goods_storage',$val['nums']);
				}
			}			
		}
		
		showDialog(L('im_common_op_succ'), 'reload', 'succ');		
	}	
	
	/**
     * 删除退货申请记录
     */
    public function returned_delOp() {
        $model_apply = Model('branch_apply');
		$bp_id = $_GET['id'];     
		
        $apply_info = $model_apply->getApplyInfo($bp_id,'bp_order_sn');		
		if (!empty($apply_info)){// && !empty($apply_info['bp_order_sn'])
			$order_sn = $apply_info['bp_order_sn'];
			//Model('branch_order')->delOrderInfo(array('order_sn'=>$order_sn,'store_id'=>$_SESSION['store_id'],'order_type'=>2));
			//Model('branch_stubbs')->delStubbsInfo(array('b_order_sn'=>$order_sn,'stubbs_type'=>2));
			
			$where = array();
            $where['bp_id'] = $bp_id;
			$where['bp_type'] = 2;
            $where['bp_store_id'] = $_SESSION['store_id'];
			$return = $model_apply->delApplyInfo($where);
		}else{
			$return = false;
		}
        if ($return) {
            showDialog('退货申请删除成功！', 'reload', 'succ');
        } else {
            showDialog('退货申请删除失败！', '', 'error');
        }
    } 	
	
	/**
	 * 生成订单编号(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，该值会传给第三方支付接口
	 * 长度 =2位 + 10位 + 3位 + 3位  = 18位
	 * 1000个会员同一微秒提订单，重复机率为1/100
	 * @return string
	 */
	public function makeOrderSn($store_id) {
		return mt_rand(10,99)
		      . sprintf('%010d',time() - 946656000)
		      . sprintf('%03d', (float) microtime() * 1000)
		      . sprintf('%03d', (int) $store_id % 1000);
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
		$menu_array[0] = array('menu_key' => 'index', 'menu_name' => '调拔订单', 'menu_url' => urlShop('seller_branch_stubbs', 'index'));
		$menu_array[1] = array('menu_key' => 'stubbs', 'menu_name' => '商品调拔', 'menu_url' => urlShop('seller_branch_stubbs', 'stubbs'));
		$menu_array[2] = array('menu_key' => 'replenishapply', 'menu_name' => '补货申请'.$this->Replenish_appay_count, 'menu_url' => urlShop('seller_branch_stubbs', 'replenishapply'));
		$menu_array[3] = array('menu_key' => 'detail', 'menu_name' => '调拔明细', 'menu_url' => urlShop('seller_branch_stubbs', 'goodsdetail'));
		$menu_array[4] = array('menu_key' => 're_order', 'menu_name' => '退货单', 'menu_url' => urlShop('seller_branch_stubbs', 'returned_order'));
		$menu_array[5] = array('menu_key' => 'returnedapply', 'menu_name' => '退货申请'.$this->Returned_appay_count, 'menu_url' => urlShop('seller_branch_stubbs', 'returnedapply'));		
		
        Tpl::output ('member_menu', $menu_array);
        Tpl::output ('menu_key', $menu_key);
    }
}