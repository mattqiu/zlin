<?php
/**
 * DES加密类
 *
 * 
 *
 *
 * @package    library
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @author	   zlin-e Team
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class CryptDES {
    var $key;  
	
    function CryptDES($key)   
    {         
        $this->key = $key;  
    }   
	      
    function encrypt($input)   
    {         
        $size = mcrypt_get_block_size('des','ecb');  
        $input = $this->pkcs5_pad($input, $size);  
        $key = $this->key;  
        $td = mcrypt_module_open('des', '', 'ecb', '');  
        $iv = @mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);  
        @mcrypt_generic_init($td, $key, $iv);  
        $data = mcrypt_generic($td, $input); 
		//$data = base64_encode($data);
		$data = bin2hex($data); 		
        mcrypt_generic_deinit($td);  
        mcrypt_module_close($td);         
        return $data;  
    } 
	        
    function decrypt($encrypted)  
    {         
        //$encrypted = base64_decode($encrypted);
		$encrypted = $this->hex2bin($encrypted);  
        $key =$this->key;  
        $td = mcrypt_module_open('des','','ecb','');   
        //使用MCRYPT_DES算法,cbc模式  
        $iv = @mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);  
        $ks = mcrypt_enc_get_key_size($td);  
        @mcrypt_generic_init($td, $key, $iv);  
        //初始处理                 
        $decrypted = mdecrypt_generic($td, $encrypted);  
        //解密  
        mcrypt_generic_deinit($td);  
        //结束               
        mcrypt_module_close($td);  
        $y=$this->pkcs5_unpad($decrypted);  
        return $y;     
    } 
	
	function hex2bin($hexdata){		
        $bindata = '';
        $length = strlen($hexdata);
        for ($i=0; $i< $length; $i += 2){
            $bindata .= chr(hexdec(substr($hexdata, $i, 2)));
        }
        return $bindata;
    } 
	        
    function pkcs5_pad ($text, $blocksize)   
    {         
        $pad = $blocksize - (strlen($text) % $blocksize);  
        return $text . str_repeat(chr($pad), $pad);  
    } 
	    
    function pkcs5_unpad($text)   
    {         
        $pad = ord($text{strlen($text)-1});  
        if ($pad > strlen($text)){  
            return false;  
        }  
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad){  
            return false;  
        }  
        return substr($text, 0, -1 * $pad);  
    }  
}