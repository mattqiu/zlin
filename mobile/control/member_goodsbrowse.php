<?php
/**
 * 我的足迹
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

class member_goodsbrowseControl extends mobileMemberControl{
     public function __construct(){
        parent::__construct();
    }
    /**
     * 浏览历史列表
     */
    public function browse_listOp(){
        $goodsbrowse_list = Model('goods_browse')->getViewedGoodsList($this->member_info['member_id'],20);
	    output_data(array('goodsbrowse_list' => $goodsbrowse_list));
    }
    /**
     * 删除浏览历史
     */
    public function browse_clearallOp(){
        $return_arr = array();		
        $model = Model('goods_browse');
        if (trim($_GET['goods_id']) == 'all') {
            if ($model->delGoodsbrowse(array('member_id'=>$_SESSION['member_id']))){
                $return_arr = array('done'=>'true');
            } else {
                $return_arr = array('done'=>'false','msg'=>'删除失败');
            }
        } elseif (intval($_GET['goods_id']) >= 0) {			
            $goods_id = intval($_GET['goods_id']);
            if ($model->delGoodsbrowse(array('member_id'=>$_SESSION['member_id'],'goods_id'=>$goods_id))){				
                $return_arr = array('done'=>'true');
            } else {
                $return_arr = array('done'=>'false','msg'=>'删除失败');
            }
        } else {						
            $return_arr = array('done'=>'false','msg'=>'参数错误');
        }
        echo json_encode($return_arr);
    }
}