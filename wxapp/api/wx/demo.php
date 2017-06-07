<?php

include_once "wxBizDataCrypt.php";


$appid = 'wx02bc6198b00b3a66';
$sessionKey = 'wylc43fQfCwXMOa3d1y5xw==';

$encryptedData="naj+iU0zZt+frcR7yx/hufPPUpxYQNbUjzFN2mzVlAkJk4MMSaNDJ3l8FreZVwijiLL9WArNQZN3k2wh+JHBTJsaAwtOkGO24YzNJhGSRHDt8FpQRhRt2XOEeNcCsu/Y286sRSihTHQ+ODnasyPi4FrGbsMTa0lS01dlzViglsZv0HimQb1pWIbvdd38nQfdYv4JB8cuRq/a/dIV3KSNaw1MW6ov/g/WLyL6+bLsuvpLXRVR9Ms5OS6ImhDviZBrTefxw+6f6jh+xdFhu0gfDm7qrQLqnSvJBFPVZi2TXxT3lFzOoNQ2EgBlt01/zikYYWue7Zf49fuK9sYf4kLzNrFz3p62AMRU1Xh4Z9dW4SpLHr+kosCUia+T7j8oYjc97FLfOr+loiFy7AhPTONT2Qf2kwBPFXcvEhXr8olQrOZ/O7MPQC8bCTRukeDNGwZc7Ezj2FZ4D6/lIRtHm2a2oYDHm3/rIDgx6tw+lYsaCyTDjls6vAtTQHFIaogRIzx5+lok9vMl5Rl03Go5/49gDQ==";
$iv = 'Dc5hvp4JnoYt7WXHr74+sA==';

$pc = new WXBizDataCrypt($appid, $sessionKey);
$errCode = $pc->decryptData($encryptedData, $iv, $data );

if ($errCode == 0) {
    print($data . "\n");
} else {
    print($errCode . "\n");
}
