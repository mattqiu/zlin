<?php
/**
 * 商品分类管理
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

class goods_classControl extends SystemControl {
    private $links = array(
        array('url'=>'act=goods_class&op=goods_class', 'lang'=>'im_manage'),
        array('url'=>'act=goods_class&op=goods_class_import', 'lang'=>'goods_class_index_import'),
        array('url'=>'act=goods_class&op=tag', 'lang'=>'goods_class_index_tag')
    );
    
    /**
     * 构造函数
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
        Language::read('goods_class');
    }
	
    /**
     * 分类管理
     * @access public
     * @return void
     */
    public function indexOp() {
        $this->goods_classOp();
    }
    public function goods_classOp() {
  		$goodsClassModel= Model('goods_class');
    	$gcId = $_GET['gc_id'] ? intval($_GET['gc_id']) : 0;
        $parentId = 0;
        $classList = array();
        $treeClassList = $goodsClassModel->getTreeClassList(3);
        if(is_array($treeClassList)) {
            foreach($treeClassList as $k => $v) {
                if($v['gc_parent_id'] == $gcId) {
                    if($treeClassList[$k+1]['deep'] > $v['deep']) {
                        $v['have_child'] = 1;
                    }
                    $classList[] = $v;
                }
                if($v['gc_id'] == $gcId) {
                    $parentId = $v['gc_parent_id'];
                    $parentName = $v['gc_name'];
                }
            }
        }
        if($gcId > 0) {
            if($parentId == 0) {
                $title = '"'.$parentName.'"的下级列表(二级)';
                $deep = 2;
            } else {
                foreach($treeClassList as $v) {
                    if($v['gc_id'] == $parentId) {
                        $grandParentsName = $v['gc_name'];
                    }
                }
                $title = '"'.$grandParentsName.' - '.$parentName.'"的下级列表(三级)';
                $deep = 3;
            }
            Tpl::output('deep', 3);
            Tpl::output('title', $title);
            Tpl::output('gc_id', $gcId);
            Tpl::output('parent_id', $parentId);
            Tpl::output('class_list', $classList);
			Tpl::setDirquna('shop');
            Tpl::showpage('goods_class.child_list');
        } else {
            Tpl::output('class_list', $classList);
            Tpl::output('top_link', $this->sublink($this->links, 'goods_class'));
			Tpl::setDirquna('shop');
            Tpl::showpage('goods_class.index');
        }
    }

    /**
     * 添加分类
     * @access public
     * @return void
     */
    public function goods_class_addOp() {
      	$goodsClassModel = Model('goods_class');
        if(chksubmit()) {
            $Validate = new Validate();
            $Validate->validateparam = array(
                array('input'=>$_POST['gc_name'], 'require'=>'true', 'message'=>L('goods_class_add_name_null')),
                array('input'=>$_POST['gc_sort'], 'require'=>'true', 'validator'=>'Number', 'message'=>L('goods_class_add_sort_int'))
            );
            $error = $Validate->validate();
            if($error != '') {
                showMessage($error);
            } else {
            	$data = array(
            		'gc_name' => trim($_POST['gc_name']),
            		'type_id' => intval($_POST['t_id']),
            		'type_name' => trim($_POST['t_name']),
            		'gc_parent_id' => intval($_POST['gc_parent_id']),
            		'commis_rate' => intval($_POST['commis_rate']),
            		'gc_sort' => intval($_POST['gc_sort']),
            		'gc_virtual' => intval($_POST['gc_virtual']),
					'gc_recommend' => intval($_POST['gc_recommend'])
            	);
                $result = $goodsClassModel->addGoodsClass($data);
                if($result) {
                    if($data['gc_parent_id'] == 0) {
                        if(!empty($_FILES['pic']['name'])) {
                            $UploadFile = new UploadFile();
                            $UploadFile->set('default_dir', ATTACH_COMMON);
                            $UploadFile->set('file_name', 'category-pic-'.$result.'.jpg');
                            $UploadFile->upfile('pic');
                        }						
                    }
                    $url = array(
                        array(
                            'url' => 'index.php?act=goods_class&op=goods_class_add&gc_parent_id='.$data['gc_parent_id'],
                            'msg' => L('goods_class_add_again')
                        ),
                        array(
                       		'url' => 'index.php?act=goods_class&op=goods_class',
                            'msg' => L('goods_class_add_back_to_list')
                        )
                    );
                    $this->log(L('im_add,goods_class_index_class').'['.$data['gc_name'].']', 1);
                    showMessage(L('im_common_save_succ'), $url);
                } else {
                    showMessage(L('im_common_save_fail'));
                }
            }
        } else {
        	$gcList = array();
        	$parentList = $goodsClassModel->getTreeClassList(2);
        	if(is_array($parentList)) {
        		foreach($parentList as $k => $v) {
        			$parentList[$k]['gc_name'] = str_repeat("&nbsp;", $v['deep']*2).$v['gc_name'];
        			if($v['deep'] == 1) {
        				$gcList[$k] = $v;
        			}
        		}
        	}
        	Tpl::output('gc_list', $gcList);
        	$tList = array();
        	$typeList = Model('type')->typeList(array('order'=>'type_sort asc'), '', 'type_id,type_name,class_id,class_name');
        	if(is_array($typeList) && !empty($typeList)) {
        		foreach($typeList as $k => $v) {
        			$tList[$v['class_id']]['type'][$k] = $v;
        			$tList[$v['class_id']]['name'] = $v['class_name'] == '' ? L('im_default') : $v['class_name'];
        		}
        	}
        	ksort($tList);
        	Tpl::output('type_list', $tList);
        	Tpl::output('gc_parent_id', intval($_GET['gc_parent_id']));
        	Tpl::output('parent_list', $parentList);
        	Tpl::output('top_link', $this->sublink($this->links, 'goods_class_add'));
        	Tpl::setDirquna('shop');
        	Tpl::showpage('goods_class.add');
        }
    }

    /**
     * 编辑分类
     * @access public
     * @return void
     */
    public function goods_class_editOp() {
        $goodsClassModel = Model('goods_class');
        if(chksubmit()) {
            $Validate = new Validate();
            $Validate->validateparam = array(
                array('input'=>$_POST['gc_name'], 'require'=>'true', 'message'=>L('goods_class_add_name_null')),
                array('input'=>$_POST['commis_rate'], 'require'=>'true', 'validator'=>'range', 'max'=>100, 'min'=>0, 'message'=>L('goods_class_add_commis_rate_error')),
                array('input'=>$_POST['gc_sort'], 'require'=>'true', 'validator'=>'Number', 'message'=>L('goods_class_add_sort_int'))
            );
            $error = $Validate->validate();
            if($error != '') {
                showMessage($error);
            }
            $data = array(
            	'gc_name' => trim($_POST['gc_name']),
            	'type_id' => intval($_POST['t_id']),
            	'type_name' => trim($_POST['t_name']),
            	'commis_rate' => intval($_POST['commis_rate']),
            	'gc_sort' => intval($_POST['gc_sort']),
            	'gc_virtual' => intval($_POST['gc_virtual']),
				'gc_recommend' => intval($_POST['gc_recommend'])
            );
            $result = $goodsClassModel->editGoodsClass($data, array('gc_id'=>intval($_POST['gc_id'])));
            if(!$result) {
                $this->log(L('im_edit,goods_class_index_class').'['.$data['gc_name'].']', 0);
                showMessage(L('goods_class_batch_edit_fail'));
            }
            if(!empty($_FILES['pic']['name'])) {
                $UploadFile = new UploadFile();
                $UploadFile->set('default_dir', ATTACH_COMMON);
                $UploadFile->set('file_name', 'category-pic-'.$data['gc_id'].'.jpg');
                $UploadFile->upfile('pic');
            }

            /* 检测是否需要关联自己操作，统一查询子分类 */
            $gcIds = array();
            if(intval($_POST['t_commis_rate']) || intval($_POST['t_associated']) || intval($_POST['t_gc_virtual'])) {
                $gcIdList = $goodsClassModel->getChildClass(intval($_POST['gc_id']));
                if(is_array($gcIdList) && !empty($gcIdList)) {
                    foreach($gcIdList as $v) {
                        $gcIds[] = $v['gc_id'];
                    }
                }
            }

            /* 更新该分类下子分类的所有分佣比例 */
            if(intval($_POST['t_commis_rate']) && !empty($gcIds)) {
                $goodsClassModel->editGoodsClass(array('commis_rate'=>$data['commis_rate']), array('gc_id'=>array('in', $gcIds)));
            }

            /* 更新该分类下子分类的所有类型 */
            if(intval($_POST['t_associated']) && !empty($gcIds)) {
                $goodsClassModel->editGoodsClass(array('type_id'=>$data['type_id'], 'type_name'=>$data['type_name']), array('gc_id'=>array('in', $gcIds)));
            }

            /* 虚拟商品 */
            if(intval($_POST['t_gc_virtual']) && !empty($gcIds)) {
                $goodsClassModel->editGoodsClass(array('gc_virtual'=>$data['gc_virtual']), array('gc_id'=>array('in', $gcIds)));
            }

            $url = array(
                array(
           			'url' => 'index.php?act=goods_class&op=goods_class_edit&gc_id='.intval($_POST['gc_id']),
                    'msg' => L('goods_class_batch_edit_again')
                ),
                array(
                    'url' => 'index.php?act=goods_class&op=goods_class',
                    'msg' => L('goods_class_add_back_to_list')
                )
            );
            $this->log(L('im_edit,goods_class_index_class').'['.$data['gc_name'].']', 1);
            showMessage(L('goods_class_batch_edit_ok'), $url, 'html', 'succ', 1, 5000);
        } else {
        	$goodsClassInfo = $goodsClassModel->getGoodsClassInfoById(intval($_GET['gc_id']));
        	if(empty($goodsClassInfo)) {
        		showMessage(L('goods_class_batch_edit_paramerror'));
        	}
        	$picName = BASE_UPLOAD_PATH.'/'.ATTACH_COMMON.'/category-pic-'.$goodsClassInfo['gc_id'].'.jpg';
        	if(file_exists($picName)) {
        		$goodsClassInfo['pic'] = UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/category-pic-'.$goodsClassInfo['gc_id'].'.jpg';
        	}
        	Tpl::output('class_array', $goodsClassInfo);
        	$gcList = $goodsClassModel->getGoodsClassListByParentId(0);
        	Tpl::output('gc_list', $gcList);
        	$parentList = $goodsClassModel->getTreeClassList(2);
        	if(is_array($parentList)) {
        		foreach($parentList as $k => $v) {
        			$parentList[$k]['gc_name'] = str_repeat("&nbsp;", $v['deep']*2).$v['gc_name'];
        		}
        	}
        	Tpl::output('parent_list', $parentList);
        	$tList = array();
        	$typeList = Model('type')->typeList(array('order'=>'type_sort asc'), '', 'type_id,type_name,class_id,class_name');
        	if(is_array($typeList) && !empty($typeList)) {
        		foreach($typeList as $k => $v) {
        			$tList[$v['class_id']]['type'][$k] = $v;
        			$tList[$v['class_id']]['name'] = $v['class_name'] == '' ? L('im_default') : $v['class_name'];
        		}
        	}
        	ksort($tList);
        	Tpl::output('type_list', $tList);
        	$this->links[] = array('url'=>'act=goods_class&op=goods_class_edit', 'lang'=>'im_edit');
        	Tpl::output('top_link', $this->sublink($this->links, 'goods_class_edit'));
        	Tpl::setDirquna('shop');
        	Tpl::showpage('goods_class.edit');
        }
    }
    
    /**
     * 编辑分类导航
     * @access public
     * @return void
     */
    public function goods_class_nav_editOp() {
    	$goodsClassModel = Model('goods_class');
    	$goodsClassInfo = $goodsClassModel->getGoodsClassInfoById(intval($_REQUEST['gc_id']));
    	if(empty($goodsClassInfo)) {
    		showMessage('该商品分类不存在');
    	}
    	if($goodsClassInfo['gc_parent_id'] != '0') {
    		showMessage('只有一级商品分类才能设置分类导航');
    	}
    	if(chksubmit()) {
    		$classIds = '';
    		if(is_array($_POST['class_id']) && !empty($_POST['class_id'])) {
    			foreach($_POST['class_id'] as $key => $value) {
    				if($value) {
    					$classIds .= $value.',';
    				}
    			}
    		}
    		$brandIds = '';
    		if(is_array($_POST['brand_id']) && !empty($_POST['brand_id'])) {
    			foreach($_POST['brand_id'] as $key => $value) {
    				if($value) {
    					$brandIds .= $value.',';
    				}
    			}
    		}
    		$data = array(
    			'gc_alias_name' => trim($_POST['gc_alias_name']),
  				'class_ids' => trim($classIds, ','),
    			'brand_ids' => trim($brandIds, ','),
    			'adv1_link' => trim($_POST['adv1_link']),
    			'adv2_link' => trim($_POST['adv2_link'])
    		);
    		if(!empty($_FILES['adv1']['name'])) {
    			$UploadFile = new UploadFile();
    			$UploadFile->set('default_dir', ATTACH_ADV);
    			if($UploadFile->upfile('adv1')) {
    				$data['adv1'] = $UploadFile->file_name;
    			}
    		}
    		if(!empty($_FILES['adv2']['name'])) {
    			$UploadFile = new UploadFile();
    			$UploadFile->set('default_dir', ATTACH_ADV);
    			if($UploadFile->upfile('adv2')) {
    				$data['adv2'] = $UploadFile->file_name;
    			}
    		}
			if (!empty($_FILES['image']['name'])) {//上传图片
            	$upload = new UploadFile();
                $upload->set('default_dir',ATTACH_COMMON);
                $upload->set('file_name','category-image-'.$_POST['gc_id'].'.jpg');
            	$upload->upfile('image');
            }
    		$result = $goodsClassModel->editGoodsClass($data, array('gc_id'=>intval($_POST['gc_id'])));
    		if($result) {
    			$url = array(
    				array(
    					'url' => 'index.php?act=goods_class&op=goods_class_nav_edit&gc_id='.intval($_POST['gc_id']),
    					'msg' => L('goods_class_batch_edit_again')
    				),
    				array(
    					'url' => 'index.php?act=goods_class&op=goods_class',
    					'msg' => L('goods_class_add_back_to_list')
    				)
    			);
    			$this->log(L('im_edit,goods_class_index_class').'['.$goodsClassInfo['gc_name'].']', 1);
    			showMessage(L('goods_class_batch_edit_ok'), $url, 'html', 'succ', 1, 5000);
    		} else {
    			showMessage(L('goods_class_batch_edit_fail'));
    		}
    	} else {
    		$classListAll = $goodsClassModel->getGoodsClassListAll();
    		$classList = $this->listToTree($classListAll, 'gc_id', 'gc_parent_id', '_child', $goodsClassInfo['gc_id']);
    		Tpl::output('class_list', $classList);
    		$gcList = $goodsClassModel->getGoodsClassListByParentId(0);
    		Tpl::output('gc_list', $gcList);
    		$brandList = array();
    		$brandPassedList = Model('brand')->getBrandPassedList(array());
    		if(is_array($brandPassedList) && !empty($brandPassedList)) {
    			foreach($brandPassedList as $key => $value) {
    				$brandList[$value['class_id']]['brand'][$key] = $value;
    				$brandList[$value['class_id']]['name'] = $value['brand_class'] == '' ? L('im_default') : $value['brand_class'];
    			}
    		}
    		ksort($brandList);
			
			$pic_name = BASE_UPLOAD_PATH.'/'.ATTACH_COMMON.'/category-image-'.$goodsClassInfo['gc_id'].'.jpg';
	        if (file_exists($pic_name)) {
	            $goodsClassInfo['image'] = UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/category-image-'.$goodsClassInfo['gc_id'].'.jpg';
	        }
		
    		Tpl::output('brand_list', $brandList);
    		Tpl::output('class_info', $goodsClassInfo);
    		Tpl::setDirquna('shop');
    		Tpl::showpage('goods_class_nav.edit');
    	}
    }
    
    /**
     * 删除分类
     * @access public
     * @return json
     */
    public function goods_class_delOp() {
    	if($_GET['id'] != '') {
    		Model('goods_class')->delGoodsClassByGcIdString($_GET['id']);
    		$this->log(L('im_delete,goods_class_index_class').'[ID:'.$_GET['id'].']', 1);
    		exit(json_encode(array('state'=>true, 'msg'=>'删除成功')));
    	} else {
    		exit(json_encode(array('state'=>false, 'msg'=>'删除失败')));
    	}
    }

    /**
     * 导入分类
     * @access public
     * @return void
     */
    public function goods_class_importOp() {
        $goodsClassModel = Model('goods_class');
        if(chksubmit()) {
            $fileType = end(explode('.', $_FILES['csv']['name']));
            if(!empty($_FILES['csv']) && !empty($_FILES['csv']['name']) && $fileType == 'csv') {
                $fp = @fopen($_FILES['csv']['tmp_name'], 'rb');
                while(!feof($fp)) {
                    $data = trim(fgets($fp, 4096));
                    switch(strtoupper($_POST['charset'])) {
                        case 'UTF-8':
                            if(strtoupper(CHARSET) !== 'UTF-8') {
                                $data = iconv('UTF-8', strtoupper(CHARSET), $data);
                            }
                            break;
                        case 'GBK':
                            if(strtoupper(CHARSET) !== 'GBK') {
                                $data = iconv('GBK', strtoupper(CHARSET), $data);
                            }
                            break;
                    }
                    if(!empty($data)) {
                        $data = str_replace('"', '', $data);
                        $tmpArray = explode(',', $data);
                        if($tmpArray[0] == 'sort_order') {
                        	continue;
                        }
                        $tmpDeep = 'parent_id_'.(count($tmpArray)-1);
                        $gcId = $goodsClassModel->addGoodsClass(array(
                        	'gc_sort' => $tmpArray[0],
                        	'gc_parent_id' => $$tmpDeep,
                        	'gc_name' => $tmpArray[count($tmpArray)-1]
                        ));
                        $tmp = 'parent_id_'.count($tmpArray);
                        $$tmp = $gcId;
                    }
                }
                $this->log(L('goods_class_index_import,goods_class_index_class'), 1);
                showMessage(L('im_common_op_succ'), 'index.php?act=goods_class&op=goods_class');
            } else {
                showMessage(L('goods_class_import_csv_null'));
            }
        } else {
        	Tpl::output('top_link', $this->sublink($this->links, 'goods_class_import'));
        	Tpl::setDirquna('shop');
        	Tpl::showpage('goods_class.import');
        }
    }

    /**
     * 导出分类
     * @access public
     * @return void
     */
    public function goods_class_exportOp() {
 		$classList = Model('goods_class')->getTreeClassList();
        @header('Content-type: application/unknown');
        @header('Content-Disposition: attachment; filename=goods_class.csv');
        if(is_array($classList)) {
            foreach($classList as $k => $v) {
                $tmp = array();
                $tmp['gc_sort'] = $v['gc_sort'];
                for($i = 1; $i <= ($v['deep']-1); $i++) {
                    $tmp[] = '';
                }
                $tmp['gc_name'] = $v['gc_name'];
                $tmpLine = iconv('UTF-8', 'GB2312//IGNORE', join(',', $tmp));
                $tmpLine = str_replace("\r\n", '', $tmpLine);
                echo $tmpLine."\r\n";
            }
        }
        $this->log(L('goods_class_index_export,goods_class_index_class'), 1);
        exit;
    }

    /**
     * TAG列表
     * @access public
     * @return void
     */
    public function tagOp() {
        Tpl::output('top_link', $this->sublink($this->links, 'tag'));
		Tpl::setDirquna('shop');
        Tpl::showpage('goods_class_tag.index');
    }

    /**
     * 输出TAG XML数据
     * @access public
     * @return xml
     */
    public function get_xmlOp() {
        $goodsClassTagModel = Model('goods_class_tag');
        $condition = array();
        if($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%'.$_POST['query'].'%');
        }
        $order = '';
        $param = array('gc_tag_id', 'gc_tag_name', 'gc_tag_value', 'gc_id_1', 'gc_id_2', 'gc_id_3');
        if(in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'].' '.$_POST['sortorder'];
        }
        $tag_list = $goodsClassTagModel->getTagList($condition, $_POST['rp'], '*', $order);
        $data = array();
        $data['now_page'] = $goodsClassTagModel->shownowpage();
        $data['total_num'] = $goodsClassTagModel->gettotalnum();
        foreach((array)$tag_list as $value) {
            $param = array();
            $operation = "<a class='btn blue' href='javascript:void(0)' onclick=\"fg_edit(".$value['gc_tag_id'].")\"><i class='fa fa-pencil-square-o'></i>编辑</a>";
            $param['operation'] = $operation;
            $param['gc_tag_id'] = $value['gc_tag_id'];
            $param['gc_tag_name'] = $value['gc_tag_name'];
            $param['gc_tag_value'] = $value['gc_tag_value'];
            $param['gc_id_1'] = $value['gc_id_1'];
            $param['gc_id_2'] = $value['gc_id_2'];
            $param['gc_id_3'] = $value['gc_id_3'];
            $data['list'][$value['gc_tag_id']] = $param;
        }
        echo Tpl::flexigridXML($data);
        exit();
    }

	/**
	 * 重置TAG
	 * @access public
	 * @return void
	 */
	public function tag_resetOp(){
		$model_class = Model('goods_class');
		$model_class_tag = Model('goods_class_tag');
		$return = $model_class_tag->clearTag();
		if(!$return) {
			showMessage(L('goods_class_reset_tag_fail'), 'index.php?act=goods_class&op=tag');
		}
		$goods_class = $model_class->getTreeClassList(3);
		if(is_array($goods_class) && !empty($goods_class)) {
			$goods_class_array = array();
			foreach($goods_class as $val) {
				if($val['gc_parent_id'] == 0) {
					$goods_class_array[$val['gc_id']]['gc_name'] = $val['gc_name'];
					$goods_class_array[$val['gc_id']]['gc_id'] = $val['gc_id'];
					$goods_class_array[$val['gc_id']]['type_id'] = $val['type_id'];
				}else {
					if(isset($goods_class_array[$val['gc_parent_id']])) {
						$goods_class_array[$val['gc_parent_id']]['sub_class'][$val['gc_id']]['gc_name']	= $val['gc_name'];
						$goods_class_array[$val['gc_parent_id']]['sub_class'][$val['gc_id']]['gc_id'] = $val['gc_id'];
						$goods_class_array[$val['gc_parent_id']]['sub_class'][$val['gc_id']]['gc_parent_id'] = $val['gc_parent_id'];
						$goods_class_array[$val['gc_parent_id']]['sub_class'][$val['gc_id']]['type_id']	= $val['type_id'];
					} else {
						foreach($goods_class_array as $v) {
							if(isset($v['sub_class'][$val['gc_parent_id']])) {
								$goods_class_array[$v['sub_class'][$val['gc_parent_id']]['gc_parent_id']]['sub_class'][$val['gc_parent_id']]['sub_class'][$val['gc_id']]['gc_name']	= $val['gc_name'];
								$goods_class_array[$v['sub_class'][$val['gc_parent_id']]['gc_parent_id']]['sub_class'][$val['gc_parent_id']]['sub_class'][$val['gc_id']]['gc_id'] = $val['gc_id'];
								$goods_class_array[$v['sub_class'][$val['gc_parent_id']]['gc_parent_id']]['sub_class'][$val['gc_parent_id']]['sub_class'][$val['gc_id']]['type_id']	= $val['type_id'];
							}
						}
					}
				}
			}
			$return = $model_class_tag->tagAdd($goods_class_array);
			if($return) {
				$this->log(L('im_reset').'tag', 1);
				showMessage(L('im_common_op_succ'), 'index.php?act=goods_class&op=tag');
			} else {
				showMessage(L('im_common_op_fail'), 'index.php?act=goods_class&op=tag');
			}
		} else {
			showMessage(L('goods_class_reset_tag_fail_no_class'), 'index.php?act=goods_class&op=tag');
		}
	}

   	/**
	 * 更新TAG名称
	 * @access public
	 * @return void
	 */
	public function tag_updateOp() {
		$model_class = Model('goods_class');
		$model_class_tag = Model('goods_class_tag');
		$tag_list = $model_class_tag->getTagList(array(), '', 'gc_tag_id,gc_id_1,gc_id_2,gc_id_3');
		if(is_array($tag_list) && !empty($tag_list)) {
			foreach($tag_list as $val) {
				$in_gc_id = array();
				if($val['gc_id_1'] != '0') {
					$in_gc_id[] = $val['gc_id_1'];
				}
				if($val['gc_id_2'] != '0') {
					$in_gc_id[] = $val['gc_id_2'];
				}
				if($val['gc_id_3'] != '0') {
					$in_gc_id[] = $val['gc_id_3'];
				}
				$gc_list = $model_class->getGoodsClassListByIds($in_gc_id);
				$update_tag	= array();
				if(isset($gc_list['0']['gc_id']) && $gc_list['0']['gc_id'] != '0') {
					$update_tag['gc_id_1'] = $gc_list['0']['gc_id'];
					$update_tag['gc_tag_name'] .= $gc_list['0']['gc_name'];
				}
				if(isset($gc_list['1']['gc_id']) && $gc_list['1']['gc_id'] != '0') {
					$update_tag['gc_id_2'] = $gc_list['1']['gc_id'];
					$update_tag['gc_tag_name'] .= "&nbsp;&gt;&nbsp;".$gc_list['1']['gc_name'];
				}
				if(isset($gc_list['2']['gc_id']) && $gc_list['2']['gc_id'] != '0') {
					$update_tag['gc_id_3'] = $gc_list['2']['gc_id'];
					$update_tag['gc_tag_name'] .= "&nbsp;&gt;&nbsp;".$gc_list['2']['gc_name'];
				}
				unset($gc_list);
				$update_tag['gc_tag_id'] = $val['gc_tag_id'];
				$return = $model_class_tag->updateTag($update_tag);
				if(!$return) {
					showMessage(L('im_common_op_fail'), 'index.php?act=goods_class&op=tag');
				}
			}
			$this->log(L('im_update').'tag', 1);
			showMessage(L('im_common_op_succ'), 'index.php?act=goods_class&op=tag');
		} else {
			showMessage(L('goods_class_update_tag_fail_no_class'), 'index.php?act=goods_class&op=tag');
		}
	}
	
    public function tag_editOp() {
        $model_class_tag = Model('goods_class_tag');
        if($_POST['form_submit']) {
            $return = $model_class_tag->updateTag(array('gc_tag_id'=>$_POST['id'], 'gc_tag_value'=>$_POST['tag_value']));
            if($return) {
                $this->log('编辑TAG值成功['.$_POST['attr_name'].']', 1);
                showDialog('编辑成功', '', 'succ', 'CUR_DIALOG.close();$("#flexigrid").flexReload()');
            } else {
                showDialog('编辑失败', '', '', '', 'CUR_DIALOG.close();');
            }
        }
        $id = $_GET['id'];
        $tag_list = $model_class_tag->getTagList(array('gc_tag_id'=>$id));
        Tpl::output('tag_info', $tag_list[0]);
		Tpl::setDirquna('shop');
        Tpl::showpage('goods_class_tag.edit', 'null_layout');
    }

    /**
     * ajax操作
     * @access public
     * @return json
     */
    public function ajaxOp() {
        switch($_GET['branch']) {
            case 'gc_name':
                $model_class = Model('goods_class');
                $class_array = $model_class->getGoodsClassInfoById(intval($_GET['id']));
                $condition['gc_name'] = trim($_GET['value']);
                $condition['gc_parent_id'] = $class_array['gc_parent_id'];
                $condition['gc_id'] = array('neq', intval($_GET['id']));
                $class_list = $model_class->getGoodsClassList($condition);
                if(empty($class_list)) {
                    $where = array('gc_id'=>intval($_GET['id']));
                    $update_array = array();
                    $update_array['gc_name'] = trim($_GET['value']);
                    $model_class->editGoodsClass($update_array, $where);
                    $return = true;
                } else {
                    $return = false;
                }
                exit(json_encode(array('result'=>$return)));
                break;
            case 'gc_sort':
                $model_class = Model('goods_class');
                $where = array('gc_id'=>intval($_GET['id']));
                $update_array = array();
                $update_array['gc_sort'] = $_GET['value'];
                $model_class->editGoodsClass($update_array, $where);
                $return = 'true';
                exit(json_encode(array('result'=>$return)));
                break;
            case 'check_class_name':
                $model_class = Model('goods_class');
                $condition['gc_name'] = trim($_GET['gc_name']);
                $condition['gc_parent_id'] = intval($_GET['gc_parent_id']);
                $condition['gc_id'] = array('neq', intval($_GET['gc_id']));
                $class_list = $model_class->getGoodsClassList($condition);
                if(empty($class_list)) {
                    exit('true');
                } else {
                    exit('false');
                }
                break;
		}
    }
    
    private function listToTree($list = array(), $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
	    $tree = array();
	    if(is_array($list)) {
	        $refer = array();
	        foreach($list as $key => $data) {
	            $refer[$data[$pk]] = &$list[$key];
	        }
	        foreach($list as $key => $data) {
	            $parentId = $data[$pid];
	            if($parentId == $root) {
	                $tree[] = &$list[$key];
	            } else {
	                if(isset($refer[$parentId])) {
	                    $parent = &$refer[$parentId];
	                    $parent[$child][] = &$list[$key];
	                }
	            }
	        }
	    }
	    return $tree;
	}
}