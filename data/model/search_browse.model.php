<?php
/**
 * 商品管理
 *
 * 
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class search_browseModel extends Model{
    public function __construct(){
        parent::__construct('search');
    }

	/**
     * 搜索过的关键字
     *
     * @param int $member_id 会员ID（一般传递$_SESSION['member_id']）
     * @param int $shownum 查询的条数，默认0则为返回全部
     * @return array
     */
    public function getViewedGoodsList($member_id = 0,$shownum = 0) {
        $shownum = ($t = intval($shownum))>0?$t:0;
        $browselist = array();
        //如果会员ID存在，则读取数据库浏览历史，或者memcache
        if ($member_id > 0){
            //查询数据库
                $browselist_tmp = $this->table('search_browse')->where(array('member_id'=>$member_id))->order('searchtime desc')->limit($shownum)->select();
                if (!empty($browselist_tmp)){
                    foreach ($browselist_tmp as $k=>$v){
                        $browselist[] = $v;
                    }
                }
            
        }
        //查询浏览过的商品记录cookie
        if (!$member_id){
            $browselist = array();
            if(cookie('viewed_search')){
                $string_viewed_goods = decrypt(cookie('viewed_search'),MD5_KEY);
                if (get_magic_quotes_gpc()) $string_viewed_goods = stripslashes($string_viewed_goods);//去除斜杠
                $cookie_arr_tmp = unserialize($string_viewed_goods);
                $cookie_arr = array();
                foreach ((array)$cookie_arr_tmp as $k=>$v){
                    $info = explode("-", $v);
                    $cookie_arr[] = array($info[0],$info[1],$info[2]);
                }
				
                //截取需要的记录数
                if ($shownum){
                    $cookie_arr = array_slice($cookie_arr,0,$shownum,true);
                }
                $cookie_arr = array_reverse($cookie_arr,true);//翻转数组，按照添加顺序倒序排列				
                if ($cookie_arr){
                    foreach ($cookie_arr as $k=>$v){		
							$browselist[] = array('searchtime'=>$v[1],'searchname'=>$v[0],'searchshop'=>$v[2]);                       
                    }
                }
            }
        }
		
        return $browselist;
    }

    /**
     * 删除浏览记录
     *
     * @param array $where
     * @return array
     */
    public function delGoodsbrowse($where){
        return $this->table('search_browse')->where($where)->delete();
    }

	/**
     * 添加单条浏览记录
     *
     * @param array $where
     * @return array
     */
    public function addGoodsbrowse($insert_arr){
        $this->table('search_browse')->insert($insert_arr);
    }
	/**
     * 添加多条浏览记录
     *
     * @param array $where
     * @return array
     */
    public function addGoodsbrowseAll($insert_arr){
        $this->table('search_browse')->insertAll($insert_arr);
    }

	/**
     * 查询单条浏览记录
     *
     * @param array $where
     * @return array
     */
    public function getGoodsbrowseOne($where, $field = '*', $order = '', $group = '') {
        $this->table('search_browse')->field($field)->where($where)->order($order)->group($group)->find();
    }
	/**
     * 查询单条浏览记录
     *
     * @param array $where
     * @return array
     */
    public function getGoodsbrowseList($where, $field = '*', $page = 0, $limit = 0, $order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('search_browse')->field($field)->where($where)->page($page[0],$page[1])->limit($limit)->order($order)->group($group)->select();
            } else {
                return $this->table('search_browse')->field($field)->where($where)->page($page[0])->limit($limit)->order($order)->group($group)->select();
            }
        } else {
            return $this->table('search_browse')->field($field)->where($where)->page($page)->limit($limit)->order($order)->group($group)->select();
        }
    }
    /**
     * 登录之后把cookie中的搜索记录存入数据库
     */
    public function mergebrowse($member_id, $store_id = 0){
        if ($member_id <= 0){
            return array('state'=>false,'msg'=>'参数错误');
        }
        //获取搜索历史cookie商品列表
        $search_info = $this->getViewedGoodsList();
        if (empty($search_info)){
            return array('state'=>true);
        }
        //cookie中搜索记录，并加入数据库
        if (!empty($search_info)){    
            if ($search_info){
                //存入数据库
                $result = $this->addViewedGoodsToDatabase($search_name='qunashop','', $member_id,$search_info);             
            }
        }
        //最后清空浏览记录cookie
        setNcCookie('viewed_search','',-3600);
        return $result;
    }
    /**
     * 搜索过的关键字
     *
     * @param int $member_id 会员ID（一般传递$_SESSION['member_id']）
     * @param int $shownum 查询的条数，默认0则为返回全部
     * @return array
     */
    public function addViewedGoods($search_name,$search_shop,$member_id = 0) {
        //未登录生成搜索关键字cookie
        if($member_id <= 0){
            $result = $this->addViewedGoodsToCookie($search_name,$search_shop);
        }
        //登录后记录搜索历史
        if($member_id > 0){
            //存入数据库
            $result = $this->addViewedGoodsToDatabase($search_name,$search_shop, $member_id);            
        }
        return $result;
    }

    /**
     * 搜索过的关键字加入历史数据库
     *
     * @param mixed $goods_id 商
     * @param int $member_id 会员ID（一般传递$_SESSION['member_id']）
     * @param int $store_id 店铺ID（一般传递$_SESSION['store_id']）
     * @param array $goods_info 如果已经获取了商品信息则可传递至函数，避免重复查询
     * @return array
     */
    public function addViewedGoodsToDatabase($search_name,$search_shop,$member_id,$search_info) {
        if (!$search_name || $member_id <= 0){
            return array('state'=>false,'msg'=>'参数错误');
        }
        $browsetime = time();
		
		if (is_array($search_info)){
			for($ii = 0;$ii < count($search_info);$ii++){
			    $condition_sql .= "searchname='".$search_info[$ii]['searchname']."'OR ";
			}		
			$conditionql = "(".$condition_sql."searchname='') AND member_id='" .intval($member_id). "'";
			//删除相同
			$this->delGoodsbrowse($conditionql); 
			$browselist_tmp = $this->table('search_browse')->where(array('member_id'=>$member_id))->order('searchtime desc')->limit('0')->select();	
			$searchxs_info = array();
			for($temp = 0;$temp < count($search_info);$temp++){
				$searchxs_info[$temp]['searchtime'] = $search_info[$temp]['searchtime'];
				$searchxs_info[$temp]['searchname'] = $search_info[$temp]['searchname'];
				$searchxs_info[$temp]['searchshop'] = $search_info[$temp]['searchshop'];
				$searchxs_info[$temp]['member_id'] = $member_id;
			}
		    for($temp = 0;$temp < count($browselist_tmp);$temp++){
				$browselist_tmpd[$temp]['searchtime'] = $browselist_tmp[$temp]['searchtime'];
				$browselist_tmpd[$temp]['searchname'] = $browselist_tmp[$temp]['searchname'];
				$browselist_tmpd[$temp]['searchshop'] = $browselist_tmp[$temp]['searchshop'];
				$browselist_tmpd[$temp]['member_id'] = $browselist_tmp[$temp]['member_id'];					
			}
											
			$cards = array_merge($searchxs_info,$browselist_tmpd);		
			if (count($cards) >= 10) {
			    $insert_arr= array_slice($cards,0, 10);	
			}else{
				$insert_arr= $cards;
			}
            $this->table('search_browse')->where(array('member_id'=>$member_id))->delete();			
		}else{
			//处理浏览历史cache中商品详细信息
            $tmp_arr = array();
			$insert_arr = array();
			$tmp_arr['searchname'] = $search_name;
			$tmp_arr['searchshop'] = $search_shop;
			$tmp_arr['member_id'] = $member_id;
            $tmp_arr['searchtime'] = $browsetime;
			$insert_arr[0] = $tmp_arr;
			$where = "searchname='".$tmp_arr['searchname']."' AND member_id='" .intval($tmp_arr['member_id']). "'";
            $this->delGoodsbrowse($where);

			//每个ID只能存储10个搜索记录	
			$browselist_tmp = $this->table('search_browse')->where(array('member_id'=>$member_id))->order('searchtime desc')->limit('0')->select();	
			if (count($browselist_tmp) >= 10) {
				for($temp = 0;$temp < count($browselist_tmp);$temp++){
					array_shift($browselist_tmp[$temp]);
				}
			    array_unshift($browselist_tmp,$insert_arr[0]);		
			    $insert_arr= array_slice($browselist_tmp,0, 10);
			    $this->table('search_browse')->where(array('member_id'=>$member_id))->delete();
			}
		}
		$result = $this->addGoodsbrowseAll($insert_arr);
		return array('state'=>true);
    }

	
    /**
     * 搜索过的关键字加入浏览历史数据库
     *
     * @param mixed $search_name 搜索关键字或者搜索关键字数组
     * @return array
     */
    public function addViewedGoodsToCookie($search_name,$search_shop){
        if (!$search_name){
            return array('state'=>false,'msg'=>'参数错误');
        }

        //搜索时间
        $browsetime = time();

        //构造cookie的一项值，每项cookie的值为搜索关键字-搜索时间
        if (is_array($search_name)){
            $search_namearr = $search_name;
            foreach ($search_name as $v){
                $cookievalue[] = $v . '-' . $browsetime . '-' . $search_shop;
            }
        } else {
            $cookievalue[] = $search_name . '-' . $browsetime . '-' . $search_shop;
            $search_namearr[] = $search_name;
        }
        unset($search_name);

        if (cookie('viewed_search')) {//如果cookie已经存在
            $string_viewed_search = decrypt(cookie('viewed_search'), MD5_KEY);
            if (get_magic_quotes_gpc()) {
                $string_viewed_search = stripslashes($string_viewed_search); // 去除斜杠
            }
            $vg_ca = @unserialize($string_viewed_search);
            if (!empty($vg_ca) && is_array($vg_ca)) {
                foreach ($vg_ca as $vk => $vv) {
                    $vv_arr = explode('-',$vv);
                    if (in_array($vv_arr[0], $search_namearr)) {//如果该搜索历史的浏览记录已经存在，则删除它
                        unset($vg_ca[$vk]);
                    }
                }
            } else {
                $vg_ca = array();
            }
            //将新浏览历史加入cookie末尾
            array_push($vg_ca,implode(',', $cookievalue));

            //cookie中最多存储10条浏览信息
            if (count($vg_ca) > 10) {
                $vg_ca = array_slice($vg_ca, -10, 10);
            }
        } else {
            $vg_ca = $cookievalue;
        }
        $vg_ca = encrypt(serialize($vg_ca), MD5_KEY);
        setNcCookie('viewed_search', $vg_ca);
    }
	 /**
     * 搜索过的关键字加入浏览历史数据库
     *
     * @param mixed $search_name 搜索关键字或者搜索关键字数组
     * @return array
     */
    public function searchkeyword($keyword,$callback,$platform){		        						
	    if(!empty($keyword)){
	        require(BASE_DATA_PATH.'/api/xs/lib/XS.php');
			$q = isset($keyword) ? trim($keyword) : '';
			$q = get_magic_quotes_gpc() ? stripslashes($q) : $q;
			$terms = array();
			if (!empty($q) && strpos($q, ':') === false) {
				try {
					$xs = new XS(C('fullindexer.appname'));
					$search = $xs->search;
					$search->setCharset('UTF-8');
				} catch (XSException $e){	
				    echo $e;
				}
			} 
			
			if($platform == 1){
				//pc端下拉数据整理
			    if(!empty($q)){
					$tes = array();
					$tes = $search->getExpandedQuery($q);
					$qqi = count($tes);
					$idd = array();
					for($ii=0; $ii<$qqi; $ii++){
						$idqq = $tes[$ii];
						$out = $xs->search->setCharset('UTF-8')->count($idqq);
						array_push($idd,$out);
					}
				    for($i=0;$i<sizeof($tes);$i++){
						$result[]=array("amount"=>"$idd[$i]","keyword"=>"$tes[$i]",);
					}
			
					if(!empty($result)){
					    //开始分面搜索
					    $aaa = $search->setQuery($tes[0])->setFacets(array('gc_id', 'gc_name'),true)->search();
					    // 读取分面结果
					    $fid_counts = $search->getFacets('gc_id'); // 返回数组，以 fid 为键，匹配数量为值
					    // 返回数组，以 year 为键，匹配数量为值
					    $ccc_counts = $search->getFacets('gc_name');
					}else{
						return false;
					}
					// 遍历 $fid_counts, $year_counts 变量即可得到各自筛选条件下的匹配数量
					if(!empty($ccc_counts)){
					    $a = array();
					    $b = array();
					    $c = array();
					    $a = array_keys($ccc_counts);
					    $al = array_slice($a,0,2); 
					    $qqq = count($al);
					    $yyy=array();
					    for($oo=0; $oo<$qqq; $oo++){
					        $rrr = (explode(" ",$al[$oo]));
					        $yyy[$oo]=$rrr['1'].' > '.$rrr[2];
					    }
					    $b = array_keys($fid_counts);
					    $c =array_values($fid_counts);
					    $bb_c = $result[0]['amount'];
					    $bb_b = $result[0]['keyword'];
					    for($dd=0; $dd<sizeof($al); $dd++){
						    $sulta[]=array("oamount"=>"$bb_c","amount"=>"$c[$dd]","cid"=>"$b[$dd]","cname"=>$yyy[$dd],"keyword"=>"$bb_b","level"=>"3",);
						}
					    unset($result[0]);
					    $cards = array_merge($sulta, $result);
					    header("Content-Type: application/json; charset=utf-8");
					    $abc = json_encode($cards);
   
					    return $callback."($abc)"; 
					}else{
					    header("Content-Type: application/json; charset=utf-8");
					    $abc_a = json_encode($result);
   
					    return $callback."($abc_a)"; 
					}
			    }else{
					return 'null';
				}
			}elseif($platform == 2){
				//wap端下拉数据整理
				$tes = array();
				$tes = $search->getExpandedQuery($q);
				$idd = array();
				for($ii=0; $ii<count($tes); $ii++){
					$idqq = $tes[$ii];
					$out = $xs->search->setCharset('UTF-8')->count($idqq);
					array_push($idd,$out);
				}
				for($i=0;$i<sizeof($tes);$i++){
					$result[]=array($tes[$i],$idd[$i]);
				}
                $jopd = $this->finishing($result,'','',$callback);				   
				return $jopd; 					   
			}
		}
	}
	//搜索热词
	public function searchot($callback){
		if(!empty($callback)){
			require(BASE_DATA_PATH.'/api/xs/lib/XS.php');
			try {
				$xs = new XS(C('fullindexer.appname'));
				$search = $xs->search;
				$search->setCharset('UTF-8');
				$tesd = $search->getHotQuery(50);
			} catch (XSException $e){	
			    echo $e;
			}
			foreach($tesd  as $word => $freq){					
				$hot[] = $word;	
				$hotcp[] = $freq;					
			}		
			for($i=0;$i<count($hot);$i++){
				if($hotcp[$i] > 500){
					$tesdp[$i] = array($hot[$i],'','1',$i);
				}else{
					$tesdp[$i] = array($hot[$i],'','0',$i);
				}
			}					
			$seqns = $this->finishing('','',$tesdp,$callback);
			return $seqns;				
		}else{
			return false;
		}
	}
	//整理json数据	
	public function finishing($list,$searchis,$hotword,$callback){
				
		$json = array ('list'=>$list,'his'=>$searchis,'hotword'=>$hotword);  
		$jsonp = json_encode($json);
		return $callback."($jsonp)"; 
	}
	//wap删除历史	
	public function deletesearchs($member_id){
		if($member_id > 0){
			$seqnsd = $this->table('search_browse')->where(array('member_id'=>$member_id))->delete();				
		}else{
			$seqnsd = setNcCookie('viewed_search','',-3600);
		}
		return $seqnsd;
	}		
		
	public function topsearch($callback){			
		$search_tmp = $this->table('search_top')->where(array('searchtop_id'=>'1'))->select();	
		$jsond = array ('result'=>"true",'errmsg'=>"",'title'=>$search_tmp[0]['searchtitle'],'ptag'=>"20492.2.1",'key'=>$search_tmp[0]['searchkey'],'url'=>"");	
		$jsonp = json_encode($jsond);		
		return $callback."($jsonp)";
	}				
}