<?php    
/*
 * PHP QR Code encoder
 *
 * Exemplatory usage
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

class PhpQRCode{
    
	//processing form input
	//remember to sanitize user input in real-life solution !!!
	private $errorCorrectionLevel = 'H';		// L M Q H	
	private $matrixPointSize = 4;				// 1 2 3 4 5 6 7 8 9 10	
	private $data = 'IMall';	
	private $pngTempDir		= '';	
	private $pngTempName    = '';    
	/**
	 * 设置
	 */
	public function set($key,$value){
		$this->$key = $value;
	}
	
	public function __construct() {
	    include "qrlib.php";
	}
	
    public function init(){
	    //ofcourse we need rights to create temp dir
	    if (!file_exists($this->pngTempDir)){
	        mkdir($this->pngTempDir);
		}
	
        if ($this->pngTempName != '') {
            $filename = $this->pngTempDir.$this->pngTempName;
        } else {
           $filename = $this->pngTempDir.'test'.md5($this->data.'|'.$this->errorCorrectionLevel.'|'.$this->matrixPointSize).'.png';
        }
	    if ($this->data != 'IMall') { 	            
	        // user data
	        QRcode::png($this->data, $filename, $this->errorCorrectionLevel, $this->matrixPointSize, 2);	        
	    } else {	    
	        //default data
	        QRcode::png('http://www.hzlwo.com', $filename, $this->errorCorrectionLevel, $this->matrixPointSize, 2);  	        
	    }    	        
	    //display generated file
	    return basename($filename);
	    
	    QRtools::timeBenchmark();    
	}
	
	public function BuildGoodsQRCode($store_id,$goods_id,$good_img){
        $this->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'goodQRcode'.DS.$store_id.DS);
        //$this->set('data',urlShop('goods', 'index', array('goods_id'=>$goods_id)));//电脑版
		$this->set('data',WAP_SITE_URL . '/tmpl/product_detail.html?goods_id='.$goods_id);//wap版
        $this->set('pngTempName', $goods_id . '.png');
        $this->init();
		if ($good_img){
			$logo = BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'goods'.DS.$store_id.DS.$good_img;//准备好的logo图片
			$QR = BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'goodQRcode'.DS.$store_id.DS.$goods_id.'.png';//已经生成的原始二维码图 
			if ($logo !== FALSE) { 
                $QR = imagecreatefromstring(file_get_contents($QR));   
                $logo = imagecreatefromstring(file_get_contents($logo));   
                $QR_width = imagesx($QR);//二维码图片宽度   
                $QR_height = imagesy($QR);//二维码图片高度   
                $logo_width = imagesx($logo);//logo图片宽度   
                $logo_height = imagesy($logo);//logo图片高度   
                $logo_qr_width = $QR_width / 5;   
                $scale = $logo_width/$logo_qr_width;   
                $logo_qr_height = $logo_height/$scale;   
                $from_width = ($QR_width - $logo_qr_width) / 2;   
                //重新组合图片并调整大小   
               imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);   
			}
            //输出图片   
            imagepng($QR, BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'goodQRcode'.DS.$store_id.DS.$goods_id.'.png');  
		}else{
			$QR = BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'goodQRcode'.DS.$store_id.DS.$goods_id.'.png';//已经生成的原始二维码图
			//输出图片
			imagepng($QR, BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'goodQRcode'.DS.$store_id.DS.$goods_id.'.png');
		}
    }
	
	public function BuildStoreQRCode($store_id,$store_logo){
        $this->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'storeQRcode'.DS);
        //$this->set('data',urlShop('show_store', 'index', array('store_id'=>$store_id))); pc端
		$this->set('data',WAP_SITE_URL . '/tmpl/store.html?store_id='.$store_id);  //wap端
        $this->set('pngTempName', $store_id . '.png');
        $this->init();
        if ($store_logo){
	        $logo = BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'LOGO'.DS.$store_logo;//准备好的logo图片
		    $QR = BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'storeQRcode'.DS.$store_id.'.png';//已经生成的原始二维码图 
		    if ($logo !== FALSE) { 
                $QR = imagecreatefromstring(file_get_contents($QR));   
                $logo = imagecreatefromstring(file_get_contents($logo));   
                $QR_width = imagesx($QR);//二维码图片宽度   
                $QR_height = imagesy($QR);//二维码图片高度   
                $logo_width = imagesx($logo);//logo图片宽度   
                $logo_height = imagesy($logo);//logo图片高度   
                $logo_qr_width = $QR_width / 5;   
                $scale = $logo_width/$logo_qr_width;   
                $logo_qr_height = $logo_height/$scale;   
                $from_width = ($QR_width - $logo_qr_width) / 2;   
                //重新组合图片并调整大小   
                imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);   
		    }
            //输出图片   
            imagepng($QR, BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.'storeQRcode'.DS.$store_id.'.png');  
	    }
    }
	
	public function BuildExtensionQRCode($member_id,$type=1){
		$filename = 'extension_join.html'; //推广系统

        $this->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_EXTENSION_QRCODE.DS);
		$this->set('data',WAP_SITE_URL . '/tmpl/member/'.$filename.'?extension='.urlsafe_b64encode($member_id));  //wap端
        $this->set('pngTempName', 'qrcode_'.$member_id.'.png');
        $this->init();
		$member_pic = getMemberAvatarForID($member_id);//会员头像
        if ($member_pic){
		    $QR = BASE_UPLOAD_PATH.DS.ATTACH_EXTENSION_QRCODE.DS.'qrcode_'.$member_id.'.png';//已经生成的原始二维码图 
		    if ($logo !== FALSE) { 
                $QR = imagecreatefromstring(file_get_contents($QR));   
                $logo = imagecreatefromstring(file_get_contents($member_pic));   
                $QR_width = imagesx($QR);//二维码图片宽度   
                $QR_height = imagesy($QR);//二维码图片高度   
                $logo_width = imagesx($logo);//logo图片宽度   
                $logo_height = imagesy($logo);//logo图片高度   
                $logo_qr_width = $QR_width / 5;   
                $scale = $logo_width/$logo_qr_width;   
                $logo_qr_height = $logo_height/$scale;   
                $from_width = ($QR_width - $logo_qr_width) / 2;   
                //重新组合图片并调整大小   
                imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);   
		    }
            //输出图片   
            imagepng($QR, BASE_UPLOAD_PATH.DS.ATTACH_EXTENSION_QRCODE.DS.'qrcode_'.$member_id.'.png');  
	    }
    }

    public function BuildWeiXinQRCode($wx_id,$wx_headerpic,$token){
        $this->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_WEIXIN.DS.'wxQRcode'.DS);
        $this->set('data',urlWeiXin('Wap','index', array('token' => $token, 'sgssz'=>'mp.weixin.qq.com')));
        $this->set('pngTempName', $wx_id . '.png');
        $this->init();
        if ($wx_headerpic){
	        $logo =str_replace(UPLOAD_SITE_URL,BASE_UPLOAD_PATH,MCthumb($wx_headerpic));//准备好的logo图片		
		    $QR = BASE_UPLOAD_PATH.DS.ATTACH_WEIXIN.DS.'wxQRcode'.DS.$wx_id.'.png';//已经生成的原始二维码图
		    if ($logo !== FALSE) { 		
                $QR = imagecreatefromstring(file_get_contents($QR));   
                $logo = imagecreatefromstring(file_get_contents($logo));   
                $QR_width = imagesx($QR);//二维码图片宽度   
                $QR_height = imagesy($QR);//二维码图片高度   
                $logo_width = imagesx($logo);//logo图片宽度   
                $logo_height = imagesy($logo);//logo图片高度   
                $logo_qr_width = $QR_width / 5;   
                $scale = $logo_width/$logo_qr_width;   
                $logo_qr_height = $logo_height/$scale;   
                $from_width = ($QR_width - $logo_qr_width) / 2;  			 
                //重新组合图片并调整大小   
                imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);   
		    }
            //输出图片   
            imagepng($QR, BASE_UPLOAD_PATH.DS.ATTACH_WEIXIN.DS.'wxQRcode'.DS.$wx_id.'.png');  
	    }
    }
	
	public function BuildWAPQRCode(){
        $this->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS);
		$this->set('data',WAP_SITE_URL);//wap版
        $this->set('pngTempName', 'wap.png');
        $this->init();
		if (C('site_logo')){
			$logo = BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.C('site_logo');//准备好的logo图片
			$QR = BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.'wap.png';//已经生成的原始二维码图 
			if ($logo !== FALSE) { 
                $QR = imagecreatefromstring(file_get_contents($QR));   
                $logo = imagecreatefromstring(file_get_contents($logo));   
                $QR_width = imagesx($QR);//二维码图片宽度   
                $QR_height = imagesy($QR);//二维码图片高度   
                $logo_width = imagesx($logo);//logo图片宽度   
                $logo_height = imagesy($logo);//logo图片高度   
                $logo_qr_width = $QR_width / 5;   
                $scale = $logo_width/$logo_qr_width;   
                $logo_qr_height = $logo_height/$scale;   
                $from_width = ($QR_width - $logo_qr_width) / 2;   
                //重新组合图片并调整大小   
               imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);   
			}
            //输出图片   
            imagepng($QR, BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.'wap.png');  
		}
    }
	
	public function BuildGeneralQRCode($url = '', $logo = '', $size = 6){
		if ($size<1 || $size>40){
			$size = 6;
		}
		//配置二维码控件参数
        $this->set('pngTempDir',BASE_UPLOAD_PATH.DS.ATTACH_TEMP.DS);
		$this->set('matrixPointSize',$size);//二维码大小
		$this->set('data',$url);//二维码url
		$qrcode_filename = md5($this->data.'|'.$this->errorCorrectionLevel.'|'.$this->matrixPointSize).'.png';
        $this->set('pngTempName', $qrcode_filename);
		//生成二维码
        $this->init();
		//添加logo
		if (!empty($logo)){//准备好的logo图片
			$QR = BASE_UPLOAD_PATH.DS.ATTACH_TEMP.DS.$qrcode_filename;//已经生成的原始二维码图 

            $QR = imagecreatefromstring(file_get_contents($QR));   
            $logo = imagecreatefromstring(file_get_contents($logo));   
            $QR_width = imagesx($QR);//二维码图片宽度   
            $QR_height = imagesy($QR);//二维码图片高度   
            $logo_width = imagesx($logo);//logo图片宽度   
            $logo_height = imagesy($logo);//logo图片高度   
            $logo_qr_width = $QR_width / 5;   
            $scale = $logo_width/$logo_qr_width;   
            $logo_qr_height = $logo_height/$scale;   
            $from_width = ($QR_width - $logo_qr_width) / 2;   
            //重新组合图片并调整大小	
			//$dst_image：新建的图片		 
            //$src_image：需要载入的图片 
            //$dst_x：设定需要载入的图片在新图中的x坐标 
            //$dst_y：设定需要载入的图片在新图中的y坐标 
            //$src_x：设定载入图片要载入的区域x坐标 
            //$src_y：设定载入图片要载入的区域y坐标 
            //$dst_w：设定载入的原图的宽度（在此设置缩放） 
            //$dst_h：设定载入的原图的高度（在此设置缩放） 
            //$src_w：原图要载入的宽度 
            //$src_h：原图要载入的高度
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);   
            //输出图片   
            imagepng($QR, BASE_UPLOAD_PATH.DS.ATTACH_TEMP.DS.$qrcode_filename);  
		}
		return UPLOAD_SITE_URL.DS.ATTACH_TEMP.DS.$qrcode_filename;
    }
}