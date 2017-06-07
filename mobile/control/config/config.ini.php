<?php
defined('InIMall') or exit('Access Invalid!');

define('CODE_ResponseSucceed', 1); //返回成功
define('CODE_InvalidUsernameOrPassword', 6); //无效的用户名或密码
define('CODE_ProcessFailed', 8); //处理失败
define('CODE_UserOrEmailExist', 11); //用户名或email已使用
define('CODE_UnexistInformation', 13);
define('CODE_BuyFailed', 14); // 库存不足
define('CODE_InvalidSession', 100); //登录过期，请重新登录
define('CODE_InvalidParameter', 101); //提交参数错误

