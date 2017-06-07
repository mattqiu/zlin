<?php
/**
 * 控制台
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

class aboutusControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('dashboard');
    }

    public function indexOp() {
        $this->aboutusOp();
    }

    /**
     * 关于我们
     */
    public function aboutusOp(){
        $version = C('version');
        $v_date = substr($version,0,4).".".substr($version,4,2);
        $s_date = substr(C('setup_date'),0,10);
        Tpl::output('v_date',$v_date);
        Tpl::output('s_date',$s_date);
        Tpl::showpage('aboutus', 'null_layout');
    }

}
