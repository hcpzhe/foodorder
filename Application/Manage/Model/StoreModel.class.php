<?php
namespace Manage\Model;
use Think\Model;
use Think\Upload;

/**
 * 店铺模型
 */
class StoreModel extends Model {
	public static $ablemap = array('status'=>'1'); //状态正常的查询条件
	public static $mystat = array('del'=>'-1','forbid'=>'0','allow'=>'1');
	public static $recom = array('0','1');
	public static $close = array('0','1');

	protected $updateFields = array('password','store_name','owner_name','owner_card','address','tel',
			'balance','rating','min_send','is_recom','store_logo','description','im_qq','im_ww',
			'last_login','last_ip','logins','sort','end_time','is_close','close_reason','status');
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
    		//  \w等价于[A-Za-z0-9_]
			array('account','/^\w{4,16}$/i','帐号格式错误，字母或数字 4-16位',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
			array('account','','帐号已经存在',self::EXISTS_VALIDATE,'unique'),
			
    		array('password','require','登录密码必须',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		array('repassword','require','确认登录密码必须',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		array('repassword','password','登录确认密码不一致',self::MUST_VALIDATE,'confirm',self::MODEL_INSERT),
    		
    		array('password','require','登录密码必须',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
    		array('repassword','require','确认登录密码必须',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
    		array('repassword','password','登录确认密码不一致',self::EXISTS_VALIDATE,'confirm',self::MODEL_UPDATE),
			
    		array('store_name','require','店铺名必须',self::MUST_VALIDATE,'regex',self::MODEL_INSERT),
    		array('store_name','require','店铺名必须',self::EXISTS_VALIDATE,'regex',self::MODEL_UPDATE),
    		
	    	array('balance','currency','店铺余额非法'),
	    	array('rating',array('1','5'),'评价分数非法',self::EXISTS_VALIDATE,'between'),
			array('min_send',array('0','99999'),'最低起送价非法',self::EXISTS_VALIDATE,'between'),
			
    		array('last_login','number','上次登录时间非法'),
    		array('logins','number','登录次数非法'),
    		array('end_time','number','到期时间非法'),
    		
	    	array('sort',array('0','99999'),'排序值非法',self::EXISTS_VALIDATE,'between'),
	    	
			array('is_recom',array('0','1'),'推荐状态非法',self::EXISTS_VALIDATE,'in'),//推荐 1-是  0-否
			array('is_close',array('0','1'),'营业状态非法',self::EXISTS_VALIDATE,'in'),//暂停营业 0-否 1-是
			array('status', array('-1','0','1') ,'店铺状态非法',self::EXISTS_VALIDATE,'in'),//-1-删除 0-禁用 1-正常
    );
	
    protected $_auto = array(
        	array('add_time', NOW_TIME, self::MODEL_INSERT),//入驻时间
    );
    
    public function myAdd($data) {
    	if (false === $this->create($data,self::MODEL_INSERT)) return false;
    	//处理密码
    	$this->password = pwd_hash($data['password']);
    	//处理店铺logo
    	if (!empty($data['store_logo'])) {
    		$setting = C('PICTURE_UPLOAD');
			$Upload = new Upload($setting);
			$store_logo = $Upload->uploadOne($data['store_logo']);
			if (!$store_logo) {
	            $this->error = $Upload->getError();
	            return false;
			}
			$store_logo['path'] = substr($setting['rootPath'], 1).$store_logo['savepath'].$store_logo['savename']; //在模板里的url路径
			$this->store_logo = $store_logo['path'];
    	}else {
    		unset($this->store_logo);
    	}
    	return $this->add();
    }
    
    public function myUpdate($data) {
    	$id = (int)$data['id']; unset($data['id']);
    	if (empty($data['password'])) unset($data['password']);
    	else $data['password'] = pwd_hash($data['password']);
    	if (false === $this->create($data,self::MODEL_UPDATE)) return false;
    	
    	//上传店铺logo
    	if (!empty($data['store_logo'])) {
    		$setting = C('PICTURE_UPLOAD');
			$Upload = new Upload($setting);
			$store_logo = $Upload->uploadOne($data['store_logo']);
			if (!$store_logo) {
	            $this->error = $Upload->getError();
	            return false;
			}
			$store_logo['path'] = substr($setting['rootPath'], 1).$store_logo['savepath'].$store_logo['savename']; //在模板里的url路径
			$this->store_logo = $store_logo['path'];
    	}else {
    		unset($this->store_logo);
    	}
    	
    	return $this->where('`id`='.$id)->save();
    }
}