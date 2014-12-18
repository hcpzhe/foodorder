<?php
namespace Home\Model;
use Think\Model;

class MemberModel extends \Manage\Model\MemberModel {
	
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
		
		$member = $this->where($map)->find();
		if(!$member || 1 != $member['status']) {
			$this->error = '用户不存在或已被禁用！';
			return false;
		}
		if($member['password'] != pwd_hash($password)) {
			$this->error = '密码错误！';
			return false;
		}
		
		/* 登录用户 */
		$this->_myLogin($member);
		
		// XXX 行为  用户登录
		tag('member_login');
		
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
	 * @param  integer $member 用户信息数组
	 */
	private function _myLogin($member){
		/* 更新登录信息 */
		$data = array(
			'id'         => $member['id'],
			'logins'     => array('exp', '`logins`+1'),
			'last_login' => NOW_TIME,
			'last_ip'    => get_client_ip(1)
		);
		$this->save($data);
	
		/* 记录登录SESSION和COOKIES */
		$auth = array(
			'id'		=> $member['id'],
			'account'	=> $member['account'],
			'logins'	=> $member['logins'],
			'last_ip'	=> $member['last_ip']
		);
		
		session('user_auth', $auth);
		session('user_auth_sign', data_auth_sign($auth));
	
	}
	
	/**
	 * 生成新用户,自动登录
	 */
	public function bulidNew($mid=null) {
		if (MID) {
			//当前session已经创建过用户了
			return MID;
		}else {
			if (strlen($mid) == 40) {
				$member = $this->where("`id`='$mid'")->find();
				if (empty($member)) return $this->bulidNew();
				$data = array(
					'id'         => $member['id'],
					'logins'     => '1',
					'reg_time' => NOW_TIME,
					'last_ip'    => get_client_ip(1)
				);
			}else {
				$data = array(
						'id'         => $mid,
						'logins'     => '1',
						'reg_time' => NOW_TIME,
						'last_ip'    => get_client_ip(1)
				);
				if (false===$this->add($data)) return false;
			}
			
			//记录登录信息
			session('user_auth', $data);
			session('user_auth_sign', data_auth_sign($data));
			
			return $data['id'];
		}
	}
}