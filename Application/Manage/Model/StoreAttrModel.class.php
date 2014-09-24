<?php
namespace Manage\Model;
use Think\Model;

/**
 * 店铺 属性值 关系
 */
class StoreAttrModel extends Model {
	
	/**
	 * 更新指定店铺筛选属性
	 * @param int $store_id 店铺ID
	 * @param array $attr_vals 属性值数组
	 * @return boolean
	 */
	public function update($store_id, $attr_vals) {
		//验证store_id合法性
		$store_M = new Model('Store');
		$store = $store_M->find($store_id);
		if (false === $store || empty($store)) {
			$this->error = '所属店铺不存在';
			return false;
		}
		//删除该店铺原有的属性, 再添加上新属性
		$this->startTrans();
		$this->where('`store_id`='.$store_id)->delete();
		//验证attr_val_id合法性 , 不合法的忽略掉
		$AttrVal_M = new Model('AttrVal');
		$attr_vals = (array)$attr_vals;
		$tmpdata = array();
		foreach ($attr_vals as $valid) {
			$tmpid = (int)$valid;
			if ($tmpid>0) {
				$AttrVal = $AttrVal_M->find($tmpid);
				if (false === $AttrVal || empty($AttrVal)) continue; //不合法的忽略掉
				$tmpdata = array('store_id'=>$store_id,'attr_val_id'=>$tmpid);
				if (false === $this->data($tmpdata)->add()) {
					$this->error = '存在非法数据';
					$this->rollback();
					return false;
				}
			}
		}
		if (empty($tmpdata)) {
			$this->error = '属性值不合法';
			$this->rollback();
			return false;
		}
		$this->commit();
		return true;
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
