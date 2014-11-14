<?php
namespace Store\Model;
use Think\Model;

/**
 * 商品模型
 */
class GoodsModel extends \Manage\Model\GoodsModel {
	
	/**
	 * 判断商品, 是否所属于自己的店铺
	 * @param int $goods_id		商品主键id
	 * @return boolean
	 */
	public function checkAuth($goods_id) {
		$store_id = $this->where("`id`=$goods_id")->getField('store_id');
		return ($store_id == STID);
	}
}
