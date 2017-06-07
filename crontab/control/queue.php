<?php
/**
 * 队列
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
ini_set('default_socket_timeout', -1);
class queueControl extends BaseCronControl {

    public function indexOp() {
        if (ob_get_level()) ob_end_clean();

        $logic_queue = Logic('queue');

        $worker = new QueueServer();
        $queues = $worker->scan();
        while (true) {
            $content = $worker->pop($queues,1800);
            if (is_array($content)) {
                $method = key($content);
                $arg = current($content);

                $result = $logic_queue->$method($arg);
                if (!$result['state']) {
                    $this->log($result['msg'],false);
                }
//                 echo date('Y-m-d H:i:s',time()).' '.$method."\n";
//                 flush();
//                 ob_flush();
            } else {
                $model = Model();
                $model->checkActive();
                unset($model);
//                 echo date('Y-m-d H:i:s',time())."  ---\n";
//                 flush();
//                 ob_flush();
            }
        }
    }
}