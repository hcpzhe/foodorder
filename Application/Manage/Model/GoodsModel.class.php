<?php
namespace Manage\Model;
use Think\Model;

/**
 * 商品模型
 */
class GoodsModel extends Model {
	public static $mystat = array('del'=>'-1','forbid'=>'0','allow'=>'1');
	public static $ablemap = array('status'=>'1'); //状态正常的查询条件
	
	protected $updateFields = array('cate_id','goods_name','image','price','sort','status'); //更新的时候不修改所属店铺ID,避免数据混乱
	
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
    		array('store_id','/^[1-9]+\d*$/','所属店铺不合法',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		array('store_id','/^[1-9]+\d*$/','所属店铺不合法',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
    		array('cate_id','number','所属分类不合法',self::EXISTS_VALIDATE,'regex',self::MODEL_BOTH),
	    	array('goods_name','require','商品名称不能为空',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
	    	array('goods_name','require','商品名称不能为空',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
	    	array('price','currency','商品价格不合法',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
	    	array('price','currency','商品价格不合法',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
			
	    	array('sort',array('0','99999'),'排序值非法',self::EXISTS_VALIDATE,'between'),
	    	
			array('status', array('-1','0','1') ,'店铺状态非法',self::EXISTS_VALIDATE,'in'),//-1-删除 0-禁用 1-正常
    );
	
    public function myAdd($data) {
    	//先create验证数据
    	if (false ===$this->create($data,self::MODEL_INSERT)) return false;
    	
    	//验证 所属店铺是否存在
    	$store_M = new Model('Store');
    	$store = $store_M->find($this->store_id);
    	if (false === $store || empty($store)) {
    		$this->error = '所属店铺不存在';
    		return false;
    	}
    	
    	//验证 所属分类是否存在
    	if ($this->cate_id > 0) {
	    	$cate_M = new Model('Category');
	    	$cate = $cate_M->find($this->cate_id);
	    	if (false === $cate || empty($cate)) {
	    		$this->error = '所属商品分类不存在';
	    		return false;
	    	}elseif ($cate['store_id'] != $this->store_id) {
	    		$this->error = '所属商品分类不属于当前店铺';
	    		return false;
	    	}
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
    	$myold = $this->find($id);
    	//先create验证数据
    	if (false ===$this->create($data,self::MODEL_UPDATE)) return false;
    	
    	if ($this->cate_id >0) {
	    	//验证 所属分类是否存在
	    	$cate_M = new Model('Category');
	    	$cate = $cate_M->find($this->cate_id);
	    	if (false === $cate || empty($cate)) {
	    		$this->error = '所属商品分类不存在';
	    		return false;
	    	}elseif ($cate['store_id'] != $myold['store_id']) {
	    		$this->error = '所属商品分类不属于当前店铺';
	    		return false;
	    	}
    	}
    	return $this->where('`id`='.$id)->save();
    }
}
