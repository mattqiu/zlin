<?php
/**
 * 邮件任务队列模型
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class mail_cronModel extends Model{
    public function __construct() {
        parent::__construct('mail_cron');
    }
    /**
     * 新增商家消息任务计划
     * @param unknown $insert
     */
    public function addMailCron($insert) {
        return $this->insert($insert);
    }
    /**
     * 查看商家消息任务计划
     * 
     * @param unknown $condition
     * @param number $limit
     */
    public function getMailCronList($condition, $limit = 0, $order = 'mail_id asc') {
        return $this->where($condition)->limit($limit)->order($order)->select();
    }
    
    /**
     * 删除商家消息任务计划
     * @param unknown $condition
     */
    public function delMailCron($condition) {
        return $this->where($condition)->delete();
    }
}