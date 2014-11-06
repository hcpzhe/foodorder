<?php
namespace Manage\Model;
use Think\Model;

class UserModel extends Model {
	
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
		
		$user = $this->where($map)->find();
		if(!$user || 1 != $user['status']) {
			$this->error = '用户不存在或已被禁用！';
			return false;
		}
		if($user['password'] != pwd_hash($password)) {
			$this->error = '密码错误！';
			return false;
		}
		
		/* 登录用户 */
		$this->_myLogin($user);
		
		// XXX 行为  用户登录
		tag('user_login');
		
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
	 * @param  integer $user 用户信息数组
	 */
	private function _myLogin($user){
		/* 更新登录信息 */
		$data = array(
			'id'         => $user['id'],
			'logins'     => array('exp', '`logins`+1'),
			'last_login' => NOW_TIME,
			'last_ip'    => get_client_ip(1)
		);
		$this->save($data);
	
		/* 记录登录SESSION和COOKIES */
		$auth = array(
			'id'		=> $user['id'],
			'account'	=> $user['account'],
			'logins'	=> $user['logins'],
			'last_ip'	=> $user['last_ip']
		);
		
		session('user_auth', $auth);
		session('user_auth_sign', data_auth_sign($auth));
	
	}
	
	public function chgPwd($org,$new){
		$user = $this->find(UID);
		if($user['password'] != pwd_hash($org)) {
			$this->error = '原始密码错误！';
			return false;
		}
		return $this->where('id='.UID)->setField('password',pwd_hash($new));
	}
}