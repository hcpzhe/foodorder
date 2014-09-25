<?php
namespace Manage\Model;
use Think\Model;

/**
 * 商品分类模型
 */
class CategoryModel extends Model {

	public static $status = array('-1','0','1');
	
	protected $updateFields = array('cate_name','parent_id','sort','status'); //更新的时候不修改所属店铺ID,避免数据混乱
	
	/**
	 * MUST_VALIDATE	必须验证 不管表单是否有设置该字段
	 * VALUE_VALIDATE	值不为空的时候才验证
	 * EXISTS_VALIDATE	表单存在该字段就验证   (默认)
	 * 
	 * self::MODEL_INSERT或者1	新增数据时候验证
	 * self::MODEL_UPDATE或者2	编辑数据时候验证
	 * self::MODEL_BOTH或者3		全部情况下验证（默认）
	 */
    protected $_validate = array(
	    	array('cate_name','require','商品分类名称不能为空'),
    		array('parent_id','number','父级分类不能为空'),
    		array('store_id','number','所属店铺不能为空'),
    		
	    	array('sort',array('0','99999'),'排序值非法',self::EXISTS_VALIDATE,'between'),
			array('status', array('-1','0','1') ,'店铺状态非法',self::EXISTS_VALIDATE,'in'),//-1-删除 0-禁用 1-正常
    );
	
    public function myAdd($data) {
    	$data['parent_id'] = (int)$data['parent_id'];
    	$data['store_id'] = (int)$data['store_id'];
    	if (false === $this->create($data,self::MODEL_INSERT)) return false;
		//验证 parent_id合法性
		if ($this->parent_id >0) {
			$parent = $this->find($this->parent_id);
			if (false === $parent || empty($parent)) {
				$this->error = '父级分类不存在';
				return false;
			}
		}
		//验证store_id合法性
		$store_M = new Model('Store');
		$store = $store_M->find($this->store_id);
		if (false === $store || empty($store)) {
			$this->error = '所属店铺不存在';
			return false;
		}
		
		return $this->add();
    }
    
    public function myUpdate($data) {
    	$id = (int)$data['id'];
    	if ($id<=0) {
    		$this->error = '请先选择要更新的分类';
    		return false;
    	}
    	unset($data['id']);
    	//验证数据
    	$data['parent_id'] = (int)$data['parent_id'];
    	if (false === $this->create($data,self::MODEL_UPDATE)) return false;
		//验证 parent_id合法性
		if ($this->parent_id >0) {
			$parent = $this->find($this->parent_id);
			if (false === $parent || empty($parent)) {
				$this->error = '父级分类不存在';
				return false;
			}
		}
		return $this->where('`id`='.$id)->save();
    }
}