<?php
$config = array (	
		//支付宝公钥
		'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC+2q/GnwyKhmgNIPYa6WrUFJ1qc2d5e+oWtXLCJyktQBsphejOpEy5sJPXQG3uSbcUeBYa+xKRp+XcwgQacVr40c53eTzIAYOn/bScuZf3uuPlO7VOXyjS3KtIAu4WlOSc24GXQ73uzLYpQXNS3y09o6XrauuLQ53+Z3nKsEa2UQIDAQAB",

		//商户私钥
		'merchant_private_key' => "MIICXQIBAAKBgQC+2q/GnwyKhmgNIPYa6WrUFJ1qc2d5e+oWtXLCJyktQBsphejOpEy5sJPXQG3uSbcUeBYa+xKRp+XcwgQacVr40c53eTzIAYOn/bScuZf3uuPlO7VOXyjS3KtIAu4WlOSc24GXQ73uzLYpQXNS3y09o6XrauuLQ53+Z3nKsEa2UQIDAQABAoGAGw0SBvv6IhRE4T9/wna9HoxBd5od23k1x7w2JNC6JGDGuM7zHX7qJROjEMpgCntGSM9wiqh1jFGY4f5Z6ImSlB0zOD6MbiryXUxlXYen63wDZqi7XR5FyKXa+JWAmi5Ycno7BL0GqzWozeSLJ8fciq6zwHgL2rRSDhmS6sKuTgECQQDiY4sIvBxElBB43SDLM7NBufaN6P9vy+g9tUWpjz/zyvYpyZZqVdScKFd4Rskhu5HvWxexhilJG+isAu1Do8GhAkEA19FLeI8Xn3za4YGHM9RD7IfrUB9ujkYLWA+Zjg8eF6vb66KXKExX6fI7Itnn4fqgV9F6WrhmFIDcVguC9SoWsQJBALUTD4rOAwIrN72kiO1fDdrdZkJ9gYonGzv4OJ71sB5MUXZ9Ae1Nd3/rmILgg1GS2JgNUTcx6uXKB+FFegcm/OECQGivv3o1fvIAMHRezmSXvHMJ4100Qf6Ff48x0fyU3LYCyWTds5D6p1J2C7V2GgMF/a1bkYxcEjgz4a1jXBzsU6ECQQCEfhG90PgZT7XkPNXjBh0KCDw+RxNO+UQOjC7scxWpoURH5pP4nvmhpOicAWvaacXxfabI50VWHLlgTj8hxYPk",

		//编码格式
		'charset' => "UTF-8",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//应用ID
		'app_id' => "2017040106514615",

		//异步通知地址,只有扫码支付预下单可用
		'notify_url' => "http://www.zlin-e.com/wxapp/api/payment/alipay/notify_url.php",

		//最大查询重试次数
		'MaxQueryRetry' => "10",

		//查询间隔
		'QueryDuration' => "3"
);