<?php
namespace Manage\Model;
use Think\Model;

/**
 * 会员模型
 * insert,update 直接create操作数据即可
 */
class MemberModel extends Model {
	public static $status = array('del'=>'-1','forbid'=>'0','allow'=>'1');
	public static $ablemap = array('status'=>'1'); //状态正常的查询条件
	
	protected $updateFields = array('password','status'); //create时可更新字段,避免数据混乱
	
	/**
	 * MUST_VALIDATE	1	必须验证 不管表单是否有设置该字段
	 * VALUE_VALIDATE	2	值不为空的时候才验证
	 * EXISTS_VALIDATE	0	表单存在该字段就验证   (默认)
	 * 
	 * self::MODEL_INSERT或者1	新增数据时候验证
	 * self::MODEL_UPDATE或者2	编辑数据时候验证
	 * self::MODEL_BOTH或者3		全部情况下验证（默认）
	 */
    protected $_validate = array(
			array('account','/^\w{4,16}$/i','会员帐号格式错误，字母或数字 4-16位'),//  \w等价于[A-Za-z0-9_]
			array('account','require','会员帐号不能为空'),
			array('account','','会员帐号已经存在',self::EXISTS_VALIDATE,'unique'),

    		array('password','require','登录密码必须'),
    		array('repassword','require','确认登录密码必须'),
    		array('repassword','password','登录确认密码不一致',self::EXISTS_VALIDATE,'confirm'),

    		array('last_login','number','上次登录时间格式非法'),
    		array('logins','number','登录次数格式非法'),
    		
			array('status', array('-1','0','1') ,'会员状态非法',self::EXISTS_VALIDATE,'in'),//-1-删除 0-禁用 1-正常
    );
	protected $_auto = array(
			array('id','_memberId',self::MODEL_INSERT,'callback'),
			array('password','pwd_hash',self::MODEL_INSERT,'function'),
			array('reg_time','time',self::MODEL_INSERT,'function'),//注册时间
	);
	
	/**
	 * 生成唯一的member_id,并验证数据库中不存在此member_id
	 * @return member_id
	 */
	private function _memberId() {
		$map = array();
		$map['id'] = member_id();
		if($this->where($map)->find()) {
			return $this->_memberId();
		}else {
			return $map['id'];
		}
	}
	
}