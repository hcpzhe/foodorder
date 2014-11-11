<?php
namespace Manage\Model;
use Think\Model;

/**
 * 商品分类模型
 */
class CategoryModel extends Model {
	public static $mystat = array('del'=>'-1','forbid'=>'0','allow'=>'1');
	public static $ablemap = array('status'=>'1'); //状态正常的查询条件
	
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
	    	array('cate_name','require','商品分类名称不能为空',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
	    	array('cate_name','require','商品分类名称不能为空',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
    		array('parent_id','number','父级分类非法',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		array('parent_id','number','父级分类非法',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
    		
    		array('store_id','/^[1-9]+\d*$/','所属店铺不能为空',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		
	    	array('sort',array('0','99999'),'排序值非法',self::EXISTS_VALIDATE,'between'),
			array('status', array('-1','0','1') ,'店铺状态非法',self::EXISTS_VALIDATE,'in'),//-1-删除 0-禁用 1-正常
    );
	
    public function myAdd($data) {
    	unset($data['id']);
    	if (false === $this->create($data,self::MODEL_INSERT)) return false;
		//验证 parent_id合法性
		if ($this->parent_id >0) {
			$this_M = new Model('Category'); //避免create的数据混淆
			$parent = $this_M->find($this->parent_id);
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
    	if (false === $this->create($data,self::MODEL_UPDATE)) return false;
		//验证 parent_id合法性
		if ($this->parent_id > 0) {
			$model = new Model('Category'); //避免 model 混淆
			$parent = $model->find($this->parent_id);
			if (false === $parent || empty($parent)) {
				$this->error = '父级分类不存在';
				return false;
			}
			$orginfo = $model->find($id);
			if ($orginfo['store_id'] != $parent['store_id']) {
				$this->error = '父级分类不存在当前店铺';
				return false;
			}
		}
		return $this->where('`id`='.$id)->save();
    }
    
    /**
     * 用于ztree的json数据
     * @param array $map	查询条件
     */
    public function ztreeArr($map) {
    	$cate_tree = array();
    	$list = $this->where($map)->order('sort asc,id desc')->select();
    	foreach ($list as $row) {
    		$tmp = array(
    				'id' => $row['id'],
    				'pId' => $row['parent_id'],
    				'name' => $row['cate_name'],
    				'open' => true,
    				'sort' => $row['sort'],
    				'status' => $row['status']
    		);
    		$tmp['pname'] = $tmp['pId'] > 0? $this->where('id='.$tmp['pId'])->getField('cate_name') : '顶级分类';
    			
    		$cate_tree[] = $tmp;
    	}
    	return $cate_tree;
    }
}
