<?php
/**
 * 商城专辑模型
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

class web_specialModel extends Model{
	
	const SPECIAL_TYPE_CMS = 1;
    const SPECIAL_TYPE_SHOP = 2;

    private $special_type_array = array(
        self::SPECIAL_TYPE_CMS => '资讯',
        self::SPECIAL_TYPE_SHOP => '商城',
    );

    public function __construct(){
        parent::__construct('web_special');
    }

	/**
	 * 读取列表 
	 * @param array $condition
	 *
	 */
	public function getList($condition, $page=null, $order='', $field='*', $limit=''){
        $list = $this->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
        return $list;
	}
	
	/**
	 * 读取完整信息 
	 * @param array $condition
	 *
	 */
	public function getSpecialInfoList($condition=array(), $page=40, $order='special_id desc'){
		$condition['special_type']=1;
		$condition['special_apply']=1;
		$condition['special_state'] = 2;
		
		$field = 'web_special.special_id, web_special.special_title, web_special.special_class, web_special_class.class_name, web_special.special_type, web_special.special_brand, brand.brand_name,brand.brand_follows, brand.brand_pic,web_special.store_id, web_special.special_desc, web_special.special_image';
		$on = 'web_special.special_class=web_special_class.class_id, web_special.special_brand=brand.brand_id';
		return Model()->table('web_special,web_special_class,brand')->field($field)->join('left join')->on($on)->page($page)->where($condition)->select();		
	}

    /**
	 * 读取单条记录
	 * @param array $condition
	 *
	 */
    public function getOne($condition,$order=''){
        $result = $this->where($condition)->order($order)->find();
        return $result;
    }

	/*
	 *  判断是否存在 
	 *  @param array $condition
     *
	 */
	public function isExist($condition) {
        $result = $this->getOne($condition);
        if(empty($result)) {
            return FALSE;
        }
        else {
            return TRUE;
        }
	}

	/*
	 * 增加 
	 * @param array $param
	 * @return bool
	 */
    public function save($param){
        return $this->insert($param);	
    }
	
	/*
	 * 更新
	 * @param array $update
	 * @param array $condition
	 * @return bool
	 */
    public function modify($update, $condition){
        return $this->where($condition)->update($update);
    }
	
	/*
	 * 删除
	 * @param array $condition
	 * @return bool
	 */
    public function drop($condition){
        return $this->where($condition)->delete();
    }
	
}

