<?php
namespace Store\Controller;
use Common\Controller\StoreBaseController;

/**
 * 商品管理
 * @author RockSnap
 *
 */
class GoodsController extends StoreBaseController {
	
	/**
	 * 列表
	 */
	public function lists() {
		
	}
	/**
	 * 新建商品
	 */
	public function goodsAdd() {
	
	}
	/**
	 * 新建接口
	 */
	public function goodsInsert() {
		
	}
	/**
	 * 查看商品
	 */
	public function goodsRead() {
		//TODO 判断当前操作的商品, 是否所属于自己的店铺
		
	}
	/**
	 * 更新接口
	 */
	public function goodsUpdate() {
		//TODO 判断当前操作的商品, 是否所属于自己的店铺
		
	}

	/**
	 * 状态修改接口
	 * @param number $id	主键ID
	 * @param string $act	del,forbid,allow
	 */
	public function state($id,$act) {
		//TODO 判断当前操作的商品, 是否所属于自己的店铺
		$this->_state($id, $act, 'Goods');
	}
}