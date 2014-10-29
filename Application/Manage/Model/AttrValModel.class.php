<?php
namespace Manage\Model;
use Think\Model;

/**
 * 筛选属性值
 */
class AttrValModel extends Model {
	public static $mystat = array('del'=>'-1','forbid'=>'0','allow'=>'1');
	public static $ablemap = array('status'=>'1'); //状态正常的查询条件
	
	protected $updateFields = array('attr_val','sort','status'); //更新的时候不修改所属属性ID,避免数据混乱
	
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
			array('attr_id','/^[1-9]+\d*$/','所属属性不合法',self::MUST_VALIDATE),
			array('attr_val','require','属性值不能为空',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
			array('attr_val','require','属性值不能为空',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
    		array('sort',array('0','99999'),'排序值非法',self::EXISTS_VALIDATE,'between'),
			array('status',array('-1','0','1'),'属性状态非法',self::EXISTS_VALIDATE,'in'),//-1-删除 0-禁用 1-正常
	);
	
	/**
	 * 添加新的属性值 替换add()方法
	 * @param array $data 新增的数据库字段
	 * @return $this->add()
	 */
	public function myAdd($data) {
		//先create验证数据
		if (false ===$this->create($data,self::MODEL_INSERT)) return false;
		
		//验证 所属属性是否存在
		$attr_M = new Model('Attr');
		$attr = $attr_M->find($this->attr_id);
		if (false === $attr || empty($attr)) {
			$this->error = '所属属性不存在';
			return false;
		}
		return $this->add();
	}
	
	/**
	 * 所有的 属性-属性值 hashList
	 * return array(
	 *  attr_id=> array(
	 *    attr_name => attr_name-data,
	 *    vals => array(
	 *      key => data
	 *    )
	 *  ),...
	 * )
	 */
	public function hashList() {
		$return = array();
		$attr_M = new AttrModel();
		$attrs = $attr_M->where(AttrModel::$ablemap)->order('sort asc,id desc')->select();
		foreach ($attrs as $atrow) {
			$return[$atrow['id']] = $atrow;
			$tmpmap = array('attr_id'=>$atrow['id']);
			$tmpmap = array_merge(self::$ablemap,$tmpmap);
			$return[$atrow['id']]['vals'] = $this->where($tmpmap)->order('sort asc,id desc')->select();
		}
		return $return;
	}
}
