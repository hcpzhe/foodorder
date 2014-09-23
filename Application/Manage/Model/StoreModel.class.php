<?php
namespace Manage\Model;
use Think\Model;

/**
 * 店铺模型
 */
class StoreModel extends Model {

	public static $status = array('-1','0','1');
	public static $recom = array('0','1');
	public static $close = array('0','1');
	
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
			array('account','/^\w{4,16}$/i','帐号格式错误，字母或数字 4-16位'),//  \w等价于[A-Za-z0-9_]
			array('account','','帐号已经存在',self::EXISTS_VALIDATE,'unique'),
			
    		array('password','require','登录密码必须'),
    		array('repassword','require','确认登录密码必须'),
    		array('repassword','password','登录确认密码不一致',self::EXISTS_VALIDATE,'confirm'),
			
	    	array('store_name','require','店铺名必须'),
			
	    	array('rating',array('1','5'),'评价分数非法',self::EXISTS_VALIDATE,'between'),
			array('min_send',array('0','99999'),'最低起送价非法',self::EXISTS_VALIDATE,'between'),
	    	
	    	array('sort',array('0','99999'),'排序值非法',self::EXISTS_VALIDATE,'between'),
	    	
			array('is_recom',array('0','1'),'推荐状态非法',self::EXISTS_VALIDATE,'in'),//推荐 1-是  0-否
			array('is_close',array('0','1'),'营业状态非法',self::EXISTS_VALIDATE,'in'),//暂停营业 0-否 1-是
			array('status', array('-1','0','1') ,'店铺状态非法',self::EXISTS_VALIDATE,'in'),//-1-删除 0-禁用 1-正常
    );
	
    protected $_auto = array(
        	array('add_time', NOW_TIME, self::MODEL_INSERT),//入驻时间
    );
}
