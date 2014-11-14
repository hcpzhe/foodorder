<?php
namespace Store\Model;
use Think\Model;

/**
 * 订单模型
 */
class OrderModel extends \Manage\Model\OrderModel {
	/**
	 * 判断订单, 是否所属于自己的店铺
	 * @param int $id		表主键id
	 * @return boolean
	 */
	public function checkAuth($id) {
		$store_id = $this->where("`id`=$id")->getField('store_id');
		return ($store_id == STID);
	}
	
	
}
