<?php
namespace Manage\Model;
use Think\Model;

/**
 * 订单模型
 */
class OrderModel extends Model {
	public static $ablemap = array('status'=>'1'); //状态正常的查询条件
	public static $mystat = array('del'=>'-1','forbid'=>'0','allow'=>'1');
	
	public static $S_pay_status = array('0','1'); //支付状态 0-否 1-是
	public static $S_phone_status = array('0','1'); //手机验证状态 0-否 1-是
	public static $S_store_status = array('0','1'); //店铺接收订单状态 0-否 1-是
	public static $S_ship_status = array('0','1'); //配送状态 0-未开始 1-配送中
	public static $S_confirm = array('0','1'); //客户收货确认 0-否 1-是
	public static $S_unreceived = array('0','1'); //用户未收货申请1-是,0-否;必须在收货截止时间内才能申请
	
	protected $updateFields = array('buyer_name','address','phone','order_note','amount','payment_id',
			'update_time','pay_time','pay_status','phone_status','store_status','store_time','ship_status',
			'confirm','ship_time','confirm_time','confirm_expired','unreceived','finish_time','status');
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
    		array('store_id','/^[1-9]+\d*$/','所属店铺必须',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		array('member_id','40','购买用户编号非法',self::MUST_VALIDATE,'length',self::MODEL_INSERT),
    		array('order_sn','','订单编号已存在',self::EXISTS_VALIDATE,'unique'),
			
    		array('buyer_name','require','购买者姓名必须',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		array('buyer_name','require','购买者姓名必须',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
    		array('address','require','地址必须',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		array('address','require','地址必须',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
    		array('phone','require','电话必须',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		array('phone','require','电话必须',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
			
    		array('payment_id','/^[1-9]+\d*$/','支付方式必须',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		array('payment_id','/^[1-9]+\d*$/','支付方式必须',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
			
    		array('pay_time','number','支付时间非法'),
    		array('ship_time','number','发货时间非法'),
    		array('confirm_time','number','客户收货确认时间非法'),
    		array('confirm_expired','number','确认收货截止时间非法'),
    		array('finish_time','number','订单完成时间非法'),
	    	
			array('pay_status',array('0','1'),'支付状态非法',self::EXISTS_VALIDATE,'in'),
			array('phone_status',array('0','1'),'手机验证状态非法',self::EXISTS_VALIDATE,'in'),
			array('store_status',array('0','1'),'店铺接收订单状态非法',self::EXISTS_VALIDATE,'in'),
			array('ship_status',array('0','1'),'配送状态非法',self::EXISTS_VALIDATE,'in'),
			array('confirm',array('0','1'),'客户收货状态非法',self::EXISTS_VALIDATE,'in'),
			array('unreceived',array('0','1'),'用户未收货申请状态非法',self::EXISTS_VALIDATE,'in'),
			
			array('status', array('-1','0','1') ,'店铺状态非法',self::EXISTS_VALIDATE,'in'),//-1-删除 0-禁用 1-正常
    );
    protected $_auto = array(
			array('order_sn','_orderSn',self::MODEL_INSERT,'callback'),
        	array('add_time', NOW_TIME, self::MODEL_INSERT),//订单创建时间
        	array('update_time', NOW_TIME, self::MODEL_BOTH),//订单更新时间
        	array('pay_time', 0, self::MODEL_INSERT),//付款时间
        	array('unreceived', 0, self::MODEL_INSERT),//用户未收货申请  必须在收货截止时间内才能申请
    );

    /**
     * 生成唯一的order_sn,并验证数据库中不存在此order_sn
     * @return order_sn
     */
    protected function _orderSn() {
    	$map = array();
    	$map['order_sn'] = order_sn();
    	if($this->where($map)->find()) {
    		return $this->_orderSn();
    	}else {
    		return $map['order_sn'];
    	}
    }
    
    public function myAdd($data) {
    	/**
    	 * $data['goods'] = array([商品id]=>购买数量,...);
    	 */
    	$goods = $data['goods']; unset($data['goods']);
    	if (!is_array($goods) || empty($goods)) {
    		$this->error = '新增失败,订单内必须有商品';
    		return false;
    	}
    	// 计算合计总金额 amount
    	$goods_ids = array_keys($goods);
    	$goods_M = new Model('Goods');
    	$goods_list = $goods_M->where(array('goods_id'=>array('in',$goods_ids)))->getField('id,cate_id,goods_name,image,price');
    	$data['amount'] = 0; $newgoods = array();
    	foreach ($goods_list as $row) {
    		$tmparr = array();
    		$tmparr['id'] = $row['id'];
    		$tmparr['num'] = (int)$goods[$row['id']];
    		if ($tmparr['num'] > 0) {
				$tmparr['price'] = $row['price'];
				$tmparr['amount'] = $tmparr['price']*$tmparr['num'];
				$data['amount'] += $tmparr['amount'];
				$newgoods[] = $tmparr;
    		}
    	}
    	if (empty($newgoods)) {
    		$this->error = '新增失败,商品数量必须存在';
    		return false;
    	}
    	/********数据验证*******************************/
    	if (preg_match('/^\w+$/',$data['order_sn'])) {
    		//如果传入的参数有订单编号
    		$tmp_auto = $this->_auto;
    		unset($tmp_auto[0]);//除去自动填充订单编号
    		$this->auto($tmp_auto);
    	}
    	if (false === $this->create($data,self::MODEL_INSERT)) return false;
    	// store_id 合法性
    	$store_M = new Model('Store');
    	$store = $store_M->where("`id`='".$this->store_id."'")->find();
    	if (false === $store || empty($store)) {
    		$this->error = '新增失败,店铺不存在';
    		return false;
    	}
    	// member_id 合法性
    	$member_M = new Model('Member');
    	$member = $member_M->where("`id`='".$this->member_id."'")->find();
    	if (false === $member || empty($member)) {
    		$this->error = '新增失败,购买用户不存在';
    		return false;
    	}
    	// payment_id 合法性
    	$payment_M = new Model('Payment');
    	$payment = $payment_M->where("`id`='".$this->payment_id."'")->find();
    	if (false === $payment || empty($payment)) {
    		$this->error = '新增失败,支付方式不存在';
    		return false;
    	}
    	/*************************************************/
    	$this->startTrans();
    	$newid = $this->add();
    	if (!$newid) {
    		$this->error = '新增失败,数据库错误';
    		$this->rollback();
    		return false;
    	}
    	$og_M = new OrderGoodsModel(); $og_flag = 0;
    	foreach ($newgoods as $row) {
    		if (!empty($goods_list[$row['id']])) {
    			//商品必须存在
    			$order_goods_data = array(
	    				'order_id' => $newid,
	    				'goods_id' => $row['id'],
	    				'quantity' => $row['num'],
	    				'price' => $row['price'],
	    				'amount' => $row['amount']
    			);
    			if (false === $og_M->myAdd($order_goods_data)) continue;
    			$og_flag++;
    		}
    	}
    	if ($og_flag <= 0) {
    		$this->error = '新增失败,订单内没有合法的商品';
    		$this->rollback();
    		return false;
    	}
    	$this->commit();
    	return $newid;
    }
    
    // 订单中的商品不在此进行更新
    public function myUpdate($data) {
    	$order_id = (int)$data['id']; unset($data['id']);
    	// 检测订单是否存在
    	$order = $this->where('`id`='.$order_id)->find();
    	if (empty($order)) {
    		$this->error = '订单不存在';
    		return false;
    	}
    	if ($order['pay_status'] == '1') {
    		//已经付款的订单, 不允许修改支付方式
    		unset($data['payment_id']);
    	}
    	
    	if (false === $this->create($data,self::MODEL_UPDATE)) return false;
    	// payment_id 合法性
    	if ($this->payment_id >0) {
	    	$payment_M = new Model('Payment');
	    	$payment = $payment_M->where("`id`='".$this->payment_id."'")->find();
	    	if (false === $payment || empty($payment)) {
	    		$this->error = '支付方式不存在';
	    		return false;
	    	}
    	}
    	return $this->where('`id`='.$order_id)->save();
    }
    
    /**
     * 删除订单内的某个商品
     * @param int $order_id
     * @param int $goods_id
     * @return boolean
     */
    public function delGoods($order_id,$goods_id) {
    	// 检测订单是否存在
    	$order = $this->where('`id`='.$order_id)->find();
    	if (empty($order)) {
    		$this->error = '订单不存在';
    		return false;
    	}
    	$this->startTrans();
    	$og_M = new OrderGoodsModel();
    	$new_amount = $og_M->myDel($order_id, $goods_id);
    	if (false === $new_amount) {
    		$this->error = '订单的总价计算错误';
    		$this->rollback();
    		return false;
    	}
    	if (false === $this->where('id='.$order_id)->setField('amount',$new_amount)) {
    		$this->error = '订单的总价更新失败';
    		$this->rollback();
    		return false;
    	}
    	$this->commit();
    	return true;
    }
}
