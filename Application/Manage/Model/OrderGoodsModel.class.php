<?php
namespace Manage\Model;
use Think\Model;

/**
 * 订单商品模型
 */
class OrderGoodsModel extends Model {
	protected $updateFields = array('quantity','price','amount');
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
    		array('order_id','/^[1-9]+\d*$/','所属订单不合法',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		array('goods_id','/^[1-9]+\d*$/','订单商品不合法',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		
	    	array('quantity','/^[1-9]+\d*$/','商品数量不能为空',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
	    	array('quantity','/^[1-9]+\d*$/','商品数量不能为空',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
	    	array('price','currency','商品单价不合法',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
	    	array('price','currency','商品单价不合法',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
    		
	    	array('amount','currency','商品价格不合法'),
    );
    
	/**
	 * 不验证 goods_id 和 order_id 的合法性
	 */
    public function myAdd($data) {
    	//先create验证数据
    	if (false ===$this->create($data,self::MODEL_INSERT)) return false;
    	
    	if (!isset($this->amount) || !is_null($this->amount)) {
    		//如果没有给出指定的商品总价, 那么进行自动计算
    		$this->amount = $this->price * $this->quantity;
    	}
    	/*
    	//验证 所属订单是否存在
    	$order_M = new Model('Order');
    	$order = $order_M->find($this->order_id);
    	if (false === $order || empty($order)) {
    		$this->error = '所属订单不存在';
    		return false;
    	}
    	//验证 所属订单是否存在
    	$goods_M = new Model('Goods');
    	$goods = $goods_M->find($this->goods_id);
    	if (false === $goods || empty($goods)) {
    		$this->error = '商品不存在';
    		return false;
    	}
    	*/
    	return $this->add();
    }

    /**
     * 不验证 goods_id 和 order_id 的合法性
     */
    public function myUpdate($data) {
    	$map['order_id'] = (int)$data['order_id'];
    	if ($map['order_id']<=0) {
    		$this->error = '请先选择要更新的订单';
    		return false;
    	}
    	unset($data['order_id']);
    	$map['goods_id'] = (int)$data['goods_id'];
    	if ($map['goods_id']<=0) {
    		$this->error = '请先选择要更新的订单商品';
    		return false;
    	}
    	unset($data['goods_id']);
    	
    	//先create验证数据
    	if (false ===$this->create($data,self::MODEL_UPDATE)) return false;
    	
    	$this->startTrans();
    	if (!isset($this->amount) || !is_null($this->amount)) {
    		// 如果没有给出指定的商品总价, 那么进行自动计算
    		if (false === $this->where($map)->save()) {
    			$this->error = '数据库错误,更新失败';
    			return false;
    		}
    		$info = $this->where($map)->find();
    		$flag = $this->where($map)->setField('amount',$info['quantity']*$info['price']);
    	}else {
    		$flag = $this->where($map)->save();
    	}
    	if (false === $flag) {
    		$this->rollback();
    		return false;
    	}
    	//更新订单总价
    	$fields = array('order_id','sum(amount)'=>'amount');
    	$info = $this->where('`order_id`='.$map['order_id'])->field($fields)->group('goods_id')->find();
    	$order_M = new Model('Order');
    	if (false === $order_M->where('id='.$map['order_id'])->setField('amount',$info['amount'])) {
    		$this->rollback();
    		$this->error = '订单总价更新失败';
    		return false;
    	}
    	$this->commit();
    	return true;
    }
    
    public function myDel($order_id,$goods_id) {
    	$map = array('order_id'=>$order_id,'goods_id'=>$goods_id);
    	if (false === $this->where($map)->delete()) {
    		$this->error = '数据库错误';
    		return false;
    	}
    	//删除成功, 返回当前订单的总价
    	$fields = array('order_id','sum(amount)'=>'amount');
    	$info = $this->where('`order_id`='.$order_id)->field($fields)->group('goods_id')->find();
    	return $info['amount'];
    }
}
