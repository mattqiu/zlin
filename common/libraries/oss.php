<?php


require_once(BASE_DATA_PATH . '/api/oss/oss.phar');

use OSS\OssClient;
use OSS\Core\OssException;

class oss
{
    static private $oss_sdk_service;
    static private $bucket;

    static private function _init()
    {


        //require_once(BASE_DATA_PATH.'/api/oss/OssClient.php');

        //self::$oss_sdk_service = new ALIOSS(NULL, NULL, C("oss.api_url"));
        //self::$oss_sdk_service->set_debug_mode(true);
        self::$bucket = C("oss.bucket");
        self::$oss_sdk_service = new OssClient(C("oss.access_id"), C("oss.access_key"), C("oss.api_url"));
    }

    static private function _format($response)
    {
        echo "|-----------------------Start---------------------------------------------------------------------------------------------------\n";
        echo "|-Status:" . $response->status . "\n";
        echo "|-Body:\n";
        echo $response->body . "\n";
        echo "|-Header:\n";
        print_r($response->header);
        echo "-----------------------End-----------------------------------------------------------------------------------------------------\n\n";
    }

    static public function upload($src_file, $new_file)
    {
        self::_init();


        try {

            $response = self::$oss_sdk_service->uploadFile(self::$bucket, $new_file, $src_file);


            if ($response->status == "200") {
                return true;
            } else {
                $logFile = BASE_DATA_PATH . '/log/oss/'.date('Y-m-d').'.log';
                error_log(date('Y-m-d H:i:s') . ' ' . json_encode($response) . "\n", 3, $logFile);
                return false;
            }
        } catch (OssException $ex) {
            print_r($ex);
            return false;
        }
    }

    static public function upload_by_content($src_file, $new_file)
    {
        self::_init();

        try {
            $response = self::$oss_sdk_service->upload_file_by_content(self::$bucket, $new_file, $src_file);

            if ($response->status == "200") {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    static public function del($img_list)
    {
        self::_init();

        try {
            $response = self::$oss_sdk_service->deleteObjects(self::$bucket, $img_list);
            if ($response->status == "200") {
                return true;
            } else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }
}

defined("ByEcOps") || exit("Access Invalid!");

?>
