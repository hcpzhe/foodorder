<?php
namespace Store\Model;
use Think\Model;

class StoreModel extends Model {
	
	/**
	 * 登录用户
	 * @param  array $map 用户查询条件 account & password
	 * @return boolean      ture-登录成功，false-登录失败
	 */
	public function login($map) {
		if (empty($map)) {
			$this->error = '登录条件异常！';
			return false;
		}
		$password = $map['password'];
		unset($map['password']);
		
		$store = $this->where($map)->find();
		if(!$store || 1 != $store['status']) {
			$this->error = '用户不存在或已被禁用！';
			return false;
		}
		if($store['password'] != pwd_hash($password)) {
			$this->error = '密码错误！';
			return false;
		}
		
		/* 登录用户 */
		$this->_myLogin($store);
		
		// XXX 行为  用户登录
		tag('store_login');
		
		return true;
	}
	
	/**
	 * 注销当前用户
	 * @return void
	 */
	public function logout(){
		session('user_auth', null);
		session('user_auth_sign', null);
	}
	
	/**
	 * 自动登录用户
	 * @param  integer $store 用户信息数组
	 */
	private function _myLogin($store){
		/* 更新登录信息 */
		$data = array(
			'id'         => $store['id'],
			'logins'     => array('exp', '`logins`+1'),
			'last_login' => NOW_TIME,
			'last_ip'    => get_client_ip(1)
		);
		$this->save($data);
	
		/* 记录登录SESSION和COOKIES */
		$auth = array(
			'id'		=> $store['id'],
			'account'	=> $store['account'],
			'logins'	=> $store['logins'],
			'last_ip'	=> $store['last_ip']
		);
		
		session('user_auth', $auth);
		session('user_auth_sign', data_auth_sign($auth));
	
	}
	
	public function chgPwd($org,$new){
		$user = $this->find(SID);
		if($user['password'] != pwd_hash($org)) {
			$this->error = '原始密码错误！';
			return false;
		}
		return $this->where('id='.SID)->setField('password',pwd_hash($new));
	}
	
	public function myUpdate($data) {
		$data['id'] = SID;
		$model = new \Manage\Model\StoreModel();
		$model->field('store_name','owner_name','owner_card','address','tel',
			'min_send','store_logo','description','im_qq','im_ww',
			'is_close','close_reason');
		if (false === $model->myUpdate($data)) {
			$this->error = $model->getError();
			return false;
		}
		return true;
	}
}