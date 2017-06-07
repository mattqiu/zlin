<?php
/**
 * 店铺地址地图
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */

defined('InIMall') or exit('Access Invalid!');
class show_mapControl extends BaseHomeControl {
    public function __construct() {
        parent::__construct();
    }
    /**
     * 店铺地址地图
     *
     */
    public function indexOp() {
        if (empty($_GET['w'])) {
            $_GET['w'] =500;
        }
        if (empty($_GET['h'])) {
            $_GET['h'] =500;
        }

        $model_store_map = Model('store_map');
        $store_id = intval($_GET['store_id']);
        if ($store_id > 0) {
            $condition = array();
            $condition['store_id'] = $store_id;
            $map_list = $model_store_map->getStoreMapList($condition, '', '', 'map_id asc');
            Tpl::output('map_list',$map_list);
            Tpl::showpage('show_map','null_layout');
        }
    }
}
