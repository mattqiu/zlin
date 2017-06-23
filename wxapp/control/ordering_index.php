<?php
/**
 * 小程序首页
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class ordering_indexControl extends wxappHomeControl{

	public function __construct() {
        parent::__construct();
    }
    
    /**
	 * 导购端登录
	 */
	public function sellerOp(){
		
	$this->code = $_POST['code'];
	if (empty($this->code)){
            output_error('获取微信小程序CODE失败，请清除微信缓存后再打开小程序');
        }
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$this->appId."&secret=".$this->appSecret."&js_code=".$this->code."&grant_type=authorization_code";
        $res =json_decode($this->httpGet($url), true);
        $openid = $res['openid'];
        $client = 'wxapp'; //微信小程序
        /*
         * 思路：首先去登录令牌中查找 是否有微信小程序的登录记录，没有就根据 unionId（微信会员再所有公众号中的唯一识别码） 去找出该会员
         * 如果有 则可以直接登录
         */
        //获取该员工登录店铺令牌
        $model_mb_seller_token = Model('mb_seller_token');        
        $mb_seller_token_info = $model_mb_seller_token->getSellerTokenInfo(array('openid'=>$openid, 'client_type'=>$client));
        //查看商家是否有登录记录
        if(empty($mb_seller_token_info)){
        	//没有则去查看该店员又没会员的登录记录
        	$model_mb_user_token = Model('mb_user_token');
        	$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfo(array('openid'=>$openid, 'client'=>$client));
        	if(!empty($mb_user_token_info)){
        		//有会员登录记录，则根据会员的记录给店员添加一条商家的登录记录
        		//根据会员的登录信息，确定默认登陆的商家
        		$this->login_seller_token($mb_user_token_info['token'], $openid, $mb_user_token_info['member_id'], $client);
        	}else{
        		//都未登录的情况下，还有一种可能性。就是通过公众号等信息登录过。那么就要通过曾今获取到每个微信特有的unionId
        		//先获取到unionId
        		$encryptedData = urldecode($_POST['encryptedData']);
        		$iv = $_POST['iv'];
        		$inc_file = BASE_PATH.DS.'api'.DS.'wx'.DS.'wxBizDataCrypt.php';
        		if(!is_file($inc_file)){
        			output_error('微信校验接口不存在');
        		}
        		require($inc_file);
        		$pc = new WXBizDataCrypt($this->appId, $res['session_key']);
        		$errCode = $pc->decryptData($encryptedData, $iv, $data);
        		file_put_contents('encrypte.txt',$data,FILE_APPEND);
        		if ($errCode == 0) {
        			$result = json_decode($data, true);//转换成数组
        			$unionId = $result['unionId'];
        		} else {
        			output_error('微信校验会员信息失败，原因：'.$errCode);
        		}
        		//根据获取到的unionId，去查找会员信息
        		$unionid = 's:7:"unionid";s:'.strlen($unionId).':"'.$unionId.'";';//拼接模糊查询的格式
        		print_r($unionid);
        		exit;
        		
        		/*
        		 * 参考mysql json格式查询：http://www.cnblogs.com/waterystone/p/5626098.html
        		 * 下面写一个mysql5.7.9以上版本支持json 查询
        		 * SELECT json_extract(member_wxinfo, '$.unionid') FROM zlin_member WHERE json_extract(member_wxinfo,'$.unionid') = 'o6AiRuOwBKVqa-DXfNQsNKk_50UA';
        		 */
        		$condition =array();
        		$condition['member_wxinfo'] = array(array('like','%'.$unionid.'%'));
        		$model_member = Model('member');
        		$member_info = $model_member->getMemberInfo($condition);
        		if(!empty($member_info)){
        			//这里证明一点，再wxapp上没有留下过记录，所以要去新增一条记录
        			//新登录生成token
        			$token = $this->login_mobile_token($member_info['member_id'], $member_info['member_name'], $client,$openid);
        		}else{
        			//没有获取到会员信息，则证明该微信账号并未再平台上注册登记过
        			output_error('抱歉该微信并未绑定平台的账号，目前是无法登录成功！');
        		}        		
        	}
        }else{
        	$token = $mb_seller_token_info['token'];
		
        }        
        if($token) {
			echo $token;
			die;
        } else {
        	output_error('未获取到正确的登录令牌，请先删除小程序后重新添加即可！');
        }       
    }
    
    /**
     * 微信小程序-导购首页
     */
    public function indexOp() {
    	$indexInfo['member_id'] = $this->member_id; //当前会员ID
    	$indexInfo['store_id'] = $this->store_info['store_id']; //当前店铺ID    	
    	$indexInfo['store_avatar'] = $this->store_info['store_avatar']; //当前店铺LOGO
    	$indexInfo['seller_id'] = $this->seller_info['seller_id']; //当前导购ID
    	$indexInfo['seller_name'] = $this->seller_info['seller_name']; //当前导购用户名
    	$indexInfo['store_num'] 	= Model('seller')->getStoreCountByMemberID($this->member_id);//可管理店铺数
    	
    	$indexInfo['saleman'] = $this->saleman;//导购列表
    	$indexInfo['saleman_len'] = count($this->saleman['saleman_name']);//导购数
    	//切换导购
    	if(!empty($_REQUEST['saleman_id'])){
    		$saleman_id = $_REQUEST['saleman_id'];
    	}else{
    		$saleman_id = $this->seller_info['seller_id'];
    	}
    	$indexInfo['saleman_id'] = $saleman_id;
    	output_data($indexInfo,'首页加载成功');
    }
    
    
}