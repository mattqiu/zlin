<?php
/**
 * 素材管理
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
class weixin_MaterialModel extends Model{
    public function __construct(){
        parent::__construct();
    }
    /**
     * 计算数量
     * 
     * @param array $condition 条件
     * $param string $table 表名
     * @return int
     */
    public function getAlbumPicCount($condition) {
        $result = $this->table('weixin_material')->where($condition)->count();
        return $result;
    }
    /**
     * 计算数量
     * 
     * @param array $condition 条件
     * $param string $table 表名
     * @return int
     */
    public function getCount($condition, $table = 'weixin_material') {
        $result = $this->table($table)->where($condition)->count();
        return $result;
    }
	/**
     * 计算容量
     * 
     * @param array $condition 条件
     * $param string $table 表名
     * @return int
     */
    public function getTotals($condition, $table = 'weixin_material') {
        $result = $this->table($table)->where($condition)->sum('materialtotal');
        return $result;
    }
    /**
     * 获取单条数据
     * 
     * @param array $condition 条件
     * @param string $table 表名
     * @return array 一维数组
     */
    public function getOne($condition, $table = 'weixin_material') {
        $resule = $this->table($table)->where($condition)->find();
        return $resule;
    }
	/**
	 * 分类列表
	 *
	 * @param array $condition 查询条件
	 * @param obj $page 分页对象
	 * @return array 二维数组
	 */
	public function getClassList($condition,$page=''){
		$param	= array();
		$param['table']			= 'weixin_material_class,weixin_material';
		$param['field']			= 'weixin_material_class.*,count(weixin_material.aclass_id) as count';
		$param['join_type']		= 'left join';
		$param['join_on']		= array('weixin_material_class.aclass_id = weixin_material.aclass_id');
		$param['where']			= $this->getCondition($condition);
		$param['order']			= $condition['order'] ? $condition['order'] : 'weixin_material_class.aclass_sort desc';
		$param['group']			= 'weixin_material_class.aclass_id';
		return Db::select($param,$page);
	}
	/**
	 * 计算分类数量
	 * 
	 * @param int id
	 * @return array 一维数组
	 */
	public function countClass($id){
		$param	= array();
		$param['table']			= 'weixin_material_class';
		$param['field']			= 'count(*) as count';
		$param['where']			= " and member_id = '$id'";
		$return = Db::select($param);
		return $return['0'];
	}
	/**
	 * 验证相册
	 *
	 * @param array $param 参数内容
	 * @return bool 布尔类型的返回结果
	 */
	public function checkAlbum($condition) {
		/**
		 * 验证是否为当前合作伙伴
		 */
		$check_array = self::getClassList($condition,'');
		if (!empty($check_array)){
			unset($check_array);
			return true;
		}
		unset($check_array);
		return false;
	}
	/**
	 * 图片列表
	 *
	 * @param array $condition 查询条件
	 * @param obj $page 分页对象
	 * @return array 二维数组
	 */
	public function getPicList($condition, $page='', $field='*'){
		$param	= array();
		$param['table']			= 'weixin_material';
		$param['where']			= $this->getCondition($condition);
		$param['order']			= $condition['order'] ? $condition['order'] : 'apic_id desc';
		$param['field']			= $field;
		return Db::select($param,$page);
	}
	/**
	 * 添加相册分类
	 *
	 * @param array $input
	 * @return bool
	 */
	public function addClass($input){
		if(is_array($input) && !empty($input)){
			return Db::insert('weixin_material_class',$input);
		}else{
			return false;
		}
	}
    /**
     * 添加相册图片
     *
     * @param array $input
     * @return bool
     */
    public function addPic($input) {
        $result = $this->table('weixin_material')->insert($input);
        return $result;
    }
	/**
	 * 更新相册分类
	 *
	 * @param array $input
	 * @param int $id
	 * @return bool
	 */
	public function updateClass($input,$id){
		if(is_array($input) && !empty($input)){
			return Db::update('weixin_material_class',$input," aclass_id='$id' ");
		}else{
			return false;
		}
	}
	/**
	 * 更新相册图片
	 *
	 * @param array $input
	 * @param int $id
	 * @return bool
	 */
	public function updatePic($input,$condition){
		if(is_array($input) && !empty($input)){
			return Db::update('weixin_material',$input,$this->getCondition($condition));
		}else{
			return false;
		}
	}
	/**
	 * 删除分类
	 *
	 * @param string $id
	 * @return bool
	 */
	public function delClass($id){
		if(!empty($id)) {
			return Db::delete('weixin_material_class'," aclass_id ='".$id."' ");
		}else{
			return false;
		}
	}
	/**
	 * 根据店铺id删除图片空间相关信息
	 * 
	 * @param int $id
	 * @return bool
	 */
	public function delAlbum($id){
		$id	= intval($id);
		Db::delete('weixin_material_class'," member_id= ".$id);
		$pic_list = $this->getPicList(array(" member_id= ".$id),'','apic_cover');
		if(!empty($pic_list) && is_array($pic_list)){
		    $image_ext = explode(',', GOODS_IMAGES_EXT);
			foreach($pic_list as $v){
			    foreach ($image_ext as $ext) {
			        $file = str_ireplace('.', $ext . '.', $v['apic_cover']);
			        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_MATERIAL.DS.$id.DS.$file);
			    }
			}
		}
		Db::delete('weixin_material'," member_id= ".$id);
	}
	/**
	 * 删除图片
	 *
	 * @param string $id
	 * @param int $member_id
	 * @return bool
	 */
	public function delPic($id, $member_id){
		$pic_list = $this->getPicList(array('in_apic_id'=>$id),'','apic_cover');
		
		/**
		 * 删除图片
		 */
        if(!empty($pic_list) && is_array($pic_list)){
		    $image_ext = explode(',', GOODS_IMAGES_EXT);
			foreach($pic_list as $v){
			    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_MATERIAL.DS.$member_id.DS.$v['apic_cover']);
			    foreach ($image_ext as $ext) {
			        $file = str_ireplace('.', $ext . '.', $v['apic_cover']);
			        @unlink(BASE_UPLOAD_PATH.DS.ATTACH_MATERIAL.DS.$member_id.DS.$file);
			    }
			}
		}
		if(!empty($id)) {
			return Db::delete('weixin_material','apic_id in('.$id.')');
		}else{
			return false;
		}
	}
	/**
	 * 查询单条分类信息
	 *
	 * @param int $id 活动id
	 * @return array 一维数组
	 */
	public function getOneClass($param){
		if(is_array($param) && !empty($param)) {
			return Db::getRow(array_merge(array('table'=>'weixin_material_class'),$param));
		}else{
			return false;
		}
	}
	/**
	 * 根据id查询一张图片
	 *
	 * @param int $id 活动id
	 * @return array 一维数组
	 */
	public function getOnePicById($param){
		if(is_array($param) && !empty($param)) {
			return Db::getRow(array_merge(array('table'=>'weixin_material'),$param));
		}else{
			return false;
		}
	}
	/**
	 * 构造查询条件
	 *
	 * @param array $condition 条件数组
	 * @return $condition_sql
	 */
	private function getCondition($condition){
		$condition_sql	= '';
		if($condition['apic_id'] != '') {
			$condition_sql .= " and apic_id= '{$condition['apic_id']}'";
		}
		if($condition['apic_name'] != '') {
			$condition_sql .= " and apic_name='".$condition['apic_name']."'";
		}
		if($condition['apic_tag'] != '') {
			$condition_sql .= " and apic_tag like '%".$condition['apic_tag']."%'";
		}
		if($condition['aclass_id'] != '') {
			$condition_sql .= " and aclass_id= '{$condition['aclass_id']}'";
		}
		if($condition['weixin_material_class.member_id'] != '') {
			$condition_sql .= " and `weixin_material_class`.member_id = '{$condition['weixin_material_class.member_id']}'";
		}
		if($condition['weixin_material_class.aclass_id'] != '') {
			$condition_sql .= " and `weixin_material_class`.aclass_id= '{$condition['weixin_material_class.aclass_id']}'";
		}
		if($condition['weixin_material.member_id'] != '') {
			$condition_sql .= " and `weixin_material`.member_id= '{$condition['weixin_material.member_id']}'";
		}
		if($condition['weixin_material.apic_id'] != '') {
			$condition_sql .= " and `weixin_material`.apic_id= '{$condition['weixin_material.apic_id']}'";
		}
		if($condition['member_id'] != '') {
			$condition_sql .= " and member_id= '{$condition['member_id']}'";
		}
		if($condition['aclass_name'] != '') {
			$condition_sql .= " and aclass_name='".$condition['aclass_name']."'";
		}
		if($condition['in_apic_id'] != '') {
			$condition_sql .= " and apic_id in (".$condition['in_apic_id'].")";
		}
		if($condition['gt_apic_id'] != '') {
			$condition_sql .= " and apic_id > '{$condition['gt_apic_id']}'";
		}
		if($condition['like_cover'] != '') {
			$condition_sql .= " and apic_cover like '%".$condition['like_cover']."%'";
		}
		if($condition['is_default'] != '') {
			$condition_sql .= " and is_default= '{$condition['is_default']}'";
		}
		if($condition['weixin_material_class.un_aclass_id'] != '') {
			$condition_sql .= " and `weixin_material_class`.aclass_id <> '{$condition['weixin_material_class.un_aclass_id']}'";
		}
		return $condition_sql;
	}
}
?>