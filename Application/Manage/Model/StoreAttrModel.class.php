<?php
namespace Manage\Model;
use Think\Model;

/**
 * 店铺 属性值 关系
 */
class StoreAttrModel extends Model {
	
	/**
	 * TODO 更新指定店铺筛选属性
	 * @param int $store_id 店铺ID
	 * @param array $attr_vals 属性值数组
	 * @return boolean
	 */
	public function update($store_id, $attr_vals) {
		//先删除该店铺原有的属性, 添加上新属性
		$this->startTrans();
		
		$this->rollback();
		return false;
	}
	
	/**
	 * 获取完全满足属性的店铺ID数组  用于筛选列表
	 * @param array $attr_vals 属性值数组
	 * @return store_id array
	 */
	public function getStoreByAttr($attr_vals) {
		$ids = null;
		foreach ($attr_vals as $val) {
			$tmpval = (int)$val;
			if ($tmpval <= 0) {
				continue;
			}
			$tmp = $this->where('`attr_val_id`='.$tmpval)->getField('store_id',true); // 获取店铺id数组
			if (empty($tmp) || is_null($tmp)) {
				return array();
			}else {
				$ids = is_null($ids) ? $tmp : array_intersect($ids, $tmp);//取交集
			}
		}
		return $ids;
	}
}
