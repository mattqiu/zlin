<?php

/**
 * 商家订货
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */

defined('InIMall') or exit('Access Invalid!');



class store_orderingControl extends mobileHomeControl{

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取订单列表
     * 
     * @return [type] [description]
     */
    /* 
//我的订单 
    请求参数：
        size       传空值 
        state_type 订单状态 表示未提交:state_new
        order      排序字段 零售额:total_price 货号:goods_serial 数量：num
        sc         排序方式   正序：asc 倒序 ：desc
        page       每页显示条数  默认10
        curpage    当前页码  分页用
    请求示例：
        http://zlin.test.com/mobile/index.php?act=store_ordering&op=ordering_list&state_type=state_new&size=&order=total_price&sc=asc&page=3&curpage=1
//总排行榜 
    请求参数：
        state_type 订单状态     不传默认所有订单
        order      排序字段:    固定 num   表示按数量排序
        sc         排序方式:    固定 desc  倒序
        size       类型    ：   固定 total 表示总排行榜数据
        page       每页显示条数 固定传0 表示获取所有数据 前台进行分页处理
    请求示例：
        http://zlin.test.com/mobile/index.php?act=store_ordering&op=ordering_list&state_type=&size=total&order=num&sc=desc&page=0&curpage=1
//订单管理
    已完成订单：
http://zlin.test.com/mobile/index.php?act=store_ordering&op=ordering_list&state_type=state_commit&size=order&order=num&sc=desc&page=0&curpage=1

总排行榜和我的订单点进去时 
传值：
订单状态(同总排行榜和我的订单的订单状态 前台传值)和商品公共id
用户id  去获取商品id和数量
     */
    public function ordering_listOp(){
        $model_order = Model('ordering');
        $condition = array();
        //订单状态        
        $condition = $this->ordering_type_no($_GET["state_type"]);
        //排序
        $order = $_GET["order"];
        $sc    = $_GET["sc"];
        //类型 我的订单size 不传 总排行榜 size：total 订单管理 size:order 
        $size  = $_GET["size"];
        $page  = $_GET["page"];
        if($page === '0'){//page传0时 表示获取所有数据
            $page  = 0;
        }else{
            $page  = $_GET["page"]?$_GET["page"]:10;
        }
        //订单管理中 未提交订单详情 (详情等同我的订单数据)
        //接口示例:zlin.test5.com/mobile/index.php?act=store_ordering&op=ordering_list&ordering_id=1&order=num&sc=desc&page=10&curpage=1
        //参数传值 ordering_id
        if(!empty($_GET["ordering_id"])){
            $condition['ordering_id'] = $_GET["ordering_id"];
        }
        $orderby = $order.' '.$sc;
        $condition['buyer_id'] = 1;//$this->seller_info['seller_id'];//商家(登录者)id
        if($size == 'total'){//请求总排行榜数据时 获取所有的状态的订单
           unset($condition['ordering_state']); 
           $map_condition['buyer_id'] = $condition['buyer_id'];
           unset($condition['buyer_id']);
        }
        //获取订单信息
        $ordering_info = $model_order->getOrderingList($condition,'*', 'ordering_id desc');
        /*print_r($ordering_info);
        exit;*/
        if($ordering_info){
            unset($condition);
            //总排行榜时获取数据
            if($size == 'total' && count($ordering_info) > 1){

                foreach ($ordering_info as $key => $value) {
                    $ordering_id[] = $value['ordering_id'];
                }
                $ordering_id = implode(',',$ordering_id);
                $condition['ordering_id'] = array('in',$ordering_id);
                //所有的订单数据
                $ordering_list = $model_order->getOrderingGoodsList($condition, $page, 'goods_commonid,goods_serial,goods_image,sum(goods_num) num,goods_price,goods_price*sum(goods_num) total_price,store_goods_state','goods_commonid', $orderby,'', array('goods_common'));
                //自己的订单数据
                $own_ordering_list = $model_order->getOrderingGoodsList($map_condition, $page, 'goods_commonid,goods_serial,goods_image,sum(goods_num) num,goods_price,goods_price*sum(goods_num) total_price,store_goods_state','goods_commonid', $orderby,'', array('goods_common'));
                //订单数据进行处理 总排行榜数量显示自己所订数量
                foreach ($ordering_list as $key => $value) {
                    $ordering_newlist[$value['goods_commonid']] = $value['num'];
                }
                foreach ($own_ordering_list as $key => $value) {
                    $own_ordering_newlist[$value['goods_commonid']] = $value['num'];
                }
                foreach ($ordering_newlist as $key => $value) {
                    $ordering_newlist[$key] = $own_ordering_newlist[$key]?$own_ordering_newlist[$key]:0;
                }
                foreach ($ordering_list as $key => $value) {
                    $ordering_list[$key]['num'] = $ordering_newlist[$value['goods_commonid']];
                }

            }else{
            //其他请求时 获取数据(多获取了订单id （ordering_id字段）)
                foreach ($ordering_info as $key => $value) {
                    $condition['ordering_id'] = $value['ordering_id'];
                    $ordering_list[] = $model_order->getOrderingGoodsList($condition, $page, 'ordering_id,goods_commonid,goods_serial,goods_image,sum(goods_num) num,goods_price,goods_price*sum(goods_num) total_price,store_goods_state','goods_commonid', $orderby,'', array('goods_common'));
                }
            }
            
          
        }
        if(!$ordering_list){
            //为空则返回空数组
            $ordering_list = array();
            if($size == 'order'){
                $bottom = array();
                output_data(array('ordering_list' => $ordering_list,'bottom'=>$bottom), mobile_page(0));
                exit;
            }
            output_data(array('ordering_list' => $ordering_list), mobile_page(0));
            exit;
        }
        $ordernum = count($ordering_list);
        if($ordernum == 1 && $size!='order' && $size!='total'){
            $ordering_list = $ordering_list[0];
        }else if ($size == 'total') {
            //总排行榜 添加排名
            if($ordernum == 1){
                $ordering_list = $ordering_list[0];
            }
            foreach ($ordering_list as $key => $value) {
                $ordering_list[$key]['sort'] = $key+1;             
            }
            
        }else if ($size == 'order') {
            //请求数据为订单管理时 数据处理
            foreach ($ordering_list as $key => $value) {
                $new_ordering_list[$key]['style_num'] = count($value);
                foreach ($value as $k => $v) {
                    $new_ordering_list[$key]['ordering_id'] = $v['ordering_id'];
                    $new_ordering_list[$key]['piece_num'] += $v['num'];
                    $new_ordering_list[$key]['total_price'] += $v['total_price'];
                }
            }
            
            $bottom = array();
            foreach ($new_ordering_list as $key => $value) {
                $model = Model();
                $ordeiing_info = $model->table('ordering')->where(array('ordering_id'=>$value['ordering_id']))->field("add_time")->find();
                $new_ordering_list[$key]['addtime'] = date("Y-m-d H:i:s",$ordeiing_info['add_time']);
                //订单编号
                $new_ordering_list[$key]['ordering_sn'] = $ordering_info[$key]['ordering_sn'];
                //买家姓名
                $new_ordering_list[$key]['buyer_name'] = $ordering_info[$key]['buyer_name'];
                //订单管理 已完成订单 底部栏显示信息 合计  总金额
                if($_GET["state_type"] == "state_commit"){    
                    $bottom['total_style_num'] += $value['style_num'];
                    $bottom['total_piece_num'] += $value['piece_num'];
                    $bottom['total_price']     += $value['total_price'];
                    
                }
            }
            
            $ordering_list = $new_ordering_list;

            
        }
        $totalPage = $model_order->totalPage;//获取总页数

        if($_GET["state_type"] == "state_commit"){
            //当订单管理 是已完成的订单添加底部合计信息
            output_data(array('ordering_list' => $ordering_list,'bottom'=>$bottom), mobile_page($totalPage));
            exit;
        }
        output_data(array('ordering_list' => $ordering_list), mobile_page($totalPage));
    }

/**
 * 订单状态
 * @param  [type] $stage [订单状态]
 * @return [type]        [description]
 */
    private function ordering_type_no($stage) { 
        switch ($stage){
            //订单状态：0(已取消)10(默认):未提交;20:未付款(已提交);21:已付款;22:已付定金;30:已发货;40:已收货
            case 'state_new':
                $condition['ordering_state'] = '10';
                break;
            case 'state_commit':
                $condition['ordering_state'] = '20';
                break;
            case 'state_send':
                $condition['ordering_state'] = '30';
                break;
            case 'state_noeval':
                $condition['ordering_state'] = '40';
                break;
        }
        return $condition;
    }
    
}