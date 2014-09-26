<?php
namespace Manage\Model;
use Think\Model;

/**
 * 筛选属性
 */
class AttrModel extends Model {
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
			array('attr_name','require','属性名必须',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
			array('attr_name','require','属性名必须',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
			
    		array('sort',array('0','99999'),'排序值非法',self::EXISTS_VALIDATE,'between'),
			array('status',array('-1','0','1'),'属性状态非法',self::EXISTS_VALIDATE,'in'),//-1-删除 0-禁用 1-正常
	);

	/**
	 * 查找 状态正常 的属性
	 * @param PKID或者array $options
	 * @return array
	 */
	public function findAble($options=array()) {
		$where = array();
		if (is_numeric($options) || is_string($options)) {
			$where[$this->getPk()]  =   $options;
		}else {
			$where = $options;
		}
		
		if ($where['status']===null||$where['status']===''||!in_array($where['status'],self::$status)) $where['status'] = '1';
		return $this->where($where)->find();
	}
	
}
