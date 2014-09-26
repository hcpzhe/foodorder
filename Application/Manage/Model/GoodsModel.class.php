<?php
namespace Manage\Model;
use Think\Model;

/**
 * 商品模型
 */
class GoodsModel extends Model {
	public static $status = array('del'=>'-1','forbid'=>'0','allow'=>'1');
	public static $ablemap = array('status'=>'1'); //状态正常的查询条件
	
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
    		array('cate_id','number','所属分类不合法'),
	    	array('goods_name','require','商品名称不能为空'),
	    	array('price','currency','商品价格不合法'),
			
	    	array('sort',array('0','99999'),'排序值非法',self::EXISTS_VALIDATE,'between'),
	    	
			array('status', array('-1','0','1') ,'店铺状态非法',self::EXISTS_VALIDATE,'in'),//-1-删除 0-禁用 1-正常
    );
	
    public function myAdd($data) {
    	//先create验证数据
    	$data['cate_id'] = (int)$data['cate_id'];
    	if (false ===$this->create($data,self::MODEL_INSERT)) return false;
    
    	//验证 所属分类是否存在
    	$cate_M = new Model('Category');
    	$cate = $cate_M->find($this->cate_id);
    	if (false === $cate || empty($cate)) {
    		$this->error = '所属商品分类不存在';
    		return false;
    	}
    	return $this->add();
    }
    
    public function myUpdate($data) {
    	$id = (int)$data['id'];
    	if ($id<=0) {
    		$this->error = '请先选择要更新的商品';
    		return false;
    	}
    	unset($data['id']);
    	//先create验证数据
    	if (isset($data['cate_id'])) $data['cate_id'] = (int)$data['cate_id'];
    	if (false ===$this->create($data,self::MODEL_UPDATE)) return false;
    	
    	if (isset($this->cate_id)) {
	    	//验证 所属分类是否存在
	    	$cate_M = new Model('Category');
	    	$cate = $cate_M->find($this->cate_id);
	    	if (false === $cate || empty($cate)) {
	    		$this->error = '所属商品分类不存在';
	    		return false;
	    	}
    	}
    	return $this->where('`id`='.$id)->save();
    }
}
