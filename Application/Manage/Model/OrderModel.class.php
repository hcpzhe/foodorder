<?php
namespace Manage\Model;
use Think\Model;

/**
 * 订单模型
 */
class OrderModel extends Model {
	public static $ablemap = array('status'=>'1'); //状态正常的查询条件
	public static $status = array('del'=>'-1','forbid'=>'0','allow'=>'1');
	
	public static $pay_status = array('0','1'); //支付状态 0-否 1-是
	public static $phone_status = array('0','1'); //手机验证状态 0-否 1-是
	public static $store_status = array('0','1'); //店铺接收订单状态 0-否 1-是
	public static $ship_status = array('0','1'); //配送状态 0-未开始 1-配送中
	public static $confirm = array('0','1'); //客户收货确认 0-否 1-是
	public static $unreceived = array('0','1'); //用户未收货申请1-是,0-否;必须在收货截止时间内才能申请
	
	protected $updateFields = array('buyer_name','address','phone','payment_id');//TODO
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
    		array('sotre_id','/^[1-9]+\d*$/','所属店铺必须',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		array('member_id','40','购买用户编号非法',self::MUST_VALIDATE,'length',self::MODEL_INSERT),
			
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
        	array('update_time', NOW_TIME, self::MODEL_UPDATE),//订单更新时间
        	array('pay_time', 0, self::MODEL_INSERT),//付款时间
    );

    /**
     * 生成唯一的order_sn,并验证数据库中不存在此order_sn
     * @return order_sn
     */
    private function _orderSn() {
    	$map = array();
    	$map['order_sn'] = order_sn();
    	if($this->where($map)->find()) {
    		return $this->_orderSn();
    	}else {
    		return $map['order_sn'];
    	}
    	$this->create();
    }
    
    public function myAdd($data) {
    	//TODO
    }
    
    public function myUpdate($data) {
    	//TODO
    }
}
